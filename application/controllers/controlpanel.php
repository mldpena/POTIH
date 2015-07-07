<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ControlPanel extends CI_Controller {
	
	/**
	 * Load needed model or library for the current controller
	 * @return [none]
	 */
	
	private function _load_libraries()
	{
		$this->load->helper('authentication');
		$this->load->helper('query');
		$this->load->library('permission_checker');
	}

	/**
	 * Default method for the controller
	 * @return [none]
	 */
	
	public function index()
	{	
		$this->_load_libraries();
		check_user_credentials();

		$page = $this->uri->segment(2);

		if (isset($_POST['data'])) 
		{
			$this->_ajax_request();
			exit();
		}

		$data = array(	'name' 			=> get_user_fullname(),
						'branch' 		=> get_branch_name(),
						'token' 		=> '&'.$this->security->get_csrf_token_name().'='.$this->security->get_csrf_hash(),
						'page' 			=> 'controlpanel',
						'script'		=> 'controlpanel_js.php',
						'section_permissions' => $this->permission_checker->get_section_permissions(),
						'page_permissions' => $this->permission_checker->get_page_permissions());


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
			case '':
				break;

			default:
				
				break;
		}

	}
}
