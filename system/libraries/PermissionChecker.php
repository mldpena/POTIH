<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class CI_PermissionChecker
{
	private $_CI;
	private $_current_permission_list = array();

	public function __construct()
	{
		$this->_CI =& get_instance();
		$this->_CI->load->helper('cookie');
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
											\Permission\CustomerReturn_Code::VIEW_CUSTOMER_RETURN);
				break;

			case 'return':
				$permission_needed = array(\Permission\SuperAdmin_Code::ADMIN,
											\Permission\Damage_Code::VIEW_DAMAGE,
											\Permission\PurchaseReturn_Code::VIEW_PURCHASE_RETURN);
				break;

			case 'delivery':
				$permission_needed = array(\Permission\SuperAdmin_Code::ADMIN,
											\Permission\StockDelivery_Code::VIEW_STOCK_DELIVERY,
											\Permission\StockReceive_Code::VIEW_STOCK_RECEIVE,
											\Permission\CustomerReceive_Code::VIEW_CUSTOMER_RECEIVE);
				break;

			case 'others':
				$permission_needed = array(\Permission\SuperAdmin_Code::ADMIN,
											\Permission\InventoryAdjust_Code::VIEW_INVENTORY_ADJUST,
											\Permission\PendingAdjust_Code::VIEW_PENDING_ADJUST,
											\Permission\Release_Code::VIEW_RELEASE);
				break;

			case 'reports':
				$permission_needed = array(\Permission\SuperAdmin_Code::ADMIN,
											\Permission\InventoryWarning_Code::VIEW_WARNING,
											\Permission\BranchInventory_Code::VIEW_BRANCH_INVENTORY);
				break;
		}

		return $this->_check_permission_exists($permission_needed);
	}

	public function _check_page_permission($page)
	{

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
}