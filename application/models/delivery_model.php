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
									'HAS_RECEIVED' => 'Item Delivery can only be deleted if delivery status is no received!',
									'NOT_OWN_BRANCH' => 'Cannot delete item delivery entry of other branches!',
									'NO_ITEMS_TO_PRINT' => 'No items to print!');

	/**
	 * Load Encrypt Class for encryption, cookie and constants
	 */
	public function __construct() 
	{
		parent::__construct();

		$this->load->constant('delivery_const');

		$this->_delivery_head_id 	= (int)$this->encrypt->decode($this->uri->segment(3));
		$this->_current_branch_id 	= (int)$this->encrypt->decode(get_cookie('branch'));
		$this->_current_user 		= (int)$this->encrypt->decode(get_cookie('temp'));
		$this->_current_date 		= date("Y-m-d h:i:s");
	}

	public function get_stock_delivery_transaction_info()
	{
		$response 		= array();
		$branch_id 		= 0;

		$response['error'] 	= '';
		$response['detail_error'] 	= ''; 

		$query_head = "SELECT CONCAT('SD',SH.`reference_number`) AS 'reference_number', COALESCE(DATE(SH.`entry_date`),'') AS 'entry_date', 
					SH.`memo`, SH.`branch_id`, SH.`to_branchid`, SUM(SD.`recv_quantity`) AS 'total_qty', SH.`delivery_type`, SH.`is_used`
					FROM `stock_delivery_head` AS SH
					LEFT JOIN stock_delivery_detail AS SD ON SD.`headid` = SH.`id`
					WHERE SH.`is_show` = ".\Constants\DELIVERY_CONST::ACTIVE." AND SH.`id` = ?
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
			$response['is_editable'] 		= ($row->total_qty == 0 && $row->branch_id == $this->_current_branch_id) ? TRUE : FALSE;
			$response['is_saved'] 			= $row->is_used == 1 ? TRUE : FALSE;
			$response['own_branch'] 		= $this->_current_branch_id;
		}


		$query_detail = "SELECT SD.`id`, SD.`product_id`, COALESCE(P.`material_code`,'') AS 'material_code', 
						COALESCE(P.`description`,'') AS 'product', SD.`quantity`, SD.`memo`, SD.`is_for_branch`, 
						SD.`recv_quantity` AS 'receiveqty', SD.`description`, P.`type`
					FROM `stock_delivery_detail` AS SD
					LEFT JOIN `stock_delivery_head` AS SH ON SD.`headid` = SH.`id` AND SH.`is_show` = ".\Constants\DELIVERY_CONST::ACTIVE."
					LEFT JOIN `product` AS P ON P.`id` = SD.`product_id` AND P.`is_show` = ".\Constants\DELIVERY_CONST::ACTIVE."
					WHERE SD.`headid` = ?";

		$result_detail = $this->db->query($query_detail,$this->_delivery_head_id);

		if ($result_detail->num_rows() == 0) 
			$response['detail_error'] = 'No item delivery details found!';
		else
		{
			$i = 0;
			foreach ($result_detail->result() as $row) 
			{
				$break_line = $row->type == \Constants\DELIVERY_CONST::STOCK ? '' : '<br/>';
				$response['detail'][$i][] = array($this->encrypt->encode($row->id));
				$response['detail'][$i][] = array($row->is_for_branch);
				$response['detail'][$i][] = array($i+1);
				$response['detail'][$i][] = array($row->product, $row->product_id, $row->type, $break_line, $row->description);
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
		$query_data 	= array($this->_delivery_head_id, $qty, $product_id, $memo, $istransfer, $description);

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
		$request_detail_id = 0;

		$old_delivery_detail_result = $this->get_stock_delivery_detail_info($delivery_detail_id);

		if ($old_delivery_detail_result->num_rows() == 1) 
		{
			$row = $old_delivery_detail_result->row();

			$request_detail_id = $row->request_detail_id;

			if (($request_detail_id != 0) && ($row->product_id != $product_id || $row->is_for_branch != $istransfer)) 
				$request_detail_id = 0;
		}
		else
			throw new Exception($this->_error_message['UNABLE_TO_SELECT_DETAILS']);
			
		$old_delivery_detail_result->free_result();

		$query_data 	= array($qty, $product_id, $memo, $istransfer, $description, $request_detail_id, $delivery_detail_id);

		$query = "UPDATE `stock_delivery_detail`
					SET
					`quantity` = ?,
					`product_id` = ?,
					`memo` = ?,
					`is_for_branch` = ?,
					`description` = ?,
					`request_detail_id` = ?
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

		$query_delivery_head_data = array($entry_date,$memo,$to_branch,$type,$this->_current_user,$this->_current_date,$this->_delivery_head_id); 

		$query_delivery_head = "UPDATE `stock_delivery_head`
					SET
					`entry_date` = ?,
					`memo` = ?,
					`to_branchid` = ?,
					`delivery_type` = ?,
					`is_used` = ".\Constants\DELIVERY_CONST::USED.",
					`last_modified_by` = ?,
					`last_modified_date` = ?
					WHERE `id` = ?;";

		array_push($query,$query_delivery_head);
		array_push($query_data,$query_delivery_head_data);

		if ($type != \Constants\DELIVERY_CONST::BOTH) 
		{
			$type = $type == \Constants\DELIVERY_CONST::SALES ? 0 : 1;

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

	public function search_stock_delivery_list($param, $with_limit = TRUE)
	{
		extract($param);

		$response['rowcnt'] = 0;

		$this->db->select("SH.`id`, COALESCE(B.`name`,'') AS 'from_branch', COALESCE(B2.`name`,'-') AS 'to_branch', 
							CONCAT('SD',SH.`reference_number`) AS 'reference_number',
							COALESCE(DATE(SH.`entry_date`),'') AS 'entry_date', IF(SH.`is_used` = 0, 'Unused', SH.`memo`) AS 'memo',
							COALESCE(SUM(SD.`quantity`), 0) AS 'total_qty', SUM(SD.`quantity` - SD.`recv_quantity`) AS 'remaining_qty',
							CASE 
								WHEN `delivery_type` = ".\Constants\DELIVERY_CONST::BOTH." THEN 'Both'
								WHEN `delivery_type` = ".\Constants\DELIVERY_CONST::SALES." THEN 'Sales'
								WHEN `delivery_type` = ".\Constants\DELIVERY_CONST::TRANSFER." THEN 'Transfer'
								ELSE 'Unused'
							END AS 'delivery_type',
							IF(SH.`is_used` = ".\Constants\DELIVERY_CONST::ACTIVE.",
								COALESCE(CASE 
									WHEN SUM(COALESCE(SD.`recv_quantity`,0)) = 0 THEN 'No Received'
									WHEN SUM(IF(SD.`quantity` - SD.`recv_quantity` < 0, 0, SD.`quantity` - SD.`recv_quantity`)) > 0 THEN 'Incomplete'
									WHEN SUM(SD.`quantity`) - SUM(SD.`recv_quantity`) = 0 THEN 'Complete'
									ELSE 'Excess'
								END,'') 
							, '') AS 'status',
							IF(SH.`is_used` = ".\Constants\DELIVERY_CONST::ACTIVE.",
								COALESCE(CASE 
									WHEN SUM(COALESCE(SD.`recv_quantity`,0)) = 0 THEN ".\Constants\DELIVERY_CONST::NO_RECEIVED."
									WHEN SUM(IF(SD.`quantity` - SD.`recv_quantity` < 0, 0, SD.`quantity` - SD.`recv_quantity`)) > 0 THEN ".\Constants\DELIVERY_CONST::INCOMPLETE."
									WHEN SUM(SD.`quantity`) - SUM(SD.`recv_quantity`) = 0 THEN ".\Constants\DELIVERY_CONST::COMPLETE."
									ELSE ".\Constants\DELIVERY_CONST::EXCESS."
								END,'') 
							, 0) AS 'status_code'")
				->from("stock_delivery_head AS SH")
				->join("stock_delivery_detail AS SD", "SD.`headid` = SH.`id`", "left")
				->join("branch AS B2", "B2.`id` = SH.`to_branchid` AND B2.`is_show` = ".\Constants\DELIVERY_CONST::ACTIVE, "left")
				->join("branch AS B", "B.`id` = SH.`branch_id` AND B.`is_show` = ".\Constants\DELIVERY_CONST::ACTIVE, "left")
				->where("SH.`is_show`", \Constants\DELIVERY_CONST::ACTIVE);

		if (!empty($date_from))
			$this->db->where("SH.`entry_date` >=", $date_from." 00:00:00");

		if (!empty($date_to))
			$this->db->where("SH.`entry_date` <=", $date_to." 23:59:59");

		if ($from_branch != \Constants\DELIVERY_CONST::ALL_OPTION) 
			$this->db->where("SH.`branch_id`", (int)$from_branch);

		if ($to_branch != \Constants\DELIVERY_CONST::ALL_OPTION) 
			$this->db->where("SH.`to_branchid`", (int)$to_branch);

		if (!empty($search_string)) 
			$this->db->like("CONCAT('SD',SH.`reference_number`,' ',SH.`memo`)", $search_string, "both");

		if ($type != \Constants\DELIVERY_CONST::ALL_OPTION) 
			$this->db->where("SH.`delivery_type`", (int)$type);


		switch ($order_by) 
		{
			case \Constants\DELIVERY_CONST::ORDER_BY_REFERENCE:
				$order_field = "SH.`reference_number`";
				break;

			case \Constants\DELIVERY_CONST::ORDER_BY_DATE:
				$order_field = "SH.`entry_date`";
				break;
		}

		$this->db->group_by("SH.`id`")
				->order_by($order_field, $order_type);

		if ($status != \Constants\DELIVERY_CONST::ALL_OPTION)
			$this->db->having("status_code", $status); 

		if ($with_limit) 
		{
			$limit = $row_end - $row_start + 1;
			$this->db->limit((int)$limit, (int)$row_start);
		}
		
		$result = $this->db->get();

		if ($result->num_rows() > 0) 
		{
			$i = 0;
			$response['rowcnt'] = $this->get_stock_delivery_count_by_filter($param);

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
	
	public function get_stock_delivery_count_by_filter($param)
	{
		extract($param);

		$this->db->select("IF(SH.`is_used` = ".\Constants\DELIVERY_CONST::ACTIVE.",
								COALESCE(CASE 
									WHEN SUM(COALESCE(SD.`recv_quantity`,0)) = 0 THEN ".\Constants\DELIVERY_CONST::NO_RECEIVED."
									WHEN SUM(IF(SD.`quantity` - SD.`recv_quantity` < 0, 0, SD.`quantity` - SD.`recv_quantity`)) > 0 THEN ".\Constants\DELIVERY_CONST::INCOMPLETE."
									WHEN SUM(SD.`quantity`) - SUM(SD.`recv_quantity`) = 0 THEN ".\Constants\DELIVERY_CONST::COMPLETE."
									ELSE ".\Constants\DELIVERY_CONST::EXCESS."
								END,'') 
							, 0) AS 'status_code'")
				->from("stock_delivery_head AS SH")
				->join("stock_delivery_detail AS SD", "SD.`headid` = SH.`id`", "left")
				->where("SH.`is_show`", \Constants\DELIVERY_CONST::ACTIVE);

		if (!empty($date_from))
			$this->db->where("SH.`entry_date` >=", $date_from." 00:00:00");

		if (!empty($date_to))
			$this->db->where("SH.`entry_date` <=", $date_to." 23:59:59");

		if ($from_branch != \Constants\DELIVERY_CONST::ALL_OPTION) 
			$this->db->where("SH.`branch_id`", (int)$from_branch);

		if ($to_branch != \Constants\DELIVERY_CONST::ALL_OPTION) 
			$this->db->where("SH.`to_branchid`", (int)$to_branch);

		if (!empty($search_string)) 
			$this->db->like("CONCAT('SD',SH.`reference_number`,' ',SH.`memo`)", $search_string, "both");

		if ($type != \Constants\DELIVERY_CONST::ALL_OPTION) 
			$this->db->where("SH.`delivery_type`", (int)$type);

		$this->db->group_by("SH.`id`");

		if ($status != \Constants\DELIVERY_CONST::ALL_OPTION)
			$this->db->having("status_code", $status);

		return $this->db->count_all_results();
	}

	public function delete_stock_delivery_head($param)
	{
		extract($param);

		$delivery_head_id = $this->encrypt->decode($head_id);

		$response = array();
		$response['error'] = '';

		$query 	= "SELECT SUM(D.`recv_quantity`) AS 'total_received', H.`branch_id` 
						FROM stock_delivery_head AS H
						LEFT JOIN stock_delivery_detail AS D ON D.`headid` = H.`id` 
						WHERE H.`id` = ? AND H.`is_show` = ".\Constants\DELIVERY_CONST::ACTIVE;

		$result = $this->db->query($query,$delivery_head_id);
		$row 	= $result->row();

		if ($row->total_received > 0) {
			throw new Exception($this->_error_message['HAS_RECEIVED']);
		}

		if ($row->branch_id != $this->_current_branch_id) {
			throw new Exception($this->_error_message['NOT_OWN_BRANCH']);
		}

		$result->free_result();

		$query_data = array($this->_current_date,$this->_current_user,$delivery_head_id);
		$query 	= "UPDATE `stock_delivery_head` 
					SET 
					`is_show` = ".\Constants\DELIVERY_CONST::DELETED.",
					`last_modified_date` = ?,
					`last_modified_by` = ?
					WHERE `id` = ?";

		$result = $this->sql->execute_query($query,$query_data);

		if ($result['error'] != '') 
			throw new Exception($this->_error_message['UNABLE_TO_DELETE_HEAD']);

		return $response;
	}

	public function search_receive_list($param, $search_type, $with_limit = TRUE)
	{
		extract($param);

		$response['rowcnt'] = 0;

		$date_type = $search_type == \Constants\DELIVERY_CONST::FOR_TRANSFER ? 'delivery_receive_date' : 'customer_receive_date';

		$this->db->select("SH.`id`, COALESCE(B.`name`,'') AS 'from_branch', COALESCE(B2.`name`,'-') AS 'to_branch', 
							CONCAT('SD',SH.`reference_number`) AS 'reference_number',
							COALESCE(DATE(SH.`$date_type`),'') AS 'entry_date', SH.`memo`,
							SUM(SD.`quantity`) AS 'total_qty',
							COALESCE(
								CASE 
									WHEN SUM(COALESCE(SD.`recv_quantity`,0)) = 0 THEN 'No Received'
									WHEN SUM(IF(SD.`quantity` - SD.`recv_quantity` < 0, 0, SD.`quantity` - SD.`recv_quantity`)) > 0 THEN 'Incomplete'
									WHEN SUM(SD.`quantity`) - SUM(SD.`recv_quantity`) = 0 THEN 'Complete'
									ELSE 'Excess'
								END,'') AS 'status',
							COALESCE(
								CASE 
									WHEN SUM(COALESCE(SD.`recv_quantity`,0)) = 0 THEN ".\Constants\DELIVERY_CONST::NO_RECEIVED."
									WHEN SUM(IF(SD.`quantity` - SD.`recv_quantity` < 0, 0, SD.`quantity` - SD.`recv_quantity`)) > 0 THEN ".\Constants\DELIVERY_CONST::INCOMPLETE."
									WHEN SUM(SD.`quantity`) - SUM(SD.`recv_quantity`) = 0 THEN ".\Constants\DELIVERY_CONST::COMPLETE."
									ELSE ".\Constants\DELIVERY_CONST::EXCESS."
						END,'') AS 'status_code'")
				->from("stock_delivery_head AS SH")
				->join("stock_delivery_detail AS SD", "SD.`headid` = SH.`id`", "left")
				->join("branch AS B2", "B2.`id` = SH.`to_branchid` AND B2.`is_show` = ".\Constants\DELIVERY_CONST::ACTIVE, "left")
				->join("branch AS B", "B.`id` = SH.`branch_id` AND B.`is_show` = ".\Constants\DELIVERY_CONST::ACTIVE, "left")
				->where("SH.`is_show`", \Constants\DELIVERY_CONST::ACTIVE)
				->where("SH.`is_used`", \Constants\DELIVERY_CONST::ACTIVE);

		switch ($search_type) 
		{
			case \Constants\DELIVERY_CONST::FOR_TRANSFER:
				$this->db->where_in("SH.`delivery_type`", [\Constants\DELIVERY_CONST::TRANSFER, \Constants\DELIVERY_CONST::BOTH])
						->where("SD.`is_for_branch`", 1);

				if (($to_branch) && $to_branch != \Constants\DELIVERY_CONST::ALL_OPTION) 
					$this->db->where("SH.`to_branchid`", $to_branch);

				break;
			
			case \Constants\DELIVERY_CONST::FOR_CUSTOMER:
				$this->db->where_in("SH.`delivery_type`", [\Constants\DELIVERY_CONST::SALES, \Constants\DELIVERY_CONST::BOTH])
						->where("SD.`is_for_branch`", 0);

				break;
		}

		if (!empty($date_from))
			$this->db->where("SH.`$date_type` >=", $date_from." 00:00:00");

		if (!empty($date_to))
			$this->db->where("SH.`$date_type` <=", $date_to." 23:59:59");

		if ($from_branch != \Constants\DELIVERY_CONST::ALL_OPTION) 
			$this->db->where("SH.`branch_id`", (int)$from_branch);

		if (!empty($search_string)) 
			$this->db->like("CONCAT('SD',SH.`reference_number`,' ',SH.`memo`)", $search_string, "both");

		switch ($order_by) 
		{
			case \Constants\DELIVERY_CONST::ORDER_BY_REFERENCE:
				$order_field = "SH.`reference_number`";
				break;

			case \Constants\DELIVERY_CONST::ORDER_BY_DATE:
				$order_field = "SH.`$date_type`";
				break;
		}

		$this->db->group_by("SH.`id`")
				->order_by($order_field, $order_type);

		if ($status != \Constants\DELIVERY_CONST::ALL_OPTION)
			$this->db->having("status_code", $status); 

		if ($with_limit) 
		{
			$limit = $row_end - $row_start + 1;
			$this->db->limit((int)$limit, (int)$row_start);
		}

		$result = $this->db->get();

		if ($result->num_rows() > 0) 
		{
			$i = 0;
			$response['rowcnt'] = $this->get_receive_list_count_by_filter($param, $search_type);

			foreach ($result->result() as $row) 
			{
				$response['data'][$i][] = array($this->encrypt->encode($row->id));
				$response['data'][$i][] = array($i+1);
				$response['data'][$i][] = array($row->reference_number);
				$response['data'][$i][] = array($row->from_branch);

				if ($search_type == \Constants\DELIVERY_CONST::FOR_TRANSFER)
					$response['data'][$i][] = array($row->to_branch);
				
				$response['data'][$i][] = array($row->entry_date);
				$response['data'][$i][] = array($row->memo);
				$response['data'][$i][] = array($row->total_qty);
				$response['data'][$i][] = array($row->status);

				$i++;
			}
		}

		$result->free_result();

		return $response;
	}

	public function get_receive_list_count_by_filter($param, $search_type)
	{
		extract($param);

		$date_type = $search_type == \Constants\DELIVERY_CONST::FOR_TRANSFER ? 'delivery_receive_date' : 'customer_receive_date';

		$this->db->select("COALESCE(
								CASE 
									WHEN SUM(COALESCE(SD.`recv_quantity`,0)) = 0 THEN ".\Constants\DELIVERY_CONST::NO_RECEIVED."
									WHEN SUM(IF(SD.`quantity` - SD.`recv_quantity` < 0, 0, SD.`quantity` - SD.`recv_quantity`)) > 0 THEN ".\Constants\DELIVERY_CONST::INCOMPLETE."
									WHEN SUM(SD.`quantity`) - SUM(SD.`recv_quantity`) = 0 THEN ".\Constants\DELIVERY_CONST::COMPLETE."
									ELSE ".\Constants\DELIVERY_CONST::EXCESS."
							END,'') AS 'status_code'")
				->from("stock_delivery_head AS SH")
				->join("stock_delivery_detail AS SD", "SD.`headid` = SH.`id`", "left")
				->where("SH.`is_show`", \Constants\DELIVERY_CONST::ACTIVE)
				->where("SH.`is_used`", \Constants\DELIVERY_CONST::ACTIVE);

		switch ($search_type) 
		{
			case \Constants\DELIVERY_CONST::FOR_TRANSFER:
				$this->db->where_in("SH.`delivery_type`", [\Constants\DELIVERY_CONST::TRANSFER, \Constants\DELIVERY_CONST::BOTH])
						->where("SD.`is_for_branch`", 1);

				if (($to_branch) && $to_branch != \Constants\DELIVERY_CONST::ALL_OPTION) 
					$this->db->where("SH.`to_branchid`", $to_branch);

				break;
			
			case \Constants\DELIVERY_CONST::FOR_CUSTOMER:
				$this->db->where_in("SH.`delivery_type`", [\Constants\DELIVERY_CONST::SALES, \Constants\DELIVERY_CONST::BOTH])
						->where("SD.`is_for_branch`", 0);

				break;
		}

		if (!empty($date_from))
			$this->db->where("SH.`$date_type` >=", $date_from." 00:00:00");

		if (!empty($date_to))
			$this->db->where("SH.`$date_type` <=", $date_to." 23:59:59");

		if ($from_branch != \Constants\DELIVERY_CONST::ALL_OPTION) 
			$this->db->where("SH.`branch_id`", (int)$from_branch);

		if (!empty($search_string)) 
			$this->db->like("CONCAT('SD',SH.`reference_number`,' ',SH.`memo`)", $search_string, "both");

		$this->db->group_by("SH.`id`");

		if ($status != \Constants\DELIVERY_CONST::ALL_OPTION)
			$this->db->having("status_code", $status); 

		return $this->db->count_all_results();
	}

	public function get_receive_details($receive_type)
	{
		$response 		= array();

		$response['error'] 	= '';
		$response['detail_error'] 	= ''; 

		$receive_date_column 	= "";
		$is_transfer 			= "";

		if ($receive_type == \Constants\DELIVERY_CONST::FOR_TRANSFER) 
		{
			$receive_date_column = "SH.`delivery_receive_date`";
			$is_transfer = 1;
		}
		else
		{
			$receive_date_column = "SH.`customer_receive_date`";
			$is_transfer = 0;
		}

		$query_head_data = array($is_transfer, $this->_delivery_head_id);

		$query_head = "SELECT CONCAT('SD',SH.`reference_number`) AS 'reference_number', COALESCE(DATE(SH.`entry_date`),'') AS 'entry_date', 
					SH.`memo`, SH.`branch_id`, SH.`to_branchid`, SH.`delivery_type`, DATE($receive_date_column) AS 'receive_date',
					SUM(IF(SD.`quantity` - SD.`recv_quantity` < 0, 0, SD.`quantity` - SD.`recv_quantity`)) AS 'remaining_qty', SUM(SD.`recv_quantity`) AS 'recv_quantity'
					FROM `stock_delivery_head` AS SH
					LEFT JOIN `stock_delivery_detail` AS SD ON SD.`headid` = SH.`id` AND SD.`is_for_branch` = ?
					WHERE SH.`is_show` = ".\Constants\DELIVERY_CONST::ACTIVE." AND SH.`id` = ?
					GROUP BY SH.`id`";

		$result_head = $this->db->query($query_head, $query_head_data);

		if ($result_head->num_rows() != 1) 
			throw new Exception($this->_error_message['UNABLE_TO_SELECT_HEAD']);
		else
		{
			$row = $result_head->row();

			$response['reference_number'] 	= $row->reference_number;
			$response['entry_date'] 		= $row->entry_date;
			$response['memo'] 				= $row->memo;

			if ($receive_type == \Constants\DELIVERY_CONST::FOR_TRANSFER)
			{
				$response['to_branchid'] 	= $row->to_branchid;
				$response['is_editable'] 	= $row->to_branchid == $this->_current_branch_id ? TRUE : FALSE;
			}
			else
				$response['is_editable'] 	= $row->branch_id == $this->_current_branch_id ? TRUE : FALSE;
				
			$response['delivery_type'] 		= $row->delivery_type;
			$response['receive_date'] 		= $row->receive_date;
			$response['own_branch'] 		= $this->_current_branch_id;
			$response['transaction_branch'] = $row->branch_id;
			$response['is_incomplete'] 		= $row->remaining_qty > 0 && $row->recv_quantity > 0 ? TRUE : FALSE;
		}

		$query_detail = "SELECT SD.`id`, SD.`product_id`, COALESCE(P.`material_code`,'') AS 'material_code', 
						COALESCE(P.`description`,'') AS 'product', SD.`quantity`, SD.`memo`, SD.`is_for_branch`, 
						SD.`recv_quantity`, SD.`description`, P.`type`, SD.`receive_memo`, SD.`received_by`,
						IF(SD.`recv_quantity` >= SD.`quantity`, 1, 0) AS 'is_checked'
					FROM `stock_delivery_detail` AS SD
					LEFT JOIN `stock_delivery_head` AS SH ON SD.`headid` = SH.`id` AND SH.`is_show` = ".\Constants\DELIVERY_CONST::ACTIVE."
					LEFT JOIN `product` AS P ON P.`id` = SD.`product_id` AND P.`is_show` = ".\Constants\DELIVERY_CONST::ACTIVE."
					WHERE SD.`headid` = ? AND SD.`is_for_branch` = $is_transfer";

		$result_detail = $this->db->query($query_detail,$this->_delivery_head_id);

		if ($result_detail->num_rows() == 0) 
			$response['detail_error'] = 'No receive details found!';
		else
		{
			$i = 0;
			foreach ($result_detail->result() as $row) 
			{
				$break_line = $row->type == \Constants\DELIVERY_CONST::STOCK ? '' : '<br/>';
				$response['detail'][$i][] = array($this->encrypt->encode($row->id));
				$response['detail'][$i][] = array($i+1);
				$response['detail'][$i][] = array($row->product, $row->product_id, $row->type, $break_line,$row->description);
				$response['detail'][$i][] = array($row->material_code);
				$response['detail'][$i][] = array($row->quantity);
				$response['detail'][$i][] = array($row->memo);

				if ($receive_type == \Constants\DELIVERY_CONST::FOR_TRANSFER)
				{
					$response['detail'][$i][] = array($row->received_by);
					$response['detail'][$i][] = array($row->receive_memo);
				}

				$response['detail'][$i][] = array($row->is_checked);
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

	public function update_stock_receive_detail($param)
	{
		extract($param);

		$response = array();

		$response['error'] = '';
		$delivery_detail_id = $this->encrypt->decode($detail_id);
		$query_data 		= array($receiveqty,$note,$receivedby,$delivery_detail_id);

		$query = "UPDATE `stock_delivery_detail`
					SET
					`recv_quantity` = ?,
					`receive_memo` = ?,
					`received_by` = ?
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

		$receive_date_column = $receive_type == \Constants\DELIVERY_CONST::FOR_TRANSFER ? 'delivery_receive_date' : 'customer_receive_date';

		$query_data = array($receive_date,$this->_delivery_head_id);
		$query 	= "UPDATE `stock_delivery_head` 
					SET `$receive_date_column` = ?
					WHERE `id` = ?";

		$result = $this->sql->execute_query($query,$query_data);

		if ($result['error'] != '') 
			throw new Exception($this->_error_message['UNABLE_TO_UPDATE_HEAD']);

		return $response;
	}

	public function get_receive_printout_detail()
	{
		$response = array();

		$response['error'] = '';

		$delivery_id = $this->encrypt->decode($this->session->userdata('delivery_receive'));

		$query_head = "SELECT CONCAT('SD',`reference_number`) AS 'reference_number', 
						DATE(`delivery_receive_date`) AS 'entry_date'
					FROM stock_delivery_head
					WHERE `id` = ?";

		$result_head = $this->db->query($query_head,$delivery_id);
		
		if ($result_head->num_rows() == 1) 
		{
			$row = $result_head->row();

			foreach ($row as $key => $value)
				$response[$key] = $value;
		}
		else
			throw new Exception($this->_error_message['UNABLE_TO_SELECT_HEAD']);
			
		$result_head->free_result();

		$query_detail = "SELECT D.`recv_quantity` AS 'quantity', COALESCE(P.`description`,'-') AS 'product', 
							D.`description`, COALESCE(P.`material_code`,'-') AS 'item_code', D.`received_by`, D.`receive_memo`
							FROM stock_delivery_head AS H
							LEFT JOIN stock_delivery_detail AS D ON D.`headid` = H.`id`
							LEFT JOIN product AS P ON P.`id` = D.`product_id`
							WHERE H.`id` = ? AND D.`is_for_branch` = 1";

		$result_detail = $this->db->query($query_detail,$delivery_id);

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

		$response['title'] = 'RECEIVING SUMMARY';

		return $response;
	}

	public function get_delivery_printout_details()
	{
		$response = array();

		$response['error'] = '';

		$delivery_id 	= $this->encrypt->decode($this->session->userdata('delivery'));
		$print_type 	= $this->session->userdata('print_type');

		$conditions = "";

		if ($print_type != 'both') 
		{
			switch ($print_type) {
				case 'customer':
					$conditions = " AND D.`is_for_branch` = 0";
					break;
				
				case 'transfer':
					$conditions = " AND D.`is_for_branch` = 1";
					break;
			}
		}

		$query_head = "SELECT CONCAT('SD',`reference_number`) AS 'reference_number', 
						DATE(`entry_date`) AS 'entry_date'
					FROM stock_delivery_head
					WHERE `id` = ?";

		$result_head = $this->db->query($query_head,$delivery_id);
		
		if ($result_head->num_rows() == 1) 
		{
			$row = $result_head->row();

			foreach ($row as $key => $value)
				$response[$key] = $value;
		}
		else
			throw new Exception($this->_error_message['UNABLE_TO_SELECT_HEAD']);
			
		$result_head->free_result();

		$query_detail = "SELECT D.`quantity` AS 'quantity', COALESCE(P.`description`,'-') AS 'product', 
							D.`description`, COALESCE(P.`material_code`,'-') AS 'item_code', D.`memo`
							FROM stock_delivery_head AS H
							LEFT JOIN stock_delivery_detail AS D ON D.`headid` = H.`id`
							LEFT JOIN product AS P ON P.`id` = D.`product_id`
							WHERE H.`id` = ? $conditions";

		$result_detail = $this->db->query($query_detail,$delivery_id);

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
			throw new Exception($this->_error_message['NO_ITEMS_TO_PRINT']);

		$result_detail->free_result();

		return $response;
	}

	public function get_customer_receive_printout_details()
	{
		$response = array();

		$response['error'] = '';

		$delivery_id = $this->encrypt->decode($this->session->userdata('customer_receive'));

		$query_head = "SELECT CONCAT('SD',`reference_number`) AS 'reference_number', 
						DATE(`customer_receive_date`) AS 'entry_date', `memo`
					FROM stock_delivery_head
					WHERE `id` = ?";

		$result_head = $this->db->query($query_head,$delivery_id);
		
		if ($result_head->num_rows() == 1) 
		{
			$row = $result_head->row();

			foreach ($row as $key => $value)
				$response[$key] = $value;
		}
		else
			throw new Exception($this->_error_message['UNABLE_TO_SELECT_HEAD']);
			
		$result_head->free_result();

		$query_detail = "SELECT D.`recv_quantity` AS 'quantity', COALESCE(P.`description`,'-') AS 'product', 
							D.`description`, COALESCE(P.`material_code`,'-') AS 'item_code', D.`memo`
							FROM stock_delivery_head AS H
							LEFT JOIN stock_delivery_detail AS D ON D.`headid` = H.`id`
							LEFT JOIN product AS P ON P.`id` = D.`product_id`
							WHERE H.`id` = ? AND D.`is_for_branch` = 0";

		$result_detail = $this->db->query($query_detail,$delivery_id);

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

	public function check_if_transaction_has_product($session_name)
	{
		$this->db->select("D.*")
				->from("stock_delivery_detail AS D")
				->join("stock_delivery_head AS H", "H.`id` = D.`headid`", "left")
				->where("H.`is_show`", \Constants\DELIVERY_CONST::ACTIVE)
				->where("H.`id`", $this->_delivery_head_id);

		switch ($session_name) 
		{
			case 'delivery_receive':
				$this->db->where("D.`is_for_branch`", 1);
				break;
			
			case 'customer_receive':
				$this->db->where("D.`is_for_branch`", 0);
				break;
		}

		$result = $this->db->get();

		return $result;
	}

	public function get_delivery_by_transaction($param)
	{
		extract($param);

		$this->db->select("SH.`id`, COALESCE(B.`name`,'') AS 'from_branch', COALESCE(B2.`name`,'-') AS 'to_branch', 
							CONCAT('SD',SH.`reference_number`) AS 'reference_number',
							COALESCE(DATE(SH.`entry_date`),'') AS 'entry_date', IF(SH.`is_used` = 0, 'Unused', SH.`memo`) AS 'memo',
							COALESCE(SUM(SD.`quantity`),'') AS 'total_qty', SUM(SD.`quantity` - SD.`recv_quantity`) AS 'remaining_qty',
							CASE 
								WHEN `delivery_type` = ".\Constants\DELIVERY_CONST::BOTH." THEN 'Both'
								WHEN `delivery_type` = ".\Constants\DELIVERY_CONST::SALES." THEN 'Sales'
								WHEN `delivery_type` = ".\Constants\DELIVERY_CONST::TRANSFER." THEN 'Transfer'
								ELSE 'Unused'
							END AS 'delivery_type',
							IF(SH.`is_used` = ".\Constants\DELIVERY_CONST::ACTIVE.",
								COALESCE(CASE 
									WHEN SUM(COALESCE(SD.`recv_quantity`,0)) = 0 THEN 'No Received'
									WHEN SUM(IF(SD.`quantity` - SD.`recv_quantity` < 0, 0, SD.`quantity` - SD.`recv_quantity`)) > 0 THEN 'Incomplete'
									WHEN SUM(SD.`quantity`) - SUM(SD.`recv_quantity`) = 0 THEN 'Complete'
									ELSE 'Excess'
								END,'') 
							, '') AS 'status',
							IF(SH.`is_used` = ".\Constants\DELIVERY_CONST::ACTIVE.",
								COALESCE(CASE 
									WHEN SUM(COALESCE(SD.`recv_quantity`,0)) = 0 THEN ".\Constants\DELIVERY_CONST::NO_RECEIVED."
									WHEN SUM(IF(SD.`quantity` - SD.`recv_quantity` < 0, 0, SD.`quantity` - SD.`recv_quantity`)) > 0 THEN ".\Constants\DELIVERY_CONST::INCOMPLETE."
									WHEN SUM(SD.`quantity`) - SUM(SD.`recv_quantity`) = 0 THEN ".\Constants\DELIVERY_CONST::COMPLETE."
									ELSE ".\Constants\DELIVERY_CONST::EXCESS."
								END,'') 
							, 0) AS 'status_code'")
				->from("stock_delivery_head AS SH")
				->join("stock_delivery_detail AS SD", "SD.`headid` = SH.`id`", "left")
				->join("branch AS B", "B.`id` = SH.`branch_id` AND B.`is_show` = ".\Constants\DELIVERY_CONST::ACTIVE, "left")
				->join("branch AS B2", "B2.`id` = SH.`to_branchid` AND B2.`is_show` = ".\Constants\DELIVERY_CONST::ACTIVE, "left")
				->where("SH.`is_show`", \Constants\DELIVERY_CONST::ACTIVE)
				->where("SH.`is_used`", \Constants\DELIVERY_CONST::USED);

		if (!empty($date_from))
			$this->db->where("SH.`entry_date` >=", $date_from.' 00:00:00');

		if (!empty($date_to))
			$this->db->where("SH.`entry_date` <=", $date_to.' 23:59:59');

		if ($from_branch != \Constants\DELIVERY_CONST::ALL_OPTION) 
			$this->db->where("SH.`branch_id`", $from_branch);

		if ($to_branch != \Constants\DELIVERY_CONST::ALL_OPTION) 
			$this->db->where("PH.`to_branchid`", $to_branch);
		
		if (!empty($search_string)) 
			$this->db->like("CONCAT('SD',SH.`reference_number`,' ',SH.`memo`)", $search_string, "both");

		if ($type != \Constants\DELIVERY_CONST::ALL_OPTION) 
			$this->db->where("SH.`delivery_type`", $type);

		$this->db->group_by("SH.`id`");

		if ($status != \Constants\DELIVERY_CONST::ALL_OPTION) 
			$this->db->having("status_code", $status);

		switch ($order_by) 
		{
			case \Constants\DELIVERY_CONST::ORDER_BY_REFERENCE:
				$order_field = "SH.`reference_number`";
				break;

			case \Constants\DELIVERY_CONST::ORDER_BY_DATE:
				$order_field = "SH.`entry_date`";
				break;
		}

		$this->db->order_by($order_field, $order_type);
		
		$result = $this->db->get();

		return $result;
	}

	public function get_delivery_receive_by_transaction($param, $search_type)
	{
		extract($param);

		$date_type = $search_type == 'TRANSFER' ? 'delivery_receive_date' : 'customer_receive_date';

		$this->db->select("SH.`id`, COALESCE(B.`name`,'') AS 'from_branch', COALESCE(B2.`name`,'-') AS 'to_branch', 
							CONCAT('SD',SH.`reference_number`) AS 'reference_number',
							COALESCE(DATE(SH.`$date_type`),'') AS 'entry_date', SH.`memo`,
							SUM(SD.`quantity`) AS 'total_qty',
							COALESCE(
								CASE 
									WHEN SUM(COALESCE(SD.`recv_quantity`,0)) = 0 THEN 'No Received'
									WHEN SUM(IF(SD.`quantity` - SD.`recv_quantity` < 0, 0, SD.`quantity` - SD.`recv_quantity`)) > 0 THEN 'Incomplete'
									WHEN SUM(SD.`quantity`) - SUM(SD.`recv_quantity`) = 0 THEN 'Complete'
									ELSE 'Excess'
								END,'') AS 'status',
							COALESCE(
								CASE 
									WHEN SUM(COALESCE(SD.`recv_quantity`,0)) = 0 THEN ".\Constants\DELIVERY_CONST::NO_RECEIVED."
									WHEN SUM(IF(SD.`quantity` - SD.`recv_quantity` < 0, 0, SD.`quantity` - SD.`recv_quantity`)) > 0 THEN ".\Constants\DELIVERY_CONST::INCOMPLETE."
									WHEN SUM(SD.`quantity`) - SUM(SD.`recv_quantity`) = 0 THEN ".\Constants\DELIVERY_CONST::COMPLETE."
									ELSE ".\Constants\DELIVERY_CONST::EXCESS."
								END,'') AS 'status_code'")
				->from("stock_delivery_head AS SH")
				->join("stock_delivery_detail AS SD", "SD.`headid` = SH.`id`", "left")
				->join("branch AS B", "B.`id` = SH.`branch_id` AND B.`is_show` = ".\Constants\DELIVERY_CONST::ACTIVE, "left")
				->join("branch AS B2", "B2.`id` = SH.`to_branchid` AND B2.`is_show` = ".\Constants\DELIVERY_CONST::ACTIVE, "left")
				->where("SH.`is_show`", \Constants\DELIVERY_CONST::ACTIVE)
				->where("SH.`is_used`", \Constants\DELIVERY_CONST::USED);

		switch ($search_type) 
		{
			case 'TRANSFER':
				$this->db->where_in(array(\Constants\DELIVERY_CONST::TRANSFER, \Constants\DELIVERY_CONST::BOTH));
				$this->db->where("SD.`is_for_branch`", 1);

				if (($to_branch) && $to_branch != \Constants\DELIVERY_CONST::ALL_OPTION) 
					$this->db->where("SH.`to_branchid`", $to_branch);

				break;
			
			case 'CUSTOMER':
				$this->db->where_in(array(\Constants\DELIVERY_CONST::SALES, \Constants\DELIVERY_CONST::BOTH));
				$this->db->where("SD.`is_for_branch`", 0);
				break;
		}

		if (!empty($date_from))
			$this->db->where("SH.`$date_type` >=", $date_from.' 00:00:00');

		if (!empty($date_to))
			$this->db->where("SH.`$date_type` <=", $date_to.' 23:59:59');

		if ($from_branch != \Constants\DELIVERY_CONST::ALL_OPTION) 
			$this->db->where("SH.`branch_id`", $from_branch);

		if (!empty($search_string)) 
			$this->db->like("CONCAT('SD',SH.`reference_number`,' ',SH.`memo`)", $search_string, "both");

		$this->db->group_by("SH.`id`");

		if ($status != \Constants\DELIVERY_CONST::ALL_OPTION) 
			$this->db->having("status_code", $status);

		switch ($order_by) 
		{
			case \Constants\DELIVERY_CONST::ORDER_BY_REFERENCE:
				$order_field = "SH.`reference_number`";
				break;

			case \Constants\DELIVERY_CONST::ORDER_BY_DATE:
				$order_field = "SH.`entry_date`";
				break;
		}

		$this->db->order_by($order_field, $order_type);
		
		$result = $this->db->get();

		return $result;
	}

	public function get_stock_delivery_detail_info($id)
	{
		$this->db->select("*")
				->from("stock_delivery_detail")
				->where("`id`", $id);

		$result = $this->db->get();

		return $result;
	}

	public function get_stock_delivery_head_info($id)
	{
		$this->db->select("*")
				->from("stock_delivery_head")
				->where("`id`", $id);

		$result = $this->db->get();

		return $result;
	}

	public function get_customer_receive_with_remaining($delivery_head_id)
	{
		$this->db->select("`id`, `quantity`, `product_id`, `description`, `memo` AS 'customer_name', `recv_quantity`")
				->from("stock_delivery_detail")
				->where("`recv_quantity` < `quantity`")
				->where("`recv_quantity` <> 0")
				->where("`headid`", $delivery_head_id)
				->where("`is_for_branch`", 0)
				->where("`memo` <> ''")
				->order_by("`memo`", "ASC");

		$result = $this->db->get();

		return $result;
	}

	public function transfer_remaining_details_to_new_return($customer_receive_detail, $customer_return_detail)
	{
		for ($i=0; $i < count($customer_receive_detail); $i++) 
		{
			$this->db->where("`id`", $customer_receive_detail[$i]['id']);
			$this->db->update("stock_delivery_detail", $customer_receive_detail[$i]['detail']);
		}

		$this->db->insert_batch("return_detail", $customer_return_detail);
	}

	public function check_if_transaction_is_incomplete()
	{
		$is_incomplete = TRUE;

		$this->db->select("SUM(IF(SD.`quantity` - SD.`recv_quantity` < 0, 0, SD.`quantity` - SD.`recv_quantity`)) AS 'remaining_qty'")
				->from("stock_delivery_head AS SH")
				->join("stock_delivery_detail AS SD", "SD.`headid` = SH.`id` AND SD.`is_for_branch` = 0", "left")
				->where("SH.`is_show`", \Constants\DELIVERY_CONST::ACTIVE)
				->where("SH.`id`", $this->_delivery_head_id)
				->group_by("SH.`id`");
		
		$result = $this->db->get();

		$row = $result->row();

		if ($row->remaining_qty <= 0)
			$is_incomplete = FALSE;

		$result->free_result();

		return $is_incomplete;
	}

	public function get_stock_delivery_count_with_no_receive()
	{
		$count = 0;

		$this->db->select("SUM(COALESCE(`recv_quantity`,0)) AS 'qty_delivered'")
				->from("stock_delivery_head AS SH")
				->join("stock_delivery_detail AS SD", "SD.`headid` = SH.`id`", "left")
				->where("SH.`is_show`", \Constants\ADJUST_CONST::ACTIVE)
				->where("SH.`to_branchid`", $this->_current_branch_id)
				->group_by("SH.`id`")
				->having("qty_delivered", 0);
		
		$result = $this->db->get();

		$count = $result->num_rows();

		$result->free_result();

		return $count;
	}
}
