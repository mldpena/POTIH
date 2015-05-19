<?php require_once("header.php"); ?>
	<div class="main-content pull-right">
		<div class="breadcrumbs-panel">
			<ol class="breadcrumb">
				<li><a href="controlpanel.php">Home</a></li>
				<li class="active">Data</li>
			</ol>
		</div>
		<div class="content-form">
			<div class="form-header">Material Type</div>
			<div class="form-body">
				<div class="sub-panel">
					Search: 
					<input type="text" class="form-control form-control mod">
					Order By:
					<select class="form-control form-control mod">
						<option>Date</option>
						<option>Name</option>
					</select>
					<input type="button" class="btn btn-success" value="Search">
				</div>
				<div class="max-row">
					<button class="btn btn-primary" data-toggle="modal" data-target="#myModal">Create New Material Type</button>
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
									<td>Sample</td>
									<td>Sample</td>
									<td>Sample</td>
								</tr>
								<tr>
									<td>Sample</td>
									<td>Sample</td>
									<td>Sample</td>
								</tr>
								<tr>
									<td>Sample</td>
									<td>Sample</td>
									<td>Sample</td>
								</tr>
								<tr>
									<td>Sample</td>
									<td>Sample</td>
									<td>Sample</td>
								</tr>
								<tr>
									<td>Sample</td>
									<td>Sample</td>
									<td>Sample</td>
								</tr>
								<tr>
									<td>Sample</td>
									<td>Sample</td>
									<td>Sample</td>
								</tr>
								<tr>
									<td>Sample</td>
									<td>Sample</td>
									<td>Sample</td>
								</tr>
								<tr>
									<td>Sample</td>
									<td>Sample</td>
									<td>Sample</td>
								</tr>
								<tr>
									<td>Sample</td>
									<td>Sample</td>
									<td>Sample</td>
								</tr>
								<tr>
									<td>Sample</td>
									<td>Sample</td>
									<td>Sample</td>
								</tr>
								<tr>
									<td>Sample</td>
									<td>Sample</td>
									<td>Sample</td>
								</tr>
								<tr>
									<td>Sample</td>
									<td>Sample</td>
									<td>Sample</td>
								</tr>
								<tr>
									<td>Sample</td>
									<td>Sample</td>
									<td>Sample</td>
								</tr>
								<tr>
									<td>Sample</td>
									<td>Sample</td>
									<td>Sample</td>
								</tr>
								<tr>
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
<?php require_once("footer.php"); ?>

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Modal title</h4>
			</div>
			<div class="modal-body">
				
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary">Save changes</button>
			</div>
		</div>
	</div>
</div>