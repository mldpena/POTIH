<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class PurchaseReturn_Model extends CI_Model {

	private $_purchase_return_head_id = 0;
	private $_current_branch_id = 0;
	private $_current_user = 0;
	private $_current_date = '';
	private $_error_message = array('UNABLE_TO_INSERT' => 'Unable to insert purchase return detail!',
									'UNABLE_TO_UPDATE' => 'Unable to update purchase return detail!',
									'UNABLE_TO_UPDATE_HEAD' => 'Unable to update purchase return head!',
									'UNABLE_TO_SELECT_HEAD' => 'Unable to get purchase return head details!',
									'UNABLE_TO_SELECT_DETAILS' => 'Unable to get purchase return details!',
									'UNABLE_TO_DELETE' => 'Unable to delete purchase return detail!',
									'UNABLE_TO_DELETE_HEAD' => 'Unable to delete purchase return head!',
									'NOT_OWN_BRANCH' => 'Cannot delete purchase return entry of other branches!');

	/**
	 * Load Encrypt Class for encryption, cookie and constants
	 */
	public function __construct() 
	{
		parent::__construct();

		$this->load->constant('purchase_return_const');

		$this->_purchase_return_head_id = (int)$this->encrypt->decode($this->uri->segment(3));
		$this->_current_branch_id 	= (int)$this->encrypt->decode(get_cookie('branch'));
		$this->_current_user 		= (int)$this->encrypt->decode(get_cookie('temp'));
		$this->_current_date 		= date("Y-m-d h:i:s");
	}

	public function get_purchasereturn_details()
	{
		$response 		= array();

		$branch_id 		= 0;

		$response['error'] 	= '';
		$response['detail_error'] 	= ''; 

		$query_head = "SELECT CONCAT('PR',`reference_number`) AS 'reference_number', COALESCE(DATE(`entry_date`),'') AS 'entry_date', 
					`memo`, `branch_id`, `supplier`, `is_used`
					FROM `purchase_return_head`
					WHERE `is_show` = ".\Constants\PURCHASE_RETURN_CONST::ACTIVE." AND `id` = ?
					GROUP BY `id`";

		$result_head = $this->db->query($query_head,$this->_purchase_return_head_id);

		if ($result_head->num_rows() != 1) 
			throw new Exception($this->_error_message['UNABLE_TO_SELECT_HEAD']);
		else
		{
			$row = $result_head->row();

			$response['reference_number'] 	= $row->reference_number;
			$response['entry_date'] 		= $row->entry_date;
			$response['memo'] 				= $row->memo;
			$response['supplier_name'] 		= $row->supplier;
			$response['is_editable'] 		= $row->branch_id == $this->_current_branch_id ? TRUE : FALSE;
			$response['is_saved'] 			= $row->is_used == 1 ? TRUE : FALSE;
		}

		$query_detail = "SELECT PD.`id`, PD.`product_id`, COALESCE(P.`material_code`,'') AS 'material_code', 
						COALESCE(P.`description`,'') AS 'product', PD.`quantity`, PD.`memo`, PD.`description`, P.`type`
					FROM `purchase_return_detail` AS PD
					LEFT JOIN `purchase_return_head` AS PH ON PD.`headid` = PH.`id` AND PH.`is_show` = ".\Constants\PURCHASE_RETURN_CONST::ACTIVE."
					LEFT JOIN `product` AS P ON P.`id` = PD.`product_id` AND P.`is_show` = ".\Constants\PURCHASE_RETURN_CONST::ACTIVE."
					WHERE PD.`headid` = ?";

		$result_detail = $this->db->query($query_detail,$this->_purchase_return_head_id);

		if ($result_detail->num_rows() == 0) 
			$response['detail_error'] = 'No purchase return details found!';
		else
		{
			$i = 0;
			foreach ($result_detail->result() as $row) 
			{
				$break_line = $row->type == \Constants\PURCHASE_RETURN_CONST::STOCK ? '' : '<br/>';
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

	public function insert_purchasereturn_detail($param)
	{
		extract($param);

		$response = array();

		$response['error'] = '';
		$query_data 		= array($this->_purchase_return_head_id,$qty,$product_id,$memo,$description);

		$query = "INSERT INTO `purchase_return_detail`
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

	public function update_purchasereturn_detail($param)
	{
		extract($param);

		$response = array();

		$response['error'] = '';
		$purchase_detail_id = $this->encrypt->decode($detail_id);
		$query_data 		= array($qty,$product_id,$memo,$description,$purchase_detail_id);

		$query = "UPDATE `purchase_return_detail`
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
	
	public function delete_purchasereturn_detail($param)
	{
		extract($param);

		$response = array();

		$response['error'] 	= '';
		$purchase_detail_id 	= $this->encrypt->decode($detail_id);

		$query = "DELETE FROM `purchase_return_detail` WHERE `id` = ?";

		$result = $this->sql->execute_query($query,$purchase_detail_id);

		if ($result['error'] != '') 
			throw new Exception($this->_error_message['UNABLE_TO_DELETE']);

		return $response;

	}

	public function update_purchasereturn_head($param)
	{
		extract($param);

		$response = array();

		$response['error'] 	= '';
		$entry_date 		= $entry_date.' '.date('h:i:s');
		$query_data 		= array($entry_date,$memo,$supplier_name,$this->_current_user,$this->_current_date,$this->_purchase_return_head_id);

		$query = "UPDATE `purchase_return_head`
					SET
					`entry_date` = ?,
					`memo` = ?,
					`supplier` = ?,
					`is_used` = ".\Constants\PURCHASE_RETURN_CONST::USED.",
					`last_modified_by` = ?,
					`last_modified_date` = ?
					WHERE `id` = ?;";

		$result = $this->sql->execute_query($query,$query_data);

		if ($result['error'] != '') 
			throw new Exception($this->_error_message['UNABLE_TO_UPDATE_HEAD']);

		return $response;
	}

	public function search_purchasereturn_list($param)
	{
		extract($param);

		$limit = $row_end - $row_start + 1;
		
		$response['rowcnt'] = 0;

		$this->db->select("PH.`id`, COALESCE(B.`name`,'') AS 'location',
							CONCAT('PR',PH.`reference_number`) AS 'reference_number', PH.`supplier`,
							COALESCE(DATE(PH.`entry_date`),'') AS 'entry_date', IF(PH.`is_used` = 0, 'Unused', PH.`memo`) AS 'memo',
							COALESCE(SUM(PD.`quantity`),'') AS 'total_qty'")
					->from("purchase_return_head AS PH")
					->join("purchase_return_detail AS PD", "PD.`headid` = PH.`id`", "left")
					->join("branch AS B", "B.`id` = PH.`branch_id` AND B.`is_show` = ".\Constants\PURCHASE_RETURN_CONST::ACTIVE, "left")
					->where("PH.`is_show`", \Constants\PURCHASE_RETURN_CONST::ACTIVE);

		if (!empty($date_from))
			$this->db->where("PH.`entry_date` >=", $date_from." 00:00:00");

		if (!empty($date_to))
			$this->db->where("PH.`entry_date` <=", $date_to." 23:59:59");

		if ($branch != \Constants\PURCHASE_RETURN_CONST::ALL_OPTION) 
			$this->db->where("PH.`branch_id`", (int)$branch);

		if (!empty($search_string)) 
			$this->db->like("CONCAT('PR',PH.`reference_number`,' ',PH.`memo`,' ',PH.`supplier`)", $search_string, "both");

		switch ($order_by) 
		{
			case \Constants\PURCHASE_RETURN_CONST::ORDER_BY_REFERENCE:
				$order_field = "PH.`reference_number`";
				break;
			
			case \Constants\PURCHASE_RETURN_CONST::ORDER_BY_LOCATION:
				$order_field = "B.`name`";
				break;

			case \Constants\PURCHASE_RETURN_CONST::ORDER_BY_DATE:
				$order_field = "PH.`entry_date`";
				break;

			case \Constants\PURCHASE_RETURN_CONST::ORDER_BY_SUPPLIER:
				$order_field = "PH.`supplier`";
				break;
		}

		$this->db->group_by("PH.`id`")
				->order_by($order_field, $order_type)
				->limit((int)$limit, (int)$row_start);

		$result = $this->db->get();

		if ($result->num_rows() > 0) 
		{
			$i = 0;
			$response['rowcnt'] = $this->get_purchasereturn_count_by_filter($param);

			foreach ($result->result() as $row) 
			{
				$response['data'][$i][] = array($this->encrypt->encode($row->id));
				$response['data'][$i][] = array($row_start + $i + 1);
				$response['data'][$i][] = array($row->location);
				$response['data'][$i][] = array($row->reference_number);
				$response['data'][$i][] = array($row->entry_date);
				$response['data'][$i][] = array($row->supplier);
				$response['data'][$i][] = array($row->memo);
				$response['data'][$i][] = array($row->total_qty);
				$response['data'][$i][] = array('');
				$i++;
			}
		}

		$result->free_result();
		
		return $response;
	}
	
	public function get_purchasereturn_count_by_filter($param)
	{
		extract($param);

		$this->db->from("purchase_return_head AS PH")
					->join("purchase_return_detail AS PD", "PD.`headid` = PH.`id`", "left")
					->where("PH.`is_show`", \Constants\PURCHASE_RETURN_CONST::ACTIVE);

		if (!empty($date_from))
			$this->db->where("PH.`entry_date` >=", $date_from." 00:00:00");

		if (!empty($date_to))
			$this->db->where("PH.`entry_date` <=", $date_to." 23:59:59");

		if ($branch != \Constants\PURCHASE_RETURN_CONST::ALL_OPTION) 
			$this->db->where("PH.`branch_id`", (int)$branch);

		if (!empty($search_string)) 
			$this->db->like("CONCAT('PR',PH.`reference_number`,' ',PH.`memo`,' ',PH.`supplier`)", $search_string, "both");

		$this->db->group_by("PH.`id`");

		return $this->db->count_all_results();
	}

	public function delete_purchasereturn_head($param)
	{
		extract($param);

		$purchase_return_id = $this->encrypt->decode($head_id);

		$response = array();
		$response['error'] = '';

		$query 	= "SELECT `branch_id` FROM purchase_return_head WHERE id = ?";
		$result = $this->db->query($query,$purchase_return_id);
		$row 	= $result->row();

		if ($row->branch_id != $this->_current_branch_id) {
			throw new Exception($this->_error_message['NOT_OWN_BRANCH']);
		}

		$result->free_result();

		$query_data = array($this->_current_date,$this->_current_user,$purchase_return_id);
		$query 	= "UPDATE `purchase_return_head` 
					SET 
					`is_show` = ".\Constants\PURCHASE_RETURN_CONST::DELETED.",
					`last_modified_date` = ?,
					`last_modified_by` = ?
					WHERE `id` = ?";

		$result = $this->sql->execute_query($query,$query_data);

		if ($result['error'] != '') 
			throw new Exception($this->_error_message['UNABLE_TO_DELETE_HEAD']);

		return $response;
	}

	public function get_purchase_return_printout_details()
	{
		$response = array();

		$response['error'] = '';

		$purchase_return_id = $this->encrypt->decode($this->session->userdata('purchase_return'));

		$query_head = "SELECT CONCAT('PR',H.`reference_number`) AS 'reference_number', 
						DATE(H.`entry_date`) AS 'entry_date', H.`supplier`, H.`memo`
					FROM purchase_return_head AS H
					WHERE H.`id` = ?";

		$result_head = $this->db->query($query_head,$purchase_return_id);
		
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
							FROM purchase_return_head AS H
							LEFT JOIN purchase_return_detail AS D ON D.`headid` = H.`id`
							LEFT JOIN product AS P ON P.`id` = D.`product_id`
							WHERE H.`id` = ?";

		$result_detail = $this->db->query($query_detail,$purchase_return_id);

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
				->from("purchase_return_detail AS D")
				->join("purchase_return_head AS H", "H.`id` = D.`headid`", "left")
				->where("H.`is_show`", \Constants\PURCHASE_RETURN_CONST::ACTIVE)
				->where("H.`id`", $this->_purchase_return_head_id);

		$result = $this->db->get();

		return $result;
	}

	public function get_purchase_return_by_transaction($param)
	{
		extract($param);

		$this->db->select("PH.`id`, COALESCE(B.`name`,'') AS 'location',
						CONCAT('PR',PH.`reference_number`) AS 'reference_number', PH.`supplier`,
						COALESCE(DATE(PH.`entry_date`),'') AS 'entry_date', IF(PH.`is_used` = 0, 'Unused', PH.`memo`) AS 'memo',
						COALESCE(SUM(PD.`quantity`),'') AS 'total_qty'")
				->from("purchase_return_head AS PH")
				->join("purchase_return_detail AS PD", "PD.`headid` = PH.`id`", "left")
				->join("branch AS B", "B.`id` = PH.`branch_id` AND B.`is_show` = ".\Constants\PURCHASE_RETURN_CONST::ACTIVE, "left")
				->where("PH.`is_show`", \Constants\PURCHASE_RETURN_CONST::ACTIVE)
				->where("PH.`is_used`", \Constants\PURCHASE_RETURN_CONST::USED);

		if (!empty($date_from))
			$this->db->where("PH.`entry_date` >=", $date_from.' 00:00:00');

		if (!empty($date_to))
			$this->db->where("PH.`entry_date` <=", $date_to.' 23:59:59');

		if ($branch != \Constants\PURCHASE_RETURN_CONST::ALL_OPTION) 
			$this->db->where("PH.`branch_id`", $branch);

		if (!empty($search_string)) 
			$this->db->like("CONCAT('PR',PH.`reference_number`,' ',PH.`memo`,' ',PH.`supplier`)", $search_string, "both");

		switch ($order_by) 
		{
			case \Constants\PURCHASE_RETURN_CONST::ORDER_BY_REFERENCE:
				$order_field = "PH.`reference_number`";
				break;
			
			case \Constants\PURCHASE_RETURN_CONST::ORDER_BY_LOCATION:
				$order_field = "B.`name`";
				break;

			case \Constants\PURCHASE_RETURN_CONST::ORDER_BY_DATE:
				$order_field = "PH.`entry_date`";
				break;

			case \Constants\PURCHASE_RETURN_CONST::ORDER_BY_SUPPLIER:
				$order_field = "PH.`supplier`";
				break;
		}

		$this->db->order_by($order_field, $order_type);
		
		$result = $this->db->get();

		return $result;
	}
}
