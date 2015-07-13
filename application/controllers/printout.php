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

		try
		{
			switch ($page) 
			{
				case 'release':
					
					$this->load->model('release_model');

					$page 		= 'pdf/release_slip.php';
					$response 	= $this->release_model->get_release_printout_detail();
					break;

				case 'delivery_receive':
					$this->load->model('delivery_model');

					$page 		= 'pdf/receive_summary.php';
					$response 	= $this->delivery_model->get_receive_printout_detail();

					break;

				case 'purchase_receive':
					$this->load->model('purchasereceive_model');

					$page 		= 'pdf/receive_summary.php';
					$response 	= $this->purchasereceive_model->get_receive_printout_detail();

					break;

				case 'customer_return':
					$this->load->model('return_model');

					$page 		= 'pdf/receive_summary.php';
					$response 	= $this->return_model->get_receive_printout_detail();
					break;

				case 'delivery':
					$this->load->model('delivery_model');

					$page 		= 'pdf/delivery_summary.php';
					$response 	= $this->delivery_model->get_delivery_printout_details();
					break;

				case 'pickup':
					$this->load->model('release_model');

					$page 		= 'pdf/pickup_summary.php';
					$response 	= $this->release_model->get_pickup_printout_details();
					break;

				case 'purchase_order':
					$this->load->model('purchaseorder_model');

					$page 		= 'pdf/purchase_order.php';
					$response 	= $this->purchaseorder_model->get_purchase_order_printout_details();
					break;

				case 'damage':
					$this->load->model('damage_model');

					$page 		= 'pdf/damage_entry.php';
					$response 	= $this->damage_model->get_damage_printout_details();
					break;

				case 'purchase_return':
					$this->load->model('purchasereturn_model');

					$page 		= 'pdf/purchase_return.php';
					$response 	= $this->purchasereturn_model->get_purchase_return_printout_details();
					break;

				case 'customer_receive':
					$this->load->model('delivery_model');

					$page 		= 'pdf/customer_receive.php';
					$response 	= $this->delivery_model->get_customer_receive_printout_details();
					break;

				default:
					echo "Invalid Page URL!";
					exit();
					break;
			}

			$this->load->view($page, $response);
			
		}catch(Exception $e){
			echo $e->getMessage();
		}
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
