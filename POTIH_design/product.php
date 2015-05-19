<?php require_once("header.php"); ?>
	<div class="main-content pull-right">
		<div class="breadcrumbs-panel">
			<ol class="breadcrumb">
				<li><a href="controlpanel.php">Home</a></li>
				<li><a href="#">Product List</a></li>
				<li class="active">Create New Product</li>
			</ol>
		</div>
		<div class="content-form">
			<div class="form-header">Create New Product</div>
			<div class="form-body default">
				<div class="max-row">
					<input type="checkbox">
					Non-stack Item
				</div>
				<div class="max-row">
					<table>
						<tr>
							<td>Item Code</td>
							<td><input type="text" class="form-control"></td>
							<td>Item Code</td>
							<td><input type="text" class="form-control"></td>
						</tr>
					</table>
					<div class="col-xs-6">
						<div class="form-group">
							Item Code:
							
						</div>
						<div class="form-group">
							Item Description:
							<textarea class="form-control" rows="4"></textarea>
						</div>
						<div class="form-group">
							Minimum Inventory:
							<input type="text" class="form-control mod">
							Maximum Inventory:
							<input type="text" class="form-control mod">
						</div>
					</div>
					<div class="col-xs-6">
						<div class="form-group">
							
							<div class="txt-data">1203918230</div>
						</div>
						<div class="form-group">
							Subgrouping:
							<div class="txt-data">1203918230</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php require_once("footer.php"); ?>