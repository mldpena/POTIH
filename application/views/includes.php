<link rel="stylesheet" type="text/css" href="<?= base_url().CSS ?>bootstrap.css">
<link rel="stylesheet" type="text/css" href="<?= base_url().CSS ?>font-awesome.css">
<link rel="stylesheet" type="text/css" href="<?= base_url().CSS ?>hitop-main.css">
<link rel="stylesheet" type="text/css" href="<?= base_url().CSS ?>table-style.css">
<link rel="stylesheet" type="text/css" href="<?= base_url().CSS ?>chosen.css">
<link rel="stylesheet" type="text/css" href="<?= base_url().CSS ?>jquery-ui.css">
<link rel="stylesheet" type="text/css" href="<?= base_url().CSS ?>jquery.timepicker.css">
<?php require_once('internal_css.php'); ?>

<script type="text/javascript"> 
	var currentURL = "<?= base_url().$this->uri->segment(1).'/' ?><?= (in_array($this->uri->segment(2),array('view','express','record'))) ? 'list' : $this->uri->segment(2)?>";
	var notificationToken = "<?= $token ?>";
	var baseURL = "<?= base_url() ?>";
</script>
<script type="text/javascript" src="<?= base_url().JS ?>jquery-1.8.0.min.js"></script>
<script type="text/javascript" src="<?= base_url().JS ?>jquery-ui-1.8.23.custom.min.js"></script>
<script type="text/javascript" src="<?= base_url().JS ?>bootstrap.js"></script>
<script type="text/javascript" src="<?= base_url().JS ?>jquery.timepicker.min.js"></script>
<script type="text/javascript" src="<?= base_url().JS ?>chosen.jquery.js"></script>
<script type="text/javascript" src="<?= base_url().JS ?>my_js_lib.js"></script>
<script type="text/javascript" src="<?= base_url().JS ?>my_js_tbl.js"></script>
<script type="text/javascript" src="<?= base_url().JS ?>my_js_tblpaging.js"></script>   
<script type="text/javascript" src="<?= base_url().JS ?>sidebar.js"></script>   
<script type="text/javascript" src="<?= base_url().JS ?>jquery.extend.js"></script>   
<script type="text/javascript" src="<?= base_url().JS ?>jquery.binder.js"></script>   
<script type="text/javascript" src="<?= base_url().JS ?>notification.js"></script>   


