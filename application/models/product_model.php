<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Product_Model extends CI_Model {

	/**
	 * Load Encrypt Class for encryption, cookie and constants
	 */
	public function __construct() {
		$this->load->library('encrypt');
		$this->load->library('constants/product_const');
		$this->load->library('sql');
		$this->load->helper('cookie');
		parent::__construct();
	}

	public function get_product_details($param)
	{
		extract($param);

		$response = array();

		$response['error'] = '';

		$product_id = $this->encrypt->decode($product_id);

		$query = "SELECT P.`material_code`, P.`description`, P.`type`, P.`min_inv`, P.`max_inv`,
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
			$response['data']['min_inv'] 		= $row->min_inv;
			$response['data']['max_inv'] 		= $row->max_inv;
			$response['data']['material_type'] 	= $row->material_type;
			$response['data']['material_id'] 	= $row->material_type_id;
			$response['data']['subgroup'] 		= $row->subgroup;
			$response['data']['subgroup_id'] 	= $row->subgroup_id;
		}
		else
		{
			$response['error'] = 'Product not found!';
		}

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

	public function insert_new_product($param)
	{
		extract($param);
		$date_today = date('Y-m-d h:i:s');
		$user_id	= $this->encrypt->decode(get_cookie('temp'));

		$response 	= array();
		$query_data = array($code,$product,$is_nonstack,$material,$subgroup,$min_inv,$max_inv,$date_today,$date_today,$user_id,$user_id);
		$response['error'] = '';

 		$query = "INSERT INTO `product`
					(`material_code`,
					`description`,
					`type`,
					`material_type_id`,
					`subgroup_id`,
					`min_inv`,
					`max_inv`,
					`date_created`,
					`last_modified_date`,
					`created_by`,
					`last_modified_by`)
					VALUES
					(?,?,?,?,?,?,?,?,?,?,?);";

		$result = $this->sql->execute_query($query,$query_data);

		if ($result['error'] != '') 
		{
			$response['error'] = 'Unable to save product!';
		}
		else
		{
			$response['id'] = $result['id'];
		}

		return $response;
	}

	public function update_product_details($param)
	{
		extract($param);
		$date_today = date('Y-m-d h:i:s');
		$user_id	= $this->encrypt->decode(get_cookie('temp'));
		$product_id = $this->encrypt->decode($product_id);

		$response 	= array();
		$query_data = array($code,$product,$is_nonstack,$material,$subgroup,$min_inv,$max_inv,$date_today,$user_id,$product_id);
		$response['error'] = '';

		$query = "UPDATE `product`
					SET
					`material_code` = ?,
					`description` = ?,
					`type` = ?,
					`material_type_id` = ?,
					`subgroup_id` = ?,
					`min_inv` = ?,
					`max_inv` = ?,
					`last_modified_date` =?,
					`last_modified_by` = ?
					WHERE `id` = ?";

		$result = $this->sql->execute_query($query,$query_data);

		if ($result['error'] != '') 
		{
			$response['error'] = 'Unable to save product!';
		}
		else
		{
			$response['id'] = $result['id'];
		}

		return $response;

	}

	public function delete_product($param)
	{
		extract($param);

		$date_today = date('Y-m-d h:i:s');
		$user_id	= $this->encrypt->decode(get_cookie('temp'));
		$product_id = $this->encrypt->decode($product_id);

		$response = array();
		$response['error'] = '';

		$query_data = array($date_today,$user_id,$product_id);
		$query 	= "UPDATE `product` 
					SET 
					`is_show` = ".PRODUCT_CONST::DELETED.",
					`last_modified_date` = ?,
					`last_modified_by` = ?
					WHERE `id` = ?";

		$result = $this->sql->execute_query($query,$query_data);

		if ($result['error'] != '') 
		{
			$response['error'] = 'Unable to delete product!';
		}

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
						COALESCE(M.`name`,'') AS 'material_type', COALESCE(S.`name`,'') AS 'subgroup', COALESCE($inventory_column,0) AS 'inventory'
						FROM product AS P
						LEFT JOIN material_type AS M ON M.`id` = P.`material_type_id` AND M.`is_show` = ".PRODUCT_CONST::ACTIVE."
						LEFT JOIN subgroup AS S ON S.`id` = P.`subgroup_id` AND S.`is_show` = ".PRODUCT_CONST::ACTIVE."
						LEFT JOIN product_branch_inventory AS PBI ON PBI.`product_id` = P.`id` $inventory_join
						WHERE P.`is_show` = ".PRODUCT_CONST::ACTIVE." $conditions
						$group_by
						ORDER BY $order_field";

		$result = $this->db->query($query,$query_data);
		echo $this->db->last_query();
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
}
