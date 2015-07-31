<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pickup extends CI_Controller {

	private $_authentication_manager;

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

		switch ($page) 
		{
			case 'list':
				$page = 'pickup_list';
				$branch_list = get_name_list_from_table(TRUE,'branch',TRUE,$this->encrypt->decode(get_cookie('branch')));
				$allow_user = $this->permission_checker->check_permission(\Permission\PickUp_Code::PRINT_SUMMARY);
				$permissions = array('allow_to_add' => $this->permission_checker->check_permission(\Permission\PickUp_Code::GENERATE_SUMMARY));

				break;
			
			default:
				echo 'Invalid Page URL!';
				exit();
				break;
		}

		if (!$allow_user) 
			header('Location:'.base_url().'login');

		$data = array(	'name' 			=> $this->encrypt->decode(get_cookie('fullname')),
						'branch' 		=> get_cookie('branch_name'),
						'token' 		=> '&'.$this->security->get_csrf_token_name().'='.$this->security->get_csrf_hash(),
						'page' 			=> $page,
						'script'		=> $page.'_js.php',
						'permission_list' => $permissions,
						'branch_list'	=> $branch_list,
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
		$this->load->model('pickup_model');

		$post_data 	= array();
		$fnc 		= '';

		$post_data 	= xss_clean(json_decode($this->input->post('data'),true));
		$fnc 		= $post_data['fnc'];

		$response['error'] = '';

		try {
			switch ($fnc) 
			{
				case 'get_pickup_summary':
					$response = $this->pickup_model->get_pickup_summary_list($post_data);
					break;

				case 'generate_summary':
					$response = $this->pickup_model->generate_pickup_summary($post_data);
					break;

				case 'delete_head':
					$this->pickup_model->delete_pickup_summary($post_data);
					break;

				case 'set_session':
					$response = $this->set_session_data($post_data);
					break;

				default:
					$response['error'] = 'Invalid Arguments!';
					break;
			}
		}
		catch (Exception $e) 
		{
			$response['error'] = $e->getMessage();
		}
		
		echo json_encode($response);
	}

	private function set_session_data($param)
	{
		extract($param);

		$response['error'] = '';

		$result_count = $this->pickup_model->check_if_summary_has_product($summary_head_id);

		if ($result_count == 0)
			throw new Exception("No details to print!");
		else
			$this->session->set_userdata('pickup_summary', $summary_head_id);
		
		return $response;
	}
}
