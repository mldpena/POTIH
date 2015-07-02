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
	<div class="content-form" id="show-info" style="display:none;">
		<div class="form-header">User Permissions</div>
		<div class="form-body">
			<div class="max-row">
				<div class="tbl-checkbtn">
					<input type="checkbox" class="preset" id="admin-permission">
					<span>Admin</span>
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
						<td><input type="checkbox" class="check-detail data-detail" value="101"></td>
						<td></td>
						<td><input type="checkbox" class="check-detail data-detail" value="102"></td>
						<td><input type="checkbox" class="check-detail data-detail" value="103"></td>
						<td><input type="checkbox" class="check-detail data-detail" value="104"></td>
						<td></td>
					</tr>
					<tr>
						<td>Material Type</td>
						<td><input type="checkbox" class="check-detail data-detail" value="105"></td>
						<td></td>
						<td><input type="checkbox" class="check-detail data-detail" value="106"></td>
						<td><input type="checkbox" class="check-detail data-detail" value="107"></td>
						<td><input type="checkbox" class="check-detail data-detail" value="108"></td>
						<td></td>
					</tr>
					<tr>
						<td>Sub Grouping</td>
						<td><input type="checkbox" class="check-detail data-detail" value="109"></td>
						<td></td>
						<td><input type="checkbox" class="check-detail data-detail" value="110"></td>
						<td><input type="checkbox" class="check-detail data-detail" value="111"></td>
						<td><input type="checkbox" class="check-detail data-detail" value="112"></td>
						<td></td>
					</tr>
					<tr>
						<td>User</td>
						<td><input type="checkbox" class="check-detail data-detail" value="113"></td>
						<td><input type="checkbox" class="check-detail data-detail" value="114"></td>
						<td><input type="checkbox" class="check-detail data-detail" value="115"></td>
						<td><input type="checkbox" class="check-detail data-detail" value="116"></td>
						<td><input type="checkbox" class="check-detail data-detail" value="117"></td>
						<td></td>
					</tr>
					<tr>
						<td>Branch</td>
						<td><input type="checkbox" class="check-detail data-detail" value="118"></td>
						<td></td>
						<td><input type="checkbox" class="check-detail data-detail" value="119"></td>
						<td><input type="checkbox" class="check-detail data-detail" value="120"></td>
						<td><input type="checkbox" class="check-detail data-detail" value="121"></td>
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
						<td><input type="checkbox" class="check-detail purchase-detail" value="131"></td>
						<td><input type="checkbox" class="check-detail purchase-detail" value="132"></td>
						<td><input type="checkbox" class="check-detail purchase-detail" value="133"></td>
						<td><input type="checkbox" class="check-detail purchase-detail" value="134"></td>
						<td><input type="checkbox" class="check-detail purchase-detail" value="135"></td>
						<td></td>
					</tr>
					<tr>
						<td>Purchase Receive</td>
						<td><input type="checkbox" class="check-detail purchase-detail" value="136"></td>
						<td><input type="checkbox" class="check-detail purchase-detail" value="136"></td>
						<td><input type="checkbox" class="check-detail purchase-detail" value="138"></td>
						<td><input type="checkbox" class="check-detail purchase-detail" value="139"></td>
						<td><input type="checkbox" class="check-detail purchase-detail" value="140"></td>
						<td></td>
					</tr>
					<tr>
						<td>Customer Return</td>
						<td><input type="checkbox" class="check-detail purchase-detail" value="141"></td>
						<td><input type="checkbox" class="check-detail purchase-detail" value="142"></td>
						<td><input type="checkbox" class="check-detail purchase-detail" value="144"></td>
						<td><input type="checkbox" class="check-detail purchase-detail" value="145"></td>
						<td><input type="checkbox" class="check-detail purchase-detail" value="146"></td>
						<td></td>
					</tr>
				</table>
			</div>
			<div class="sub-panel header-section">
				<div align="left">
					<input type="checkbox" class="permission-section" id="return-permission"> Returns Section
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
						<td><input type="checkbox" class="check-detail return-detail" value="156"></td>
						<td><input type="checkbox" class="check-detail return-detail" value="157"></td>
						<td><input type="checkbox" class="check-detail return-detail" value="158"></td>
						<td><input type="checkbox" class="check-detail return-detail" value="159"></td>
						<td><input type="checkbox" class="check-detail return-detail" value="160"></td>
						<td></td>
					</tr>
					<tr>
						<td>Purchase Return</td>
						<td><input type="checkbox" class="check-detail return-detail" value="161"></td>
						<td><input type="checkbox" class="check-detail return-detail" value="162"></td>
						<td><input type="checkbox" class="check-detail return-detail" value="163"></td>
						<td><input type="checkbox" class="check-detail return-detail" value="164"></td>
						<td><input type="checkbox" class="check-detail return-detail" value="165"></td>
						<td></td>
					</tr>
				</table>
			</div>
			<div class="sub-panel header-section">
				<div align="left">
					<input type="checkbox" class="permission-section" id="transfer-permission"> Stock Transaferring Section
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
						<td>Stock Delivery</td>
						<td><input type="checkbox" class="check-detail transfer-detail" value="171"></td>
						<td><input type="checkbox" class="check-detail transfer-detail" value="172"></td>
						<td><input type="checkbox" class="check-detail transfer-detail" value="173"></td>
						<td><input type="checkbox" class="check-detail transfer-detail" value="174"></td>
						<td><input type="checkbox" class="check-detail transfer-detail" value="175"></td>
						<td></td>
					</tr>
					<tr>
						<td>Stock Receive</td>
						<td><input type="checkbox" class="check-detail transfer-detail" value="176"></td>
						<td><input type="checkbox" class="check-detail transfer-detail" value="177"></td>
						<td></td>
						<td><input type="checkbox" class="check-detail transfer-detail" value="178"></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td>Customer Receive</td>
						<td><input type="checkbox" class="check-detail transfer-detail" value="179"></td>
						<td><input type="checkbox" class="check-detail transfer-detail" value="180"></td>
						<td></td>
						<td><input type="checkbox" class="check-detail transfer-detail" value="181"></td>
						<td></td>
						<td></td>
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
						<td><input type="checkbox" class="check-detail other-detail" value="191"></td>
						<td><input type="checkbox" class="check-detail other-detail" value="192"></td>
						<td><input type="checkbox" class="check-detail other-detail" value="193"></td>
						<td><input type="checkbox" class="check-detail other-detail" value="194"></td>
						<td><input type="checkbox" class="check-detail other-detail" value="195"></td>
						<td></td>
					</tr>
					<tr>
						<td>Pending Inventory Adjust</td>
						<td><input type="checkbox" class="check-detail other-detail" value="196"></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td>
							<input type="checkbox" class="check-detail other-detail" value="197"> Allow to Approve / Decline<br/>
							<input type="checkbox" class="check-detail other-detail" value="198"> Automatically approve request
						</td>
					</tr>
					<tr>
						<td>Warehouse Release</td>
						<td><input type="checkbox" class="check-detail other-detail" value="199"></td>
						<td><input type="checkbox" class="check-detail other-detail" value="200"></td>
						<td><input type="checkbox" class="check-detail other-detail" value="201"></td>
						<td><input type="checkbox" class="check-detail other-detail" value="202"></td>
						<td><input type="checkbox" class="check-detail other-detail" value="203"></td>
						<td></td>
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
						<td><input type="checkbox" class="check-detail reports-detail" value="211"></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td>Product Branch Inventory</td>
						<td><input type="checkbox" class="check-detail reports-detail" value="212"></td>
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
