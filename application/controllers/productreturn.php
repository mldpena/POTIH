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
				echo 'Invalid Page URL!';
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
		$this->load->model('return_model');

		$post_data 	= array();
		$fnc 		= '';

		$post_data 	= xss_clean(json_decode($this->input->post('data'),true));
		$fnc 		= $post_data['fnc'];

		switch ($fnc) 
		{
			case 'create_reference_number':
				$response = get_next_number('return_head','reference_number');
				break;

			case 'get_return_details':
				$response = $this->return_model->get_return_details();
				break;

			case 'autocomplete_product':
				$response = get_product_list_autocomplete($post_data);
				break;

			case 'insert_return_detail':
				$response = $this->return_model->insert_return_detail($post_data);
				break;

			case 'update_return_detail':
				$response = $this->return_model->update_return_detail($post_data);
				break;

			case 'delete_return_detail':
				$response = $this->return_model->delete_return_detail($post_data);
				break;

			case 'save_return_head':
				$response = $this->return_model->update_return_head($post_data);
				break;

			case 'search_return_list':
				$response = $this->return_model->search_return_list($post_data);
				break;

			case 'delete_return_head':
				$response = $this->return_model->delete_return_head($post_data);
				break;

			default:
				$response['error'] = 'Invalid arguments!';
				break;
		}

		echo json_encode($response);
	}

}
