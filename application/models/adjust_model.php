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
						LEFT JOIN inventory_adjust AS IA ON IA.`product_id` = P.`id` AND IA.`status` = ".ADJUST_CONST::REQUEST."
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

		return $response;
	}
}
