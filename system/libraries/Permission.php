<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class Permission 
	{
		private $CI 

		function __construct()
		{
			$CI =& get_instance();
			$CI->load->helper('cookie');
		}
	}
?>