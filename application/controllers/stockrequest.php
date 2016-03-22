<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class StockRequest extends CI_Controller {

	private $_authentication_manager;
	private $_request_manager;
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

		$page 		= $this->uri->segment(2);
		$controller = $this->uri->segment(1);

		if (isset($_POST['data'])) 
		{
			$this->_ajax_request();
			exit();
		}

		$permissions = array();
		$branch_list = '';
		$to_branch_list = '';

		if ($controller == 'requestto') 
		{
			switch ($page) 
			{
				case 'list':
					$page = 'requestto_list';
					$branch_list = get_name_list_from_table(TRUE,'branch',TRUE, $this->encrypt->decode(get_cookie('branch')));
					$to_branch_list = get_name_list_from_table(TRUE,'branch',TRUE);
					$allow_user = $this->permission_checker->check_permission(\Permission\StockRequestTo_Code::VIEW_STOCKREQUEST);
					$permissions = array('allow_to_add' => $this->permission_checker->check_permission(\Permission\StockRequestTo_Code::ADD_STOCKREQUEST),
										'allow_to_view_detail' => $this->permission_checker->check_permission(\Permission\StockRequestTo_Code::VIEW_STOCKREQUEST_DETAIL),
										'allow_to_delete' => $this->permission_checker->check_permission(\Permission\StockRequestTo_Code::DELETE_STOCKREQUEST));

					break;

				case 'view':
					$page = 'requestto_detail';
					$branch_list = get_name_list_from_table(TRUE,'branch',FALSE);
					$allow_user = $this->permission_checker->check_permission(\Permission\StockRequestTo_Code::VIEW_STOCKREQUEST);
					$permissions = array('allow_to_edit' => $this->permission_checker->check_permission(\Permission\StockRequestTo_Code::EDIT_STOCKREQUEST),
										'allow_to_add' => $this->permission_checker->check_permission(\Permission\StockRequestTo_Code::ADD_STOCKREQUEST),
										'allow_to_edit_incomplete' => $this->permission_checker->check_permission(\Permission\StockRequestTo_Code::EDIT_INCOMPLETE_TRANSACTION));
					break;

				default:
					echo "Invalid Page URL!";
					exit();
					break;
			}
		}
		
		if ($controller == 'requestfrom') 
		{
			switch ($page) 
			{
				case 'list':
					$page = 'requestfrom_list';
					$branch_list = get_name_list_from_table(TRUE,'branch',TRUE);
					$to_branch_list = get_name_list_from_table(TRUE,'branch',TRUE,$this->encrypt->decode(get_cookie('branch')));
					$allow_user = $this->permission_checker->check_permission(\Permission\StockRequestFrom_Code::VIEW_STOCKREQUEST);
					$permissions = array('allow_to_view_detail' => $this->permission_checker->check_permission(\Permission\StockRequestFrom_Code::VIEW_STOCKREQUEST_DETAIL),
										'allow_to_delete' => FALSE,
										'allow_to_add' => FALSE);
					break;

				case 'view':
					$page = 'requestfrom_detail';
					$branch_list = get_name_list_from_table(TRUE,'branch',FALSE);
					$allow_user = $this->permission_checker->check_permission(\Permission\StockRequestFrom_Code::VIEW_STOCKREQUEST);
					$permissions = array('allow_to_edit' => FALSE, 'allow_to_add' => FALSE);
					break;

				default:
					echo "Invalid Page URL!";
					exit();
					break;
			}
		}

		if (!$allow_user) 
			header('Location:'.base_url().'controlpanel');

		$data = array(	'name' 			=> $this->encrypt->decode(get_cookie('fullname')),
						'branch' 		=> get_cookie('branch_name'),
						'token' 		=> '&'.$this->security->get_csrf_token_name().'='.$this->security->get_csrf_hash(),
						'page' 			=> $page,
						'script'		=> $page.'_js.php',
						'branch_list' 	=> $branch_list,
						'to_branch_list' => $to_branch_list,
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
		$this->load->model('request_model');
		$this->load->service('request_manager');
		$this->load->service('notification_manager');
		
		$this->_notification_manager = new Services\Notification_Manager();
		$this->_request_manager = new Services\Request_Manager();

		$post_data 	= array();
		$fnc 		= '';

		$post_data 	= xss_clean(json_decode($this->input->post('data'),true));
		$fnc 		= $post_data['fnc'];

		$response['error'] = '';

		try {
			switch ($fnc) 
			{
				case 'create_reference_number':
					$response = get_next_number('stock_request_head','reference_number', array('entry_date' => date("Y-m-d H:i:s")));
					break;

				case 'search_request_to_list':
					$response = $this->request_model->search_request_list($post_data);
					break;

				case 'get_request_details':
					$response = $this->request_model->get_stock_request_details();
					break;

				case 'insert_detail':
					$response = $this->request_model->insert_stock_request_detail($post_data);
					break;

				case 'delete_detail':
					$response = $this->request_model->delete_stock_request_detail($post_data);
					break;

				case 'update_detail':
					$response = $this->request_model->update_stock_request_detail($post_data);
					break;

				case 'save_request_head':
					$response = $this->request_model->update_stock_request_head($post_data);
					break;

				case 'autocomplete_product':
					$response = get_product_list_autocomplete($post_data);
					break;

				case 'delete_head':
					$response = $this->request_model->delete_stock_request_head($post_data);
					break;

				case 'create_delivery':
					$response = $this->_request_manager->create_stock_delivery_from_selected_request_detail($post_data);
					break;

				case 'set_session':
					$response = $this->set_session_data();
					break;

				case 'check_notifications':
					$response = $this->_notification_manager->get_header_notifications();
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

		$result = $this->request_model->check_if_transaction_has_product();

		if ($result->num_rows() == 0)
			throw new Exception("Please encode at least one product!");
		else
			$this->session->set_userdata('stock_request',$this->uri->segment(3));

		$result->free_result();
		
		return $response;
	}
}
