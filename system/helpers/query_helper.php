<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
if (!function_exists('get_name_list_from_table')) 
{
	function get_name_list_from_table($is_option = false, $table = '', $include_all = false, $default_value = 0)
	{
		$CI =& get_instance();

		$data_list = (!$is_option) ? array() : '';

		$query = "SELECT CONCAT(`name`) AS 'name', `id`
					FROM $table WHERE `is_show` = 1"; 

		$result = $CI->db->query($query);

		if ($include_all) {
			if (!$is_option) {
				$data_list[0] = 'ALL';
			}else{
				$data_list .= "<option value='0'>ALL</option>";
			}
		}
		
		if ($result->num_rows() > 0) {
			foreach ($result->result() as $row) {
				if (!$is_option) {
					$data_list[$row->id] = $row->name;
				}else{
					$selected = ($default_value != 0 && $default_value == $row->id) ? 'selected' : '';
					$data_list .= "<option value='".$row->id."' $selected>".$row->name."</option>";
				}
			}
		}

		return $data_list;
	}
}
//Temporary
if (!function_exists('get_next_number')) 
{
	function get_next_number($table_name = '', $field = '', $additional_field = array(), $default_value = 100000)
	{
		$CI =& get_instance();
		$CI->load->library('sql');

		$user_id 	= $CI->encrypt->decode(get_cookie('temp'));
		$branch_id 	= $CI->encrypt->decode(get_cookie('branch'));
		$next_value = $default_value + 1;
		$query 		= array();
		$query_data = array();

		array_push($query,"SET @invoiceno_d = 0;");
		array_push($query_data,array());

		array_push($query,"SELECT COAlESCE(MAX(`$field` + 0),$default_value) INTO @invoiceno_d FROM `$table_name` WHERE `is_show` = 1 FOR UPDATE;");
		array_push($query_data,array());

		$query_temp 		= "INSERT INTO `$table_name`(`$field`,`date_created`,`created_by`,`last_modified_by`,`branch_id`";
		$query_temp_values 	= "VALUES(IF(@invoiceno_d = 0,'$next_value',@invoiceno_d+1),NOW(),?,?,?";
		$query_data_temp 	= array($user_id,$user_id,$branch_id);

		foreach ($additional_field as $key => $value) {
			$query_temp .= ",`".$key."`";
			$query_temp_values .= ",?";
			array_push($query_data_temp,$value);
		}

		$query_temp = $query_temp.") ".$query_temp_values.")";

		array_push($query,$query_temp,"SET @insert_id = LAST_INSERT_ID();","SELECT @insert_id AS 'id';");
		array_push($query_data,$query_data_temp,array(),array());

		$response = $CI->sql->execute_transaction($query,$query_data);

		return $response;
	}
}

if (!function_exists('get_product_list_autocomplete')) 
{
	function get_product_list_autocomplete($param, $with_inventory = FALSE)
	{
		extract($param);

		$CI =& get_instance();
		
		$CI->load->model('product_model');
		$CI->load->constant('product_const');

		$branch_id 	= $CI->encrypt->decode(get_cookie('branch'));
		$result 	= $CI->product_model->get_product_by_term($term, $branch_id, $with_inventory);

		$i = 0;
		
		$response = array();
		
		foreach ($result->result() as $row) 
		{
			$response[$i]['label'] = $row->description;
			$response[$i]['value'] = $row->id;
			$response[$i]['ret_datas'] = ($with_inventory) ? array($row->id,$row->description,$row->material_code,$row->inventory) : array($row->id,$row->description,$row->material_code, $row->type);
			$i++;			
		}

		$result->free_result();

		return $response;
	}
}

if (!function_exists('check_current_inventory')) 
{
	function check_current_inventory($param, $checker_type, $table_name = '')
	{
		extract($param);

		$CI =& get_instance();

		$CI->load->model('product_model');
		$CI->load->constant('product_const');

		$data 		= array();
		$branch_id 	= $CI->encrypt->decode(get_cookie('branch'));
		$row_id 	= strlen($row_id) > 1 ? $CI->encrypt->decode($row_id) : 0;
		$inserted_quantity = 0;

		//Temporary
		if ($row_id != 0 && !empty($table_name)) 
		{
			$query = "SELECT `quantity` FROM $table_name WHERE `id` = ?";
			$result = $CI->db->query($query, $row_id);
			if ($result->num_rows() == 1)
			{
				$row = $result->row();
				$inserted_quantity = $row->quantity;
			}

			$result->free_result();
		}
		
		$response = array();
		$response['is_insufficient'] = 0;
		$response['is_excess'] = 0;

		$result = $CI->product_model->get_product_inventory_info($product_id, $branch_id);

		$row = $result->row();

		if ($checker_type == \Constants\PRODUCT_CONST::MIN_CHECKER) 
		{
			if ((($row->current_inventory + $inserted_quantity) - $qty) < 0 && $row->min_inv != 0) 
				$response['is_insufficient'] = \Constants\PRODUCT_CONST::NEGATIVE_INV;
			elseif ((($row->current_inventory + $inserted_quantity) - $qty) >= 0 && (($row->current_inventory + $inserted_quantity) - $qty) <= $row->min_inv && $row->min_inv != 0)
				$response['is_insufficient'] = \Constants\PRODUCT_CONST::MINIMUM;
		}
		else
		{
			if ((($row->current_inventory + $inserted_quantity) + $qty) >= $row->max_inv && $row->max_inv != 0) 
				$response['is_excess'] = TRUE;
		}
		
		$response['checker'] = $checker_type;
		$response['current_inventory'] = $row->current_inventory + $inserted_quantity;

		$result->free_result();

		return $response;
	}
}
/* End of file query_helper.php */
/* Location: ./system/helpers/query_helper.php */