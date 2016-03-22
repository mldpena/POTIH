<?php

namespace Services;

class Product_Manager
{
	private $_CI;
	private $_own_sales_reservation_manager;
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
		$this->_current_date 		= date("Y-m-d H:i:s");
	}

	/**
	 * Get product list result set based on product filters and format returned data
	 * @param  array $param [array of filters]
	 * @return array $response [formatted result set]
	 */
	public function get_product_list_info($param)
	{
		$row_start = (int)$param['row_start'];
		
		$response = array();

		$response['rowcnt'] = 0;

		$result = $this->_CI->product_model->get_product_list_by_filter($param);

		if ($result->num_rows() > 0) 
		{
			$i = 0;
			
			$response['rowcnt'] = $this->_CI->product_model->get_product_list_count_by_filter($param);

			foreach ($result->result() as $row) 
			{
				$response['data'][$i][] = array($this->_CI->encrypt->encode($row->id));
				$response['data'][$i][] = array($row_start + $i + 1);
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

		$this->_validate_product_details($code, $product);

		$product_field_data = array('material_code' => $code,
									'description' => $product,
									'uom' => $uom,
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

		$response = $this->_CI->product_model->insert_new_product_using_transaction($product_field_data,$branch_inventory_field_data);

		if (!empty($response['error']))
			throw new \Exception($this->_error_message['UNABLE_TO_INSERT']);
			
		return $response;
	}

	/**
	 * Get product details and minimum and maximum inventory per branch based on product id
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
			$response['data']['uom'] 			= $row->uom;

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
		
		$response['own_branch'] = $this->_current_branch_id;
		$response['is_exempted'] = !in_array($this->_current_user, array(1,8)) ? FALSE : TRUE;
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
									'uom' => $uom,
									'type' => $is_nonstack,
									'material_type_id' => $material,
									'subgroup_id' => $subgroup,
									'last_modified_date' => $this->_current_date,
									'last_modified_by' => $this->_current_user);

		$branch_inventory_field_data = array();

		for ($i=0; $i < count($min_max_values); $i++) 
		{ 
			array_push($branch_inventory_field_data,array(array('min_inv' => $min_max_values[$i][2],
																'max_inv' => $min_max_values[$i][3]), 
																$this->_CI->encrypt->decode($min_max_values[$i][0])));
		}

		$error_message = $this->_CI->product_model->update_product_using_transaction($product_field_data,$branch_inventory_field_data, $product_id);

		if (!empty($error_message))
			throw new \Exception($this->_error_message['UNABLE_TO_UPDATE']);
			
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

		$affected_rows = $this->_CI->product_model->update_product_by_id($updated_product_fields,$product_id);

		if ($affected_rows == 0)
			throw new \Exception($this->_error_message['UNABLE_TO_DELETE']);
	}

	public function get_product_warning_list_info($param)
	{
		$row_start = (int)$param['row_start'];

		$response = array();

		$response['rowcnt'] = 0;

		$result = $this->_CI->product_model->get_product_warning_list_by_filter($param);

		if ($result->num_rows() > 0) 
		{
			$i = 0;
			$response['rowcnt'] = $this->_CI->product_model->get_product_warning_list_count_by_filter($param);

			foreach ($result->result() as $row) 
			{
				$response['data'][$i][] = array($row_start + $i + 1);
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

	public function get_product_transaction_list_info($param)
	{
		$row_start = (int)$param['row_start'];
		
		$response = array();

		$response['rowcnt'] = 0;

		$result = $this->_CI->product_model->get_transaction_summary_by_filter($param);

		if ($result->num_rows() > 0) 
		{
			$i = 0;
			$response['rowcnt'] = $this->_CI->product_model->get_transaction_summary_count_by_filter($param);

			foreach ($result->result() as $row) 
			{
				$response['data'][$i][] = array($row_start + $i + 1);
				
				foreach ($row as $key => $value)
				{
					if ($key !== 'sales_reservation')
      					$response['data'][$i][] = array($value);
				}

      			$response['data'][$i][] = array($row->beginv + $row->purchase_receive + $row->customer_return + $row->stock_receive 
      											+ $row->adjust_increase - $row->damage - $row->purchase_return - $row->stock_delivery - $row->customer_delivery 
      											- $row->adjust_decrease - $row->release);

      			$response['data'][$i][] = array($row->sales_reservation);
      			
				$i++;
			}
		}

		$result->free_result();
		
		return $response;
	}

	public function get_branch_inventory_list_info($param)
	{
		$row_start = (int)$param['row_start'];

		$this->_CI->load->model('branch_model');

		$branch_column_list = "";

		$result_branch = $this->_CI->branch_model->get_branch_list();

		if ($result_branch->num_rows() > 0) 
		{
			foreach ($result_branch->result() as $row) 
			{
				$branch_column_list .= ",SUM(IF(PBI.`branch_id` = ".$row->id.", PBI.`inventory`, 0)) AS '".$row->name."'";	
			}
		}
		
		$result_branch->free_result();

		$response = array();

		$response['rowcnt'] = 0;

		$result = $this->_CI->product_model->get_product_branch_inventory_list_by_filter($param, $branch_column_list);

		if ($result->num_rows() > 0) 
		{
			$i = 0;
			$response['rowcnt'] = $this->_CI->product_model->get_product_branch_inventory_list_count_by_filter($param);

			foreach ($result->result() as $row) 
			{
				$response['data'][$i][] = array($row_start + $i + 1);

				foreach ($row as $key => $value)
      				$response['data'][$i][] = array($value);

				$i++;
			}
		}

		$result->free_result();
		
		return $response;
	}

	/**
	 * Validate file type and import products from csv file. Validates material code and product name per row. 
	 * Beginning inventory will be set as an adjustment. 
	 * @return array $response
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
				$current_branch_list[strtolower($row->name)] = $row->id;

			$branch_result->free_result();

			while (($product_csv_data = fgetcsv($handle,10000))!==FALSE) 
			{
				$i++;

				if ($i == 1 && count($product_csv_data) > 3) 
				{
					for ($x=3; $x < count($product_csv_data); $x++) 
						$csv_branch_id_list[strtolower($product_csv_data[$x])] = $x;
				}
				else if ($i > 1 && count($product_csv_data) >= 3)
				{
					if (count($product_csv_data) <= 2)
					{
						$response['logs'][] = 'Row #'.$i." : Unable to process current row because of incomplete detail count!";
						continue;
					}

					if (!in_array(strtolower($product_csv_data[2]), array('yes', 'no')) || empty($product_csv_data[0]) || empty($product_csv_data[1])) 
					{
						$response['logs'][] = 'Row #'.$i." : Unable to process current row because of incomplete details!";
						continue;
					}

					$with_error 	= FALSE;
					$material_code 	= trim($product_csv_data[0]);
					$product_name 	= trim($product_csv_data[1]);
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

						if($product_subgroup_result->num_rows() > 0)
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

						foreach ($current_branch_list as $key => $value) 
						{
							array_push($branch_inventory_field_data, array('branch_id' => $value,
																			'product_id' => 0,
																			'inventory' => 0,
																			'min_inv' => 0,
																			'max_inv' => 0));

							if (array_key_exists($key, $csv_branch_id_list)) 
							{
								$new_inventory = is_numeric($product_csv_data[$csv_branch_id_list[$key]]) ? $product_csv_data[$csv_branch_id_list[$key]] : 0;

								array_push($adjustment_field_data, array('branch_id' => $value,
																	'product_id' => 0,
																	'old_inventory' => 0,
																	'new_inventory' => $new_inventory,
																	'is_show' => 1,
																	'status' => 2,
																	'memo' => 'Beginning Inventory',
																	'created_by' => $this->_current_user,
																	'date_created' => $this->_current_date));
							}
						}

						$result_product_inserted_transaction = $this->_CI->product_model->insert_new_product_using_transaction($product_field_data, $branch_inventory_field_data);
					
						if (!empty($result_product_inserted_transaction['error'])) 
							throw new Exception($this->_error_message['UNABLE_TO_INSERT']);
							
						$product_id = $this->_CI->encrypt->decode($result_product_inserted_transaction['id']);

						for ($x=0; $x < count($adjustment_field_data); $x++) 
							$adjustment_field_data[$x]['product_id'] = $product_id;

						if (count($adjustment_field_data) > 0) 
							$this->_CI->adjust_model->insert_batch_adjustment($adjustment_field_data);
						
						$response['logs'][] = 'Row #'.$i." : Successfully imported!";
					}
				}
			}
		}
		else
			$response['error'] = 'Invalid file type!';
		
		return $response;
	}

	/**
	 * Update beginning inventory of products per branch. If the specified branch doenst exist,
	 * branch inventory will no be over written. Beginning inventory will inserted as an adjustment
	 * @return array $response
	 */
	public function update_beginning_inventory_from_csv()
	{
		$this->_CI->load->model('branch_model');
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
				$current_branch_list[strtolower($row->name)] = $row->id;

			$branch_result->free_result();

			while (($product_csv_data = fgetcsv($handle,10000))!==FALSE) 
			{
				$i++;

				if ($i == 1 && count($product_csv_data) > 1) 
				{
					for ($x=1; $x < count($product_csv_data); $x++) 
						$csv_branch_id_list[strtolower($product_csv_data[$x])] = $x;
				}
				else
				{
					if (count($product_csv_data) <= 1 || empty($product_csv_data[0]))
					{
						$response['logs'][] = 'Row #'.$i." : Unable to process current row because of incomplete details!";
						continue;
					}

					$material_code 	= trim($product_csv_data[0]);
					$product_id 	= 0;

					$result = $this->_CI->product_model->check_if_field_data_exists(array("`material_code`" => $material_code));
				
					if ($result->num_rows() == 0) 
					{
						$response['logs'][] = 'Row #'.$i." : Product with material code [".$material_code."] doesnt exists!";
						continue;
					}

					$row = $result->row();
					$product_id = $row->id;

					$result->free_result();

					$adjustment_field_data = array();

					foreach ($csv_branch_id_list as $key => $value) 
					{
						if (array_key_exists($key, $current_branch_list)) 
						{
							$old_inventory 	= 0;
							$new_inventory = is_numeric($product_csv_data[$value]) ? $product_csv_data[$value] : 0;

							$result_product_inventory_info = $this->_CI->product_model->get_product_inventory_info($product_id, $current_branch_list[$key]);

							if ($result_product_inventory_info->num_rows() > 0)
							{
								$row = $result_product_inventory_info->row();
								$old_inventory = $row->current_inventory;
							}

							$result_product_inventory_info->free_result();

							if ($new_inventory == 0 || $old_inventory == $new_inventory)
								continue;

							array_push($adjustment_field_data, array('branch_id' => $current_branch_list[$key],
																'product_id' => $product_id,
																'old_inventory' => $old_inventory,
																'new_inventory' => $new_inventory,
																'is_show' => 1,
																'status' => 2,
																'memo' => 'Beginning Inventory',
																'created_by' => $this->_current_user,
																'date_created' => $this->_current_date));
						}
					}

					if (count($adjustment_field_data) > 0)
					{
						$this->_CI->adjust_model->insert_batch_adjustment($adjustment_field_data);
						$response['logs'][] = 'Row #'.$i." : Successfully updated beginning inventory!";
					} 
					else
						$response['logs'][] = 'Row #'.$i." : Unable to update beginning inventory!";
				}
			}
		}
		else
			$response['error'] = 'Invalid file type!';
		
		return $response;

		/*$exploded_name 	= explode(".", $_FILES["file"]["name"]);
		$extension 		= end($exploded_name);

		$response = array();

		$response['error'] = '';

		$i = 0;

		if ($_FILES['file']['type'] == 'application/vnd.ms-excel' && $extension == 'csv')
		{
			$product_csv_file = $_FILES['file']['tmp_name'];
			$handle = fopen($product_csv_file,"r");

			while (($product_csv_data = fgetcsv($handle,10000))!==FALSE) 
			{
				$i++;

				$product_name = strtolower(trim($product_csv_data[0]));
				$new_material_code = trim($product_csv_data[1]);

				$product_id_result = $this->_CI->product_model->check_if_field_data_exists(array("LOWER(`description`)" => $product_name));
				
				if ($product_id_result->num_rows() == 0) 
				{
					$response['logs'][] = 'Row #'.$i." : Unable to find product!";
					continue;
				}

				$row = $product_id_result->row();
				$product_id = $row->id;
				$product_id_result->free_result();

				$product_details = ['material_code' => $new_material_code,
									'subgroup_id' => 19];

				$affected_rows = $this->_CI->product_model->update_product_by_id($product_details, $product_id);

				if ($affected_rows == 1)
					$response['logs'][] = 'Row #'.$i." : Material Code Successfully updated!";
				else
					$response['logs'][] = 'Row #'.$i." : Unable to update material code!";
			}
		}
		else
			$response['error'] = 'Invalid file type!';

		return $response;*/
	}

	/**
	 * Write string logs to specified file name
	 * @param  array $logs_list [array of string logs]
	 * @param  string $file_name [import_logs - default file name]
	 */
	public function write_logs_to_file($logs_list, $file_name = 'import_logs')
	{
		$string_logs = "";

   		for ($i=0; $i < count($logs_list); $i++)
   			$string_logs .= $logs_list[$i].PHP_EOL;
   		
   		file_put_contents($file_name.".txt", $string_logs);
	}

	/**
	 * Get the first 10 result of product search based on material code and product name
	 * @param  array  $param          
	 * @param  boolean $with_inventory
	 * @return array $response
	 */
	public function get_product_autocomplete($param, $with_inventory = FALSE)
	{
		extract($param);

		$response	= [];
		$branch_id 	= $this->_CI->encrypt->decode(get_cookie('branch'));
		$result 	= $this->_CI->product_model->get_product_by_term($term, $branch_id, $with_inventory);

		$i = 0;
		
		foreach ($result->result() as $row) 
		{
			$returned_data = [$row->id, $row->description, $row->material_code, $row->type, $row->uom, 1];

			if ($with_inventory) 
				$returned_data[3] = $row->inventory;

			$response[$i]['label'] = $row->description;
			$response[$i]['value'] = $row->id;
			$response[$i]['ret_datas'] = $returned_data;

			$i++;			
		}

		$result->free_result();

		return $response;
	}

	/**
	 * Check current inventory based from checker type
	 * @param  array $param      
	 * @param  int $checker_type [0 - Minimum checker, 1 - Maximum checker]
	 * @return array $response
	 */
	public function check_current_inventory($param, $checker_type, $table_name = '')
	{
		extract($param);

		if (!class_exists('Services\SalesReservation_Manager',FALSE)) 
			$this->_CI->load->service('salesreservation_manager');

		$this->_CI->_own_sales_reservation_manager = new \Services\SalesReservation_Manager();

		$branch_id 	= $this->_CI->encrypt->decode(get_cookie('branch'));

		$response = [];
		$response['is_insufficient'] = 0;
		$response['is_excess'] = 0;
		$response['reservation_list'] = [];
		$inserted_quantity = 0;

		$result = $this->_CI->product_model->get_product_inventory_info($product_id, $branch_id);

		if ($row_id != 0 && !empty($table_name)) 
		{
			$transaction_result = $this->_CI->product_model->get_product_qty_transaction($row_id, $table_name);

			if ($transaction_result->num_rows() == 1)
			{
				$row = $transaction_result->row();
				$inserted_quantity = $row->quantity;
			}

			$transaction_result->free_result();
		}

		$row = $result->row();

		if ($checker_type == \Constants\SALESRESERVATION_CONST::MIN_CHECKER) 
		{
			/**
			 * Check if current inventory will reach negative or zero,
			 * If after deducting the transaction qty and inventory > 0, deduct the unsold qty
			 */
			if ((($row->current_inventory + $inserted_quantity) - $qty) < 0 && $row->min_inv != 0) 
				$response['is_insufficient'] = \Constants\SALESRESERVATION_CONST::NEGATIVE_INV;
			elseif ((($row->current_inventory + $inserted_quantity) - $qty) >= 0 && (($row->current_inventory + $inserted_quantity) - $qty) <= $row->min_inv && $row->min_inv != 0)
				$response['is_insufficient'] = \Constants\SALESRESERVATION_CONST::MINIMUM;

			if ($response['is_insufficient'] == 0)
			{
				$total_unsold = 0;
				$reservation_list = $this->_CI->_own_sales_reservation_manager->get_product_sales_reservation($product_id, $branch_id);
			
				foreach ($reservation_list as $key => $array)
					$total_unsold += (int)$array['unsold_qty'];

				if (((($row->current_inventory + $inserted_quantity) - $qty) - $total_unsold) <= 0) 
					$response['reservation_list'] = $reservation_list;
			}
		}
		else
		{
			$inserted_quantity *= -1;

			if ((($row->current_inventory + $inserted_quantity) + $qty) >= $row->max_inv && $row->max_inv != 0) 
				$response['is_excess'] = TRUE;
		}

		$response['checker'] = $checker_type;
		$response['current_inventory'] = $row->current_inventory + $inserted_quantity;

		$result->free_result();

		return $response;
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