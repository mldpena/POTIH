<?php require_once("includes.php"); ?>
<style type="text/css">
	body{
		background: url('../img/blurry.jpg') no-repeat center center fixed; 
		-webkit-background-size: cover;
		-moz-background-size: cover;
		-o-background-size: cover;
		background-size: cover;
	}
	.main-wrapper{
		width: auto;
	}
	.header-nav,
	.footer{
		background: rgba(255, 255, 255, 0.2);
		border: none;
		color: #fff;
	}
	.footer{
		text-align: center;
	}
</style>
<body>
	<div align="center">
		<div class="login-wrapper">
			<div class="login-panel">
				<img src="../img/hitop-main.png" class="login-logo">
				<div class="login-creds">
					<div class="login-fields pull-right">
						<div id="messagebox"></div>
						<div class="input-group input-group-lg">
							<span class="input-group-addon"><i class="fa fa-user"></i></span>
							<input type="text" class="form-control" placeholder="Username" id="username">
						</div>
						<div class="input-group input-group-lg">
							<span class="input-group-addon"><i class="fa fa-lock" style="width:15px;"></i></span>
							<input type="password" class="form-control" placeholder="Password" id="password">
						</div>
						<div class="pull-right"><a href="controlpanel.php"><button class="btn btn-primary" id="submit">Login</button></a></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
<?php require_once("footer.php"); ?>