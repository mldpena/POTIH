<div id="dynamic-css"></div>
<div class="main-content pull-right">
	<div class="breadcrumbs-panel">
		<ol class="breadcrumb">
			<li><a href="<?= base_url() ?>controlpanel">Home</a></li>
			<li class="active"><a href="<?= base_url() ?>delivery/list">Stock Receive List</a></li>
			<li class="active"><a href="<?= base_url() ?>delivery/view">Stock Receive Detail</a></li>
		</ol>
	</div>
	<div class="content-form">
		<div class="form-header">Stock Receive Detail</div>
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
						<td>Receive Date:</td>
						<td><input type="text" class="form-control" id="receive_date"></td>
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
				<input type="button" class="btn btn-primary" value="Print" id="print">
				<input type="button" class="btn btn-success" value="Save" id="save">
			</div>
		</div>
	</div>
</div>