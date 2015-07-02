<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class Permission 
	{
		function __construct()
		{
			$CI =& get_instance();
			$CI->load->helper('cookie');
		}
	}
?>