<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Adjust_Model extends CI_Model {

	private $_current_branch_id = 0;
	private $_current_user = 0;
	private $_current_date = '';
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

		$this->_current_branch_id 	= $this->encrypt->decode(get_cookie('branch'));
		$this->_current_user 		= $this->encrypt->decode(get_cookie('temp'));
		$this->_current_date 		= date("Y-m-d h:i:s");
	}

	public function get_product_adjust_list($param)
	{
		extract($param);

		$response 	= array();
		$response['rowcnt'] = 0;

		$conditions 		= "";
		$order_field 		= "";
		$query_data = array($this->_current_branch_id,$this->_current_branch_id);

		if (!empty($code)) 
		{
			$conditions .= " AND P.`material_code` LIKE ?";
			array_push($query_data,'%'.$code.'%');
		}

		if (!empty($product)) 
		{
			$conditions .= " AND P.`description` LIKE ?";
			array_push($query_data,'%'.$product.'%');
		}

		if ($subgroup != \Constants\ADJUST_CONST::ALL_OPTION) 
		{
			$conditions .= " AND P.`subgroup_id` = ?";
			array_push($query_data,$subgroup);
		}

		if ($material != \Constants\ADJUST_CONST::ALL_OPTION) 
		{
			$conditions .= " AND P.`material_type_id` = ?";
			array_push($query_data,$material);
		}

		if (!empty($datefrom))
		{
			$conditions .= " AND P.`date_created` >= ?";
			array_push($query_data,$datefrom.' 00:00:00');
		}

		if (!empty($dateto))
		{
			$conditions .= " AND P.`date_created` <= ?";
			array_push($query_data,$datefrom.' 23:59:59');
		}

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

			$conditions .= " AND P.`type` = ?";
			array_push($query_data,$type);
		}

		if ($invstat != \Constants\ADJUST_CONST::ALL_OPTION) 
		{
			switch ($invstat) {
				case \Constants\ADJUST_CONST::POSITIVE_INV:
					$conditions .= " AND PBI.`inventory` > 0";
					break;
				
				case \Constants\ADJUST_CONST::NEGATIVE_INV:
					$conditions .= " AND PBI.`inventory` < 0";
					break;

				case \Constants\ADJUST_CONST::ZERO_INV:
					$conditions .= " AND PBI.`inventory` = 0";
					break;
			}
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

		$query = "SELECT P.`id`, P.`material_code`, P.`description`,
						CASE 
							WHEN P.`type` = ".\Constants\ADJUST_CONST::NON_STOCK." THEN 'Non - Stock'
							WHEN P.`type` = ".\Constants\ADJUST_CONST::STOCK." THEN 'Stock'
						END AS 'type',
						COALESCE(M.`name`,'') AS 'material_type', COALESCE(S.`name`,'') AS 'subgroup', 
						COALESCE(PBI.`inventory`,0) AS 'inventory', COALESCE(IA.`id`,0) AS 'adjust_id', 
						COALESCE(IA.`new_inventory`,0) AS 'requested_new_inventory'
						FROM product AS P
						LEFT JOIN material_type AS M ON M.`id` = P.`material_type_id` AND M.`is_show` = ".\Constants\ADJUST_CONST::ACTIVE."
						LEFT JOIN subgroup AS S ON S.`id` = P.`subgroup_id` AND S.`is_show` = ".\Constants\ADJUST_CONST::ACTIVE."
						LEFT JOIN product_branch_inventory AS PBI ON PBI.`product_id` = P.`id` AND PBI.`branch_id` = ?
						LEFT JOIN inventory_adjust AS IA ON IA.`product_id` = P.`id` AND IA.`branch_id` = ? AND IA.`status` = ".\Constants\ADJUST_CONST::PENDING."
						WHERE P.`is_show` = ".\Constants\ADJUST_CONST::ACTIVE." $conditions
						ORDER BY $order_field";

		$result = $this->db->query($query,$query_data);

		if ($result->num_rows() > 0) 
		{
			$i = 0;
			$response['rowcnt'] = $result->num_rows();

			foreach ($result->result() as $row) 
			{
				$adjust_id = $row->adjust_id == 0 ? 0 : $this->encrypt->encode($row->adjust_id);
				
				$response['data'][$i][] = array($this->encrypt->encode($row->id));
				$response['data'][$i][] = array($i+1);
				$response['data'][$i][] = array($row->material_code);
				$response['data'][$i][] = array($row->description);
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

	public function get_pending_adjust_list($param)
	{
		extract($param);

		$response 	= array();
		$response['rowcnt'] = 0;

		$conditions 		= "";
		$order_field 		= "";
		$query_data = array();

		if (!empty($code)) 
		{
			$conditions .= " AND P.`material_code` LIKE ?";
			array_push($query_data,'%'.$code.'%');
		}

		if (!empty($product)) 
		{
			$conditions .= " AND P.`description` LIKE ?";
			array_push($query_data,'%'.$product.'%');
		}

		if ($subgroup != \Constants\ADJUST_CONST::ALL_OPTION) 
		{
			$conditions .= " AND P.`subgroup_id` = ?";
			array_push($query_data,$subgroup);
		}

		if ($material != \Constants\ADJUST_CONST::ALL_OPTION) 
		{
			$conditions .= " AND P.`material_type_id` = ?";
			array_push($query_data,$material);
		}

		if (!empty($datefrom))
		{
			$conditions .= " AND IA.`date_created` >= ?";
			array_push($query_data,$datefrom.' 00:00:00');
		}

		if (!empty($dateto))
		{
			$conditions .= " AND IA.`date_created` <= ?";
			array_push($query_data,$dateto.' 23:59:59');
		}

		if ($branch != \Constants\ADJUST_CONST::ALL_OPTION) 
		{
			$conditions .= " AND IA.`branch_id` <= ?";
			array_push($query_data,$branch);
		}

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

			$conditions .= " AND P.`type` = ?";
			array_push($query_data,$type);
		}

		if ($status != \Constants\ADJUST_CONST::ALL_OPTION) 
		{
			switch ($status) {
				case \Constants\ADJUST_CONST::PENDING:
					$conditions .= " AND IA.`status` = ".\Constants\ADJUST_CONST::PENDING;
					break;
				
				case \Constants\ADJUST_CONST::APPROVED:
					$conditions .= " AND IA.`status` = ".\Constants\ADJUST_CONST::APPROVED;
					break;

				case \Constants\ADJUST_CONST::DECLINED:
					$conditions .= " AND IA.`status` = ".\Constants\ADJUST_CONST::DECLINED;
					break;
			}
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

		$query = "SELECT IA.`id`, P.`material_code`, P.`description`,
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
						DATE(IA.`date_created`) AS 'date_created', COALESCE(B.`name`,'') AS 'from_branch'
						FROM inventory_adjust AS IA
						LEFT JOIN product AS P ON P.`id` = IA.`product_id` AND P.`is_show` = ".\Constants\ADJUST_CONST::ACTIVE."
						LEFT JOIN product_branch_inventory AS PBI ON PBI.`product_id` = P.`id` AND PBI.`branch_id` = IA.`branch_id`
						LEFT JOIN branch AS B ON B.`id` = IA.`branch_id` AND B.`is_show` = ".\Constants\ADJUST_CONST::ACTIVE."
						WHERE IA.`is_show` = ".\Constants\ADJUST_CONST::ACTIVE." $conditions
						ORDER BY $order_field";

		$result = $this->db->query($query,$query_data);

		if ($result->num_rows() > 0) 
		{
			$i = 0;
			$response['rowcnt'] = $result->num_rows();

			foreach ($result->result() as $row) 
			{	
				$response['data'][$i][] = array($this->encrypt->encode($row->id));
				$response['data'][$i][] = array($this->encrypt->encode($row->id));
				$response['data'][$i][] = array($i+1);
				$response['data'][$i][] = array($row->material_code);
				$response['data'][$i][] = array($row->description);
				$response['data'][$i][] = array($row->type);
				$response['data'][$i][] = array($row->from_branch);
				$response['data'][$i][] = array($row->date_created);
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

	public function get_adjust_express_list($param)
	{
		extract($param);

		$conditions		= "";
		$order_field 	= "";
		$having 		= "";

		$response 	= array();
		$query_data = array($this->_current_branch_id);

		$response['rowcnt'] = 0;
		
		
		if (!empty($date_from))
		{
			$conditions .= " AND IA.`date_created` >= ?";
			array_push($query_data,$date_from.' 00:00:00');
		}

		if (!empty($date_to))
		{
			$conditions .= " AND IA.`date_created` <= ?";
			array_push($query_data,$date_to.' 23:59:59');
		}
	
		if (!empty($search_string)) 
		{
			$conditions .= " AND CONCAT(P.`description`,' ',IA.`memo`,' ',P.`material_code`) LIKE ?";
			array_push($query_data,'%'.$search_string.'%');
		}

		switch ($order_by) 
		{
			case \Constants\ADJUST_CONST::ORDER_BY_NAME:
				$order_field = "P.`description`";
				break;
			
			case \Constants\ADJUST_CONST::ORDER_BY_CODE:
				$order_field = "P.`material_code`";
				break;
		}

		$query = "SELECT IA.`id`, COALESCE(P.`description`,'') AS 'description', 
					COALESCE(P.`id`,0) AS 'product_id', P.`material_code`,
					IA.`old_inventory`, IA.`new_inventory`, IA.`memo`, 
					CASE 
						WHEN IA.`status` = ".\Constants\ADJUST_CONST::PENDING." THEN 'Pending'
						WHEN IA.`status` = ".\Constants\ADJUST_CONST::APPROVED." THEN 'Approved'
						WHEN IA.`status` = ".\Constants\ADJUST_CONST::DECLINED." THEN 'Declined'
					END AS 'status'
					FROM inventory_adjust AS IA
					LEFT JOIN product AS P ON P.`id` = IA.`product_id` AND P.`is_show` = 1
					WHERE IA.`is_show` = 1 AND IA.`branch_id` = ? $conditions
					ORDER BY $order_field $order_type";

		$result = $this->db->query($query,$query_data);
		
		if ($result->num_rows() > 0) 
		{
			$response['rowcnt'] = $result->num_rows();
			$i = 0;

			foreach ($result->result() as $row) 
			{
				$response['data'][$i][] = array($this->encrypt->encode($row->id));
				$response['data'][$i][] = array($i+1);
				$response['data'][$i][] = array($row->description,$this->encrypt->encode($row->product_id));
				$response['data'][$i][] = array($row->material_code);
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

	public function insert_inventory_adjust_for_import($adjust_field_data)
	{
		$this->db->insert_batch('inventory_adjust', $adjust_field_data);
	}
}
