<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('get_branch_name')) 
{
	function get_branch_name()
	{
		$CI =& get_instance();
		$CI->load->helper('cookie');
		$CI->load->library('encrypt');
		$CI->load->library('constants/branch_const');

		
		$branch_id 	= $CI->encrypt->decode(get_cookie('branch'));
		$query 		= "SELECT CONCAT(`code`,' - ',`name`) AS 'name' 
							FROM branch WHERE `is_show` = ".BRANCH_CONST::ACTIVE." AND `id` = ?";
		$result 	= $CI->db->query($query,$branch_id);
		$row		= $result->row();
		$name 		= $row->name;
		$result->free_result();

		return $name;
	}
}

if (!function_exists('get_user_fullname')) 
{
	function get_user_fullname()
	{
		$CI =& get_instance();
		$CI->load->helper('cookie');
		$CI->load->library('encrypt');

		$full_name 	= $CI->encrypt->decode(get_cookie('fullname'));
		return $full_name;
	}
}

if (!function_exists('get_name_list_from_table')) 
{
	function get_name_list_from_table($is_option = false, $table = '', $include_all = false)
	{
		$CI =& get_instance();

		$data_list = (!$is_option) ? array() : '';

		$query = "SELECT CONCAT(`code`,' - ',`name`) AS 'name', `id`
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
					$data_list .= "<option value='".$row->id."'>".$row->name."</option>";
				}
			}
		}

		return $data_list;
	}
}

if (!function_exists('get_next_number')) 
{
	function get_next_number($table_name = '', $field = '', $default_value = 100000)
	{
		$CI =& get_instance();
		$CI->load->helper('cookie');
		$CI->load->library('encrypt');
		$CI->load->library('sql');

		$user_id 	= $CI->encrypt->decode(get_cookie('temp'));
		$branch_id 	= $CI->encrypt->decode(get_cookie('branch'));
		$next_value = $default_value + 1;
		$query 		= array();

		array_push($query,"SET @invoiceno_d = 0;");
		array_push($query,"SELECT COAlESCE(MAX(`$field` + 0),$default_value) INTO @invoiceno_d FROM `$table_name` WHERE `is_show` = 1 FOR UPDATE;");
		$invoiceno_variable = "IF(@invoiceno_d = 0,'$next_value',@invoiceno_d+1)";
		array_push($query,"INSERT INTO `$table_name`(`$field`,`date_created`,`created_by`,`last_modified_by`,`branch_id`) VALUES($invoiceno_variable,NOW(),$user_id,$user_id,$branch_id)");

		$data = $CI->sql->execute_transaction($query);

		return $data;
	}
}

if (!function_exists('get_product_list_autocomplete')) 
{
	function get_product_list_autocomplete($param)
	{
		extract($param);
		$CI =& get_instance();
		$CI->load->helper('cookie');
		$CI->load->library('encrypt');
		$CI->load->library('sql');
		$CI->load->library('constants/product_const');

		$data 		= array();
		$term 		= '%'.$term.'%';
		$query_data = array($term,$term);

		$query = "SELECT P.`description`, P.`id`, P.`material_code`, COALESCE(PBI.`inventory`,0) AS 'inventory'
					FROM product AS P
					LEFT JOIN product_branch_inventory AS PBI ON PBI.`product_id` = P.`id`
					WHERE P.`is_show` = ".PRODUCT_CONST::ACTIVE." AND (P.`description` LIKE ? OR P.`material_code` LIKE ?)
					LIMIT 10";

		$result = $CI->db->query($query,$query_data);

		$i = 0;
		
		foreach ($result->result() as $row) 
		{
			$data[$i]['label'] = $row->description;
			$data[$i]['value'] = $row->id;
			$data[$i]['ret_datas'] = array($row->id,$row->description,$row->material_code,$row->inventory);
			$i++;			
		}

		$result->free_result();

		return $data;
	}
}

/* End of file query_helper.php */
/* Location: ./system/helpers/query_helper.php */