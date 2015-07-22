<?php
	namespace Constants;
	
	require_once(CONSTANTS.'default_const.php');

	class User_Const extends Default_Const
	{
		const INACTIVE 	= 0;
		const ORDER_BY_CODE = 1;
		const ORDER_BY_NAME = 2;
		const OWN_PROFILE = 1;
		const OTHER_PROFILE = 0;
		const DUMMY_PASSWORD = "******";
	}

?>