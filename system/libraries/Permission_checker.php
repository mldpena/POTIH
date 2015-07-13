<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class CI_Permission_checker
{
	private $_CI;
	private $_current_permission_list = array();

	public function __construct()
	{
		$this->_CI =& get_instance();
		$this->_CI->load->helper('cookie');
		$this->_CI->load->file(CONSTANTS.'permission_const.php');
		$this->_current_permission_list = json_decode(get_cookie('permissions'));
	}

	public function check_section_permission($page)
	{
		$permission_exist = false;

		switch ($page) {
			case 'data':
				$permission_needed = array(\Permission\SuperAdmin_Code::ADMIN,
											\Permission\Product_Code::VIEW_PRODUCT,
											\Permission\Material_Code::VIEW_MATERIAL,
											\Permission\SubGroup_Code::VIEW_SUBGROUP,
											\Permission\User_Code::VIEW_USER,
											\Permission\Branch_Code::VIEW_BRANCH);
				break;

			case 'purchase':
				$permission_needed = array(\Permission\SuperAdmin_Code::ADMIN,
											\Permission\Purchase_Code::VIEW_PURCHASE,
											\Permission\PurchaseReceive_Code::VIEW_PURCHASE_RECEIVE,
											\Permission\PurchaseReturn_Code::VIEW_PURCHASE_RETURN);
				break;

			case 'damage':
				$permission_needed = array(\Permission\SuperAdmin_Code::ADMIN,
											\Permission\Damage_Code::VIEW_DAMAGE);
				break;

			case 'delivery':
				$permission_needed = array(\Permission\SuperAdmin_Code::ADMIN,
											\Permission\StockRequestTo_Code::VIEW_STOCKREQUEST,
											\Permission\StockRequestFrom_Code::VIEW_STOCKREQUEST,
											\Permission\StockDelivery_Code::VIEW_STOCK_DELIVERY,
											\Permission\StockReceive_Code::VIEW_STOCK_RECEIVE,
											\Permission\CustomerReceive_Code::VIEW_CUSTOMER_RECEIVE,
											\Permission\CustomerReturn_Code::VIEW_CUSTOMER_RETURN);
				break;

			case 'pickup':
				$permission_needed = array(\Permission\SuperAdmin_Code::ADMIN,
											\Permission\Assortment_Code::VIEW_ASSORTMENT,
											\Permission\Release_Code::VIEW_RELEASE,
											\Permission\PickUp_Code::PRINT_SUMMARY);
				break;

			case 'adjust':
				$permission_needed = array(\Permission\SuperAdmin_Code::ADMIN,
											\Permission\InventoryAdjust_Code::VIEW_INVENTORY_ADJUST,
											\Permission\PendingAdjust_Code::VIEW_PENDING_ADJUST);
				break;

			case 'reports':
				$permission_needed = array(\Permission\SuperAdmin_Code::ADMIN,
											\Permission\InventoryWarning_Code::VIEW_WARNING,
											\Permission\BranchInventory_Code::VIEW_BRANCH_INVENTORY,
											\Permission\TransactionSummary_Code::VIEW_TRANSACTION_SUMMARY);
				break;
		}

		return $this->_check_permission_exists($permission_needed);
	}

	private function _check_permission_exists($permission_needed)
	{
		$permission_exists = false;

		for ($i = 0; $i < count($permission_needed); $i++) 
		{ 
			if (in_array($permission_needed[$i],$this->_current_permission_list)) 
			{
				$permission_exists = true;
				break;
			}
		}

		return $permission_exists;
	}

	public function check_permission($permission_needed)
	{
		if (!is_array($permission_needed))
			$permission_list = array(\Permission\SuperAdmin_Code::ADMIN,$permission_needed);
		else
		{
			$permission_list = array(\Permission\SuperAdmin_Code::ADMIN);
			$permission_list = array_merge($permission_list,$permission_needed);
		}
		
		return $permission_exists = $this->_check_permission_exists($permission_list);
	}

	public function get_section_permissions()
	{
		return array('data' => $this->check_section_permission('data'),
					'purchase' => $this->check_section_permission('purchase'),
					'delivery' => $this->check_section_permission('delivery'),
					'damage' => $this->check_section_permission('damage'),
					'pickup' => $this->check_section_permission('pickup'),
					'adjust' => $this->check_section_permission('adjust'),
					'reports' => $this->check_section_permission('reports'));
	}

	public function get_page_permissions()
	{
		return  array('product' => $this->check_permission(\Permission\Product_Code::VIEW_PRODUCT),
						'material' => $this->check_permission(\Permission\Material_Code::VIEW_MATERIAL),
						'subgroup' => $this->check_permission(\Permission\SubGroup_Code::VIEW_SUBGROUP),
						'user' => $this->check_permission(\Permission\User_Code::VIEW_USER),
						'branch' => $this->check_permission(\Permission\Branch_Code::VIEW_BRANCH),
						'purchase' => $this->check_permission(\Permission\Purchase_Code::VIEW_PURCHASE),
						'purchase_receive' => $this->check_permission(\Permission\PurchaseReceive_Code::VIEW_PURCHASE_RECEIVE),
						'customer_return' => $this->check_permission(\Permission\CustomerReturn_Code::VIEW_CUSTOMER_RETURN),
						'damage' => $this->check_permission(\Permission\Damage_Code::VIEW_DAMAGE),
						'purchase_return' => $this->check_permission(\Permission\PurchaseReturn_Code::VIEW_PURCHASE_RETURN),
						'stock_request_to' => $this->check_permission(\Permission\StockRequestTo_Code::VIEW_STOCKREQUEST),
						'stock_request_from' => $this->check_permission(\Permission\StockRequestFrom_Code::VIEW_STOCKREQUEST),
						'stock_delivery' => $this->check_permission(\Permission\StockDelivery_Code::VIEW_STOCK_DELIVERY),
						'stock_receive' => $this->check_permission(\Permission\StockReceive_Code::VIEW_STOCK_RECEIVE),
						'customer_receive' => $this->check_permission(\Permission\CustomerReceive_Code::VIEW_CUSTOMER_RECEIVE),
						'inventory_adjust' => $this->check_permission(\Permission\InventoryAdjust_Code::VIEW_INVENTORY_ADJUST),
						'pending_adjust' => $this->check_permission(\Permission\PendingAdjust_Code::VIEW_PENDING_ADJUST),
						'release' => $this->check_permission(\Permission\Release_Code::VIEW_RELEASE),
						'inventory_warning' => $this->check_permission(\Permission\InventoryWarning_Code::VIEW_WARNING),
						'branch_inventory' => $this->check_permission(\Permission\BranchInventory_Code::VIEW_BRANCH_INVENTORY),
						'transaction_summary' => $this->check_permission(\Permission\TransactionSummary_Code::VIEW_TRANSACTION_SUMMARY),
						'assortment' => $this->check_permission(\Permission\Assortment_Code::VIEW_ASSORTMENT),
						'pickup' => $this->check_permission(\Permission\PickUp_Code::PRINT_SUMMARY));
	}
}