<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Printout extends CI_Controller {

	/**
	 * Load needed model or library for the current controller
	 * @return [none]
	 */
	
	public function __construct()
	{
		parent::__construct();
		$this->load->library('tcpdf/tcpdf');
	}

	/**
	 * Default method for the controller
	 * @return [none]
	 */
	
	public function index()
	{	
		$page = $this->uri->segment(2);

		try
		{
			switch ($page) 
			{
				case 'assortment':
					
					$this->load->model('assortment_model');

					$page 		= 'release_slip';
					$response 	= $this->assortment_model->get_release_order_printout_details();
					break;

				case 'release':
					$this->load->model('release_model');

					$page 		= 'release_slip';
					$response 	= $this->release_model->get_release_printout_details();
					break;

				case 'delivery_receive':
					$this->load->model('delivery_model');

					$page 		= 'receive_summary';
					$response 	= $this->delivery_model->get_receive_printout_detail();

					break;

				case 'purchase_receive':
					$this->load->model('purchasereceive_model');

					$page 		= 'receive_summary';
					$response 	= $this->purchasereceive_model->get_receive_printout_detail();

					break;

				case 'customer_return':
					$this->load->model('return_model');

					$page 		= 'receive_summary';
					$response 	= $this->return_model->get_receive_printout_detail();
					break;

				case 'delivery':
					$this->load->model('delivery_model');

					$page 		= 'delivery_summary';
					$response 	= $this->delivery_model->get_delivery_printout_details();
					break;

				case 'pickup':
					$this->load->model('pickup_model');

					$page 		= 'pickup_summary';
					$response 	= $this->pickup_model->get_pickup_printout_details();
					break;

				case 'purchase_order':
					$this->load->model('purchaseorder_model');

					$page 		= 'purchase_order';
					$response 	= $this->purchaseorder_model->get_purchase_order_printout_details();
					break;

				case 'damage':
					$this->load->model('damage_model');

					$page 		= 'damage_entry';
					$response 	= $this->damage_model->get_damage_printout_details();
					break;

				case 'purchase_return':
					$this->load->model('purchasereturn_model');

					$page 		= 'purchase_return';
					$response 	= $this->purchasereturn_model->get_purchase_return_printout_details();
					break;

				case 'customer_receive':
					$this->load->model('delivery_model');

					$page 		= 'customer_receive';
					$response 	= $this->delivery_model->get_customer_receive_printout_details();
					break;

				default:
					echo "Invalid Page URL!";
					exit();
					break;
			}

			$this->load->view('pdf/'.$page, $response);
			
		}
		catch(Exception $e)
		{
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
