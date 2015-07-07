<div class="main-content pull-right">
	<div class="breadcrumbs-panel">
		<ol class="breadcrumb">
			<li><a href="<?= base_url() ?>controlpanel">Home</a></li>
			<li class="active"><a href="<?= base_url() ?>release/pickup">Pick-Up Summary</a></li>
		</ol>
	</div>
	<div class="content-form">
		<div class="form-header">Pick Up Summary</div>
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
						<td>Location:</td>
						<td colspan="3"><select class="form-control" id="branch_list"><?= $branch_list ?></select></td>
					</tr>
				</table>
			</div>
			<div class="sub-panel">
				Search: 
				<input type="text" class="form-control form-control mod" id="search_string">
				Order By:
				<select class="form-control form-control mod" id="order_by">
					<option value="1">Doc #</option>
					<option value="2">Location</option>
					<option value="3">Customer</option>
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
					<div class="tbl max" id="tbl"></div>
				</center>
			</div>
			<div class="max-row" align="right">
				<input type="button" class="btn btn-primary" value="Print" id="print">
			</div>
		</div>
	</div>
</div>