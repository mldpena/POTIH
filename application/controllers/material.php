<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Material extends CI_Controller {
	
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
				$page = 'material_list';
				$allow_user = $this->permission_checker->check_permission(\Permission\Material_Code::VIEW_MATERIAL);
				$permissions = array('allow_to_add' => $this->permission_checker->check_permission(\Permission\Material_Code::ADD_MATERIAL),
									'allow_to_edit' => $this->permission_checker->check_permission(\Permission\Material_Code::EDIT_MATERIAL),
									'allow_to_delete' => $this->permission_checker->check_permission(\Permission\Material_Code::DELETE_MATERIAL));

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
		$this->load->model('material_model');

		$post_data 	= array();
		$fnc 		= '';

		$post_data 	= xss_clean(json_decode($this->input->post('data'),true));
		$fnc 		= $post_data['fnc'];

		$response['error'] = '';

		try {
			switch ($fnc) 
			{
				case 'insert_new_material':
					$response = $this->material_model->add_new_material($post_data);
					break;

				case 'search_material_list' :
					$response = $this->material_model->search_material_list($post_data);
					break;

				case 'get_material_details' :
					$response = $this->material_model->get_material_details($post_data);
					break;

				case 'edit_material' :
					$response = $this->material_model->update_material($post_data);
					break;

				case 'delete_material' :
					$response = $this->material_model->delete_material($post_data);
					break;

				default:
					$response['error'] = 'Invalid arguments';
					break;
			}
		}catch (Exception $e) {
			$response['error'] = $e->getMessage();
		}
		
		echo json_encode($response);
	}

}
