<!DOCTYPE html>
<html lang="en">
<head>
	<title>CRM system</title>
	<meta name="Descriprion" content="CRM system">
	<meta name="Keywords" content="CRM system">
	<meta charset="utf-8">
	<link rel="stylesheet" href="<?php echo base_url('css/auth.css'); ?>">
	<script type="text/javascript" src="<?php echo base_url('js/jquery.min.js'); ?>"></script>
	<script type="text/javascript">
		$(document).ready(function () {
			$('#login_0').css('height', $(document).height());
			$('#login_1').css('height', $(document).height());

			$('#submit').click(function()
			{
				$.ajax({
					type: 'post',
					url: '<?php echo base_url("auth/login");?>',
					data: {
						'email': $('#email').val(),
						'password': $('#password').val(),
						'remember': $('#remember').prop('checked')
					},
					success: ok_login
				});
			});

			function ok_login(data)
			{
				if( data == 'login' )
				{
					$(location).attr('href', '<?php echo base_url();?>');
				}
			}
		});
	</script>
</head>
<body>
		<div id="login_0">
1
		</div>
		<div id="login_1">
			<?php //echo form_open("auth/login", array('autocomplete' => 'off'));?>
				<div style="width: 272px; margin: 150px auto; padding: 10px; background: rgba(0, 0, 0, 0.3);">
					<input type="text" name="email" value="" id="email" autocomplete="on" placeholder="e-mail"  />
					<input type="password" name="password" value="" id="password" autocomplete="on" placeholder="password"  />
					<div style="clear: both;"></div>
					<input type="checkbox" name="remember" id="remember" value="1" /><span>Запомнить</span>
					<div style="clear: both;"></div>
					<input type="submit" name="submit" value="Login" id="submit" />
					<p>или</p>
					<p><a href="<?php echo base_url('auth/create_user'); ?>">Зарегистрироватся</a></p>
				</div>
			<?php //echo form_close();?>
		</div>
	
	
</body>
</html>