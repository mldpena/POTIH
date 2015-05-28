<div class="main-content pull-right">
	<div class="breadcrumbs-panel">
		<ol class="breadcrumb">
			<li><a href="<?= base_url() ?>controlpanel">Home</a></li>
			<li class="active"><a href="<?= base_url() ?>purchasereceive/list">Purchase Receive List</a></li>
			<li class="active"><a href="<?= base_url() ?>purchasereceive/detail">Purchase Receive Detail</a></li>
		</ol>
	</div>
	<div class="content-form">
		<div class="form-header">Purchase Receive Detail</div>
		<div class="form-body">
			<div class="max-row tbl-filters">
				<table class="pull-left">
					<tr>
						<td>Reference #:</td>
						<td style="width:300px;"><input type="text" class="form-control" id="reference_no" disabled></td>
					</tr>
					<tr>
						<td>Receive Date:</td>
						<td><input type="text" class="form-control" id="date"></td>
					</tr>
					<tr>
						<td valign="top">Memo:</td>
						<td><textarea class="form-control" rows="3"></textarea></td>
					</tr>
				</table>
				<div class="pull-right po-container">
					<!-- <div class="tbl-checkbtn pull-right po-chkbx" align="center">
						<input type="checkbox" id="po_for_current_branch">
						PO for Current Branch
					</div> -->
					<div class="tbl single max tbl-po" id="tbl_po"></div>
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
			<div class="max-row" align="right">
				<input type="button" class="btn btn-primary" value="Print">
				<input type="button" class="btn btn-success" value="Save">
			</div>
		</div>
	</div>
</div>