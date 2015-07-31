<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class PurchaseOrder_Model extends CI_Model {

	private $_purchase_head_id = 0;
	private $_current_branch_id = 0;
	private $_current_user = 0;
	private $_current_date = '';
	private $_error_message = array('UNABLE_TO_INSERT' => 'Unable to insert purchase detail!',
									'UNABLE_TO_UPDATE' => 'Unable to update purchase detail!',
									'UNABLE_TO_UPDATE_HEAD' => 'Unable to update purchase head!',
									'UNABLE_TO_SELECT_HEAD' => 'Unable to get purchase head details!',
									'UNABLE_TO_SELECT_DETAILS' => 'Unable to get purchase details!',
									'UNABLE_TO_DELETE' => 'Unable to delete purchase detail!',
									'UNABLE_TO_DELETE_HEAD' => 'Unable to delete purchase head!',
									'HAS_RECEIVED' => 'PO can only be deleted if purchase status is no received!',
									'NOT_OWN_BRANCH' => 'Cannot delete purchase order entry of other branches!');

	/**
	 * Load Encrypt Class for encryption, cookie and constants
	 */
	public function __construct() 
	{
		parent::__construct();

		$this->load->constant('purchase_const');

		$this->_purchase_head_id 	= $this->encrypt->decode($this->uri->segment(3));
		$this->_current_branch_id 	= $this->encrypt->decode(get_cookie('branch'));
		$this->_current_user 		= $this->encrypt->decode(get_cookie('temp'));
		$this->_current_date 		= date("Y-m-d h:i:s");
	}

	public function get_purchaseorder_details()
	{
		$response 		= array();
		$response['error'] = '';

		$response['detail_error'] = '';

		$query_head = "SELECT CONCAT('PO',PH.`reference_number`) AS 'reference_number', COALESCE(DATE(PH.`entry_date`),'') AS 'entry_date', 
					PH.`memo`, PH.`branch_id`, PH.`supplier`, PH.`for_branchid`,
					PH.`is_imported`, PH.`is_used`, SUM(IF(PD.`quantity` - PD.`recv_quantity` < 0, 0, PD.`quantity` - PD.`recv_quantity`)) AS 'remaining_qty', 
					SUM(PD.`recv_quantity`) AS 'recv_quantity'
					FROM `purchase_head` AS PH
					LEFT JOIN purchase_detail AS PD ON PD.`headid` = PH.`id`
					WHERE PH.`is_show` = ".\Constants\PURCHASE_CONST::ACTIVE." AND PH.`id` = ?
					GROUP BY PH.`id`";

		$result_head = $this->db->query($query_head,$this->_purchase_head_id);

		if ($result_head->num_rows() != 1) 
			throw new Exception($this->_error_message['UNABLE_TO_SELECT_HEAD']);
		else
		{
			$row = $result_head->row();

			$response['reference_number'] 	= $row->reference_number;
			$response['entry_date'] 		= $row->entry_date;
			$response['memo'] 				= $row->memo;
			$response['supplier_name'] 		= $row->supplier;
			$response['orderfor'] 			= $row->for_branchid;
			$response['is_imported'] 		= $row->is_imported;
			$response['is_editable'] 		= $row->recv_quantity == 0 ? (($row->branch_id == $this->_current_branch_id) ? TRUE : FALSE) : FALSE;
			$response['is_saved'] 			= $row->is_used == 1 ? TRUE : FALSE;
			$response['is_incomplete'] 		= $row->remaining_qty > 0 && $row->recv_quantity > 0 ? TRUE : FALSE;
			$response['transaction_branch'] = $row->branch_id;
		}

		$query_detail = "SELECT PD.`id`, PD.`product_id`, COALESCE(P.`material_code`,'') AS 'material_code', 
						COALESCE(P.`description`,'') AS 'product', PD.`quantity`, PD.`memo`, PD.`description`, P.`type`, PD.`recv_quantity`
					FROM `purchase_detail` AS PD
					LEFT JOIN `purchase_head` AS PH ON PD.`headid` = PH.`id` AND PH.`is_show` = ".\Constants\PURCHASE_CONST::ACTIVE."
					LEFT JOIN `product` AS P ON P.`id` = PD.`product_id` AND P.`is_show` = ".\Constants\PURCHASE_CONST::ACTIVE."
					WHERE PD.`headid` = ?";

		$result_detail = $this->db->query($query_detail,$this->_purchase_head_id);

		if ($result_detail->num_rows() == 0) 
			$response['detail_error'] = $this->_error_message['UNABLE_TO_SELECT_DETAILS'];
		else
		{
			$i = 0;
			foreach ($result_detail->result() as $row) 
			{
				$break_line = $row->type == \Constants\PURCHASE_CONST::STOCK ? '' : '<br/>';
				$response['detail'][$i][] = array($this->encrypt->encode($row->id));
				$response['detail'][$i][] = array('');
				$response['detail'][$i][] = array($i+1);
				$response['detail'][$i][] = array($row->product, $row->product_id, $row->type, $break_line, $row->description);
				$response['detail'][$i][] = array($row->material_code);
				$response['detail'][$i][] = array($row->quantity);
				$response['detail'][$i][] = array($row->recv_quantity);
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

	public function insert_purchaseorder_detail($param)
	{
		extract($param);

		$response = array();
		$response['error'] = '';

		$query_data 		= array($this->_purchase_head_id,$qty,$product_id,$memo,$description);

		$query = "INSERT INTO `purchase_detail`
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

	public function update_purchaseorder_detail($param)
	{
		extract($param);

		$response = array();
		$response['error'] = '';

		$purchase_detail_id = $this->encrypt->decode($detail_id);
		$query_data 		= array($qty,$product_id,$memo,$description,$purchase_detail_id);

		$query = "UPDATE `purchase_detail`
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
	
	public function delete_purchaseorder_detail($param)
	{
		extract($param);

		$response = array();
		$response['error'] 	= '';

		$purchase_detail_id 	= $this->encrypt->decode($detail_id);

		$query = "DELETE FROM `purchase_detail` WHERE `id` = ?";

		$result = $this->sql->execute_query($query,$purchase_detail_id);

		if ($result['error'] != '') 
			throw new Exception($this->_error_message['UNABLE_TO_DELETE']);

		return $response;

	}

	public function update_purchaseorder_head($param)
	{
		extract($param);

		$response = array();

		$response['error'] 	= '';
		$entry_date 		= $entry_date.' '.date('h:i:s');
		$query_data 		= array($entry_date,$memo,$supplier_name,$orderfor,$is_imported,$this->_current_user,$this->_current_date,$this->_purchase_head_id);

		$query = "UPDATE `purchase_head`
					SET
					`entry_date` = ?,
					`memo` = ?,
					`supplier` = ?,
					`for_branchid` = ?,
					`is_imported` = ?,
					`is_used` = ".\Constants\PURCHASE_CONST::USED.",
					`last_modified_by` = ?,
					`last_modified_date` = ?
					WHERE `id` = ?;";

		$result = $this->sql->execute_query($query,$query_data);

		if ($result['error'] != '') 
			throw new Exception($this->_error_message['UNABLE_TO_UPDATE_HEAD']);

		return $response;
	}

	public function search_purchaseorder_list($param)
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
			$conditions .= " AND PH.`entry_date` >= ?";
			array_push($query_data,$date_from.' 00:00:00');
		}

		if (!empty($date_to))
		{
			$conditions .= " AND PH.`entry_date` <= ?";
			array_push($query_data,$date_to.' 23:59:59');
		}

		if ($branch != \Constants\PURCHASE_CONST::ALL_OPTION) 
		{
			$conditions .= " AND PH.`branch_id` = ?";
			array_push($query_data,$branch);
		}

		if ($for_branch != \Constants\PURCHASE_CONST::ALL_OPTION) 
		{
			$conditions .= " AND PH.`for_branchid` = ?";
			array_push($query_data,$for_branch);
		}
	
		if (!empty($search_string)) 
		{
			$conditions .= " AND CONCAT('PO',PH.`reference_number`,' ',PH.`memo`,' ',PH.`supplier`) LIKE ?";
			array_push($query_data,'%'.$search_string.'%');
		}

		if ($type != \Constants\PURCHASE_CONST::ALL_OPTION) 
		{
			switch ($type) 
			{
				case \Constants\PURCHASE_CONST::IMPORTED:
					$conditions .= " AND PH.`is_imported` = ".\Constants\PURCHASE_CONST::IMPORTED;
					break;
				
				case \Constants\PURCHASE_CONST::LOCAL:
					$conditions .= " AND PH.`is_imported` = ".\Constants\PURCHASE_CONST::LOCAL;
					break;
			}
		}

		switch ($order_by) 
		{
			case \Constants\PURCHASE_CONST::ORDER_BY_REFERENCE:
				$order_field = "PH.`reference_number`";
				break;
			
			case \Constants\PURCHASE_CONST::ORDER_BY_LOCATION:
				$order_field = "B.`name`";
				break;

			case \Constants\PURCHASE_CONST::ORDER_BY_DATE:
				$order_field = "PH.`entry_date`";
				break;

			case \Constants\PURCHASE_CONST::ORDER_BY_SUPPLIER:
				$order_field = "PH.`supplier`";
				break;
		}

		if ($status != \Constants\PURCHASE_CONST::ALL_OPTION) 
		{
			$having = "HAVING status_code = ?";
			array_push($query_data,$status);
		}

		$query = "SELECT PH.`id`, COALESCE(B.`name`,'') AS 'location', COALESCE(B2.`name`,'') AS 'forbranch', 
					CONCAT('PO',PH.`reference_number`) AS 'reference_number', PH.`supplier`,
					COALESCE(DATE(PH.`entry_date`),'') AS 'entry_date', IF(PH.`is_used` = 0, 'Unused', PH.`memo`) AS 'memo',
					COALESCE(SUM(PD.`quantity`),0) AS 'total_qty', PH.`is_used`,
					IF(PH.`is_used` = ".\Constants\PURCHASE_CONST::ACTIVE.",
						COALESCE(CASE 
							WHEN SUM(COALESCE(PD.`recv_quantity`,0)) = 0 THEN 'No Received'
							WHEN SUM(IF(PD.`quantity` - PD.`recv_quantity` < 0, 0, PD.`quantity` - PD.`recv_quantity`)) > 0 THEN 'Incomplete'
							WHEN SUM(PD.`quantity`) - SUM(PD.`recv_quantity`) = 0 THEN 'Complete'
							ELSE 'Excess'
						END,'') 
					, '') AS 'status',
					IF(PH.`is_used` = ".\Constants\PURCHASE_CONST::ACTIVE.",
						COALESCE(CASE 
							WHEN SUM(COALESCE(PD.`recv_quantity`,0)) = 0 THEN ".\Constants\PURCHASE_CONST::NO_RECEIVED."
							WHEN SUM(IF(PD.`quantity` - PD.`recv_quantity` < 0, 0, PD.`quantity` - PD.`recv_quantity`)) > 0 THEN ".\Constants\PURCHASE_CONST::INCOMPLETE."
							WHEN SUM(PD.`quantity`) - SUM(PD.`recv_quantity`) = 0 THEN ".\Constants\PURCHASE_CONST::COMPLETE."
							ELSE ".\Constants\PURCHASE_CONST::EXCESS."
						END,'') 
					, 0) AS 'status_code',
					CASE 
						WHEN PH.`is_imported` = ".\Constants\PURCHASE_CONST::IMPORTED." THEN 'Imported'
						WHEN PH.`is_imported` = ".\Constants\PURCHASE_CONST::LOCAL." THEN 'Local'
						ELSE ''
					END AS 'type'
					FROM purchase_head AS PH
					LEFT JOIN purchase_detail AS PD ON PD.`headid` = PH.`id`
					LEFT JOIN branch AS B ON B.`id` = PH.`branch_id` AND B.`is_show` = ".\Constants\PURCHASE_CONST::ACTIVE."
					LEFT JOIN branch AS B2 ON B2.`id` = PH.`for_branchid` AND B2.`is_show` = ".\Constants\PURCHASE_CONST::ACTIVE."
					WHERE PH.`is_show` = ".\Constants\PURCHASE_CONST::ACTIVE." $conditions
					GROUP BY PH.`id`
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
				$response['data'][$i][] = array($row->forbranch);
				$response['data'][$i][] = array($row->reference_number);
				$response['data'][$i][] = array($row->type);
				$response['data'][$i][] = array($row->entry_date);
				$response['data'][$i][] = array($row->supplier);
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
	
	public function delete_purchaseorder_head($param)
	{
		extract($param);

		$purchase_head_id = $this->encrypt->decode($head_id);

		$response = array();
		$response['error'] = '';

		$query 	= "SELECT SUM(D.`recv_quantity`) AS 'total_received', H.`branch_id` 
						FROM purchase_head AS H
						LEFT JOIN purchase_detail AS D ON D.`headid` = H.`id` 
						WHERE H.`id` = ? AND H.`is_show` = ".\Constants\PURCHASE_CONST::ACTIVE;

		$result = $this->db->query($query,$purchase_head_id);
		$row 	= $result->row();

		if ($row->total_received > 0) {
			throw new Exception($this->_error_message['HAS_RECEIVED']);
		}

		if ($row->branch_id != $this->_current_branch_id) {
			throw new Exception($this->_error_message['NOT_OWN_BRANCH']);
		}

		$result->free_result();

		$query_data = array($this->_current_date,$this->_current_user,$purchase_head_id);
		$query 	= "UPDATE `purchase_head` 
					SET 
					`is_show` = ".\Constants\PURCHASE_CONST::DELETED.",
					`last_modified_date` = ?,
					`last_modified_by` = ?
					WHERE `id` = ?";

		$result = $this->sql->execute_query($query,$query_data);

		if ($result['error'] != '') 
			throw new Exception($this->_error_message['UNABLE_TO_DELETE_HEAD']);

		return $response;
	}

	public function get_purchase_order_printout_details()
	{
		$response = array();

		$response['error'] = '';

		$purchase_id = $this->encrypt->decode($this->session->userdata('purchase_order'));

		$query_head = "SELECT CONCAT('PO',H.`reference_number`) AS 'reference_number', 
						DATE(H.`entry_date`) AS 'entry_date', H.`supplier`, H.`memo`, COALESCE(B.`name`,'') AS 'for_branch'
					FROM purchase_head AS H
					LEFT JOIN branch AS B ON B.`id` = H.`for_branchid` AND B.`is_show` = ".\Constants\PURCHASE_CONST::ACTIVE."
					WHERE H.`id` = ?";

		$result_head = $this->db->query($query_head,$purchase_id);
		
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
							FROM purchase_head AS H
							LEFT JOIN purchase_detail AS D ON D.`headid` = H.`id`
							LEFT JOIN product AS P ON P.`id` = D.`product_id`
							WHERE H.`id` = ?";

		$result_detail = $this->db->query($query_detail,$purchase_id);

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

	public function check_if_transaction_has_product()
	{
		$this->db->select("D.*")
				->from("purchase_detail AS D")
				->join("purchase_head AS H", "H.`id` = D.`headid`", "left")
				->where("H.`is_show`", \Constants\PURCHASE_CONST::ACTIVE)
				->where("H.`id`", $this->_purchase_head_id);

		$result = $this->db->get();

		return $result;
	}

	public function get_purchase_by_transaction($param)
	{
		extract($param);

		$this->db->select("PH.`id`, COALESCE(B.`name`,'') AS 'location', COALESCE(B2.`name`,'') AS 'forbranch', 
							CONCAT('PO',PH.`reference_number`) AS 'reference_number', PH.`supplier`,
							COALESCE(DATE(PH.`entry_date`),'') AS 'entry_date', IF(PH.`is_used` = 0, 'Unused', PH.`memo`) AS 'memo',
							COALESCE(SUM(PD.`quantity`),0) AS 'total_qty', PH.`is_used`,
							IF(PH.`is_used` = ".\Constants\PURCHASE_CONST::ACTIVE.",
								COALESCE(CASE 
									WHEN SUM(COALESCE(PD.`recv_quantity`,0)) = 0 THEN 'No Received'
									WHEN SUM(IF(PD.`quantity` - PD.`recv_quantity` < 0, 0, PD.`quantity` - PD.`recv_quantity`)) > 0 THEN 'Incomplete'
									WHEN SUM(PD.`quantity`) - SUM(PD.`recv_quantity`) = 0 THEN 'Complete'
									ELSE 'Excess'
								END,'') 
							, '') AS 'status',
							IF(PH.`is_used` = ".\Constants\PURCHASE_CONST::ACTIVE.",
								COALESCE(CASE 
									WHEN SUM(COALESCE(PD.`recv_quantity`,0)) = 0 THEN ".\Constants\PURCHASE_CONST::NO_RECEIVED."
									WHEN SUM(IF(PD.`quantity` - PD.`recv_quantity` < 0, 0, PD.`quantity` - PD.`recv_quantity`)) > 0 THEN ".\Constants\PURCHASE_CONST::INCOMPLETE."
									WHEN SUM(PD.`quantity`) - SUM(PD.`recv_quantity`) = 0 THEN ".\Constants\PURCHASE_CONST::COMPLETE."
									ELSE ".\Constants\PURCHASE_CONST::EXCESS."
								END,'') 
							, 0) AS 'status_code',
							CASE 
								WHEN PH.`is_imported` = ".\Constants\PURCHASE_CONST::IMPORTED." THEN 'Imported'
								WHEN PH.`is_imported` = ".\Constants\PURCHASE_CONST::LOCAL." THEN 'Local'
								ELSE ''
							END AS 'type'")
				->from("purchase_head AS PH")
				->join("purchase_detail AS PD", "PD.`headid` = PH.`id`", "left")
				->join("branch AS B", "B.`id` = PH.`branch_id` AND B.`is_show` = ".\Constants\PURCHASE_CONST::ACTIVE, "left")
				->join("branch AS B2", "B2.`id` = PH.`for_branchid` AND B2.`is_show` = ".\Constants\PURCHASE_CONST::ACTIVE, "left")
				->where("PH.`is_show`", \Constants\PURCHASE_CONST::ACTIVE)
				->where("PH.`is_used`", \Constants\PURCHASE_CONST::USED);


		if (!empty($date_from))
			$this->db->where("PH.`entry_date` >=", $date_from.' 00:00:00');

		if (!empty($date_to))
			$this->db->where("PH.`entry_date` <=", $date_to.' 23:59:59');

		if ($branch != \Constants\PURCHASE_CONST::ALL_OPTION) 
			$this->db->where("PH.`branch_id`", $branch);

		if ($for_branch != \Constants\PURCHASE_CONST::ALL_OPTION) 
			$this->db->where("PH.`for_branchid`", $for_branch);
	
		if (!empty($search_string)) 
			$this->db->like("CONCAT('PO',PH.`reference_number`,' ',PH.`memo`,' ',PH.`supplier`)", $search_string, "both");

		if ($type != \Constants\PURCHASE_CONST::ALL_OPTION) 
			$this->db->where("PH.`is_imported`", \Constants\PURCHASE_CONST::IMPORTED);

		$this->db->group_by("PH.`id`");

		if ($status != \Constants\PURCHASE_CONST::ALL_OPTION) 
			$this->db->having("status_code", $status);

		switch ($order_by) 
		{
			case \Constants\PURCHASE_CONST::ORDER_BY_REFERENCE:
				$order_field = "PH.`reference_number`";
				break;
			
			case \Constants\PURCHASE_CONST::ORDER_BY_LOCATION:
				$order_field = "B.`name`";
				break;

			case \Constants\PURCHASE_CONST::ORDER_BY_DATE:
				$order_field = "PH.`entry_date`";
				break;

			case \Constants\PURCHASE_CONST::ORDER_BY_SUPPLIER:
				$order_field = "PH.`supplier`";
				break;
		}

		$this->db->order_by($order_field, $order_type);
		
		$result = $this->db->get();

		return $result;
	}

	public function get_purchase_order_details_with_remaining($selected_purchase_detail_id)
	{
		$this->db->select("`id`, `quantity`, `product_id`, `description`, `memo`, `recv_quantity`")
				->from("purchase_detail")
				->where("`recv_quantity` < `quantity`")
				->where_in("`id`", $selected_purchase_detail_id);

		$result = $this->db->get();

		return $result;
	}

	public function transfer_remaining_details_to_new_po($old_purchase_detail, $new_purchase_detail)
	{
		$purchase_detail_ids = array();

		for ($i=0; $i < count($old_purchase_detail); $i++) 
		{
			$this->db->where("`id`", $old_purchase_detail[$i]['id']);
			$this->db->update("purchase_detail", $old_purchase_detail[$i]['detail']);

			array_push($purchase_detail_ids, $old_purchase_detail[$i]['id']);
		}

		$this->db->where_in("`id`", $purchase_detail_ids)
					->where("`recv_quantity`", 0);
		$this->db->delete("purchase_detail");

		$this->db->insert_batch("purchase_detail", $new_purchase_detail);
	}
}
