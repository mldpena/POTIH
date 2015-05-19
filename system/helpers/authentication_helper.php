<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('check_user_credentials')) 
{
	function check_user_credentials()
	{
		$isset_cookies = check_set_cookies();

		if (!$isset) 
		{
			logout_user();
		}

		$is_exists = check_user_exists();

		if (!$is_exists) 
		{
			logout_user();
		}

		return true;
	}
}

if (!function_exists('check_set_cookies')) 
{
	function check_set_cookies()
	{
		$CI =& get_instance();
		$CI->load->helper('cookie');

		$isset = true;

		if (!isset($_COOKIE['username']) || !isset($_COOKIE['fullname']) || !isset($_COOKIE['temp']) || !isset($_COOKIE['branch'])) 
		{
			delete_user_cookies();
			$isset = false;
		}

		return $isset;
	}
}

if (!function_exists('check_user_exists')) 
{
	function check_user_exists()
	{
		$CI =& get_instance();
		$CI->load->library('encrypt');
		$CI->load->helper('cookie');

		$isset 		= true;
		$username 	= $CI->encrypt->decode(get_cookie('username'));
		$fullname 	= $CI->encrypt->decode(get_cookie('fullname'));
		$temp 		= $CI->encrypt->decode(get_cookie('temp'));
		$query_data = array($username,$fullname,$temp);

		$query = "SELECT `id`, `username`, `password`, `full_name`
					FROM `user` AS U 
					WHERE U.`is_show` = 1 AND U.`username` = ? AND U.`full_name` = ? AND U.`id` = ?";

		$result = $CI->db->query($query,$query_data);

		if ($result->num_rows() != 1) 
		{
			$isset = false;
		}

		$result->free_result();

		return $isset;
	}
}

if (!function_exists('logout_user')) 
{
	function logout_user()
	{
		delete_user_cookies();
		header('Location:'.base_url().'login');
		exit();
	}
}

if (!function_exists('delete_user_cookies')) 
{
	function delete_user_cookies()
	{
		$CI =& get_instance();
		$CI->load->helper('cookie');

		delete_cookie('username');
		delete_cookie('fullname');
		delete_cookie('temp');
		delete_cookie('branch');
	}
}
/* End of file authentication_helper.php */
/* Location: ./system/helpers/authentication_helper.php */