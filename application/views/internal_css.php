<?php
	switch ($page) {
		case 'login':
?>
		<style type="text/css">
			body{
				background: url('<?= base_url().IMG ?>blurry.jpg') no-repeat center center fixed; 
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
				position: absolute;
				bottom: 0;
				text-align: center;
			}
		</style>
<?php
		break;
		case 'controlpanel':
?>
		<style type="text/css">
			.sidebar{
				display: none;
			}
			.main-content{
				width: 100%;
				float: left;
				margin: -15px 0 0 0;
				padding: 0px 15px 15px 15px;
			}
		</style>
<?php
		break;
	}
?>
	