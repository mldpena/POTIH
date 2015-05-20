<div class="main-content pull-right">
	<div class="breadcrumbs-panel">
		<ol class="breadcrumb">
			<li><a href="controlpanel.php">Home</a></li>
			<li class="active"><a href="#">Product List</a></li>
		</ol>
	</div>
	<div class="content-form">
		<div class="form-header">Product List</div>
		<div class="form-body">
			<div class="max-row tbl-filters" align="center">
				<table>
					<tr>
						<td>Item Code:</td>
						<td><input type="text" class="form-control"></td>
						<td>Type:</td>
						<td>
							<select class="form-control">
								<option>Sample</option>
								<option>Sample</option>
							</select>
						</td>
						<td>Branch:</td>
						<td>
							<select class="form-control">
								<option>Sample</option>
								<option>Sample</option>
							</select>
						</td>
					</tr>
					<tr>
						<td>Item Name:</td>
						<td><input type="text" class="form-control"></td>
						<td>Material Type:</td>
						<td>
							<select class="form-control">
								<option>Sample</option>
								<option>Sample</option>
							</select>
						</td>
						<td>Inventory Status:</td>
						<td>
							<select class="form-control">
								<option>Sample</option>
								<option>Sample</option>
							</select>
						</td>
					</tr>
					<tr>
						<td>Subgroup:</td>
						<td><input type="text" class="form-control"></td>
						<td>Date To:</td>
						<td><input type="text" class="form-control"></td>
						<td>Date From:</td>
						<td><input type="text" class="form-control"></td>
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
				<input type="button" class="btn btn-success" value="Search">
			</div>
			<div class="max-row">
				<button class="btn btn-primary" data-toggle="modal" data-target="#createProductModal">Create New Product</button>
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

<!-- Modal -->
<div class="modal fade" id="createProductModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Create New Product</h4>
			</div>
			<div class="modal-body">
				<div class="form-group">
					<div class="checkbox pull-right">
						<input type="checkbox" value="">Non-stack Item
					</div>
				</div>
				<div class="form-group">
					Item Code:
					<input type="text" class="form-control">
				</div>
				<div class="form-group">
					Item Description:
					<textarea class="form-control" rows="4"></textarea>
				</div>
				<div class="form-group">
					<div class="row">
						<div class="col-xs-6">
							Minimum Inventory:
							<input type="text" class="form-control">
						</div>
						<div class="col-xs-6">
							Maximum Inventory:
							<input type="text" class="form-control">
						</div>
					</div>
				</div>
				<div class="form-group">
					<div class="row">
						<div class="col-xs-6">
							Material Type:
							<div class="txt-data">1203918230</div>
						</div>
						<div class="col-xs-6">
							Subgrouping:
							<div class="txt-data">1203918230</div>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary">Save changes</button>
			</div>
		</div>
	</div>
</div>