<div class="main-content pull-right">
	<div class="breadcrumbs-panel">
		<ol class="breadcrumb">
			<li><a href="<?= base_url() ?>controlpanel">Home</a></li>
			<li class="active"><a href="<?= base_url() ?>pickup/list">Pick-Up Summary List</a></li>
		</ol>
	</div>
	<div class="content-form">
		<div class="form-header">Pick-Up Summary List</div>
		<div class="form-body">
			<div class="max-row tbl-filters" align="center">
				<table>
					<tr>
						<td>Location:</td>
						<td style="width:300px;">
							<select class="form-control" id="branch_list"><?= $branch_list ?></select>
						</td>
					</tr>
				</table>
			</div>
			<div class="sub-panel">
				Search: 
				<input id="search_string"type="text" class="form-control form-control mod">
				<input type="button" class="btn btn-success" value="Search " id="search">
			</div>

			<?php if($permission_list['allow_to_add']) : ?>

			<div class="max-row">
				<button class="btn btn-primary" id="create_summary">Create Pick-Up Summary</button>
			</div>

			<?php endif; ?>

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
<!-- Modal for delete confirmation -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Delete Pick-Up Summary</h4>
			</div>
			<div class="modal-body">
				<div class="message-content">
					Are you sure you want to delete this summary?	
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