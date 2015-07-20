<?php

namespace Services;

class Header_Info_Manager
{
	private $_CI;

	public function __construct()
	{
		$this->_CI = $CI =& get_instance();
	}

	public function get_branch_name()
	{
		$this->_CI->load->constant('branch_const');
		$this->_CI->load->model('branch_model');

		$branch_id 	= $this->_CI->encrypt->decode(get_cookie('branch'));

		$result 	= $this->_CI->branch_model->get_branch_info_by_id($branch_id);

		$row		= $result->row();
		$name 		= $row->name;

		$result->free_result();

		return $name;
	}

	public function get_user_full_name()
	{
		$full_name 	= $this->_CI->encrypt->decode(get_cookie('fullname'));
		return $full_name;
	}
}

?>