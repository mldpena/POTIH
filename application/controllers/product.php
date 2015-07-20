<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Product extends CI_Controller {
		
	private $_product_manager;
	private $_authentication_manager;
	private $_header_info_manager;

	/**
	 * Load needed model or library for the current controller
	 * @return [none]
	 */
	
	public function __construct()
	{
		parent::__construct();

		$this->load->service('authentication_manager');
		$this->load->service('header_info_manager');
		$this->load->service('product_manager');
		//$this->load->service('product_import_manager');
		$this->load->library('permission_checker');

		$this->_authentication_manager = new Services\Authentication_Manager();
		$this->_header_info_manager = new Services\Header_Info_Manager();
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

		/*if (isset($_FILES['file'])) 
		{
			$this->_product_manager->import_product_from_csv();
			exit();
		}*/

		$permissions = array();

		switch ($page) 
		{
			case 'list':
				$page = 'product_list';
				$branch_list = get_name_list_from_table(TRUE,'branch',TRUE,$this->encrypt->decode(get_cookie('branch')));
				$allow_user = $this->permission_checker->check_permission(\Permission\Product_Code::VIEW_PRODUCT);
				$permissions = array('allow_to_add' => $this->permission_checker->check_permission(\Permission\Product_Code::ADD_PRODUCT),
									'allow_to_edit' => $this->permission_checker->check_permission(\Permission\Product_Code::EDIT_PRODUCT),
									'allow_to_delete' => $this->permission_checker->check_permission(\Permission\Product_Code::DELETE_PRODUCT));

				break;
			
			case 'warning':
				$page = 'inventory_warning_list';
				$branch_list = get_name_list_from_table(TRUE,'branch',FALSE);
				$allow_user = $this->permission_checker->check_permission(\Permission\InventoryWarning_Code::VIEW_WARNING);

				break;


			case 'summary':
				$page = 'transaction_list';
				$branch_list = get_name_list_from_table(TRUE,'branch',TRUE,$this->encrypt->decode(get_cookie('branch')));
				$allow_user = $this->permission_checker->check_permission(\Permission\TransactionSummary_Code::VIEW_TRANSACTION_SUMMARY);
				break;

			case 'inventory':
				$page = 'branch_inventory_list';
				$branch_list = get_name_list_from_table(TRUE,'branch',TRUE);
				$allow_user = $this->permission_checker->check_permission(\Permission\BranchInventory_Code::VIEW_BRANCH_INVENTORY);

				break;

			case 'record':
				$page = 'transaction_record';
				$branch_list = get_name_list_from_table(TRUE,'branch',TRUE);
				$allow_user = TRUE;
				break;

			default:
				echo 'Invalid Page URL!';
				exit();
				break;
		}

		if (!$allow_user) 
			header('Location:'.base_url().'login');

		$data = array(	'name' 			=> $this->_header_info_manager->get_user_full_name(),
						'branch' 		=> $this->_header_info_manager->get_branch_name(),
						'token' 		=> '&'.$this->security->get_csrf_token_name().'='.$this->security->get_csrf_hash(),
						'page' 			=> $page,
						'script'		=> $page.'_js.php',
						'material_list' => get_name_list_from_table(TRUE,'material_type',TRUE),
						'subgroup_list' => get_name_list_from_table(TRUE,'subgroup',TRUE),
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
		$this->_product_manager = new Services\Product_Manager();

		$post_data 	= array();
		$fnc 		= '';

		$post_data 	= xss_clean(json_decode($this->input->post('data'),true));
		$fnc 		= $post_data['fnc'];

		$response['error'] = '';

		try
		{
			switch ($fnc) 
			{
				case 'get_product_list':
					$response = $this->_product_manager->get_product_list_info($post_data);
					break;

				case 'get_branch_list_for_min_max':
					$response = $this->_product_manager->get_min_max_per_branch();
					break;

				case 'get_material_and_subgroup':
					$response = $this->_product_manager->get_material_and_subgroup_by_character($post_data);
					break;

			};
		}
		catch (Exception $e)
		{
			$response['error'] = $e->getMessage();
		}

		echo json_encode($response);

		/*$this->load->model('product_model');

		$post_data 	= array();
		$fnc 		= '';

		$post_data 	= xss_clean(json_decode($this->input->post('data'),true));
		$fnc 		= $post_data['fnc'];

		$response['error'] = '';

		try {
			switch ($fnc) 
			{
				case 'get_product_list':
					$response = $this->product_model->get_product_list($post_data);
					break;

				case 'get_material_and_subgroup':
					$response = $this->product_model->get_product_material_subgroup($post_data);
					break;

				case 'insert_new_product':
					$response = $this->product_model->insert_new_product($post_data);
					break;

				case 'get_product_details':
					$response = $this->product_model->get_product_details($post_data);
					break;

				case 'update_product_details':
					$response = $this->product_model->update_product_details($post_data);
					break;

				case 'delete_product':
					$response = $this->product_model->delete_product($post_data);
					break;

				case 'get_branch_list_for_min_max':
					$response = $this->_get_branch_list();
					break;

				case 'get_inventory_warning_list':
					$response = $this->product_model->get_product_warning_list($post_data);
					break;

				case 'get_branch_list':
					$response['branches'] = get_name_list_from_table(FALSE,'branch',FALSE);
					break;

				case 'get_branch_inventory_list':
					$response = $this->product_model->get_product_branch_inventory_list($post_data);
					break;

				case 'get_transaction_list':
					$response = $this->product_model->get_transaction_summary($post_data);
					break;

				case 'get_product_name':
					$response = $this->product_model->get_product_name();
					break;

				case 'autocomplete_product':
					$response = get_product_list_autocomplete($post_data);
					break;

				case 'get_transaction_record':
					$response = $this->product_model->get_transaction_record($post_data);
					break;

				case 'get_transaction_breakdown':
					$response = $this->product_model->get_transaction_breakdown($post_data);
					break;

				default:
					$response['error'] = 'Invalid arguments!';
					break;
			}
		}catch (Exception $e) {
			$response['error'] = $e->getMessage();
		}
		
		echo json_encode($response);*/
	}

	/*

	private function _import_product_csv()
	{
		$extension	= end(explode(".", $_FILES["file"]["name"]));

		if ($_FILES['file']['type'] == 'application/vnd.ms-excel' && $extension == 'csv')
			$data = $this->product_model->importCSVData($shipperid,$datetime,$csvFile);
		else
			$data['error'][] = 'Invalid file type!';
		
		echo json_encode($data);
	}*/
}