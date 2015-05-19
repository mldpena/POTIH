<div align="center">
	<div class="login-wrapper">
		<div class="login-panel">
			<img src="<?= base_url().IMG ?>hitop-main.png" class="login-logo">
			<div class="login-creds">
				<div class="login-fields pull-right">
					<div id="messagebox_1"></div>
					<div class="input-group input-group-lg">
						<span class="input-group-addon"><i class="fa fa-user"></i></span>
						<input type="text" class="form-control" placeholder="Username" id="username">
					</div>
					<div class="input-group input-group-lg">
						<span class="input-group-addon"><i class="fa fa-lock" style="width:15px;"></i></span>
						<input type="password" class="form-control" placeholder="Password" id="password">
					</div>
					<div class="pull-right"><button class="btn btn-primary" id="submit">Login</button></div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="myModalLabel">Select a Branch to Login</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Select a Branch: </label>
                    <select id="branch" class="form-control"></select>
                </div>
                <center>
                    <div id ="messagebox_2"></div>
                </center>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="proceed">Proceed</button>
            </div>
        </div>
    </div>
</div>
