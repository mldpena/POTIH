<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class CI_PermissionChecker
	{
		public function __construct()
		{
			//echo "string";
			//$this->_CI =& get_instance();
			//$this->_CI->load->helper('cookie');
			//$this->_current_permission_list = json_decode(get_cookie('permissions'));
		}

		public function sample()
		{
			echo "string";
			/*$permissionExist = false;

			switch ($page) {
				case 'data':
					$permissionNeeded = array(\Permission\SuperAdmin_Code::ADMIN,
												\Permission\Product_Code::VIEW_PRODUCT,
												\Permission\Material_Code::VIEW_MATERIAL,
												\Permission\SubGroup_Code::VIEW_SUBGROUP,
												\Permission\User_Code::VIEW_USER,
												\Permission\Branch_Code::VIEW_BRANCH);
					break;
			}

			for ($i = 0; $i < count($permissionNeeded); $i++) { 
				if (in_array($permissionNeeded[$i],$this->_current_permission_list)) {
					$permissionExist = true;
					break;
				}
			}

			return $permissionExist;*/
		}
	}
?>