<?php
	/**
	* List of constants for delivery model
	*/

	require_once(CONSTANTS.'default_const.php');

	class Release_Const extends Default_Const
	{
		const ORDER_BY_REFERENCE = 1;
		const ORDER_BY_LOCATION = 2;
		const ORDER_BY_CUSTOMER = 3;
		const INCOMPLETE = 1;
		const COMPLETE = 2;
		const NO_RECEIVED = 3;
	}

?>