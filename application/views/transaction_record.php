<div class="main-content pull-right">
	<div class="breadcrumbs-panel">
		<ol class="breadcrumb">
			<li><a href="<?= base_url() ?>controlpanel">Home</a></li>
			<li class="active"><a href="<?= base_url() ?>product/record/<?= $this->uri->segment(3).'/'.$this->uri->segment(4) ?>">Product Transaction Record</a></li>
		</ol>
	</div>
	<div class="content-form">
		<div class="form-header">Product Transaction Record</div>
		<div class="form-body">
			<div class="max-row tbl-filters" align="center">
				<table>
					<tr>
						<td>Date From:</td>
						<td><input type="text" class="form-control" id="date_from"></td>
						<td>Date To:</td>
						<td><input type="text" class="form-control" id="date_to"></td>
					</tr>
					<tr>
						<td>Location:</td>
						<td colspan="3"><select class="form-control" id="branch"><?= $branch_list ?></select></td>
					</tr>
				</table>
			</div>
			<div class="sub-panel">
				Search: 
				<input type="text" class="form-control form-control mod txtproduct" id="product_search">
				<input type="hidden" class="form-control form-control mod" id="product_id">
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
			<div class="max-row">
				<center>
					<div id="tbl_breakdown" class="tbl max"></div>
				</center>
			</div>
		</div>
	</div>
</div>