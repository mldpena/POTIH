<?php

namespace Services;

class Product_Manager
{
	private $_CI;
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

	public function __construct()
	{
		$this->_CI = $CI =& get_instance();

		$this->_current_branch_id 	= $this->_CI->encrypt->decode(get_cookie('branch'));
		$this->_current_user 		= $this->_CI->encrypt->decode(get_cookie('temp'));
		$this->_current_date 		= date("Y-m-d h:i:s");
	}

	/**
	 * Get product list result set based on product filters and format returned data
	 * @param  array $param [array of filters]
	 * @return array $response [formatted result set]
	 */
	public function get_product_list_info($param)
	{
		$response = array();

		$response['rowcnt'] = 0;

		$result = $this->_CI->product_model->get_product_list_by_filter($param);

		if ($result->num_rows() > 0) 
		{
			$i = 0;
			$response['rowcnt'] = $result->num_rows();

			foreach ($result->result() as $row) 
			{
				$response['data'][$i][] = array($this->_CI->encrypt->encode($row->id));
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

	/**
	 * Set minimum and maximum inventory per branch
	 * @return array $response [branch result set]
	 */
	public function get_min_max_per_branch()
	{
		$response = array();

		$branch_list = get_name_list_from_table(FALSE,'branch');
		
		$i = 0;

		foreach ($branch_list as $key => $value) 
		{
			$response['data'][$i][] = array(0);		
			$response['data'][$i][] = array($i+1);		
			$response['data'][$i][] = array($value,$key);		
			$response['data'][$i][] = array(0);		
			$response['data'][$i][] = array(0);	
			$i++;		
		}

		return $response;
	}

	/**
	 * Get material name, id and subgroup name, id by the first 2 characters of the inputted material code
	 * @param  array $param
	 * @return array $response [array of material and subgroup info]
	 */
	public function get_material_and_subgroup_by_character($param)
	{
		extract($param);

		$response 	= array();
		$response['error']			= '';
		$response['material_name']	= '';
		$response['material_id'] 	= 0;
		$response['subgroup_name'] 	= '';
		$response['subgroup_id'] 	= 0;

		$this->_CI->load->model('material_model');
		$this->_CI->load->model('subgroup_model');

		$result = $this->_CI->material_model->get_material_by_code($code[0]);

		if ($result->num_rows() == 1) 
		{
			$row = $result->row();
			$response['material_name']	= $row->name;
			$response['material_id'] 	= $row->id;
		}

		$result->free_result();

		$result = $this->_CI->subgroup_model->get_subgroup_by_code($code[1]);

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
	 * Validate product name and material code, and formatting the required field before inserting the new product
	 * @param  array $param
	 * @return array $response [Returns an error or a product id]
	 */
	public function insert_new_product_details($param)
	{
		extract($param);

		$response 	= array();
		$response['error']			= '';

		$this->_validate_product_details($code, $product);

		$product_field_data = array('material_code' => $code,
									'description' => $product,
									'type' => $is_nonstack,
									'material_type_id' => $material,
									'subgroup_id' => $subgroup,
									'date_created' => $this->_current_date,
									'created_by' => $this->_current_user);

		$branch_inventory_field_data = array();

		for ($i=0; $i < count($min_max_values); $i++) 
		{ 
			array_push($branch_inventory_field_data,array('branch_id' => $min_max_values[$i][1],
															'product_id' => 0,
															'inventory' => 0,
															'min_inv' => $min_max_values[$i][2],
															'max_inv' => $min_max_values[$i][3]));
		}

		$response['id'] = $this->_CI->product_model->insert_new_product_using_transaction($product_field_data,$branch_inventory_field_data);

		return $response;
	}

	/**
	 * Get product details and minimum and maximum inventory per branch based on product id using transaction
	 * @param  array $param [contains product id]
	 * @return array $response [list of product details, min and max values]
	 */
	public function get_product_details($param)
	{
		extract($param);

		$response = array();

		$response['error'] = '';

		$product_id = $this->_CI->encrypt->decode($product_id);

		$result = $this->_CI->product_model->get_product_details_by_id($product_id);

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

			$branch_inventory_result = $this->_CI->product_model->get_product_min_max_by_product_id($product_id);

			if ($branch_inventory_result->num_rows() != 0) 
			{
				$i = 0;
				foreach ($branch_inventory_result->result() as $row) 
				{
					$response['branch_inventory'][$i][] = array($this->_CI->encrypt->encode($row->id));
					$response['branch_inventory'][$i][] = array($i+1);
					$response['branch_inventory'][$i][] = array($row->branch,$row->branch_id);
					$response['branch_inventory'][$i][] = array($row->min_inv);
					$response['branch_inventory'][$i][] = array($row->max_inv);
					$i++;
				}
			}

			$branch_inventory_result->free_result();
		}
		else
			throw new \Exception($this->_error_message["UNABLE_TO_SELECT"]);

		$result->free_result();
		
		return $response;
	}

	/**
	 * Update product details, min and max inventory by product id using transaction
	 * @param  array $param [array of details for product and branch inventory]
	 */
	public function update_product_details($param)
	{
		extract($param);

		$product_id = $this->_CI->encrypt->decode($product_id);

		$this->_validate_product_details($code, $product, $product_id);

		$product_field_data = array('material_code' => $code,
									'description' => $product,
									'type' => $is_nonstack,
									'material_type_id' => $material,
									'subgroup_id' => $subgroup,
									'date_created' => $this->_current_date,
									'created_by' => $this->_current_user);

		$branch_inventory_field_data = array();

		for ($i=0; $i < count($min_max_values); $i++) 
		{ 
			array_push($branch_inventory_field_data,array(array('min_inv' => $min_max_values[$i][2],
																'max_inv' => $min_max_values[$i][3]), 
																$this->_CI->encrypt->decode($min_max_values[$i][0])));
		}

		$this->_CI->product_model->update_product_using_transaction($product_field_data,$branch_inventory_field_data, $product_id);
	}

	/**
	 * Delete selected product using id
	 * @param  array $param [product id]
	 */
	public function delete_product($param)
	{
		extract($param);

		$product_id = $this->_CI->encrypt->decode($head_id);

		$updated_product_fields = array('is_show' => \Constants\PRODUCT_CONST::DELETED,
										'last_modified_date' => $this->_current_date,
										'last_modified_by' => $this->_current_user);

		$affected_rows = $this->_CI->product_model->delete_product_by_id($updated_product_fields,$product_id);

		if ($affected_rows == 0)
			throw new \Exception($this->_error_message['UNABLE_TO_DELETE']);
	}

	/**
	 * Validate file type and import products from csv file. Validates material code and product name per row. 
	 * Beginning inventory will be set as an adjustment. 
	 */
	public function import_product_from_csv()
	{
		$this->_CI->load->model('branch_model');
		$this->_CI->load->model('material_model');
		$this->_CI->load->model('subgroup_model');
		$this->_CI->load->model('adjust_model');
		$this->_CI->load->model('product_model');
		$this->_CI->load->constant('product_const');

		$exploded_name 	= explode(".", $_FILES["file"]["name"]);
		$extension 		= end($exploded_name);

		$response = array();

		$response['error'] = '';

		$i = 0;

		if ($_FILES['file']['type'] == 'application/vnd.ms-excel' && $extension == 'csv')
		{
			$product_csv_file = $_FILES['file']['tmp_name'];
			$handle = fopen($product_csv_file,"r");

			$current_branch_list = array();
			$csv_branch_id_list = array();

			$branch_result = $this->_CI->branch_model->get_branch_list();

			foreach ($branch_result->result() as $row) 
				$current_branch_list[$row->name] = $row->id;

			$branch_result->free_result();

			while (($product_csv_data = fgetcsv($handle,10000))!==FALSE) 
			{
				$i++;
				if ($i != 1) 
				{
					$with_error 	= FALSE;
					$material_code 	= $product_csv_data[0];
					$product_name 	= $product_csv_data[1];
					$is_nonstack 	= strtolower($product_csv_data[2]) == 'yes' ? \Constants\PRODUCT_CONST::NON_STOCK : \Constants\PRODUCT_CONST::STOCK;
					$material_type_id = 0;
					$subgroup_id 	= 0;

					$result = $this->_CI->product_model->check_if_field_data_exists(array("`material_code`" => $material_code));
				
					if ($result->num_rows() > 0) 
					{
						$response['logs'][] = 'Row #'.$i." : Material Code [".$material_code."] already exists!";
						$with_error = TRUE;
					}

					$result->free_result();

					$result = $this->_CI->product_model->check_if_field_data_exists(array("`description`" => $product_name));

					if ($result->num_rows() > 0) 
					{
						$response['logs'][] = 'Row #'.$i." : Product Name [".$product_name."] already exists!";
						$with_error = TRUE;
					}
						
					$result->free_result();

					if ($is_nonstack == \Constants\PRODUCT_CONST::STOCK) 
					{
						$product_material_result = $this->_CI->material_model->get_material_by_code($material_code[0]);
						$product_subgroup_result = $this->_CI->subgroup_model->get_subgroup_by_code($material_code[1]);

						if($product_material_result->num_rows() == 0)
						{
							$response['logs'][] = 'Row #'.$i." : Product should have a valid material type!";
							$with_error = TRUE;
						}
						else
						{
							$row = $product_material_result->row();
							$material_type_id = $row->id;
						}

						if($product_subgroup_result->num_rows() == 0)
						{
							$response['logs'][] = 'Row #'.$i." : Product should have a valid subgroup!";
							$with_error = TRUE;
						}
						else
						{
							$row = $product_subgroup_result->row();
							$subgroup_id = $row->id;
						}

						$product_material_result->free_result();
						$product_subgroup_result->free_result();
					}

					if (!$with_error) 
					{
						$product_field_data = array('material_code' => $material_code,
												'description' => $product_name,
												'type' => $is_nonstack,
												'material_type_id' => $material_type_id,
												'subgroup_id' => $subgroup_id,
												'date_created' => $this->_current_date,
												'created_by' => $this->_current_user);

						$branch_inventory_field_data = array();
						$adjustment_field_data = array();

						for ($x=0; $x < count($csv_branch_id_list); $x++) 
						{ 
							array_push($branch_inventory_field_data, array('branch_id' => $csv_branch_id_list[$x],
																			'product_id' => 0,
																			'inventory' => 0,
																			'min_inv' => 0,
																			'max_inv' => 0));

							array_push($adjustment_field_data, array('branch_id' => $csv_branch_id_list[$x],
																	'product_id' => 0,
																	'old_inventory' => 0,
																	'new_inventory' => 0,
																	'is_show' => 1,
																	'status' => 2,
																	'memo' => 'Beginning Inventory',
																	'created_by' => $this->_current_user,
																	'date_created' => $this->_current_date));
						}

						$product_id = $this->_CI->encrypt->decode($this->_CI->product_model->insert_new_product_using_transaction($product_field_data, $branch_inventory_field_data));
						
						for ($x=3; $x < count($product_csv_data); $x++) 
						{ 
							$adjustment_field_counter = $x - 3;

							$adjustment_field_data[$adjustment_field_counter]['new_inventory'] 	= $product_csv_data[$x];
							$adjustment_field_data[$adjustment_field_counter]['product_id'] 	= $product_id;
						}
						
						$this->_CI->adjust_model->insert_inventory_adjust_for_import($adjustment_field_data);
						$response['logs'][] = 'Row #'.$i." : Successfully imported!";
					}
				}
				else
				{
					for ($x=3; $x < count($product_csv_data); $x++) 
						array_push($csv_branch_id_list,$current_branch_list[$product_csv_data[$x]]);
				}
			}
		}
		else
			$response['error'] = 'Invalid file type!';
		
		return $response;
	}

	public function write_logs_to_file($logs_list)
	{
		$string_logs = "";

   		for ($i=0; $i < count($logs_list); $i++)
   			$string_logs .= $logs_list[$i].PHP_EOL;
   		
   		file_put_contents("import_logs.txt", $string_logs);
	}

	/**
	 * Check if material code and product name exists
	 * @param  string  $material_code 
	 * @param  string  $product_name  
	 * @param  integer $current_id               
	 */
	private function _validate_product_details($material_code, $product_name, $current_id = 0)
	{
		$result = $this->_CI->product_model->check_if_field_data_exists(array("`material_code`" => $material_code), $current_id);

		if ($result->num_rows() > 0) 
			throw new \Exception($this->_error_message['CODE_EXISTS']);
			
		$result->free_result();

		$result = $this->_CI->product_model->check_if_field_data_exists(array("`description`" => $product_name), $current_id);

		if ($result->num_rows() > 0) 
			throw new \Exception($this->_error_message['NAME_EXISTS']);
			
		$result->free_result();
	}
}

?>