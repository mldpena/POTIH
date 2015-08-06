<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Release extends CI_Controller {
	
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
				$page = 'release_list';
				$branch_list = get_name_list_from_table(TRUE, 'branch', TRUE, $this->encrypt->decode(get_cookie('branch')));
				$allow_user = $this->permission_checker->check_permission(\Permission\Release_Code::VIEW_RELEASE);
				$permissions = array('allow_to_add' => $this->permission_checker->check_permission(\Permission\Release_Code::ADD_RELEASE),
									'allow_to_view_detail' => $this->permission_checker->check_permission(\Permission\Release_Code::VIEW_RELEASE_DETAIL),
									'allow_to_delete' => $this->permission_checker->check_permission(\Permission\Release_Code::DELETE_RELEASE));
				break;

			case 'view':
				$page = 'release_detail';
				$allow_user = $this->permission_checker->check_permission(\Permission\Release_Code::VIEW_RELEASE);
				$permissions = array('allow_to_edit' => $this->permission_checker->check_permission(\Permission\Release_Code::EDIT_RELEASE),
									'allow_to_add' => $this->permission_checker->check_permission(\Permission\Release_Code::ADD_RELEASE));
				break;
			
			default:
				echo "Invalid Page URL!";
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
		$this->load->model('release_model');
		$this->load->service('notification_manager');
		
		$this->_notification_manager = new Services\Notification_Manager();

		$response 	= array();
		$post_data 	= array();
		$fnc 		= '';

		$post_data 	= xss_clean(json_decode($this->input->post('data'),true));
		$fnc 		= $post_data['fnc'];

		try {
			switch ($fnc) 
			{
				case 'search_release_list':
					$response = $this->release_model->search_release_list($post_data);
					break;

				case 'create_reference_number':
					$response = get_next_number('release_head','reference_number',array('entry_date' => date("Y-m-d h:i:s")));
					break;

				case 'get_release_details':
					$response = $this->release_model->get_release_details();
					break;

				case 'get_pa_details':
					$response = $this->release_model->get_pa_details($post_data);
					break;

				case 'insert_release_detail':
					$response = $this->release_model->insert_release_detail($post_data);
					break;

				case 'update_release_detail':
					$response = $this->release_model->update_release_detail($post_data);
					break;

				case 'delete_release_detail':
					$response = $this->release_model->delete_release_detail($post_data);
					break;

				case 'save_release_head':
					$response = $this->release_model->update_release_head($post_data);
					break;

				case 'delete_head':
					$response = $this->release_model->delete_release_head($post_data);
					break;
					
				case 'set_session':
					$response = $this->set_session_data();
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
			$response['error'] = $e->getMessage();
		}
		
		echo json_encode($response);
	}

	private function set_session_data()
	{
		$response['error'] = '';

		$result = $this->release_model->check_if_transaction_has_product();

		if ($result->num_rows() == 0)
			throw new Exception("Please encode at least one product!");
		else
			$this->session->set_userdata('release_slip',$this->uri->segment(3));

		$result->free_result();
		
		return $response;
	}

}
