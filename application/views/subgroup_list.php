<div class="main-content pull-right">
	<div class="breadcrumbs-panel">
		<ol class="breadcrumb">
			<li><a href="<?= base_url() ?>controlpanel">Home</a></li>
			<li class="active"><a href="<?= base_url() ?>material/list">Subgroup List</a></li>
		</ol>
	</div>
	<div class="content-form">
		<div class="form-header">Subgroup List</div>
		<div class="form-body">
			<div class="sub-panel">
				Search: 
				<input id="searchstring"type="text" class="form-control form-control mod">
				Order By:
				<select id="orderby" class="form-control form-control mod">
					<option value="1">Code</option>
					<option value="2">Name</option>
				</select>
				<input type="button" class="btn btn-success" value="Search " id="search">
			</div>
			<div class="max-row">
				<button class="btn btn-primary" data-toggle="modal" data-target="#myModal">Create New Subgroup </button>
			</div>
			<div class="max-row">
				<div id="messagebox_1"></div>
			</div>
			<div class="max-row">
				<center>
					<div id="tbl" class="tbl"></div>
				</center>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="myModalLabelCreate"></h4>
			</div>
			<div class="modal-body">
			<div class="form-group">
				<label>Code</label>
				<input type="text" class="form-control nexttab" id='code'>	
			</div>

			<div class="form-group">
				<label>Name:</label>
				<input type="text" class="form-control nexttab" id='name'>
			</div>
				
			</div>
			<div class="modal-footer">
				<center><div id = "messagebox_2"></div></center>
				<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
				<button type="button" class="btn btn-primary" id="sample">Save</button>
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