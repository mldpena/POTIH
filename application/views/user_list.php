<div class="main-content pull-right">
	<div class="breadcrumbs-panel">
		<ol class="breadcrumb">
			<li><a href="<?= base_url() ?>controlpanel">Home</a></li>
			<li class="active"><a href="<?= base_url() ?>user/list">User List</a></li>
		</ol>
	</div>
	<div class="content-form">
		<div class="form-header">User List</div>
		<div class="form-body">
			<div class="sub-panel">
				Search : 
				<input type="text" id="search_string" class="form-control mod">
				Status :
				<select class="form-control mod" id="status">
					<option value="0">ALL</option>
					<option value="1">Active</option>
					<option value="2">Inactive</option>
				</select>
				Order By :
				<select id="order_by" class="form-control mod">
					<option value="1">Code</option>
					<option value="2">Name</option>
				</select>
				<input type="button" class="btn btn-primary" value="ASC" id="order_type">
				<input type="button" class="btn btn-success" value="Search" id="search">
			</div>
			<div class="max-row">
				<a href="<?= base_url() ?>user/add">
					<button class="btn btn-primary" data-toggle="modal">Create New User</button>
				</a>
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
<!-- Modal for delete confirmation -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Delete User</h4>
			</div>
			<div class="modal-body">
				<div class="message-content">
					Are you sure you want to delete this user?
				</div>
				<br/><div id="messagebox_2"></div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
				<button type="button" class="btn btn-primary" id="delete">Delete</button>
			</div>
		</div>
	</div>
</div>