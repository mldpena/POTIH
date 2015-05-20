<div class="main-content pull-right">
	<div class="breadcrumbs-panel">
		<ol class="breadcrumb">
			<li><a href="<?= base_url() ?>controlpanel">Home</a></li>
			<li><a href="<?= base_url() ?>user/list">User List</a></li>
			<li class="active"><a href="<?= base_url() ?>user/detail">User Information</a></li>
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
								<td colspan="3"><input type="text" class="form-control" placeholder="Select a Branch"></td>
							</tr>
							<tr>
								<td>User Code:</td>
								<td><input type="text" class="form-control"></td>
								<td>Username:</td>
								<td><input type="text" class="form-control"></td>
							</tr>
							<tr>
								<td>Full Name:</td>
								<td><input type="text" class="form-control"></td>
								<td>Password:</td>
								<td><input type="password" class="form-control"></td>
							</tr>
							<tr>
								<td>Status:</td>
								<td>
									<div class="tbl-checkbtn">
										<input type="checkbox">
										<span>Active</span>
									</div>
								</td>
								<td>Contact No.:</td>
								<td><input type="text" class="form-control"></td>
							</tr>
						</table>
					</div>
				</div>
			</div>
			<div class="max-row" align="right">
				<input type="button" class="btn btn-success" value='Save'>
				<input type="button" class="btn btn-info" value='Show Advanced Info' id="show-info-btn">
			</div>
		</div>
	</div>

	<div class="content-form" id="show-info">
		<div class="form-header">User Permissions</div>
		<div class="form-body">
			<div class="max-row">
				<div class="tbl-checkbtn">
					<input type="checkbox">
					<span>Admin</span>
				</div>
			</div>
			<div class="max-row tbl max user">
				<table>
					<tr class="tableheader">
						<td style="width:345px;">Page</td>
						<td style="width:100px;">View</td>
						<td style="width:100px;">Add</td>
						<td style="width:100px;">Edit</td>
						<td style="width:100px;">Delete</td>
						<td style="width:320px;">Others</td>
					</tr>
						<tr>
							<td>Branch Settings</td>
							<td><input type="checkbox" class="check-detail" value="101"></td>
							<td><input type="checkbox" class="check-detail" value="102"></td>
							<td><input type="checkbox" class="check-detail" value="103"></td>
							<td><input type="checkbox" class="check-detail" value="104"></td>
							<td></td>
						</tr>
						<tr>
							<td>User Settings</td>
							<td><input type="checkbox" class="check-detail" value="110"></td>
							<td><input type="checkbox" class="check-detail" value="111"></td>
							<td><input type="checkbox" class="check-detail" value="112"></td>
							<td><input type="checkbox" class="check-detail" value="113"></td>
							<td></td>
						</tr>
						<tr>
							<td>Shipper Settings</td>
							<td><input type="checkbox" class="check-detail" value="119"></td>
							<td><input type="checkbox" class="check-detail" value="120"></td>
							<td><input type="checkbox" class="check-detail" value="121"></td>
							<td><input type="checkbox" class="check-detail" value="122"></td>
							<td></td>
						</tr>
						<tr>
							<td>Segregation Settings</td>
							<td><input type="checkbox" class="check-detail" value="128"></td>
							<td><input type="checkbox" class="check-detail" value="129"></td>
							<td><input type="checkbox" class="check-detail" value="130"></td>
							<td><input type="checkbox" class="check-detail" value="131"></td>
							<td></td>
						</tr>
						<tr>
							<td>Segregation Detail Settings</td>
							<td><input type="checkbox" class="check-detail" value="132"></td>
							<td><input type="checkbox" class="check-detail" value="133"></td>
							<td><input type="checkbox" class="check-detail" value="134"></td>
							<td><input type="checkbox" class="check-detail" value="135"></td>
							<td></td>
						</tr>
						<tr>
							<td>Email Settings</td>
							<td><input type="checkbox" class="check-detail" value="137"></td>
							<td></td>
							<td><input type="checkbox" class="check-detail" value="138"></td>
							<td></td>
							<td></td>
						</tr>
						<tr>
							<td>SMS Settings</td>
							<td><input type="checkbox" class="check-detail" value="144"></td>
							<td></td>
							<td><input type="checkbox" class="check-detail" value="145"></td>
							<td></td>
							<td></td>
						</tr>
						<tr>
							<td>Rate Adjust Settings</td>
							<td><input type="checkbox" class="check-detail" value="151"></td>
							<td><input type="checkbox" class="check-detail" value="152"></td>
							<td><input type="checkbox" class="check-detail" value="153"></td>
							<td><input type="checkbox" class="check-detail" value="154"></td>
							<td></td>
						</tr>
						<tr>
							<td>Municipal Settings</td>
							<td><input type="checkbox" class="check-detail" value="160"></td>
							<td><input type="checkbox" class="check-detail" value="161"></td>
							<td><input type="checkbox" class="check-detail" value="162"></td>
							<td><input type="checkbox" class="check-detail" value="163"></td>
							<td></td>
						</tr>
						<tr>
							<td>Waybill List</td>
							<td><input type="checkbox" class="check-detail" value="169"></td>
							<td><input type="checkbox" class="check-detail" value="170"></td>
							<td><input type="checkbox" class="check-detail" value="171"></td>
							<td><input type="checkbox" class="check-detail" value="172"></td>
							<td>
								<div class="user-check-group">
									<div class="input"><input type="checkbox" class="check-detail" value="173"></div><div class="text">Import Excel</div>
								</div>
								<div class="user-check-group">
									<div class="input"><input type="checkbox" class="check-detail" value="174"></div><div class="text">Export Excel</div>
								</div>
								<div class="user-check-group">
									<div class="input"><input type="checkbox" class="check-detail" value="175"></div><div class="text">Pull Out Waybill</div>
								</div>
							</td>
						</tr>
						<tr>
							<td>Waybill Logs</td>
							<td><input type="checkbox" class="check-detail" value="181"></td>
							<td><input type="checkbox" class="check-detail" value="182"></td>
							<td><input type="checkbox" class="check-detail" value="183"></td>
							<td><input type="checkbox" class="check-detail" value="184"></td>
							<td></td>
						</tr>
						<tr>
							<td>Waybill Track</td>
							<td><input type="checkbox" class="check-detail" value="191"></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
						</tr>
						<tr>
							<td>Segregation</td>
							<td><input type="checkbox" class="check-detail" value="192"></td>
							<td><input type="checkbox" class="check-detail" value="193"></td>
							<td><input type="checkbox" class="check-detail" value="194"></td>
							<td><input type="checkbox" class="check-detail" value="195"></td>
							<td>
								<div class="user-check-group">
									<div class="input"><input type="checkbox" class="check-detail" value="196"></div><div class="text">Auto Segregate</div>
								</div>
								<div class="user-check-group">
									<div class="input"><input type="checkbox" class="check-detail" value="197"></div><div class="text">Auto Dispatch</div>
								</div>
							</td>
						</tr>
						<tr>
							<td>Dispatch</td>
							<td><input type="checkbox" class="check-detail" value="203"></td>
							<td><input type="checkbox" class="check-detail" value="204"></td>
							<td><input type="checkbox" class="check-detail" value="205"></td>
							<td><input type="checkbox" class="check-detail" value="206"></td>
							<td>
								<div class="user-check-group">
									<div class="input"><input type="checkbox" class="check-detail" value="207"></div><div class="text">Print Tracking Sheet</div>
								</div>
								<div class="user-check-group">
									<div class="input"><input type="checkbox" class="check-detail" value="208"></div><div class="text">Print Manifest</div>
								</div>
								<div class="user-check-group">
									<div class="input"><input type="checkbox" class="check-detail" value="209"></div><div class="text">Print Run Sheet</div>
								</div>
								<div class="user-check-group">
									<div class="input"><input type="checkbox" class="check-detail" value="210"></div><div class="text">Send SMS</div>
								</div>
							</td>
						</tr>
						<tr>
							<td>Delivery Warning</td>
							<td><input type="checkbox" class="check-detail" value="228"></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
						</tr>
						<tr>
							<td>Billing Statement</td>
							<td><input type="checkbox" class="check-detail" value="216"></td>
							<td></td>
							<td></td>
							<td></td>
							<td>
								<div class="user-check-group">
									<div class="input"><input type="checkbox" class="check-detail" value="217"></div><div class="text">Export Excel</div>
								</div>
								<div class="user-check-group">
									<div class="input"><input type="checkbox" class="check-detail" value="218"></div><div class="text">Mail Shipper</div>
								</div>
								<div class="user-check-group">
									<div class="input"><input type="checkbox" class="check-detail" value="223"></div><div class="text">Export PDF</div>
								</div>
							</td>
						</tr>
						<tr>
							<td>Statement of Account</td>
							<td><input type="checkbox" class="check-detail" value="219"></td>
							<td></td>
							<td></td>
							<td></td>
							<td>
								<div class="user-check-group">
									<div class="input"><input type="checkbox" class="check-detail" value="220"></div><div class="text">Export PDF</div>
								</div>
								<div class="user-check-group">
									<div class="input"><input type="checkbox" class="check-detail" value="221"></div><div class="text">Mail Shipper</div>
								</div>
								<div class="user-check-group">
									<div class="input"><input type="checkbox" class="check-detail" value="222"></div><div class="text">Add On Amount</div>
								</div>
							</td>
						</tr>
				</table>
			</div>
		</div>
	</div>
</div>
