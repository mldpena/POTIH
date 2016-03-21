<div id="dynamic-css"></div>
<div class="main-content pull-right">
	<div class="breadcrumbs-panel">
		<ol class="breadcrumb">
			<li><a href="<?= base_url() ?>controlpanel">Home</a></li>
			<li><a href="<?= base_url() ?>assort/list">Pick-Up Assortment List</a></li>
			<li class="active"><a href="<?= base_url() ?>assort/view/<?= $this->uri->segment(3) ?>">Pick-Up Assortment Detail</a></li>
		</ol>
	</div>
	<div class="content-form">
		<div class="form-header">Pick-Up Assortment Detail</div>
		<div class="form-body">
			<div class="max-row tbl-filters">
				<div class="row">
					<div class="col-md-12">
						<label class="form-inline">
							<input type="radio" name="customer-type" value="2" checked>
							Walk-in
						</label>
						<label class="form-inline margin-left5">
							<input type="radio" name="customer-type" value="1">
							Regular
						</label>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<table>
							<tr>
								<td>Reference #:</td>
								<td style="width:300px;"><input type="text" class="form-control" id="reference_no" disabled></td>
							</tr>
							<tr>
								<td>Customer:</td>
								<td>
									<select class="form-control hide-elem" id="customer">
										<option value="0"></option>
										<?= $customer_list ?>
									</select>
									<input type="text" class="form-control" id="walkin-customer">
								</td>
							</tr>
							<tr>
								<td>Date:</td>
								<td><input type="text" class="form-control" id="date"></td>
							</tr>
							<tr>
								<td valign="top">Memo:</td>
								<td><textarea class="form-control" rows="3" id='memo'></textarea></td>
							</tr>
						</table>
					</div>
				</div>
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
			<div class="max-row">
				<div class="pull-right">
					<input type="button" class="btn btn-primary" value="Print" id="print">
					<input type="button" class="btn btn-success" value="Save" id="save">
				</div>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Delete Pick-Up Assortment Entry</h4>
			</div>
			<div class="modal-body">
				<div class="message-content">
					Are you sure you want to delete this pick-up assortment entry?
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