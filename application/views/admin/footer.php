</div><!--//Wrapper-->
<div id="fade_all"></div>
<!-- Завершение задачи - поле для записи результата -->
<div id="finish_task_field">
	<h2 style="margin: 10px 0 0 5%;">Результат выполнения задачи</h2>
	<div id="close_result_field"></div>
	<textarea id="text_result"></textarea>
	<button class="save" id="ajax_save_task_result">Сохранить</button>
</div>
<div id="check">
<span>Задача завершена</span>
</div>
<div id="edit_profile_field">
	<div class="close"></div>
	<div class="mini_save" id="save_edit_profile"></div>
	<div class="avatar">
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
		<img id="userfile" name="userfile" title="Загрузить новый аватар" src="<?php echo base_url('img/avatars/' . $avatar); ?>" />
		<input type="file" class="add_file" value="" />
	</div>
	<div style="clear: both;"></div>
	<p class="author hastip" title="Редактировать профиль"><?php echo $profile['first_name']; ?></p>
	<p class="author hastip" title="Редактировать профиль"><?php echo $profile['last_name']; ?></p>
	<input type="text" class="text" value="<?php echo $profile['first_name']; ?>" placeholder="Имя" id="edit_profile_name" />
	<input type="text" class="text" value="<?php echo $profile['last_name']; ?>" placeholder="Фамилия" id="edit_profile_lastname" />
	<h5>Информация аккаунта</h5>
	<div class="sub_field">
		<p class="profile_hide"><?php echo $profile['email']; ?></p>
		<p class="profile_hide"><?php echo $profile['phone']; ?></p>
		<!--<input type="text" class="text" style="margin-left: 0;" value="<?php echo $profile['email']; ?>" placeholder="Епочта" id="edit_profile_email" />
		<!--<input type="password" class="text" style="margin-left: 0;" placeholder="Новый пароль" id="edit_profile_password" />-->
		<input type="text" class="text" style="margin-left: 0;" value="<?php echo $profile['phone']; ?>" placeholder="Контактный телефон" id="edit_profile_phone" />
		<p><?php echo $profile['group_description']; ?></p>
		<p>Регистрация <?php echo date("d.m.Y",$profile['created_on']); ?></p>
		<p>Крайняя авторизация <?php echo date("d.m.Y",$profile['last_login']); ?></p>
	</div>
	<h5>Общая информация</h5>
	<div class="sub_field load">

	</div>
</div>
<script type="text/javascript">
	$('.hastip').tooltipsy();
</script>

<script type="text/javascript" src="<?php echo base_url('js/jquery.ajax.upload.js'); ?>"></script>
<script type="text/javascript">
$(document).ready(function() {
	var upload = new AjaxUpload('#userfile', {
		//upload script 
		action: '<?php echo base_url('main/upload_avatar'); ?>',
		onSubmit : function(file, extension){
		//show loading animation
		$("#loading").show();
		//check file extension
		if (! (extension && /^(jpg|png|jpeg|gif)$/.test(extension))){
       // extension is not allowed
			 $("#loading").hide();
			 $("<span class='error'>Error: Not a valid file extension</span>").appendTo("#file_holder #errormes");
			// cancel upload
       return false;
			} else {
			  // get rid of error
			$('.error').hide();
			}	
			//send the data
			upload.setData({'file': file});
		},
		onComplete : function(file, response){
			//alert(response);
			$('#userfile').attr('src', '<?php echo base_url('img/avatars'); ?>/' + response);
			$('#header_avatar').attr('src', '<?php echo base_url('img/avatars'); ?>/' + response);
			//$('#userfile').load();
			//alert(response);
			//hide the loading animation
			$("#loading").hide();
			//add display:block to success message holder
			$(".success").css("display", "block");
			
	//This lower portion gets the error message from upload.php file and appends it to our specifed error message block
			//find the div in the iFrame and append to error message	
			var oBody = $(".iframe").contents().find("div");
			//add the iFrame to the errormes td
			$(oBody).appendTo("#file_holder #errormes");
		}
	});
});	
		</script>
</body>
</html>