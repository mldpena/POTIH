<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class PurchaseOrder extends CI_Controller {
	
	/**
	 * Load needed model or library for the current controller
	 * @return [none]
	 */
	
	private function _load_libraries()
	{
		$this->load->helper('authentication');
		$this->load->helper('query');
		$this->load->model('purchaseorder_model');
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

		switch ($page) 
		{
			case 'list':
				$page = 'purchaseorder_list';
				$data['branch_list'] = get_name_list_from_table(TRUE,'branch',TRUE);

				break;

			case 'add':
			case 'view':
				$page = 'purchaseorder_detail';
				$data['branch_list'] = get_name_list_from_table(TRUE,'branch',FALSE);

				break;
			
			default:
				# code...
				break;
		}

		

		$data['name']	= get_user_fullname();
		$data['branch']	= get_branch_name();
		$data['token']	= '&'.$this->security->get_csrf_token_name().'='.$this->security->get_csrf_hash();
		$data['page'] 	= $page;
		$data['script'] = $page.'_js.php';

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
		$post_data 	= array();
		$fnc 		= '';

		$post_data 	= xss_clean(json_decode($this->input->post('data'),true));
		$fnc 		= $post_data['fnc'];

		switch ($fnc) 
		{
			case 'create_reference_number':
				$this->_create_reference_number($post_data);
				break;

			case 'get_purchaseorder_details':
				$this->_get_purchaseorder_details();
				break;
			case 'autocomplete_product':
				$this->_get_product_list($post_data);
				break;
			case 'insert_purchaseorder_detail':
				$this->_insert_purchaseorder_detail($post_data);
				break;

			case 'update_purchaseorder_detail':
				$this->_update_purchaseorder_detail($post_data);
				break;
			case 'delete_purchaseorder_detail':
				$this->_delete_purchaseorder_detail($post_data);
				break;

			case 'save_purchaseorder_head':
				$this->_save_purchaseorder_head($post_data);
				break;
			case 'search_purchaseorder_list':
				$this->_search_purchaseorder_list($post_data);
				break;
			case 'delete_purchaseorder_head':
				$this->_delete_purchaseorder_head($post_data);
				break;
		

			default:
				
				break;
		}

	}
	private function _create_reference_number($param)
	{
		$response = get_next_number('purchase_head','reference_number');
		echo json_encode($response);
	}

	private function _get_purchaseorder_details()
	{
		$response = $this->purchaseorder_model->get_purchaseorder_details();
		echo json_encode($response);
	}
	private function _get_product_list($param)
	{
		$response = get_product_list_autocomplete($param);
		echo json_encode($response);
	}
	private function _insert_purchaseorder_detail($param)
	{
		$response = $this->purchaseorder_model->insert_purchaseorder_detail($param);
		echo json_encode($response);
	}
	private function _update_purchaseorder_detail($param)
	{
		$response = $this->purchaseorder_model->update_purchaseorder_detail($param);
		echo json_encode($response);
	}
	private function _delete_purchaseorder_detail($param)
	{
		$response = $this->purchaseorder_model->delete_purchaseorder_detail($param);
		echo json_encode($response);
	}
	private function _save_purchaseorder_head($param)
	{
		$response = $this->purchaseorder_model->update_purchaseorder_head($param);
		echo json_encode($response);
	}
	private function _search_purchaseorder_list($param)
	{
		$response = $this->purchaseorder_model->search_purchaseorder_list($param);
		echo json_encode($response);
	}
	private function _delete_purchaseorder_head($param)
	{
		$response = $this->purchaseorder_model->delete_purchaseorder_head($param);
		echo json_encode($response);
	}
	

}
