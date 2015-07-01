<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Damage extends CI_Controller {
	
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
				$page = 'damage_list';
				$data['branch_list'] = get_name_list_from_table(TRUE,'branch',TRUE);
				break;

			case 'add':
			case 'view':
				$page = 'damage_detail';
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
		$this->load->model('damage_model');
		
		$post_data 	= array();
		$fnc 		= '';

		$post_data 	= xss_clean(json_decode($this->input->post('data'),true));
		$fnc 		= $post_data['fnc'];

		$response['error'] = '';

		try {
			switch ($fnc) 
			{
				case 'create_reference_number':
					$response = get_next_number('damage_head','reference_number',array('entry_date' => date("Y-m-d h:i:s")));
					break;

				case 'get_damage_details':
					$response = $this->damage_model->get_damage_details();
					break;

				case 'autocomplete_product':
					$response = get_product_list_autocomplete($post_data);
					break;

				case 'insert_detail':
					$response = $this->damage_model->insert_damage_detail($post_data);
					break;

				case 'update_detail':
					$response = $this->damage_model->update_damage_detail($post_data);
					break;

				case 'delete_detail':
					$response = $this->damage_model->delete_damage_detail($post_data);
					break;

				case 'save_damage_head':
					$response = $this->damage_model->update_damage_head($post_data);
					break;

				case 'search_damage_list':
					$response = $this->damage_model->search_damage_list($post_data);
					break;

				case 'delete_head':
					$response = $this->damage_model->delete_damage_head($post_data);
					break;

				case 'check_product_inventory':
					$response = check_current_inventory($post_data);
					break;
					
				default:
					$response['error'] = 'Invalid arguments!';
					break;
			}
		}catch (Exception $e) {
			$response['error'] = $e->getMessage();
		}

		echo json_encode($response);
	}
}
