<div class="main-content pull-right">
	<div class="breadcrumbs-panel">
		<ol class="breadcrumb">
			<li><a href="controlpanel.php">Home</a></li>
			<li><a href="#">User List</a></li>
			<li class="active">User Information</li>
		</ol>
	</div>
	<div class="content-form">
		<div class="form-header">User Information</div>
		<div class="form-body">
			<div class="max-row">
				<div class="row">
					<div class="col-xs-2">
						<img src="<?= base_url().IMG?>person.jpg" class="img-responsive img-thumbnail">
					</div>
					<div class="col-xs-10 tbl-form max">
						<table>
							<tr>
								<td>Branches:</td>
								<td colspan="3"><input type="text" class="form-control" placeholder="Select a Branch"></td>
							</tr>
							<tr>
								<td>User Code:</td>
								<td><input type="text" class="form-control"></td>
								<td>Username:</td>
								<td><input type="text" class="form-control"></td>
							</tr>
							<tr>
								<td>Full Name:</td>
								<td><input type="text" class="form-control"></td>
								<td>Password:</td>
								<td><input type="password" class="form-control"></td>
							</tr>
							<tr>
								<td>Status:</td>
								<td>
									<div class="tbl-checkbtn">
										<div><input type="checkbox" value=""></div>
										<span>Active</span>
									</div>
								</td>
								<td>Contact No.:</td>
								<td><input type="text" class="form-control"></td>
							</tr>
						</table>
					</div>
				</div>
			</div>
			<div class="max-row" align="right">
				<input type="button" class="btn btn-success" value='Save'>
				<input type="button" class="btn btn-info" value='Show Advanced Info'>
			</div>
		</div>
	</div>
</div>
