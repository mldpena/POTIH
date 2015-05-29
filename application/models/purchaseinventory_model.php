<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Purchaseinventory_Model extends CI_Model {

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
		public function get_purchaseinventory_list($param)
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
							 WHEN  PBI.`inventory`  <  PBI.`min_inv` THEN 'Insufficient'
							 WHEN  PBI.`inventory`  > PBI.`max_inv` THEN 'excess'
							 
						 END AS 'status',
						CASE 
							WHEN P.`type` = ".PRODUCT_CONST::NON_STOCK." THEN 'Non - Stock'
							WHEN P.`type` = ".PRODUCT_CONST::STOCK." THEN 'Stock'
						END AS 'type',
						COALESCE(M.`name`,'') AS 'material_type', COALESCE(S.`name`,'') AS 'subgroup', COALESCE($inventory_column,0) AS 'inventory1',PBI.`min_inv`, PBI. `max_inv`
						
						FROM product AS P
						LEFT JOIN material_type AS M ON M.`id` = P.`material_type_id` AND M.`is_show` = ".PRODUCT_CONST::ACTIVE."
						LEFT JOIN subgroup AS S ON S.`id` = P.`subgroup_id` AND S.`is_show` = ".PRODUCT_CONST::ACTIVE."
						LEFT JOIN product_branch_inventory AS PBI ON PBI.`product_id` = P.`id` $inventory_join
						WHERE PBI.`inventory`  > PBI.`max_inv` OR PBI.`inventory`  <  PBI.`min_inv`  AND   P.`is_show` = ".PRODUCT_CONST::ACTIVE."
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
				$response['data'][$i][] = array(number_format($row->inventory1,0));
				$response['data'][$i][] = array($row->status);
				
				$i++;
			}
		}

		return $response;
	}

	
	
}
