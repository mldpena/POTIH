<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login_Model extends CI_Model {

	public function __construct() {
		$this->load->library('encrypt');
		parent::__construct();
	}

	public function check_user_credential($param)
	{

	}
}
