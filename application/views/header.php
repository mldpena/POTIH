<!DOCTYPE html>
<html>
<head>
	<title>Hi-Top Inventory System</title>
	<?php include("includes.php"); ?>
</head>
<body>
	<div class="main-wrapper">
		<div class="container-fluid">
			<div class="row">
				<div class="header-nav">
					<div class="version pull-left">
						Hi-Top - Version 1.0
					</div>
					<div class="user-creds">
						<div class="btn-group pull-right">
							<button type="button" class="btn btn-default dropdown-toggle user-btn" data-toggle="dropdown" aria-expanded="false">
								Admin <span class="caret"></span>
							</button>
							<ul class="dropdown-menu" role="menu">
								<li><a href="#">My Profile</a></li>
								<li><a href="index.php">Logout</a></li>
							</ul>
						</div>
						<a href="#"><div class="ez-menu pull-right">Settings</div></a>
						<a href="controlpanel.php"><div class="ez-menu pull-right">Control Panel</div></a>
					</div>
				</div>
			</div>
		</div>