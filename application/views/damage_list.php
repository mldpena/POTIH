<div class="main-content pull-right">
	<div class="breadcrumbs-panel">
		<ol class="breadcrumb">
			<li><a href="<?= base_url() ?>controlpanel">Home</a></li>
			<li class="active"><a href="<?= base_url() ?>damage/list">Damage Products List</a></li>
		</ol>
	</div>
	<div class="content-form">
		<div class="form-header">Damage Products List</div>
		<div class="form-body">
			<div class="max-row tbl-filters" align="center">
				<table>
					<tr>
						<td>Date From:</td>
						<td><input type="text" class="form-control"></td>
						<td>Date To:</td>
						<td><input type="text" class="form-control"></td>
					</tr>
					<tr>
						<td>Location:</td>
						<td colspan="3"><input type="text" class="form-control"></td>
					</tr>
				</table>
			</div>
			<div class="sub-panel">
				Search: 
				<input type="text" class="form-control form-control mod">
				Order By:
				<select class="form-control form-control mod">
					<option>Date</option>
					<option>Name</option>
				</select>
				<input type="button" class="btn btn-primary" value="ASC">
				<input type="button" class="btn btn-success" value="Search">
			</div>
			<div class="max-row">
				<a href="<?= base_url() ?>damage/detail">
					<button class="btn btn-primary">Create New Damage Entry</button>
				</a>
			</div>
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
								<td></td>
								<td>Location</td>
								<td>Date</td>
								<td>Entry Number</td>
								<td>Request by</td>
								<td>Amount</td>
								<td style="width:245px;">Memo</td>
							</tr>
							<tr>
								<td></td>
								<td>Sample</td>
								<td>Sample</td>
								<td>Sample</td>
								<td>Sample</td>
								<td>Sample</td>
								<td>Sample</td>
							</tr>
							<tr>
								<td></td>
								<td>Sample</td>
								<td>Sample</td>
								<td>Sample</td>
								<td>Sample</td>
								<td>Sample</td>
								<td>Sample</td>
							</tr>
							<tr>
								<td></td>
								<td>Sample</td>
								<td>Sample</td>
								<td>Sample</td>
								<td>Sample</td>
								<td>Sample</td>
								<td>Sample</td>
							</tr>
							<tr>
								<td></td>
								<td>Sample</td>
								<td>Sample</td>
								<td>Sample</td>
								<td>Sample</td>
								<td>Sample</td>
								<td>Sample</td>
							</tr>
							<tr>
								<td></td>
								<td>Sample</td>
								<td>Sample</td>
								<td>Sample</td>
								<td>Sample</td>
								<td>Sample</td>
								<td>Sample</td>
							</tr>
							<tr>
								<td></td>
								<td>Sample</td>
								<td>Sample</td>
								<td>Sample</td>
								<td>Sample</td>
								<td>Sample</td>
								<td>Sample</td>
							</tr>
							<tr>
								<td></td>
								<td>Sample</td>
								<td>Sample</td>
								<td>Sample</td>
								<td>Sample</td>
								<td>Sample</td>
								<td>Sample</td>
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
		</div>
	</div>
</div>