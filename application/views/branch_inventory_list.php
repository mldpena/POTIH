<div class="main-content pull-right">
	<div class="breadcrumbs-panel">
		<ol class="breadcrumb">
			<li><a href="<?= base_url() ?>controlpanel">Home</a></li>
			<li class="active"><a href="<?= base_url() ?>product/inventory">Product Branch Inventory</a></li>
		</ol>
	</div>
	<div class="content-form">
		<div class="form-header">Product Branch Inventory</div>
		<div class="form-body">
			<div class="max-row tbl-filters" align="center">
				<table>
					<tr>
						<td>Material Code:</td>
						<td><input type="text" class="form-control" id="itemcode" maxlength="8"></td>
						<td>Material Type:</td>
						<td><select class="form-control" id="material"><?= $material_list ?></select></td>
					</tr>
					<tr class="hide-elem">
						<td>Type:</td>
						<td>
							<select class="form-control" id="type">
								<option value="0">ALL</option>
								<option value="1" selected>Stock</option>
								<option value="2">Non - Stock</option>
							</select>
						</td>
					</tr>
					<tr>
						<td>Material Name:</td>
						<td><input type="text" class="form-control" id="product"></td>
						<td>Subgroup:</td>
						<td><select class="form-control" id="subgroup"><?= $subgroup_list ?></select></td>
					</tr>
					<tr>
						<td class="hide-elem">Date To:</td>
						<td class="hide-elem"><input type="text" class="form-control" id="date_from"></td>
						<td class="hide-elem">Date From:</td>
						<td class="hide-elem"><input type="text" class="form-control" id="date_to"></td>
					</tr>
					
					<tr>
						<td>Branch:</td>
						<td colspan="3" style="width:552px;">
							<select id="branch" class="form-control" multiple="multiple" data-placeholder="Select a Branch"><?= $branch_list ?></select>
						</td>
					</tr>
				</table>
			</div>
			<div class="sub-panel">
				Order By:
				<select class="form-control form-control mod" id="orderby">
					<option value="1">Item Code</option>
					<option value="2">Item Name</option>
				</select>
				<input type="button" class="btn btn-success" value="Search" id="search">
			</div>
			<div class="max-row">
				<div id="messagebox_1"></div>
			</div>
			<div class="max-row">
				<center>
					<img src="<?= base_url().IMG ?>loading.gif" class="img-logo" id="loadingimg">
					<div id="tbl" class="tbl max"></div>
				</center>
			</div>
			<div class="max-row" align="right">
				<button class="btn btn-info btn-excel" id="export"><i class="fa fa-file-excel-o"></i>&nbsp; Export Excel</button>		
			</div>
		</div>
	</div>
</div>