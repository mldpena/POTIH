<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Release extends CI_Controller {
	
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
				$page = 'release_list';
				$data['branch_list'] = get_name_list_from_table(TRUE,'branch',TRUE);
				break;

			case 'add':
			case 'view':
				$page = 'release_detail';
				$data['branch_list'] = get_name_list_from_table(TRUE,'branch',FALSE);
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
		$this->load->model('release_model');

		$post_data 	= array();
		$fnc 		= '';

		$post_data 	= xss_clean(json_decode($this->input->post('data'),true));
		$fnc 		= $post_data['fnc'];

		$response['error'] = '';

		try {
			switch ($fnc) 
			{
				case 'create_reference_number':
					$response = get_next_number('release_head','reference_number', array('entry_date' => date("Y-m-d h:i:s")));
					break;

				case 'search_release_list':
					$response = $this->release_model->search_release_list($post_data);
					break;

				case 'delete_head':
					$response = $this->release_model->delete_release_head($post_data);
					break;

				case 'get_release_details':
					$response = $this->release_model->get_release_details();
					break;

				case 'autocomplete_product':
					$response = get_product_list_autocomplete($post_data);
					break;

				case 'insert_detail':
					$response = $this->release_model->insert_release_detail($post_data);
					break;

				case 'update_detail':
					$response = $this->release_model->update_release_detail($post_data);
					break;

				case 'delete_detail':
					$response = $this->release_model->delete_release_detail($post_data);
					break;

				case 'save_release_head':
					$response = $this->release_model->update_release_head($post_data);
					break;

				case 'update_release_detail':
					$response = $this->release_model->update_release_qty_detail($post_data);
					break;

				case 'check_product_inventory':
					$response = check_current_inventory($post_data);
					break;
					
				default:
					$response['error'] = 'Invalid Arguments!';
					break;
			}
		}catch (Exception $e) {
			$response['error'] = $e->getMessage();
		}

		echo json_encode($response);
	}

}