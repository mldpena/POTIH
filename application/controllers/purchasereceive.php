<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class PurchaseReceive extends CI_Controller {
	
	/**
	 * Load needed model or library for the current controller
	 * @return [none]
	 */
	
	private function _load_libraries()
	{
		$this->load->helper('authentication');
		$this->load->helper('query');
		$this->load->model('purchase_receive_model');
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
				$page = 'purchasereceive_list';
				break;

			case 'view':
				$page = 'purchasereceive_detail';
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

			case 'get_purchase_receive_details':
				$this->_get_purchase_receive_details();
				break;

			case 'get_po_details':
				$this->_get_po_details($post_data);
				break;

			case 'insert_receive_detail':
				$this->_insert_receive_detail($post_data);
				break;

			default:
				
				break;
		}

	}

	private function _create_reference_number($param)
	{
		$response = get_next_number('purchase_receive_head','reference_number');
		echo json_encode($response);
	}

	private function _get_purchase_receive_details()
	{
		$response = $this->purchase_receive_model->get_purchase_receive_details();
		echo json_encode($response);
	}

	private function _get_po_details($param)
	{
		$response = $this->purchase_receive_model->get_po_details($param);
		echo json_encode($response);
	}

	private function _insert_receive_detail($param)
	{
		$response = $this->purchase_receive_model->insert_receive_detail($param);
		echo json_encode($response);
	}
}
