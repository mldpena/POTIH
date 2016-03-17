<?php
	namespace Constants;
	
	require_once(CONSTANTS.'default_const.php');

	class SalesReservation_Const extends Default_Const
	{
		const ORDER_BY_REFERENCE = 1;
		const ORDER_BY_LOCATION = 2;
		const ORDER_BY_DATE = 3;
		const ORDER_BY_CUSTOMER = 4;
		const INCOMPLETE = 1;
		const COMPLETE = 2;
		const NO_SOLD = 3;
		const EXCESS = 4;
		const INCOMPLETE_NO_SOLD = 5;
		const WALKIN = -1;
		const TBL_RESERVATION_DETAIL = 'sales_reservation_detail';
		const TBL_RESERVATION_HEAD = 'sales_reservation_head';
	}

?>