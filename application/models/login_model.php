<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login_Model extends CI_Model {

	public function __construct() {
		$this->load->library('encrypt');
		parent::__construct();
	}

	public function check_user_credential($param)
	{
		$response 	= array();
		$user_name 	= $param['user'];
		$password 	= $this->encrypt->encode($param['pass']);
		$query_data = array($user_name,$password);
		
		$query 	= "SELECT `id` FROM user WHERE `username` = ? AND `password` = ?";
		$result = $this->db->query($query,$query_data);

		if ($result->num_rows() != 1) 
		{
			$response['error'] = "Invalid Username / Password!";
		}
		else
		{
			$row = $result->row();
			$response['userid'] = $this->encrypt->encode($row->id);
		}

		$result->free_result();

		return $response; 
	}
}
