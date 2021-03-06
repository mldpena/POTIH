<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class SalesReservation extends CI_Controller {
	
	private $_authentication_manager;
	private $_autocomplete_manager;
	private $_sales_reservation_manager;
	private $_notification_manager;
	private $_product_manager;
	private $_customer_manager;

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
		$salesman_list = '';

		switch ($page) 
		{
			case 'list':
				$page = 'salesreservation_list';
				$branch_list = get_name_list_from_table(TRUE, 'branch', TRUE, $this->encrypt->decode(get_cookie('branch')));
				$customer_list = get_name_list_from_table(TRUE, 'customer', TRUE, 0, "`code`, ' - ', `company_name`");

				$allow_user = $this->permission_checker->check_permission(\Permission\SalesReservation_Code::VIEW_SALES_RESERVATION);
				$permissions = array(
										'allow_to_add' => $this->permission_checker->check_permission(\Permission\SalesReservation_Code::ADD_SALES_RESERVATION),
										'allow_to_view_detail' => $this->permission_checker->check_permission(\Permission\SalesReservation_Code::VIEW_SALES_RESERVATION_DETAIL),
										'allow_to_delete' => $this->permission_checker->check_permission(\Permission\SalesReservation_Code::DELETE_SALES_RESERVATION)
									);
				break;

			case 'view':
				$page = 'salesreservation_detail';
				$branch_list = get_name_list_from_table(TRUE, 'branch', FALSE, $this->encrypt->decode(get_cookie('branch')));
				$customer_list = get_name_list_from_table(TRUE, 'customer', FALSE, 0, "`code`, ' - ', `company_name`");
				$salesman_list = get_name_list_from_table(TRUE, 'user', FALSE, $this->encrypt->decode(get_cookie('temp')), '`full_name`', " AND `type` = ".\Permission\UserType_Code::SALESMAN);
				$allow_user = $this->permission_checker->check_permission(\Permission\SalesReservation_Code::VIEW_SALES_RESERVATION);
				$permissions = array(
										'allow_to_edit' => $this->permission_checker->check_permission(\Permission\SalesReservation_Code::EDIT_SALES_RESERVATION),
										'allow_to_add' => $this->permission_checker->check_permission(\Permission\SalesReservation_Code::ADD_SALES_RESERVATION)
									);
				
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
						'customer_list' => $customer_list,
						'salesman_list' => $salesman_list,
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
		$this->load->model('product_model');
		$this->load->service('autocomplete_manager');
		$this->load->service('salesreservation_manager');
		$this->load->service('notification_manager');
		$this->load->service('product_manager');
		$this->load->service('customer_manager');

		$this->_notification_manager = new Services\Notification_Manager();
		$this->_autocomplete_manager = new Services\Autocomplete_Manager();
		$this->_sales_reservation_manager = new Services\SalesReservation_Manager();
		$this->_product_manager = new Services\Product_Manager();
		$this->_customer_manager = new Services\Customer_Manager();

		$post_data 	= array();
		$fnc 		= '';

		$post_data 	= xss_clean(json_decode($this->input->post('data'),true));
		$fnc 		= $post_data['fnc'];

		$response['error'] = '';

		try 
		{
			switch ($fnc) 
			{
				case 'create_reference_number':
					$response = get_next_number('sales_reservation_head', 'reference_number', [
																								'entry_date' => date("Y-m-d H:i:s"),
																								'due_date' => date('Y-m-d', strtotime('+7 day', strtotime(date('Y-m-d ')))),
																								'for_branch_id' => $this->encrypt->decode(get_cookie('branch'))
																							  ],
																							  " AND `branch_id` = ".$this->encrypt->decode(get_cookie('branch')));
					break;

				case 'get_sales_reservation_details':
					$response = $this->_sales_reservation_manager->get_sales_reservation_details();
					break;

				case 'insert_detail':
					$response = $this->_sales_reservation_manager->insert_sales_reservation_detail($post_data);
					break;

				case 'delete_detail':
					$response = $this->_sales_reservation_manager->delete_sales_reservation_detail($post_data);
					break;

				case 'update_detail':
					$response = $this->_sales_reservation_manager->update_sales_reservation_detail($post_data);
					break;

				case 'save_sales_reservation_head':
					$response = $this->_sales_reservation_manager->update_sales_reservation_head($post_data);
					break;

				case 'delete_head':
					$response = $this->_sales_reservation_manager->delete_sales_reservation($post_data);
					break;

				case 'check_product_inventory':
					$response = $this->_product_manager->check_current_inventory($post_data, \Constants\SALESRESERVATION_CONST::MIN_CHECKER);
					break;

				case 'search_sales_reservation_list':
					$response = $this->_sales_reservation_manager->search_sales_reservation_list($post_data);
					break;

				case 'autocomplete_product':
					$response = $this->_product_manager->get_product_autocomplete($post_data);
					break;

				case 'get_customer_details':
					$response = $this->_customer_manager->get_customer_details($post_data['customer_id']);
					break;

				case 'check_notifications':
					$response = $this->_notification_manager->get_header_notifications();
					break;
				
				case 'recent_name_autocomplete':
					$response = $this->_autocomplete_manager->get_recent_names($post_data, 1);
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
}
