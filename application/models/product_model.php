<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Product_Model extends CI_Model {

	private $_current_branch_id = 0;
	private $_current_user 	= 0;
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
		parent::__construct();

		$this->_current_branch_id 	= (int)$this->encrypt->decode(get_cookie('branch'));
		$this->_current_user 		= (int)$this->encrypt->decode(get_cookie('temp'));
	}

	public function insert_new_product_using_transaction($product_field_data, $branch_inventory_field_data)
	{
		$response = array();

		$new_product_id = 0;

		$this->db->trans_start();

			$this->db->insert('product', $product_field_data);

			$new_product_id = $this->db->insert_id();

			for ($i=0; $i < count($branch_inventory_field_data); $i++) 
				$branch_inventory_field_data[$i]["product_id"] = $new_product_id;

			$this->db->insert_batch('product_branch_inventory', $branch_inventory_field_data);

		$this->db->trans_complete();

		$response['error'] 	= $this->db->error()['message'];
		$response['id'] 	= $this->encrypt->encode($new_product_id);

		return $response;
	}

	public function update_product_using_transaction($product_field_data, $branch_inventory_field_data, $product_id)
	{
		$this->db->trans_start();

			$this->db->where("id", $product_id);
			$this->db->update("product", $product_field_data);

			for ($i=0; $i < count($branch_inventory_field_data); $i++)
			{
				$branch_inventory_id = $branch_inventory_field_data[$i][1];

				$this->db->where("id", $branch_inventory_id);
				$this->db->update("product_branch_inventory", $branch_inventory_field_data[$i][0]);
			}

		$this->db->trans_complete();

		return $this->db->error()['message'];
	}

	public function get_product_details_by_id($product_id)
	{
		$this->db->select("P.`material_code`, P.`description`,
						COALESCE(P.`type`, '') AS 'type',
						COALESCE(M.`name`,'') AS 'material_type', COALESCE(S.`name`,'') AS 'subgroup',
						P.`material_type_id`, P.`subgroup_id`, COALESCE(P.`uom`, '') AS 'uom'")
				->from("product AS P")
				->join("material_type AS M", "M.`id` = P.`material_type_id` AND M.`is_show` = ".\Constants\PRODUCT_CONST::ACTIVE, "left")
				->join("subgroup AS S", "S.`id` = P.`subgroup_id` AND S.`is_show` = ".\Constants\PRODUCT_CONST::ACTIVE, "left")
				->where("P.`is_show`", \Constants\PRODUCT_CONST::ACTIVE)
				->where("P.`id`", $product_id);

		$result = $this->db->get();

		return $result;
	}

	public function get_product_min_max_by_product_id($product_id)
	{
		$this->db->select("PBI.`id`, COALESCE(CONCAT(B.`code`,' - ',B.`name`),'') AS 'branch', 
							COALESCE(B.`id`,0) AS 'branch_id', PBI.`min_inv`, PBI.`max_inv`")
				->from("product_branch_inventory AS PBI")
				->join("branch AS B", "B.`id` = PBI.`branch_id`")
				->where("B.`is_show`", \Constants\PRODUCT_CONST::ACTIVE)
				->where("PBI.`product_id`", $product_id);

		$result = $this->db->get();

		return $result;
	}

	public function update_product_by_id($product_fields, $product_id)
	{
		$this->db->where("`id`", $product_id);

		$this->db->update("product", $product_fields);

		return $this->db->affected_rows();
	}

	public function get_product_list_by_filter($param, $with_limit = TRUE)
	{
		extract($param);

		$product_branch_inventory_condition = "";

		$this->db->select("P.`id`, P.`material_code`, P.`description`,
							CASE 
								WHEN P.`type` = ".\Constants\PRODUCT_CONST::NON_STOCK." THEN 'Non - Stock'
								WHEN P.`type` = ".\Constants\PRODUCT_CONST::STOCK." THEN 'Stock'
								ELSE ''
							END AS 'type',
							COALESCE(M.`name`,'') AS 'material_type', COALESCE(S.`name`,'') AS 'subgroup'");

		if ($branch != \Constants\PRODUCT_CONST::ALL_OPTION)
		{
			$product_branch_inventory_condition = " AND PBI.`branch_id` = ".$this->db->escape($branch);
			$this->db->select("COALESCE(PBI.`inventory`) AS 'inventory'");
		} 
		else
			$this->db->select("COALESCE(SUM(PBI.`inventory`)) AS 'inventory'");

		$this->db->from("product AS P")
				->join("material_type AS M", "M.`id` = P.`material_type_id` AND M.`is_show` = ".\Constants\PRODUCT_CONST::ACTIVE, "left")
				->join("subgroup AS S", "S.`id` = P.`subgroup_id` AND S.`is_show` = ".\Constants\PRODUCT_CONST::ACTIVE, "left")
				->join("product_branch_inventory AS PBI", "PBI.`product_id` = P.`id` $product_branch_inventory_condition", "left")
				->where("P.`is_show`",\Constants\PRODUCT_CONST::ACTIVE);

		if (!empty($code)) 
			$this->db->like("P.`material_code`", $code, "both");

		if (!empty($product)) 
			$this->db->like("P.`description`", $product, "both");

		if ($subgroup != \Constants\PRODUCT_CONST::ALL_OPTION) 
			$this->db->where("P.`subgroup_id`", $subgroup);

		if ($material != \Constants\PRODUCT_CONST::ALL_OPTION) 
			$this->db->where("P.`material_type_id`", $material);

		if (!empty($datefrom))
			$this->db->where("P.`date_created` >=", $datefrom.' 00:00:00');

		if (!empty($dateto))
			$this->db->where("P.`date_created` <=", $dateto.' 23:59:59');

		if ($invstat != \Constants\PRODUCT_CONST::ALL_OPTION && $branch != \Constants\PRODUCT_CONST::ALL_OPTION) 
		{
			switch ($invstat) {
				case \Constants\PRODUCT_CONST::POSITIVE_INV:
					$this->db->where("PBI.`inventory` > ",0);
					break;
				
				case \Constants\PRODUCT_CONST::NEGATIVE_INV:
					$this->db->where("PBI.`inventory` < ",0);
					break;

				case \Constants\PRODUCT_CONST::ZERO_INV:
					$this->db->where("PBI.`inventory`",0);
					break;
			}
		}

		if ($type != \Constants\PRODUCT_CONST::ALL_OPTION) 
		{
			switch ($type) 
			{
				case 1:
					$type = \Constants\PRODUCT_CONST::STOCK;
					break;
				
				case 2:
					$type = \Constants\PRODUCT_CONST::NON_STOCK;
					break;
			}

			$this->db->where("P.`type`",$type);
		}

		if ($branch == \Constants\PRODUCT_CONST::ALL_OPTION)
			$this->db->group_by("P.`id`");

		switch ($orderby) 
		{
			case \Constants\PRODUCT_CONST::ORDER_BY_NAME:
				$order_field = "P.`description`";
				break;
			
			case \Constants\PRODUCT_CONST::ORDER_BY_CODE:
				$order_field = "P.`material_code`";
				break;
		}

		$this->db->order_by($order_field,"DESC");

		if ($invstat != \Constants\PRODUCT_CONST::ALL_OPTION && $branch == \Constants\PRODUCT_CONST::ALL_OPTION) 
		{
			$comparison_operator = '';

			switch ($invstat) {
				case \Constants\PRODUCT_CONST::POSITIVE_INV:
					$comparison_operator = '>';
					break;
				
				case \Constants\PRODUCT_CONST::NEGATIVE_INV:
					$comparison_operator = '<';
					break;

				case \Constants\PRODUCT_CONST::ZERO_INV:
					$comparison_operator = '=';
					break;
			}

			$this->db->having("inventory $comparison_operator", 0);
		}

		if ($with_limit) 
		{
			$limit = $row_end - $row_start + 1;
			$this->db->limit($limit, $row_start);
		}
					

		$result = $this->db->get();

		return $result;		
	}

	public function get_product_list_count_by_filter($param)
	{
		extract($param);

		$product_branch_inventory_condition = "";

		if ($branch != \Constants\PRODUCT_CONST::ALL_OPTION)
		{
			$product_branch_inventory_condition = " AND PBI.`branch_id` = ".$this->db->escape($branch);
			$this->db->select("COALESCE(PBI.`inventory`) AS 'inventory'");
		} 
		else
			$this->db->select("COALESCE(SUM(PBI.`inventory`)) AS 'inventory'");

		$this->db->from("product AS P")
				->join("product_branch_inventory AS PBI", "PBI.`product_id` = P.`id` $product_branch_inventory_condition", "left")
				->where("P.`is_show`",\Constants\PRODUCT_CONST::ACTIVE);

		if (!empty($code)) 
			$this->db->like("P.`material_code`", $code, "both");

		if (!empty($product)) 
			$this->db->like("P.`description`", $product, "both");

		if ($subgroup != \Constants\PRODUCT_CONST::ALL_OPTION) 
			$this->db->where("P.`subgroup_id`", $subgroup);

		if ($material != \Constants\PRODUCT_CONST::ALL_OPTION) 
			$this->db->where("P.`material_type_id`", $material);

		if (!empty($datefrom))
			$this->db->where("P.`date_created` >=", $datefrom.' 00:00:00');

		if (!empty($dateto))
			$this->db->where("P.`date_created` <=", $dateto.' 23:59:59');

		if ($invstat != \Constants\PRODUCT_CONST::ALL_OPTION && $branch != \Constants\PRODUCT_CONST::ALL_OPTION) 
		{
			switch ($invstat) {
				case \Constants\PRODUCT_CONST::POSITIVE_INV:
					$this->db->where("PBI.`inventory` > ",0);
					break;
				
				case \Constants\PRODUCT_CONST::NEGATIVE_INV:
					$this->db->where("PBI.`inventory` < ",0);
					break;

				case \Constants\PRODUCT_CONST::ZERO_INV:
					$this->db->where("PBI.`inventory`",0);
					break;
			}
		}

		if ($type != \Constants\PRODUCT_CONST::ALL_OPTION) 
		{
			switch ($type) 
			{
				case 1:
					$type = \Constants\PRODUCT_CONST::STOCK;
					break;
				
				case 2:
					$type = \Constants\PRODUCT_CONST::NON_STOCK;
					break;
			}

			$this->db->where("P.`type`",$type);
		}

		if ($branch == \Constants\PRODUCT_CONST::ALL_OPTION)
			$this->db->group_by("P.`id`");

		if ($invstat != \Constants\PRODUCT_CONST::ALL_OPTION && $branch == \Constants\PRODUCT_CONST::ALL_OPTION) 
		{
			$comparison_operator = '';

			switch ($invstat) {
				case \Constants\PRODUCT_CONST::POSITIVE_INV:
					$comparison_operator = '>';
					break;
				
				case \Constants\PRODUCT_CONST::NEGATIVE_INV:
					$comparison_operator = '<';
					break;

				case \Constants\PRODUCT_CONST::ZERO_INV:
					$comparison_operator = '=';
					break;
			}

			$this->db->having("inventory $comparison_operator",0);
		}

		$inner_query = $this->db->get_compiled_select();

		$query_count = "SELECT COUNT(*) AS rowCount FROM ($inner_query)A";

		$result = $this->db->query($query_count);
		$row 	= $result->row();
		$count 	= $row->rowCount;

		$result->free_result();

		return $count;
	}

	public function get_product_by_term($term, $branch_id, $with_inventory)
	{
		$this->db->select("P.`description`, P.`id`, P.`material_code`, P.`type`, 
							CASE
								WHEN P.`uom` = 1 THEN 'PCS'
								WHEN P.`uom` = 2 THEN 'KGS'
								WHEN P.`uom` = 3 THEN 'ROLL'
								ELSE ''
							END AS 'uom'");

		if ($with_inventory)
			$this->db->select("COALESCE(PBI.`inventory`,0) AS 'inventory'");

		$this->db->from("`product` AS P");

		if ($with_inventory)
			$this->db->join("`product_branch_inventory` AS PBI","PBI.`product_id` = P.`id` AND PBI.`branch_id` = ".$this->db->escape($branch_id),"left");

		$this->db->where("P.`is_show`", 1)
				->group_start()
					->like("P.`description`", $term, "both")
					->or_like("P.`material_code`", $term, "both")
				->group_end()
				->limit(10);

		$result = $this->db->get();

		return $result;
	}

	public function get_product_inventory_info($product_id, $branch_id)
	{
		$this->db->select("`inventory` AS 'current_inventory', `min_inv`, `max_inv`")
				->from("`product_branch_inventory`")
				->where("`product_id`", $product_id)
				->where("`branch_id`", $branch_id);

		$result = $this->db->get();

		return $result;
	}

	public function check_if_field_data_exists($field_data, $current_product_id = 0)
	{
		$this->db->select("`id`")
				->from("product")
				->where("`is_show`", \Constants\PRODUCT_CONST::ACTIVE);

		if ($current_product_id !== 0)
			$this->db->where("`id` <>", $current_product_id);

		foreach ($field_data as $key => $value) 
			$this->db->where($key, $value);

		$result = $this->db->get();

		return $result;
	}


	public function get_product_warning_list_by_filter($param, $with_limit = TRUE)
	{
		extract($param);

		$this->db->select("P.`id`, P.`material_code`, P.`description`,
							CASE
								WHEN  PBI.`inventory` < 0 THEN 'Negative'
								WHEN  PBI.`inventory` < PBI.`min_inv` THEN 'Insufficient'
								WHEN  PBI.`inventory` > PBI.`max_inv` THEN 'Excess'	 
							END AS 'status',
							CASE 
								WHEN P.`type` = ".\Constants\PRODUCT_CONST::NON_STOCK." THEN 'Non - Stock'
								WHEN P.`type` = ".\Constants\PRODUCT_CONST::STOCK." THEN 'Stock'
								ELSE ''
							END AS 'type',
							COALESCE(M.`name`,'') AS 'material_type', COALESCE(S.`name`,'') AS 'subgroup', 
							COALESCE(PBI.`inventory`,0) AS 'inventory', PBI.`min_inv`, PBI. `max_inv`")
				->from("product AS P")
				->join("material_type AS M", "M.`id` = P.`material_type_id` AND M.`is_show` = ".\Constants\PRODUCT_CONST::ACTIVE, "left")
				->join("subgroup AS S", "S.`id` = P.`subgroup_id` AND S.`is_show` = ".\Constants\PRODUCT_CONST::ACTIVE, "left")
				->join("product_branch_inventory AS PBI", "PBI.`product_id` = P.`id` AND PBI.`branch_id` = $branch", "left")
				->group_start()
					->group_start()
						->where("PBI.`inventory` > PBI.`max_inv`")
						->where("PBI.`max_inv` <>", 0)
					->group_end()
					->or_group_start()
						->where("PBI.`inventory` < PBI.`min_inv`")
						->where("PBI.`min_inv` <>", 0)
						->where("PBI.`inventory` >", 0)
					->group_end()
					->or_group_start()
						->where("PBI.`inventory` <", 0)
						->where("PBI.`min_inv` <>", 0)
					->group_end()
				->group_end()
				->where("P.`is_show`", \Constants\PRODUCT_CONST::ACTIVE);

		if (!empty($code)) 
			$this->db->like("P.`material_code`", $code, "both");

		if (!empty($product)) 
			$this->db->like("P.`description`", $product, "both");

		if ($subgroup != \Constants\PRODUCT_CONST::ALL_OPTION) 
			$this->db->where("P.`subgroup_id`", $subgroup);

		if ($material != \Constants\PRODUCT_CONST::ALL_OPTION) 
			$this->db->where("P.`material_type_id`", $material);

		if (!empty($datefrom))
			$this->db->where("P.`date_created` >=", $datefrom.' 00:00:00');

		if (!empty($dateto))
			$this->db->where("P.`date_created` <=", $dateto.' 23:59:59');

		if ($type != \Constants\PRODUCT_CONST::ALL_OPTION) 
		{
			switch ($type) 
			{
				case 1:
					$type = \Constants\PRODUCT_CONST::STOCK;
					break;
				
				case 2:
					$type = \Constants\PRODUCT_CONST::NON_STOCK;
					break;
			}

			$this->db->where("P.`type`",$type);
		}

		switch ($orderby) 
		{
			case \Constants\PRODUCT_CONST::ORDER_BY_NAME:
				$order_field = "P.`description`";
				break;
			
			case \Constants\PRODUCT_CONST::ORDER_BY_CODE:
				$order_field = "P.`material_code`";
				break;
		}

		$this->db->order_by($order_field,"DESC");

		if ($with_limit) 
		{
			$limit = $row_end - $row_start + 1;
			$this->db->limit((int)$limit, (int)$row_start);
		}

		$result = $this->db->get();

		return $result;
	}

	public function get_product_warning_list_count_by_filter($param)
	{
		extract($param);

		$this->db->from("product AS P")
				->join("product_branch_inventory AS PBI", "PBI.`product_id` = P.`id` AND PBI.`branch_id` = $branch", "left")
				->group_start()
					->group_start()
						->where("PBI.`inventory` > PBI.`max_inv`")
						->where("PBI.`max_inv` <>", 0)
					->group_end()
					->or_group_start()
						->where("PBI.`inventory` < PBI.`min_inv`")
						->where("PBI.`min_inv` <>", 0)
						->where("PBI.`inventory` >", 0)
					->group_end()
					->or_group_start()
						->where("PBI.`inventory` <", 0)
						->where("PBI.`min_inv` <>", 0)
					->group_end()
				->group_end()
				->where("P.`is_show`", \Constants\PRODUCT_CONST::ACTIVE);

		if (!empty($code)) 
			$this->db->like("P.`material_code`", $code, "both");

		if (!empty($product)) 
			$this->db->like("P.`description`", $product, "both");

		if ($subgroup != \Constants\PRODUCT_CONST::ALL_OPTION) 
			$this->db->where("P.`subgroup_id`", $subgroup);

		if ($material != \Constants\PRODUCT_CONST::ALL_OPTION) 
			$this->db->where("P.`material_type_id`", $material);

		if (!empty($datefrom))
			$this->db->where("P.`date_created` >=", $datefrom.' 00:00:00');

		if (!empty($dateto))
			$this->db->where("P.`date_created` <=", $dateto.' 23:59:59');

		if ($type != \Constants\PRODUCT_CONST::ALL_OPTION) 
		{
			switch ($type) 
			{
				case 1:
					$type = \Constants\PRODUCT_CONST::STOCK;
					break;
				
				case 2:
					$type = \Constants\PRODUCT_CONST::NON_STOCK;
					break;
			}

			$this->db->where("P.`type`",$type);
		}

		return $this->db->count_all_results();
	}

	public function get_product_branch_inventory_list_by_filter($param, $branch_column, $with_limit = TRUE)
	{
		extract($param);

		$this->db->select("P.`material_code`, P.`description`,
						CASE 
							WHEN P.`type` = ".\Constants\PRODUCT_CONST::NON_STOCK." THEN 'Non - Stock'
							WHEN P.`type` = ".\Constants\PRODUCT_CONST::STOCK." THEN 'Stock'
							ELSE ''
						END AS 'type'
						$branch_column
						,SUM(`inventory`) AS 'total_inventory'")
				->from("product AS P")
				->join("product_branch_inventory AS PBI", "PBI.`product_id` = P.`id`", "left")
				->where("P.`is_show`", \Constants\PRODUCT_CONST::ACTIVE);

		if (!empty($code)) 
			$this->db->like("P.`material_code`", $code, "both");

		if (!empty($product)) 
			$this->db->like("P.`description`", $product, "both");

		if ($subgroup != \Constants\PRODUCT_CONST::ALL_OPTION) 
			$this->db->where("P.`subgroup_id`", $subgroup);

		if ($material != \Constants\PRODUCT_CONST::ALL_OPTION) 
			$this->db->where("P.`material_type_id`", $material);

		if (!empty($datefrom))
			$this->db->where("P.`date_created` >=", $datefrom.' 00:00:00');

		if (!empty($dateto))
			$this->db->where("P.`date_created` <=", $dateto.' 23:59:59');

		if ($type != \Constants\PRODUCT_CONST::ALL_OPTION) 
		{
			switch ($type) 
			{
				case 1:
					$type = \Constants\PRODUCT_CONST::STOCK;
					break;
				
				case 2:
					$type = \Constants\PRODUCT_CONST::NON_STOCK;
					break;
			}

			$this->db->where("P.`type`",$type);
		}

		$this->db->group_by("P.`id`");

		switch ($orderby) 
		{
			case \Constants\PRODUCT_CONST::ORDER_BY_NAME:
				$order_field = "P.`description`";
				break;
			
			case \Constants\PRODUCT_CONST::ORDER_BY_CODE:
				$order_field = "P.`material_code`";
				break;
		}

		$this->db->order_by($order_field,"DESC");

		if ($with_limit) 
		{
			$limit = $row_end - $row_start + 1;
			$this->db->limit((int)$limit, (int)$row_start);
		}

		$result = $this->db->get();

		return $result;
	}

	public function get_product_branch_inventory_list_count_by_filter($param)
	{
		extract($param);

		$this->db->from("product AS P")
				->where("P.`is_show`", \Constants\PRODUCT_CONST::ACTIVE);

		if (!empty($code)) 
			$this->db->like("P.`material_code`", $code, "both");

		if (!empty($product)) 
			$this->db->like("P.`description`", $product, "both");

		if ($subgroup != \Constants\PRODUCT_CONST::ALL_OPTION) 
			$this->db->where("P.`subgroup_id`", $subgroup);

		if ($material != \Constants\PRODUCT_CONST::ALL_OPTION) 
			$this->db->where("P.`material_type_id`", $material);

		if (!empty($datefrom))
			$this->db->where("P.`date_created` >=", $datefrom.' 00:00:00');

		if (!empty($dateto))
			$this->db->where("P.`date_created` <=", $dateto.' 23:59:59');

		if ($type != \Constants\PRODUCT_CONST::ALL_OPTION) 
		{
			switch ($type) 
			{
				case 1:
					$type = \Constants\PRODUCT_CONST::STOCK;
					break;
				
				case 2:
					$type = \Constants\PRODUCT_CONST::NON_STOCK;
					break;
			}

			$this->db->where("P.`type`",$type);
		}

		return $this->db->count_all_results();
	}
	
	public function get_transaction_summary_by_filter($param, $with_limit = TRUE)
	{
		extract($param);

		$conditions 	= "";
		$date_condition = "";
		$branch_condition = "";
		$order_field 	= "";
		$having 		= "";
		$temp_table 	= "";
		$temp_beginning = "0 AS 'beginv', ";
		$query_data 	= array();
      	

      	if ($branch != \Constants\PRODUCT_CONST::ALL_OPTION)
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

				$query_temp = "CREATE TEMPORARY TABLE temp_beginning_transaction
								(
									product_id BIGINT NOT NULL DEFAULT 0,
								    beginning_inventory INT NOT NULL DEFAULT 0,
									INDEX idx_productid (product_id)
								)
								SELECT P.`id` AS 'product_id', COALESCE(SUM(`purchase_receive` + `customer_return` + `stock_receive` + `adjust_increase` 
												- `damage` - `purchase_return` - `stock_delivery` - `customer_delivery` 
												- `adjust_decrease` - `warehouse_release`),0) AS 'beginning_inventory'
								FROM product AS P
								LEFT JOIN daily_transaction_summary AS TS ON TS.`product_id` = P.`id` $branch_condition AND TS.`date` < ?
								WHERE P.`is_show` = ".\Constants\PRODUCT_CONST::ACTIVE."
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

		if ($subgroup != \Constants\PRODUCT_CONST::ALL_OPTION) 
		{
			$conditions .= " AND P.`subgroup_id` = ?";
			array_push($query_data,$subgroup);
		}

		if ($material !=  \Constants\PRODUCT_CONST::ALL_OPTION) 
		{
			$conditions .= " AND P.`material_type_id` = ?";
			array_push($query_data,$material);
		}

		if ($type !=  \Constants\PRODUCT_CONST::ALL_OPTION) 
		{
			switch ($type) 
			{
				case 1:
					$type =  \Constants\PRODUCT_CONST::STOCK;
					break;
				
				case 2:
					$type =  \Constants\PRODUCT_CONST::NON_STOCK;
					break;
			}

			$conditions .= " AND P.`type` = ?";
			array_push($query_data,$type);
		}

		switch ($orderby) 
		{
			case  \Constants\PRODUCT_CONST::ORDER_BY_NAME:
				$order_field = "P.`description`";
				break;
			
			case  \Constants\PRODUCT_CONST::ORDER_BY_CODE:
				$order_field = "P.`material_code`";
				break;
		}

		if ($purchase_receive == \Constants\PRODUCT_CONST::WITH_TRANSACTION)
			$having .= " OR `purchase_receive` > 0";
		else if ($purchase_receive == \Constants\PRODUCT_CONST::WITHOUT_TRANSACTION)
			$having .= " OR `purchase_receive` = 0";

		if ($customer_return == \Constants\PRODUCT_CONST::WITH_TRANSACTION)
			$having .= " OR `customer_return` > 0";
		else if ($customer_return == \Constants\PRODUCT_CONST::WITHOUT_TRANSACTION)
			$having .= " OR `customer_return` = 0";

		if ($stock_receive == \Constants\PRODUCT_CONST::WITH_TRANSACTION)
			$having .= " OR `stock_receive` > 0";
		else if ($stock_receive == \Constants\PRODUCT_CONST::WITHOUT_TRANSACTION)
			$having .= " OR `stock_receive` = 0";

		if ($adjust_increase == \Constants\PRODUCT_CONST::WITH_TRANSACTION)
			$having .= " OR `adjust_increase` > 0";
		else if ($adjust_increase == \Constants\PRODUCT_CONST::WITHOUT_TRANSACTION)
			$having .= " OR `adjust_increase` = 0";

		if ($damage == \Constants\PRODUCT_CONST::WITH_TRANSACTION)
			$having .= " OR `damage` > 0";
		else if ($damage == \Constants\PRODUCT_CONST::WITHOUT_TRANSACTION)
			$having .= " OR `damage` = 0";

		if ($purchase_return == \Constants\PRODUCT_CONST::WITH_TRANSACTION)
			$having .= " OR `purchase_return` > 0";
		else if ($purchase_return == \Constants\PRODUCT_CONST::WITHOUT_TRANSACTION)
			$having .= " OR `purchase_return` = 0";

		if ($stock_delivery == \Constants\PRODUCT_CONST::WITH_TRANSACTION)
			$having .= " OR `stock_delivery` > 0";
		else if ($stock_delivery == \Constants\PRODUCT_CONST::WITHOUT_TRANSACTION)
			$having .= " OR `stock_delivery` = 0";

		if ($customer_delivery == \Constants\PRODUCT_CONST::WITH_TRANSACTION)
			$having .= " OR `customer_delivery` > 0";
		else if ($customer_delivery == \Constants\PRODUCT_CONST::WITHOUT_TRANSACTION)
			$having .= " OR `customer_delivery` = 0";

		if ($adjust_decrease == \Constants\PRODUCT_CONST::WITH_TRANSACTION)
			$having .= " OR `adjust_decrease` > 0";
		else if ($adjust_decrease == \Constants\PRODUCT_CONST::WITHOUT_TRANSACTION)
			$having .= " OR `adjust_decrease` = 0";

		if ($release == \Constants\PRODUCT_CONST::WITH_TRANSACTION)
			$having .= " OR `release` > 0";
		else if ($release == \Constants\PRODUCT_CONST::WITHOUT_TRANSACTION)
			$having .= " OR `release` = 0";

		if (!empty($having)) 
			$having = "HAVING (".substr($having, 4).")";

		$query = "SELECT P.`material_code`, P.`description` AS 'product', 
						CASE 
							WHEN P.`type` = ".\Constants\PRODUCT_CONST::NON_STOCK." THEN 'Non-Stock'
							WHEN P.`type` = ".\Constants\PRODUCT_CONST::STOCK." THEN 'Stock'
							ELSE ''
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
				WHERE P.`is_show` = ".\Constants\PRODUCT_CONST::ACTIVE." $conditions
				GROUP BY P.`id`
				$having
				ORDER BY $order_field";

		if ($with_limit) 
		{
			$limit = $row_end - $row_start + 1;
			$this->db->limit((int)$limit, (int)$row_start);
		}

		$result = $this->db->query($query,$query_data);

		return $result;
	}

	public function get_transaction_summary_count_by_filter($param)
	{
		extract($param);

		$conditions 	= "";
		$date_condition = "";
		$branch_condition = "";
		$order_field 	= "";
		$having 		= "";
		$temp_table 	= "";
		$temp_beginning = "0 AS 'beginv', ";
		$query_data 	= array();
      	

      	if ($branch != \Constants\PRODUCT_CONST::ALL_OPTION)
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

				$query_temp = "CREATE TEMPORARY TABLE temp_beginning_transaction
								(
									product_id BIGINT NOT NULL DEFAULT 0,
								    beginning_inventory INT NOT NULL DEFAULT 0,
									INDEX idx_productid (product_id)
								)
								SELECT P.`id` AS 'product_id', COALESCE(SUM(`purchase_receive` + `customer_return` + `stock_receive` + `adjust_increase` 
												- `damage` - `purchase_return` - `stock_delivery` - `customer_delivery` 
												- `adjust_decrease` - `warehouse_release`),0) AS 'beginning_inventory'
								FROM product AS P
								LEFT JOIN daily_transaction_summary AS TS ON TS.`product_id` = P.`id` $branch_condition AND TS.`date` < ?
								WHERE P.`is_show` = ".\Constants\PRODUCT_CONST::ACTIVE."
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

		if ($subgroup != \Constants\PRODUCT_CONST::ALL_OPTION) 
		{
			$conditions .= " AND P.`subgroup_id` = ?";
			array_push($query_data,$subgroup);
		}

		if ($material !=  \Constants\PRODUCT_CONST::ALL_OPTION) 
		{
			$conditions .= " AND P.`material_type_id` = ?";
			array_push($query_data,$material);
		}

		if ($type !=  \Constants\PRODUCT_CONST::ALL_OPTION) 
		{
			switch ($type) 
			{
				case 1:
					$type =  \Constants\PRODUCT_CONST::STOCK;
					break;
				
				case 2:
					$type =  \Constants\PRODUCT_CONST::NON_STOCK;
					break;
			}

			$conditions .= " AND P.`type` = ?";
			array_push($query_data,$type);
		}

		switch ($orderby) 
		{
			case  \Constants\PRODUCT_CONST::ORDER_BY_NAME:
				$order_field = "P.`description`";
				break;
			
			case  \Constants\PRODUCT_CONST::ORDER_BY_CODE:
				$order_field = "P.`material_code`";
				break;
		}

		if ($purchase_receive == \Constants\PRODUCT_CONST::WITH_TRANSACTION)
			$having .= " OR `purchase_receive` > 0";
		else if ($purchase_receive == \Constants\PRODUCT_CONST::WITHOUT_TRANSACTION)
			$having .= " OR `purchase_receive` = 0";

		if ($customer_return == \Constants\PRODUCT_CONST::WITH_TRANSACTION)
			$having .= " OR `customer_return` > 0";
		else if ($customer_return == \Constants\PRODUCT_CONST::WITHOUT_TRANSACTION)
			$having .= " OR `customer_return` = 0";

		if ($stock_receive == \Constants\PRODUCT_CONST::WITH_TRANSACTION)
			$having .= " OR `stock_receive` > 0";
		else if ($stock_receive == \Constants\PRODUCT_CONST::WITHOUT_TRANSACTION)
			$having .= " OR `stock_receive` = 0";

		if ($adjust_increase == \Constants\PRODUCT_CONST::WITH_TRANSACTION)
			$having .= " OR `adjust_increase` > 0";
		else if ($adjust_increase == \Constants\PRODUCT_CONST::WITHOUT_TRANSACTION)
			$having .= " OR `adjust_increase` = 0";

		if ($damage == \Constants\PRODUCT_CONST::WITH_TRANSACTION)
			$having .= " OR `damage` > 0";
		else if ($damage == \Constants\PRODUCT_CONST::WITHOUT_TRANSACTION)
			$having .= " OR `damage` = 0";

		if ($purchase_return == \Constants\PRODUCT_CONST::WITH_TRANSACTION)
			$having .= " OR `purchase_return` > 0";
		else if ($purchase_return == \Constants\PRODUCT_CONST::WITHOUT_TRANSACTION)
			$having .= " OR `purchase_return` = 0";

		if ($stock_delivery == \Constants\PRODUCT_CONST::WITH_TRANSACTION)
			$having .= " OR `stock_delivery` > 0";
		else if ($stock_delivery == \Constants\PRODUCT_CONST::WITHOUT_TRANSACTION)
			$having .= " OR `stock_delivery` = 0";

		if ($customer_delivery == \Constants\PRODUCT_CONST::WITH_TRANSACTION)
			$having .= " OR `customer_delivery` > 0";
		else if ($customer_delivery == \Constants\PRODUCT_CONST::WITHOUT_TRANSACTION)
			$having .= " OR `customer_delivery` = 0";

		if ($adjust_decrease == \Constants\PRODUCT_CONST::WITH_TRANSACTION)
			$having .= " OR `adjust_decrease` > 0";
		else if ($adjust_decrease == \Constants\PRODUCT_CONST::WITHOUT_TRANSACTION)
			$having .= " OR `adjust_decrease` = 0";

		if ($release == \Constants\PRODUCT_CONST::WITH_TRANSACTION)
			$having .= " OR `release` > 0";
		else if ($release == \Constants\PRODUCT_CONST::WITHOUT_TRANSACTION)
			$having .= " OR `release` = 0";

		if (!empty($having)) 
			$having = "HAVING (".substr($having, 4).")";

		$query = "SELECT COUNT(*) AS 'rowcnt' FROM
				(
					SELECT P.`material_code`, P.`description` AS 'product', 
							CASE 
								WHEN P.`type` = ".\Constants\PRODUCT_CONST::NON_STOCK." THEN 'Non-Stock'
								WHEN P.`type` = ".\Constants\PRODUCT_CONST::STOCK." THEN 'Stock'
								ELSE ''
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
					WHERE P.`is_show` = ".\Constants\PRODUCT_CONST::ACTIVE." $conditions
					GROUP BY P.`id`
					$having
				)A";
		
		$result = $this->db->query($query, $query_data);

		$row = $result->row();

		return $row->rowcnt;
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
      	

      	if ($branch != \Constants\PRODUCT_CONST::ALL_OPTION)
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

			$query_temp_data = $query_data;

			array_push($query_temp_data,$product_id);

			$query_temp = "CREATE TEMPORARY TABLE temp_beginning_transaction
								(
									product_id BIGINT NOT NULL DEFAULT 0,
								    beginning_inventory INT NOT NULL DEFAULT 0,
									INDEX idx_productid (product_id)
								)
								SELECT P.`id` AS 'product_id', COALESCE(SUM(`purchase_receive` + `customer_return` + `stock_receive` + `adjust_increase` 
												- `damage` - `purchase_return` - `stock_delivery` - `customer_delivery` 
												- `adjust_decrease` - `warehouse_release`),0) AS 'beginning_inventory'
								FROM product AS P
								LEFT JOIN daily_transaction_summary AS TS ON TS.`product_id` = P.`id` $branch_condition AND TS.`date` < ?
								WHERE P.`is_show` = ".\Constants\PRODUCT_CONST::ACTIVE." AND P.`id` = ?
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
				WHERE P.`is_show` = ".\Constants\PRODUCT_CONST::ACTIVE." AND P.`id` = ?
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

			if ($branch != \Constants\PRODUCT_CONST::ALL_OPTION)
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
						WHERE IA.`is_show` = ".\Constants\PRODUCT_CONST::ACTIVE." AND IA.`status` = 2 $conditions
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
			$link_location 			= "";

			switch ($module_access) 
			{
				case 'purchasereceive':
					$head_table 	= "purchase_receive_head";
					$detail_table 	= "purchase_receive_detail";
					$reference_character = "PR";
					$link_location 	= "poreceive";
					break;

				case 'customereturn':
					$head_table 	= "return_head";
					$detail_table 	= "return_detail";
					$reference_character = "RD";
					$link_location 	= "return";
					break;

				case 'stockreceive':
					$head_table 	= "stock_delivery_head";
					$detail_table 	= "stock_delivery_detail";
					$reference_character = "SD";
					$additional_condition = " AND D.`is_for_branch` = 1";
					$quantity_column = "D.`recv_quantity`";
					$date_column = "H.`delivery_receive_date`";
					$branch_column = "H.`to_branchid`";
					$link_location 	= "delreceive";
					break;

				case 'damage':
					$head_table 	= "damage_head";
					$detail_table 	= "damage_detail";
					$reference_character = "DD";
					$link_location 	= "damage";
					break;

				case 'purchasereturn':
					$head_table 	= "purchase_return_head";
					$detail_table 	= "purchase_return_detail";
					$reference_character = "PR";
					$link_location 	= "purchaseret";
					break;

				case 'stockdelivery':
					$head_table 	= "stock_delivery_head";
					$detail_table 	= "stock_delivery_detail";
					$reference_character = "SD";
					$additional_condition = " AND D.`is_for_branch` = 1";
					$date_column = "H.`entry_date`";
					$link_location 	= "delivery";
					break;

				case 'customerdelivery':
					$head_table 	= "stock_delivery_head";
					$detail_table 	= "stock_delivery_detail";
					$reference_character = "SD";
					$additional_condition = " AND D.`is_for_branch` = 0";
					$date_column = "H.`customer_receive_date`";
					$link_location 	= "custreceive";
					break;

				case 'release':
					$head_table 	= "release_head";
					$detail_table 	= "release_detail";
					$reference_character = "WR";
					$link_location 	= "release";
					break;
			}

	      	if ($branch != \Constants\PRODUCT_CONST::ALL_OPTION)
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
							SUM($quantity_column) AS 'quantity', COALESCE(U.`full_name`,'') AS 'prepared_by', H.`memo`, H.`id`
							FROM $head_table AS H
							LEFT JOIN $detail_table AS D ON D.`headid` = H.`id`
							LEFT JOIN user AS U ON U.`id` = H.`created_by`
							WHERE H.`is_show` = ".\Constants\PRODUCT_CONST::ACTIVE." $conditions AND D.`product_id` = ?
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
				{
					if ($key == 'reference_number') 
					{
						if (!in_array($module_access, $custom_query_module)) 
							$value = "<a href ='".base_url()."$link_location/view/".$this->encrypt->encode($row->id)."'>".$value."</a>";
					}
					else if ($key == 'id')
						continue;

      				$response['data'][$i][] = array($value);
				}

				$i++;
			}
		}
		else
			throw new Exception($this->_error_message['NO_TRANSACTION_FOUND']);

		$result->free_result();

		return $response;
	}

	public function get_product_warning_count($warning_type)
	{
		$this->db->from("product AS P, product_branch_inventory AS PBI")
						->where("P.`is_show`", 1)
						->where("P.`type`", 1)
						->where("PBI.`product_id` = P.`id` AND PBI.`branch_id` = ".$this->_current_branch_id);

		switch ($warning_type) 
		{
			case 'MAX':
				$this->db->where("PBI.`inventory` > PBI.`max_inv`")
						->where("PBI.`max_inv` <>", 0);
						
				break;
			
			case 'MIN':
				$this->db->where("PBI.`inventory` < PBI.`min_inv`")
						->where("PBI.`inventory` >", 0)
						->where("PBI.`min_inv` <>", 0);		
				break;

			case 'NEGATIVE':
				$this->db->where("PBI.`inventory` < ", 0)
						->where("PBI.`min_inv` <> ", 0);
				break;
		}

		return $this->db->count_all_results();
	}
}
