<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class StockDelivery extends CI_Controller {
	
	/**
	 * Load needed model or library for the current controller
	 * @return [none]
	 */
	
	private function _load_libraries()
	{
		$this->load->helper('cookie');
		$this->load->library('encrypt');
		$this->load->helper('authentication');
		$this->load->helper('query');
		$this->load->library('permission_checker');
		$this->load->library('session');
	}

	/**
	 * Default method for the controller
	 * @return [none]
	 */
	
	public function index()
	{	
		$this->_load_libraries();

		check_user_credentials();

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

		if ($controller == 'delivery') 
		{
			switch ($page) 
			{
				case 'list':
					$page = 'delivery_list';
					$branch_list = get_name_list_from_table(TRUE,'branch',TRUE);
					$allow_user = $this->permission_checker->check_permission(\Permission\StockDelivery_Code::VIEW_STOCK_DELIVERY);
					$permissions = array('allow_to_add' => $this->permission_checker->check_permission(\Permission\StockDelivery_Code::ADD_STOCK_DELIVERY),
										'allow_to_view_detail' => $this->permission_checker->check_permission(\Permission\StockDelivery_Code::VIEW_STOCK_DELIVERY_DETAIL),
										'allow_to_delete' => $this->permission_checker->check_permission(\Permission\StockDelivery_Code::DELETE_STOCK_DELIVERY));

					break;

				case 'view':
					$page = 'delivery_detail';
					$branch_list = get_name_list_from_table(TRUE,'branch',FALSE);
					$allow_user = $this->permission_checker->check_permission(\Permission\StockDelivery_Code::VIEW_STOCK_DELIVERY);
					$permissions = array('allow_to_edit' => $this->permission_checker->check_permission(\Permission\StockDelivery_Code::EDIT_STOCK_DELIVERY),
										'allow_to_add' => $this->permission_checker->check_permission(\Permission\StockDelivery_Code::ADD_STOCK_DELIVERY));
					break;

				default:
					echo "Invalid Page URL!";
					exit();
					break;
			}
		}
		
		if ($controller == 'delreceive') 
		{
			switch ($page) 
			{
				case 'list':
					$page = 'deliveryreceive_list';
					$branch_list = get_name_list_from_table(TRUE,'branch',TRUE);
					$to_branch_list = get_name_list_from_table(TRUE,'branch',TRUE,$this->encrypt->decode(get_cookie('branch')));
					$allow_user = $this->permission_checker->check_permission(\Permission\StockReceive_Code::VIEW_STOCK_RECEIVE);
					$permissions = array('allow_to_view_detail' => $this->permission_checker->check_permission(\Permission\StockReceive_Code::VIEW_STOCK_RECEIVE_DETAIL));
					break;

				case 'view':
					$page = 'deliveryreceive_detail';
					$branch_list = get_name_list_from_table(TRUE,'branch',FALSE);
					$allow_user = $this->permission_checker->check_permission(\Permission\StockReceive_Code::VIEW_STOCK_RECEIVE);
					$permissions = array('allow_to_edit' => $this->permission_checker->check_permission(\Permission\StockReceive_Code::EDIT_STOCK_RECEIVE));
					break;

				default:
					echo "Invalid Page URL!";
					exit();
					break;
			}
		}

		if ($controller == 'custreceive') 
		{
			switch ($page) 
			{
				case 'list':
					$page = 'customerreceive_list';
					$branch_list = get_name_list_from_table(TRUE,'branch',TRUE);
					$allow_user = $this->permission_checker->check_permission(\Permission\CustomerReceive_Code::VIEW_CUSTOMER_RECEIVE);
					$permissions = array('allow_to_view_detail' => $this->permission_checker->check_permission(\Permission\CustomerReceive_Code::VIEW_CUSTOMER_RECEIVE_DETAIL));
					break;

				case 'view':
					$page = 'customerreceive_detail';
					$allow_user = $this->permission_checker->check_permission(\Permission\CustomerReceive_Code::VIEW_CUSTOMER_RECEIVE);
					$permissions = array('allow_to_edit' => $this->permission_checker->check_permission(\Permission\CustomerReceive_Code::EDIT_CUSTOMER_RECEIVE));
					break;

				default:
					echo "Invalid Page URL!";
					exit();
					break;
			}
		}

		if (!$allow_user) 
			header('Location:'.base_url().'login');

		$data = array(	'name' 			=> get_user_fullname(),
						'branch' 		=> get_branch_name(),
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
		$this->load->model('delivery_model');

		$post_data 	= array();
		$fnc 		= '';

		$post_data 	= xss_clean(json_decode($this->input->post('data'),true));
		$fnc 		= $post_data['fnc'];

		$response['error'] = '';

		try {
			switch ($fnc) 
			{
				case 'create_reference_number':
					$response = get_next_number('stock_delivery_head','reference_number', array('entry_date' => date("Y-m-d h:i:s"),
																								'delivery_receive_date' => date("Y-m-d h:i:s"),
																								'customer_receive_date' => date("Y-m-d h:i:s")));
					break;

				case 'get_stock_delivery_details':
					$response = $this->delivery_model->get_stock_delivery_details();
					break;

				case 'autocomplete_product':
					$response = get_product_list_autocomplete($post_data);
					break;

				case 'insert_stock_delivery_detail':
					$response = $this->delivery_model->insert_stock_delivery_detail($post_data);
					break;

				case 'update_stock_delivery_detail':
					$response = $this->delivery_model->update_stock_delivery_detail($post_data);
					break;

				case 'delete_detail':
					$response = $this->delivery_model->delete_stock_delivery_detail($post_data);
					break;

				case 'save_stock_delivery_head':
					$response = $this->delivery_model->update_stock_delivery_head($post_data);
					break;

				case 'search_stock_delivery_list':
					$response = $this->delivery_model->search_stock_delivery_list($post_data);
					break;

				case 'delete_head':
					$response = $this->delivery_model->delete_stock_delivery_head($post_data);
					break;

				case 'search_stock_receive_list':
					$response = $this->delivery_model->search_receive_list($post_data, 1);
					break;

				case 'get_stock_receive_details':
					$response = $this->delivery_model->get_receive_details(1);
					break;

				case 'update_receive_detail':
					$response = $this->delivery_model->update_receive_detail($post_data);
					break;

				case 'search_customer_receive_list':
					$response = $this->delivery_model->search_receive_list($post_data, 2);
					break;

				case 'get_customer_receive_details':
					$response = $this->delivery_model->get_receive_details(2);
					break;
					
				case 'check_product_inventory':
					$response = check_current_inventory($post_data,0);
					break;

				case 'update_delivery_receive_head':
					$response = $this->delivery_model->update_receive_head($post_data, 1);
					break;

				case 'update_customer_receive_head':
					$response = $this->delivery_model->update_receive_head($post_data, 2);
					break;

				case 'update_stock_receive_detail':
					$response = $this->delivery_model->update_stock_receive_detail($post_data);
					break;

				case 'set_session':
					$this->set_session_data('delivery_receive');
					break;

				case 'set_session_delivery':
					$this->set_session_data('delivery');
					break;

				case 'set_session_receive':
					$this->set_session_data('customer_receive');
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

	private function set_session_data($session_name)
	{
		$this->session->set_userdata($session_name,$this->uri->segment(3));
	}
}
