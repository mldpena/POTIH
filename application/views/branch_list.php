<div class="main-content pull-right">
	<div class="breadcrumbs-panel">
		<ol class="breadcrumb">
			<li><a href="<?= base_url() ?>controlpanel">Home</a></li>
			<li class="active"><a href="<?= base_url() ?>branch/list">Branch List</a></li>
		</ol>
	</div>
	<div class="content-form">
		<div class="form-header">Branch List</div>
		<div class="form-body">
			<div class="sub-panel">
				Search: 
				<input id="search_string"type="text" class="form-control form-control mod">
				Order By:
				<select id="orderby" class="form-control form-control mod">
					<option value="1">Code</option>
					<option value="2">Name</option>
				</select>
				<input type="button" class="btn btn-success" value="Search " id="search">
			</div>
			<div class="max-row">
				<button class="btn btn-primary" data-toggle="modal" data-target="#createModal">Create Branch</button>
			</div>
			<div class="max-row">
				<div id="messagebox_1"></div>
			</div>
			<div class="max-row">
				<center>
					<img src="<?= base_url().IMG ?>loading.gif" class="img-logo" id="loadingimg">
					<div id="tbl" class="tbl"></div>
				</center>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabelCreate">Add New Branch</h4>
			</div>
			<div class="modal-body">
				<div class="form-group">
					<label>Code</label>
					<input type="text" class="form-control modal-fields" id='code'>	
				</div>
				<div class="form-group">
					<label>Name:</label>
					<input type="text" class="form-control modal-fields" id='name'>
				</div>
				<div id = "messagebox_3"></div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
				<button type="button" class="btn btn-primary" id="save">Save</button>
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
				<h4 class="modal-title" id="myModalLabel">Delete Branch</h4>
			</div>
			<div class="modal-body">
				Are you sure you want to delete this branch?
				<div id="messagebox_2"></div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
				<button type="button" class="btn btn-primary" id="delete">Delete</button>
			</div>
		</div>
	</div>
</div>