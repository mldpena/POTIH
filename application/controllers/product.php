<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Product extends CI_Controller {
	
	/**
	 * Load needed model or library for the current controller
	 * @return [none]
	 */
	
	private function _load_libraries()
	{
		$this->load->helper('authentication');
		$this->load->helper('query');
		$this->load->helper('cookie');
		$this->load->library('encrypt');
		$this->load->library('permission_checker');
	}

	/**
	 * Default method for the controller
	 * @return [none]
	 */
	
	public function index()
	{	
		$this->_load_libraries();

		check_user_credentials();

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

		$data = array(	'name' 			=> get_user_fullname(),
						'branch' 		=> get_branch_name(),
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
		$this->load->model('product_model');

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
		
		echo json_encode($response);
	}

	private function _get_branch_list()
	{
		$i = 0;
		$response = array();
		$branch_list = get_name_list_from_table(FALSE,'branch');
	
		foreach ($branch_list as $key => $value) {
			$response['data'][$i][] = array(0);		
			$response['data'][$i][] = array($i+1);		
			$response['data'][$i][] = array($value,$key);		
			$response['data'][$i][] = array(0);		
			$response['data'][$i][] = array(0);	
			$i++;		
		}

		return $response;
	}

	private function _remapData($response)
	{
		
	}
}