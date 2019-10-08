	<script type="text/javascript">	
		$('#country_comp .text').typeahead({
			hint: true,
			highlight: true,
			minLength: 1
		}
		
		$('#city_comp .text').typeahead({
			hint: true,
			highlight: true,
			minLength: 1
		}
	</script>
	
	<!--Модальное окно-->

	<div id="modal">
		<div class="title">
			<h2>Новое контактное лицо</h2>
			<div class="close"></div>
		</div>
		<div style="clear: both;"></div>
		<div class="field">
			<span class="label">Компания:</span>
			<input type="text" class="text" value='<?php echo stripslashes($data_clients['name']); ?>' disabled style="width: 430px; margin-bottom: 10px;" />
			<input type="hidden" id="ajax_select_client_company" value="<?php echo $data_clients['id']; ?>">
		</div>
		<div class="field" id="name_comp">
			<span class="label">ФИО:</span>
			<input type="text" class="text" style="width: 430px;" id="ajax_name_client"/>
		</div>
		<div class="field">
			<span class="label">Должность:</span>
			<input type="text" class="text" placeholder="Должность" style="width: 430px; margin-bottom: 10px;" id="ajax_doljnost_client" />
		</div>
		<div style="clear: both;"></div>
		<div class="field">

			<span class="label">Контакты:</span>
			<input type="text" 
				id="ajax_client_country" 
				style="width: 430px; margin-bottom: 10px;"
				autocomplete ="off"
				class="text" 
				placeholder="Страна" 
				data-provide="typeahead" 
				data-items="20" 
				data-source='[
					<?php
						$str = '';
						foreach( $all_country as $list_country )
						{
							$str ='"' . $list_country['country'] . '", ' . $str;
						}
						echo substr($str, 0, strlen($str) - 2);
					?>
			]'>
			
			<input type="text" 
				id="ajax_client_city" 
				style="width: 430px; margin-bottom: 10px;"
				autocomplete ="off"
				class="text" 
				placeholder="Город" 
				data-provide="typeahead" 
				data-items="20" 
				data-source='[
					<?php
						$str = '';
							foreach( $all_city as $list_city )
						{
							$str ='"' . $list_city['city'] . '", ' . $str;
						}
						echo substr($str, 0, strlen($str) - 2);
					?>
			]'>
			
			<input type="text" class="text" placeholder="Улица, Дом" style="width: 430px; margin-bottom: 10px;" id="ajax_adres_client"/>
			<input type="text" class="text phone" style="width: 440px; padding: 1px 0 0 30px; background-position: 8px 8px;" id="ajax_phone_client" />
			<input type="text" class="text skype" style="width: 440px; padding: 1px 0 0 30px; background-position: 8px 8px;" id="ajax_skype_client" />
			<input type="text" class="text at" style="width: 440px; padding: 1px 0 0 30px; background-position: 8px 8px;" id="ajax_email_client" />
			<input type="text" class="text www" style="width: 440px; padding: 1px 0 0 30px; background-position: 8px 8px;" id="ajax_www_client" />
		</div>
		<div style="clear: both;"></div>

		<button class="file_btn">Приложить файл</button>

		<input type="file" class="add_file" value="" />

		<button class="save" id="ajax_save_client">Сохранить</button>

	</div><!--//Modal-->