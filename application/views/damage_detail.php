<div class="main-content pull-right">
	<div class="breadcrumbs-panel">
		<ol class="breadcrumb">
			<li><a href="<?= base_url() ?>controlpanel">Home</a></li>
			<li><a href="<?= base_url() ?>damage/list">Damage Products List</a></li>
			<li class="active"><a href="<?= base_url() ?>damage/detail">Create New Damage Entry</a></li>
		</ol>
	</div>
	<div class="content-form">
		<div class="form-header">Create New Damage Entry</div>
		<div class="form-body">
			<div class="max-row tbl-filters">
				<table>
					<tr>
						<td>Reference Number:</td>
						<td style="width:300px;"><input type="text" class="form-control"></td>
					</tr>
					<tr>
						<td>Date:</td>
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
					<!-- This table is a sample from JS table Layout -->
					<div class="tbl">
						<table class="tblstyle">
							<tr class="tableheader">
								<td>Item Code</td>
								<td>Product</td>
								<td>Qty</td>
								<td>Note</td>
								<td></td>
								<td></td>
							</tr>
							<tr>
								<td>Sample</td>
								<td>Sample</td>
								<td>Sample</td>
								<td>Sample</td>
								<td class="action-btn"><i class="fa fa-check fa-lg"></i></td>
								<td class="action-btn"><i class="fa fa-close fa-lg"></i></td>
							</tr>
							<tr>
								<td>Sample</td>
								<td>Sample</td>
								<td>Sample</td>
								<td>Sample</td>
								<td class="action-btn"><i class="fa fa-check fa-lg"></i></td>
								<td class="action-btn"><i class="fa fa-close fa-lg"></i></td>
							</tr>
							<tr>
								<td>Sample</td>
								<td>Sample</td>
								<td>Sample</td>
								<td>Sample</td>
								<td class="action-btn"><i class="fa fa-check fa-lg"></i></td>
								<td class="action-btn"><i class="fa fa-close fa-lg"></i></td>
							</tr>
							<tr>
								<td>Sample</td>
								<td>Sample</td>
								<td>Sample</td>
								<td>Sample</td>
								<td class="action-btn"><i class="fa fa-check fa-lg"></i></td>
								<td class="action-btn"><i class="fa fa-close fa-lg"></i></td>
							</tr>
							<tr>
								<td>Sample</td>
								<td>Sample</td>
								<td>Sample</td>
								<td>Sample</td>
								<td class="action-btn"><i class="fa fa-check fa-lg"></i></td>
								<td class="action-btn"><i class="fa fa-close fa-lg"></i></td>
							</tr>
							<tr>
								<td>Sample</td>
								<td>Sample</td>
								<td>Sample</td>
								<td>Sample</td>
								<td class="action-btn"><i class="fa fa-check fa-lg"></i></td>
								<td class="action-btn"><i class="fa fa-close fa-lg"></i></td>
							</tr>
							<tr>
								<td>Sample</td>
								<td>Sample</td>
								<td>Sample</td>
								<td>Sample</td>
								<td class="action-btn"><i class="fa fa-check fa-lg"></i></td>
								<td class="action-btn"><i class="fa fa-close fa-lg"></i></td>
							</tr>
							<tr>
								<td>Sample</td>
								<td>Sample</td>
								<td>Sample</td>
								<td>Sample</td>
								<td class="action-btn"><i class="fa fa-check fa-lg"></i></td>
								<td class="action-btn"><i class="fa fa-close fa-lg"></i></td>
							</tr>
							<tr>
								<td>Sample</td>
								<td>Sample</td>
								<td>Sample</td>
								<td>Sample</td>
								<td class="action-btn"><i class="fa fa-check fa-lg"></i></td>
								<td class="action-btn"><i class="fa fa-close fa-lg"></i></td>
							</tr>
							<tr>
								<td>Sample</td>
								<td>Sample</td>
								<td>Sample</td>
								<td>Sample</td>
								<td class="action-btn"><i class="fa fa-check fa-lg"></i></td>
								<td class="action-btn"><i class="fa fa-close fa-lg"></i></td>
							</tr>
						</table>
						<table>
							<tr>
								<td><input type="button" value="Previous"></td>
								<td><input type="text"></td>
								<td><input type="button" value="Next"></td>
							</tr>
							<tr>
								<td></td>
								<td>1 / 10</td>
								<td></td>
							</tr>
						</table>
					</div>
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