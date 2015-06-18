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

		if ($controller == 'delivery') 
		{
			switch ($page) 
			{
				case 'list':
					$page = 'delivery_list';
					$data['branch_list'] = get_name_list_from_table(TRUE,'branch',TRUE);
					break;

				

				case 'add':
				case 'view':
					$page = 'delivery_detail';
					$data['branch_list'] = get_name_list_from_table(TRUE,'branch',FALSE);
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
					$data['branch_list'] = get_name_list_from_table(TRUE,'branch',TRUE);
					$data['to_branch_list'] = get_name_list_from_table(TRUE,'branch',TRUE,$this->encrypt->decode(get_cookie('branch')));
					break;

				case 'view':
					$page = 'deliveryreceive_detail';
					$data['branch_list'] = get_name_list_from_table(TRUE,'branch',FALSE);
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
					$data['branch_list'] = get_name_list_from_table(TRUE,'branch',TRUE);
					break;

				case 'view':
					$page = 'customerreceive_detail';
					break;

				default:
					echo "Invalid Page URL!";
					exit();
					break;
			}
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
		$this->load->model('delivery_model');

		$post_data 	= array();
		$fnc 		= '';

		$post_data 	= xss_clean(json_decode($this->input->post('data'),true));
		$fnc 		= $post_data['fnc'];

		switch ($fnc) 
		{
			case 'create_reference_number':
				$response = get_next_number('stock_delivery_head','reference_number');
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

			case 'delete_stock_delivery_detail':
				$response = $this->delivery_model->delete_stock_delivery_detail($post_data);
				break;

			case 'save_stock_delivery_head':
				$response = $this->delivery_model->update_stock_delivery_head($post_data);
				break;

			case 'search_stock_delivery_list':
				$response = $this->delivery_model->search_stock_delivery_list($post_data);
				break;

			case 'delete_stock_delivery_head':
				$response = $this->delivery_model->delete_stock_delivery_head($post_data);
				break;

			case 'search_stock_receive_list':
				$response = $this->delivery_model->search_stock_receive_list($post_data);
				break;

			case 'get_stock_receive_details':
				$response = $this->delivery_model->get_stock_receive_details($post_data);
				break;

			case 'update_stock_receive_detail':
				$response = $this->delivery_model->update_stock_receive_detail($post_data);
				break;

			case 'search_customer_receive_list':
				$response = $this->delivery_model->search_customer_receive_list($post_data);
				break;

			case 'get_customer_receive_details':
				$response = $this->delivery_model->get_customer_receive_details($post_data);
				break;
				
			case 'check_product_inventory':
				$response = $this->delivery_model->check_current_inventory($post_data);
				break;

			default:
				$response['error'] = 'Invalid Arguments!';
				break;
		}

		echo json_encode($response);
	}

}
