<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('get_branch_name')) 
{
	function get_branch_name()
	{
		$CI =& get_instance();
		$CI->load->helper('cookie');
		$CI->load->library('encrypt');

		
		$branch_id 	= $CI->encrypt->decode(get_cookie('branch'));
		$query 		= "SELECT `name` FROM branch WHERE `is_show` = 1 AND `id` = ?"; 
		$result 	= $CI->db->query($query,$branch_id);
		$row		= $result->row();
		$name 		= $row->name;
		$result->free_result();

		return $name;
	}

	function get_user_fullname()
	{
		$CI =& get_instance();
		$CI->load->helper('cookie');
		$CI->load->library('encrypt');

		$full_name 	= $CI->encrypt->decode(get_cookie('fullname'));
		return $full_name;
	}
}

/* End of file query_helper.php */
/* Location: ./system/helpers/query_helper.php */