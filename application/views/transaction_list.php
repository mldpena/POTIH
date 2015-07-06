<div class="main-content pull-right">
	<div class="breadcrumbs-panel">
		<ol class="breadcrumb">
			<li><a href="<?= base_url() ?>controlpanel">Home</a></li>
			<li class="active"><a href="<?= base_url() ?>product/warning">Product Inventory Warning List</a></li>
		</ol>
	</div>
	<div class="content-form">
		<div class="form-header">Product Inventory Warning List</div>
		<div class="form-body">
			<div class="max-row tbl-filters equal-sizes" align="center">
				<table>
					<tr>
						<td>Material Code:</td>
						<td><input type="text" class="form-control" id="itemcode" maxlength="8"></td>
						<td>Type:</td>
						<td>
							<select class="form-control" id="type">
								<option value="0">ALL</option>
								<option value="1">Stock</option>
								<option value="2">Non - Stock</option>
							</select>
						</td>
						<td>Branch:</td>
						<td>
							<select class="form-control" id="branch"><?= $branch_list ?></select>
						</td>
					</tr>
					<tr>
						<td>Material Name:</td>
						<td><input type="text" class="form-control" id="product"></td>
						<td>Material Type:</td>
						<td>
							<select class="form-control" id="material"><?= $material_list ?></select>
						</td>
						
					</tr>
					<tr>
						<td>Subgroup:</td>
						<td>
							<select class="form-control" id="subgroup"><?= $subgroup_list ?></select>
						</td>
						<td style="display:none;">Date To:</td>
						<td style="display:none;"><input type="text" class="form-control" id="date_from"></td>
						<td style="display:none;">Date From:</td>
						<td style="display:none;"><input type="text" class="form-control" id="date_to"></td>
					</tr>
				</table>
			</div>
			<div class="sub-panel">
				Order By:
				<select class="form-control form-control mod" id="orderby">
					<option value="1">Item Code</option>
					<option value="2">Item Name</option>
				</select>
				<input type="button" class="btn btn-success" value="Search" id="search">
				<input type="button" class="btn btn-warning" id="show-adv-info" value="Show Advanced Info">
			</div>
			<div class="max-row" id="show-info">
				<div class="tbl-filters">
					<center>
						<table>
							<tr>
								<td><input type="checkbox"></td>
								<td>Date From:</td>
								<td><input type="text" class="form-control"></td>
								<td>Date To:</td>
								<td><input type="text" class="form-control"></td>
							</tr>
						</table>
					</center>
				</div>
				<div class="tbl max">
					<table>
						<tr class="tableheader">
							<td>Particular</td>
							<td>Purchase Receive</td>
							<td>Customer Return</td>
							<td>Stock Receive</td>
							<td>Adjust Increase</td>
							<td>Damage</td>
							<td>Purchase Return</td>
							<td>Stock Delivery</td>
							<td>Customer Delivery</td>
							<td>Adjust Decreased</td>
						</tr>
						<tr>
							<td>With Transactions</td>
							<td><input type="checkbox"></td>
							<td><input type="checkbox"></td>
							<td><input type="checkbox"></td>
							<td><input type="checkbox"></td>
							<td><input type="checkbox"></td>
							<td><input type="checkbox"></td>
							<td><input type="checkbox"></td>
							<td><input type="checkbox"></td>
							<td><input type="checkbox"></td>
						</tr>
						<tr>
							<td>Without Transactions</td>
							<td><input type="checkbox"></td>
							<td><input type="checkbox"></td>
							<td><input type="checkbox"></td>
							<td><input type="checkbox"></td>
							<td><input type="checkbox"></td>
							<td><input type="checkbox"></td>
							<td><input type="checkbox"></td>
							<td><input type="checkbox"></td>
							<td><input type="checkbox"></td>
						</tr>
					</table>
				</div>
				<div class="row">
					<hr>
				</div>
			</div>
			<div class="max-row">
				<div id="messagebox_1"></div>
			</div>
			<div class="max-row">
				<center>
					<img src="<?= base_url().IMG ?>loading.gif" class="img-logo" id="loadingimg">
					<div id="tbl" class="tbl max"></div>
				</center>
			</div>
		</div>
	</div>
</div>