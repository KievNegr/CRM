<!DOCTYPE html>
<html>
<head>
	<title>CRM deals</title>
	<meta name_deal="Descriprion" content="CRM system">
	<meta name_deal="Keywords" content="CRM system">
	<meta charset="utf-8">
	<link rel="stylesheet" href="<?php echo base_url('css/style.css'); ?>">
	<link rel="stylesheet" href="<?php echo base_url('css/datePicker.css'); ?>" />
	<script src="<?php echo base_url('js/jquery.min.js'); ?>" type="text/javascript"></script>
	<script src="<?php echo base_url('js/scripts.js'); ?>" type="text/javascript"></script>
	<script src="<?php echo base_url('js/jquery.color-2.1.2.min.js'); ?>" type="text/javascript"></script>
	<script src="<?php echo base_url('js/typeahead.min.js'); ?>" type="text/javascript"></script>
	<script src="<?php echo base_url('js/tinymce/tinymce.min.js'); ?>" type="text/javascript"></script>
	<script src="<?php echo base_url('js/jquery.datePicker.js'); ?>" type="text/javascript"></script>
	<script src="<?php echo base_url('js/date.js'); ?>" type="text/javascript"></script>
	<script type="text/javascript">
		$(function()
		{
			$('.date-pick').datePicker();
		});

		tinymce.init({
			language : 'ru',
		    selector: "textarea",
		    plugins: "media, image, table, preview, link"
		 });
	</script>
</head>

<body>
	<div id="wrapper">
		<div id="header">
			<div class="logo">
				<img src="<?php echo base_url('img/logo_company.jpg'); ?>" />
			</div><!--/logo-->
			<ul id="menu">
				<li><a href="<?php echo base_url(); ?>" <?php echo $headerMenu['index'];?>>Главная</a></li>
				<li><a href="<?php echo base_url('deals'); ?>" <?php echo $headerMenu['deals'];?>>Сделки</a></li>
				<li><a href="<?php echo base_url('clients'); ?>"<?php echo $headerMenu['clients'];?>>Клиенты</a></li>
				<li><a href="<?php echo base_url('tasks'); ?>"<?php echo $headerMenu['tasks'];?>>Задачи</a></li>
			</ul><!--/menu-->
			<div class="search">
				<input type="text" place="deals" class="search_text" placeholder="Поиск">
			</div><!--/search-->
			<div id="profile">
				<div class="notify"></div>
				<div class="settings"></div>
				<div class="logout"></div>
			</div><!--/profile-->
		</div><!--/header-->