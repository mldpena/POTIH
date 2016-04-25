<div class="main-content pull-right">
	<div class="breadcrumbs-panel">
		<ol class="breadcrumb">
			<li><a href="<?= base_url() ?>controlpanel">Home</a></li>
			<li class="active"><a href="<?= base_url() ?>customer/list">Customer List</a></li>
		</ol>
	</div>
	<div class="content-form">
		<div class="form-header">Customer List</div>
		<div class="form-body">
			<div class="sub-panel">
				Search : 
				<input type="text" id="search_string" class="form-control mod">
				Is Vatable :
				<select class="form-control mod" id="is-vatable">
					<option value="0">ALL</option>
					<option value="1">Nonvat</option>
					<option value="2">Vatable</option>
				</select>
				Order By :
				<select id="order_by" class="form-control mod">
					<option value="1">Code</option>
					<option value="2">Name</option>
				</select>
				<input type="button" class="btn btn-primary" value="ASC" id="order_type">
				<input type="button" class="btn btn-success" value="Search" id="search">
			</div>

			<?php if($permission_list['allow_to_add']) : ?>

			<div class="max-row">
				<a href="<?= base_url() ?>customer/add">
					<button class="btn btn-primary" data-toggle="modal">Create New Customer</button>
				</a>
			</div>

			<?php endif; ?>
			
			<div class="max-row">
				<div id="messagebox_1"></div>
			</div>
			<div class="max-row">
				<center>
					<img src="<?= base_url().IMG ?>loading.gif" class="img-logo" id="loadingimg">
					<div id="tbl" class="tbl max"></div>
				</center>
			</div>
			<div class="max-row" align="right">
				<button class="btn btn-success btn-excel btn-import" data-toggle="modal" data-target="#uploadModal" id="import-customer"><i class="fa fa-file-excel-o"></i>&nbsp; Import Customer</button>	
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
				<h4 class="modal-title" id="myModalLabel">Delete Customer</h4>
			</div>
			<div class="modal-body">
				<div class="message-content">
					Are you sure you want to delete this customer?
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

<div class="modal fade" id="uploadModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="modalLabel">Import Customer</h4>
			</div>
			<div class="modal-body">
				<div class="message-content">
					Upload CSV File :
					<input type="file" name="fileData" id="fileData" />
				</div>
				<br/>
				<center>
					<img src="<?= base_url().IMG ?>loading.gif" class="img-logo loadingimg" id="loadingimg_upload">
					<div id="messagebox_4"></div>
				</center>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
				<button type="button" class="btn btn-primary" id="uploadFile">Upload</button>
			</div>
		</div>
	</div>
</div>