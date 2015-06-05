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
				$page = 'product_list';
				/* Temporary */
				$data['branch_list'] 	= get_name_list_from_table(TRUE,'branch',TRUE);
				$data['material_list'] 	= get_name_list_from_table(TRUE,'material_type',TRUE);
				$data['subgroup_list'] 	= get_name_list_from_table(TRUE,'subgroup',TRUE);
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
		$this->load->model('product_model');

		$post_data 	= array();
		$fnc 		= '';

		$post_data 	= xss_clean(json_decode($this->input->post('data'),true));
		$fnc 		= $post_data['fnc'];

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

			default:
				$response['error'] = 'Invalid arguments!';
				break;
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
			$response['data'][$i][] = array(1);		
			$response['data'][$i][] = array(1);	
			$i++;		
		}

		return $response;
	}
}