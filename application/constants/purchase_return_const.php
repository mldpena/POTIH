<?php
	namespace Constants;
	
	require_once(CONSTANTS.'default_const.php');

	class Purchase_Return_Const extends Default_Const
	{
		const ORDER_BY_REFERENCE = 1;
		const ORDER_BY_LOCATION = 2;
		const ORDER_BY_DATE = 3;
		const ORDER_BY_SUPPLIER = 4;
	}

?>