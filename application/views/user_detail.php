<div class="main-content pull-right">
	<div class="breadcrumbs-panel">
		<ol class="breadcrumb">
			<li><a href="<?= base_url() ?>controlpanel">Home</a></li>
			<li><a href="<?= base_url() ?>user/list">User List</a></li>
			<li class="active"><a href="<?= base_url() ?>user/add">User Information</a></li>
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
								<td><input type="text" class="form-control" id="contact"></td>
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
	<div class="content-form" id="show-info" style="display:none;">
		<div class="form-header">User Permissions</div>
		<div class="form-body">
			<div class="max-row">
				<div class="tbl-checkbtn">
					<input type="checkbox">
					<span>Admin</span>
				</div>
			</div>
			<div class="max-row">
				<h2>Data Section</h2>
			</div>
			<div class="max-row tbl max user">
				<table>
					<tr class="tableheader">
						<td style="width:345px;">Page</td>
						<td style="width:100px;">View</td>
						<td style="width:100px;">View Detail</td>
						<td style="width:100px;">Add</td>
						<td style="width:100px;">Edit</td>
						<td style="width:100px;">Delete</td>
						<td style="width:320px;">Others</td>
					</tr>
					<tr>
						<td>Product</td>
						<td><input type="checkbox" class="check-detail" value="101"></td>
						<td></td>
						<td><input type="checkbox" class="check-detail" value="103"></td>
						<td><input type="checkbox" class="check-detail" value="104"></td>
						<td><input type="checkbox" class="check-detail" value="101"></td>
						<td></td>
					</tr>
					<tr>
						<td>Material Type</td>
						<td><input type="checkbox" class="check-detail" value="101"></td>
						<td></td>
						<td><input type="checkbox" class="check-detail" value="103"></td>
						<td><input type="checkbox" class="check-detail" value="104"></td>
						<td><input type="checkbox" class="check-detail" value="101"></td>
						<td></td>
					</tr>
					<tr>
						<td>Sub Grouping</td>
						<td><input type="checkbox" class="check-detail" value="101"></td>
						<td></td>
						<td><input type="checkbox" class="check-detail" value="103"></td>
						<td><input type="checkbox" class="check-detail" value="104"></td>
						<td><input type="checkbox" class="check-detail" value="101"></td>
						<td></td>
					</tr>
					<tr>
						<td>User</td>
						<td><input type="checkbox" class="check-detail" value="101"></td>
						<td><input type="checkbox" class="check-detail" value="101"></td>
						<td><input type="checkbox" class="check-detail" value="103"></td>
						<td><input type="checkbox" class="check-detail" value="104"></td>
						<td><input type="checkbox" class="check-detail" value="101"></td>
						<td></td>
					</tr>
					<tr>
						<td>Branch</td>
						<td><input type="checkbox" class="check-detail" value="101"></td>
						<td></td>
						<td><input type="checkbox" class="check-detail" value="103"></td>
						<td><input type="checkbox" class="check-detail" value="104"></td>
						<td><input type="checkbox" class="check-detail" value="101"></td>
						<td></td>
					</tr>
				</table>
			</div>
			<div class="max-row">
				<h2>Purchase Section</h2>
			</div>
			<div class="max-row tbl max user">
				<table>
					<tr class="tableheader">
						<td style="width:345px;">Page</td>
						<td style="width:100px;">View</td>
						<td style="width:100px;">View Detail</td>
						<td style="width:100px;">Add</td>
						<td style="width:100px;">Edit</td>
						<td style="width:100px;">Delete</td>
						<td style="width:320px;">Others</td>
					</tr>
					<tr>
						<td>Purchase Order</td>
						<td><input type="checkbox" class="check-detail" value="101"></td>
						<td><input type="checkbox" class="check-detail" value="101"></td>
						<td><input type="checkbox" class="check-detail" value="103"></td>
						<td><input type="checkbox" class="check-detail" value="104"></td>
						<td><input type="checkbox" class="check-detail" value="101"></td>
						<td></td>
					</tr>
					<tr>
						<td>Purchase Receive</td>
						<td><input type="checkbox" class="check-detail" value="101"></td>
						<td><input type="checkbox" class="check-detail" value="101"></td>
						<td><input type="checkbox" class="check-detail" value="103"></td>
						<td><input type="checkbox" class="check-detail" value="104"></td>
						<td><input type="checkbox" class="check-detail" value="101"></td>
						<td></td>
					</tr>
					<tr>
						<td>Damage</td>
						<td><input type="checkbox" class="check-detail" value="101"></td>
						<td><input type="checkbox" class="check-detail" value="101"></td>
						<td><input type="checkbox" class="check-detail" value="103"></td>
						<td><input type="checkbox" class="check-detail" value="104"></td>
						<td><input type="checkbox" class="check-detail" value="101"></td>
						<td></td>
					</tr>
					<tr>
						<td>Return</td>
						<td><input type="checkbox" class="check-detail" value="101"></td>
						<td><input type="checkbox" class="check-detail" value="101"></td>
						<td><input type="checkbox" class="check-detail" value="103"></td>
						<td><input type="checkbox" class="check-detail" value="104"></td>
						<td><input type="checkbox" class="check-detail" value="101"></td>
						<td></td>
					</tr>
					<tr>
						<td>Purchase Return</td>
						<td><input type="checkbox" class="check-detail" value="101"></td>
						<td><input type="checkbox" class="check-detail" value="101"></td>
						<td><input type="checkbox" class="check-detail" value="103"></td>
						<td><input type="checkbox" class="check-detail" value="104"></td>
						<td><input type="checkbox" class="check-detail" value="101"></td>
						<td></td>
					</tr>
				</table>
			</div>
			<div class="max-row">
				<h2>Stock Transferring Section</h2>
			</div>
			<div class="max-row tbl max user">
				<table>
					<tr class="tableheader">
						<td style="width:345px;">Page</td>
						<td style="width:100px;">View</td>
						<td style="width:100px;">View Detail</td>
						<td style="width:100px;">Add</td>
						<td style="width:100px;">Edit</td>
						<td style="width:100px;">Delete</td>
						<td style="width:320px;">Others</td>
					</tr>
					<tr>
						<td>Stock Delivery</td>
						<td><input type="checkbox" class="check-detail" value="101"></td>
						<td><input type="checkbox" class="check-detail" value="101"></td>
						<td><input type="checkbox" class="check-detail" value="103"></td>
						<td><input type="checkbox" class="check-detail" value="104"></td>
						<td><input type="checkbox" class="check-detail" value="101"></td>
						<td></td>
					</tr>
					<tr>
						<td>Stock Receive</td>
						<td><input type="checkbox" class="check-detail" value="101"></td>
						<td><input type="checkbox" class="check-detail" value="101"></td>
						<td></td>
						<td><input type="checkbox" class="check-detail" value="104"></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td>Customer Receive</td>
						<td><input type="checkbox" class="check-detail" value="101"></td>
						<td><input type="checkbox" class="check-detail" value="101"></td>
						<td></td>
						<td><input type="checkbox" class="check-detail" value="104"></td>
						<td></td>
						<td></td>
					</tr>
				</table>
			</div>
			<div class="max-row">
				<h2>Others Section</h2>
			</div>
			<div class="max-row tbl max user">
				<table>
					<tr class="tableheader">
						<td style="width:345px;">Page</td>
						<td style="width:100px;">View</td>
						<td style="width:100px;">View Detail</td>
						<td style="width:100px;">Add</td>
						<td style="width:100px;">Edit</td>
						<td style="width:100px;">Delete</td>
						<td style="width:320px;">Others</td>
					</tr>
					<tr>
						<td>Inventory Adjust</td>
						<td><input type="checkbox" class="check-detail" value="101"></td>
						<td><input type="checkbox" class="check-detail" value="101"></td>
						<td><input type="checkbox" class="check-detail" value="103"></td>
						<td><input type="checkbox" class="check-detail" value="104"></td>
						<td><input type="checkbox" class="check-detail" value="101"></td>
						<td></td>
					</tr>
					<tr>
						<td>Pending Inventory Adjust</td>
						<td><input type="checkbox" class="check-detail" value="101"></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td><input type="checkbox" class="check-detail" value="104">Allow to Approve / Decline</td>
					</tr>
					<tr>
						<td>Warehouse Release</td>
						<td><input type="checkbox" class="check-detail" value="101"></td>
						<td><input type="checkbox" class="check-detail" value="101"></td>
						<td><input type="checkbox" class="check-detail" value="104"></td>
						<td><input type="checkbox" class="check-detail" value="104"></td>
						<td><input type="checkbox" class="check-detail" value="104"></td>
						<td></td>
					</tr>
				</table>
			</div>
			<div class="max-row">
				<h2>Reports Section</h2>
			</div>
			<div class="max-row tbl max user">
				<table>
					<tr class="tableheader">
						<td style="width:345px;">Page</td>
						<td style="width:100px;">View</td>
						<td style="width:100px;">View Detail</td>
						<td style="width:100px;">Add</td>
						<td style="width:100px;">Edit</td>
						<td style="width:100px;">Delete</td>
						<td style="width:320px;">Others</td>
					</tr>
					<tr>
						<td>Product Inventory Warning</td>
						<td><input type="checkbox" class="check-detail" value="101"></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td>Product Branch Inventory</td>
						<td><input type="checkbox" class="check-detail" value="101"></td>
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
