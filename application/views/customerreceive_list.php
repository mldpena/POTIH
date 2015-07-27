<div class="main-content pull-right">
	<div class="breadcrumbs-panel">
		<ol class="breadcrumb">
			<li><a href="<?= base_url() ?>controlpanel">Home</a></li>
			<li class="active"><a href="<?= base_url() ?>custreceive/list">Customer Receive List</a></li>
		</ol>
	</div>
	<div class="content-form">
		<div class="form-header">Customer Receive List</div>
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
				<button class="btn btn-info btn-excel" id="export"><i class="fa fa-file-excel-o"></i>&nbsp; Export Excel</button>		
			</div>
		</div>
	</div>
</div>