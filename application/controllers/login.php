<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller {

	private function _load_libraries()
	{
		$this->load->model('login_model');
	}

	public function index()
	{	
		$this->_load_libraries();

		if (isset($_POST['data'])) 
		{
			$this->_ajax_request();
			exit();
		}

		$data['token']	= '&'.$this->security->get_csrf_token_name().'='.$this->security->get_csrf_hash();
		$data['page'] 	= 'login';
		$data['script'] = 'login_js.php';

		$this->load->view('master', $data);
	}

	public function _remap()
	{
        $param_offset = 1;
        $method = 'index';
	    $params = array_slice($this->uri->rsegment_array(), $param_offset);

	    call_user_func_array(array($this, $method), $params);
	} 

	private function _ajax_request()
	{
		$post_data 	= array();
		$fnc 		= '';

		$post_data 	= xss_clean(json_decode($this->input->post('data'),true));
		$fnc 		= $post_data['fnc'];

		switch ($fnc) 
		{
			case 'check_user':
				$this->_check_user($post_data);
				break;

			default:
				
				break;
		}

	}

	private function _check_user($param)
	{
		$data = $this->login_model->check_user_credential($param);
		echo json_encode($data);
	}

}
