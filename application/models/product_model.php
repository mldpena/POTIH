<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Product_Model extends CI_Model {

	private $_current_branch_id = 0;
	private $_current_user = 0;
	private $_current_date = '';

	/**
	 * Load Encrypt Class for encryption, cookie and constants
	 */
	public function __construct() {
		$this->load->library('encrypt');
		$this->load->file(CONSTANTS.'product_const.php');
		$this->load->library('sql');
		$this->load->helper('cookie');

		$this->_current_branch_id 	= $this->encrypt->decode(get_cookie('branch'));
		$this->_current_user 		= $this->encrypt->decode(get_cookie('temp'));
		$this->_current_date 		= date("Y-m-d h:i:s");

		parent::__construct();
	}

	public function get_product_details($param)
	{
		extract($param);

		$response = array();

		$response['error'] = '';

		$product_id = $this->encrypt->decode($product_id);

		$query = "SELECT P.`material_code`, P.`description`, P.`type`,
						COALESCE(M.`name`,'') AS 'material_type', COALESCE(S.`name`,'') AS 'subgroup',
						P.`material_type_id`, P.`subgroup_id`
						FROM product AS P
						LEFT JOIN material_type AS M ON M.`id` = P.`material_type_id` AND M.`is_show` = ".PRODUCT_CONST::ACTIVE." 
						LEFT JOIN subgroup AS S ON S.`id` = P.`subgroup_id` AND S.`is_show` = ".PRODUCT_CONST::ACTIVE."
						WHERE P.`is_show` = ".PRODUCT_CONST::ACTIVE." AND P.`id` = ?";

		$result = $this->db->query($query,$product_id);

		if ($result->num_rows() == 1) 
		{
			$row = $result->row();

			$response['data']['type'] 			= $row->type;
			$response['data']['material_code'] 	= $row->material_code;
			$response['data']['product'] 		= $row->description;
			$response['data']['material_type'] 	= $row->material_type;
			$response['data']['material_id'] 	= $row->material_type_id;
			$response['data']['subgroup'] 		= $row->subgroup;
			$response['data']['subgroup_id'] 	= $row->subgroup_id;

			$query_inventory = "SELECT PBI.`id`, COALESCE(CONCAT(B.`code`,' - ',B.`name`),'') AS 'branch', COALESCE(B.`id`,0) AS 'branch_id',
									PBI.`min_inv`, PBI.`max_inv`
									FROM product_branch_inventory AS PBI 
									LEFT JOIN branch AS B ON B.`id` = PBI.`branch_id`
									WHERE B.`is_show` = ".PRODUCT_CONST::ACTIVE." AND PBI.`product_id` = ?";

			$result_inventory = $this->db->query($query_inventory,$product_id);

			if ($result_inventory->num_rows() != 0) 
			{
				$i = 0;
				foreach ($result_inventory->result() as $row) 
				{
					$response['branch_inventory'][$i][] = array($this->encrypt->encode($row->id));
					$response['branch_inventory'][$i][] = array($i+1);
					$response['branch_inventory'][$i][] = array($row->branch,$row->branch_id);
					$response['branch_inventory'][$i][] = array($row->min_inv);
					$response['branch_inventory'][$i][] = array($row->max_inv);
					$i++;
				}
			}

			$result_inventory->free_result();
		}
		else
			$response['error'] = 'Product not found!';

		$result->free_result();

		return $response;
	}

	public function get_product_material_subgroup($param)
	{
		extract($param);
		$response 	= array();
		$response['error']			= '';
		$response['material_name']	= '';
		$response['material_id'] 	= 0;
		$response['subgroup_name'] 	= '';
		$response['subgroup_id'] 	= 0;
		

		$query = "SELECT `name`, `id` 
					FROM material_type WHERE `code` = ? AND `is_show` = ".PRODUCT_CONST::ACTIVE;

		$result = $this->db->query($query,$code[0]);

		if ($result->num_rows() == 1) 
		{
			$row = $result->row();
			$response['material_name']	= $row->name;
			$response['material_id'] 	= $row->id;
		}

		$result->free_result();

		$query = "SELECT `name`, `id` 
					FROM subgroup WHERE `code` = ? AND `is_show` = ".PRODUCT_CONST::ACTIVE;

		$result = $this->db->query($query,$code[1]);
		if ($result->num_rows() == 1) 
		{
			$row = $result->row();
			$response['subgroup_name']	= $row->name;
			$response['subgroup_id'] 	= $row->id;
		}

		$result->free_result();

		return $response;
	}

	/**
	 * Convert to SQL transaction
	 */

	public function insert_new_product($param)
	{
		extract($param);

		$response 	= array();
		$query_data = array($code,$product,$is_nonstack,$material,$subgroup,$this->_current_date,$this->_current_date,$this->_current_user,$this->_current_user);
		$response['error'] = '';

 		$query = "INSERT INTO `product`
					(`material_code`,
					`description`,
					`type`,
					`material_type_id`,
					`subgroup_id`,
					`date_created`,
					`last_modified_date`,
					`created_by`,
					`last_modified_by`)
					VALUES
					(?,?,?,?,?,?,?,?,?);";

		$result = $this->sql->execute_query($query,$query_data);

		if ($result['error'] != '') 
			$response['error'] = 'Unable to save product!';
		else
		{
			$response['id'] = $result['id'];
			$product_id 	= $this->encrypt->decode($result['id']);
			$query_inventory_data = array();

			$query_inventory = "INSERT INTO `product_branch_inventory`
						(`branch_id`,
						`product_id`,
						`inventory`,
						`min_inv`,
						`max_inv`)
						VALUES";

			$bind_values = "";

			for ($i=0; $i < count($min_max_values); $i++) 
			{ 
				$bind_values .= ",(?,?,0,?,?)";	
				array_push($query_inventory_data,$min_max_values[$i][1],$product_id,$min_max_values[$i][2],$min_max_values[$i][3]);
			}

			$query_inventory .= substr($bind_values,1);

			$result_inventory = $this->sql->execute_query($query_inventory,$query_inventory_data);

			if ($result_inventory['error'] != '') 
				$response['error'] = 'Unable to save branch inventory!';
		}

		return $response;
	}

	public function update_product_details($param)
	{
		extract($param);
		$product_id = $this->encrypt->decode($product_id);

		$response 	= array();
		$query 		= array();
		$query_data = array();
		$response['error'] = '';

		$query_product = "UPDATE `product`
						SET
						`material_code` = ?,
						`description` = ?,
						`type` = ?,
						`material_type_id` = ?,
						`subgroup_id` = ?,
						`last_modified_date` =?,
						`last_modified_by` = ?
						WHERE `id` = ?";

		$query_product_data = array($code,$product,$is_nonstack,$material,$subgroup,$this->_current_date,$this->_current_user,$product_id);
		
		array_push($query,$query_product);
		array_push($query_data,$query_product_data);

		for ($i=0; $i < count($min_max_values); $i++) 
		{ 
			$inventory_id = $this->encrypt->decode($min_max_values[$i][0]);

			$query_inventory_data = array();
			$query_inventory = "UPDATE product_branch_inventory
								SET `min_inv` = ?,
									`max_inv` = ?
								WHERE `id` = ?";

			array_push($query,$query_inventory);		
			array_push($query_data,array($min_max_values[$i][2],$min_max_values[$i][3],$inventory_id));
		}

		$result = $this->sql->execute_transaction($query,$query_data);

		if ($result['error'] != '') 
			$response['error'] = 'Unable to save product!';
		else
			$response['id'] = $result['id'];

		return $response;

	}

	public function delete_product($param)
	{
		extract($param);

		$product_id = $this->encrypt->decode($product_id);

		$response = array();
		$response['error'] = '';

		$query_data = array($this->_current_date,$this->_current_user,$product_id);
		$query 	= "UPDATE `product` 
					SET 
					`is_show` = ".PRODUCT_CONST::DELETED.",
					`last_modified_date` = ?,
					`last_modified_by` = ?
					WHERE `id` = ?";

		$result = $this->sql->execute_query($query,$query_data);

		if ($result['error'] != '') 
			$response['error'] = 'Unable to delete product!';

		return $response;
	}

	public function get_product_list($param)
	{
		extract($param);

		$response 	= array();
		$response['rowcnt'] = 0;

		$conditions 		= "";
		$order_field 		= "";
		$group_by 			= "";
		$inventory_join 	= "";
		$inventory_column 	= "PBI.`inventory`";
		$query_data = array();

		if ($branch != PRODUCT_CONST::ALL_OPTION) 
		{
			$inventory_join = " AND PBI.`branch_id` = ?";
			array_push($query_data,$branch);
		}
		else
		{
			$inventory_column = "SUM(PBI.`inventory`)";
			$group_by = "GROUP BY P.`id`";
		}

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

		if ($subgroup != PRODUCT_CONST::ALL_OPTION) 
		{
			$conditions .= " AND P.`subgroup_id` = ?";
			array_push($query_data,$subgroup);
		}

		if ($material != PRODUCT_CONST::ALL_OPTION) 
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
			array_push($query_data,$dateto.' 23:59:59');
		}

		if ($type != PRODUCT_CONST::ALL_OPTION) 
		{
			switch ($type) 
			{
				case 1:
					$type = PRODUCT_CONST::STOCK;
					break;
				
				case 2:
					$type = PRODUCT_CONST::NON_STOCK;
					break;
			}

			$conditions .= " AND P.`type` = ?";
			array_push($query_data,$type);
		}

		if ($invstat != PRODUCT_CONST::ALL_OPTION) 
		{
			switch ($invstat) {
				case PRODUCT_CONST::POSITIVE_INV:
					$conditions .= " AND PBI.`inventory` > 0";
					break;
				
				case PRODUCT_CONST::NEGATIVE_INV:
					$conditions .= " AND PBI.`inventory` < 0";
					break;

				case PRODUCT_CONST::ZERO_INV:
					$conditions .= " AND PBI.`inventory` = 0";
					break;
			}
		}

		switch ($orderby) 
		{
			case PRODUCT_CONST::ORDER_BY_NAME:
				$order_field = "P.`description`";
				break;
			
			case PRODUCT_CONST::ORDER_BY_CODE:
				$order_field = "P.`material_code`";
				break;
		}

		$query = "SELECT P.`id`, P.`material_code`, P.`description`,
						CASE 
							WHEN P.`type` = ".PRODUCT_CONST::NON_STOCK." THEN 'Non - Stock'
							WHEN P.`type` = ".PRODUCT_CONST::STOCK." THEN 'Stock'
						END AS 'type',
						COALESCE(M.`name`,'') AS 'material_type', COALESCE(S.`name`,'') AS 'subgroup', 
						COALESCE($inventory_column,0) AS 'inventory'
						FROM product AS P
						LEFT JOIN material_type AS M ON M.`id` = P.`material_type_id` AND M.`is_show` = ".PRODUCT_CONST::ACTIVE."
						LEFT JOIN subgroup AS S ON S.`id` = P.`subgroup_id` AND S.`is_show` = ".PRODUCT_CONST::ACTIVE."
						LEFT JOIN product_branch_inventory AS PBI ON PBI.`product_id` = P.`id` $inventory_join
						WHERE P.`is_show` = ".PRODUCT_CONST::ACTIVE." $conditions
						$group_by
						ORDER BY $order_field";

		$result = $this->db->query($query,$query_data);

		if ($result->num_rows() > 0) 
		{
			$i = 0;
			$response['rowcnt'] = $result->num_rows();

			foreach ($result->result() as $row) 
			{
				$response['data'][$i][] = array($this->encrypt->encode($row->id));
				$response['data'][$i][] = array($i+1);
				$response['data'][$i][] = array($row->material_code);
				$response['data'][$i][] = array($row->description);
				$response['data'][$i][] = array($row->type);
				$response['data'][$i][] = array($row->material_type);
				$response['data'][$i][] = array($row->subgroup);
				$response['data'][$i][] = array(number_format($row->inventory,0));
				$response['data'][$i][] = array('');
				$i++;
			}
		}

		return $response;
	}

	public function get_product_warning_list($param)
	{
		extract($param);

		$response 	= array();
		$response['rowcnt'] = 0;

		$conditions 		= "";
		$order_field 		= "";
		$group_by 			= "";
		$inventory_join 	= "";
		$inventory_column 	= "PBI.`inventory`";
		$query_data = array();

		if ($branch != PRODUCT_CONST::ALL_OPTION) 
		{
			$inventory_join = " AND PBI.`branch_id` = ?";
			array_push($query_data,$branch);
		}
		else
		{
			$inventory_column = "SUM(PBI.`inventory`)";
			$group_by = "GROUP BY P.`id`";
		}

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

		if ($subgroup != PRODUCT_CONST::ALL_OPTION) 
		{
			$conditions .= " AND P.`subgroup_id` = ?";
			array_push($query_data,$subgroup);
		}

		if ($material != PRODUCT_CONST::ALL_OPTION) 
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

		if ($type != PRODUCT_CONST::ALL_OPTION) 
		{
			switch ($type) 
			{
				case 1:
					$type = PRODUCT_CONST::STOCK;
					break;
				
				case 2:
					$type = PRODUCT_CONST::NON_STOCK;
					break;
			}

			$conditions .= " AND P.`type` = ?";
			array_push($query_data,$type);
		}

		switch ($orderby) 
		{
			case PRODUCT_CONST::ORDER_BY_NAME:
				$order_field = "P.`description`";
				break;
			
			case PRODUCT_CONST::ORDER_BY_CODE:
				$order_field = "P.`material_code`";
				break;
		}

		$query = "SELECT P.`id`, P.`material_code`, P.`description`,
						CASE
							 WHEN  PBI.`inventory` < PBI.`min_inv` THEN 'Insufficient'
							 WHEN  PBI.`inventory` > PBI.`max_inv` THEN 'Excess'	 
						END AS 'status',
						CASE 
							WHEN P.`type` = ".PRODUCT_CONST::NON_STOCK." THEN 'Non - Stock'
							WHEN P.`type` = ".PRODUCT_CONST::STOCK." THEN 'Stock'
						END AS 'type',
						COALESCE(M.`name`,'') AS 'material_type', COALESCE(S.`name`,'') AS 'subgroup', 
						COALESCE($inventory_column,0) AS 'inventory', PBI.`min_inv`, PBI. `max_inv`
						FROM product AS P
						LEFT JOIN material_type AS M ON M.`id` = P.`material_type_id` AND M.`is_show` = ".PRODUCT_CONST::ACTIVE."
						LEFT JOIN subgroup AS S ON S.`id` = P.`subgroup_id` AND S.`is_show` = ".PRODUCT_CONST::ACTIVE."
						LEFT JOIN product_branch_inventory AS PBI ON PBI.`product_id` = P.`id` $inventory_join
						WHERE (PBI.`inventory` > PBI.`max_inv` OR PBI.`inventory` < PBI.`min_inv`) AND P.`is_show` = ".PRODUCT_CONST::ACTIVE."
						$conditions
						$group_by
						ORDER BY $order_field";

		$result = $this->db->query($query,$query_data);

		if ($result->num_rows() > 0) 
		{
			$i = 0;
			$response['rowcnt'] = $result->num_rows();

			foreach ($result->result() as $row) 
			{
				$response['data'][$i][] = array($this->encrypt->encode($row->id));
				$response['data'][$i][] = array($i+1);
				$response['data'][$i][] = array($row->material_code);
				$response['data'][$i][] = array($row->description);
				$response['data'][$i][] = array($row->type);
				$response['data'][$i][] = array($row->material_type);
				$response['data'][$i][] = array($row->subgroup);
				$response['data'][$i][] = array($row->min_inv);
				$response['data'][$i][] = array($row->max_inv);
				$response['data'][$i][] = array(number_format($row->inventory,0));
				$response['data'][$i][] = array($row->status);
				$i++;
			}
		}

		$result->free_result();
		
		return $response;
	}

}
