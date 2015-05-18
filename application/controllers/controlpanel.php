<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ControlPanel extends CI_Controller {

	// private function loadLibraries()
	// {
	// 	$this->load->model('login_model');
	// 	$this->load->library('sqlfunction');
	// }

	public function index()
	{	
		// $this->loadLibraries();
		// $isLogout = $this->uri->segment(2);

		// if ($isLogout == "logout") {
		// 	$this->myfunction->deleteSessionCookies();
		// }

		// if (isset($_COOKIE['username']) && isset($_COOKIE['fullname']) && isset($_COOKIE['temp']) && isset($_COOKIE['permissions'])) {
		// 	$check = $this->sqlfunction->checkUserExist();
		// 	if ($check == 'success') {
		// 		$this->myfunction->relocate('controlpanel');
		// 	}
		// }

		// if (isset($_POST['data'])) {
		// 	$this->ajaxRequest();
		// 	exit();
		// }

		$data['page'] 	= 'controlpanel';
		$data['script'] = 'controlpanel_js.php';

		$this->load->view('master', $data);
	}

	// public function _remap()
	// {
 //        $param_offset = 1;
 //        $method = 'index';
	//     $params = array_slice($this->uri->rsegment_array(), $param_offset);

	//     call_user_func_array(array($this, $method), $params);
	// } 

	// private function ajaxRequest()
	// {
	// 	$postData 	= array();
	// 	$fnc 		= '';

	// 	$postData 	= json_decode($_POST['data'],true);
	// 	$fnc 		= $postData['fnc'];

	// 	switch ($fnc) {
	// 		case 'check_login':
	// 			$this->checkLogin($postData);
	// 			break;

	// 		case 'get_user_branch_list':
	// 			$this->getUserBranchList($postData);
	// 			break;

	// 		case 'final_verification':
	// 			$this->verifyUser($postData);
	// 			break;
	// 		default:
				
	// 			break;
	// 	}

	// }

	// private function checkLogin($param)
	// {
	// 	$data = $this->login_model->checkUserCredential($param);
	// 	echo json_encode($data);
	// }
	
	// private function getUserBranchList($param)
	// {
	// 	$data = $this->sqlfunction->getUserBranchList($param['userid']);
	// 	echo json_encode($data);
	// }

	// private function verifyUser($param)
	// {
	// 	$data = $this->login_model->finalVerifyUser($param);
	// 	echo json_encode($data);
	// }

}
