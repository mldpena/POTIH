<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Branch extends CI_Controller {
	
	/**
	 * Load needed model or library for the current controller
	 * @return [none]
	 */
	
	private function _load_libraries()
	{
		
		$this->load->helper('authentication');
		$this->load->helper('query');
		$this->load->model('branch_model');
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
				$page = 'branch_list';
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
			case 'insert_new_branch':
				$this->_add_new_branch($post_data);
				break;

			case 'search_branch_list' :
				$this->_search_branch_list($post_data);
				break;

			case 'get_branch_details' :
				$this->_get_branch_details($post_data);
				break;

			case 'edit_branch' :
				$this->_update_branch_details($post_data);
				break;

			case 'delete_branch' :
				$this->_delete_branch_details($post_data);
				break;

			default:
				
				break;
		}

	}

	private function _add_new_branch($param)
	{
		$response = $this->branch_model->add_new_branch($param);
		echo json_encode($response);
	}

	private function _search_branch_list($param)
	{
		$response = $this->branch_model->search_branch_list($param);
		echo json_encode($response);
	}
	
	private function _get_branch_details($param)
	{
		$response = $this->branch_model->get_branch_details($param);
		echo json_encode($response);
	}
	
	private function _update_branch_details($param)
	{
		$response = $this->branch_model->update_branch($param);
		echo json_encode($response);
	}

	private function _delete_branch_details($param)
	{
		$response = $this->branch_model->delete_branch($param);
		echo json_encode($response);
	}

}
