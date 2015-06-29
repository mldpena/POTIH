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
				$data['branch_list'] = get_name_list_from_table(TRUE,'branch',TRUE);
				break;

			case 'view':
				$page = 'purchasereceive_detail';
				break;
			
			default:
				echo "Invalid Page URL!";
				exit();
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
		$this->load->model('purchasereceive_model');

		$response 	= array();
		$post_data 	= array();
		$fnc 		= '';

		$post_data 	= xss_clean(json_decode($this->input->post('data'),true));
		$fnc 		= $post_data['fnc'];

		switch ($fnc) 
		{
			case 'create_reference_number':
				$response = get_next_number('purchase_receive_head','reference_number',array('entry_date' => date("Y-m-d h:i:s")));
				break;

			case 'get_purchase_receive_details':
				$response = $this->purchasereceive_model->get_purchase_receive_details();
				break;

			case 'get_po_details':
				$response = $this->purchasereceive_model->get_po_details($post_data);
				break;

			case 'insert_receive_detail':
				$response = $this->purchasereceive_model->insert_receive_detail($post_data);
				break;

			case 'save_purchase_receive_head':
				$response = $this->purchasereceive_model->update_receive_head($post_data);
				break;

			case 'search_purchase_receive_list':
				$response = $this->purchasereceive_model->search_purchase_receive_list($post_data);
				break;

			case 'delete_head':
				$response = $this->purchasereceive_model->delete_purchase_receive_head($post_data);
				break;

			case 'delete_purchase_receive_detail':
				$response = $this->purchasereceive_model->delete_purchase_receive_detail($post_data);
				break;

			case 'update_receive_detail':
				$response = $this->purchasereceive_model->update_purchase_receive_detail($post_data);
				break;

			default:
				$response['error'] = 'Invalid arguments!';
				break;
		}

		echo json_encode($response);
	}
}
