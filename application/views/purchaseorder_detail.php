<div class="main-content pull-right">
	<div class="breadcrumbs-panel">
		<ol class="breadcrumb">
			<li><a href="<?= base_url() ?>controlpanel">Home</a></li>
			<li class="active"><a href="<?= base_url() ?>purchaseorder/list">Purchase Order List</a></li>
			<li class="active"><a href="<?= base_url() ?>purchaseorder/detail">Purchase Order Detail</a></li>
		</ol>
	</div>
	<div class="content-form">
		<div class="form-header">Purchase Order Detail</div>
		<div class="form-body">
			<div class="max-row tbl-filters">
				<table>
					<tr>
						<td>Reference #:</td>
						<td style="width:300px;"><input type="text" class="form-control"></td>
					</tr>
					<tr>
						<td>Supplier:</td>
						<td><input type="text" class="form-control"></td>
					</tr>
					<tr>
						<td>Date:</td>
						<td><input type="text" class="form-control"></td>
					</tr>
					<tr>
						<td>Order For:</td>
						<td><input type="text" class="form-control"></td>
					</tr>
					<tr>
						<td valign="top">Memo:</td>
						<td><textarea class="form-control" rows="3"></textarea></td>
					</tr>
				</table>
			</div>
			<div class="divider-line"></div>
			<div class="max-row">
				<div class="lblmsg warning">
					Message not good!
				</div>
			</div>
			<div class="max-row">
				<center>
					<div id="tbl" class="tbl max"></div>
				</center>
			</div>
			<div class="max-row tbl-total" align="right">
				<table>
					<tr>
						<td>Total Amount:</td>
						<td><span>0.00</span></td>
					</tr>
				</table>
			</div>
			<div class="max-row" align="right">
				<input type="button" class="btn btn-primary" value="Print">
				<input type="button" class="btn btn-success" value="Save">
			</div>
		</div>
	</div>
</div>