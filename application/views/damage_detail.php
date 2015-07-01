<div class="main-content pull-right">
	<div class="breadcrumbs-panel">
		<ol class="breadcrumb">
			<li><a href="<?= base_url() ?>controlpanel">Home</a></li>
			<li><a href="<?= base_url() ?>damage/list">Damage Products List</a></li>
			<li class="active"><a href="<?= base_url() ?>damage/add">Create New Damage Entry</a></li>
		</ol>
	</div>
	<div class="content-form">
		<div class="form-header">Create New Damage Entry</div>
		<div class="form-body">
			<div class="max-row tbl-filters">
				<table>
					<tr>
						<td>Reference Number:</td>
						<td style="width:300px;"><input type="text" class="form-control" id="reference_no" disabled></td>
					</tr>
					<tr>
						<td>Date:</td>
						<td><input type="text" class="form-control" id="date"></td>
					</tr>
					<tr>
						<td valign="top">Memo:</td>
						<td><textarea class="form-control" rows="3" id="memo"></textarea></td>
					</tr>
				</table>
			</div>
			<div class="divider-line"></div>
			<div class="max-row">
				<div id="messagebox_1"></div>
			</div>
			<div class="max-row">
				<center>
					<div id="tbl" class="tbl max"></div>
				</center>
			</div>
			<div class="max-row tbl-total" align="right">
				<table>
					<tr>
						<td>Total Quantity:</td>
						<td><span id="total_qty">0</span></td>
					</tr>
				</table>
			</div>
			<div class="max-row" align="right">
				<input type="button" class="btn btn-primary" value="Print" id="print">
				<input type="button" class="btn btn-success" value="Save" id="save">
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Delete Damage Detail</h4>
			</div>
			<div class="modal-body">
				<div class="message-content">
					Are you sure you want to delete this detail?
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
