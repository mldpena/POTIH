<div class="main-content pull-right">
	<div class="breadcrumbs-panel">
		<ol class="breadcrumb">
			<li><a href="<?= base_url() ?>controlpanel">Home</a></li>
			<li class="active"><a href="<?= base_url() ?>adjust/list">Inventory Adjust List</a></li>
		</ol>
	</div>
	<div class="content-form">
		<div class="form-header">Inventory Adjust List</div>
		<div class="form-body">
			<div class="max-row tbl-filters" align="center">
				<table>
					<tr>
						<td>Material Code:</td>
						<td><input type="text" class="form-control" id="itemcode"></td>
						<td>Type:</td>
						<td>
							<select class="form-control" id="type">
								<option value="0">ALL</option>
								<option value="1">Stock</option>
								<option value="2">Non - Stock</option>
							</select>
						</td>
						<td>Inventory Status:</td>
						<td>
							<select class="form-control" id="invstatus">
								<option value="0">ALL</option>
								<option value="1">With Inventory</option>
								<option value="2">Negative Inventory</option>
								<option value="3">Zero Inventory</option>
							</select>
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
			</div>
			<div class="max-row">
				<a href="<?= base_url() ?>adjust/express"><button class="btn btn-primary">Inventory Adjust Express</button></a>
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
<!-- Modal for request details -->
<div class="modal fade" id="requestAdjustModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Inventory Adjust</h4>
			</div>
			<div class="modal-body">
				<div class="form-group" id="div-pending">
					Status:
					<span id="status">PENDING</span>
				</div>
				<div class="form-group">
					<div class="row">
						<div class="col-xs-6">
							Product Name:
							<div class="txt-data modal-fields" id="product_name"></div>
						</div>
						<div class="col-xs-6">
							Material Code:
							<div class="txt-data modal-fields" id="product_code"></div>
						</div>
					</div>
				</div>
				<div class="form-group">
					<div class="row">
						<div class="col-xs-6">
							Old Inventory:
							<div class="modal-fields" id="old_inventory"></div>
						</div>
						<div class="col-xs-6">
							New Inventory:
							<input type="text" class="form-control modal-fields" id="new_inventory">
						</div>
					</div>
				</div>
				<div class="form-group">
					Memo:
					<textarea class="form-control modal-fields" rows="4" id="memo"></textarea>
				</div>
				<div id="messagebox_2"></div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
				<button type="button" class="btn btn-primary" id="save">Save</button>
			</div>
		</div>
	</div>
</div>