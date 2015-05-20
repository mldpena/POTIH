<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller {
	
	/**
	 * Load needed model or library for the current controller
	 * @return [none]
	 */
	
	private function _load_libraries()
	{
		$this->load->model('login_model');
		$this->load->helper('authentication');
	}

	/**
	 * Default method for the controller
	 * @return [none]
	 */
	
	public function index()
	{	
		$this->_load_libraries();

		$page = $this->uri->segment(2);

		if ($page == 'logout') 
		{
			logout_user();
		}

		if (check_set_cookies()) 
		{
			if (check_user_exists()) 
			{
				header('Location:'.base_url().'controlpanel');
				exit();
			}
		}

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

	/**
	 * Forces controller to always go to index instead of directly accessing the methods
	 * @return [none]
	 */
	
	public function _remap()
	{
        $param_offset = 1;
        $method = 'index';
	    $params = array_slice($this->uri->rsegment_array(), $param_offset);

	    call_user_func_array(array($this, $method), $params);
	} 

	/**
	 * List of AJAX request
	 * @return [none]
	 */
	
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

			case 'set_branch_user_session':
				$this->_set_branch_user_session($post_data);
				break;

			default:
				
				break;
		}

	}

	/**
	 * Check user name and password
	 * @param  $param [array]
	 * @return $data [json array]
	 */
	
	private function _check_user($param)
	{
		$response = $this->login_model->check_user_credential($param);
		echo json_encode($response);
	}

	/**
	 * Set user detail and branch in cookie
	 * @param $data [json array]
	 */
	
	private function _set_branch_user_session($param)
	{
		$response = $this->login_model->set_user_session($param);
		echo json_encode($response);
	}

}
