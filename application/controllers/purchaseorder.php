<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class PurchaseOrder extends CI_Controller {
	
	private $_authentication_manager;
	private $_autocomplete_manager;
	private $_purchase_manager;
	private $_notification_manager;
	private $_product_manager;

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
				$page = 'purchaseorder_list';
				$branch_list = get_name_list_from_table(TRUE, 'branch', TRUE, $this->encrypt->decode(get_cookie('branch')));
				$allow_user = $this->permission_checker->check_permission(\Permission\Purchase_Code::VIEW_PURCHASE);
				$permissions = array('allow_to_add' => $this->permission_checker->check_permission(\Permission\Purchase_Code::ADD_PURCHASE),
									'allow_to_view_detail' => $this->permission_checker->check_permission(\Permission\Purchase_Code::VIEW_PURCHASE_DETAIL),
									'allow_to_delete' => $this->permission_checker->check_permission(\Permission\Purchase_Code::DELETE_PURCHASE));
				break;

			case 'view':
				$page = 'purchaseorder_detail';
				$branch_list = get_name_list_from_table(TRUE, 'branch', FALSE,  $this->encrypt->decode(get_cookie('branch')));
				$allow_user = $this->permission_checker->check_permission(\Permission\Purchase_Code::VIEW_PURCHASE);
				$permissions = array('allow_to_edit' => $this->permission_checker->check_permission(\Permission\Purchase_Code::EDIT_PURCHASE),
									'allow_to_add' => $this->permission_checker->check_permission(\Permission\Purchase_Code::ADD_PURCHASE),
									'allow_to_edit_transfer' => $this->permission_checker->check_permission(\Permission\Purchase_Code::TRANSFER_INCOMPLETE_PO));
				
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
		$this->load->model('purchaseorder_model');
		$this->load->model('product_model');
		$this->load->service('autocomplete_manager');
		$this->load->service('purchase_manager');
		$this->load->service('notification_manager');
		$this->load->service('product_manager');

		$this->_notification_manager = new Services\Notification_Manager();
		$this->_autocomplete_manager = new Services\Autocomplete_Manager();
		$this->_purchase_manager = new Services\Purchase_Manager();
		$this->_product_manager = new Services\Product_Manager();

		$post_data 	= array();
		$fnc 		= '';

		$post_data 	= xss_clean(json_decode($this->input->post('data'),true));
		$fnc 		= $post_data['fnc'];

		$response['error'] = '';

		try {
			switch ($fnc) 
			{
				case 'create_reference_number':
					$response = get_next_number('purchase_head','reference_number',array('entry_date' => date("Y-m-d H:i:s"),
																						'for_branchid' => $this->encrypt->decode(get_cookie('branch'))));
					break;

				case 'get_purchaseorder_details':
					$response = $this->purchaseorder_model->get_purchaseorder_details();
					break;

				case 'autocomplete_product':
					$response = $this->_product_manager->get_product_autocomplete($post_data);
					break;

				case 'insert_detail':
					$response = $this->purchaseorder_model->insert_purchaseorder_detail($post_data);
					break;

				case 'update_detail':
					$response = $this->purchaseorder_model->update_purchaseorder_detail($post_data);
					break;

				case 'delete_detail':
					$response = $this->purchaseorder_model->delete_purchaseorder_detail($post_data);
					break;

				case 'save_purchaseorder_head':
					$response = $this->purchaseorder_model->update_purchaseorder_head($post_data);
					break;

				case 'search_purchaseorder_list':
					$response = $this->purchaseorder_model->search_purchaseorder_list($post_data);
					break;

				case 'delete_head':
					$response = $this->purchaseorder_model->delete_purchaseorder_head($post_data);
					break;

				case 'check_product_inventory':
					$response = $this->_product_manager->check_current_inventory($post_data, \Constants\PURCHASE_CONST::MAX_CHECKER);
					break;

				case 'set_session':
					$response = $this->set_session_data();
					break;

				case 'recent_name_autocomplete':
					$response = $this->_autocomplete_manager->get_recent_names($post_data, 2);
					break;

				case 'transfer_remaining':
					$response = $this->_purchase_manager->transfer_remaining_po_to_new($post_data);
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

		$result = $this->purchaseorder_model->check_if_transaction_has_product();

		if ($result->num_rows() == 0)
			throw new Exception("Please encode at least one product!");
		else
			$this->session->set_userdata('purchase_order',$this->uri->segment(3));

		$result->free_result();
		
		return $response;
	}
}
