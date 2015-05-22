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
						<td style="width:300px;"><input type="text" class="form-control"></td>
					</tr>
					<tr>
						<td>DR #:</td>
						<td><input type="text" class="form-control"></td>
					</tr>
					<tr>
						<td>Receive Date:</td>
						<td><input type="text" class="form-control"></td>
					</tr>
					<tr>
						<td valign="top">Memo:</td>
						<td><textarea class="form-control" rows="3"></textarea></td>
					</tr>
				</table>
				<div class="pull-right po-container">
					<div class="tbl-checkbtn pull-right po-chkbx" align="center">
						<input type="checkbox">
						PO for Current Branch
					</div>
					<div class="tbl single max tbl-po">
						<table>
							<tr class="tableheader">
								<td><input type="checkbox"></td>
								<td>PO Date</td>
								<td>Amount</td>
							</tr>
							<tr>
								<td><input type="checkbox"></td>
								<td>11010101</td>
								<td>300.00</td>
							</tr>
							<tr>
								<td><input type="checkbox"></td>
								<td>11010101</td>
								<td>300.00</td>
							</tr>
							<tr>
								<td><input type="checkbox"></td>
								<td>11010101</td>
								<td>300.00</td>
							</tr>
							<tr>
								<td><input type="checkbox"></td>
								<td>11010101</td>
								<td>300.00</td>
							</tr>
							<tr>
								<td><input type="checkbox"></td>
								<td>11010101</td>
								<td>300.00</td>
							</tr>
							<tr>
								<td><input type="checkbox"></td>
								<td>11010101</td>
								<td>300.00</td>
							</tr>
							<tr>
								<td><input type="checkbox"></td>
								<td>11010101</td>
								<td>300.00</td>
							</tr>
						</table>
					</div>
				</div>
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