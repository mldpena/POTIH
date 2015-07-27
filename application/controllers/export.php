<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Export extends CI_Controller {

	private $_export_manager;
	/**
	 * Load needed model or library for the current controller
	 * @return [none]
	 */
	
	public function __construct()
	{
		parent::__construct();

		$this->load->library('zip');
		$this->load->library('XLSXWriter');
		$this->load->service('export_manager');

		$this->_export_manager = new Services\Export_Manager();
	}

	/**
	 * Default method for the controller
	 * @return [none]
	 */
	
	public function index()
	{	
		$access_type = $this->input->get('fnc');

		try
		{
			switch ($access_type) 
			{
				case 'product_list':
					$page = 'products';
					$response = $this->_export_manager->parse_get_product_list($this->input->get());
					break;

				case 'purchase_transaction':
					$page = 'purchase_transaction';
					$response = $this->_export_manager->parse_get_purchase_by_transaction($this->input->get());
					break;

				case 'purchase_receive_transaction':
					$page = 'purchase_receive_transaction';
					$response = $this->_export_manager->parse_get_purchase_receive_by_transaction($this->input->get());
					break;

				case 'purchase_return_transaction':
					$page = 'purchase_return_transaction';
					$response = $this->_export_manager->parse_get_purchase_return_by_transaction($this->input->get());
					break;

				case 'delivery_transaction':
					$page = 'delivery_transaction';
					$response = $this->_export_manager->parse_get_delivery_by_transaction($this->input->get());
					break;

				case 'delivery_receive_transaction':
					$page = 'delivery_receive_transaction';
					$response = $this->_export_manager->parse_get_delivery_receive_by_transaction($this->input->get(), 'TRANSFER');
					break;

				case 'customer_receive_transaction':
					$page = 'customer_receive_transaction';
					$response = $this->_export_manager->parse_get_delivery_receive_by_transaction($this->input->get(), 'CUSTOMER');
					break;

				case 'customer_return_transaction':
					$page = 'customer_return_transaction';
					$response = $this->_export_manager->parse_get_customer_return_by_transaction($this->input->get());
					break;

				case 'damage_transaction':
					$page = 'damage_transaction';
					$response = $this->_export_manager->parse_get_damage_by_transaction($this->input->get());
					break;

				case 'inventory_adjustment':
					$page = 'inventory_adjustment';
					$response = $this->_export_manager->parse_get_inventory_adjustment($this->input->get());
					break;

				case 'inventory_warning':
					$page = 'inventory_warning';
					$response = $this->_export_manager->parse_get_inventory_warning($this->input->get());
					break;

				case 'branch_inventory':
					$page = 'branch_inventory';
					$response = $this->_export_manager->parse_get_branch_inventory($this->input->get());
					break;

				case 'product_transaction':
					$page = 'product_transaction';
					$response = $this->_export_manager->parse_get_product_transaction_list_info($this->input->get());
					break;

				default:
					echo "Invalid Page URL!";
					exit();
					break;
			}

			$this->load->view('excel/'.$page, $response);
			
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
