<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Branch extends CI_Controller {

	private $_config;
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

		$permissions = array();

		switch ($page) 
		{
			case 'list':
				$page = 'branch_list';
				$allow_user = $this->permission_checker->check_permission(\Permission\Branch_Code::VIEW_BRANCH);
				$permissions = array('allow_to_add' => $this->permission_checker->check_permission(\Permission\Branch_Code::ADD_BRANCH),
									'allow_to_edit' => $this->permission_checker->check_permission(\Permission\Branch_Code::EDIT_BRANCH),
									'allow_to_delete' => $this->permission_checker->check_permission(\Permission\Branch_Code::DELETE_BRANCH));

				break;
			
			default:
				echo 'Invalid Page URL!';
				exit();
				break;
		}

		if (!$allow_user) 
			header('Location:'.base_url().'login');

		$data = array(	'name' 			=> get_user_fullname(),
						'branch' 		=> get_branch_name(),
						'token' 		=> '&'.$this->security->get_csrf_token_name().'='.$this->security->get_csrf_hash(),
						'page' 			=> $page,
						'script'		=> $page.'_js.php',
						'permission_list' => $permissions,
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
		$this->load->model('branch_model');

		$post_data 	= array();
		$fnc 		= '';

		$post_data 	= xss_clean(json_decode($this->input->post('data'),true));
		$fnc 		= $post_data['fnc'];

		$response['error'] = '';

		try {
			switch ($fnc) 
			{
				case 'insert_new_branch':
					$response = $this->branch_model->add_new_branch($post_data);
					break;

				case 'search_branch_list' :
					$response = $this->branch_model->search_branch_list($post_data);
					break;

				case 'get_branch_details' :
					$response = $this->branch_model->get_branch_details($post_data);
					break;

				case 'edit_branch' :
					$response = $this->branch_model->update_branch($post_data);
					break;

				case 'delete_branch' :
					$response = $this->branch_model->delete_branch($post_data);
					break;

				case 'update_main_branch':
					$response = $this->_set_main_branch_config($post_data);
					break;

				default:
					$response['error'] = 'Invalid Arguments!';
					break;
			}
		}catch (Exception $e) {
			$response['error'] = $e->getMessage();
		}
		
		echo json_encode($response);
	}
}
