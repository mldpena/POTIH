<div class="main-content pull-right">
	<div class="breadcrumbs-panel">
		<ol class="breadcrumb">
			<li><a href="<?= base_url() ?>controlpanel">Home</a></li>
			<li><a href="<?= base_url() ?>customer/list">Customer List</a></li>
			<li class="active"><a href="<?= base_url() ?>customer/<?= $this->uri->segment(2).'/'.$this->uri->segment(3) ?>">Customer Information</a></li>
		</ol>
	</div>
	<div class="content-form">
		<div class="form-header">Customer Information</div>
		<div class="form-body">
			<div class="max-row">
				<div class="row">
					<div class="col-xs-12 tbl-form max">
						<table>
							<tr>
								<td>Code</td>
								<td><input type="text" class="form-control" id="code"></td>
								<td>Company Name:</td>
								<td><input type="email" class="form-control" id="company-name"></td>
							</tr>
							<tr>
								<td valign="top">Office Address:</td>
								<td>
									<textarea class="form-control" rows="3" placeholder="Office Address" id="office-address"></textarea>
								</td>
								<td valign="top">Plant Address:</td>
								<td>
									<textarea class="form-control" rows="3" placeholder="Plant Address" id="plant-address"></textarea>
								</td>
							</tr>
							<tr>
								<td>Contact No.:</td>
								<td><input type="text" class="form-control" id="contact"></td>
								<td>Contact Person:</td>
								<td><input type="text" class="form-control" id="contact-person"></td>
							</tr>
							<tr>
								<td>Tin:</td>
								<td><input type="text" class="form-control" id="tin"></td>
								<td>Tax:</td>
								<td>
									<select class="form-control" id="tax">
										<option value="2">Vatable</option>
										<option value="1">Non-Vatable</option>
									</select>
								</td>
							</tr>
							<tr>
								<td>Business Style:</td>
								<td><input type="text" class="form-control" id="business-style"></td>
							</tr>
						</table>
						<div id="messagebox_1"></div>
					</div>
				</div>
			</div>
			<div class="max-row" align="right">
				<input type="button" class="btn btn-success" value='Save' id="save">
			</div>
		</div>
	</div>
</div>