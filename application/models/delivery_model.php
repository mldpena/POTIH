<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Delivery_Model extends CI_Model {

	private $_delivery_head_id = 0;
	private $_current_branch_id = 0;
	private $_current_user = 0;
	private $_current_date = '';
	private $_error_message = array('UNABLE_TO_INSERT' => 'Unable to insert delivery detail!',
									'UNABLE_TO_UPDATE' => 'Unable to update delivery detail!',
									'UNABLE_TO_UPDATE_HEAD' => 'Unable to update delivery head!',
									'UNABLE_TO_SELECT_HEAD' => 'Unable to get delivery head details!',
									'UNABLE_TO_SELECT_DETAILS' => 'Unable to get delivery details!',
									'UNABLE_TO_DELETE' => 'Unable to delete delivery detail!',
									'UNABLE_TO_DELETE_HEAD' => 'Unable to delete delivery head!',
									'HAS_RECEIVED' => 'Stock Delivery can only be deleted if delivery status is no received!');

	/**
	 * Load Encrypt Class for encryption, cookie and constants
	 */
	public function __construct() 
	{
		$this->load->library('encrypt');
		$this->load->file(CONSTANTS.'delivery_const.php');
		$this->load->library('sql');
		$this->load->helper('cookie');

		$this->_delivery_head_id 	= $this->encrypt->decode($this->uri->segment(3));
		$this->_current_branch_id 	= $this->encrypt->decode(get_cookie('branch'));
		$this->_current_user 		= $this->encrypt->decode(get_cookie('temp'));
		$this->_current_date 		= date("Y-m-d h:i:s");

		parent::__construct();
	}

	public function get_stock_delivery_details()
	{
		$response 		= array();
		$branch_id 		= 0;

		$response['error'] 	= '';
		$response['detail_error'] 	= ''; 

		$query_head = "SELECT CONCAT('SD',SH.`reference_number`) AS 'reference_number', COALESCE(DATE(SH.`entry_date`),'') AS 'entry_date', 
					SH.`memo`, SH.`branch_id`, SH.`to_branchid`, SUM(SD.`recv_quantity`) AS 'total_qty', SH.`delivery_type`, SH.`is_used`
					FROM `stock_delivery_head` AS SH
					LEFT JOIN stock_delivery_detail AS SD ON SD.`headid` = SH.`id`
					WHERE SH.`is_show` = ".DELIVERY_CONST::ACTIVE." AND SH.`id` = ?
					GROUP BY SH.`id`";

		$result_head = $this->db->query($query_head,$this->_delivery_head_id);

		if ($result_head->num_rows() != 1) 
			throw new Exception($this->_error_message['UNABLE_TO_SELECT_HEAD']);
		else
		{
			$row = $result_head->row();

			$response['reference_number'] 	= $row->reference_number;
			$response['entry_date'] 		= $row->entry_date;
			$response['memo'] 				= $row->memo;
			$response['to_branchid'] 		= $row->to_branchid;
			$response['delivery_type'] 		= $row->delivery_type;
			$response['is_editable'] 		= $row->total_qty == 0 ? TRUE : FALSE;
			$response['is_saved'] 			= $row->is_used;
			$response['own_branch'] 		= $this->_current_branch_id;
			$branch_id = $row->branch_id;
		}


		$query_detail = "SELECT SD.`id`, SD.`product_id`, COALESCE(P.`material_code`,'') AS 'material_code', 
						COALESCE(P.`description`,'') AS 'product', SD.`quantity`, SD.`memo`, SD.`is_for_branch`, 
						SD.`recv_quantity` AS 'receiveqty', SD.`description`
					FROM `stock_delivery_detail` AS SD
					LEFT JOIN `stock_delivery_head` AS SH ON SD.`headid` = SH.`id` AND SH.`is_show` = ".DELIVERY_CONST::ACTIVE."
					LEFT JOIN `product` AS P ON P.`id` = SD.`product_id` AND P.`is_show` = ".DELIVERY_CONST::ACTIVE."
					WHERE SD.`headid` = ?";

		$result_detail = $this->db->query($query_detail,$this->_delivery_head_id);

		if ($result_detail->num_rows() == 0) 
			$response['detail_error'] = 'No stock delivery details found!';
		else
		{
			$i = 0;
			foreach ($result_detail->result() as $row) 
			{
				$break_line = empty($row->description) ? '' : '<br/>';
				$response['detail'][$i][] = array($this->encrypt->encode($row->id));
				$response['detail'][$i][] = array($row->is_for_branch);
				$response['detail'][$i][] = array($i+1);
				$response['detail'][$i][] = array($row->product, $row->product_id,$break_line,$row->description);
				$response['detail'][$i][] = array($row->material_code);
				$response['detail'][$i][] = array($row->quantity);
				$response['detail'][$i][] = array($row->receiveqty);
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

	public function insert_stock_delivery_detail($param)
	{
		extract($param);

		$response = array();

		$response['error'] = '';
		$query_data 		= array($this->_delivery_head_id,$qty,$product_id,$memo,$istransfer,$description);

		$query = "INSERT INTO `stock_delivery_detail`
					(`headid`,
					`quantity`,
					`product_id`,
					`memo`,
					`is_for_branch`,
					`description`)
					VALUES
					(?,?,?,?,?,?);";

		$result = $this->sql->execute_query($query,$query_data);

		if ($result['error'] != '') 
			throw new Exception($this->_error_message['UNABLE_TO_INSERT']);
		else
			$response['id'] = $result['id'];

		return $response;
	}

	public function update_stock_delivery_detail($param)
	{
		extract($param);

		$response = array();

		$response['error'] = '';
		$delivery_detail_id = $this->encrypt->decode($detail_id);
		$query_data 		= array($qty,$product_id,$memo,$istransfer,$description,$delivery_detail_id);

		$query = "UPDATE `stock_delivery_detail`
					SET
					`quantity` = ?,
					`product_id` = ?,
					`memo` = ?,
					`is_for_branch` = ?,
					`description` = ?
					WHERE `id` = ?;";

		$result = $this->sql->execute_query($query,$query_data);

		if ($result['error'] != '') 
			throw new Exception($this->_error_message['UNABLE_TO_UPDATE']);

		return $response;
	}
	
	public function delete_stock_delivery_detail($param)
	{
		extract($param);

		$response = array();

		$response['error'] 	= '';
		$delivery_detail_id 	= $this->encrypt->decode($detail_id);

		$query = "DELETE FROM `stock_delivery_detail` WHERE `id` = ?";

		$result = $this->sql->execute_query($query,$delivery_detail_id);

		if ($result['error'] != '') 
			throw new Exception($this->_error_message['UNABLE_TO_DELETE']);

		return $response;

	}

	public function update_stock_delivery_head($param)
	{
		extract($param);

		$response = array();

		$response['error'] 	= '';
		$entry_date 		= $entry_date.' '.date('h:i:s');

		$query_data 		= array();
		$query 				= array();

		$query_delivery_head_data = array($entry_date,$entry_date,$entry_date,$memo,$to_branch,$type,$this->_current_user,$this->_current_date,$this->_delivery_head_id); 

		$query_delivery_head = "UPDATE `stock_delivery_head`
					SET
					`entry_date` = ?,
					`delivery_receive_date` = ?,
					`customer_receive_date` = ?,
					`memo` = ?,
					`to_branchid` = ?,
					`delivery_type` = ?,
					`is_used` = ".DELIVERY_CONST::USED.",
					`last_modified_by` = ?,
					`last_modified_date` = ?
					WHERE `id` = ?;";

		array_push($query,$query_delivery_head);
		array_push($query_data,$query_delivery_head_data);

		if ($type != DELIVERY_CONST::BOTH) 
		{
			$type = $type == DELIVERY_CONST::SALES ? 0 : 1;

			$query_delivery_detail_data = array($type,$this->_delivery_head_id);
			$query_delivery_detail = "UPDATE `stock_delivery_detail`
										SET `is_for_branch` = ?
										WHERE `headid` = ?";

			array_push($query,$query_delivery_detail);
			array_push($query_data,$query_delivery_detail_data);
		}

		$result = $this->sql->execute_transaction($query,$query_data);

		if ($result['error'] != '') 
			throw new Exception($this->_error_message['UNABLE_TO_UPDATE_HEAD']);

		return $response;
	}

	public function search_stock_delivery_list($param)
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
			$conditions .= " AND SH.`entry_date` >= ?";
			array_push($query_data,$date_from.' 00:00:00');
		}

		if (!empty($date_to))
		{
			$conditions .= " AND SH.`entry_date` <= ?";
			array_push($query_data,$date_to.' 23:59:59');
		}

		if ($from_branch != DELIVERY_CONST::ALL_OPTION) 
		{
			$conditions .= " AND SH.`branch_id` = ?";
			array_push($query_data,$from_branch);
		}

		if ($to_branch != DELIVERY_CONST::ALL_OPTION) 
		{
			$conditions .= " AND SH.`to_branchid` = ?";
			array_push($query_data,$to_branch);
		}
	
		if (!empty($search_string)) 
		{
			$conditions .= " AND CONCAT('SD',SH.`reference_number`,' ',SH.`memo`) LIKE ?";
			array_push($query_data,'%'.$search_string.'%');
		}

		if ($type != DELIVERY_CONST::ALL_OPTION) 
		{
			switch ($type) 
			{
				case DELIVERY_CONST::BOTH:
					$conditions .= " AND SH.`delivery_type` = ".DELIVERY_CONST::BOTH;
					break;
				
				case DELIVERY_CONST::SALES:
					$conditions .= " AND SH.`delivery_type` = ".DELIVERY_CONST::SALES;
					break;

				case DELIVERY_CONST::TRANSFER:
					$conditions .= " AND SH.`delivery_type` = ".DELIVERY_CONST::TRANSFER;
					break;
			}
		}

		switch ($order_by) 
		{
			case DELIVERY_CONST::ORDER_BY_REFERENCE:
				$order_field = "SH.`reference_number`";
				break;

			case DELIVERY_CONST::ORDER_BY_DATE:
				$order_field = "SH.`entry_date`";
				break;
		}

		if ($status != DELIVERY_CONST::ALL_OPTION) 
		{
			switch ($status) 
			{
				case DELIVERY_CONST::INCOMPLETE:
					$having = "HAVING remaining_qty < total_qty AND remaining_qty <> 0";
					break;
				
				case DELIVERY_CONST::COMPLETE:
					$having = "HAVING remaining_qty = 0";
					break;

				case DELIVERY_CONST::NO_RECEIVED:
					$having = "HAVING remaining_qty = total_qty";
					break;
			}
		}

		$query = "SELECT SH.`id`, COALESCE(B.`name`,'') AS 'from_branch', COALESCE(B2.`name`,'-') AS 'to_branch', 
					CONCAT('SD',SH.`reference_number`) AS 'reference_number',
					COALESCE(DATE(SH.`entry_date`),'') AS 'entry_date', IF(SH.`is_used` = 0, 'Unused', SH.`memo`) AS 'memo',
					COALESCE(SUM(SD.`quantity`),'') AS 'total_qty', SUM(SD.`quantity` - SD.`recv_quantity`) AS 'remaining_qty',
					CASE 
						WHEN `delivery_type` = ".DELIVERY_CONST::BOTH." THEN 'Both'
						WHEN `delivery_type` = ".DELIVERY_CONST::SALES." THEN 'Sales'
						WHEN `delivery_type` = ".DELIVERY_CONST::TRANSFER." THEN 'Transfer'
						ELSE 'Unused'
					END AS 'delivery_type',
					COALESCE(CASE
						WHEN SUM(SD.`quantity` - SD.`recv_quantity`) < SUM(SD.`quantity`) AND SUM(SD.`quantity` - SD.`recv_quantity`) <> 0 THEN 'Incomplete'
						WHEN SUM(SD.`quantity` - SD.`recv_quantity`) = 0 THEN 'Complete'
						WHEN SUM(SD.`quantity` - SD.`recv_quantity`) = SUM(SD.`quantity`) THEN 'No Received'
					END,'') AS 'status'
					FROM stock_delivery_head AS SH
					LEFT JOIN stock_delivery_detail AS SD ON SD.`headid` = SH.`id`
					LEFT JOIN branch AS B ON B.`id` = SH.`branch_id` AND B.`is_show` = ".DELIVERY_CONST::ACTIVE."
					LEFT JOIN branch AS B2 ON B2.`id` = SH.`to_branchid` AND B2.`is_show` = ".DELIVERY_CONST::ACTIVE."
					WHERE SH.`is_show` = ".DELIVERY_CONST::ACTIVE." $conditions
					GROUP BY SH.`id`
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
				$response['data'][$i][] = array($row->reference_number);
				$response['data'][$i][] = array($row->from_branch);
				$response['data'][$i][] = array($row->to_branch);
				$response['data'][$i][] = array($row->entry_date);
				$response['data'][$i][] = array($row->delivery_type);
				$response['data'][$i][] = array($row->memo);
				$response['data'][$i][] = array($row->total_qty);
				$response['data'][$i][] = array($row->status);
				$response['data'][$i][] = array('');
				$i++;
			}
		}

		$result->free_result();

		return $response;
	}
	
	public function delete_stock_delivery_head($param)
	{
		extract($param);

		$delivery_head_id = $this->encrypt->decode($head_id);

		$response = array();
		$response['error'] = '';

		$query 	= "SELECT SUM(`recv_quantity`) AS 'total_received' FROM stock_delivery_detail WHERE `headid` = ?";
		$result = $this->db->query($query,$delivery_head_id);
		$row 	= $result->row();

		if ($row->total_received > 0) {
			throw new Exception($this->_error_message['HAS_RECEIVED']);
		}

		$result->free_result();

		$query_data = array($this->_current_date,$this->_current_user,$delivery_head_id);
		$query 	= "UPDATE `stock_delivery_head` 
					SET 
					`is_show` = ".DELIVERY_CONST::DELETED.",
					`last_modified_date` = ?,
					`last_modified_by` = ?
					WHERE `id` = ?";

		$result = $this->sql->execute_query($query,$query_data);

		if ($result['error'] != '') 
			throw new Exception($this->_error_message['UNABLE_TO_DELETE_HEAD']);

		return $response;
	}

	public function search_receive_list($param, $search_type)
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
			$conditions .= " AND SH.`entry_date` >= ?";
			array_push($query_data,$date_from.' 00:00:00');
		}

		if (!empty($date_to))
		{
			$conditions .= " AND SH.`entry_date` <= ?";
			array_push($query_data,$date_to.' 23:59:59');
		}

		if ($from_branch != DELIVERY_CONST::ALL_OPTION) 
		{
			$conditions .= " AND SH.`branch_id` = ?";
			array_push($query_data,$from_branch);
		}
	
		if (!empty($search_string)) 
		{
			$conditions .= " AND CONCAT('SD',SH.`reference_number`,' ',SH.`memo`) LIKE ?";
			array_push($query_data,'%'.$search_string.'%');
		}

		$fixed_condition = "";

		switch ($search_type) {
			case DELIVERY_CONST::FOR_TRANSFER:
				$fixed_condition = "AND SH.`delivery_type` IN(".DELIVERY_CONST::TRANSFER.",".DELIVERY_CONST::BOTH.") AND SD.`is_for_branch` = 1";
				if (($to_branch) && $to_branch != DELIVERY_CONST::ALL_OPTION) 
				{
					$conditions .= " AND SH.`to_branchid` = ?";
					array_push($query_data,$to_branch);
				}
				break;
			
			case DELIVERY_CONST::FOR_CUSTOMER:
				$fixed_condition = "AND SH.`delivery_type` IN(".DELIVERY_CONST::SALES.",".DELIVERY_CONST::BOTH.") AND SD.`is_for_branch` = 0";
				break;
		}

		switch ($order_by) 
		{
			case DELIVERY_CONST::ORDER_BY_REFERENCE:
				$order_field = "SH.`reference_number`";
				break;

			case DELIVERY_CONST::ORDER_BY_DATE:
				$order_field = "SH.`entry_date`";
				break;
		}

		$query = "SELECT SH.`id`, COALESCE(B.`name`,'') AS 'from_branch', COALESCE(B2.`name`,'-') AS 'to_branch', 
					CONCAT('SD',SH.`reference_number`) AS 'reference_number',
					COALESCE(DATE(SH.`entry_date`),'') AS 'entry_date', SH.`memo`,
					SUM(SD.`quantity`) AS 'total_qty'
					FROM stock_delivery_head AS SH
					LEFT JOIN stock_delivery_detail AS SD ON SD.`headid` = SH.`id`
					LEFT JOIN branch AS B ON B.`id` = SH.`branch_id` AND B.`is_show` = ".DELIVERY_CONST::ACTIVE."
					LEFT JOIN branch AS B2 ON B2.`id` = SH.`to_branchid` AND B2.`is_show` = ".DELIVERY_CONST::ACTIVE."
					WHERE SH.`is_show` = ".DELIVERY_CONST::ACTIVE." AND SH.`is_used` = ".DELIVERY_CONST::USED." 
						$fixed_condition $conditions
					GROUP BY SH.`id`
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
				$response['data'][$i][] = array($row->reference_number);
				$response['data'][$i][] = array($row->from_branch);

				if ($search_type == DELIVERY_CONST::FOR_TRANSFER)
					$response['data'][$i][] = array($row->to_branch);
				
				$response['data'][$i][] = array($row->entry_date);
				$response['data'][$i][] = array($row->memo);
				$response['data'][$i][] = array($row->total_qty);

				$i++;
			}
		}

		$result->free_result();

		return $response;
	}

	public function get_receive_details($receive_type)
	{
		$response 		= array();
		$branch_id 		= 0;

		$response['error'] 	= '';
		$response['detail_error'] 	= ''; 

		$receive_date_column 	= "";
		$is_transfer 			= "";

		if ($receive_type == DELIVERY_CONST::FOR_TRANSFER) 
		{
			$receive_date_column = "SH.`delivery_receive_date`";
			$is_transfer = 1;
		}
		else
		{
			$receive_date_column = "SH.`customer_receive_date`";
			$is_transfer = 0;
		}

		$query_head = "SELECT CONCAT('SD',SH.`reference_number`) AS 'reference_number', COALESCE(DATE(SH.`entry_date`),'') AS 'entry_date', 
					SH.`memo`, SH.`branch_id`, SH.`to_branchid`, SH.`delivery_type`, DATE($receive_date_column) AS 'receive_date'
					FROM `stock_delivery_head` AS SH
					WHERE SH.`is_show` = ".DELIVERY_CONST::ACTIVE." AND SH.`id` = ?
					GROUP BY SH.`id`";

		$result_head = $this->db->query($query_head,$this->_delivery_head_id);

		if ($result_head->num_rows() != 1) 
			throw new Exception($this->_error_message['UNABLE_TO_SELECT_HEAD']);
		else
		{
			$row = $result_head->row();

			$response['reference_number'] 	= $row->reference_number;
			$response['entry_date'] 		= $row->entry_date;
			$response['memo'] 				= $row->memo;

			if ($receive_type == DELIVERY_CONST::FOR_TRANSFER) 
				$response['to_branchid'] 		= $row->to_branchid;

			$response['delivery_type'] 		= $row->delivery_type;
			$response['receive_date'] 		= $row->receive_date;
			$branch_id = $row->branch_id;
		}

		$query_detail = "SELECT SD.`id`, SD.`product_id`, COALESCE(P.`material_code`,'') AS 'material_code', 
						COALESCE(P.`description`,'') AS 'product', SD.`quantity`, SD.`memo`, SD.`is_for_branch`, 
						SD.`recv_quantity`, SD.`description`
					FROM `stock_delivery_detail` AS SD
					LEFT JOIN `stock_delivery_head` AS SH ON SD.`headid` = SH.`id` AND SH.`is_show` = ".DELIVERY_CONST::ACTIVE."
					LEFT JOIN `product` AS P ON P.`id` = SD.`product_id` AND P.`is_show` = ".DELIVERY_CONST::ACTIVE."
					WHERE SD.`headid` = ? AND SD.`is_for_branch` = $is_transfer";

		$result_detail = $this->db->query($query_detail,$this->_delivery_head_id);

		if ($result_detail->num_rows() == 0) 
			$response['detail_error'] = 'No receive details found!';
		else
		{
			$i = 0;
			foreach ($result_detail->result() as $row) 
			{
				$break_line = empty($row->description) ? '' : '<br/>';
				$response['detail'][$i][] = array($this->encrypt->encode($row->id));
				$response['detail'][$i][] = array($i+1);
				$response['detail'][$i][] = array($row->product, $row->product_id,$break_line,$row->description);
				$response['detail'][$i][] = array($row->material_code);
				$response['detail'][$i][] = array($row->quantity);
				$response['detail'][$i][] = array($row->memo);
				$response['detail'][$i][] = array('');
				$response['detail'][$i][] = array($row->recv_quantity);
				$response['detail'][$i][] = array('');
				$i++;
			}
		}

		$result_head->free_result();
		$result_detail->free_result();

		return $response;
	}

	public function update_receive_detail($param)
	{
		extract($param);

		$response = array();

		$response['error'] = '';
		$delivery_detail_id = $this->encrypt->decode($detail_id);
		$query_data 		= array($receiveqty,$delivery_detail_id);

		$query = "UPDATE `stock_delivery_detail`
					SET
					`recv_quantity` = ?
					WHERE `id` = ?;";

		$result = $this->sql->execute_query($query,$query_data);

		if ($result['error'] != '') 
			throw new Exception($this->_error_message['UNABLE_TO_UPDATE']);

		return $response;
	}

	public function update_receive_head($param, $receive_type)
	{
		extract($param);

		$response = array();
		$response['error'] = '';

		$receive_date_column = $receive_type == DELIVERY_CONST::FOR_TRANSFER ? 'delivery_receive_date' : 'customer_receive_date';

		$query_data = array($receive_date,$this->_delivery_head_id);
		$query 	= "UPDATE `stock_delivery_head` 
					SET `$receive_date_column` = ?
					WHERE `id` = ?";

		$result = $this->sql->execute_query($query,$query_data);

		if ($result['error'] != '') 
			throw new Exception($this->_error_message['UNABLE_TO_UPDATE_HEAD']);

		return $response;
	}
}
