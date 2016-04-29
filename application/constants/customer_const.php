<?php
	namespace Constants;
	
	require_once(CONSTANTS.'user_const.php');

	class Customer_Const extends User_Const
	{
		const NONVAT = 1;
		const VATABLE = 2;
	}

?>