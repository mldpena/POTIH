<div class="main-content pull-right">
	<div class="breadcrumbs-panel">
		<ol class="breadcrumb">
			<li><a href="<?= base_url() ?>controlpanel">Home</a></li>
			<li><a href="<?= base_url() ?>user/list">User List</a></li>
			<li class="active"><a href="<?= base_url() ?>user/<?= $this->uri->segment(2).'/'.$this->uri->segment(3) ?>">User Information</a></li>
		</ol>
	</div>
	<div class="content-form">
		<div class="form-header">User Information</div>
		<div class="form-body">
			<div class="max-row">
				<div class="row">
					<div class="col-xs-2">
						<img src="<?= base_url().IMG?>person.jpg" class="img-responsive img-thumbnail">
					</div>
					<div class="col-xs-10 tbl-form max">
						<table>
							<tr>
								<td>Branches:</td>
								<td colspan="3">
									<select id="branches" class="form-control" multiple="multiple" data-placeholder="Select a Branch"><?= $branch_list ?></select>
								</td>
							</tr>
							<tr>
								<td>User Code:</td>
								<td><input type="text" class="form-control" id="user_code"></td>
								<td>Username:</td>
								<td><input type="text" class="form-control" id="user_name"></td>
							</tr>
							<tr>
								<td>Full Name:</td>
								<td><input type="text" class="form-control" id="full_name"></td>
								<td>Password:</td>
								<td><input type="password" class="form-control" id="password" value="123456"></td>
							</tr>
							<tr>
								<td>Status:</td>
								<td>
									<div class="tbl-checkbtn">
										<input type="checkbox" id="is_active" checked="checked">
										<span>Active</span>
									</div>
								</td>
								<td>Contact No.:</td>
								<td><input type="text" class="form-control" id="contact" maxlength="11"></td>
							</tr>
						</table>
						<div id="messagebox_1"></div>
					</div>
				</div>
			</div>
			<div class="max-row" align="right">
				<input type="button" class="btn btn-success" value='Save' id="save">
				<input type="button" class="btn btn-info" value='Show Advanced Info' id="show-info-btn">
			</div>
		</div>
	</div>
	<div class="content-form hide-elem" id="show-info">
		<div class="form-header">User Permissions</div>
		<div class="form-body">
			<div class="max-row">
				<div class="tbl-checkbtn">
					<div class="pull-left">
						<input type="checkbox" class="preset" id="admin-permission" value="<?= \Permission\UserType_Code::ADMIN ?>">
						<span>Admin</span>
					</div>
					<div class="pull-left margin-left5">
						<input type="checkbox" class="preset" id="encoder-permission" value="<?= \Permission\UserType_Code::NORMAL_USER ?>">
						<span>Encoder</span>
					</div>
					<div class="pull-left margin-left5">
						<input type="checkbox" class="preset" id="salesman-permission" value="<?= \Permission\UserType_Code::SALESMAN ?>">
						<span>Salesman</span>
					</div>
				</div>
			</div>
			<div class="sub-panel header-section">
				<div align="left">
					<input type="checkbox" class="permission-section" id="data-permission"> Data Section
				</div>
			</div>
			<div class="max-row tbl max user">
				<table>
					<tr class="tableheader">
						<td style="width:345px;">Page</td>
						<td style="width:130px;">View</td>
						<td style="width:130px;">View Detail</td>
						<td style="width:130px;">Add</td>
						<td style="width:130px;">Edit</td>
						<td style="width:130px;">Delete</td>
						<td style="width:320px;">Others</td>
					</tr>
					<tr>
						<td>Product</td>
						<td><input type="checkbox" class="check-detail data-detail encoder-preset" value="<?= \Permission\Product_Code::VIEW_PRODUCT ?>"></td>
						<td></td>
						<td><input type="checkbox" class="check-detail data-detail encoder-preset" value="<?= \Permission\Product_Code::ADD_PRODUCT ?>"></td>
						<td><input type="checkbox" class="check-detail data-detail encoder-preset" value="<?= \Permission\Product_Code::EDIT_PRODUCT ?>"></td>
						<td><input type="checkbox" class="check-detail data-detail encoder-preset" value="<?= \Permission\Product_Code::DELETE_PRODUCT ?>"></td>
						<td></td>
					</tr>
					<tr>
						<td>Material Type</td>
						<td><input type="checkbox" class="check-detail data-detail encoder-preset" value="<?= \Permission\Material_Code::VIEW_MATERIAL ?>"></td>
						<td></td>
						<td><input type="checkbox" class="check-detail data-detail encoder-preset" value="<?= \Permission\Material_Code::ADD_MATERIAL ?>"></td>
						<td><input type="checkbox" class="check-detail data-detail encoder-preset" value="<?= \Permission\Material_Code::EDIT_MATERIAL ?>"></td>
						<td><input type="checkbox" class="check-detail data-detail encoder-preset" value="<?= \Permission\Material_Code::DELETE_MATERIAL ?>"></td>
						<td></td>
					</tr>
					<tr>
						<td>Sub Grouping</td>
						<td><input type="checkbox" class="check-detail data-detail encoder-preset" value="<?= \Permission\SubGroup_Code::VIEW_SUBGROUP ?>"></td>
						<td></td>
						<td><input type="checkbox" class="check-detail data-detail encoder-preset" value="<?= \Permission\SubGroup_Code::ADD_SUBGROUP ?>"></td>
						<td><input type="checkbox" class="check-detail data-detail encoder-preset" value="<?= \Permission\SubGroup_Code::EDIT_SUBGROUP ?>"></td>
						<td><input type="checkbox" class="check-detail data-detail encoder-preset" value="<?= \Permission\SubGroup_Code::DELETE_SUBGROUP ?>"></td>
						<td></td>
					</tr>
					<tr>
						<td>User</td>
						<td><input type="checkbox" class="check-detail data-detail" value="<?= \Permission\User_Code::VIEW_USER ?>"></td>
						<td><input type="checkbox" class="check-detail data-detail" value="<?= \Permission\User_Code::VIEW_USER_DETAIL ?>"></td>
						<td><input type="checkbox" class="check-detail data-detail" value="<?= \Permission\User_Code::ADD_USER ?>"></td>
						<td><input type="checkbox" class="check-detail data-detail" value="<?= \Permission\User_Code::EDIT_USER ?>"></td>
						<td><input type="checkbox" class="check-detail data-detail" value="<?= \Permission\User_Code::DELETE_USER ?>"></td>
						<td></td>
					</tr>
					<tr>
						<td>Customer</td>
						<td><input type="checkbox" class="check-detail data-detail" value="<?= \Permission\Customer_Code::VIEW_CUSTOMER ?>"></td>
						<td><input type="checkbox" class="check-detail data-detail" value="<?= \Permission\Customer_Code::VIEW_CUSTOMER_DETAIL ?>"></td>
						<td><input type="checkbox" class="check-detail data-detail" value="<?= \Permission\Customer_Code::ADD_CUSTOMER ?>"></td>
						<td><input type="checkbox" class="check-detail data-detail" value="<?= \Permission\Customer_Code::EDIT_CUSTOMER ?>"></td>
						<td><input type="checkbox" class="check-detail data-detail" value="<?= \Permission\Customer_Code::DELETE_CUSTOMER ?>"></td>
						<td></td>
					</tr>
					<tr>
						<td>Branch</td>
						<td><input type="checkbox" class="check-detail data-detail" value="<?= \Permission\Branch_Code::VIEW_BRANCH ?>"></td>
						<td></td>
						<td><input type="checkbox" class="check-detail data-detail" value="<?= \Permission\Branch_Code::ADD_BRANCH ?>"></td>
						<td><input type="checkbox" class="check-detail data-detail" value="<?= \Permission\Branch_Code::EDIT_BRANCH ?>"></td>
						<td><input type="checkbox" class="check-detail data-detail" value="<?= \Permission\Branch_Code::DELETE_BRANCH ?>"></td>
						<td></td>
					</tr>
				</table>
			</div>
			<div class="sub-panel header-section">
				<div align="left">
					<input type="checkbox" class="permission-section" id="purchase-permission"> Purchase Section
				</div>
			</div>
			<div class="max-row tbl max user">
				<table>
					<tr class="tableheader">
						<td style="width:345px;">Page</td>
						<td style="width:130px;">View</td>
						<td style="width:130px;">View Detail</td>
						<td style="width:130px;">Add</td>
						<td style="width:130px;">Edit</td>
						<td style="width:130px;">Delete</td>
						<td style="width:320px;">Others</td>
					</tr>
					<tr>
						<td>Purchase Order</td>
						<td><input type="checkbox" class="check-detail purchase-detail encoder-preset" value="<?= \Permission\Purchase_Code::VIEW_PURCHASE ?>"></td>
						<td><input type="checkbox" class="check-detail purchase-detail encoder-preset" value="<?= \Permission\Purchase_Code::VIEW_PURCHASE_DETAIL ?>"></td>
						<td><input type="checkbox" class="check-detail purchase-detail encoder-preset" value="<?= \Permission\Purchase_Code::ADD_PURCHASE ?>"></td>
						<td><input type="checkbox" class="check-detail purchase-detail encoder-preset" value="<?= \Permission\Purchase_Code::EDIT_PURCHASE ?>"></td>
						<td><input type="checkbox" class="check-detail purchase-detail encoder-preset" value="<?= \Permission\Purchase_Code::DELETE_PURCHASE ?>"></td>
						<td><input type="checkbox" class="check-detail purchase-detail encoder-preset" value="<?= \Permission\Purchase_Code::TRANSFER_INCOMPLETE_PO ?>"> Edit and Transfer Incomplete PO</td>
					</tr>
					<tr>
						<td>Purchase Receive</td>
						<td><input type="checkbox" class="check-detail purchase-detail encoder-preset" value="<?= \Permission\PurchaseReceive_Code::VIEW_PURCHASE_RECEIVE ?>"></td>
						<td><input type="checkbox" class="check-detail purchase-detail encoder-preset" value="<?= \Permission\PurchaseReceive_Code::VIEW_PURCHASE_RECEIVE_DETAIL ?>"></td>
						<td><input type="checkbox" class="check-detail purchase-detail encoder-preset" value="<?= \Permission\PurchaseReceive_Code::ADD_PURCHASE_RECEIVE ?>"></td>
						<td><input type="checkbox" class="check-detail purchase-detail encoder-preset" value="<?= \Permission\PurchaseReceive_Code::EDIT_PURCHASE_RECEIVE ?>"></td>
						<td><input type="checkbox" class="check-detail purchase-detail encoder-preset" value="<?= \Permission\PurchaseReceive_Code::DELETE_PURCHASE_RECEIVE ?>"></td>
						<td></td>
					</tr>
					<tr>
						<td>Purchase Return</td>
						<td><input type="checkbox" class="check-detail purchase-detail encoder-preset" value="<?= \Permission\PurchaseReturn_Code::VIEW_PURCHASE_RETURN ?>"></td>
						<td><input type="checkbox" class="check-detail purchase-detail encoder-preset" value="<?= \Permission\PurchaseReturn_Code::VIEW_PURCHASE_RETURN_DETAIL ?>"></td>
						<td><input type="checkbox" class="check-detail purchase-detail encoder-preset" value="<?= \Permission\PurchaseReturn_Code::ADD_PURCHASE_RETURN ?>"></td>
						<td><input type="checkbox" class="check-detail purchase-detail encoder-preset" value="<?= \Permission\PurchaseReturn_Code::EDIT_PURCHASE_RETURN ?>"></td>
						<td><input type="checkbox" class="check-detail purchase-detail encoder-preset" value="<?= \Permission\PurchaseReturn_Code::DELETE_PURCHASE_RETURN ?>"></td>
						<td></td>
					</tr>
				</table>
			</div>
			<div class="sub-panel header-section">
				<div align="left">
					<input type="checkbox" class="permission-section" id="sales-permission"> Sales Section
				</div>
			</div>
			<div class="max-row tbl max user">
				<table>
					<tr class="tableheader">
						<td style="width:345px;">Page</td>
						<td style="width:130px;">View</td>
						<td style="width:130px;">View Detail</td>
						<td style="width:130px;">Add</td>
						<td style="width:130px;">Edit</td>
						<td style="width:130px;">Delete</td>
						<td style="width:320px;">Others</td>
					</tr>
					<tr>
						<td>Sales Reservation</td>
						<td><input type="checkbox" class="check-detail sales-detail salesman-preset encoder-preset" value="<?= \Permission\SalesReservation_Code::VIEW_SALES_RESERVATION ?>"></td>
						<td><input type="checkbox" class="check-detail sales-detail salesman-preset encoder-preset" value="<?= \Permission\SalesReservation_Code::VIEW_SALES_RESERVATION_DETAIL ?>"></td>
						<td><input type="checkbox" class="check-detail sales-detail salesman-preset encoder-preset" value="<?= \Permission\SalesReservation_Code::ADD_SALES_RESERVATION ?>"></td>
						<td><input type="checkbox" class="check-detail sales-detail salesman-preset encoder-preset" value="<?= \Permission\SalesReservation_Code::EDIT_SALES_RESERVATION ?>"></td>
						<td><input type="checkbox" class="check-detail sales-detail salesman-preset encoder-preset" value="<?= \Permission\SalesReservation_Code::DELETE_SALES_RESERVATION ?>"></td>
						<td></td>
					</tr>
					<tr>
						<td>Sales Invoice</td>
						<td><input type="checkbox" class="check-detail sales-detail salesman-preset encoder-preset" value="<?= \Permission\Sales_Code::VIEW_SALES ?>"></td>
						<td><input type="checkbox" class="check-detail sales-detail salesman-preset encoder-preset" value="<?= \Permission\Sales_Code::VIEW_SALES_DETAIL ?>"></td>
						<td><input type="checkbox" class="check-detail sales-detail salesman-preset encoder-preset" value="<?= \Permission\Sales_Code::ADD_SALES ?>"></td>
						<td><input type="checkbox" class="check-detail sales-detail salesman-preset encoder-preset" value="<?= \Permission\Sales_Code::EDIT_SALES ?>"></td>
						<td><input type="checkbox" class="check-detail sales-detail salesman-preset encoder-preset" value="<?= \Permission\Sales_Code::DELETE_SALES ?>"></td>
						<td></td>
					</tr>
				</table>
			</div>
			<div class="sub-panel header-section">
				<div align="left">
					<input type="checkbox" class="permission-section" id="transfer-permission"> Item Transaferring Section
				</div>
			</div>
			<div class="max-row tbl max user">
				<table>
					<tr class="tableheader">
						<td style="width:345px;">Page</td>
						<td style="width:130px;">View</td>
						<td style="width:130px;">View Detail</td>
						<td style="width:130px;">Add</td>
						<td style="width:130px;">Edit</td>
						<td style="width:130px;">Delete</td>
						<td style="width:320px;">Others</td>
					</tr>
					<tr>
						<td>Request Item from Other Branches</td>
						<td><input type="checkbox" class="check-detail transfer-detail encoder-preset" value="<?= \Permission\StockRequestTo_Code::VIEW_STOCKREQUEST ?>"></td>
						<td><input type="checkbox" class="check-detail transfer-detail encoder-preset" value="<?= \Permission\StockRequestTo_Code::VIEW_STOCKREQUEST_DETAIL ?>"></td>
						<td><input type="checkbox" class="check-detail transfer-detail encoder-preset" value="<?= \Permission\StockRequestTo_Code::ADD_STOCKREQUEST ?>"></td>
						<td><input type="checkbox" class="check-detail transfer-detail encoder-preset" value="<?= \Permission\StockRequestTo_Code::EDIT_STOCKREQUEST ?>"></td>
						<td><input type="checkbox" class="check-detail transfer-detail encoder-preset" value="<?= \Permission\StockRequestTo_Code::DELETE_STOCKREQUEST ?>"></td>
						<td><input type="checkbox" class="check-detail transfer-detail encoder-preset" value="<?= \Permission\StockRequestTo_Code::EDIT_INCOMPLETE_TRANSACTION ?>"> Edit Incomplete Transaction</td>
					</tr>
					<tr>
						<td>Item Requested by Other Branches</td>
						<td><input type="checkbox" class="check-detail transfer-detail encoder-preset" value="<?= \Permission\StockRequestFrom_Code::VIEW_STOCKREQUEST ?>"></td>
						<td><input type="checkbox" class="check-detail transfer-detail encoder-preset" value="<?= \Permission\StockRequestFrom_Code::VIEW_STOCKREQUEST_DETAIL ?>"></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td>Item Delivery</td>
						<td><input type="checkbox" class="check-detail transfer-detail encoder-preset" value="<?= \Permission\StockDelivery_Code::VIEW_STOCK_DELIVERY ?>"></td>
						<td><input type="checkbox" class="check-detail transfer-detail encoder-preset" value="<?= \Permission\StockDelivery_Code::VIEW_STOCK_DELIVERY_DETAIL ?>"></td>
						<td><input type="checkbox" class="check-detail transfer-detail encoder-preset" value="<?= \Permission\StockDelivery_Code::ADD_STOCK_DELIVERY ?>"></td>
						<td><input type="checkbox" class="check-detail transfer-detail encoder-preset" value="<?= \Permission\StockDelivery_Code::EDIT_STOCK_DELIVERY ?>"></td>
						<td><input type="checkbox" class="check-detail transfer-detail encoder-preset" value="<?= \Permission\StockDelivery_Code::DELETE_STOCK_DELIVERY ?>"></td>
						<td><input type="checkbox" class="check-detail transfer-detail encoder-preset" value="<?= \Permission\StockDelivery_Code::EDIT_INCOMPLETE_TRANSACTION ?>"> Edit Incomplete Transaction</td>
					</tr>
					<tr>
						<td>Item Receive</td>
						<td><input type="checkbox" class="check-detail transfer-detail encoder-preset" value="<?= \Permission\StockReceive_Code::VIEW_STOCK_RECEIVE ?>"></td>
						<td><input type="checkbox" class="check-detail transfer-detail encoder-preset" value="<?= \Permission\StockReceive_Code::VIEW_STOCK_RECEIVE_DETAIL ?>"></td>
						<td></td>
						<td><input type="checkbox" class="check-detail transfer-detail encoder-preset" value="<?= \Permission\StockReceive_Code::EDIT_STOCK_RECEIVE ?>"></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td>Customer Receive</td>
						<td><input type="checkbox" class="check-detail transfer-detail encoder-preset" value="<?= \Permission\CustomerReceive_Code::VIEW_CUSTOMER_RECEIVE ?>"></td>
						<td><input type="checkbox" class="check-detail transfer-detail encoder-preset" value="<?= \Permission\CustomerReceive_Code::VIEW_CUSTOMER_RECEIVE_DETAIL ?>"></td>
						<td></td>
						<td><input type="checkbox" class="check-detail transfer-detail encoder-preset" value="<?= \Permission\CustomerReceive_Code::EDIT_CUSTOMER_RECEIVE ?>"></td>
						<td></td>
						<td><input type="checkbox" class="check-detail transfer-detail encoder-preset" value="<?= \Permission\CustomerReceive_Code::TRANSFER_TO_RETURN ?>"> Transfer to Customer Return</td>
					</tr>
					<tr>
						<td>Customer Return</td>
						<td><input type="checkbox" class="check-detail transfer-detail encoder-preset" value="<?= \Permission\CustomerReturn_Code::VIEW_CUSTOMER_RETURN ?>"></td>
						<td><input type="checkbox" class="check-detail transfer-detail encoder-preset" value="<?= \Permission\CustomerReturn_Code::VIEW_CUSTOMER_RETURN_DETAIL ?>"></td>
						<td><input type="checkbox" class="check-detail transfer-detail encoder-preset" value="<?= \Permission\CustomerReturn_Code::ADD_CUSTOMER_RETURN ?>"></td>
						<td><input type="checkbox" class="check-detail transfer-detail encoder-preset" value="<?= \Permission\CustomerReturn_Code::EDIT_CUSTOMER_RETURN ?>"></td>
						<td><input type="checkbox" class="check-detail transfer-detail encoder-preset" value="<?= \Permission\CustomerReturn_Code::DELETE_CUSTOMER_RETURN ?>"></td>
						<td></td>
					</tr>
				</table>
			</div>
			<div class="sub-panel header-section">
				<div align="left">
					<input type="checkbox" class="permission-section" id="return-permission"> Damage Section
				</div>
			</div>
			<div class="max-row tbl max user">
				<table>
					<tr class="tableheader">
						<td style="width:345px;">Page</td>
						<td style="width:130px;">View</td>
						<td style="width:130px;">View Detail</td>
						<td style="width:130px;">Add</td>
						<td style="width:130px;">Edit</td>
						<td style="width:130px;">Delete</td>
						<td style="width:320px;">Others</td>
					</tr>
					<tr>
						<td>Damage</td>
						<td><input type="checkbox" class="check-detail return-detail encoder-preset" value="<?= \Permission\Damage_Code::VIEW_DAMAGE ?>"></td>
						<td><input type="checkbox" class="check-detail return-detail encoder-preset" value="<?= \Permission\Damage_Code::VIEW_DAMAGE_DETAIL ?>"></td>
						<td><input type="checkbox" class="check-detail return-detail encoder-preset" value="<?= \Permission\Damage_Code::ADD_DAMAGE ?>"></td>
						<td><input type="checkbox" class="check-detail return-detail encoder-preset" value="<?= \Permission\Damage_Code::EDIT_DAMAGE ?>"></td>
						<td><input type="checkbox" class="check-detail return-detail encoder-preset" value="<?= \Permission\Damage_Code::DELETE_DAMAGE ?>"></td>
						<td></td>
					</tr>
				</table>
			</div>
			<div class="sub-panel header-section">
				<div align="left">
					<input type="checkbox" class="permission-section" id="pickup-permission"> Pick-Up Section
				</div>
			</div>
			<div class="max-row tbl max user">
				<table>
					<tr class="tableheader">
						<td style="width:345px;">Page</td>
						<td style="width:130px;">View</td>
						<td style="width:130px;">View Detail</td>
						<td style="width:130px;">Add</td>
						<td style="width:130px;">Edit</td>
						<td style="width:130px;">Delete</td>
						<td style="width:320px;">Others</td>
					</tr>
					<tr>
						<td>Pick-Up Assortment</td>
						<td><input type="checkbox" class="check-detail pickup-detail encoder-preset" value="<?= \Permission\Assortment_Code::VIEW_ASSORTMENT ?>"></td>
						<td><input type="checkbox" class="check-detail pickup-detail encoder-preset" value="<?= \Permission\Assortment_Code::VIEW_ASSORTMENT_DETAIL ?>"></td>
						<td><input type="checkbox" class="check-detail pickup-detail encoder-preset" value="<?= \Permission\Assortment_Code::ADD_ASSORTMENT ?>"></td>
						<td><input type="checkbox" class="check-detail pickup-detail encoder-preset" value="<?= \Permission\Assortment_Code::EDIT_ASSORTMENT ?>"></td>
						<td><input type="checkbox" class="check-detail pickup-detail encoder-preset" value="<?= \Permission\Assortment_Code::DELETE_ASSORTMENT ?>"></td>
						<td><input type="checkbox" class="check-detail pickup-detail encoder-preset" value="<?= \Permission\Assortment_Code::EDIT_INCOMPLETE_TRANSACTION ?>"> Edit Incomplete Transaction</td>
					</tr>
					<tr>
						<td>Warehouse Release</td>
						<td><input type="checkbox" class="check-detail pickup-detail encoder-preset" value="<?= \Permission\Release_Code::VIEW_RELEASE ?>"></td>
						<td><input type="checkbox" class="check-detail pickup-detail encoder-preset" value="<?= \Permission\Release_Code::VIEW_RELEASE_DETAIL ?>"></td>
						<td><input type="checkbox" class="check-detail pickup-detail encoder-preset" value="<?= \Permission\Release_Code::ADD_RELEASE ?>"></td>
						<td><input type="checkbox" class="check-detail pickup-detail encoder-preset" value="<?= \Permission\Release_Code::EDIT_RELEASE ?>"></td>
						<td><input type="checkbox" class="check-detail pickup-detail encoder-preset" value="<?= \Permission\Release_Code::DELETE_RELEASE ?>"></td>
						<td></td>
					</tr>
					<tr>
						<td>Pick-Up Summary</td>
						<td></td>
						<td></td>
						<td><input type="checkbox" class="check-detail pickup-detail encoder-preset" value="<?= \Permission\PickUp_Code::GENERATE_SUMMARY ?>"></td>
						<td></td>
						<td></td>
						<td><input type="checkbox" class="check-detail pickup-detail encoder-preset" value="<?= \Permission\PickUp_Code::PRINT_SUMMARY ?>"> Print Summary</td>
					</tr>
				</table>
			</div>
			<div class="sub-panel header-section">
				<div align="left">
					<input type="checkbox" class="permission-section" id="other-permission"> Others Section
				</div>
			</div>
			<div class="max-row tbl max user">
				<table>
					<tr class="tableheader">
						<td style="width:345px;">Page</td>
						<td style="width:130px;">View</td>
						<td style="width:130px;">View Detail</td>
						<td style="width:130px;">Add</td>
						<td style="width:130px;">Edit</td>
						<td style="width:130px;">Delete</td>
						<td style="width:320px;">Others</td>
					</tr>
					<tr>
						<td>Inventory Adjust</td>
						<td><input type="checkbox" class="check-detail other-detail encoder-preset" value="<?= \Permission\InventoryAdjust_Code::VIEW_INVENTORY_ADJUST ?>"></td>
						<td><input type="checkbox" class="check-detail other-detail encoder-preset" value="<?= \Permission\InventoryAdjust_Code::VIEW_INVENTORY_ADJUST_DETAIL ?>"></td>
						<td><input type="checkbox" class="check-detail other-detail encoder-preset" value="<?= \Permission\InventoryAdjust_Code::ADD_INVENTORY_ADJUST ?>"></td>
						<td><input type="checkbox" class="check-detail other-detail encoder-preset" value="<?= \Permission\InventoryAdjust_Code::EDIT_INVENTORY_ADJUST ?>"></td>
						<td><input type="checkbox" class="check-detail other-detail encoder-preset" value="<?= \Permission\InventoryAdjust_Code::DELETE_INVENTORY_ADJUST ?>"></td>
						<td></td>
					</tr>
					<tr>
						<td>Pending Inventory Adjust</td>
						<td><input type="checkbox" class="check-detail other-detail" value="<?= \Permission\PendingAdjust_Code::VIEW_PENDING_ADJUST ?>"></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td>
							<input type="checkbox" class="check-detail other-detail" value="<?= \Permission\PendingAdjust_Code::ALLOW_TO_APPROVE_AND_DECLINE ?>"> Allow to Approve / Decline<br/>
							<input type="checkbox" class="check-detail other-detail" value="<?= \Permission\PendingAdjust_Code::AUTO_APPROVE ?>"> Automatically approve request
						</td>
					</tr>
				</table>
			</div>
			<div class="sub-panel header-section">
				<div align="left">
					<input type="checkbox" class="permission-section" id="reports-permission"> Reports Section
				</div>
			</div>
			<div class="max-row tbl max user">
				<table>
					<tr class="tableheader">
						<td style="width:345px;">Page</td>
						<td style="width:130px;">View</td>
						<td style="width:130px;">View Detail</td>
						<td style="width:130px;">Add</td>
						<td style="width:130px;">Edit</td>
						<td style="width:130px;">Delete</td>
						<td style="width:320px;">Others</td>
					</tr>
					<tr>
						<td>Product Inventory Warning</td>
						<td><input type="checkbox" class="check-detail reports-detail" value="<?= \Permission\SystemReport_Code::VIEW_PRODUCT_WARNING ?>"></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td>Product Branch Inventory</td>
						<td><input type="checkbox" class="check-detail reports-detail encoder-preset" value="<?= \Permission\SystemReport_Code::VIEW_BRANCH_INVENTORY ?>"></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td>Product Transaction Summary</td>
						<td><input type="checkbox" class="check-detail reports-detail" value="<?= \Permission\SystemReport_Code::VIEW_TRANSACTION_SUMMARY ?>"></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td>Sales Report</td>
						<td><input type="checkbox" class="check-detail reports-detail" value="<?= \Permission\SystemReport_Code::VIEW_SALES_REPORT ?>"></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td>Sales Book Report</td>
						<td><input type="checkbox" class="check-detail reports-detail" value="<?= \Permission\SystemReport_Code::VIEW_BOOK_REPORT ?>"></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
				</table>
			</div>
		</div>
	</div>
</div>
