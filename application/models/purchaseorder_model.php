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
					PH.`memo`, PH.`branch_id`, PH.`supplier`, PH.`for_branchid`, SUM(PD.`recv_quantity`) AS 'total_qty', PH.`is_imported`, PH.`is_used`
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
			$response['is_editable'] 		= $row->total_qty == 0 ? (($row->branch_id == $this->_current_branch_id) ? TRUE : FALSE) : FALSE;
			$response['is_saved'] 			= $row->is_used == 1 ? TRUE : FALSE;
		}

		$query_detail = "SELECT PD.`id`, PD.`product_id`, COALESCE(P.`material_code`,'') AS 'material_code', 
						COALESCE(P.`description`,'') AS 'product', PD.`quantity`, PD.`memo`, PD.`description`, P.`type`
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
				$break_line = empty($row->description) ? '' : '<br/>';
				$response['detail'][$i][] = array($this->encrypt->encode($row->id));
				$response['detail'][$i][] = array($i+1);
				$response['detail'][$i][] = array($row->product, $row->product_id, $row->type, $break_line, $row->description);
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
			switch ($status) 
			{
				case \Constants\PURCHASE_CONST::INCOMPLETE:
					$having = "HAVING remaining_qty < total_qty AND remaining_qty > 0";
					break;
				
				case \Constants\PURCHASE_CONST::COMPLETE:
					$having = "HAVING remaining_qty = 0";
					break;

				case \Constants\PURCHASE_CONST::NO_RECEIVED:
					$having = "HAVING remaining_qty = total_qty";
					break;

				case \Constants\PURCHASE_CONST::EXCESS:
					$having = "HAVING remaining_qty < 0";
					break;
			}

			$having .= " AND is_used = 1";
		}

		$query = "SELECT PH.`id`, COALESCE(B.`name`,'') AS 'location', COALESCE(B2.`name`,'') AS 'forbranch', 
					CONCAT('PO',PH.`reference_number`) AS 'reference_number', PH.`supplier`,
					COALESCE(DATE(PH.`entry_date`),'') AS 'entry_date', IF(PH.`is_used` = 0, 'Unused', PH.`memo`) AS 'memo',
					COALESCE(SUM(PD.`quantity`),0) AS 'total_qty', COALESCE(SUM(PD.`quantity` - PD.`recv_quantity`),0) AS 'remaining_qty', PH.`is_used`,
					COALESCE(CASE 
						WHEN SUM(PD.`recv_quantity`) = SUM(PD.`quantity`) THEN 'Complete'
						WHEN SUM(PD.`recv_quantity` ) > SUM(PD.`quantity`) THEN 'Excess'
						WHEN SUM(PD.`recv_quantity`) > 0 THEN 'Incomplete'
						WHEN SUM(PD.`recv_quantity`) = 0 THEN 'No Received'
					END,'') AS 'status',
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
				$response['data'][$i][] = array($row->remaining_qty);
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
}
