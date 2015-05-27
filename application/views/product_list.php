<div class="main-content pull-right">
	<div class="breadcrumbs-panel">
		<ol class="breadcrumb">
			<li><a href="<?= base_url() ?>controlpanel">Home</a></li>
			<li class="active"><a href="<?= base_url() ?>product/list">Product List</a></li>
		</ol>
	</div>
	<div class="content-form">
		<div class="form-header">Product List</div>
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
				<button class="btn btn-primary" data-toggle="modal" data-target="#createProductModal" id="create_product">Create New Product</button>
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
<!-- Modal for insert and update products -->
<div class="modal fade" id="createProductModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Create New Product</h4>
			</div>
			<div class="modal-body">
				<div class="form-group">
					<div class="checkbox pull-right" id="new_check_nonstack">
						<input type="checkbox" class="modal-fields" value="" id="new_nonstack">Non-stock Item
					</div>
				</div>
				<div class="form-group">
					Material Code:
					<input type="text" class="form-control modal-fields" id="new_itemcode" maxlength="8">
				</div>
				<div class="form-group">
					Material Description:
					<textarea class="form-control modal-fields" rows="4" id="new_product"></textarea>
				</div>
				<div class="form-group">
					<div id="tbl_min_max" class="tbl max"></div>
				</div>
				<div class="form-group">
					<div class="row">
						<div class="col-xs-6">
							Material Type:
							<div class="txt-data modal-fields" id="material_text"></div>
							<input type="hidden" class="modal-fields" id="material_id" value="0">
						</div>
						<div class="col-xs-6">
							Subgrouping:
							<div class="txt-data modal-fields" id="subgroup_text"></div>
							<input type="hidden" class="modal-fields" id="subgroup_id" value="0">
						</div>
					</div>
				</div>
				<div id="messagebox_2"></div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary" id="save">Save</button>
			</div>
		</div>
	</div>
</div>
<!-- Modal for delete confirmation -->
<div class="modal fade" id="deleteProductModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Delete Product</h4>
			</div>
			<div class="modal-body">
				Are you sure you want to delete this product?
				<div id="messagebox_3"></div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
				<button type="button" class="btn btn-primary" id="delete">Delete</button>
			</div>
		</div>
	</div>
</div>