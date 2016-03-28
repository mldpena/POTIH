<div class="main-content pull-right">
	<div class="breadcrumbs-panel">
		<ol class="breadcrumb">
			<li><a href="<?= base_url() ?>controlpanel">Home</a></li>
			<li class="active"><a href="<?= base_url() ?>sales/report">Sales Report</a></li>
		</ol>
	</div>
	<div class="content-form">
		<div class="form-header">Sales Report</div>
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
						<td>Report Type:</td>
						<td colspan="3">
							<select class="form-control" id="report-type">
								<option value="1">Daily Sales Report</option>
								<option value="2">Periodic Sales Report</option>
								<option value="3">Customer Report</option>
							</select>
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
			<div class="max-row tbl-total" align="right">
				<table>
					<tr>
						<td>Total Amount:</td>
						<td><span id="total_amount">0</span></td>
					</tr>
				</table>
			</div>
			<div class="max-row" align="right">
				<button class="btn btn-info btn-excel export export-option-1" id="daily_sales"><i class="fa fa-file-excel-o"></i>&nbsp; Export Daily <br/> Sales Excel</button>		
				<button class="btn btn-info btn-excel export export-option-2 hide-elem" id="periodic_sales"><i class="fa fa-file-excel-o"></i>&nbsp; Export Periodic <br/> Sales Excel</button>		
				<button class="btn btn-info btn-excel export export-option-3 hide-elem" id="customer_sales"><i class="fa fa-file-excel-o"></i>&nbsp; Export Customer <br/> Sales Excel</button>		
			</div>
		</div>
	</div>
</div>