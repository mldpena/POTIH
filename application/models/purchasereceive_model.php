<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class PurchaseReceive_Model extends CI_Model {

	private $_receive_head_id = 0;
	private $_current_branch_id = 0;
	private $_current_user = 0;
	private $_current_date = '';
	private $_error_message = array('UNABLE_TO_INSERT' => 'Unable to insert purchase receive detail!',
									'UNABLE_TO_UPDATE' => 'Unable to update purchase receive detail!',
									'UNABLE_TO_UPDATE_HEAD' => 'Unable to update purchase receive head!',
									'UNABLE_TO_SELECT_HEAD' => 'Unable to get purchase receive head details!',
									'UNABLE_TO_SELECT_DETAILS' => 'Unable to get purchase details!',
									'UNABLE_TO_DELETE' => 'Unable to delete purchase receive detail!',
									'UNABLE_TO_DELETE_HEAD' => 'Unable to delete purchase receive head!',
									'PURCHASE_NOT_FOUND' => 'No purchase order found!',
									'NOT_OWN_BRANCH' => 'Cannot delete purchase receive entry of other branches!');

	/**
	 * Load Encrypt Class for encryption, cookie and constants
	 */
	public function __construct() 
	{
		parent::__construct();

		$this->load->constant('purchase_receive_const');

		$this->_receive_head_id 	= (int)$this->encrypt->decode($this->uri->segment(3));
		$this->_current_branch_id 	= (int)$this->encrypt->decode(get_cookie('branch'));
		$this->_current_user 		= (int)$this->encrypt->decode(get_cookie('temp'));
		$this->_current_date 		= date("Y-m-d H:i:s");
	}

	public function get_purchase_receive_details()
	{
		$response 		= array();
		$po_head_ids 	= array();

		$response['error'] 	= '';
		$response['detail_error'] 	= ''; 
		$response['po_list_error'] 	= ''; 

		$query_head = "SELECT CONCAT('PR',`reference_number`) AS 'reference_number', 
				COALESCE(DATE(`entry_date`),'') AS 'entry_date', `memo`, `branch_id`, `is_used`
					FROM `purchase_receive_head`
					WHERE `is_show` = ".\Constants\PURCHASE_RECEIVE_CONST::ACTIVE." AND `id` = ?";

		$result_head = $this->db->query($query_head,$this->_receive_head_id);

		if ($result_head->num_rows() != 1) 
			throw new Exception($this->_error_message['UNABLE_TO_SELECT_HEAD']);
		else
		{
			$row = $result_head->row();

			$response['reference_number'] 	= $row->reference_number;
			$response['entry_date'] 		= date('m-d-Y', strtotime($row->entry_date));
			$response['memo'] 				= $row->memo;
			$response['branch_id'] 			= $row->branch_id;
			$response['is_editable'] 		= $row->branch_id == $this->_current_branch_id ? TRUE : FALSE;
			$response['is_saved'] 			= $row->is_used == 1 ? TRUE : FALSE;
		}

		$result_head->free_result();

		//Temporary query. Can be break down to two query to optimize speed
		$query_po_list_data = array($this->_receive_head_id,$this->_current_branch_id);
		$query_po_list = "SELECT 
							    PH.`id`,
								IF(COUNT(PRD.`id`) > 0, 1, 0) AS 'is_received',
							    CONCAT('PO', PH.`reference_number`) AS 'po_number',
							    DATE(PH.`entry_date`) AS 'po_date',
							    SUM(PD.`quantity`) AS 'total_qty',
							    SUM(IF((PD.`quantity` - PD.`recv_quantity`) < 0, 0, PD.`quantity` - PD.`recv_quantity`)) AS 'total_remaining_qty'
							FROM
							    purchase_head AS PH
								LEFT JOIN purchase_detail AS PD ON PD.`headid` = PH.`id`
							    LEFT JOIN (
									SELECT PRD.`purchase_detail_id`, PRD.`id`, PRH.`branch_id`
							        FROM purchase_receive_head AS PRH
							        LEFT JOIN purchase_receive_detail AS PRD ON PRD.`headid` = PRH.`id`
							        WHERE PRH.`is_show` = ".\Constants\PURCHASE_RECEIVE_CONST::ACTIVE." AND PRH.`id` = ?
							    )AS PRD ON PRD.`purchase_detail_id` = PD.`id`
							WHERE
							    PH.`is_show` = ".\Constants\PURCHASE_RECEIVE_CONST::ACTIVE." AND PH.`is_used` = ".\Constants\PURCHASE_RECEIVE_CONST::USED."
							        AND (PH.`for_branchid` = ? OR PH.`for_branchid` = PRD.`branch_id`)
							GROUP BY PH.`id`
							HAVING total_remaining_qty > 0 OR is_received = 1";

		$result_po_list = $this->db->query($query_po_list,$query_po_list_data);

		if ($result_po_list->num_rows() == 0 && $response['branch_id'] == $this->_current_branch_id) 
			throw new Exception($this->_error_message['PURCHASE_NOT_FOUND']);
		else
		{
			$i = 0;
			foreach ($result_po_list->result() as $row) 
			{
				$response['po_lists'][$i][] = array($this->encrypt->encode($row->id));
				$response['po_lists'][$i][] = array($row->is_received);
				$response['po_lists'][$i][] = array($row->po_number);
				$response['po_lists'][$i][] = array(date('m-d-Y', strtotime($row->po_date)));
				$response['po_lists'][$i][] = array($row->total_qty);

				$i++;
			}
		}

		$result_po_list->free_result();
		
		$query_detail = "SELECT PRD.`id` AS 'receive_detail_id', PRD.`purchase_detail_id`,
						COALESCE(CONCAT('PO',PH.`reference_number`),'') AS 'po_number',
						PRD.`product_id`, COALESCE(P.`material_code`,'') AS 'material_code', 
						COALESCE(P.`type`, '') AS 'type',
						COALESCE(P.`description`,'') AS 'product', COALESCE(PD.`description`,'') AS 'description',
						CASE
							WHEN P.`uom` = ".\Constants\PURCHASE_RECEIVE_CONST::PCS." THEN 'PCS'
							WHEN P.`uom` = ".\Constants\PURCHASE_RECEIVE_CONST::KG." THEN 'KGS'
							WHEN P.`uom` = ".\Constants\PURCHASE_RECEIVE_CONST::ROLL." THEN 'ROLL'
							ELSE ''
						END AS 'uom',
						COALESCE(PD.`quantity`,0) AS 'quantity', COALESCE(PD.`memo`,'') AS 'memo', 
						(COALESCE(PD.`quantity`,0) - COALESCE(PD.`recv_quantity`,0)) AS 'qty_remaining',
						PRD.`received_by`, PRD.`receive_memo`, PRD.`quantity` AS 'qty_receive',
						IF(PRD.`quantity` >= COALESCE(PD.`quantity`,0), 1, 0) AS 'is_checked'
					FROM `purchase_receive_detail` AS PRD
					LEFT JOIN `purchase_receive_head` AS PRH ON PRH.`id` = PRD.`headid` 
					LEFT JOIN `purchase_detail` AS PD ON PD.`id` = PRD.`purchase_detail_id`
					LEFT JOIN `purchase_head` AS PH ON PH.`id` = PD.`headid`
					LEFT JOIN `product` AS P ON P.`id` = PD.`product_id` AND P.`is_show` = ".\Constants\PURCHASE_RECEIVE_CONST::ACTIVE."
					WHERE PRD.`headid` = ? AND PH.`is_show` = ".\Constants\PURCHASE_RECEIVE_CONST::ACTIVE." AND PH.`is_used` = ".\Constants\PURCHASE_RECEIVE_CONST::ACTIVE;

		$result_detail = $this->db->query($query_detail,$this->_receive_head_id);

		if ($result_detail->num_rows() == 0) 
			$response['detail_error'] = $this->_error_message['UNABLE_TO_SELECT_DETAILS'];
		else
		{
			$i = 0;
			foreach ($result_detail->result() as $row) 
			{
				$break_line = $row->type == \Constants\PURCHASE_RECEIVE_CONST::STOCK ? '' : '<br/>';
				$response['detail'][$i][] = array($this->encrypt->encode($row->receive_detail_id));
				$response['detail'][$i][] = array($this->encrypt->encode($row->purchase_detail_id));
				$response['detail'][$i][] = array($i+1);
				$response['detail'][$i][] = array($row->po_number);
				$response['detail'][$i][] = array($row->product, $row->product_id, $row->type, $break_line, $row->description);
				$response['detail'][$i][] = array($row->material_code);
				$response['detail'][$i][] = array($row->uom);
				$response['detail'][$i][] = array($row->quantity);
				$response['detail'][$i][] = array($row->memo);
				$response['detail'][$i][] = array($row->qty_remaining, $row->qty_remaining);
				$response['detail'][$i][] = array($row->received_by);
				$response['detail'][$i][] = array($row->receive_memo);
				$response['detail'][$i][] = array($row->is_checked);
				$response['detail'][$i][] = array($row->qty_receive, $row->qty_receive);
				$response['detail'][$i][] = array('');
				$response['detail'][$i][] = array('');
				$i++;
			}
		}

		$result_detail->free_result();

		return $response;
	}

	public function get_po_details($param, $response = array())
	{
		extract($param);

		$po_head_ids = "";
		$condition = "";

		$response['detail_error'] = '';

		$query_data = array($this->_receive_head_id);

		if (is_array($po_head_id)) 
		{
			$po_head_ids = $this->db->escape_str(implode(",",$po_head_id));
			$condition = "IN($po_head_ids)";
		}
		else
		{
			$po_head_ids = $this->encrypt->decode($po_head_id);
			$condition = "= ?";
			array_push($query_data,$po_head_ids);
		}

		$query = "SELECT COALESCE(PRD.`id`,0) AS 'receive_detail_id',
						PD.`id` AS 'po_detail_id', PD.`product_id`, COALESCE(P.`material_code`,'') AS 'material_code',
						COALESCE(P.`description`,'') AS 'product', PD.`quantity`, PD.`memo`, 
						CASE
							WHEN P.`uom` = ".\Constants\PURCHASE_RECEIVE_CONST::PCS." THEN 'PCS'
							WHEN P.`uom` = ".\Constants\PURCHASE_RECEIVE_CONST::KG." THEN 'KGS'
							WHEN P.`uom` = ".\Constants\PURCHASE_RECEIVE_CONST::ROLL." THEN 'ROLL'
							ELSE ''
						END AS 'uom',
						CONCAT('PO',PH.`reference_number`) AS 'po_number', PD.`description`, 
						COALESCE(P.`type`, '') AS 'type',
						COALESCE(PRD.`quantity`,0) AS 'qty_receive', (PD.`quantity` - PD.`recv_quantity`) AS 'qty_remaining',
						COALESCE(PRD.`receive_memo`,'') AS 'receive_memo', COALESCE(PRD.`received_by`,'') AS 'received_by',
						IF(COALESCE(PRD.`id`,0) = 0 AND (PD.`quantity` - PD.`recv_quantity`) <= 0, 1, 0) AS 'is_removed'
					FROM `purchase_head` AS PH
					LEFT JOIN `purchase_detail` AS PD ON PD.`headid` = PH.`id` 
					LEFT JOIN `product` AS P ON P.`id` = PD.`product_id` AND P.`is_show` = ".\Constants\PURCHASE_RECEIVE_CONST::ACTIVE."
					LEFT JOIN (
								SELECT PRD.`purchase_detail_id`, PRD.`quantity`, PRD.`id`, PRD.`receive_memo`, PRD.`received_by`
						        FROM purchase_receive_head AS PRH
						        LEFT JOIN purchase_receive_detail AS PRD ON PRD.`headid` = PRH.`id`
						        WHERE PRH.`is_show` = ".\Constants\PURCHASE_RECEIVE_CONST::ACTIVE." AND PRH.`id` = ?
					)AS PRD ON PRD.`purchase_detail_id` = PD.`id`
					WHERE PH.`is_show` = ".\Constants\PURCHASE_RECEIVE_CONST::ACTIVE." AND PH.`is_used` = ".\Constants\PURCHASE_RECEIVE_CONST::USED." AND PH.`id` $condition
					HAVING is_removed = 0";

		$result = $this->db->query($query,$query_data);

		if ($result->num_rows() == 0) 
			throw new Exception($this->_error_message['PURCHASE_NOT_FOUND']);
		else
		{
			$i = 0;
			foreach ($result->result() as $row) 
			{
				$break_line = $row->type == \Constants\PURCHASE_RECEIVE_CONST::STOCK ? '' : '<br/>';
				$response['detail'][$i][] = $row->receive_detail_id == 0 ? array(0) : array($this->encrypt->encode($row->receive_detail_id));
				$response['detail'][$i][] = array($this->encrypt->encode($row->po_detail_id));
				$response['detail'][$i][] = array($i+1);
				$response['detail'][$i][] = array($row->po_number);
				$response['detail'][$i][] = array($row->product, $row->product_id, $row->type, $break_line, $row->description);
				$response['detail'][$i][] = array($row->material_code);
				$response['detail'][$i][] = array($row->uom);
				$response['detail'][$i][] = array($row->quantity);
				$response['detail'][$i][] = array($row->memo);
				$response['detail'][$i][] = array($row->qty_remaining, $row->qty_remaining);
				$response['detail'][$i][] = array($row->received_by);
				$response['detail'][$i][] = array($row->receive_memo);
				$response['detail'][$i][] = array('');
				$response['detail'][$i][] = array($row->qty_receive,$row->qty_receive);
				$response['detail'][$i][] = array('');
				$response['detail'][$i][] = array('');
				$i++;
			}
		}

		$result->free_result();

		return $response;
	}

	public function insert_receive_detail($param)
	{
		extract($param);

		$response = array();

		$response['error'] = '';

		$purchase_detail_id = $this->encrypt->decode($purchase_detail_id);

		$query_data = array($this->_receive_head_id,$quantity,$product_id,$purchase_detail_id,$note,$receivedby);

		$query = "INSERT INTO `purchase_receive_detail`
					(`headid`,
					`quantity`,
					`product_id`,
					`purchase_detail_id`,
					`receive_memo`,
					`received_by`)
					VALUES
					(?,?,?,?,?,?);";

		$result = $this->sql->execute_query($query,$query_data);

		if ($result['error'] != '') 
			throw new Exception($this->_error_message['UNABLE_TO_INSERT']);
		else
			$response['id'] = $result['id'];

		return $response;
	}

	public function update_receive_head($param)
	{
		extract($param);

		$response = array();

		$response['error'] 	= '';
		$entry_date 		= $entry_date.' '.date('H:i:s');
		$query_data 		= array($entry_date,$memo,$this->_current_branch_id,$this->_current_user,$this->_current_date,$this->_receive_head_id);

		$query = "UPDATE `purchase_receive_head`
					SET
					`entry_date` = ?,
					`memo` = ?,
					`branch_id`= ?,
					`is_used` = ".\Constants\PURCHASE_RECEIVE_CONST::USED.",
					`last_modified_by` = ?,
					`last_modified_date` = ?
					WHERE `id` = ?;";

		$result = $this->sql->execute_query($query,$query_data);

		if ($result['error'] != '') 
			throw new Exception($this->_error_message['UNABLE_TO_UPDATE_HEAD']);

		return $response;
	}

	public function search_purchase_receive_list($param)
	{
		extract($param);

		$limit = $row_end - $row_start + 1;

		$response['rowcnt'] = 0;

		$this->db->select("PRH.`id`, COALESCE(B.`name`,'') AS 'location', COALESCE(B2.`name`,'') AS 'for_branch',
							CONCAT('PR',PRH.`reference_number`) AS 'reference_number', COALESCE(GROUP_CONCAT(DISTINCT CONCAT('PO',PH.`reference_number`)),'') AS 'po_numbers',
						    COALESCE(DATE(PRH.`entry_date`),'') AS 'entry_date', IF(PRH.`is_used` = 0, 'Unused',PRH.`memo`) AS 'memo', 
						    COALESCE(SUM(PRD.`quantity`),'') AS 'total_qty'")
				->from("purchase_receive_head AS PRH")
				->join("purchase_receive_detail AS PRD", "PRD.`headid` = PRH.`id`", "left")
				->join("purchase_detail AS PD", "PD.`id` = PRD.`purchase_detail_id`", "left")
				->join("purchase_head AS PH", "PH.`id` = PD.`headid` AND PH.`is_show` = ".\Constants\PURCHASE_RECEIVE_CONST::ACTIVE." AND PH.`is_used` = ".\Constants\PURCHASE_RECEIVE_CONST::USED, "left")
				->join("branch AS B", "B.`id` = PRH.`branch_id` AND B.`is_show` = ".\Constants\PURCHASE_RECEIVE_CONST::ACTIVE, "left")
				->join("branch AS B2", "B2.`id` = PH.`for_branchid` AND B2.`is_show` = ".\Constants\PURCHASE_RECEIVE_CONST::ACTIVE, "left")
				->where("PRH.`is_show`", \Constants\PURCHASE_RECEIVE_CONST::ACTIVE);

		if (!empty($date_from))
			$this->db->where("PRH.`entry_date` >=", $date_from." 00:00:00");

		if (!empty($date_to))
			$this->db->where("PRH.`entry_date` <=", $date_to." 23:59:59");

		if ($branch != \Constants\PURCHASE_RECEIVE_CONST::ALL_OPTION) 
			$this->db->where("PRH.`branch_id`", (int)$branch);

		if (!empty($search_string)) 
			$this->db->like("CONCAT('PR',PRH.`reference_number`,' ',PRH.`memo`)", $search_string, "both");

		switch ($order_by) 
		{
			case \Constants\PURCHASE_RECEIVE_CONST::ORDER_BY_REFERENCE:
				$order_field = "PRH.`reference_number`";
				break;
			
			case \Constants\PURCHASE_RECEIVE_CONST::ORDER_BY_LOCATION:
				$order_field = "B.`name`";
				break;

			case \Constants\PURCHASE_RECEIVE_CONST::ORDER_BY_DATE:
				$order_field = "PRH.`entry_date`";
				break;
		}

		$this->db->group_by("PRH.`id`")
				->order_by($order_field, $order_type)
				->limit((int)$limit, (int)$row_start);

		$result = $this->db->get();

		if ($result->num_rows() > 0) 
		{
			$i = 0;
			$response['rowcnt'] = $this->get_purchase_receive_list_count_by_filter($param);

			foreach ($result->result() as $row) 
			{
				$response['data'][$i][] = array($this->encrypt->encode($row->id));
				$response['data'][$i][] = array($row_start + $i + 1);
				$response['data'][$i][] = array($row->location);
				$response['data'][$i][] = array($row->for_branch);
				$response['data'][$i][] = array($row->reference_number);
				$response['data'][$i][] = array($row->po_numbers);
				$response['data'][$i][] = array(date('m-d-Y', strtotime($row->entry_date)));
				$response['data'][$i][] = array($row->memo);
				$response['data'][$i][] = array($row->total_qty);
				$response['data'][$i][] = array('');
				$i++;
			}
		}

		$result->free_result();
		
		return $response;
	}

	public function get_purchase_receive_list_count_by_filter($param)
	{
		extract($param);

		$response['rowcnt'] = 0;

		$this->db->from("purchase_receive_head AS PRH")
				->join("purchase_receive_detail AS PRD", "PRD.`headid` = PRH.`id`", "left")
				->where("PRH.`is_show`", \Constants\PURCHASE_RECEIVE_CONST::ACTIVE);

		if (!empty($date_from))
			$this->db->where("PRH.`entry_date` >=", $date_from." 00:00:00");

		if (!empty($date_to))
			$this->db->where("PRH.`entry_date` <=", $date_to." 23:59:59");

		if ($branch != \Constants\PURCHASE_RECEIVE_CONST::ALL_OPTION) 
			$this->db->where("PRH.`branch_id`", (int)$branch);

		if (!empty($search_string)) 
			$this->db->like("CONCAT('PR',PRH.`reference_number`,' ',PRH.`memo`)", $search_string, "both");

		$this->db->group_by("PRH.`id`");

		return $this->db->count_all_results();
	}

	public function delete_purchase_receive_head($param)
	{
		extract($param);

		$purchase_receive_id = $this->encrypt->decode($head_id);

		$response = array();
		$response['error'] = '';

		$query 	= "SELECT `branch_id` FROM purchase_receive_head WHERE id = ?";
		$result = $this->db->query($query,$purchase_receive_id);
		$row 	= $result->row();

		if ($row->branch_id != $this->_current_branch_id) {
			throw new Exception($this->_error_message['NOT_OWN_BRANCH']);
		}

		$result->free_result();

		$query_data = array($this->_current_date,$this->_current_user,$purchase_receive_id);
		$query 	= "UPDATE `purchase_receive_head` 
					SET 
					`is_show` = ".\Constants\PURCHASE_RECEIVE_CONST::DELETED.",
					`last_modified_date` = ?,
					`last_modified_by` = ?
					WHERE `id` = ?";

		$result = $this->sql->execute_query($query,$query_data);

		if ($result['error'] != '') 
			throw new Exception($this->_error_message['UNABLE_TO_DELETE_HEAD']);

		return $response;
	}

	public function delete_purchase_receive_detail($param)
	{
		extract($param);

		$response = array();

		$response['error'] 	= '';
		$purchase_receive_detail = $this->encrypt->decode($detail_id);

		$query = "DELETE FROM `purchase_receive_detail` WHERE `id` = ?";

		$result = $this->sql->execute_query($query,$purchase_receive_detail);

		if ($result['error'] != '') 
			throw new Exception($this->_error_message['UNABLE_TO_DELETE']);

		return $response;

	}

	public function update_purchase_receive_detail($param)
	{
		extract($param);

		$response = array();

		$response['error'] = '';
		$receive_detail_id 	= $this->encrypt->decode($detail_id);
		$purchase_detail_id = $this->encrypt->decode($purchase_detail_id);
		$query_data 		= array($quantity,$product_id,$purchase_detail_id,$note,$receivedby,$receive_detail_id);

		$query = "UPDATE `purchase_receive_detail`
					SET
					`quantity` = ?,
					`product_id` = ?,
					`purchase_detail_id` = ?,
					`receive_memo` = ?,
					`received_by` = ?
					WHERE `id` = ?;";

		$result = $this->sql->execute_query($query,$query_data);

		if ($result['error'] != '') 
			throw new Exception($this->_error_message['UNABLE_TO_UPDATE']);

		return $response;
	}

	public function get_receive_printout_detail()
	{
		$response = array();

		$response['error'] = '';

		$receive_id = $this->encrypt->decode($this->session->userdata('purchase_receive'));

		$query_head = "SELECT CONCAT('PR',`reference_number`) AS 'reference_number', 
						DATE(`entry_date`) AS 'entry_date'
					FROM purchase_receive_head
					WHERE `id` = ?";

		$result_head = $this->db->query($query_head,$receive_id);
		
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
							COALESCE(PD.`description`,'') AS 'description', COALESCE(P.`material_code`,'-') AS 'item_code', 
							D.`received_by`, D.`receive_memo`,
							CASE
								WHEN P.`uom` = 1 THEN 'PCS'
								WHEN P.`uom` = 2 THEN 'KGS'
								WHEN P.`uom` = 3 THEN 'ROLL'
								ELSE ''
							END AS 'uom'
							FROM purchase_receive_head AS H
							LEFT JOIN purchase_receive_detail AS D ON D.`headid` = H.`id`
							LEFT JOIN product AS P ON P.`id` = D.`product_id`
							LEFT JOIN purchase_detail AS PD ON PD.`id` = D.`purchase_detail_id`
							WHERE H.`id` = ?";

		$result_detail = $this->db->query($query_detail,$receive_id);

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

		$response['title'] = 'PURCHASE RECEIVE SUMMARY';

		return $response;
	}

	public function check_if_transaction_has_product()
	{
		$this->db->select("D.*")
				->from("purchase_receive_detail AS D")
				->join("purchase_receive_head AS H", "H.`id` = D.`headid`", "left")
				->where("H.`is_show`", \Constants\PURCHASE_RECEIVE_CONST::ACTIVE)
				->where("H.`id`", $this->_receive_head_id);

		$result = $this->db->get();

		return $result;
	}

	public function get_purchase_receive_by_transaction($param)
	{
		extract($param);

		$this->db->select("PRH.`id`, COALESCE(B.`name`,'') AS 'location', COALESCE(B2.`name`,'') AS 'for_branch',
						CONCAT('PR',PRH.`reference_number`) AS 'reference_number', 
						COALESCE(GROUP_CONCAT(DISTINCT CONCAT('PO',PH.`reference_number`)),'') AS 'po_numbers',
					    COALESCE(DATE(PRH.`entry_date`),'') AS 'entry_date', IF(PRH.`is_used` = 0, 'Unused',PRH.`memo`) AS 'memo', 
					    COALESCE(SUM(PRD.`quantity`),'') AS 'total_qty'")
				->from("purchase_receive_head AS PRH")
				->join("purchase_receive_detail AS PRD", "PRD.`headid` = PRH.`id`", "left")
				->join("purchase_detail AS PD", "PD.`id` = PRD.`purchase_detail_id`", "left")
				->join("purchase_head AS PH", "PH.`id` = PD.`headid` AND PH.`is_show` = ".\Constants\PURCHASE_RECEIVE_CONST::ACTIVE." AND PH.`is_used` = ".\Constants\PURCHASE_RECEIVE_CONST::USED, "left")
				->join("branch AS B", "B.`id` = PRH.`branch_id` AND B.`is_show` = ".\Constants\PURCHASE_RECEIVE_CONST::ACTIVE, "left")
				->join("branch AS B2", "B2.`id` = PH.`for_branchid` AND B2.`is_show` = ".\Constants\PURCHASE_RECEIVE_CONST::ACTIVE, "left")
				->where("PRH.`is_show`",\Constants\PURCHASE_RECEIVE_CONST::ACTIVE)
				->where("PRH.`is_used`",\Constants\PURCHASE_RECEIVE_CONST::USED);

		if (!empty($date_from))
			$this->db->where("PRH.`entry_date` >=", $date_from.' 00:00:00');

		if (!empty($date_to))
			$this->db->where("PRH.`entry_date` <=", $date_to.' 23:59:59');

		if ($branch != \Constants\PURCHASE_RECEIVE_CONST::ALL_OPTION) 
			$this->db->where("PRH.`branch_id`", $branch);

		if (!empty($search_string)) 
			$this->db->like("CONCAT('PR',PRH.`reference_number`,' ',PRH.`memo`)", $search_string, "both");

		$this->db->group_by("PRH.`id`");

		switch ($order_by) 
		{
			case \Constants\PURCHASE_RECEIVE_CONST::ORDER_BY_REFERENCE:
				$order_field = "PRH.`reference_number`";
				break;
			
			case \Constants\PURCHASE_RECEIVE_CONST::ORDER_BY_LOCATION:
				$order_field = "B.`name`";
				break;

			case \Constants\PURCHASE_RECEIVE_CONST::ORDER_BY_DATE:
				$order_field = "PRH.`entry_date`";
				break;
		}

		$this->db->order_by($order_field, $order_type);

		$result = $this->db->get();

		return $result;
	}
}
