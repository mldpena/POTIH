<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Productbranchinventory extends CI_Controller {
	
	/**
	 * Load needed model or library for the current controller
	 * @return [none]
	 */
	
	private function _load_libraries()
	{
		$this->load->model('productbranchinventory_model');
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
				$page = 'productbranchinventory_list';
				$data['branch_list'] 	= get_name_list_from_table(TRUE,'branch',FALSE);
				$data['material_list'] 	= get_name_list_from_table(TRUE,'material_type',TRUE);
				$data['subgroup_list'] 	= get_name_list_from_table(TRUE,'subgroup',TRUE);
				break;
			
			default:

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
			case 'get_productbranchinventory_list':
				$this->_get_productbranchinventory_list($post_data);
				break;
			case 'create_table':
				$this->_create_table($post_data);
				break;
			
			
			default:
				
				break;
		}

	}
	private function _get_productbranchinventory_list($param)
	{
		$response = $this->productbranchinventory_model->get_productbranchinventory_list($param);
		echo json_encode($response);
	}
	private function _create_table($param)
	{
		$response = get_name_list_from_table(FALSE,'branch',FALSE);
		echo json_encode($response);
	}

}