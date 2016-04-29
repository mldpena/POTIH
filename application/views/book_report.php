<div class="main-content pull-right">
	<div class="breadcrumbs-panel">
		<ol class="breadcrumb">
			<li><a href="<?= base_url() ?>controlpanel">Home</a></li>
			<li class="active"><a href="<?= base_url() ?>sales/book_report">Sales Book Report</a></li>
		</ol>
	</div>
	<div class="content-form">
		<div class="form-header">Sales Book Report</div>
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
						<td>Customer:</td>
						<td colspan="3">
							<select class="form-control" id="customer">
								<?= $customer_list ?>
								<option value="-1">Walk-in Customer</option>
							</select>
						</td>
					</tr>
					<tr>
						<td>For Branch:</td>
						<td colspan="3">
							<select class="form-control" id="for_branch"><?= $branch_list ?></select>
						</td>
					</tr>
					<tr>
						<td>Salesman:</td>
						<td colspan="3">
							<select class="form-control" id="salesman"><?= $salesman_list ?></select>
						</td>
					</tr>
				</table>
			</div>
			<div class="sub-panel">
				<input type="button" class="btn btn-success" value="Search" id="search">
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
			<div class="max-row" align="right" id="tbl-total">
				<table>
					<tr>
						<td>Total Invoice Amount:</td>
						<td class="td-right"><span id="total-invoice-amount">0</span></td>
					</tr>
					<tr>
						<td>Total VATable Amount:</td>
						<td class="td-right"><span id="total-vatable-amount">0</span></td>
					</tr>
					<tr>
						<td>Total VAT Amount:</td>
						<td class="td-right"><span id="total-vat-amount">0</span></td>
					</tr>
					<tr>
						<td>Total VAT Exempt Amount:</td>
						<td class="td-right"><span id="total-vat-exempt-amount">0</span></td>
					</tr>
				</table>
			</div>
			<div class="max-row" align="right">
				<button class="btn btn-info btn-excel export" id="export"><i class="fa fa-file-excel-o"></i>&nbsp; Export Excel</button>			
			</div>
		</div>
	</div>
</div>