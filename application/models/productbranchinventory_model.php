<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Productbranchinventory_Model extends CI_Model {

	/**
	 * Load Encrypt Class for encryption, cookie and constants
	 */
	public function __construct() {
		$this->load->library('encrypt');
		$this->load->file(CONSTANTS.'productinventory_const.php');
		$this->load->library('sql');
		$this->load->helper('cookie');
		parent::__construct();
	}

 	public function get_productbranchinventory_list($param)
	{
		extract($param);

		$response 	= array();
		$response['rowcnt'] = 0;
		$conditions 		= "";
		$order_field 		= "";
		$query_data = array(implode(",",$branch));
		$branch_column="";

		$query_branch= "SELECT `id`, `name` FROM branch WHERE `is_show`= 1 ";
		$result_branch = $this->db->query($query_branch);

		if ($result_branch->num_rows() > 0) 
			{

			foreach ($result_branch->result() as $row1) 
			{
		
		$branch_column .= ",(IF(PBI.`branch_id` = ".$row1->id.", PBI.`inventory`, 0)) AS '".$row1->name."'";
			}
      			
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

		if ($subgroup != PRODUCTINVENTORY_CONST::ALL_OPTION) 
		{
			$conditions .= " AND P.`subgroup_id` = ?";
			array_push($query_data,$subgroup);
		}

		if ($material !=  PRODUCTINVENTORY_CONST::ALL_OPTION) 
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

		if ($type !=  PRODUCTINVENTORY_CONST::ALL_OPTION) 
		{
			switch ($type) 
			{
				case 1:
					$type =  PRODUCTINVENTORY_CONST::STOCK;
					break;
				
				case 2:
					$type =  PRODUCTINVENTORY_CONST::NON_STOCK;
					break;
			}

			$conditions .= " AND P.`type` = ?";
			array_push($query_data,$type);
		}

		

		switch ($orderby) 
		{
			case  PRODUCTINVENTORY_CONST::ORDER_BY_NAME:
				$order_field = "P.`description`";
				break;
			
			case  PRODUCTINVENTORY_CONST::ORDER_BY_CODE:
				$order_field = "P.`material_code`";
				break;
		}

		$query = "SELECT P.`id`, P.`material_code`, P.`description`,
						CASE 
							WHEN P.`type` = ".PRODUCTINVENTORY_CONST::NON_STOCK." THEN 'Non - Stock'
							WHEN P.`type` = ".PRODUCTINVENTORY_CONST::STOCK." THEN 'Stock' 

						END AS 'type' $branch_column

						FROM product AS P
						LEFT JOIN product_branch_inventory AS PBI ON PBI.`product_id` = P.`id` AND PBI.`branch_id` IN(?)
						WHERE P.`is_show` = ".PRODUCTINVENTORY_CONST::ACTIVE." 
						$conditions
						GROUP BY P.`id`
						ORDER BY $order_field"
						;


		$result = $this->db->query($query,$query_data);

		if ($result->num_rows() > 0) 
		{
			$i = 0;
			$response['rowcnt'] = $result->num_rows();

			foreach ($result->result() as $row) 
			{
				
		
		
				$counter = 0;
				foreach ($row as $key => $value) {
					if ($key == 'id') {
						$value = $this->encrypt->encode($value);
					}elseif ($counter == 1) {
						$response['data'][$i][] = array($i+1);
					}
      				$response['data'][$i][] = array($value);
      				$counter++;
      			}
				$i++;
			}
		
		}
      	

    
		return $response;
	}
	


}
