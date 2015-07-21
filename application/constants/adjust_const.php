<?php
	namespace Constants;
	
	require_once(CONSTANTS.'default_const.php');

	class Adjust_Const extends Default_Const
	{
		const ORDER_BY_CODE = 1;
		const ORDER_BY_NAME = 2;
		const ORDER_BY_LOCATION = 3;
		const PENDING = 1;
		const APPROVED = 2;
		const DECLINED = 3;
	}

?>