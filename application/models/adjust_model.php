<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Adjust_Model extends CI_Model {

	private $_current_branch_id = 0;
	private $_current_user = 0;
	private $_current_date = '';
	private $_interval_date = '';
	private $_error_message = array('REQUEST_EXISTS' => 'Cannot submit adjust request! Current product still has a pending request!',
									'UNABLE_TO_INSERT' => 'Unable to insert inventory adjust!',
									'UNABLE_TO_UPDATE' => 'Unable to update inventory adjust!',
									'UNABLE_TO_SELECT' => 'Unable to get select details!',
									'UNABLE_TO_DELETE' => 'Unable to delete request! Selected request might already be deleted or approved/declined.');
	/**
	 * Load Encrypt Class for encryption, cookie and constants
	 */
	public function __construct() {
		parent::__construct();

		$this->load->constant('adjust_const');

		$this->_current_branch_id 	= (int)$this->encrypt->decode(get_cookie('branch'));
		$this->_current_user 		= (int)$this->encrypt->decode(get_cookie('temp'));
		$this->_current_date 		= date("Y-m-d H:i:s");
		$this->_interval_date 		= date('Y-m-d H:i:s', strtotime('-'.\Constants\ADJUST_CONST::DATE_INTERVAL.' day', strtotime($this->_current_date)));
	}

	public function get_product_adjust_list($param, $with_limit = TRUE)
	{
		extract($param);

		$response['rowcnt'] = 0;

		$this->db->select("P.`id`, P.`material_code`, P.`description`, 
							CASE
								WHEN P.`uom` = ".\Constants\ADJUST_CONST::PCS." THEN 'PCS'
								WHEN P.`uom` = ".\Constants\ADJUST_CONST::KG." THEN 'KGS'
								WHEN P.`uom` = ".\Constants\ADJUST_CONST::ROLL." THEN 'ROLL'
							END AS 'uom',
							CASE 
								WHEN P.`type` = ".\Constants\ADJUST_CONST::NON_STOCK." THEN 'Non - Stock'
								WHEN P.`type` = ".\Constants\ADJUST_CONST::STOCK." THEN 'Stock'
							END AS 'type',
							COALESCE(M.`name`,'') AS 'material_type', COALESCE(S.`name`,'') AS 'subgroup', 
							COALESCE(PBI.`inventory`,0) AS 'inventory', COALESCE(IA.`id`,0) AS 'adjust_id', 
							COALESCE(IA.`new_inventory`,0) AS 'requested_new_inventory'")
				->from("product AS P")
				->join("material_type AS M", "M.`id` = P.`material_type_id` AND M.`is_show` = ".\Constants\ADJUST_CONST::ACTIVE, "left")
				->join("subgroup AS S", "S.`id` = P.`subgroup_id` AND S.`is_show` = ".\Constants\ADJUST_CONST::ACTIVE, "left")
				->join("product_branch_inventory AS PBI", "PBI.`product_id` = P.`id` AND PBI.`branch_id` = ".$this->_current_branch_id, "left")
				->join("inventory_adjust AS IA", "IA.`product_id` = P.`id` AND IA.`is_show` = ".\Constants\ADJUST_CONST::ACTIVE." AND IA.`branch_id` = ".$this->_current_branch_id." AND IA.`status` = ".\Constants\ADJUST_CONST::PENDING, "left")
				->where("P.`is_show`", \Constants\ADJUST_CONST::ACTIVE);

		if (!empty($code)) 
			$this->db->like("P.`material_code`", $code, "both");

		if (!empty($product)) 
			$this->db->like("P.`description`", $product, "both");

		if ($subgroup != \Constants\ADJUST_CONST::ALL_OPTION) 
			$this->db->where("P.`subgroup_id`", $subgroup);

		if ($material != \Constants\ADJUST_CONST::ALL_OPTION) 
			$this->db->where("P.`material_type_id`", $material);

		if (!empty($datefrom))
			$this->db->where("P.`date_created` >=", $datefrom.' 00:00:00');

		if (!empty($dateto))
			$this->db->where("P.`date_created` <=", $dateto.' 23:59:59');

		if ($type != \Constants\ADJUST_CONST::ALL_OPTION) 
		{
			switch ($type) 
			{
				case 1:
					$type = \Constants\ADJUST_CONST::STOCK;
					break;
				
				case 2:
					$type = \Constants\ADJUST_CONST::NON_STOCK;
					break;
			}

			$this->db->where("P.`type`",$type);
		}

		if ($invstat != \Constants\ADJUST_CONST::ALL_OPTION) 
		{
			$comparison_operator = '';

			switch ($invstat) {
				case \Constants\ADJUST_CONST::POSITIVE_INV:
					$comparison_operator = '>';
					break;
				
				case \Constants\ADJUST_CONST::NEGATIVE_INV:
					$comparison_operator = '<';
					break;

				case \Constants\ADJUST_CONST::ZERO_INV:
					$comparison_operator = '=';
					break;
			}

			$this->db->where("PBI.`inventory` $comparison_operator", 0);
		}

		switch ($orderby) 
		{
			case \Constants\ADJUST_CONST::ORDER_BY_NAME:
				$order_field = "P.`description`";
				break;
			
			case \Constants\ADJUST_CONST::ORDER_BY_CODE:
				$order_field = "P.`material_code`";
				break;
		}

		$this->db->order_by($order_field,"DESC");

		if ($with_limit) 
		{
			$limit = $row_end - $row_start + 1;
			$this->db->limit($limit, $row_start);
		}
		
		$result = $this->db->get();

		if ($result->num_rows() > 0) 
		{
			$i = 0;
			$response['rowcnt'] = $this->get_product_adjust_count_by_filter($param);

			foreach ($result->result() as $row) 
			{
				$adjust_id = $row->adjust_id == 0 ? 0 : $this->encrypt->encode($row->adjust_id);
				
				$response['data'][$i][] = array($this->encrypt->encode($row->id));
				$response['data'][$i][] = array($row_start + $i + 1);
				$response['data'][$i][] = array($row->material_code);
				$response['data'][$i][] = array($row->description);
				$response['data'][$i][] = array($row->uom);
				$response['data'][$i][] = array($row->type);
				$response['data'][$i][] = array($row->material_type);
				$response['data'][$i][] = array($row->subgroup);
				$response['data'][$i][] = array($row->inventory,0);
				$response['data'][$i][] = array($row->requested_new_inventory,$adjust_id);
				$i++;
			}
		}

		$result->free_result();

		return $response;
	}

	public function get_product_adjust_count_by_filter($param)
	{
		extract($param);

		$this->db->from("product AS P")
				->where("P.`is_show`", \Constants\ADJUST_CONST::ACTIVE);

		if (!empty($code)) 
			$this->db->like("P.`material_code`", $code, "both");

		if (!empty($product)) 
			$this->db->like("P.`description`", $product, "both");

		if ($subgroup != \Constants\ADJUST_CONST::ALL_OPTION) 
			$this->db->where("P.`subgroup_id`", $subgroup);

		if ($material != \Constants\ADJUST_CONST::ALL_OPTION) 
			$this->db->where("P.`material_type_id`", $material);

		if (!empty($datefrom))
			$this->db->where("P.`date_created` >=", $datefrom.' 00:00:00');

		if (!empty($dateto))
			$this->db->where("P.`date_created` <=", $dateto.' 23:59:59');

		if ($type != \Constants\ADJUST_CONST::ALL_OPTION) 
		{
			switch ($type) 
			{
				case 1:
					$type = \Constants\ADJUST_CONST::STOCK;
					break;
				
				case 2:
					$type = \Constants\ADJUST_CONST::NON_STOCK;
					break;
			}

			$this->db->where("P.`type`",$type);
		}

		return $this->db->count_all_results();
	}

	public function get_adjust_details($param)
	{
		extract($param);

		$response 	= array();

		$response['error'] = '';

		$product_id = $this->encrypt->decode($product_id);
		$adjust_id = $adjust_id == '0' ? 0 : $this->encrypt->decode($adjust_id);

		$query_data = array($this->_current_branch_id,$adjust_id,$product_id);
		$query = "SELECT P.`description` AS 'product_name', P.`material_code`, COALESCE(IA.`old_inventory`,PBI.`inventory`) AS 'old_inventory',
					COALESCE(IA.`new_inventory`,0) AS 'new_inventory'
					FROM product AS P
					LEFT JOIN product_branch_inventory AS PBI ON PBI.`product_id` = P.`id` AND PBI.`branch_id` = ?
					LEFT JOIN inventory_adjust AS IA ON IA.`product_id` = P.`id` AND IA.`id` = ?
					WHERE P.`id` = ?";

		$result = $this->db->query($query,$query_data);
		if ($result->num_rows() == 1) 
		{
			$row = $result->row();

			foreach ($row as $key => $value) 
				$response[$key] = $value;
		}
		else
			throw new Exception($this->_error_message['UNABLE_TO_SELECT']);

		$result->free_result();

		return $response;
	}

	public function insert_inventory_adjust($param)
	{
		extract($param);
		
		$response 	= array();

		$response['error'] = '';

		$status 	= $this->permission_checker->check_permission(\Permission\PendingAdjust_Code::AUTO_APPROVE) == TRUE ? \Constants\ADJUST_CONST::APPROVED : \Constants\ADJUST_CONST::PENDING; 
		$product_id = is_numeric($product_id) ? $product_id : $this->encrypt->decode($product_id);

		$query_validation_data = array($product_id,$this->_current_branch_id);
		$query_validation = "SELECT `id` FROM inventory_adjust WHERE `product_id` = ? AND `branch_id` = ? AND `status` = ".\Constants\ADJUST_CONST::PENDING." AND `is_show` = ".\Constants\ADJUST_CONST::ACTIVE;

		$result_validation = $this->db->query($query_validation,$query_validation_data);

		if ($result_validation->num_rows() > 0)
			throw new Exception($this->_error_message['REQUEST_EXISTS']);			

		$result_validation->free_result();

		$query_data = array($this->_current_branch_id,$product_id,$old_inventory,$new_inventory,\Constants\ADJUST_CONST::ACTIVE,$status,$memo,$this->_current_user,$this->_current_user,$this->_current_date,$this->_current_date);
		$query = "INSERT INTO `inventory_adjust`
					(`branch_id`,
					`product_id`,
					`old_inventory`,
					`new_inventory`,
					`is_show`,
					`status`,
					`memo`,
					`created_by`,
					`last_modified_by`,
					`date_created`,
					`last_modified_date`)
					VALUES
					(?,?,?,?,?,?,?,?,?,?,?)";

		$result = $this->sql->execute_query($query,$query_data);
		if ($result['error'] != '') 
			throw new Exception($this->_error_message['UNABLE_TO_INSERT']);
		else
		{
			$response['status'] = $status;
			$response['id'] = $result['id'];
		}

		return $response;
	}

	public function update_inventory_adjust($param)
	{
		extract($param);

		$response 	= array();

		$response['error'] = '';

		$status 	= \Constants\ADJUST_CONST::PENDING; //$config->general->main_branch_id == $this->_current_branch_id ? \Constants\ADJUST_CONST::APPROVED : \Constants\ADJUST_CONST::PENDING;
		$product_id = is_numeric($product_id) ? $product_id : $this->encrypt->decode($product_id);
		$adjust_id 	= $this->encrypt->decode($detail_id);

		$query_validation_data = array($product_id,$this->_current_branch_id,$adjust_id);
		$query_validation = "SELECT `id` FROM inventory_adjust WHERE `product_id` = ? AND `branch_id` = ? AND `id` <> ? AND `status` = ".\Constants\ADJUST_CONST::PENDING." AND `is_show` = ".\Constants\ADJUST_CONST::ACTIVE;

		$result_validation = $this->db->query($query_validation,$query_validation_data);

		if ($result_validation->num_rows() > 0)
			throw new Exception($this->_error_message['REQUEST_EXISTS']);			

		$result_validation->free_result();

		$query_data = array($product_id,$old_inventory,$new_inventory,$memo,$this->_current_user,$this->_current_date,$adjust_id);
		$query = "UPDATE `inventory_adjust`
					SET 
					`product_id` = ?,
					`old_inventory` = ?,
					`new_inventory` = ?,
					`memo` = ?,
					`last_modified_by` = ?,
					`last_modified_date` = ?
					WHERE `id` = ?";

		$result = $this->sql->execute_query($query,$query_data);
		if ($result['error'] != '') 
			throw new Exception($this->_error_message['UNABLE_TO_INSERT']);
		else
			$response['status'] = $status;
		
		return $response;
	}

	public function get_pending_adjust_list($param, $with_limit = TRUE)
	{
		extract($param);

		$response['rowcnt'] = 0;

		$this->db->select("IA.`id`, P.`material_code`, P.`description`, 
							CASE
								WHEN P.`uom` = ".\Constants\ADJUST_CONST::PCS." THEN 'PCS'
								WHEN P.`uom` = ".\Constants\ADJUST_CONST::KG." THEN 'KGS'
								WHEN P.`uom` = ".\Constants\ADJUST_CONST::ROLL." THEN 'ROLL'
							END AS 'uom',
							CASE 
								WHEN P.`type` = ".\Constants\ADJUST_CONST::NON_STOCK." THEN 'Non - Stock'
								WHEN P.`type` = ".\Constants\ADJUST_CONST::STOCK." THEN 'Stock'
							END AS 'type',
							CASE 
								WHEN IA.`status` = ".\Constants\ADJUST_CONST::PENDING." THEN 'Pending'
								WHEN IA.`status` = ".\Constants\ADJUST_CONST::APPROVED." THEN 'Approved'
								WHEN IA.`status` = ".\Constants\ADJUST_CONST::DECLINED." THEN 'Declined'
							END AS 'status', IA.`memo`,
							COALESCE(PBI.`inventory`,0) AS 'current_inventory', 
							IA.`old_inventory`, IA.`new_inventory` AS 'requested_new_inventory',
							DATE(IA.`date_created`) AS 'date_created', COALESCE(B.`name`,'') AS 'from_branch'")
				->from("inventory_adjust AS IA")
				->join("product AS P", "P.`id` = IA.`product_id` AND P.`is_show` = ".\Constants\ADJUST_CONST::ACTIVE, "inner")
				->join("product_branch_inventory AS PBI", "PBI.`product_id` = P.`id` AND PBI.`branch_id` = IA.`branch_id`", "left")
				->join("branch AS B", "B.`id` = IA.`branch_id` AND B.`is_show` = ".\Constants\ADJUST_CONST::ACTIVE, "left")
				->where("IA.`is_show`", \Constants\ADJUST_CONST::ACTIVE);

		if (!empty($code)) 
			$this->db->like("P.`material_code`", $code, "both");

		if (!empty($product)) 
			$this->db->like("P.`description`", $product, "both");

		if ($subgroup != \Constants\ADJUST_CONST::ALL_OPTION) 
			$this->db->where("P.`subgroup_id`", $subgroup);

		if ($material != \Constants\ADJUST_CONST::ALL_OPTION) 
			$this->db->where("P.`material_type_id`", $material);

		if (!empty($datefrom))
			$this->db->where("IA.`date_created` >=", $datefrom.' 00:00:00');

		if (!empty($dateto))
			$this->db->where("IA.`date_created` <=", $dateto.' 23:59:59');
		
		if ($branch != \Constants\ADJUST_CONST::ALL_OPTION) 
			$this->db->where("IA.`branch_id`", $branch);

		if ($type != \Constants\ADJUST_CONST::ALL_OPTION) 
		{
			switch ($type) 
			{
				case 1:
					$type = \Constants\ADJUST_CONST::STOCK;
					break;
				
				case 2:
					$type = \Constants\ADJUST_CONST::NON_STOCK;
					break;
			}

			$this->db->where("P.`type`",$type);
		}

		if ($status != \Constants\ADJUST_CONST::ALL_OPTION) 
			$this->db->where("IA.`status`", $status);

		switch ($orderby) 
		{
			case \Constants\ADJUST_CONST::ORDER_BY_NAME:
				$order_field = "P.`description`";
				break;
			
			case \Constants\ADJUST_CONST::ORDER_BY_CODE:
				$order_field = "P.`material_code`";
				break;
		}

		$this->db->order_by($order_field,"DESC");

		if ($with_limit) 
		{
			$limit = $row_end - $row_start + 1;
			$this->db->limit($limit, $row_start);
		}

		$result = $this->db->get();

		if ($result->num_rows() > 0) 
		{
			$i = 0;
			$response['rowcnt'] = $this->get_pending_adjust_list_count_by_filter($param);

			foreach ($result->result() as $row) 
			{	
				$response['data'][$i][] = array($this->encrypt->encode($row->id));
				$response['data'][$i][] = array($this->encrypt->encode($row->id));
				$response['data'][$i][] = array($row_start + $i + 1);
				$response['data'][$i][] = array($row->material_code);
				$response['data'][$i][] = array($row->description);
				$response['data'][$i][] = array($row->uom);
				$response['data'][$i][] = array($row->type);
				$response['data'][$i][] = array($row->from_branch);
				$response['data'][$i][] = array(date('m-d-Y', strtotime($row->date_created)));
				$response['data'][$i][] = array($row->old_inventory);
				$response['data'][$i][] = array($row->current_inventory);
				$response['data'][$i][] = array($row->requested_new_inventory);
				$response['data'][$i][] = array($row->status);
				$response['data'][$i][] = array($row->memo);
				$i++;
			}
		}

		$result->free_result();

		return $response;
	}

	public function get_pending_adjust_list_count_by_filter($param)
	{
		extract($param);

		$this->db->from("inventory_adjust AS IA")
				->join("product AS P", "P.`id` = IA.`product_id` AND P.`is_show` = ".\Constants\ADJUST_CONST::ACTIVE, "left")
				->where("IA.`is_show`", \Constants\ADJUST_CONST::ACTIVE);

		if (!empty($code)) 
			$this->db->like("P.`material_code`", $code, "both");

		if (!empty($product)) 
			$this->db->like("P.`description`", $product, "both");

		if ($subgroup != \Constants\ADJUST_CONST::ALL_OPTION) 
			$this->db->where("P.`subgroup_id`", $subgroup);

		if ($material != \Constants\ADJUST_CONST::ALL_OPTION) 
			$this->db->where("P.`material_type_id`", $material);

		if (!empty($datefrom))
			$this->db->where("IA.`date_created` >=", $datefrom.' 00:00:00');

		if (!empty($dateto))
			$this->db->where("IA.`date_created` <=", $dateto.' 23:59:59');
		
		if ($branch != \Constants\ADJUST_CONST::ALL_OPTION) 
			$this->db->where("IA.`branch_id`", $branch);

		if ($type != \Constants\ADJUST_CONST::ALL_OPTION) 
		{
			switch ($type) 
			{
				case 1:
					$type = \Constants\ADJUST_CONST::STOCK;
					break;
				
				case 2:
					$type = \Constants\ADJUST_CONST::NON_STOCK;
					break;
			}

			$this->db->where("P.`type`",$type);
		}

		if ($status != \Constants\ADJUST_CONST::ALL_OPTION) 
			$this->db->where("IA.`status`", $status);

		return $this->db->count_all_results();
	}

	public function update_request_status($param)
	{
		extract($param);

		$response['error'] = '';

		$status = 0;
		$adjust_ids = array();

		for ($i=0; $i < count($adjust_id_list); $i++) 
			array_push($adjust_ids,$this->encrypt->decode($adjust_id_list[$i]));

		$adjust_ids = implode(',',$adjust_ids);

		switch ($action) 
		{
			case 'approve':
				$status = \Constants\ADJUST_CONST::APPROVED;
				break;
			
			case 'decline':
				$status = \Constants\ADJUST_CONST::DECLINED;
				break;
		}

		$query_data = array($status,$this->_current_user,$this->_current_date);

		$query = "UPDATE `inventory_adjust`
					SET `status` = ?,
						`last_modified_by` = ?,
						`last_modified_date` = ?
					WHERE `id` IN($adjust_ids)";

		$result = $this->sql->execute_query($query,$query_data);

		if ($result['error'])
			throw new Exception($this->_error_message['UNABLE_TO_UPDATE']);

		return $response;
	}

	public function get_adjust_express_list($param, $with_limit = TRUE)
	{
		extract($param);

		$response['rowcnt'] = 0;

		$this->db->select("IA.`id`, COALESCE(P.`description`,'') AS 'description', 
							COALESCE(P.`id`,0) AS 'product_id', P.`material_code`,
							CASE
								WHEN P.`uom` = ".\Constants\ADJUST_CONST::PCS." THEN 'PCS'
								WHEN P.`uom` = ".\Constants\ADJUST_CONST::KG." THEN 'KGS'
								WHEN P.`uom` = ".\Constants\ADJUST_CONST::ROLL." THEN 'ROLL'
							END AS 'uom',
							IA.`old_inventory`, IA.`new_inventory`, IA.`memo`, 
							CASE 
								WHEN IA.`status` = ".\Constants\ADJUST_CONST::PENDING." THEN 'Pending'
								WHEN IA.`status` = ".\Constants\ADJUST_CONST::APPROVED." THEN 'Approved'
								WHEN IA.`status` = ".\Constants\ADJUST_CONST::DECLINED." THEN 'Declined'
							END AS 'status'")
				->from("inventory_adjust AS IA")
				->join("product AS P", "P.`id` = IA.`product_id` AND P.`is_show` = ".\Constants\ADJUST_CONST::ACTIVE)
				->where("IA.`is_show`", \Constants\ADJUST_CONST::ACTIVE)
				->where("IA.`branch_id`", $this->_current_branch_id);

		if (!empty($date_from))
			$this->db->where("IA.`date_created` >=", $date_from.' 00:00:00');

		if (!empty($date_to))
			$this->db->where("IA.`date_created` <=", $date_to.' 23:59:59');

		if (!empty($search_string)) 
			$this->db->like("CONCAT(P.`description`,' ',IA.`memo`,' ',P.`material_code`)", $search_string, "both");

		switch ($order_by) 
		{
			case \Constants\ADJUST_CONST::ORDER_BY_NAME:
				$order_field = "P.`description`";
				break;
			
			case \Constants\ADJUST_CONST::ORDER_BY_CODE:
				$order_field = "P.`material_code`";
				break;
		}

		$this->db->order_by($order_field, $order_type);

		if ($with_limit) 
		{
			$limit = $row_end - $row_start + 1;
			$this->db->limit((int)$limit, (int)$row_start);
		}

		$result = $this->db->get();
		
		if ($result->num_rows() > 0) 
		{
			$response['rowcnt'] = $this->get_adjust_express_list_count_by_filter($param);

			$i = 0;

			foreach ($result->result() as $row) 
			{
				$response['data'][$i][] = array($this->encrypt->encode($row->id));
				$response['data'][$i][] = array($row_start + $i + 1);
				$response['data'][$i][] = array($row->description,$this->encrypt->encode($row->product_id));
				$response['data'][$i][] = array($row->material_code);
				$response['data'][$i][] = array($row->uom);
				$response['data'][$i][] = array($row->old_inventory);
				$response['data'][$i][] = array($row->new_inventory);
				$response['data'][$i][] = array($row->memo);
				$response['data'][$i][] = array($row->status);
				$response['data'][$i][] = array('');
				$response['data'][$i][] = array('');
				$i++;
			}
		}

		return $response;
	}

	public function get_adjust_express_list_count_by_filter($param)
	{
		extract($param);

		$this->db->from("inventory_adjust AS IA")
				->join("product AS P", "P.`id` = IA.`product_id` AND P.`is_show` = ".\Constants\ADJUST_CONST::ACTIVE)
				->where("IA.`is_show`", \Constants\ADJUST_CONST::ACTIVE)
				->where("IA.`branch_id`", $this->_current_branch_id);

		if (!empty($date_from))
			$this->db->where("IA.`date_created` >=", $date_from.' 00:00:00');

		if (!empty($date_to))
			$this->db->where("IA.`date_created` <=", $date_to.' 23:59:59');

		if (!empty($search_string)) 
			$this->db->like("CONCAT(P.`description`,' ',IA.`memo`,' ',P.`material_code`)", $search_string, "both");

		return $this->db->count_all_results();
	}

	public function delete_inventory_request($param)
	{
		extract($param);

		$response['error'] = '';

		$detail_id = $this->encrypt->decode($detail_id);

		$query_data = array(\Constants\ADJUST_CONST::DELETED,$this->_current_user,$this->_current_date,$detail_id);

		$query = "UPDATE `inventory_adjust`
					SET `is_show` = ?,
						`last_modified_by` = ?,
						`last_modified_date` = ?
					WHERE `id` = ? AND `status` = ".\Constants\ADJUST_CONST::PENDING;

		$result = $this->sql->execute_query($query,$query_data);

		if ($result['error'])
			throw new Exception($this->_error_message['UNABLE_TO_UPDATE']);
		else if ($this->db->affected_rows() != 1)
			throw new Exception($this->_error_message['UNABLE_TO_DELETE']);
		
		return $response;
	}

	public function insert_batch_adjustment($adjust_field_data)
	{
		$this->db->insert_batch('inventory_adjust', $adjust_field_data);
	}

	public function get_inventory_adjustment_list($param)
	{
		extract($param);

		$this->db->select("IA.`id`, P.`material_code`, P.`description`,
							CASE 
								WHEN P.`type` = ".\Constants\ADJUST_CONST::NON_STOCK." THEN 'Non - Stock'
								WHEN P.`type` = ".\Constants\ADJUST_CONST::STOCK." THEN 'Stock'
							END AS 'type',
							CASE 
								WHEN IA.`status` = ".\Constants\ADJUST_CONST::PENDING." THEN 'Pending'
								WHEN IA.`status` = ".\Constants\ADJUST_CONST::APPROVED." THEN 'Approved'
								WHEN IA.`status` = ".\Constants\ADJUST_CONST::DECLINED." THEN 'Declined'
							END AS 'status', IA.`memo`,
							COALESCE(PBI.`inventory`,0) AS 'current_inventory', 
							IA.`old_inventory`, IA.`new_inventory` AS 'requested_new_inventory',
							DATE(IA.`date_created`) AS 'date_created', COALESCE(B.`name`,'') AS 'from_branch'")
				->from("inventory_adjust AS IA")
				->join("product AS P", "P.`id` = IA.`product_id` AND P.`is_show` = ".\Constants\ADJUST_CONST::ACTIVE, "left")
				->join("product_branch_inventory AS PBI", "PBI.`product_id` = P.`id` AND PBI.`branch_id` = IA.`branch_id`", "left")
				->join("branch AS B", "B.`id` = IA.`branch_id` AND B.`is_show` = ".\Constants\ADJUST_CONST::ACTIVE, "left")
				->where("IA.`is_show`", \Constants\ADJUST_CONST::ACTIVE);

		if (!empty($code)) 
			$this->db->like("P.`material_code`", $code, "both");

		if (!empty($product)) 
			$this->db->like("P.`description`", $product, "both");

		if ($subgroup != \Constants\ADJUST_CONST::ALL_OPTION) 
			$this->db->where("P.`subgroup_id`", $subgroup);

		if ($material != \Constants\ADJUST_CONST::ALL_OPTION) 
			$this->db->where("P.`material_type_id`", $material);

		if (!empty($date_from))
			$this->db->where("IA.`date_created` >=", $date_from.' 00:00:00');

		if (!empty($date_to))
			$this->db->where("IA.`date_created` <=", $date_to.' 23:59:59');

		if ($branch != \Constants\ADJUST_CONST::ALL_OPTION) 
			$this->db->where("IA.`branch_id`", $branch);

		if ($type != \Constants\ADJUST_CONST::ALL_OPTION) 
		{
			switch ($type) 
			{
				case 1:
					$type = \Constants\ADJUST_CONST::STOCK;
					break;
				
				case 2:
					$type = \Constants\ADJUST_CONST::NON_STOCK;
					break;
			}

			$this->db->where("P.`type`", $type);
		}

		if ($status != \Constants\ADJUST_CONST::ALL_OPTION) 
			$this->db->where("IA.`status`", $status);

		switch ($orderby) 
		{
			case \Constants\ADJUST_CONST::ORDER_BY_NAME:
				$order_field = "P.`description`";
				break;
			
			case \Constants\ADJUST_CONST::ORDER_BY_CODE:
				$order_field = "P.`material_code`";
				break;
		}

		$this->db->order_by($order_field, $orderby);
		
		$result = $this->db->get();

		return $result;
	}

	public function get_pending_adjust_count()
	{
		$this->db->where("`status`", \Constants\ADJUST_CONST::PENDING)
				->where("`is_show`", \Constants\ADJUST_CONST::ACTIVE)
				->where("`branch_id`", $this->_current_branch_id)
				->where("`date_created` >=", $this->_interval_date);


		return $this->db->count_all_results('inventory_adjust');
	}
}
