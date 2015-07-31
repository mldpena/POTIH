<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Assortment extends CI_Controller {
	
	private $_authentication_manager;
	private $_autocomplete_manager;

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

		$page 		= $this->uri->segment(2);

		if (isset($_POST['data'])) 
		{
			$this->_ajax_request();
			exit();
		}

		$permissions = array();

		switch ($page) 
		{
			case 'list':
				$page = 'assortment_list';
				$branch_list = get_name_list_from_table(TRUE,'branch',TRUE);
				$allow_user = $this->permission_checker->check_permission(\Permission\Assortment_Code::VIEW_ASSORTMENT);
				$permissions = array('allow_to_add' => $this->permission_checker->check_permission(\Permission\Assortment_Code::ADD_ASSORTMENT),
									'allow_to_view_detail' => $this->permission_checker->check_permission(\Permission\Assortment_Code::VIEW_ASSORTMENT_DETAIL),
									'allow_to_delete' => $this->permission_checker->check_permission(\Permission\Assortment_Code::DELETE_ASSORTMENT));
				break;

			case 'view':
				$page = 'assortment_detail';
				$branch_list = get_name_list_from_table(TRUE,'branch',FALSE);
				$allow_user = $this->permission_checker->check_permission(\Permission\Assortment_Code::VIEW_ASSORTMENT);
				$permissions = array('allow_to_edit' => $this->permission_checker->check_permission(\Permission\Assortment_Code::EDIT_ASSORTMENT),
									'allow_to_add' => $this->permission_checker->check_permission(\Permission\Assortment_Code::ADD_ASSORTMENT),
									'allow_to_edit_incomplete' => $this->permission_checker->check_permission(\Permission\Assortment_Code::EDIT_INCOMPLETE_TRANSACTION));
				
				break;

			default:
				echo "Invalid Page URL!";
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
		$this->load->model('assortment_model');
		$this->load->service('autocomplete_manager');

		$this->_autocomplete_manager = new Services\Autocomplete_Manager();

		$post_data 	= array();
		$fnc 		= '';

		$post_data 	= xss_clean(json_decode($this->input->post('data'),true));
		$fnc 		= $post_data['fnc'];

		$response['error'] = '';

		try {
			switch ($fnc) 
			{
				case 'search_assortment_list':
					$response = $this->assortment_model->search_assortment_list($post_data);
					break;

				case 'create_reference_number':
					$response = get_next_number('release_order_head','reference_number',array('entry_date' => date("Y-m-d h:i:s")));
					break;

				case 'get_assortment_details':
					$response = $this->assortment_model->get_assortment_details();
					break;

				case 'recent_name_autocomplete':
					$response = $this->_autocomplete_manager->get_recent_names($post_data, 1);
					break;

				case 'insert_detail':
					$response = $this->assortment_model->insert_assortment_detail($post_data);
					break;

				case 'update_detail':
					$response = $this->assortment_model->update_assortment_detail($post_data);
					break;

				case 'delete_detail':
					$response = $this->assortment_model->delete_assortment_detail($post_data);
					break;

				case 'set_session':
					$response = $this->set_session_data();
					break;

				case 'autocomplete_product':
					$response = get_product_list_autocomplete($post_data);
					break;

				case 'save_assortment_head':
					$response = $this->assortment_model->update_assortment_head($post_data);
					break;

				case 'delete_head':
					$response = $this->assortment_model->delete_assortment_head($post_data);
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

	private function set_session_data()
	{
		$response['error'] = '';

		$result = $this->assortment_model->check_if_transaction_has_product();

		if ($result->num_rows() == 0)
			throw new Exception("Please encode at least one product!");
		else
			$this->session->set_userdata('release_slip',$this->uri->segment(3));

		$result->free_result();
		
		return $response;
	}
}
