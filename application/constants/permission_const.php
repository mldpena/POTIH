<?php
	namespace Permission;

	class SuperAdmin_Code
	{
		const ADMIN = 100;
	}
	
	class Product_Code
	{
		const VIEW_PRODUCT = 101;
		const ADD_PRODUCT = 102;
		const EDIT_PRODUCT = 103;
		const DELETE_PRODUCT = 104;
	}

	class Material_Code
	{
		const VIEW_MATERIAL = 105;
		const ADD_MATERIAL = 106;
		const EDIT_MATERIAL = 107;
		const DELETE_MATERIAL = 108;
	}

	class SubGroup_Code
	{
		const VIEW_SUBGROUP = 109;
		const ADD_SUBGROUP = 110;
		const EDIT_SUBGROUP = 111;
		const DELETE_SUBGROUP = 112;
	}

	class User_Code
	{
		const VIEW_USER = 113;
		const VIEW_USER_DETAIL = 114;
		const ADD_USER = 115;
		const EDIT_USER = 116;
		const DELETE_USER = 117;
	}

	class Branch_Code
	{
		const VIEW_BRANCH  = 118;
		const ADD_BRANCH = 119;
		const EDIT_BRANCH = 120;
		const DELETE_BRANCH = 121;
	}

	class Purchase_Code
	{
		const VIEW_PURCHASE = 131;
		const VIEW_PURCHASE_DETAIL = 132;
		const ADD_PURCHASE = 133;
		const EDIT_PURCHASE = 134;
		const DELETE_PURCHASE= 135;
		const TRANSFER_INCOMPLETE_PO = 241;
	}

	class PurchaseReceive_Code
	{
		const VIEW_PURCHASE_RECEIVE = 136;
		const VIEW_PURCHASE_RECEIVE_DETAIL = 137;
		const ADD_PURCHASE_RECEIVE = 138;
		const EDIT_PURCHASE_RECEIVE = 139;
		const DELETE_PURCHASE_RECEIVE= 140; 
	}

	class CustomerReturn_Code
	{
		const VIEW_CUSTOMER_RETURN = 141;
		const VIEW_CUSTOMER_RETURN_DETAIL = 142;
		const ADD_CUSTOMER_RETURN = 143;
		const EDIT_CUSTOMER_RETURN = 144;
		const DELETE_CUSTOMER_RETURN= 145; 
	}

	class Damage_Code
	{
		const VIEW_DAMAGE = 156;
		const VIEW_DAMAGE_DETAIL = 157;
		const ADD_DAMAGE = 158;
		const EDIT_DAMAGE = 159;
		const DELETE_DAMAGE= 160; 
	}

	class PurchaseReturn_Code
	{
		const VIEW_PURCHASE_RETURN = 161;
		const VIEW_PURCHASE_RETURN_DETAIL = 162;
		const ADD_PURCHASE_RETURN = 163;
		const EDIT_PURCHASE_RETURN = 164;
		const DELETE_PURCHASE_RETURN= 165; 
	}

	class StockDelivery_Code
	{
		const VIEW_STOCK_DELIVERY = 171;
		const VIEW_STOCK_DELIVERY_DETAIL = 172;
		const ADD_STOCK_DELIVERY = 173;
		const EDIT_STOCK_DELIVERY = 174;
		const DELETE_STOCK_DELIVERY= 175;
		const EDIT_INCOMPLETE_TRANSACTION = 246; 
	}

	class StockReceive_Code
	{
		const VIEW_STOCK_RECEIVE = 176;
		const VIEW_STOCK_RECEIVE_DETAIL = 177;
		const EDIT_STOCK_RECEIVE = 178;
	}

	class CustomerReceive_Code
	{
		const VIEW_CUSTOMER_RECEIVE = 179;
		const VIEW_CUSTOMER_RECEIVE_DETAIL = 180;
		const EDIT_CUSTOMER_RECEIVE = 181;
		const TRANSFER_TO_RETURN = 245;
	}

	class InventoryAdjust_Code
	{
		const VIEW_INVENTORY_ADJUST = 191;
		const VIEW_INVENTORY_ADJUST_DETAIL = 192;
		const ADD_INVENTORY_ADJUST = 193;
		const EDIT_INVENTORY_ADJUST = 194;
		const DELETE_INVENTORY_ADJUST= 195; 
	}

	class PendingAdjust_Code
	{
		const VIEW_PENDING_ADJUST = 196;
		const ALLOW_TO_APPROVE_AND_DECLINE = 197;
		const AUTO_APPROVE = 198;
	}

	class Release_Code
	{
		const VIEW_RELEASE = 199;
		const VIEW_RELEASE_DETAIL = 200;
		const ADD_RELEASE = 201;
		const EDIT_RELEASE = 202;
		const DELETE_RELEASE= 203; 
	}

	class InventoryWarning_Code
	{
		const VIEW_WARNING = 211;
	}

	class BranchInventory_Code
	{
		const VIEW_BRANCH_INVENTORY = 212;
	}

	class TransactionSummary_Code
	{
		const VIEW_TRANSACTION_SUMMARY = 213;
	}

	class Assortment_Code
	{
		const VIEW_ASSORTMENT = 223;
		const VIEW_ASSORTMENT_DETAIL = 224;
		const ADD_ASSORTMENT = 225;
		const EDIT_ASSORTMENT = 226;
		const DELETE_ASSORTMENT= 227;
		const EDIT_INCOMPLETE_TRANSACTION = 243;
	}

	class PickUp_Code
	{
		const GENERATE_SUMMARY = 232;
		const PRINT_SUMMARY = 233;
	}

	class StockRequestTo_Code
	{
		const VIEW_STOCKREQUEST = 234;
		const VIEW_STOCKREQUEST_DETAIL = 235;
		const ADD_STOCKREQUEST = 236;
		const EDIT_STOCKREQUEST = 237;
		const DELETE_STOCKREQUEST= 238;
		const EDIT_INCOMPLETE_TRANSACTION = 242;
	}

	class StockRequestFrom_Code
	{
		const VIEW_STOCKREQUEST = 239;
		const VIEW_STOCKREQUEST_DETAIL = 240;
	}

	class Customer_Code
	{
		const VIEW_CUSTOMER = 250;
		const VIEW_CUSTOMER_DETAIL = 251;
		const ADD_CUSTOMER = 252;
		const EDIT_CUSTOMER = 253;
		const DELETE_CUSTOMER = 254;
	}

	class SalesReservation_Code
	{
		const VIEW_SALES_RESERVATON = 260;
		const VIEW_SALES_RESERVATON_DETAIL = 261;
		const ADD_SALES_RESERVATON = 262;
		const EDIT_SALES_RESERVATON = 263;
		const DELETE_SALES_RESERVATON= 264;
	}
?>