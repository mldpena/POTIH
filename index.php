<?php include("includes.php"); ?>
<!DOCTYPE html>
<html>
<head>
	<title>Hi-Top Inventory System</title>
	<style type="text/css">
		body{
			background: url('../img/blur-bg.jpg') no-repeat center center fixed; 
			-webkit-background-size: cover;
			-moz-background-size: cover;
			-o-background-size: cover;
			background-size: cover;
		}
	</style>
</head>
<body>
<div align="center">
	<div class="login-wrapper">
		<div class="login-panel">
			<img src="img/hitop-main.png" class="login-logo">
			<div class="login-creds">
				<div class="login-fields pull-right">
					<div class="input-group input-group-lg">
						<span class="input-group-addon"><i class="fa fa-user"></i></span>
						<input type="text" class="form-control" placeholder="Username">
					</div>
					<div class="input-group input-group-lg">
						<span class="input-group-addon"><i class="fa fa-lock" style="width:15px;"></i></span>
						<input type="password" class="form-control" placeholder="Password">
					</div>
					<div class="pull-right"><a href="controlpanel.php"><button class="btn btn-primary">Login</button></a></div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php include("footer.php"); ?>