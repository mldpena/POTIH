<?php
	namespace Constants;
	
	require_once(CONSTANTS.'default_const.php');

	class Sales_Const extends Default_Const
	{
		const ORDER_BY_REFERENCE = 1;
		const ORDER_BY_LOCATION = 2;
		const ORDER_BY_DATE = 3;
		const ORDER_BY_CUSTOMER = 4;
		const INCOMPLETE = 1;
		const COMPLETE = 2;
		const NO_DELIVERY = 3;
		const EXCESS = 4;
		const WALKIN = -1;
		const TBL_SALES_DETAIL = 'sales_detail';
		const TBL_SALES_HEAD = 'sales_head';
		const DAILY_SALES_REPORT = 1;
		const PERIODIC_SALES_REPORT = 2;
		const CUSTOMER_SALES_REPORT = 3;
	}

?>