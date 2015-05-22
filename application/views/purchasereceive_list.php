<div class="main-content pull-right">
	<div class="breadcrumbs-panel">
		<ol class="breadcrumb">
			<li><a href="<?= base_url() ?>controlpanel">Home</a></li>
			<li class="active"><a href="<?= base_url() ?>purchasereceive/list">Purchase Receive List</a></li>
		</ol>
	</div>
	<div class="content-form">
		<div class="form-header">Purchase Receive List</div>
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
						<td colspan="3">
							<select class="form-control">
								<option>Sample</option>
								<option>Sample</option>
								<option>Sample</option>
							</select>
						</td>
					</tr>
					<tr>
						<td>For Branch:</td>
						<td colspan="3">
							<select class="form-control">
								<option>Sample</option>
								<option>Sample</option>
								<option>Sample</option>
							</select>
						</td>
					</tr>
					<tr>
						<td>Delivery Status:</td>
						<td colspan="3">
							<select class="form-control">
								<option>Sample</option>
								<option>Sample</option>
								<option>Sample</option>
							</select>
						</td>
					</tr>
					<tr>
						<td>Supplier:</td>
						<td colspan="3">
							<select class="form-control">
								<option>Sample</option>
								<option>Sample</option>
								<option>Sample</option>
							</select>
						</td>
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
				<a href="<?= base_url() ?>purchasereceive/detail">
					<button class="btn btn-primary">Create New Purchase Receive</button>
				</a>
			</div>
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
		</div>
	</div>
</div>