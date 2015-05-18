<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login_Model extends CI_Model {

	// public function __construct() {
	// 	$this->load->library('encrypt');
	// 	$this->load->library('myfunction');
	// 	parent::__construct();
	// }

	// public function checkUserCredential($param)
	// {
	// 	$data['error'] = '';
	// 	$pass = $this->myfunction->encryptDataMD5($param['pass']);

	// 	$query = " SELECT `id`, `username`, `password`, `fullname`
	// 					FROM `user` WHERE `show` = 1 AND `username` = ? AND `password` = ?";

	// 	$result = $this->db->query($query, array($param['user'], $pass));

	// 	if ($result->num_rows() != 1) {
	// 		$data['error'] = "Invalid Username / Password!";
	// 	}
	// 	else
	// 	{
	// 		$row = $result->row();
	// 		$data['userid'] = $this->encrypt->encode($row->id);
	// 	}

	// 	$result->free_result();

	// 	return $data;
	// }

	// public function finalVerifyUser($param)
	// {
	// 	$data['error'] = '';
	// 	$pass = $this->myfunction->encryptDataMD5($param['pass']);

	// 	$query = "SELECT `id`, `username`, `password`, `fullname`
	// 				FROM `user` AS U 
	// 				WHERE U.`show` = 1 AND U.`username` = ? AND U.`password` = ?";

	// 	$result = $this->db->query($query, array($param['user'], $pass));

	// 	if ($result->num_rows() != 1) {
	// 		$data['error'] = "Invalid Username / Password!";
	// 	}
	// 	else
	// 	{
	// 		$row 				= $result->row();
	// 		$permissions 		= array();
	// 		$query 				= "SELECT `permissioncode` FROM userbranchpermission WHERE `userid` = ? AND `branchid` = ?";
	// 		$result_permission 	= $this->db->query($query, array($row->id,$param['branchid']));

	// 		foreach ($result_permission->result() as $row_p) {
	// 			array_push($permissions,$row_p->permissioncode);
	// 		}

	// 		$this->myfunction->setCookie('username',$this->encrypt->encode($row->username));
	// 		$this->myfunction->setCookie('fullname',$this->encrypt->encode($row->fullname));
	// 		$this->myfunction->setCookie('temp',$this->encrypt->encode($row->id));
	// 		$this->myfunction->setCookie('branch',$this->encrypt->encode($param['branchid']));
	// 		$this->myfunction->setCookie('permissions',json_encode($permissions));
	// 	}

	// 	$result->free_result();

	// 	return $data;
	// }
}
