<div class="main-content pull-right">
	<div class="breadcrumbs-panel">
		<ol class="breadcrumb">
			<li><a href="<?= base_url() ?>controlpanel">Home</a></li>
			<li><a href="<?= base_url() ?>adjust/list">Inventory Adjust List</a></li>
			<li class="active"><a href="<?= base_url() ?>adjust/express">Inventory Adjust Express List</a></li>
		</ol>
	</div>
	<div class="content-form">
		<div class="form-header">Inventory Adjust Express List</div>
		<div class="form-body">
			<div class="max-row tbl-filters" align="center">
				<table>
					<tr>
						<td>Date From:</td>
						<td><input type="text" class="form-control" id="date_from"></td>
						<td>Date To:</td>
						<td><input type="text" class="form-control" id="date_to"></td>
					</tr>
				</table>
			</div>
			<div class="sub-panel">
				Search: 
				<input type="text" class="form-control form-control mod" id="search_string">
				Order By:
				<select class="form-control form-control mod" id="order_by">
					<option value="1">Name</option>
					<option value="2">Code</option>
				</select>
				<input type="button" class="btn btn-primary" value="ASC" id="order_type">
				<input type="button" class="btn btn-success" value="Search" id="search">
			</div>
			<div class="max-row">
				<center>
					<img src="<?= base_url().IMG ?>loading.gif" class="img-logo" id="loadingimg">
					<div id="tbl" class="tbl max"></div>
					<div id="messagebox_1"></div>
				</center>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="deleteAdjustRequest" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Delete Inventory Request Entry</h4>
			</div>
			<div class="modal-body">
				Are you sure you want to delete this inventory request entry?
				<div id="messagebox_2"></div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
				<button type="button" class="btn btn-primary" id="delete">Delete</button>
			</div>
		</div>
	</div>
</div>