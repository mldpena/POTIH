<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Request_Model extends CI_Model {

	private $_request_head_id = 0;
	private $_current_branch_id = 0;
	private $_current_user = 0;
	private $_current_date = '';
	private $_error_message = array('UNABLE_TO_INSERT' => 'Unable to insert request detail!',
									'UNABLE_TO_UPDATE' => 'Unable to update request detail!',
									'UNABLE_TO_UPDATE_HEAD' => 'Unable to update request head!',
									'UNABLE_TO_SELECT_HEAD' => 'Unable to get request head details!',
									'UNABLE_TO_SELECT_DETAILS' => 'Unable to get request details!',
									'UNABLE_TO_DELETE' => 'Unable to delete request detail!',
									'UNABLE_TO_DELETE_HEAD' => 'Unable to delete request head!',
									'HAS_DELIVERED' => 'Item Request can only be deleted if request status is no received!',
									'NOT_OWN_BRANCH' => 'Cannot delete item request entry of other branches!',
									'NO_ITEMS_TO_PRINT' => 'No items to print!');

	/**
	 * Load Encrypt Class for encryption, cookie and constants
	 */
	public function __construct() 
	{
		parent::__construct();

		$this->load->constant('request_const');

		$this->_request_head_id 	= $this->encrypt->decode($this->uri->segment(3));
		$this->_current_branch_id 	= $this->encrypt->decode(get_cookie('branch'));
		$this->_current_user 		= $this->encrypt->decode(get_cookie('temp'));
		$this->_current_date 		= date("Y-m-d h:i:s");
	}

	public function get_stock_request_details()
	{
		$response 		= array();
		$branch_id 		= 0;

		$response['error'] 	= '';
		$response['detail_error'] 	= ''; 
		$response['delivery_reference_numbers'] = '';

		$query_head = "SELECT CONCAT('SR',SH.`reference_number`) AS 'reference_number', COALESCE(DATE(SH.`entry_date`),'') AS 'entry_date', 
					SH.`memo`, SH.`branch_id`, SH.`request_to_branchid`, SUM(SD.`qty_delivered`) AS 'qty_delivered', SH.`is_used`,
					SUM(IF(SD.`quantity` - SD.`qty_delivered` < 0, 0, SD.`quantity` - SD.`qty_delivered`)) AS 'remaining_qty'
					FROM `stock_request_head` AS SH
					LEFT JOIN stock_request_detail AS SD ON SD.`headid` = SH.`id`
					WHERE SH.`is_show` = ".\Constants\REQUEST_CONST::ACTIVE." AND SH.`id` = ?
					GROUP BY SH.`id`";

		$result_head = $this->db->query($query_head,$this->_request_head_id);

		if ($result_head->num_rows() != 1) 
			throw new Exception($this->_error_message['UNABLE_TO_SELECT_HEAD']);
		else
		{
			$row = $result_head->row();

			$response['reference_number'] 	= $row->reference_number;
			$response['entry_date'] 		= $row->entry_date;
			$response['memo'] 				= $row->memo;
			$response['to_branchid'] 		= $row->request_to_branchid;
			$response['is_editable'] 		= ($row->qty_delivered == 0 && $row->branch_id == $this->_current_branch_id) ? TRUE : FALSE;
			$response['is_saved'] 			= $row->is_used;
			$response['own_branch'] 		= $this->_current_branch_id;
			$response['is_incomplete'] 		= $row->remaining_qty > 0 && $row->qty_delivered > 0 ? TRUE : FALSE;
			$response['transaction_branch'] = $row->branch_id;
		}


		$query_detail = "SELECT SD.`id`, SD.`product_id`, COALESCE(P.`material_code`,'') AS 'material_code', 
						COALESCE(P.`description`,'') AS 'product', SD.`quantity`, SD.`memo`,
						SD.`qty_delivered` AS 'qty_delivered', SD.`description`, P.`type`
					FROM `stock_request_detail` AS SD
					LEFT JOIN `stock_request_head` AS SH ON SD.`headid` = SH.`id` AND SH.`is_show` = ".\Constants\REQUEST_CONST::ACTIVE."
					LEFT JOIN `product` AS P ON P.`id` = SD.`product_id` AND P.`is_show` = ".\Constants\REQUEST_CONST::ACTIVE."
					WHERE SD.`headid` = ?";

		$result_detail = $this->db->query($query_detail,$this->_request_head_id);

		if ($result_detail->num_rows() == 0) 
			$response['detail_error'] = 'No item delivery details found!';
		else
		{
			$i = 0;
			foreach ($result_detail->result() as $row) 
			{
				$break_line = $row->type == \Constants\REQUEST_CONST::STOCK ? '' : '<br/>';
				$response['detail'][$i][] = array($this->encrypt->encode($row->id));
				$response['detail'][$i][] = array('');
				$response['detail'][$i][] = array($i+1);
				$response['detail'][$i][] = array($row->product, $row->product_id, $row->type, $break_line, $row->description);
				$response['detail'][$i][] = array($row->material_code);
				$response['detail'][$i][] = array($row->quantity);
				$response['detail'][$i][] = array($row->qty_delivered);
				$response['detail'][$i][] = array($row->memo);
				$response['detail'][$i][] = array('');
				$response['detail'][$i][] = array('');
				$i++;
			}
		}

		$result_head->free_result();
		$result_detail->free_result();

		$this->db->select("COALESCE(GROUP_CONCAT(DISTINCT CONCAT('SD',SH.`reference_number`)),'') AS 'delivery_reference_numbers'")
				->from("stock_request_head AS SRH")
				->join("stock_request_detail AS SRD", "SRD.`headid` = SRH.`id`", "left")
				->join("stock_delivery_detail AS SD", "SD.`request_detail_id` = SRD.`id`", "left")
				->join("stock_delivery_head AS SH", "SH.`id` = SD.`headid`", "left")
				->where("SRH.`is_show`", \Constants\REQUEST_CONST::ACTIVE)
				->where("SH.`is_show`", \Constants\REQUEST_CONST::ACTIVE)
				->where("SRH.`id`", $this->_request_head_id);

		$result = $this->db->get();

		if ($result->num_rows() == 1) 
		{
			$row = $result->row();
			$response['delivery_reference_numbers'] = $row->delivery_reference_numbers;
		}

		$result->free_result();

		return $response;
	}

	public function insert_stock_request_detail($param)
	{
		extract($param);

		$response = array();

		$response['error'] = '';
		$query_data = array($this->_request_head_id,$qty,$product_id,$memo,$description);

		$query = "INSERT INTO `stock_request_detail`
					(`headid`,
					`quantity`,
					`product_id`,
					`memo`,
					`description`)
					VALUES
					(?,?,?,?,?);";

		$result = $this->sql->execute_query($query,$query_data);

		if ($result['error'] != '') 
			throw new Exception($this->_error_message['UNABLE_TO_INSERT']);
		else
			$response['id'] = $result['id'];

		return $response;
	}

	public function update_stock_request_detail($param)
	{
		extract($param);

		$response = array();

		$response['error'] = '';
		$request_detail_id = $this->encrypt->decode($detail_id);
		$query_data 		= array($qty,$product_id,$memo,$description,$request_detail_id);

		$query = "UPDATE `stock_request_detail`
					SET
					`quantity` = ?,
					`product_id` = ?,
					`memo` = ?,
					`description` = ?
					WHERE `id` = ?;";

		$result = $this->sql->execute_query($query,$query_data);

		if ($result['error'] != '') 
			throw new Exception($this->_error_message['UNABLE_TO_UPDATE']);

		return $response;
	}
	
	public function delete_stock_request_detail($param)
	{
		extract($param);

		$response = array();

		$response['error'] 	= '';
		$request_detail_id 	= $this->encrypt->decode($detail_id);

		$query = "DELETE FROM `stock_request_detail` WHERE `id` = ?";

		$result = $this->sql->execute_query($query,$request_detail_id);

		if ($result['error'] != '') 
			throw new Exception($this->_error_message['UNABLE_TO_DELETE']);

		return $response;

	}

	public function update_stock_request_head($param)
	{
		extract($param);

		$response = array();

		$response['error'] 	= '';
		$entry_date 		= $entry_date.' '.date('h:i:s');

		$query_data 		= array();
		$query 				= array();

		$query_data = array($entry_date,$memo,$to_branch,$this->_current_user,$this->_current_date,$this->_request_head_id); 

		$query = "UPDATE `stock_request_head`
					SET
					`entry_date` = ?,
					`memo` = ?,
					`request_to_branchid` = ?,
					`is_used` = ".\Constants\REQUEST_CONST::USED.",
					`last_modified_by` = ?,
					`last_modified_date` = ?
					WHERE `id` = ?;";

		$result = $this->sql->execute_query($query,$query_data);

		if ($result['error'] != '') 
			throw new Exception($this->_error_message['UNABLE_TO_UPDATE_HEAD']);

		return $response;
	}

	public function search_request_list($param)
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

		if ($from_branch != \Constants\REQUEST_CONST::ALL_OPTION) 
		{
			$conditions .= " AND SH.`branch_id` = ?";
			array_push($query_data,$from_branch);
		}

		if ($to_branch != \Constants\REQUEST_CONST::ALL_OPTION) 
		{
			$conditions .= " AND SH.`request_to_branchid` = ?";
			array_push($query_data,$to_branch);
		}
	
		if (!empty($search_string)) 
		{
			$conditions .= " AND CONCAT('SR',SH.`reference_number`,' ',SH.`memo`) LIKE ?";
			array_push($query_data,'%'.$search_string.'%');
		}

		switch ($order_by) 
		{
			case \Constants\REQUEST_CONST::ORDER_BY_REFERENCE:
				$order_field = "SH.`reference_number`";
				break;

			case \Constants\REQUEST_CONST::ORDER_BY_DATE:
				$order_field = "SH.`entry_date`";
				break;
		}

		if ($status != \Constants\REQUEST_CONST::ALL_OPTION) 
		{
			$having = "HAVING status_code = ?";
			array_push($query_data,$status);
		}

		$query = "SELECT SH.`id`, COALESCE(B.`name`,'') AS 'from_branch', COALESCE(B2.`name`,'-') AS 'to_branch', 
					CONCAT('SD',SH.`reference_number`) AS 'reference_number',
					COALESCE(DATE(SH.`entry_date`),'') AS 'entry_date', IF(SH.`is_used` = 0, 'Unused', SH.`memo`) AS 'memo',
					COALESCE(SUM(SD.`quantity`),'') AS 'total_qty', SUM(SD.`quantity` - SD.`qty_delivered`) AS 'remaining_qty',
					IF(SH.`is_used` = ".\Constants\REQUEST_CONST::ACTIVE.",
						COALESCE(CASE 
							WHEN SUM(COALESCE(SD.`qty_delivered`,0)) = 0 THEN 'No Received'
							WHEN SUM(IF(SD.`quantity` - SD.`qty_delivered` < 0, 0, SD.`quantity` - SD.`qty_delivered`)) > 0 THEN 'Incomplete'
							WHEN SUM(SD.`quantity`) - SUM(SD.`qty_delivered`) = 0 THEN 'Complete'
							ELSE 'Excess'
						END,'') 
					, '') AS 'status',
					IF(SH.`is_used` = ".\Constants\REQUEST_CONST::ACTIVE.",
						COALESCE(CASE 
							WHEN SUM(COALESCE(SD.`qty_delivered`,0)) = 0 THEN ".\Constants\REQUEST_CONST::NO_RECEIVED."
							WHEN SUM(IF(SD.`quantity` - SD.`qty_delivered` < 0, 0, SD.`quantity` - SD.`qty_delivered`)) > 0 THEN ".\Constants\REQUEST_CONST::INCOMPLETE."
							WHEN SUM(SD.`quantity`) - SUM(SD.`qty_delivered`) = 0 THEN ".\Constants\REQUEST_CONST::COMPLETE."
							ELSE ".\Constants\REQUEST_CONST::EXCESS."
						END,'') 
					, 0) AS 'status_code'
					FROM stock_request_head AS SH
					LEFT JOIN stock_request_detail AS SD ON SD.`headid` = SH.`id`
					LEFT JOIN branch AS B ON B.`id` = SH.`branch_id` AND B.`is_show` = ".\Constants\REQUEST_CONST::ACTIVE."
					LEFT JOIN branch AS B2 ON B2.`id` = SH.`request_to_branchid` AND B2.`is_show` = ".\Constants\REQUEST_CONST::ACTIVE."
					WHERE SH.`is_show` = ".\Constants\REQUEST_CONST::ACTIVE." $conditions
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
	
	public function delete_stock_request_head($param)
	{
		extract($param);

		$request_head_id = $this->encrypt->decode($head_id);

		$response = array();
		$response['error'] = '';

		$query 	= "SELECT SUM(D.`qty_delivered`) AS 'total_delivered', H.`branch_id` 
						FROM stock_request_head AS H
						LEFT JOIN stock_request_detail AS D ON D.`headid` = H.`id` 
						WHERE H.`id` = ? AND H.`is_show` = ".\Constants\REQUEST_CONST::ACTIVE;

		$result = $this->db->query($query,$request_head_id);
		$row 	= $result->row();

		if ($row->total_delivered > 0)
			throw new Exception($this->_error_message['HAS_DELIVERED']);

		if ($row->branch_id != $this->_current_branch_id)
			throw new Exception($this->_error_message['NOT_OWN_BRANCH']);

		$result->free_result();

		$query_data = array($this->_current_date,$this->_current_user,$request_head_id);
		$query 	= "UPDATE `stock_request_head` 
					SET 
					`is_show` = ".\Constants\REQUEST_CONST::DELETED.",
					`last_modified_date` = ?,
					`last_modified_by` = ?
					WHERE `id` = ?";

		$result = $this->sql->execute_query($query,$query_data);

		if ($result['error'] != '') 
			throw new Exception($this->_error_message['UNABLE_TO_DELETE_HEAD']);

		return $response;
	}

	public function get_stock_request_head_info($id)
	{
		$this->db->select("*")
				->from("stock_request_head")
				->where("`id`", $id);

		$result = $this->db->get();

		return $result;
	}

	public function check_if_transaction_has_product()
	{
		$this->db->select("D.*")
				->from("stock_request_detail AS D")
				->join("stock_request_head AS H", "H.`id` = D.`headid`", "left")
				->where("H.`is_show`", \Constants\REQUEST_CONST::ACTIVE)
				->where("H.`id`", $this->_request_head_id);

		$result = $this->db->get();

		return $result;
	}

	public function get_stock_request_details_with_remaining($selected_request_detail_id)
	{
		$this->db->select("`id`, `quantity`, `product_id`, `description`, `memo`, `qty_delivered`")
				->from("stock_request_detail")
				->where("`qty_delivered` < `quantity`")
				->where_in("`id`", $selected_request_detail_id);

		$result = $this->db->get();

		return $result;
	}

	public function create_delivery_from_remaining_request_detail($new_stock_delivery_detail)
	{
		$this->db->insert_batch("stock_delivery_detail", $new_stock_delivery_detail);
	}

	public function get_stock_request_count_with_no_receive()
	{
		$count = 0;

		$this->db->select("SUM(COALESCE(`qty_delivered`,0)) AS 'qty_delivered'")
				->from("stock_request_head AS SH")
				->join("stock_request_detail AS SD", "SD.`headid` = SH.`id`", "left")
				->where("SH.`is_show`", \Constants\ADJUST_CONST::ACTIVE)
				->where("SH.`request_to_branchid`", $this->_current_branch_id)
				->group_by("SH.`id`")
				->having("qty_delivered", 0);
		
		$result = $this->db->get();

		$count = $result->num_rows();

		$result->free_result();

		return $count;
	}
}
