<!DOCTYPE html>
<html>
	<head>
		<title>Hi-Top Inventory System</title>
		<?php require_once("includes.php"); ?>
	</head>
	<body>
		<div class="main-wrapper">
			<div class="container-fluid">
				<div class="row">
					<div class="header-nav">
						<div class="version pull-left">
							Hi-Top - Version 1.0 <?= isset($branch) ? ' | Branch : '.$branch : '' ?>
						</div>
						<?php if ($page != 'login') : ?>
							<div class="user-creds">
								<div class="btn-group pull-right">
									<button type="button" class="btn btn-default dropdown-toggle user-btn" data-toggle="dropdown" aria-expanded="false">
										<?= isset($name) ? $name : '' ?> <span class="caret"></span>
									</button>
									<ul class="dropdown-menu" role="menu">
										<li><a href="#">My Profile</a></li>
										<li><a href="<?= base_url() ?>login/logout">Logout</a></li>
									</ul>
								</div>
								<a href="<?= base_url() ?>controlpanel"><div class="ez-menu pull-right">Home</div></a>
							</div>
						<?php endif; ?>
					</div>
				</div>
			</div>
			<?php if ($page != 'login') : ?>
			<div class="container-fluid">
				<div class="row">
					<?php require_once("sidebar.php"); ?>
			<?php endif; ?>