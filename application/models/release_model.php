<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Release_Model extends CI_Model {

	private $_release_head_id = 0;
	private $_current_branch_id = 0;
	private $_current_user = 0;
	private $_current_date = '';
	private $_error_message = array('UNABLE_TO_INSERT' => 'Unable to insert release detail!',
									'UNABLE_TO_UPDATE' => 'Unable to update release detail!',
									'UNABLE_TO_UPDATE_HEAD' => 'Unable to update release head!',
									'UNABLE_TO_SELECT_HEAD' => 'Unable to get release head details!',
									'UNABLE_TO_SELECT_DETAILS' => 'Unable to get release details!',
									'UNABLE_TO_DELETE' => 'Unable to delete release detail!',
									'UNABLE_TO_DELETE_HEAD' => 'Unable to delete release head!',
									'HAS_RELEASED' => 'Released entry can only be deleted if status is no received!',
									'NOT_OWN_BRANCH' => 'Cannot delete warehouse release entry of other branches!');

	public function __construct() 
	{
		$this->load->library('encrypt');
		$this->load->file(CONSTANTS.'release_const.php');
		$this->load->library('sql');
		$this->load->helper('cookie');

		$this->_release_head_id 	= $this->encrypt->decode($this->uri->segment(3));
		$this->_current_branch_id 	= $this->encrypt->decode(get_cookie('branch'));
		$this->_current_user 		= $this->encrypt->decode(get_cookie('temp'));
		$this->_current_date 		= date("Y-m-d h:i:s");
		
		parent::__construct();
	}

	public function search_release_list($param)
	{
		extract($param);

		$conditions		= "";
		$order_field 	= "";
		$having 		= "";

		$response 	= array();
		$query_data = array();

		$response['rowcnt'] = 0;

		if (!empty($date_from))
		{
			$conditions .= " AND RH.`entry_date` >= ?";
			array_push($query_data,$date_from.' 00:00:00');
		}

		if (!empty($date_to))
		{
			$conditions .= " AND RH.`entry_date` <= ?";
			array_push($query_data,$date_to.' 23:59:59');
		}

		if ($branch != 0) 
		{
			$conditions .= " AND RH.`branch_id` = ?";
			array_push($query_data,$branch);
		}

		if (!empty($search_string)) 
		{
			$conditions .= " AND CONCAT('WR',RH.`reference_number`,' ',RH.`memo`,' ',RH.`customer`) LIKE ?";
			array_push($query_data,'%'.$search_string.'%');
		}

		if ($status != RELEASE_CONST::ALL_OPTION) 
		{
			switch ($status) 
			{
				case RELEASE_CONST::INCOMPLETE:
					$having = "HAVING remaining_qty < total_qty AND remaining_qty > 0";
					break;
				
				case RELEASE_CONST::COMPLETE:
					$having = "HAVING remaining_qty = 0";
					break;

				case RELEASE_CONST::NO_RECEIVED:
					$having = "HAVING remaining_qty = total_qty";
					break;

				case RELEASE_CONST::EXCESS:
					$having = "HAVING remaining_qty < 0";
					break;
			}
		}

		switch ($order_by) 
		{
			case RELEASE_CONST::ORDER_BY_REFERENCE:
				$order_field = "RH.`reference_number`";
				break;
			
			case RELEASE_CONST::ORDER_BY_LOCATION:
				$order_field = "B.`name`";
				break;

			case RELEASE_CONST::ORDER_BY_CUSTOMER:
				$order_field = "RH.`customer`";
				break;
		}


		$query = "SELECT RH.`id`, COALESCE(B.`name`,'') AS 'location', CONCAT('WR',RH.`reference_number`) AS 'reference_number',
					COALESCE(DATE(`entry_date`),'') AS 'entry_date',RH. `is_used`, IF(RH.`is_used` = 0, 'Unused', RH.`memo`) AS 'memo', RH.`customer`,
					COALESCE(SUM(RD.`quantity`),'') AS 'total_qty', SUM(RD.`quantity` - RD.`qty_released`) AS 'remaining_qty',
					COALESCE(CASE
						WHEN SUM(RD.`qty_released`) = SUM(RD.`quantity`) THEN 'Complete'
						WHEN SUM(RD.`qty_released` ) > SUM(RD.`quantity`) THEN 'Excess'
						WHEN SUM(RD.`qty_released`) > 0 THEN 'Incomplete'
						WHEN SUM(RD.`qty_released`) = 0 THEN 'No Received'
					END,'') AS 'status'
					FROM release_head AS RH
					LEFT JOIN release_detail AS RD ON RD.`headid` = RH.`id`
					LEFT JOIN branch AS B ON B.`id` = RH.`branch_id` AND B.`is_show` = ".RELEASE_CONST::ACTIVE."
					WHERE RH.`is_show` = ".RELEASE_CONST::ACTIVE." $conditions
					GROUP BY RH.`id`
					$having
					ORDER BY $order_field $order_type";
					
		$result = $this->db->query($query,$query_data);
		
		if ($result->num_rows() > 0) 
		{
			$i = 0;
			$response['rowcnt'] = $result->num_rows();

			foreach ($result->result() as $row) 
			{
				$response['data'][$i][] = array($this->encrypt->encode($row->id));
				$response['data'][$i][] = array($i+1);
				$response['data'][$i][] = array($row->location);
				$response['data'][$i][] = array($row->reference_number);
				$response['data'][$i][] = array($row->entry_date);
				$response['data'][$i][] = array($row->customer);
				$response['data'][$i][] = array($row->memo);
				$response['data'][$i][] = array($row->status);
				$response['data'][$i][] = array('');
				$i++;
			}
		}

		return $response;
	}

	public function delete_release_head($param)
	{
		extract($param);

		$release_head_id = $this->encrypt->decode($head_id);

		$response = array();
		$response['error'] = '';

		$query 	= "SELECT SUM(`qty_released`) AS 'total_released' FROM release_detail WHERE `headid` = ?";
		$result = $this->db->query($query,$release_head_id);
		$row 	= $result->row();

		if ($row->total_released > 0) {
			throw new Exception($this->_error_message['HAS_RELEASED']);
		}

		$result->free_result();

		$query 	= "SELECT `branch_id` FROM release_head WHERE id = ?";
		$result = $this->db->query($query,$release_head_id);
		$row 	= $result->row();

		if ($row->branch_id != $this->_current_branch_id) {
			throw new Exception($this->_error_message['NOT_OWN_BRANCH']);
		}

		$result->free_result();

		$query_data = array($this->_current_date,$this->_current_user,$release_head_id);
		$query 	= "UPDATE `release_head` 
					SET 
					`is_show` = ".RELEASE_CONST::DELETED.",
					`last_modified_date` = ?,
					`last_modified_by` = ?
					WHERE `id` = ?";

		$result = $this->sql->execute_query($query,$query_data);

		if ($result['error'] != '') 
			throw new Exception($this->_error_message['UNABLE_TO_DELETE_HEAD']);
		
		return $response;
	}

	public function get_release_details()
	{
		$response = array();
		$response['error'] 	= '';
		$response['detail_error'] 	= ''; 

		$query_head = "SELECT CONCAT('WR',`reference_number`) AS 'reference_number', 
					COALESCE(DATE(`entry_date`),'') AS 'entry_date', `memo`, `customer`, `is_used`,
					`branch_id`
					FROM `release_head`
					WHERE `is_show` = ".RELEASE_CONST::ACTIVE." AND `id` = ?";

		$result_head = $this->db->query($query_head,$this->_release_head_id);

		if ($result_head->num_rows() != 1) 
			throw new Exception($this->_error_message['UNABLE_TO_SELECT_HEAD']);
		else
		{
			$row = $result_head->row();

			$response['reference_number'] 	= $row->reference_number;
			$response['entry_date'] 		= $row->entry_date;
			$response['memo'] 				= $row->memo;
			$response['customer_name'] 		= $row->customer;
			$response['is_saved'] 			= $row->is_used;
			$response['is_editable'] 		= $row->branch_id == $this->_current_branch_id ? TRUE : FALSE;
		}

		$query_detail = "SELECT RD.`id`, RD.`product_id`, COALESCE(P.`material_code`,'') AS 'material_code', 
						COALESCE(P.`description`,'') AS 'product', RD.`quantity`, RD.`memo`, RD.`qty_released`, RD.`description`, P.`type`
					FROM `release_detail` AS RD
					LEFT JOIN `release_head` AS RH ON RD.`headid` = RH.`id` AND RH.`is_show` = ".RELEASE_CONST::ACTIVE."
					LEFT JOIN `product` AS P ON P.`id` = RD.`product_id` AND P.`is_show` = ".RELEASE_CONST::ACTIVE."
					WHERE RD.`headid` = ?";

		$result_detail = $this->db->query($query_detail,$this->_release_head_id);

		if ($result_detail->num_rows() == 0) 
			$response['detail_error'] = 'No release details found!';
		else
		{
			$i = 0;
			foreach ($result_detail->result() as $row) 
			{
				$break_line = empty($row->description) ? '' : '<br/>';
				$response['detail'][$i][] = array($this->encrypt->encode($row->id));
				$response['detail'][$i][] = array($i+1);
				$response['detail'][$i][] = array($row->product, $row->product_id, $row->type, $break_line, $row->description);
				$response['detail'][$i][] = array($row->material_code);
				$response['detail'][$i][] = array($row->quantity);
				$response['detail'][$i][] = array($row->memo);
				$response['detail'][$i][] = array($row->qty_released);
				$response['detail'][$i][] = array('');
				$response['detail'][$i][] = array('');
				$i++;
			}
		}
		
		$result_head->free_result();
		$result_detail->free_result();

		return $response;
	}

	public function insert_release_detail($param)
	{
		extract($param);

		$response = array();

		$response['error'] = '';
		$query_data 		= array($this->_release_head_id,$qty,$product_id,$memo,$description);

		$query = "INSERT INTO `release_detail`
					(`headid`,
					`quantity`,
					`product_id`,
					`memo`,
					`description`)
					VALUES
					(?,?,?,?,?)";

		$result = $this->sql->execute_query($query,$query_data);

		if ($result['error'] != '') 
			throw new Exception($this->_error_message['UNABLE_TO_INSERT']);
		else
			$response['id'] = $result['id'];

		return $response;
	}

	public function update_release_detail($param)
	{
		extract($param);

		$response = array();

		$response['error'] 	= '';
		$release_detail_id 	= $this->encrypt->decode($detail_id);
		$query_data 		= array($qty,$product_id,$memo,$description,$release_detail_id);

		$query = "UPDATE `release_detail`
					SET
					`quantity` = ?,
					`product_id` = ?,
					`memo` = ?,
					`description` = ?
					WHERE `id` = ?";

		$result = $this->sql->execute_query($query,$query_data);

		if ($result['error'] != '') 
			throw new Exception($this->_error_message['UNABLE_TO_UPDATE']);

		return $response;
	}

	public function delete_release_detail($param)
	{
		extract($param);

		$response = array();

		$response['error'] 	= '';
		$release_detail_id 	= $this->encrypt->decode($detail_id);

		$query = "DELETE FROM `release_detail` WHERE `id` = ?";

		$result = $this->sql->execute_query($query,$release_detail_id);

		if ($result['error'] != '') 
			throw new Exception($this->_error_message['UNABLE_TO_DELETE']);

		return $response;

	}

	public function update_release_head($param)
	{
		extract($param);

		$response = array();

		$response['error'] 	= '';
		$entry_date 		= $entry_date.' '.date('h:i:s');
		$query_data 		= array($entry_date,$memo,$customer_name,$this->_current_user,$this->_current_date,$this->_release_head_id);

		$query = "UPDATE `release_head`
					SET
					`entry_date` = ?,
					`memo` = ?,
					`customer` = ?,
					`is_used` = ".RELEASE_CONST::USED.",
					`last_modified_by` = ?,
					`last_modified_date` = ?
					WHERE `id` = ?;";

		$result = $this->sql->execute_query($query,$query_data);

		if ($result['error'] != '') 
			throw new Exception($this->_error_message['UNABLE_TO_UPDATE_HEAD']);

		return $response;
	}

	public function update_release_qty_detail($param)
	{
		extract($param);

		$response = array();

		$response['error'] 	= '';
		$release_detail_id 	= $this->encrypt->decode($detail_id);
		$query_data 		= array($released_qty,$release_detail_id);

		$query = "UPDATE `release_detail`
					SET
					`qty_released` = ?
					WHERE `id` = ?";

		$result = $this->sql->execute_query($query,$query_data);

		if ($result['error'] != '') 
			throw new Exception($this->_error_message['UNABLE_TO_UPDATE']);

		return $response;
	}  	

	public function get_release_printout_detail()
	{
		$response = array();

		$response['error'] = '';

		$release_id = $this->encrypt->decode($this->session->userdata('release_slip'));

		$query_head = "SELECT CONCAT('WR',`reference_number`) AS 'reference_number', 
						DATE(`entry_date`) AS 'entry_date', `customer`, `memo`
					FROM release_head 
					WHERE `id` = ?";

		$result_head = $this->db->query($query_head,$release_id);
		
		if ($result_head->num_rows() == 1) 
		{
			$row = $result_head->row();

			foreach ($row as $key => $value)
				$response[$key] = $value;
		}
		else
			throw new Exception($this->_error_message['UNABLE_TO_SELECT_HEAD']);
			
		$result_head->free_result();

		$query_detail = "SELECT D.`quantity`, COALESCE(P.`description`,'-') AS 'product', 
							D.`description`, COALESCE(P.`material_code`,'-') AS 'item_code', D.`memo`
							FROM release_head AS H
							LEFT JOIN release_detail AS D ON D.`headid` = H.`id`
							LEFT JOIN product AS P ON P.`id` = D.`product_id`
							WHERE H.`id` = ?";

		$result_detail = $this->db->query($query_detail,$release_id);

		if ($result_detail->num_rows() > 0) 
		{
			$i = 0;
			foreach ($result_detail->result() as $row) 
			{
				foreach ($row as $key => $value) 
					$response['detail'][$i][$key] = $value;

				$i++;
			}
		}
		else
			throw new Exception($this->_error_message['UNABLE_TO_SELECT_DETAILS']);

		$result_detail->free_result();

		return $response;
	}

	public function get_pickup_summary_detail($param)
	{
		extract($param);

		$conditions		= "";
		$order_field 	= "";

		$response 	= array();
		$query_data = array();

		$response['rowcnt'] = 0;

		if (!empty($date_from))
		{
			$conditions .= " AND RH.`entry_date` >= ?";
			array_push($query_data,$date_from.' 00:00:00');
		}

		if (!empty($date_to))
		{
			$conditions .= " AND RH.`entry_date` <= ?";
			array_push($query_data,$date_to.' 23:59:59');
		}

		if ($branch != 0) 
		{
			$conditions .= " AND RH.`branch_id` = ?";
			array_push($query_data,$branch);
		}

		if (!empty($search_string)) 
		{
			$conditions .= " AND CONCAT('WR',RH.`reference_number`,' ',RH.`memo`,' ',RH.`customer`) LIKE ?";
			array_push($query_data,'%'.$search_string.'%');
		}

		switch ($order_by) 
		{
			case RELEASE_CONST::ORDER_BY_REFERENCE:
				$order_field = "RH.`reference_number`";
				break;
			
			case RELEASE_CONST::ORDER_BY_LOCATION:
				$order_field = "B.`name`";
				break;

			case RELEASE_CONST::ORDER_BY_CUSTOMER:
				$order_field = "RH.`customer`";
				break;
		}


		$query = "SELECT RH.`id`, COALESCE(B.`name`,'') AS 'location', CONCAT('WR',RH.`reference_number`) AS 'reference_number',
					COALESCE(DATE(`entry_date`),'') AS 'entry_date',RH. `is_used`, IF(RH.`is_used` = 0, 'Unused', RH.`memo`) AS 'memo', RH.`customer`,
					COALESCE(SUM(RD.`quantity`),'') AS 'total_qty', SUM(RD.`quantity` - RD.`qty_released`) AS 'remaining_qty',
					COALESCE(CASE
						WHEN SUM(RD.`qty_released`) = SUM(RD.`quantity`) THEN 'Complete'
						WHEN SUM(RD.`qty_released` ) > SUM(RD.`quantity`) THEN 'Excess'
						WHEN SUM(RD.`qty_released`) > 0 THEN 'Incomplete'
						WHEN SUM(RD.`qty_released`) = 0 THEN 'No Received'
					END,'') AS 'status'
					FROM release_head AS RH
					LEFT JOIN release_detail AS RD ON RD.`headid` = RH.`id`
					LEFT JOIN branch AS B ON B.`id` = RH.`branch_id` AND B.`is_show` = ".RELEASE_CONST::ACTIVE."
					WHERE RH.`is_show` = ".RELEASE_CONST::ACTIVE." AND RD.`qty_released` > 0 $conditions
					GROUP BY RH.`id`
					ORDER BY $order_field $order_type";
					
		$result = $this->db->query($query,$query_data);
		
		if ($result->num_rows() > 0) 
		{
			$i = 0;
			$response['rowcnt'] = $result->num_rows();

			foreach ($result->result() as $row) 
			{
				$response['data'][$i][] = array('');
				$response['data'][$i][] = array($this->encrypt->encode($row->id));
				$response['data'][$i][] = array($i+1);
				$response['data'][$i][] = array($row->location);
				$response['data'][$i][] = array($row->reference_number);
				$response['data'][$i][] = array($row->entry_date);
				$response['data'][$i][] = array($row->customer);
				$response['data'][$i][] = array($row->memo);
				$response['data'][$i][] = array($row->status);
				$response['data'][$i][] = array('');
				$i++;
			}
		}

		return $response;
	}

	public function get_pickup_printout_details()
	{
		$response = array();

		$response['error'] = '';

		$temp_release_id = array();
		$release_id_enc = $this->session->userdata('pickup_summary');

		for ($i=0; $i < count($release_id_enc); $i++)
			array_push($temp_release_id,$this->encrypt->decode($release_id_enc[$i]));

		$release_id = implode(",",$temp_release_id);

		$query_detail = "SELECT D.`quantity`, COALESCE(P.`description`,'-') AS 'product', 
							D.`description`, COALESCE(P.`material_code`,'-') AS 'item_code', D.`memo`, 
							CONCAT('WR',H.`reference_number`) AS 'reference_number', H.`customer`
							FROM release_head AS H
							LEFT JOIN release_detail AS D ON D.`headid` = H.`id`
							LEFT JOIN product AS P ON P.`id` = D.`product_id`
							WHERE H.`id` IN($release_id) ";

		$result_detail = $this->db->query($query_detail);

		if ($result_detail->num_rows() > 0) 
		{
			$i = 0;
			foreach ($result_detail->result() as $row) 
			{
				foreach ($row as $key => $value) 
					$response['detail'][$i][$key] = $value;

				$i++;
			}
		}
		else
			throw new Exception($this->_error_message['UNABLE_TO_SELECT_DETAILS']);

		$result_detail->free_result();

		return $response;
	}
}