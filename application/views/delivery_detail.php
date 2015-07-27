<div id="dynamic-css"></div>
<div class="main-content pull-right">
	<div class="breadcrumbs-panel">
		<ol class="breadcrumb">
			<li><a href="<?= base_url() ?>controlpanel">Home</a></li>
			<li><a href="<?= base_url() ?>delivery/list">Item Delivery List</a></li>
			<li class="active"><a href="<?= base_url() ?>delivery/view/<?= $this->uri->segment(3) ?>">Item Delivery Detail</a></li>
		</ol>
	</div>
	<div class="content-form">
		<div class="form-header">Item Delivery Detail</div>
		<div class="form-body">
			<div class="max-row tbl-filters">
				<table>
					<tr>
						<td>Delivery Type:</td>
						<td style="width:300px;">
							<select class="form-control" id="delivery_type">
								<option value="1">BOTH</option>
								<option value="2">Sales</option>
								<option value="3">Transfer</option>
							</select>
						</td>
					</tr>
					<tr>
						<td>Reference #:</td>
						<td style="width:300px;"><input type="text" class="form-control" id="reference_no" disabled></td>
					</tr>
					<tr id="delivery_to_list">
						<td>Delivery To:</td>
						<td colspan="1">
							<select class="form-control" id="to_branch" ><?= $branch_list ?></select>
						</td>
					</tr>
					<tr>
						<td>Delivery Date:</td>
						<td><input type="text" class="form-control" id="date"></td>
					</tr>
					<tr>
						<td valign="top">Memo:</td>
						<td><textarea class="form-control" rows="3" id='memo'></textarea></td>
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
				<input type="button" class="btn btn-primary" value="Print Type" data-toggle="modal" data-target="#printModal">
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
				<h4 class="modal-title" id="myModalLabel">Delete stock delivery Entry</h4>
			</div>
			<div class="modal-body">
				<div class="message-content">
					Are you sure you want to delete this stock delivery entry?
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
<div class="modal fade" id="printModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Print Type</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12" align="center">
						<div class="btn btn-print color-default">
							<img src="<?= base_url().IMG ?>printcustomer.png" class="">
							<div>Print for Customer Only</div>
						</div>
						<div class="btn btn-print color-default">
							<img src="<?= base_url().IMG ?>printtransfer.png" class="">
							<div>Print for Transfer Only</div>
						</div>
						<div class="btn btn-print color-default">
							<img src="<?= base_url().IMG ?>printboth.png" class="">
							<div>Print Both</div>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary" id="">Print</button>
			</div>
		</div>
	</div>
</div>