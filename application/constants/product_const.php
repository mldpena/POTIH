<?php
	namespace Constants;
	
	require_once(CONSTANTS.'default_const.php');

	class Product_Const extends Default_Const
	{
		const ORDER_BY_CODE = 1;
		const ORDER_BY_NAME = 2;
		const WITH_TRANSACTION = 1;
		const WITHOUT_TRANSACTION = 2;
		const NEGATIVE_STATUS_INV = 1;
		const INSUFFICIENT_STATUS_INV = 2;
		const EXCESS_STATUS_INV = 3;
	}

?>