<div class="main-content pull-right">
	<div class="breadcrumbs-panel">
		<ol class="breadcrumb">
			<li><a href="<?= base_url() ?>controlpanel">Home</a></li>
			<li class="active"><a href="<?= base_url() ?>requestfrom/list">Item Requested by Other Branches List</a></li>
		</ol>
	</div>
	<div class="content-form">
		<div class="form-header">Item Requested by Other Branches List</div>
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
						<td>From Branch:</td>
						<td colspan="3">
							<select class="form-control" id="from_branch"><?= $branch_list ?></select>
						</td>
					</tr>
					<tr style="display:none;">
						<td>To Branch:</td>
						<td colspan="3">
							<select class="form-control" id="to_branch"><?= $to_branch_list ?></select>
						</td>
					</tr>
					<tr>
						<td>Status:</td>
						<td colspan="3">
							<select class="form-control" id="status">
								<option value="0">ALL</option>
								<option value="1">Incomplete</option>
								<option value="2">Complete</option>
								<option value="3">No Received</option>
								<option value="4">Excess</option>
							</select>
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
					<option value="3">Date</option>
				</select>
				<input type="button" class="btn btn-primary" value="ASC" id="order_type">
				<input type="button" class="btn btn-success" value="Search" id="search">
			</div>

			<?php if($permission_list['allow_to_add']) : ?>

			<div class="max-row">
				<button class="btn btn-primary" id="create_new">Create New Item Request</button>
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
			<!-- <div class="max-row" align="right">
				<button class="btn btn-info btn-excel" id="export"><i class="fa fa-file-excel-o"></i>&nbsp; Export Excel</button>		
			</div> -->
		</div>
	</div>
</div>
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Delete Item Request Entry</h4>
			</div>
			<div class="modal-body">
				<div class="message-content">
					Are you sure you want to delete this item request entry?
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