<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Printout extends CI_Controller {

	/**
	 * Load needed model or library for the current controller
	 * @return [none]
	 */
	
	private function _load_libraries()
	{
		$this->load->library('tcpdf/tcpdf');
		$this->load->library('session');
	}

	/**
	 * Default method for the controller
	 * @return [none]
	 */
	
	public function index()
	{	
		$this->_load_libraries();
		
		$page = $this->uri->segment(2);

		switch ($page) 
		{
			case 'release':
				
				$this->load->model('release_model');

				$page 		= 'pdf/release_slip.php';
				$response 	= $this->release_model->get_release_printout_detail();
				break;

			case 'view':
				$page = 'release_detail';

				break;

			default:
				echo "Invalid Page URL!";
				exit();
				break;
		}

		$this->load->view($page, $response);
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
}
