<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Return_Model extends CI_Model {

	private $_return_head_id = 0;
	private $_current_branch_id = 0;
	private $_current_user = 0;
	private $_current_date = '';

	/**
	 * Load Encrypt Class for encryption, cookie and constants
	 */
	public function __construct() {
		$this->load->library('encrypt');
		$this->load->file(CONSTANTS.'return_const.php');
		$this->load->library('sql');
		$this->load->helper('cookie');

		$this->_return_head_id 		= $this->encrypt->decode($this->uri->segment(3));
		$this->_current_branch_id 	= $this->encrypt->decode(get_cookie('branch'));
		$this->_current_user 		= $this->encrypt->decode(get_cookie('temp'));
		$this->_current_date 		= date("Y-m-d h:i:s");

		parent::__construct();
	}

	public function get_return_details()
	{
		$response 		= array();
		$branch_id 		= 0;

		$response['head_error'] 	= '';
		$response['detail_error'] 	= ''; 

		$query_head = "SELECT CONCAT('RD',`reference_number`) AS 'reference_number',
					COALESCE(DATE(`entry_date`),'') AS 'entry_date', `memo`, `branch_id`, `customer`, `received_by`
					FROM `return_head`
					WHERE `is_show` = ".RETURN_CONST::ACTIVE." AND `id` = ?";

		$result_head = $this->db->query($query_head,$this->_return_head_id);

		if ($result_head->num_rows() != 1) 
			$response['head_error'] = 'Unable to get return head details!';
		else
		{
			$row = $result_head->row();

			$response['reference_number'] 	= $row->reference_number;
			$response['entry_date'] 		= $row->entry_date;
			$response['memo'] 				= $row->memo;
			$response['customer_name'] 		= $row->customer;
			$response['received_by'] 		= $row->received_by;
			$branch_id = $row->branch_id;
		}

		$query_detail = "SELECT RD.`id`, RD.`product_id`, COALESCE(P.`material_code`,'') AS 'material_code', 
						COALESCE(P.`description`,'') AS 'product', RD.`quantity`, RD.`memo`, RD.`description`
					FROM `return_detail` AS RD
					LEFT JOIN `return_head` AS RH ON RD.`headid` = RH.`id` AND RH.`is_show` = ".RETURN_CONST::ACTIVE."
					LEFT JOIN `product` AS P ON P.`id` = RD.`product_id` AND P.`is_show` = ".RETURN_CONST::ACTIVE."
					WHERE RD.`headid` = ?";

		$result_detail = $this->db->query($query_detail,$this->_return_head_id);

		if ($result_detail->num_rows() == 0) 
			$response['detail_error'] = 'No return details found!';
		else
		{
			$i = 0;
			foreach ($result_detail->result() as $row) 
			{
				$break_line = empty($row->description) ? '' : '<br/>';
				$response['detail'][$i][] = array($this->encrypt->encode($row->id));
				$response['detail'][$i][] = array($i+1);
				$response['detail'][$i][] = array($row->product, $row->product_id, $break_line, $row->description);
				$response['detail'][$i][] = array($row->material_code);
				$response['detail'][$i][] = array($row->quantity);
				$response['detail'][$i][] = array($row->memo);
				$response['detail'][$i][] = array('');
				$response['detail'][$i][] = array('');
				$i++;
			}
		}

		$result_head->free_result();
		$result_detail->free_result();

		return $response;
	}

	public function insert_return_detail($param)
	{
		extract($param);

		$response = array();

		$response['error'] = '';
		$query_data 		= array($this->_return_head_id,$qty,$product_id,$memo,$description);

		$query = "INSERT INTO `return_detail`
					(`headid`,
					`quantity`,
					`product_id`,
					`memo`,
					`description`)
					VALUES
					(?,?,?,?,?);";

		$result = $this->sql->execute_query($query,$query_data);

		if ($result['error'] != '') 
			$response['error'] = 'Unable to insert return detail!';
		else
			$response['id'] = $result['id'];

		return $response;
	}

	public function update_return_detail($param)
	{
		extract($param);

		$response = array();

		$response['error'] = '';
		$return_detail_id 	= $this->encrypt->decode($detail_id);
		$query_data 		= array($qty,$product_id,$memo,$description,$return_detail_id);

		$query = "UPDATE `return_detail`
					SET
					`quantity` = ?,
					`product_id` = ?,
					`memo` = ?,
					`description` = ?
					WHERE `id` = ?;";

		$result = $this->sql->execute_query($query,$query_data);

		if ($result['error'] != '') 
			$response['error'] = 'Unable to update return detail!';

		return $response;
	}

	public function delete_return_detail($param)
	{
		extract($param);

		$response = array();

		$response['error'] 	= '';
		$return_detail_id 	= $this->encrypt->decode($detail_id);

		$query = "DELETE FROM `return_detail` WHERE `id` = ?";

		$result = $this->sql->execute_query($query,$return_detail_id);

		if ($result['error'] != '') 
			$response['error'] = 'Unable to delete return detail!';

		return $response;

	}

	public function update_return_head($param)
	{
		extract($param);

		$response = array();

		$response['error'] 	= '';
		$entry_date 		= $entry_date.' '.date('h:i:s');
		$query_data 		= array($entry_date,$memo,$customer_name,$received_by,$this->_current_user,$this->_current_date,$this->_return_head_id);

		$query = "UPDATE `return_head`
					SET
					`entry_date` = ?,
					`memo` = ?,
					`customer` = ?,
					`received_by` = ?,
					`is_used` = ".RETURN_CONST::USED.",
					`last_modified_by` = ?,
					`last_modified_date` = ?
					WHERE `id` = ?;";

		$result = $this->sql->execute_query($query,$query_data);

		if ($result['error'] != '') 
			$response['error'] = 'Unable to update return head!';

		return $response;
	}

	public function search_return_list($param)
	{
		extract($param);

		$conditions		= "";
		$order_field 	= "";

		$response 	= array();
		$query_data = array();

		$response['rowcnt'] = 0;
		
		
		if (!empty($date_from))
		{
			$conditions .= " AND RD.`entry_date` >= ?";
			array_push($query_data,$date_from.' 00:00:00');
		}

		if (!empty($date_to))
		{
			$conditions .= " AND RD.`entry_date` <= ?";
			array_push($query_data,$date_to.' 23:59:59');
		}

		if ($branch != RETURN_CONST::ALL_OPTION) 
		{
			$conditions .= " AND RD.`branch_id` = ?";
			array_push($query_data,$branch);
		}

		if (!empty($search_string)) 
		{
			$conditions .= " AND CONCAT('RD',RD.`reference_number`,' ',RD.`memo`,' ',RD.`customer`,' ',RD.`received_by`) LIKE ?";
			array_push($query_data,'%'.$search_string.'%');
		}

		switch ($order_by) 
		{
			case RETURN_CONST::ORDER_BY_REFERENCE:
				$order_field = "RD.`reference_number`";
				break;
			
			case RETURN_CONST::ORDER_BY_LOCATION:
				$order_field = "B.`name`";
				break;

			case RETURN_CONST::ORDER_BY_DATE:
				$order_field = "RD.`entry_date`";
				break;
		}


		$query = "SELECT RD.`id`, COALESCE(B.`name`,'') AS 'location', CONCAT('RD',RD.`reference_number`) AS 'reference_number',
					COALESCE(DATE(`entry_date`),'') AS 'entry_date', IF(RD.`is_used` = 0, 'Unused', RD.`memo`) AS 'memo', 
					RD.`customer`, RD.`received_by`
					FROM return_head AS RD
					LEFT JOIN branch AS B ON B.`id` = RD.`branch_id` AND B.`is_show` = ".RETURN_CONST::ACTIVE."
					WHERE RD.`is_show` = ".RETURN_CONST::ACTIVE." $conditions
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
				$response['data'][$i][] = array($row->received_by);
				$response['data'][$i][] = array($row->memo);
				$response['data'][$i][] = array('');
				$i++;
			}
		}

		$result->free_result();
		
		return $response;
	}

	public function delete_return_head($param)
	{
		extract($param);

		$return_id 		= $this->encrypt->decode($head_id);

		$response = array();
		$response['error'] = '';

		$query_data = array($this->_current_date,$this->_current_user,$return_id);
		$query 	= "UPDATE `return_head` 
					SET 
					`is_show` = ".RETURN_CONST::DELETED.",
					`last_modified_date` = ?,
					`last_modified_by` = ?
					WHERE `id` = ?";

		$result = $this->sql->execute_query($query,$query_data);

		if ($result['error'] != '') 
			$response['error'] = 'Unable to delete return head!';
		
		return $response;
	}

}
