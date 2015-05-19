<link rel="stylesheet" type="text/css" href="<?= base_url().CSS ?>bootstrap.css">
<link rel="stylesheet" type="text/css" href="<?= base_url().CSS ?>font-awesome.css">
<link rel="stylesheet" type="text/css" href="<?= base_url().CSS ?>hitop-main.css">
<link rel="stylesheet" type="text/css" href="<?= base_url().CSS ?>table-style.css">
<link rel="stylesheet" type="text/css" href="<?= base_url().CSS ?>chosen.css">
<link rel="stylesheet" type="text/css" href="<?= base_url().CSS ?>jquery-ui.css">
<link rel="stylesheet" type="text/css" href="<?= base_url().CSS ?>jquery.timepicker.css">
<?php require_once('internal_css.php'); ?>

<script type="text/javascript" src="<?= base_url().JS ?>jquery-1.8.0.min.js"></script>
<script type="text/javascript" src="<?= base_url().JS ?>jquery-ui-1.8.23.custom.min.js"></script>
<script type="text/javascript" src="<?= base_url().JS ?>bootstrap.js"></script>
<script type="text/javascript" src="<?= base_url().JS ?>jquery.timepicker.min.js"></script>
<script type="text/javascript" src="<?= base_url().JS ?>chosen.jquery.js"></script>
<?php if(isset($script)) { require_once(SCRIPTS.$script); } ?>
<?php require_once(SCRIPTS.'helpers/table_helper.php') ?>
<?php require_once(SCRIPTS.'helpers/error_helper.php') ?>