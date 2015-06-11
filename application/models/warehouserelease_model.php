<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Warehouserelease_Model extends CI_Model {

	private $_return_head_id = 0;
	private $_current_branch_id = 0;
	private $_current_user = 0;
	private $_current_date = '';

	/**
	 * Load Encrypt Class for encryption, cookie and constants
	 */
	public function __construct() {
		$this->load->library('encrypt');
		$this->load->library('constants/return_const');
		$this->load->library('sql');
		$this->load->helper('cookie');

		$this->_return_head_id 		= $this->encrypt->decode($this->uri->segment(3));
		$this->_current_branch_id 	= $this->encrypt->decode(get_cookie('branch'));
		$this->_current_user 		= $this->encrypt->decode(get_cookie('temp'));
		$this->_current_date 		= date("Y-m-d h:i:s");

		parent::__construct();
	}

	public function get_warehouserelease_details()
	{
		$response 		= array();
		$branch_id 		= 0;

		$response['head_error'] 	= '';
		$response['detail_error'] 	= ''; 

		$query_head = "SELECT CONCAT('WRD',`reference_number`) AS 'reference_number', COALESCE(DATE(`entry_date`),'') AS 'entry_date', `memo`, `branch_id`, `customer`
					FROM `warehouserelease_head`
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
			$branch_id = $row->branch_id;
		}

		$query_detail_data = array($branch_id,$this->_return_head_id);

		$query_detail = "SELECT WRD.`id`, WRD.`product_id`, COALESCE(P.`material_code`,'') AS 'material_code', 
						COALESCE(P.`description`,'') AS 'product', WRD.`quantity`, WRD.`memo`, WRD.`qty_released`,
						COALESCE(PBI.`inventory`,0) AS 'inventory'
					FROM `warehouserelease_detail` AS WRD
					LEFT JOIN `warehouserelease_head` AS WRH ON WRD.`headid` = WRH.`id` AND WRH.`is_show` = ".RETURN_CONST::ACTIVE."
					LEFT JOIN `product` AS P ON P.`id` = WRD.`product_id` AND P.`is_show` = ".RETURN_CONST::ACTIVE."
					LEFT JOIN `product_branch_inventory` AS PBI ON PBI.`product_id` = P.`id` AND PBI.`branch_id` = ? 
					WHERE WRD.`headid` = ?";

		$result_detail = $this->db->query($query_detail,$query_detail_data);

		if ($result_detail->num_rows() == 0) 
			$response['detail_error'] = 'No return details found!';
		else
		{
			$i = 0;
			foreach ($result_detail->result() as $row) 
			{
				$response['detail'][$i][] = array($this->encrypt->encode($row->id));
				$response['detail'][$i][] = array($i+1);
				$response['detail'][$i][] = array($row->product, $row->product_id);
				$response['detail'][$i][] = array($row->material_code);
				$response['detail'][$i][] = array($row->quantity);
				$response['detail'][$i][] = array($row->inventory);
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

public function insert_warehouserelease_detail($param)
	{
		extract($param);

		$response = array();

		$response['error'] = '';
		$query_data 		= array($this->_return_head_id,$qty,$product_id,$memo,$released);

		$query = "INSERT INTO `warehouserelease_detail`
					(`headid`,
					`quantity`,
					`product_id`,
					`memo`, `qty_released`)
					VALUES
					(?,?,?,?,?);";

		$result = $this->sql->execute_query($query,$query_data);

		if ($result['error'] != '') 
			$response['error'] = 'Unable to insert return detail!';
		else
			$response['id'] = $result['id'];

		return $response;
	}

	public function update_warehouserelease_detail($param)
	{
		extract($param);

		$response = array();

		$response['error'] = '';
		$return_detail_id 	= $this->encrypt->decode($detail_id);
		$query_data 		= array($qty,$product_id,$memo, $released,$return_detail_id);

		$query = "UPDATE `warehouserelease_detail`
					SET
					`quantity` = ?,
					`product_id` = ?,
					`memo` = ?,
					`qty_released` = ?
					WHERE `id` = ?;";

		$result = $this->sql->execute_query($query,$query_data);

		if ($result['error'] != '') 
			$response['error'] = 'Unable to update return detail!';

		return $response;
	}

	public function delete_warehouserelease_detail($param)
	{
		extract($param);

		$response = array();

		$response['error'] 	= '';
		$return_detail_id 	= $this->encrypt->decode($detail_id);

		$query = "DELETE FROM `warehouserelease_detail` WHERE `id` = ?";

		$result = $this->sql->execute_query($query,$return_detail_id);

		if ($result['error'] != '') 
			$response['error'] = 'Unable to delete return detail!';

		return $response;

	}

	public function update_warehouserelease_head($param)
	{
		extract($param);

		$response = array();

		$response['error'] 	= '';
		$entry_date 		= $entry_date.' '.date('h:i:s');
		$query_data 		= array($entry_date,$memo,$customer_name,$this->_current_user,$this->_current_date,$this->_return_head_id);

		$query = "UPDATE `warehouserelease_head`
					SET
					`entry_date` = ?,
					`memo` = ?,
					`customer` = ?,
					`is_used` = ".RETURN_CONST::USED.",
					`last_modified_by` = ?,
					`last_modified_date` = ?
					WHERE `id` = ?;";

		$result = $this->sql->execute_query($query,$query_data);

		if ($result['error'] != '') 
			$response['error'] = 'Unable to update return head!';

		return $response;
	}
	public function search_warehouserelease_list($param)
	{
		extract($param);

		$conditions		= "";
		$order_field 	= "";

		$response 	= array();
		$query_data = array();

		$response['rowcnt'] = 0;
		
		
		if (!empty($date_from))
		{
			$conditions .= " AND RD.`date_created` >= ?";
			array_push($query_data,$date_from.' 00:00:00');
		}

		if (!empty($date_to))
		{
			$conditions .= " AND RD.`date_created` <= ?";
			array_push($query_data,$date_to.' 23:59:59');
		}

		if ($branch != RETURN_CONST::ALL_OPTION) 
		{
			$conditions .= " AND RD.`branch_id` = ?";
			array_push($query_data,$branch);
		}

		if (!empty($search_string)) 
		{
			$conditions .= " AND CONCAT('WRD',RD.`reference_number`,' ',B.`name`,' ',RD.`customer`) LIKE ?";
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
				$order_field = "RD.`customer`";
				break;
		}


		$query = "SELECT RD.`id`, COALESCE(B.`name`,'') AS 'location', CONCAT('WRD',RD.`reference_number`) AS 'reference_number',
					COALESCE(DATE(`entry_date`),'') AS 'entry_date', IF(RD.`is_used` = 0, 'Unused', RD.`memo`) AS 'memo', RD.`customer`
					FROM warehouserelease_head AS RD
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
				$response['data'][$i][] = array($row->memo);
				$response['data'][$i][] = array('');
				$i++;
			}
		}

		$result->free_result();
		
		return $response;
	}
	public function delete_warehouserelease_head($param)
	{
		extract($param);

		$return_head_id 		= $this->encrypt->decode($return_id);

		$response = array();
		$response['error'] = '';

		$query_data = array($this->_current_date,$this->_current_user,$return_head_id);
		$query 	= "UPDATE `warehouserelease_head` 
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
