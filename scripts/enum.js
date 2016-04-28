var AdjustState = {
	Pending : { name : "Pending", value : 1 },
	Approved : { name : "Approved", value : 2 },
	Declined : { name : "Declined", value : 3 }
};

var ProductType = {
	Stock : 1,
	NonStock : 0
};

var InventoryState = {
	Sufficient  : 0,
	Minimum     : 1,
	Negative    : 2
}

var InventoryCheckerType = {
	MaxInv  : 1,
	MinInv : 0
}

var CustomerType = {
	Regular : 1,
	Walkin : 2
};

var TransactionState = {
	Saved : 1,
	Unsaved : 0
}

var DeliveryType = {
	Unsaved 	: 0,
	Both 		: 1,
	Sales 		: 2,
	Transfer 	: 3
}

var TransferState = {
	ForTransfer : 1,
	ForSales : 0
}

var PurchaseType = {
	Imported : 1,
	Local : 2
}

var NotificationType = {
	Incomplete : 1,
	NoDelivery : 2
};

var Tax = {
	Nonvat : 1,
	Vatable :2 
};

var SalesReportType = {
	DailySales : 1,
	PeriodicSales : 2,
	Customer : 3
};

var ProfileStatus = {
	OwnProfile : 1,
	OtherProfile : 0
};

var UserType = {
	SuperAdmin : 1,
	Admin : 2,
	NormalUser : 3,
	Salesman : 4
};