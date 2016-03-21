<div id="dynamic-css"></div>
<div class="main-content pull-right">
	<div class="breadcrumbs-panel">
		<ol class="breadcrumb">
			<li><a href="<?= base_url() ?>controlpanel">Home</a></li>
			<li><a href="<?= base_url() ?>sales/list">Sales Invoice List</a></li>
			<li class="active"><a href="<?= base_url() ?>sales/view/<?= $this->uri->segment(3) ?>">Sales Invoice Detail</a></li>
		</ol>
	</div>
	<div class="content-form">
		<div class="form-header">Sales Invoice Detail</div>
		<div class="form-body">
			<div class="max-row tbl-filters">
				<div class="row">
					<div class="col-md-12">
						<label class="form-inline margin-left5">
							<input type="radio" name="customer-type" value="2">
							Walk-in
						</label>
						<label class="form-inline">
							<input type="radio" name="customer-type" value="1" checked>
							Regular
						</label>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="pull-left">
							<table>
								<tr>
									<td>Reference #:</td>
									<td width="200px"><input type="text" class="form-control" id="reference_no" disabled></td>
								</tr>
								<tr>
									<td>Customer:</td>
									<td>
										<select class="form-control" id="customer">
											<option value="0"></option>
											<?= $customer_list ?>
										</select>
										<input type="text" class="form-control hide-elem" id="walkin-customer">
									</td>
								</tr>
								<tr>
									<td>Salesman:</td>
									<td>
										<select class="form-control" id="salesman">
											<option value="0"></option>
											<?= $salesman_list ?>
										</select>
									</td>
								</tr>
								<tr>
									<td>Is Vatable:</td>
									<td>
										<select class="form-control" id="is-vatable" disabled>
											<option value="1">NO</option>
											<option value="2">YES</option>
										</select>
									</td>
								</tr>
								<tr>
									<td valign="top">Address:</td>
									<td><textarea class="form-control" cols="30" rows="3" id='address'></textarea></td>
								</tr>
							</table>
						</div>
						<div class="pull-left" style="margin-left: 15px;">
							<table>
								<tr>
									<td>Date:</td>
									<td width="200px"><input type="text" class="form-control" id="date"></td>
								</tr>
								<tr>
									<td>PO. No.:</td>
									<td><input type="text" class="form-control" id="po-number"></td>
								</tr>
								<tr>
									<td>DR. No.:</td>
									<td><input type="text" class="form-control" id="dr-number"></td>
								</tr>
								<tr>
									<td>For Branch:</td>
									<td>
										<select class="form-control" id="orderfor" ><?= $branch_list ?></select>
									</td>
								</tr>
								<tr>
									<td valign="top">Memo:</td>
									<td><textarea class="form-control" cols="30" rows="3" id='memo'></textarea></td>
								</tr>
							</table>
						</div>
						<div class="pull-right salestbl-detail">
							<div class="tbl single max tbl-reservation" id="tbl_reservation"></div>
						</div>
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
			<div class="max-row tbl-total" align="left">
				<table>
					<tr>
						<td>Total Quantity:</td>
						<td class="td-right"><span id="total_qty">0</span></td>
					</tr>
				</table>
			</div>
			<div class="max-row tbl-total" align="right">
				<table>
					<tr>
						<td>VATable:</td>
						<td class="td-right"><span id="vatable">0</span></td>
					</tr>
					<tr>
						<td>VAT Amount:</td>
						<td class="td-right"><span id="vat-amount" >0</span></td>
					</tr>
					<tr>
						<td>Total Sales (VAT Inclusive):</td>
						<td class="td-right"><span id="vat-inclusive">0</span></td>
					</tr>
					<tr>
						<td>Less VAT:</td>
						<td class="td-right"><span id="less-vat">0</span></td>
					</tr>
					<tr>
						<td>Total:</td>
						<td class="td-right"><span id="total">0</span></td>
					</tr>
					<tr>
						<td>Total Amount Due:</td>
						<td class="td-right"><span id="amount-due">0</span></td>
					</tr>
				</table>
			</div>
			<div class="max-row">
				<div class="pull-right">
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
				<h4 class="modal-title" id="myModalLabel">Delete Sales Invoice Entry</h4>
			</div>
			<div class="modal-body">
				<div class="message-content">
					Are you sure you want to delete this sales invoice entry?
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