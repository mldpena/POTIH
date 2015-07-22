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

		$this->_receive_head_id 	= $this->encrypt->decode($this->uri->segment(3));
		$this->_current_branch_id 	= $this->encrypt->decode(get_cookie('branch'));
		$this->_current_user 		= $this->encrypt->decode(get_cookie('temp'));
		$this->_current_date 		= date("Y-m-d h:i:s");
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
			$response['entry_date'] 		= $row->entry_date;
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
							    SUM(PD.`quantity` - PD.`recv_quantity`) AS 'total_remaining_qty'
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
				$response['po_lists'][$i][] = array($row->po_date);
				$response['po_lists'][$i][] = array($row->total_qty);
				
				if ($row->is_received == 1 && !in_array($row->id,$po_head_ids)) 
					array_push($po_head_ids,$row->id);

				$i++;
			}
		}

		$result_po_list->free_result();

		if (count($po_head_ids) > 0) {
			$param['po_head_id'] = $po_head_ids;
			$response = $this->get_po_details($param,$response);
		}
		
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
						CONCAT('PO',PH.`reference_number`) AS 'po_number', PD.`description`, P.`type`,
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
				$break_line = empty($row->description) ? '' : '<br/>';
				$response['detail'][$i][] = $row->receive_detail_id == 0 ? array(0) : array($this->encrypt->encode($row->receive_detail_id));
				$response['detail'][$i][] = array($this->encrypt->encode($row->po_detail_id));
				$response['detail'][$i][] = array($i+1);
				$response['detail'][$i][] = array($row->po_number);
				$response['detail'][$i][] = array($row->product, $row->product_id, $row->type, $break_line, $row->description);
				$response['detail'][$i][] = array($row->material_code);
				$response['detail'][$i][] = array($row->quantity);
				$response['detail'][$i][] = array($row->memo);
				$response['detail'][$i][] = array($row->qty_remaining);
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
		$entry_date 		= $entry_date.' '.date('h:i:s');
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

		$conditions		= "";
		$order_field 	= "";

		$response 	= array();
		$query_data = array();

		$response['rowcnt'] = 0;
		
		
		if (!empty($date_from))
		{
			$conditions .= " AND PRH.`entry_date` >= ?";
			array_push($query_data,$date_from.' 00:00:00');
		}

		if (!empty($date_to))
		{
			$conditions .= " AND PRH.`entry_date` <= ?";
			array_push($query_data,$date_to.' 23:59:59');
		}

		if ($branch != \Constants\PURCHASE_RECEIVE_CONST::ALL_OPTION) 
		{
			$conditions .= " AND PRH.`branch_id` = ?";
			array_push($query_data,$branch);
		}
	
		if (!empty($search_string)) 
		{
			$conditions .= " AND CONCAT('PR',PRH.`reference_number`,' ',PRH.`memo`) LIKE ?";
			array_push($query_data,'%'.$search_string.'%');
		}

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


		$query = "SELECT 
						PRH.`id`, COALESCE(B.`name`,'') AS 'location', COALESCE(B2.`name`,'') AS 'for_branch',
						CONCAT('PR',PRH.`reference_number`) AS 'reference_number', COALESCE(GROUP_CONCAT(DISTINCT CONCAT('PO',PH.`reference_number`)),'') AS 'po_numbers',
					    COALESCE(DATE(PRH.`entry_date`),'') AS 'entry_date', IF(PRH.`is_used` = 0, 'Unused',PRH.`memo`) AS 'memo', 
					    COALESCE(SUM(PRD.`quantity`),'') AS 'total_qty'
					FROM
						purchase_receive_head AS PRH
					    LEFT JOIN purchase_receive_detail AS PRD ON PRD.`headid` = PRH.`id`
					    LEFT JOIN purchase_detail AS PD ON PD.`id` = PRD.`purchase_detail_id`
					    LEFT JOIN purchase_head AS PH ON PH.`id` = PD.`headid` AND PH.`is_show` = ".\Constants\PURCHASE_RECEIVE_CONST::ACTIVE." AND PH.`is_used` = ".\Constants\PURCHASE_RECEIVE_CONST::USED."
					    LEFT JOIN branch AS B ON B.`id` = PRH.`branch_id` AND B.`is_show` = ".\Constants\PURCHASE_RECEIVE_CONST::ACTIVE."
					    LEFT JOIN branch AS B2 ON B2.`id` = PH.`for_branchid` AND B2.`is_show` = ".\Constants\PURCHASE_RECEIVE_CONST::ACTIVE."
					WHERE PRH.`is_show` = ".\Constants\PURCHASE_RECEIVE_CONST::ACTIVE." $conditions
					GROUP BY PRH.`id`
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
				$response['data'][$i][] = array($row->for_branch);
				$response['data'][$i][] = array($row->reference_number);
				$response['data'][$i][] = array($row->po_numbers);
				$response['data'][$i][] = array($row->entry_date);
				$response['data'][$i][] = array($row->memo);
				$response['data'][$i][] = array($row->total_qty);
				$response['data'][$i][] = array('');
				$i++;
			}
		}

		$result->free_result();
		
		return $response;
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
							D.`received_by`, D.`receive_memo`
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

		return $response;
	}
}
