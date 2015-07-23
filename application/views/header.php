<!DOCTYPE html>
<html>
	<head>
		<title>Hi-Top Inventory System</title>
		<?php require_once("includes.php"); ?>
		<script type="text/javascript">
			$(function(){
		        $(document).mouseup(function (e){
		            var container = $("#notifications-panel");
		            if (!container.is(e.target) && container.has(e.target).length === 0) 
		                container.hide();
		        });
		        $("#drop-notif").toggle(
		        	function(){
		           		$("#notifications-panel").show();
		           	},
		           	function(){
		           		$("#notifications-panel").hide();
		           	}
		        );
		        $(".notifications-single").click(function(){
		            $("#notifications-panel").hide();
		        });
			});
		</script>
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
										<li><a href="<?= base_url() ?>user/view/<?= get_cookie('temp') ?>">My Profile</a></li>
										<li><a href="<?= base_url() ?>login/logout">Logout</a></li>
									</ul>
								</div>
								<a href="<?= base_url() ?>controlpanel"><div class="ez-menu pull-right">Home</div></a>
								<div class="pull-right">
				                    <div class="notifications-icon active" id="drop-notif">
				                        <div class="notif-circle" style="display:none;">10</div>
				                        <i class="fa fa-bell"></i>
				                    </div>
				                    <div class="dropdown-menu-m notifications-dropdown" id="notifications-panel">
				                        <div class="notifications-header" data-toggle="dropdown" aria-expanded="false">Notifications</div>
				                        <div class="notifications-single">
				                            <div class="detail-container">
				                                <div class="subject">New Order Received</div>
				                                <div class="time">30 minutes ago</div>
				                            </div>
				                            <img class="icon" src="<?= base_url().IMG ?>customerreceive.png">
				                        </div>
				                        <div class="notifications-single">
				                            <div class="detail-container">
				                                <div class="subject">New Return Report</div>
				                                <div class="time">10 minutes ago</div>
				                            </div>
				                            <img class="icon" src="<?= base_url().IMG ?>stockreceive.png">
				                        </div>
				                        <div class="notifications-single">
				                            <div class="detail-container">
				                                <div class="subject">New Collection</div>
				                                <div class="time">3 minutes ago</div>
				                            </div>
				                            <img class="icon" src="<?= base_url().IMG ?>return.png">
				                        </div>
				                    </div>
				                </div>
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