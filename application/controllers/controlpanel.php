<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ControlPanel extends CI_Controller {
	
	/**
	 * Load needed model or library for the current controller
	 * @return [none]
	 */
	
	private function _load_libraries()
	{
		$this->load->helper('authentication');
		$this->load->helper('query');
		$this->load->library('permission_checker');
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

		$data = array(	'name' 			=> get_user_fullname(),
						'branch' 		=> get_branch_name(),
						'token' 		=> '&'.$this->security->get_csrf_token_name().'='.$this->security->get_csrf_hash(),
						'page' 			=> 'controlpanel',
						'script'		=> 'controlpanel_js.php',
						'section_permissions' => array(	'data' => $this->permission_checker->check_section_permission('data'),
													'purchase' => $this->permission_checker->check_section_permission('purchase'),
													'return' => $this->permission_checker->check_section_permission('return'),
													'delivery' => $this->permission_checker->check_section_permission('delivery'),
													'others' => $this->permission_checker->check_section_permission('others'),
													'reports' => $this->permission_checker->check_section_permission('reports')),
						'page_permissions' => array('product' => $this->permission_checker->check_permission(\Permission\Product_Code::VIEW_PRODUCT),
													'material' => $this->permission_checker->check_permission(\Permission\Material_Code::VIEW_MATERIAL),
													'subgroup' => $this->permission_checker->check_permission(\Permission\SubGroup_Code::VIEW_SUBGROUP),
													'user' => $this->permission_checker->check_permission(\Permission\User_Code::VIEW_USER),
													'branch' => $this->permission_checker->check_permission(\Permission\Branch_Code::VIEW_BRANCH),
													'purchase' => $this->permission_checker->check_permission(\Permission\Purchase_Code::VIEW_PURCHASE),
													'purchase_receive' => $this->permission_checker->check_permission(\Permission\PurchaseReceive_Code::VIEW_PURCHASE_RECEIVE),
													'customer_return' => $this->permission_checker->check_permission(\Permission\CustomerReturn_Code::VIEW_CUSTOMER_RETURN),
													'damage' => $this->permission_checker->check_permission(\Permission\Damage_Code::VIEW_DAMAGE),
													'purchase_return' => $this->permission_checker->check_permission(\Permission\PurchaseReturn_Code::VIEW_PURCHASE_RETURN),
													'stock_delivery' => $this->permission_checker->check_permission(\Permission\StockDelivery_Code::VIEW_STOCK_DELIVERY),
													'stock_receive' => $this->permission_checker->check_permission(\Permission\StockReceive_Code::VIEW_STOCK_RECEIVE),
													'customer_receive' => $this->permission_checker->check_permission(\Permission\CustomerReceive_Code::VIEW_CUSTOMER_RECEIVE),
													'inventory_adjust' => $this->permission_checker->check_permission(\Permission\InventoryAdjust_Code::VIEW_INVENTORY_ADJUST),
													'pending_adjust' => $this->permission_checker->check_permission(\Permission\PendingAdjust_Code::VIEW_PENDING_ADJUST),
													'release' => $this->permission_checker->check_permission(\Permission\Release_Code::VIEW_RELEASE),
													'inventory_warning' => $this->permission_checker->check_permission(\Permission\InventoryWarning_Code::VIEW_WARNING),
													'branch_inventory' => $this->permission_checker->check_permission(\Permission\BranchInventory_Code::VIEW_BRANCH_INVENTORY)));


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
			case '':
				break;

			default:
				
				break;
		}

	}
}
