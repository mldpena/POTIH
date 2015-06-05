<div class="main-content pull-right">
	<div class="breadcrumbs-panel">
		<ol class="breadcrumb">
			<li><a href="<?= base_url() ?>controlpanel">Home</a></li>
			<li class="active"><a href="<?= base_url() ?>poreceive/list">Purchase Receive List</a></li>
		</ol>
	</div>
	<div class="content-form">
		<div class="form-header">Purchase Receive List</div>
		<div class="form-body">
			<div class="max-row tbl-filters" align="center">
				<table>
					<tr>
						<td>Date From:</td>
						<td><input type="text" class="form-control" id="date_from"></td>
						<td>Date To:</td>
						<td><input type="text" class="form-control" id="date_to"></td>
					</tr>
					<tr>
						<td>Location:</td>
						<td colspan="3">
							<select class="form-control" id="branch_list"><?= $branch_list ?></select>
						</td>
					</tr>
				</table>
			</div>
			<div class="sub-panel">
				Search: 
				<input type="text" class="form-control form-control mod" id="search_string">
				Order By:
				<select class="form-control form-control mod" id="order_by">
					<option value="1">Reference #</option>
					<option value="2">Location</option>
					<option value="3">Entry Date</option>
				</select>
				<input type="button" class="btn btn-primary" value="ASC" id="order_type">
				<input type="button" class="btn btn-success" value="Search" id="search">
			</div>
			<div class="max-row">
				<button class="btn btn-primary" id="create_new">Create New Purchase Receive</button>
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
<div class="modal fade" id="deletePurchaseReceiveModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Delete Purchase Receive Entry</h4>
			</div>
			<div class="modal-body">
				Are you sure you want to delete this purchase receive entry?
				<div id="messagebox_2"></div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
				<button type="button" class="btn btn-primary" id="delete">Delete</button>
			</div>
		</div>
	</div>
</div>