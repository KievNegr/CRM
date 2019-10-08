<!DOCTYPE html>
<html lang="en">
<head>
	<title>CRM system</title>
	<meta name="Descriprion" content="CRM system">
	<meta name="Keywords" content="CRM system">
	<meta charset="utf-8">
	<link rel="stylesheet" href="<?php echo base_url('css/register.css'); ?>">
	<script type="text/javascript" src="<?php echo base_url('js/jquery.min.js'); ?>"></script>

	<script type="text/javascript">
		$(document).ready(function () {
			$('#login_0').css('height', $(document).height());
			$('#login_1').css('height', $(document).height());
		});
	</script>
</head>
<body>
	<div id="login_0">
1
		</div>
		<div id="login_1">		
			<div id="infoMessage"><?php echo $message;?></div>
			<div style="width: 272px; margin: 50px auto; padding: 10px; background: rgba(0, 0, 0, 0.3);">
				<?php echo form_open("auth/create_user");?>
				  <input type="text" name="first_name" value="" id="first_name" placeholder="Имя" autocomplete="off" />

				  <input type="text" name="last_name" value="" id="last_name" placeholder="Фамилия" autocomplete="off" />
				  
				  <!--<p>Компания:<br />
				  <?php echo form_input($company);?>
				  </p>-->

					<input type="text" name="email" value="" id="email" placeholder="Email" autocomplete="off" />	

				  <input type="text" name="phone1" value="" id="phone1" placeholder="Телефон" autocomplete="off" />	

				  <input type="password" name="password" value="" id="password" placeholder="Пароль" autocomplete="off" />

				  <input type="password" name="password_confirm" value="" id="password_confirm" placeholder="Подтверждение пароля" autocomplete="off" />
				  
				  
				  <p><?php echo form_submit('submit', 'Create User');?></p>
				
					<p>или</p>
					<p><a href="<?php echo base_url('auth/login'); ?>">Залогинится</a></p>
				  
				<?php echo form_close();?>
			</div>
		</div>
</body>
</html>
