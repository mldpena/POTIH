<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller {
	
	private $_authentication_manager;

	/**
	 * Load needed model or library for the current controller
	 * @return [none]
	 */

	public function __construct()
	{
		parent::__construct();

		$this->load->service('authentication_manager');

		$this->_authentication_manager = new Services\Authentication_Manager();
	}

	/**
	 * Default method for the controller
	 * @return [none]
	 */
	
	public function index()
	{	
		$page = $this->uri->segment(2);

		if ($page == 'logout') 
			$this->_authentication_manager->logout();

		if ($this->_authentication_manager->check_set_cookies()) 
		{
			if ($this->_authentication_manager->check_user_exists()) 
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

		$response['error'] = '';

		try
		{
			switch ($fnc) 
			{
				case 'check_user':
					$response = $this->_authentication_manager->validate_user_input_credential($post_data);
					break;

				case 'set_branch_user_session':
					$response = $this->_authentication_manager->set_user_session($post_data);
					break;

				default:
					$response['error'] = 'Invalid arguments!';
					break;
			}
		}
		catch (Exception $e)
		{
			$response['error'] = $e->getMessage();
		}

		echo json_encode($response);
	}
}
