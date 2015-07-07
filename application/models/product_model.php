<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Product_Model extends CI_Model {

	private $_current_branch_id = 0;
	private $_current_user = 0;
	private $_current_date = '';
	private $_error_message = array('CODE_EXISTS' => 'Material code already exists!',
									'NAME_EXISTS' => 'Product Name already exists!',
									'UNABLE_TO_INSERT' => 'Unable to insert product!',
									'UNABLE_TO_SAVE_INVENTORY' => 'Unable to insert min and max values!',
									'UNABLE_TO_UPDATE' => 'Unable to update product!',
									'UNABLE_TO_SELECT' => 'Unable to get select details!',
									'UNABLE_TO_DELETE' => 'Unable to delete product!',
									'UNABLE_TO_GET_TRANSACTION' => 'Error while processing your requests. Please try again.',
									'NO_TRANSACTION_FOUND' => 'No transaction found!');

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
			throw new Exception($this->_error_message['UNABLE_TO_SELECT']);

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

		$response 	= array();
		$query 		= array();
		$query_data = array();

		$response['error'] = '';

		$this->validate_product($param,PRODUCT_CONST::INSERT_PROCESS);

		$query_product = "INSERT INTO `product`
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

		$query_product_data = array($code,$product,$is_nonstack,$material,$subgroup,$this->_current_date,$this->_current_date,$this->_current_user,$this->_current_user);

		array_push($query,$query_product);
		array_push($query_data,$query_product_data);

		$query_last_insert_id = "SET @insert_id = LAST_INSERT_ID();";

		array_push($query,$query_last_insert_id);
		array_push($query_data,array());

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
			$bind_values .= ",(?,@insert_id,0,?,?)";	
			array_push($query_inventory_data,$min_max_values[$i][1],$min_max_values[$i][2],$min_max_values[$i][3]);
		}

		$query_inventory .= substr($bind_values,1).";";

		array_push($query,$query_inventory,"SELECT @insert_id AS 'id';");
		array_push($query_data,$query_inventory_data,array());

		$result = $this->sql->execute_transaction($query,$query_data);

		if ($result['error'] != '') 
			throw new Exception($this->_error_message['UNABLE_TO_INSERT']);
		else
			$response['id'] = $result['id'];

		return $response;
	}

	public function update_product_details($param)
	{
		extract($param);

		$response 	= array();
		$query 		= array();
		$query_data = array();
		$response['error'] = '';

		$this->validate_product($param,PRODUCT_CONST::UPDATE_PROCESS);

		$product_id = $this->encrypt->decode($product_id);

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
			throw new Exception($this->_error_message['UNABLE_TO_UPDATE']);
		else
			$response['id'] = $result['id'];

		return $response;

	}

	public function delete_product($param)
	{
		extract($param);

		$product_id = $this->encrypt->decode($head_id);

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

		$result->free_result();
		
		return $response;
	}

	public function get_product_warning_list($param)
	{
		extract($param);

		$response 	= array();
		$response['rowcnt'] = 0;

		$conditions 	= "";
		$order_field 	= "";
		$query_data 	= array($branch);

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
						COALESCE(PBI.`inventory`,0) AS 'inventory', PBI.`min_inv`, PBI. `max_inv`
						FROM product AS P
						LEFT JOIN material_type AS M ON M.`id` = P.`material_type_id` AND M.`is_show` = ".PRODUCT_CONST::ACTIVE."
						LEFT JOIN subgroup AS S ON S.`id` = P.`subgroup_id` AND S.`is_show` = ".PRODUCT_CONST::ACTIVE."
						LEFT JOIN product_branch_inventory AS PBI ON PBI.`product_id` = P.`id` AND PBI.`branch_id` = ?
						WHERE ((PBI.`inventory` > PBI.`max_inv` AND PBI.`max_inv` <> 0) OR (PBI.`inventory` < PBI.`min_inv` AND PBI.`min_inv` <> 0)) AND P.`is_show` = ".PRODUCT_CONST::ACTIVE."
						$conditions
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

	public function get_product_branch_inventory_list($param)
	{
		extract($param);

		$response 	= array();

		$response['rowcnt'] = 0;

		$conditions 		= "";
		$order_field 		= "";
		$query_data 		= array();
		$branch_column 		= "";
		$branch_list_id 	= implode(",",$branch);

		$query_branch= "SELECT `id`, `name` FROM branch WHERE `is_show` = ".PRODUCT_CONST::ACTIVE;
		$result_branch = $this->db->query($query_branch);

		if ($result_branch->num_rows() > 0) 
		{
			foreach ($result_branch->result() as $row) 
			{
				$branch_column .= ",SUM(IF(PBI.`branch_id` = ".$row->id.", PBI.`inventory`, 0)) AS '".$row->name."'";		
			}
		}
		
		$result_branch->free_result();
      
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

		if ($material !=  PRODUCT_CONST::ALL_OPTION) 
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

		if ($type !=  PRODUCT_CONST::ALL_OPTION) 
		{
			switch ($type) 
			{
				case 1:
					$type =  PRODUCT_CONST::STOCK;
					break;
				
				case 2:
					$type =  PRODUCT_CONST::NON_STOCK;
					break;
			}

			$conditions .= " AND P.`type` = ?";
			array_push($query_data,$type);
		}

		switch ($orderby) 
		{
			case  PRODUCT_CONST::ORDER_BY_NAME:
				$order_field = "P.`description`";
				break;
			
			case  PRODUCT_CONST::ORDER_BY_CODE:
				$order_field = "P.`material_code`";
				break;
		}

		$query = "SELECT P.`material_code`, P.`description`,
						CASE 
							WHEN P.`type` = ".PRODUCT_CONST::NON_STOCK." THEN 'Non - Stock'
							WHEN P.`type` = ".PRODUCT_CONST::STOCK." THEN 'Stock' 
						END AS 'type'
						$branch_column
						,SUM(`inventory`) AS 'total_inventory'
						FROM product AS P
						LEFT JOIN product_branch_inventory AS PBI ON PBI.`product_id` = P.`id`
						WHERE P.`is_show` = ".PRODUCT_CONST::ACTIVE." 
						$conditions
						GROUP BY P.`id`
						ORDER BY $order_field";

		$result = $this->db->query($query,$query_data);

		if ($result->num_rows() > 0) 
		{
			$i = 0;
			$response['rowcnt'] = $result->num_rows();

			foreach ($result->result() as $row) 
			{
				$response['data'][$i][] = array($i+1);
				
				foreach ($row as $key => $value)
      				$response['data'][$i][] = array($value);

				$i++;
			}
		}

		$result->free_result();

		return $response;
	}

	public function get_transaction_summary($param)
	{
		extract($param);

		$response 	= array();

		$response['rowcnt'] = 0;

		$conditions 	= "";
		$date_condition = "";
		$branch_condition = "";
		$order_field 	= "";
		$having 		= "";
		$temp_table 	= "";
		$temp_beginning = "0 AS 'beginv', ";
		$query_data 	= array();
      	

      	if ($branch != PRODUCT_CONST::ALL_OPTION)
      	{
      		$branch_condition = " AND TS.`branch_id` = ?";
      		array_push($query_data,$branch);
      	}

      	if ($is_include_date)
      	{
      		if (!empty($date_from)) 
      		{
      			$date_condition .= " AND TS.`date` >= ?";
				array_push($query_data,$date_from);

				$temp_beginning = "COALESCE(TEMP.`beginning_inventory`,0) AS 'beginv',";
				$temp_table = "LEFT JOIN temp_beginning_transaction AS TEMP ON TEMP.`product_id` = P.`id`";

				$query_truncate = "TRUNCATE temp_beginning_transaction;";
				$result_truncate = $this->sql->execute_query($query_truncate);

				if ($result_truncate['error'])
					throw new Exception($this->_error_message['UNABLE_TO_GET_TRANSACTION']);

				$query_temp = "INSERT INTO temp_beginning_transaction(`product_id`,`beginning_inventory`)
								SELECT P.`id`, COALESCE(SUM(`purchase_receive` + `customer_return` + `stock_receive` + `adjust_increase` 
													- `damage` - `purchase_return` - `stock_delivery` - `customer_delivery` 
													- `adjust_decrease` - `warehouse_release`),0) AS 'beginning_inventory'
									FROM product AS P
									LEFT JOIN daily_transaction_summary AS TS ON TS.`product_id` = P.`id` $branch_condition AND TS.`date` < ?
									WHERE P.`is_show` = ".PRODUCT_CONST::ACTIVE."
									GROUP BY P.`id`";

				$result_temp = $this->sql->execute_query($query_temp,$query_data);

				if ($result_temp['error'])
					throw new Exception($this->_error_message['UNABLE_TO_GET_TRANSACTION']);
      		}

      		if (!empty($date_to)) 
      		{
      			$date_condition .= " AND TS.`date` <= ?";
				array_push($query_data,$date_to);
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

		if ($subgroup != PRODUCT_CONST::ALL_OPTION) 
		{
			$conditions .= " AND P.`subgroup_id` = ?";
			array_push($query_data,$subgroup);
		}

		if ($material !=  PRODUCT_CONST::ALL_OPTION) 
		{
			$conditions .= " AND P.`material_type_id` = ?";
			array_push($query_data,$material);
		}

		if ($type !=  PRODUCT_CONST::ALL_OPTION) 
		{
			switch ($type) 
			{
				case 1:
					$type =  PRODUCT_CONST::STOCK;
					break;
				
				case 2:
					$type =  PRODUCT_CONST::NON_STOCK;
					break;
			}

			$conditions .= " AND P.`type` = ?";
			array_push($query_data,$type);
		}

		switch ($orderby) 
		{
			case  PRODUCT_CONST::ORDER_BY_NAME:
				$order_field = "P.`description`";
				break;
			
			case  PRODUCT_CONST::ORDER_BY_CODE:
				$order_field = "P.`material_code`";
				break;
		}

		if ($purchase_receive == PRODUCT_CONST::WITH_TRANSACTION)
			$having .= " OR purchase_receive > 0";
		else if ($purchase_receive == PRODUCT_CONST::WITHOUT_TRANSACTION)
			$having .= " OR purchase_receive = 0";

		if ($customer_return == PRODUCT_CONST::WITH_TRANSACTION)
			$having .= " OR customer_return > 0";
		else if ($customer_return == PRODUCT_CONST::WITHOUT_TRANSACTION)
			$having .= " OR customer_return = 0";

		if ($stock_receive == PRODUCT_CONST::WITH_TRANSACTION)
			$having .= " OR stock_receive > 0";
		else if ($stock_receive == PRODUCT_CONST::WITHOUT_TRANSACTION)
			$having .= " OR stock_receive = 0";

		if ($adjust_increase == PRODUCT_CONST::WITH_TRANSACTION)
			$having .= " OR adjust_increase > 0";
		else if ($adjust_increase == PRODUCT_CONST::WITHOUT_TRANSACTION)
			$having .= " OR adjust_increase = 0";

		if ($damage == PRODUCT_CONST::WITH_TRANSACTION)
			$having .= " OR damage > 0";
		else if ($damage == PRODUCT_CONST::WITHOUT_TRANSACTION)
			$having .= " OR damage = 0";

		if ($purchase_return == PRODUCT_CONST::WITH_TRANSACTION)
			$having .= " OR purchase_return > 0";
		else if ($purchase_return == PRODUCT_CONST::WITHOUT_TRANSACTION)
			$having .= " OR purchase_return = 0";

		if ($stock_delivery == PRODUCT_CONST::WITH_TRANSACTION)
			$having .= " OR stock_delivery > 0";
		else if ($stock_delivery == PRODUCT_CONST::WITHOUT_TRANSACTION)
			$having .= " OR stock_delivery = 0";

		if ($customer_delivery == PRODUCT_CONST::WITH_TRANSACTION)
			$having .= " OR customer_delivery > 0";
		else if ($customer_delivery == PRODUCT_CONST::WITHOUT_TRANSACTION)
			$having .= " OR customer_delivery = 0";

		if ($adjust_decrease == PRODUCT_CONST::WITH_TRANSACTION)
			$having .= " OR adjust_decrease > 0";
		else if ($adjust_decrease == PRODUCT_CONST::WITHOUT_TRANSACTION)
			$having .= " OR adjust_decrease = 0";

		if ($release == PRODUCT_CONST::WITH_TRANSACTION)
			$having .= " OR release > 0";
		else if ($release == PRODUCT_CONST::WITHOUT_TRANSACTION)
			$having .= " OR release = 0";

		if (!empty($having)) 
			$having = "HAVING (".substr($having, 4).")";

		$query = "SELECT P.`material_code`, P.`description` AS 'product', 
						CASE 
							WHEN P.`type` = ".PRODUCT_CONST::NON_STOCK." THEN 'Non-Stock'
							WHEN P.`type` = ".PRODUCT_CONST::STOCK." THEN 'Stock'
						END AS 'type',
						$temp_beginning
						COALESCE(SUM(TS.`purchase_receive`),0) AS 'purchase_receive',
						COALESCE(SUM(TS.`customer_return`),0) AS 'customer_return',
						COALESCE(SUM(TS.`stock_receive`),0) AS 'stock_receive',
						COALESCE(SUM(TS.`adjust_increase`),0) AS 'adjust_increase',
						COALESCE(SUM(TS.`damage`),0) AS 'damage',
						COALESCE(SUM(TS.`purchase_return`),0) AS 'purchase_return',
						COALESCE(SUM(TS.`stock_delivery`),0) AS 'stock_delivery',
						COALESCE(SUM(TS.`customer_delivery`),0) AS 'customer_delivery',
						COALESCE(SUM(TS.`adjust_decrease`),0) AS 'adjust_decrease',
						COALESCE(SUM(TS.`warehouse_release`),0) AS 'release'
				FROM product AS P
				$temp_table
				LEFT JOIN daily_transaction_summary AS TS ON TS.`product_id` = P.`id` $branch_condition $date_condition
				WHERE P.`is_show` = ".PRODUCT_CONST::ACTIVE." $conditions
				GROUP BY P.`id`
				$having
				ORDER BY $order_field";

		$result = $this->db->query($query,$query_data);

		if ($result->num_rows() > 0) 
		{
			$i = 0;
			$response['rowcnt'] = $result->num_rows();

			foreach ($result->result() as $row) 
			{
				$response['data'][$i][] = array($i+1);
				
				foreach ($row as $key => $value)
      				$response['data'][$i][] = array($value);

      			$response['data'][$i][] = array($row->beginv + $row->purchase_receive + $row->customer_return + $row->stock_receive 
      											+ $row->adjust_increase - $row->damage - $row->purchase_return - $row->stock_delivery - $row->customer_delivery 
      											- $row->adjust_decrease - $row->release);

				$i++;
			}
		}

		$result->free_result();

		return $response;
	}

	public function get_product_name()
	{
		$response = array();

		$response['error'] = '';

		$product_id = $this->encrypt->decode($this->uri->segment(3));

		$query = "SELECT `id`, `description` AS 'product' FROM product WHERE `id` = ?";

		$result = $this->db->query($query,$product_id);

		if ($result->num_rows() != 1)
			throw new Exception($this->_error_message['UNABLE_TO_SELECT']);
		else
		{
			$row = $result->row();
			$response['product_id'] = $row->id;
			$response['product_name'] = $row->product;
		}

		$result->free_result();

		return $response;
	}

	public function get_transaction_record($param)
	{
		extract($param);

		$response 	= array();

		$response['error'] = '';

		$conditions 	= "";
		$date_condition = "";
		$branch_condition = "";
		$order_field 	= "";
		$having 		= "";
		$temp_table 	= "";
		$temp_beginning = "0 AS 'beginv', ";
		$query_data 	= array();
      	

      	if ($branch != PRODUCT_CONST::ALL_OPTION)
      	{
      		$branch_condition = " AND TS.`branch_id` = ?";
      		array_push($query_data,$branch);
      	}

  		if (!empty($date_from)) 
  		{
  			$date_condition .= " AND TS.`date` >= ?";
			array_push($query_data,$date_from);

			$temp_beginning = "COALESCE(TEMP.`beginning_inventory`,0) AS 'beginv',";
			$temp_table = "LEFT JOIN temp_beginning_transaction AS TEMP ON TEMP.`product_id` = P.`id`";

			$query_truncate = "TRUNCATE temp_beginning_transaction;";
			$result_truncate = $this->sql->execute_query($query_truncate);

			if ($result_truncate['error'])
				throw new Exception($this->_error_message['UNABLE_TO_GET_TRANSACTION']);

			$query_temp_data = $query_data;
			array_push($query_temp_data,$product_id);

			$query_temp = "INSERT INTO temp_beginning_transaction(`product_id`,`beginning_inventory`)
							SELECT P.`id`, COALESCE(SUM(`purchase_receive` + `customer_return` + `stock_receive` + `adjust_increase` 
												- `damage` - `purchase_return` - `stock_delivery` - `customer_delivery` 
												- `adjust_decrease` - `warehouse_release`),0) AS 'beginning_inventory'
								FROM product AS P
								LEFT JOIN daily_transaction_summary AS TS ON TS.`product_id` = P.`id` $branch_condition AND TS.`date` < ?
								WHERE P.`is_show` = ".PRODUCT_CONST::ACTIVE." AND P.`id` = ?
								GROUP BY P.`id`";

			$result_temp = $this->sql->execute_query($query_temp,$query_temp_data);

			if ($result_temp['error'])
				throw new Exception($this->_error_message['UNABLE_TO_GET_TRANSACTION']);
  		}

  		if (!empty($date_to)) 
  		{
  			$date_condition .= " AND TS.`date` <= ?";
			array_push($query_data,$date_to);
  		}

  		array_push($query_data,$product_id);

		$query = "SELECT 
						$temp_beginning
						COALESCE(SUM(TS.`purchase_receive`),0) AS 'purchase_receive',
						COALESCE(SUM(TS.`customer_return`),0) AS 'customer_return',
						COALESCE(SUM(TS.`stock_receive`),0) AS 'stock_receive',
						COALESCE(SUM(TS.`adjust_increase`),0) AS 'adjust_increase',
						COALESCE(SUM(TS.`damage`),0) AS 'damage',
						COALESCE(SUM(TS.`purchase_return`),0) AS 'purchase_return',
						COALESCE(SUM(TS.`stock_delivery`),0) AS 'stock_delivery',
						COALESCE(SUM(TS.`customer_delivery`),0) AS 'customer_delivery',
						COALESCE(SUM(TS.`adjust_decrease`),0) AS 'adjust_decrease',
						COALESCE(SUM(TS.`warehouse_release`),0) AS 'release'
				FROM product AS P
				$temp_table
				LEFT JOIN daily_transaction_summary AS TS ON TS.`product_id` = P.`id` $branch_condition $date_condition
				WHERE P.`is_show` = ".PRODUCT_CONST::ACTIVE." AND P.`id` = ?
				GROUP BY P.`id`";

		$result = $this->db->query($query,$query_data);

		if ($result->num_rows() > 0) 
		{
			$i = 0;
			$response['rowcnt'] = $result->num_rows();

			foreach ($result->result() as $row) 
			{
				foreach ($row as $key => $value)
      				$response['data'][$i][] = array($value);

      			$response['data'][$i][] = array($row->beginv + $row->purchase_receive + $row->customer_return + $row->stock_receive 
      											+ $row->adjust_increase - $row->damage - $row->purchase_return - $row->stock_delivery - $row->customer_delivery 
      											- $row->adjust_decrease - $row->release);

				$i++;
			}
		}

		$result->free_result();

		return $response;	
	}

	public function get_transaction_breakdown($param)
	{
		extract($param);

		$response 	= array();
		$custom_query_module = array('adjustdec','adjustinc');

		$response['error'] = '';

		$conditions 	= "";
		$query_data 	= array();

		if (in_array($module_access,$custom_query_module)) 
		{
			$adjust_condition = ($module_access == 'adjustinc') ? '>' : '<';
			$absolute = ($module_access == 'adjustinc') ? '' : '* -1';

			if ($branch != PRODUCT_CONST::ALL_OPTION)
	      	{
	      		$conditions = " AND IA.`branch_id` = ?";
	      		array_push($query_data,$branch);
	      	}

	  		if (!empty($date_from)) 
	  		{
	  			$conditions .= " AND IA.`date_created` >= ?";
				array_push($query_data,$date_from.' 00:00:00');
	  		}

	  		if (!empty($date_to)) 
	  		{
	  			$conditions .= " AND IA.`date_created` <= ?";
				array_push($query_data,$date_to.' 00:00:00');
	  		}

	  		array_push($query_data,$product_id);

			$query = "SELECT DATE(IA.`date_created`) AS 'entry_date', '' AS 'reference_number', 
						(IA.`new_inventory` - IA.`old_inventory`) $absolute AS 'quantity', 
						COALESCE(U.`full_name`,'') AS 'prepared_by', IA.`memo` 
						FROM inventory_adjust AS IA
						LEFT JOIN user AS U ON U.`id` = IA.`created_by`
						WHERE IA.`is_show` = ".PRODUCT_CONST::ACTIVE." AND IA.`status` = 2 $conditions
						AND IA.`product_id` = ? AND (IA.`new_inventory` - IA.`old_inventory`) $adjust_condition 0
						ORDER BY IA.`date_created`";
		}
		else
		{
			$head_table 			= "";
			$detail_table 			= "";
			$reference_character 	= "";
			$additional_condition 	= "";
			$quantity_column 		= "D.`quantity`";
			$date_column 			= "H.`entry_date`";
			$branch_column 			= "H.`branch_id`";

			switch ($module_access) 
			{
				case 'purchasereceive':
					$head_table 	= "purchase_receive_head";
					$detail_table 	= "purchase_receive_detail";
					$reference_character = "PR";
					break;

				case 'customereturn':
					$head_table 	= "return_head";
					$detail_table 	= "return_detail";
					$reference_character = "RD";
					break;

				case 'stockreceive':
					$head_table 	= "stock_delivery_head";
					$detail_table 	= "stock_delivery_detail";
					$reference_character = "SD";
					$additional_condition = " AND D.`is_for_branch` = 1";
					$quantity_column = "D.`recv_quantity`";
					$date_column = "H.`delivery_receive_date`";
					$branch_column = "H.`to_branchid`";
					break;

				case 'damage':
					$head_table 	= "damage_head";
					$detail_table 	= "damage_detail";
					$reference_character = "DD";
					break;

				case 'purchasereturn':
					$head_table 	= "purchase_return_head";
					$detail_table 	= "purchase_return_detail";
					$reference_character = "PR";
					break;

				case 'stockdelivery':
					$head_table 	= "stock_delivery_head";
					$detail_table 	= "stock_delivery_detail";
					$reference_character = "SD";
					$additional_condition = " AND D.`is_for_branch` = 1";
					$date_column = "H.`entry_date`";
					break;

				case 'customerdelivery':
					$head_table 	= "stock_delivery_head";
					$detail_table 	= "stock_delivery_detail";
					$reference_character = "SD";
					$additional_condition = " AND D.`is_for_branch` = 0";
					$quantity_column = "D.`recv_quantity`";
					$date_column = "H.`customer_receive_date`";
					break;

				case 'release':
					$head_table 	= "release_head";
					$detail_table 	= "release_detail";
					$reference_character = "WR";
					break;
			}

	      	if ($branch != PRODUCT_CONST::ALL_OPTION)
	      	{
	      		$conditions = " AND $branch_column = ?";
	      		array_push($query_data,$branch);
	      	}

	  		if (!empty($date_from)) 
	  		{
	  			$conditions .= " AND H.`entry_date` >= ?";
				array_push($query_data,$date_from.' 00:00:00');
	  		}

	  		if (!empty($date_to)) 
	  		{
	  			$conditions .= " AND H.`entry_date` <= ?";
				array_push($query_data,$date_to.' 00:00:00');
	  		}

	  		array_push($query_data,$product_id);

			$query = "SELECT DATE($date_column) AS 'entry_date', CONCAT('$reference_character',H.`reference_number`) AS 'reference_number', 
							SUM($quantity_column) AS 'quantity', COALESCE(U.`full_name`,'') AS 'prepared_by', H.`memo`
							FROM $head_table AS H
							LEFT JOIN $detail_table AS D ON D.`headid` = H.`id`
							LEFT JOIN user AS U ON U.`id` = H.`created_by`
							WHERE H.`is_show` = ".PRODUCT_CONST::ACTIVE." $conditions AND D.`product_id` = ?
							$additional_condition
							GROUP BY H.`id`
							ORDER BY $date_column";

		}

		$result = $this->db->query($query,$query_data);

		if ($result->num_rows() > 0) 
		{
			$i = 0;

			foreach ($result->result() as $row) 
			{
				foreach ($row as $key => $value)
      				$response['data'][$i][] = array($value);

				$i++;
			}
		}
		else
			throw new Exception($this->_error_message['NO_TRANSACTION_FOUND']);

		$result->free_result();

		return $response;
	}

	private function validate_product($param, $function_type)
	{
		extract($param);

		$query = "SELECT * FROM product WHERE `material_code` = ? AND `is_show` = ".PRODUCT_CONST::ACTIVE;
		$query .= $function_type == PRODUCT_CONST::INSERT_PROCESS ? "" : " AND `id` <> ?";

		$query_data = array($code);
		if ($function_type == PRODUCT_CONST::UPDATE_PROCESS) 
		{
			$id = $this->encrypt->decode($product_id);
			array_push($query_data,$id);
		}

		$result = $this->db->query($query,$query_data);

		if ($result->num_rows() > 0) 
			throw new Exception($this->_error_message['CODE_EXISTS']);
			
		$result->free_result();

		$query = "SELECT * FROM product WHERE `description` = ? AND `is_show` = ".PRODUCT_CONST::ACTIVE;
		$query .= $function_type == PRODUCT_CONST::INSERT_PROCESS ? "" : " AND `id` <> ?";

		$query_data = array($product);

		if ($function_type == PRODUCT_CONST::UPDATE_PROCESS) 
		{
			$id = $this->encrypt->decode($product_id);
			array_push($query_data,$id);
		}

		$result = $this->db->query($query,$query_data);

		if ($result->num_rows() > 0) 
			throw new Exception($this->_error_message['NAME_EXISTS']);
			
		$result->free_result();
	}
}
