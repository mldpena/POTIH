<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Release_Model extends CI_Model {

	private $_release_head_id = 0;
	private $_current_branch_id = 0;
	private $_current_user = 0;
	private $_current_date = '';
	private $_error_message = array('UNABLE_TO_INSERT' => 'Unable to insert release detail!',
									'UNABLE_TO_UPDATE' => 'Unable to update release detail!',
									'UNABLE_TO_UPDATE_HEAD' => 'Unable to update release head!',
									'UNABLE_TO_SELECT_HEAD' => 'Unable to get release head details!',
									'UNABLE_TO_SELECT_DETAILS' => 'Unable to get release details!',
									'UNABLE_TO_DELETE' => 'Unable to delete release detail!',
									'UNABLE_TO_DELETE_HEAD' => 'Unable to delete release head!',
									'ASSORTMENT_NOT_FOUND' => 'No pick-up assortment found!',
									'NOT_OWN_BRANCH' => 'Cannot delete release entry of other branches!');

	/**
	 * Load Encrypt Class for encryption, cookie and constants
	 */
	public function __construct() 
	{
		parent::__construct();

		$this->load->constant('release_const');

		$this->_release_head_id 	= $this->encrypt->decode($this->uri->segment(3));
		$this->_current_branch_id 	= $this->encrypt->decode(get_cookie('branch'));
		$this->_current_user 		= $this->encrypt->decode(get_cookie('temp'));
		$this->_current_date 		= date("Y-m-d H:i:s");
	}

	public function get_release_details()
	{
		$response 		= array();
		$po_head_ids 	= array();

		$response['error'] 	= '';
		$response['detail_error'] 	= ''; 
		$response['release_order_list_error'] 	= ''; 

		$query_head = "SELECT CONCAT('WR',`reference_number`) AS 'reference_number', 
					COALESCE(DATE(`entry_date`),'') AS 'entry_date', `memo`, `branch_id`, `is_used`
					FROM `release_head`
					WHERE `is_show` = ".\Constants\RELEASE_CONST::ACTIVE." AND `id` = ?";

		$result_head = $this->db->query($query_head,$this->_release_head_id);

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
		$query_po_list_data = array($this->_release_head_id,$this->_current_branch_id);
		$query_po_list = "SELECT 
							    PH.`id`,
								IF(COUNT(PRD.`id`) > 0, 1, 0) AS 'is_received',
							    CONCAT('PA', PH.`reference_number`) AS 'pa_number', PH.`customer`,
							    DATE(PH.`entry_date`) AS 'po_date',
							    SUM(PD.`quantity`) AS 'total_qty',
							    SUM(IF((PD.`quantity` - PD.`qty_released`) < 0, 0, PD.`quantity` - PD.`qty_released`)) AS 'total_remaining_qty'
							FROM
							    release_order_head AS PH
								LEFT JOIN release_order_detail AS PD ON PD.`headid` = PH.`id`
							    LEFT JOIN (
									SELECT PRD.`release_order_detail_id`, PRD.`id`, PRH.`branch_id`
							        FROM release_head AS PRH
							        LEFT JOIN release_detail AS PRD ON PRD.`headid` = PRH.`id`
							        WHERE PRH.`is_show` = ".\Constants\RELEASE_CONST::ACTIVE." AND PRH.`id` = ?
							    )AS PRD ON PRD.`release_order_detail_id` = PD.`id`
							WHERE
							    PH.`is_show` = ".\Constants\RELEASE_CONST::ACTIVE." AND PH.`is_used` = ".\Constants\RELEASE_CONST::USED."
							        AND (PH.`branch_id` = ? OR PH.`branch_id` = PRD.`branch_id`)
							GROUP BY PH.`id`
							HAVING total_remaining_qty > 0 OR is_received = 1";

		$result_po_list = $this->db->query($query_po_list,$query_po_list_data);

		if ($result_po_list->num_rows() == 0 && $response['branch_id'] == $this->_current_branch_id) 
			throw new Exception($this->_error_message['ASSORTMENT_NOT_FOUND']);
		else
		{
			$i = 0;
			foreach ($result_po_list->result() as $row) 
			{
				$response['release_order_lists'][$i][] = array($this->encrypt->encode($row->id));
				$response['release_order_lists'][$i][] = array($row->is_received);
				$response['release_order_lists'][$i][] = array($row->pa_number);
				$response['release_order_lists'][$i][] = array($row->customer);
				$response['release_order_lists'][$i][] = array(date('m-d-Y', strtotime($row->po_date)));
				$response['release_order_lists'][$i][] = array($row->total_qty);

				$i++;
			}
		}

		$result_po_list->free_result();

		$query_detail = "SELECT PRD.`id` AS 'receive_detail_id', PRD.`release_order_detail_id`,
						COALESCE(CONCAT('PA',PH.`reference_number`),'') AS 'po_number',
						PRD.`product_id`, COALESCE(P.`material_code`,'') AS 'material_code', 
						COALESCE(P.`type`, '') AS 'type',
						COALESCE(P.`description`,'') AS 'product', COALESCE(PD.`description`,'') AS 'description', 
						CASE
							WHEN P.`uom` = ".\Constants\RELEASE_CONST::PCS." THEN 'PCS'
							WHEN P.`uom` = ".\Constants\RELEASE_CONST::KG." THEN 'KGS'
							WHEN P.`uom` = ".\Constants\RELEASE_CONST::ROLL." THEN 'ROLL'
							ELSE ''
						END AS 'uom',
						COALESCE(PD.`quantity`,0) AS 'quantity', COALESCE(PD.`memo`,'') AS 'memo', 
						(COALESCE(PD.`quantity`,0) - COALESCE(PD.`qty_released`,0)) AS 'qty_remaining',
						PRD.`quantity` AS 'qty_released', 
						IF(PRD.`quantity` >= COALESCE(PD.`quantity`,0), 1, 0) AS 'is_checked'
					FROM `release_detail` AS PRD
					LEFT JOIN `release_head` AS PRH ON PRH.`id` = PRD.`headid` 
					LEFT JOIN `release_order_detail` AS PD ON PD.`id` = PRD.`release_order_detail_id`
					LEFT JOIN `release_order_head` AS PH ON PH.`id` = PD.`headid`
					LEFT JOIN `product` AS P ON P.`id` = PD.`product_id`
					WHERE PRD.`headid` = ? AND PH.`is_show` = ".\Constants\RELEASE_CONST::ACTIVE." AND PH.`is_used` = ".\Constants\RELEASE_CONST::ACTIVE;

		$result_detail = $this->db->query($query_detail,$this->_release_head_id);

		if ($result_detail->num_rows() == 0) 
			$response['detail_error'] = $this->_error_message['UNABLE_TO_SELECT_DETAILS'];
		else
		{
			$i = 0;
			foreach ($result_detail->result() as $row) 
			{
				$break_line = $row->type == \Constants\RELEASE_CONST::STOCK ? '' : '<br/>';

				$response['detail'][$i][] = array($this->encrypt->encode($row->receive_detail_id));
				$response['detail'][$i][] = array($this->encrypt->encode($row->release_order_detail_id));
				$response['detail'][$i][] = array($i+1);
				$response['detail'][$i][] = array($row->po_number);
				$response['detail'][$i][] = array($row->product, $row->product_id, $row->type, $break_line, $row->description);
				$response['detail'][$i][] = array($row->material_code);
				$response['detail'][$i][] = array($row->uom);
				$response['detail'][$i][] = array($row->quantity);
				$response['detail'][$i][] = array($row->memo);
				$response['detail'][$i][] = array($row->qty_remaining, $row->qty_remaining);
				$response['detail'][$i][] = array($row->is_checked);
				$response['detail'][$i][] = array($row->qty_released, $row->qty_released);
				$response['detail'][$i][] = array('');
				$response['detail'][$i][] = array('');
				$i++;
			}
		}

		$result_detail->free_result();

		return $response;
	}

	public function get_pa_details($param, $response = array())
	{
		extract($param);

		$po_head_ids = "";
		$condition = "";

		$response['detail_error'] = '';

		$query_data = array($this->_release_head_id);

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
						PD.`id` AS 'release_order_detail_id', PD.`product_id`, COALESCE(P.`material_code`,'') AS 'material_code', 
						COALESCE(P.`description`,'') AS 'product', 
						CASE
							WHEN P.`uom` = ".\Constants\RELEASE_CONST::PCS." THEN 'PCS'
							WHEN P.`uom` = ".\Constants\RELEASE_CONST::KG." THEN 'KGS'
							WHEN P.`uom` = ".\Constants\RELEASE_CONST::ROLL." THEN 'ROLL'
							ELSE ''
						END AS 'uom',
						PD.`quantity`, PD.`memo`, 
						CONCAT('PA',PH.`reference_number`) AS 'po_number', PD.`description`, 
						COALESCE(P.`type`, '') AS 'type',
						COALESCE(PRD.`quantity`,0) AS 'qty_released', (PD.`quantity` - PD.`qty_released`) AS 'qty_remaining',
						IF(COALESCE(PRD.`id`,0) = 0 AND (PD.`quantity` - PD.`qty_released`) <= 0, 1, 0) AS 'is_removed'
					FROM `release_order_head` AS PH
					LEFT JOIN `release_order_detail` AS PD ON PD.`headid` = PH.`id` 
					LEFT JOIN `product` AS P ON P.`id` = PD.`product_id`
					LEFT JOIN (
								SELECT PRD.`release_order_detail_id`, PRD.`quantity`, PRD.`id`
						        FROM release_head AS PRH
						        LEFT JOIN release_detail AS PRD ON PRD.`headid` = PRH.`id`
						        WHERE PRH.`is_show` = ".\Constants\RELEASE_CONST::ACTIVE." AND PRH.`id` = ?
					)AS PRD ON PRD.`release_order_detail_id` = PD.`id`
					WHERE PH.`is_show` = ".\Constants\RELEASE_CONST::ACTIVE." AND PH.`is_used` = ".\Constants\RELEASE_CONST::USED." AND PH.`id` $condition
					HAVING is_removed = 0";

		$result = $this->db->query($query,$query_data);

		if ($result->num_rows() == 0) 
			throw new Exception($this->_error_message['ASSORTMENT_NOT_FOUND']);
		else
		{
			$i = 0;
			foreach ($result->result() as $row) 
			{
				$break_line = $row->type == \Constants\RELEASE_CONST::STOCK ? '' : '<br/>';
				$response['detail'][$i][] = $row->receive_detail_id == 0 ? array(0) : array($this->encrypt->encode($row->receive_detail_id));
				$response['detail'][$i][] = array($this->encrypt->encode($row->release_order_detail_id));
				$response['detail'][$i][] = array($i+1);
				$response['detail'][$i][] = array($row->po_number);
				$response['detail'][$i][] = array($row->product, $row->product_id, $row->type, $break_line, $row->description);
				$response['detail'][$i][] = array($row->material_code);
				$response['detail'][$i][] = array($row->uom);
				$response['detail'][$i][] = array($row->quantity);
				$response['detail'][$i][] = array($row->memo);
				$response['detail'][$i][] = array($row->qty_remaining, $row->qty_remaining);
				$response['detail'][$i][] = array('');
				$response['detail'][$i][] = array($row->qty_released,$row->qty_released);
				$response['detail'][$i][] = array('');
				$response['detail'][$i][] = array('');
				$i++;
			}
		}

		$result->free_result();

		return $response;
	}

	public function insert_release_detail($param)
	{
		extract($param);

		$response = array();

		$response['error'] = '';

		$release_order_detail_id = $this->encrypt->decode($release_order_detail_id);

		$query_data = array($this->_release_head_id,$quantity,$product_id,$release_order_detail_id);

		$query = "INSERT INTO `release_detail`
					(`headid`,
					`quantity`,
					`product_id`,
					`release_order_detail_id`)
					VALUES
					(?,?,?,?);";

		$result = $this->sql->execute_query($query,$query_data);

		if ($result['error'] != '') 
			throw new Exception($this->_error_message['UNABLE_TO_INSERT']);
		else
			$response['id'] = $result['id'];

		return $response;
	}

	public function update_release_head($param)
	{
		extract($param);

		$response = array();

		$response['error'] 	= '';
		$entry_date 		= $entry_date.' '.date('H:i:s');
		$query_data 		= array($entry_date,$memo,$this->_current_branch_id,$this->_current_user,$this->_current_date,$this->_release_head_id);

		$query = "UPDATE `release_head`
					SET
					`entry_date` = ?,
					`memo` = ?,
					`branch_id`= ?,
					`is_used` = ".\Constants\RELEASE_CONST::USED.",
					`last_modified_by` = ?,
					`last_modified_date` = ?
					WHERE `id` = ?;";

		$result = $this->sql->execute_query($query,$query_data);

		if ($result['error'] != '') 
			throw new Exception($this->_error_message['UNABLE_TO_UPDATE_HEAD']);

		return $response;
	}

	public function search_release_list($param, $with_limit = TRUE)
	{
		extract($param);

		$response['rowcnt'] = 0;

		$this->db->select("PRH.`id`, COALESCE(B.`name`,'') AS 'location', 
							CONCAT('WR',PRH.`reference_number`) AS 'reference_number', 
							COALESCE(GROUP_CONCAT(DISTINCT CONCAT('PA',PH.`reference_number`)),'') AS 'panumbers',
						    COALESCE(DATE(PRH.`entry_date`),'') AS 'entry_date', IF(PRH.`is_used` = 0, 'Unused',PRH.`memo`) AS 'memo', 
						    COALESCE(SUM(PRD.`quantity`),'') AS 'total_qty'")
				->from("release_head AS PRH")
				->join("release_detail AS PRD", "PRD.`headid` = PRH.`id`", "left")
				->join("release_order_detail AS PD", "PD.`id` = PRD.`release_order_detail_id`", "left")
				->join("release_order_head AS PH", "PH.`id` = PD.`headid` AND PH.`is_show` = ".\Constants\RELEASE_CONST::ACTIVE." AND PH.`is_used` = ".\Constants\RELEASE_CONST::USED, "left")
				->join("branch AS B", "B.`id` = PRH.`branch_id` AND B.`is_show` = ".\Constants\RELEASE_CONST::ACTIVE, "left")
				->where("PRH.`is_show`", \Constants\RELEASE_CONST::ACTIVE);

		if (!empty($date_from))
			$this->db->where("PRH.`entry_date` >=", $date_from." 00:00:00");

		if (!empty($date_to))
			$this->db->where("PRH.`entry_date` <=", $date_to." 23:59:59");

		if ($branch != \Constants\RELEASE_CONST::ALL_OPTION) 
			$this->db->where("PRH.`branch_id`", (int)$branch);

		if (!empty($search_string)) 
			$this->db->like("CONCAT('WR',PRH.`reference_number`,' ',PRH.`memo`,' ','PA',PH.`reference_number`)", $search_string, "both");

		switch ($order_by) 
		{
			case \Constants\RELEASE_CONST::ORDER_BY_REFERENCE:
				$order_field = "PRH.`reference_number`";
				break;
			
			case \Constants\RELEASE_CONST::ORDER_BY_LOCATION:
				$order_field = "B.`name`";
				break;

			case \Constants\RELEASE_CONST::ORDER_BY_DATE:
				$order_field = "PRH.`entry_date`";
				break;
		}

		$this->db->group_by("PRH.`id`")
				->order_by($order_field, $order_type);

		if ($with_limit) 
		{
			$limit = $row_end - $row_start + 1;
			$this->db->limit((int)$limit, (int)$row_start);
		}

		$result = $this->db->get();

		if ($result->num_rows() > 0) 
		{
			$i = 0;

			$response['rowcnt'] = $this->get_release_list_count_by_filter($param);

			foreach ($result->result() as $row) 
			{
				$response['data'][$i][] = array($this->encrypt->encode($row->id));
				$response['data'][$i][] = array($row_start + $i + 1);
				$response['data'][$i][] = array($row->location);
				$response['data'][$i][] = array($row->reference_number);
				$response['data'][$i][] = array($row->panumbers);
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

	public function get_release_list_count_by_filter($param)
	{
		extract($param);

		$this->db->from("release_head AS PRH")
				->join("release_detail AS PRD", "PRD.`headid` = PRH.`id`", "left")
				->join("release_order_detail AS PD", "PD.`id` = PRD.`release_order_detail_id`", "left")
				->join("release_order_head AS PH", "PH.`id` = PD.`headid` AND PH.`is_show` = ".\Constants\RELEASE_CONST::ACTIVE." AND PH.`is_used` = ".\Constants\RELEASE_CONST::USED, "left")
				->where("PRH.`is_show`", \Constants\RELEASE_CONST::ACTIVE);

		if (!empty($date_from))
			$this->db->where("PRH.`entry_date` >=", $date_from." 00:00:00");

		if (!empty($date_to))
			$this->db->where("PRH.`entry_date` <=", $date_to." 23:59:59");

		if ($branch != \Constants\RELEASE_CONST::ALL_OPTION) 
			$this->db->where("PRH.`branch_id`", (int)$branch);

		if (!empty($search_string)) 
			$this->db->like("CONCAT('WR',PRH.`reference_number`,' ',PRH.`memo`,' ','PA',PH.`reference_number`)", $search_string, "both");

		return $this->db->count_all_results();
	}

	public function delete_release_head($param)
	{
		extract($param);

		$release_head_id = $this->encrypt->decode($head_id);

		$response = array();
		$response['error'] = '';

		$query 	= "SELECT `branch_id` FROM release_head WHERE id = ?";
		$result = $this->db->query($query,$release_head_id);
		$row 	= $result->row();

		if ($row->branch_id != $this->_current_branch_id) {
			throw new Exception($this->_error_message['NOT_OWN_BRANCH']);
		}

		$result->free_result();

		$query_data = array($this->_current_date,$this->_current_user,$release_head_id);
		$query 	= "UPDATE `release_head` 
					SET 
					`is_show` = ".\Constants\RELEASE_CONST::DELETED.",
					`last_modified_date` = ?,
					`last_modified_by` = ?
					WHERE `id` = ?";

		$result = $this->sql->execute_query($query,$query_data);

		if ($result['error'] != '') 
			throw new Exception($this->_error_message['UNABLE_TO_DELETE_HEAD']);

		return $response;
	}

	public function delete_release_detail($param)
	{
		extract($param);

		$response = array();

		$response['error'] 	= '';
		$release_detail_id = $this->encrypt->decode($detail_id);

		$query = "DELETE FROM `release_detail` WHERE `id` = ?";

		$result = $this->sql->execute_query($query,$release_detail_id);

		if ($result['error'] != '') 
			throw new Exception($this->_error_message['UNABLE_TO_DELETE']);

		return $response;

	}

	public function update_release_detail($param)
	{
		extract($param);

		$response = array();

		$response['error'] = '';
		$release_detail_id 	= $this->encrypt->decode($detail_id);
		$release_order_detail_id = $this->encrypt->decode($release_order_detail_id);
		$query_data 		= array($quantity,$product_id,$release_order_detail_id,$release_detail_id);

		$query = "UPDATE `release_detail`
					SET
					`quantity` = ?,
					`product_id` = ?,
					`release_order_detail_id` = ?
					WHERE `id` = ?;";

		$result = $this->sql->execute_query($query,$query_data);

		if ($result['error'] != '') 
			throw new Exception($this->_error_message['UNABLE_TO_UPDATE']);

		return $response;
	}

	public function get_release_printout_details()
	{
		$response = array();

		$response['error'] = '';

		$release_head_id = $this->encrypt->decode($this->session->userdata('release_slip'));

		$query_head = "SELECT CONCAT('WR',RH.`reference_number`) AS 'reference_number', 
						DATE(H.`entry_date`) AS 'entry_date', RH.`customer`, RH.`memo`, 
						CONCAT('PA',RH.`reference_number`) AS 'assortment_number'
					FROM release_head AS H
					LEFT JOIN release_detail AS D ON D.`headid` = H.`id`
					LEFT JOIN release_order_detail AS RD ON RD.`id` = D.`release_order_detail_id` 
					LEFT JOIN release_order_head AS RH ON RH.`id` = RD.`headid`
					WHERE H.`id` = ?
					GROUP BY H.`id`";

		$result_head = $this->db->query($query_head,$release_head_id);

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
							COALESCE(PD.`description`,'') AS 'description', COALESCE(P.`material_code`,'-') AS 'item_code', PD.`memo`,
							CASE
								WHEN P.`uom` = 1 THEN 'PCS'
								WHEN P.`uom` = 2 THEN 'KGS'
								WHEN P.`uom` = 3 THEN 'ROLL'
								ELSE ''
							END AS 'uom'
							FROM release_head AS H
							LEFT JOIN release_detail AS D ON D.`headid` = H.`id`
							LEFT JOIN product AS P ON P.`id` = D.`product_id`
							LEFT JOIN release_order_detail AS PD ON PD.`id` = D.`release_order_detail_id`
							WHERE H.`id` = ?";

		$result_detail = $this->db->query($query_detail,$release_head_id);

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

		$response['page_title'] = 'WAREHOUSE RELEASE SLIP';

		return $response;
	}

	public function check_if_transaction_has_product()
	{
		$this->db->select("D.*")
				->from("release_detail AS D")
				->join("release_head AS H", "H.`id` = D.`headid`", "left")
				->where("H.`is_show`", \Constants\RELEASE_CONST::ACTIVE)
				->where("H.`id`", $this->_release_head_id);

		$result = $this->db->get();

		return $result;
	}
}
