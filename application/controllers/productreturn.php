<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ProductReturn extends CI_Controller {
	
	/**
	 * Load needed model or library for the current controller
	 * @return [none]
	 */
	
	private function _load_libraries()
	{
		$this->load->helper('authentication');
		$this->load->helper('query');
		$this->load->model('return_model');
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
				$page = 'return_list';
				$data['branch_list'] = get_name_list_from_table(TRUE,'branch',TRUE);
				break;

			case 'add':
			case 'view':
				$page = 'return_detail';
				break;
			
			default:
				echo 'Invalid URL';
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
		$post_data 	= array();
		$fnc 		= '';

		$post_data 	= xss_clean(json_decode($this->input->post('data'),true));
		$fnc 		= $post_data['fnc'];

		switch ($fnc) 
		{
			case 'create_reference_number':
				$this->_create_reference_number($post_data);
				break;

			case 'get_return_details':
				$this->_get_return_details();
				break;

			case 'autocomplete_product':
				$this->_get_produc_list($post_data);
				break;

			case 'insert_return_detail':
				$this->_insert_return_detail($post_data);
				break;

			case 'update_return_detail':
				$this->_update_return_detail($post_data);
				break;

			case 'delete_return_detail':
				$this->_delete_return_detail($post_data);
				break;

			case 'save_return_head':
				$this->_save_return_head($post_data);
				break;

			case 'search_return_list':
				$this->_search_return_list($post_data);
				break;

			case 'delete_return_head':
				$this->_delete_return_head($post_data);
				break;

			default:
				
				break;
		}

	}

	private function _create_reference_number($param)
	{
		$response = get_next_number('return_head','reference_number');
		echo json_encode($response);
	}

	private function _get_return_details()
	{
		$response = $this->return_model->get_return_details();
		echo json_encode($response);
	}

	private function _get_produc_list($param)
	{
		$response = get_product_list_autocomplete($param);
		echo json_encode($response);
	}

	private function _insert_return_detail($param)
	{
		$response = $this->return_model->insert_return_detail($param);
		echo json_encode($response);
	}

	private function _update_return_detail($param)
	{
		$response = $this->return_model->update_return_detail($param);
		echo json_encode($response);
	}

	private function _delete_return_detail($param)
	{
		$response = $this->return_model->delete_return_detail($param);
		echo json_encode($response);
	}

	private function _save_return_head($param)
	{
		$response = $this->return_model->update_return_head($param);
		echo json_encode($response);
	}

	private function _search_return_list($param)
	{
		$response = $this->return_model->search_return_list($param);
		echo json_encode($response);
	}

	private function _delete_return_head($param)
	{
		$response = $this->return_model->delete_return_head($param);
		echo json_encode($response);
	}
}
