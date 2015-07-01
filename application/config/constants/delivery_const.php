<?php
	/**
	* List of constants for delivery model
	*/

	require_once(CONSTANTS.'default_const.php');

	class Delivery_Const extends Default_Const
	{
		const ORDER_BY_REFERENCE = 1;
		const ORDER_BY_LOCATION = 2;
		const ORDER_BY_DATE = 3;
		const ORDER_BY_SUPPLIER = 4;
		const INCOMPLETE = 1;
		const COMPLETE = 2;
		const NO_RECEIVED = 3;
		const BOTH = 1;
		const SALES = 2;
		const TRANSFER = 3;
		const FOR_TRANSFER = 1;
		const FOR_CUSTOMER = 2;
	}

?>