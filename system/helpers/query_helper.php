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

/* End of file query_helper.php */
/* Location: ./system/helpers/query_helper.php */