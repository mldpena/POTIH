<?php
	namespace Constants;
	
	abstract class Default_Const
	{
		const DATE_INTERVAL = 30;
		const ACTIVE 		= 1;
		const DELETED 		= 0;
		const ALL_OPTION 	= 0;

		const NON_STOCK 	= 0;
		const STOCK 		= 1;

		const POSITIVE_INV 	= 1;
		const NEGATIVE_INV 	= 2;
		const ZERO_INV 	= 3;

		const USED = 1;

		const INSERT_PROCESS = 1;
		const UPDATE_PROCESS = 2;

		const MINIMUM = 1;

		const MIN_CHECKER = 0;
		const MAX_CHECKER = 1;

		const PCS = 1;
		const KG = 2;
		const ROLL = 3;

		const WALKIN = -1;
	}
