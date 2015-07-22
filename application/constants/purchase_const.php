<?php
	namespace Constants;
	
	require_once(CONSTANTS.'default_const.php');

	class Purchase_Const extends Default_Const
	{
		const ORDER_BY_REFERENCE = 1;
		const ORDER_BY_LOCATION = 2;
		const ORDER_BY_DATE = 3;
		const ORDER_BY_SUPPLIER = 4;
		const INCOMPLETE = 1;
		const COMPLETE = 2;
		const NO_RECEIVED = 3;
		const EXCESS = 4;
		const IMPORTED = 1;
		const LOCAL = 2;
	}

?>