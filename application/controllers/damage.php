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
		$this->load->model('damage_model');
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

			case 'get_damage_details':
				$this->_get_damage_details();
				break;

			case 'autocomplete_product':
				$this->_get_produc_list($post_data);
				break;

			case 'insert_damage_detail':
				$this->_insert_damage_detail($post_data);
				break;

			case 'update_damage_detail':
				$this->_update_damage_detail($post_data);
				break;

			case 'delete_damage_detail':
				$this->_delete_damage_detail($post_data);
				break;

			case 'save_damage_head':
				$this->_save_damage_head($post_data);
				break;

			case 'search_damage_list':
				$this->_search_damage_list($post_data);
				break;

			case 'delete_damage_head':
				$this->_delete_damage_head($post_data);
				break;

			default:
				
				break;
		}

	}

	private function _create_reference_number($param)
	{
		$response = get_next_number('damage_head','reference_number');
		echo json_encode($response);
	}

	private function _get_damage_details()
	{
		$response = $this->damage_model->get_damage_details();
		echo json_encode($response);
	}

	private function _get_produc_list($param)
	{
		$response = get_product_list_autocomplete($param);
		echo json_encode($response);
	}

	private function _insert_damage_detail($param)
	{
		$response = $this->damage_model->insert_damage_detail($param);
		echo json_encode($response);
	}

	private function _update_damage_detail($param)
	{
		$response = $this->damage_model->update_damage_detail($param);
		echo json_encode($response);
	}

	private function _delete_damage_detail($param)
	{
		$response = $this->damage_model->delete_damage_detail($param);
		echo json_encode($response);
	}

	private function _save_damage_head($param)
	{
		$response = $this->damage_model->update_damage_head($param);
		echo json_encode($response);
	}

	private function _search_damage_list($param)
	{
		$response = $this->damage_model->search_damage_list($param);
		echo json_encode($response);
	}

	private function _delete_damage_head($param)
	{
		$response = $this->damage_model->delete_damage_head($param);
		echo json_encode($response);
	}
}
