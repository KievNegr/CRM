<!DOCTYPE html>
<html>
<head>
	<title>CRM deals</title>
	<meta name_deal="Descriprion" content="CRM system">
	<meta name_deal="Keywords" content="CRM system">
	<meta charset="utf-8">
	<link rel="stylesheet" href="<?php echo base_url('css/admin.css'); ?>">
	<link rel="stylesheet" href="<?php echo base_url('css/datePicker.css'); ?>" />
	<script src="<?php echo base_url('js/jquery.min.js'); ?>" type="text/javascript"></script>
	<script src="<?php echo base_url('js/scripts.js'); ?>" type="text/javascript"></script>
	<script src="<?php echo base_url('js/admin.js'); ?>" type="text/javascript"></script>
	<script src="<?php echo base_url('js/typeahead.min.js'); ?>" type="text/javascript"></script>
	<script src="<?php echo base_url('js/tooltipsy.min.js'); ?>" type="text/javascript"></script>
	<script src="<?php echo base_url('js/jquery.autosize.min.js'); ?>" type="text/javascript"></script>
	<script src="<?php echo base_url('js/jquery.datePicker.js'); ?>" type="text/javascript"></script>
	<script src="<?php echo base_url('js/date.js'); ?>" type="text/javascript"></script>
	<script>
		$(function()
		{
			$('.date-pick').datePicker();
		});
	</script>
</head>

<body>
	<div id="wrapper">
		<div id="header" style="background: <?php echo $header; ?>;">
			<div id="logo">
				<img src="<?php echo base_url('img/logo_company.jpg'); ?>" />
			</div>
			<h1 link="<?php echo $index; ?>"><?php echo $title_h1; ?></h1>
			<div class="search">
				<input type="text" place="<?php echo $place; ?>" class="search_text" placeholder="Поиск" />
				<!--<button class="search_button" style="float: left">Найти</button>-->
			</div>
			<div id="profile">
				<?php
					if( !empty($profile['avatar']) )
					{
						$avatar = $profile['avatar'];
					}
					else
					{
						$avatar = 'default.png';
					}
				?>
				<img id="header_avatar" src="<?php echo base_url('img/avatars/' . $avatar); ?>" />
			</div>
		</div><!--/header-->