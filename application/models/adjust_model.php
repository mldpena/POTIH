<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Adjust_Model extends CI_Model {

	private $_current_branch_id = 0;
	private $_current_user = 0;
	private $_current_date = '';

	/**
	 * Load Encrypt Class for encryption, cookie and constants
	 */
	public function __construct() {
		$this->load->library('encrypt');
		$this->load->library('constants/adjust_const');
		$this->load->library('sql');
		$this->load->helper('cookie');

		$this->_current_branch_id 	= $this->encrypt->decode(get_cookie('branch'));
		$this->_current_user 		= $this->encrypt->decode(get_cookie('temp'));
		$this->_current_date 		= date("Y-m-d h:i:s");

		parent::__construct();
	}

	public function get_product_adjust_list($param)
	{
		extract($param);

		$response 	= array();
		$response['rowcnt'] = 0;

		$conditions 		= "";
		$order_field 		= "";
		$query_data = array($this->_current_branch_id);

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

		if ($subgroup != ADJUST_CONST::ALL_OPTION) 
		{
			$conditions .= " AND P.`subgroup_id` = ?";
			array_push($query_data,$subgroup);
		}

		if ($material != ADJUST_CONST::ALL_OPTION) 
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

		if ($type != ADJUST_CONST::ALL_OPTION) 
		{
			switch ($type) 
			{
				case 1:
					$type = ADJUST_CONST::STOCK;
					break;
				
				case 2:
					$type = ADJUST_CONST::NON_STOCK;
					break;
			}

			$conditions .= " AND P.`type` = ?";
			array_push($query_data,$type);
		}

		if ($invstat != ADJUST_CONST::ALL_OPTION) 
		{
			switch ($invstat) {
				case ADJUST_CONST::POSITIVE_INV:
					$conditions .= " AND PBI.`inventory` > 0";
					break;
				
				case ADJUST_CONST::NEGATIVE_INV:
					$conditions .= " AND PBI.`inventory` < 0";
					break;

				case ADJUST_CONST::ZERO_INV:
					$conditions .= " AND PBI.`inventory` = 0";
					break;
			}
		}

		switch ($orderby) 
		{
			case ADJUST_CONST::ORDER_BY_NAME:
				$order_field = "P.`description`";
				break;
			
			case ADJUST_CONST::ORDER_BY_CODE:
				$order_field = "P.`material_code`";
				break;
		}

		$query = "SELECT P.`id`, P.`material_code`, P.`description`,
						CASE 
							WHEN P.`type` = ".ADJUST_CONST::NON_STOCK." THEN 'Non - Stock'
							WHEN P.`type` = ".ADJUST_CONST::STOCK." THEN 'Stock'
						END AS 'type',
						COALESCE(M.`name`,'') AS 'material_type', COALESCE(S.`name`,'') AS 'subgroup', 
						COALESCE(PBI.`inventory`,0) AS 'inventory', COALESCE(IA.`id`,0) AS 'adjust_id', 
						COALESCE(IA.`new_inventory`,0) AS 'requested_new_inventory'
						FROM product AS P
						LEFT JOIN material_type AS M ON M.`id` = P.`material_type_id` AND M.`is_show` = ".ADJUST_CONST::ACTIVE."
						LEFT JOIN subgroup AS S ON S.`id` = P.`subgroup_id` AND S.`is_show` = ".ADJUST_CONST::ACTIVE."
						LEFT JOIN product_branch_inventory AS PBI ON PBI.`product_id` = P.`id` AND PBI.`branch_id` = ?
						LEFT JOIN inventory_adjust AS IA ON IA.`product_id` = P.`id` AND IA.`status` = ".ADJUST_CONST::PENDING."
						WHERE P.`is_show` = ".ADJUST_CONST::ACTIVE." $conditions
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
			$response['error'] = 'Unable to get adjustment details!';

		$result->free_result();

		return $response;
	}

	public function insert_inventory_adjust($param,$config)
	{
		extract($param);
		
		$response 	= array();
		$status 	= $config->general->main_branch_id == $this->_current_branch_id ? ADJUST_CONST::APPROVED : ADJUST_CONST::PENDING;
		$product_id = $this->encrypt->decode($product_id);

		$response['error'] = '';

		$query_data = array($this->_current_branch_id,$product_id,$old_inventory,$new_inventory,ADJUST_CONST::ACTIVE,$status,$memo,$this->_current_user,$this->_current_user,$this->_current_date,$this->_current_date);
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
			$response['error'] = 'Unable to insert inventory adjust!';
		else
		{
			$response['status'] = $status;
			$response['id'] = $this->encrypt->encode($result['id']);
		}

		return $response;
	}

	public function update_inventory_adjust($param,$config)
	{
		extract($param);

		$response 	= array();
		$status 	= $config->general->main_branch_id == $this->_current_branch_id ? ADJUST_CONST::APPROVED : ADJUST_CONST::PENDING;
		$adjust_id 	= $this->encrypt->decode($adjust_id);

		$response['error'] = '';

		$query_data = array($new_inventory,$memo,$this->_current_user,$this->_current_date,$adjust_id);
		$query = "UPDATE `inventory_adjust`
					SET `new_inventory` = ?,
					`memo` = ?,
					`last_modified_by` = ?,
					`last_modified_date` = ?
					WHERE `id` = ?";

		$result = $this->sql->execute_query($query,$query_data);

		if ($result['error'] != '') 
			$response['error'] = 'Unable to insert inventory adjust!';
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

		if ($subgroup != ADJUST_CONST::ALL_OPTION) 
		{
			$conditions .= " AND P.`subgroup_id` = ?";
			array_push($query_data,$subgroup);
		}

		if ($material != ADJUST_CONST::ALL_OPTION) 
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

		if ($branch != ADJUST_CONST::ALL_OPTION) 
		{
			$conditions .= " AND IA.`branch_id` <= ?";
			array_push($query_data,$branch);
		}

		if ($type != ADJUST_CONST::ALL_OPTION) 
		{
			switch ($type) 
			{
				case 1:
					$type = ADJUST_CONST::STOCK;
					break;
				
				case 2:
					$type = ADJUST_CONST::NON_STOCK;
					break;
			}

			$conditions .= " AND P.`type` = ?";
			array_push($query_data,$type);
		}

		if ($status != ADJUST_CONST::ALL_OPTION) 
		{
			switch ($status) {
				case ADJUST_CONST::PENDING:
					$conditions .= " AND IA.`status` = ".ADJUST_CONST::PENDING;
					break;
				
				case ADJUST_CONST::APPROVED:
					$conditions .= " AND IA.`status` = ".ADJUST_CONST::APPROVED;
					break;

				case ADJUST_CONST::DECLINED:
					$conditions .= " AND IA.`status` = ".ADJUST_CONST::DECLINED;
					break;
			}
		}

		switch ($orderby) 
		{
			case ADJUST_CONST::ORDER_BY_NAME:
				$order_field = "P.`description`";
				break;
			
			case ADJUST_CONST::ORDER_BY_CODE:
				$order_field = "P.`material_code`";
				break;
		}

		$query = "SELECT IA.`id`, P.`material_code`, P.`description`,
						CASE 
							WHEN P.`type` = ".ADJUST_CONST::NON_STOCK." THEN 'Non - Stock'
							WHEN P.`type` = ".ADJUST_CONST::STOCK." THEN 'Stock'
						END AS 'type',
						CASE 
							WHEN IA.`status` = ".ADJUST_CONST::PENDING." THEN 'Pending'
							WHEN IA.`status` = ".ADJUST_CONST::APPROVED." THEN 'Approved'
							WHEN IA.`status` = ".ADJUST_CONST::DECLINED." THEN 'Declined'
						END AS 'status', IA.`memo`,
						COALESCE(PBI.`inventory`,0) AS 'current_inventory', 
						IA.`old_inventory`, IA.`new_inventory` AS 'requested_new_inventory',
						DATE(IA.`date_created`) AS 'date_created', COALESCE(B.`name`,'') AS 'from_branch'
						FROM inventory_adjust AS IA
						LEFT JOIN product AS P ON P.`id` = IA.`product_id` AND P.`is_show` = ".ADJUST_CONST::ACTIVE."
						LEFT JOIN product_branch_inventory AS PBI ON PBI.`product_id` = P.`id` AND PBI.`branch_id` = IA.`branch_id`
						LEFT JOIN branch AS B ON B.`id` = IA.`branch_id` AND B.`is_show` = ".ADJUST_CONST::ACTIVE."
						WHERE IA.`is_show` = ".ADJUST_CONST::ACTIVE." $conditions
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

		$response = array();
		$response['error'] = '';

		$status = 0;
		$adjust_ids = array();

		for ($i=0; $i < count($adjust_id_list); $i++) 
			array_push($adjust_ids,$this->encrypt->decode($adjust_id_list[$i]));

		$adjust_ids = implode(',',$adjust_ids);

		switch ($action) 
		{
			case 'approve':
				$status = ADJUST_CONST::APPROVED;
				break;
			
			case 'decline':
				$status = ADJUST_CONST::DECLINED;
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
			$response['error'] = 'Unable to update inventory adjust request!';

		return $response;
	}
}
