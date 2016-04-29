<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends CI_Controller {
	
	private $_authentication_manager;
	private $_notification_manager;

	/**
	 * Load needed model or library for the current controller
	 * @return [none]
	 */
	
	public function __construct()
	{
		parent::__construct();

		$this->load->service('authentication_manager');
		$this->load->library('permission_checker');

		$this->_authentication_manager = new Services\Authentication_Manager();
	}

	/**
	 * Default method for the controller
	 * @return [none]
	 */
	
	public function index()
	{	
		$this->_authentication_manager->check_user_credentials();

		$page = $this->uri->segment(2);

		if (isset($_POST['data'])) 
		{
			$this->_ajax_request();
			exit();
		}

		$permissions = array();
		$branch_list = '';

		switch ($page) 
		{
			case 'list':
				$page = 'user_list';
				$allow_user = $this->permission_checker->check_permission(\Permission\User_Code::VIEW_USER);
				$permissions = array('allow_to_add' => $this->permission_checker->check_permission(\Permission\User_Code::ADD_USER),
									'allow_to_view_detail' => $this->permission_checker->check_permission(\Permission\User_Code::VIEW_USER_DETAIL),
									'allow_to_delete' => $this->permission_checker->check_permission(\Permission\User_Code::DELETE_USER));
				break;
			
			case 'add':
				$page = 'user_detail';
				$branch_list = get_name_list_from_table(TRUE, 'branch', FALSE, $this->encrypt->decode(get_cookie('branch')));
				$allow_user = $this->permission_checker->check_permission(array(\Permission\User_Code::VIEW_USER,\Permission\User_Code::ADD_USER));
				$permissions = array('allow_to_edit' => TRUE);
				break;

			case 'view':
				$page = 'user_detail';
				$branch_list = get_name_list_from_table(TRUE, 'branch', TRUE);

				if ($this->uri->segment(3) != get_cookie('temp'))
					$allow_user = $this->permission_checker->check_permission(array(\Permission\User_Code::VIEW_USER,\Permission\User_Code::VIEW_USER_DETAIL));
				else 
					$allow_user = TRUE;
				
				$permissions = array('allow_to_edit' => $this->permission_checker->check_permission(\Permission\User_Code::EDIT_USER));
				break;

			default:
				echo 'Invalid Page URL!';
				exit();
				break;
		}

		if (!$allow_user) 
			header('Location:'.base_url().'controlpanel');

		$data = array(	'name' 			=> $this->encrypt->decode(get_cookie('fullname')),
						'branch' 		=> get_cookie('branch_name'),
						'token' 		=> '&'.$this->security->get_csrf_token_name().'='.$this->security->get_csrf_hash(),
						'page' 			=> $page,
						'script'		=> $page.'_js.php',
						'branch_list' 	=> $branch_list,
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
		$this->load->model('user_model');
		$this->load->service('notification_manager');
		
		$this->_notification_manager = new Services\Notification_Manager();

		$post_data 	= array();
		$fnc 		= '';

		$post_data 	= xss_clean(json_decode($this->input->post('data'),true));
		$fnc 		= $post_data['fnc'];

		$response['error'] = '';

		try {
			switch ($fnc) 
			{
				case 'insert_new_user':
					$response = $this->user_model->insert_new_user($post_data);
					break;

				case 'get_user_list':
					$response = $this->user_model->get_user_list($post_data);
					break;

				case 'delete_user':
					$response = $this->user_model->delete_user($post_data);
					break;

				case 'get_user_details':
					$response = $this->user_model->get_user_details($post_data);
					break;

				case 'update_user':
					$response = $this->user_model->update_user($post_data);
					break;

				case 'check_notifications':
					$response = $this->_notification_manager->get_header_notifications();
					break;

				default:
					$response['error'] = 'Invalid arguments!';
					break;
			}	
		} 
		catch (Exception $e) 
		{
			$response['error']  = $e->getMessage();
		}
		
		echo json_encode($response);
	}
}
