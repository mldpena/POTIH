<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Warehouserelease extends CI_Controller {
	
	/**
	 * Load needed model or library for the current controller
	 * @return [none]
	 */
	
	private function _load_libraries()
	{
		$this->load->helper('authentication');
		$this->load->helper('query');
		$this->load->model('warehouserelease_model');
		
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
		
		$addview =  array();
		$this->load->model('warehouserelease_model');
	
		if (isset($_POST['data'])) 
		{
			$this->_ajax_request();
			exit();
		}

		switch ($page) 
		{

			case 'list':
				$page = 'warehouserelease_list';

				$data['branch_list'] = get_name_list_from_table(TRUE,'branch',TRUE);
				break;

			case 'add':
				$addview = 1;
				$page = 'warehouserelease_detail';
				$data['branch_list'] = get_name_list_from_table(TRUE,'branch',FALSE);
							break;
			case 'view':
				$addview= 2;
				
				$page  = 'warehouserelease_detail1';
				
				$data['branch_list'] = get_name_list_from_table(TRUE,'branch',FALSE);
				break;

			default:
				echo "Invalid Page URL!";
				exit();
				break;
		}

		$this->warehouserelease_model->do_some($addview);
		
		$data['name']	= get_user_fullname();
		$data['branch']	= get_branch_name();
		$data['token']	= '&'.$this->security->get_csrf_token_name().'='.$this->security->get_csrf_hash();
		$data['page'] 	= $page;
		$data['script'] = $page.'_js.php';


	  //  $this->load->model('warehouserelease_model', $addview);


		//$this->load->script('warehouserelease_detail', $data);

		$this->load->view('master', $data);
		
		//return $addview;
		//$this->warehouserelease_model->set_variable($variable);
		//return $chosen1;
		//echo json_encode($addview);
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
		$this->load->model('warehouserelease_model');

		$post_data 	= array();
		$fnc 		= '';

		$post_data 	= xss_clean(json_decode($this->input->post('data'),true));
		$fnc 		= $post_data['fnc'];

		switch ($fnc) 
		{
			
			case 'create_reference_number':
				$response = get_next_number('warehouserelease_head','reference_number');
				break;
			case 'get_warehouserelease_details':
				$response = $this->warehouserelease_model->get_warehouserelease_details();
				break;
			case 'autocomplete_product':
				$response = get_product_list_autocomplete($post_data);
				break;
			case 'insert_warehouserelease_detail':
				$response = $this->warehouserelease_model->insert_warehouserelease_detail($post_data);
				break;

			case 'update_warehouserelease_detail':
				$response = $this->warehouserelease_model->update_warehouserelease_detail($post_data);
				break;
			case 'delete_warehouserelease_detail':
				$response = $this->warehouserelease_model->delete_warehouserelease_detail($post_data);
				break;
			case 'save_warehouserelease_head':
				$response = $this->warehouserelease_model->update_warehouserelease_head($post_data);
				break;
			case 'search_warehouserelease_list':
				$response = $this->warehouserelease_model->search_warehouserelease_list($post_data);
				break;
			case 'delete_warehouserelease_head':
				$response = $this->warehouserelease_model->delete_warehouserelease_head($post_data);
				break;

			case 'get_chosen':
				$response = $this->warehouserelease_model->get_chosen($post_data);
				break;
			default:
				$response['error'] = 'Invalid Arguments!';
				break;
		}

		echo json_encode($response);
	}

}
