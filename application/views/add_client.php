	<script type="text/javascript">	
		$('#name_comp .text').typeahead({
			hint: true,
			highlight: true,
			minLength: 1
		}
		
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

	<div id="modal">
		<div class="title">
			<h2>Добавление новой компании</h2>
			<div class="close"></div>
		</div>
		<div style="clear: both;"></div>
		<div class="field" id="name_comp">
			<span class="label">Название:</span>
			<input type="text" 
					id="ajax_name_company" 
					style="width: 430px; margin-right: 0;"
					autocomplete ="off"
					class="text" 
					placeholder="Название компании" 
					data-provide="typeahead" 
					data-items="20" 
					data-source='[
						<?php
							$str = '';
							foreach( $all_company as $list_company )
							{
								$str = '"' . $list_company['name'] .  '",' . $str;
							}
							echo  substr($str, 0, strlen($str) - 1) ;
						?>
					]'>
		</div>
		<div style="clear: both;"></div>
		<div class="field">
			<span class="label">Контакты:</span>
			<input type="text" 
					id="ajax_country_company" 
					style="width: 430px;"
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
					id="ajax_city_company" 
					style="width: 430px; margin-top: 10px;"
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
				
				<input type="text" class="text" placeholder="Улица, Дом" style="width: 430px; margin-top: 10px;" id="ajax_adres_company"/>
				<input type="text" class="text phone" style="width: 440px; padding: 1px 0 0 30px; background-position: 8px 8px; margin-top: 10px;" id="ajax_phone_company" />
				<input type="text" class="text skype" style="width: 440px; padding: 1px 0 0 30px; background-position: 8px 8px;" id="ajax_skype_company" />
				<input type="text" class="text at" style="width: 440px; padding: 1px 0 0 30px; background-position: 8px 8px;" id="ajax_email_company" />
				<input type="text" class="text www" style="width: 440px; padding: 1px 0 0 30px; background-position: 8px 8px;" id="ajax_www_company" />
		</div>
		<div style="clear: both;"></div>
		<div class="field">
			<span class="label">Описание:</span>
			<textarea class="textarea" id="ajax_desc_company" style="width: 467px;"></textarea>
		</div>
		<div style="clear: both;"></div>
		<button class="file_btn">Приложить файл</button>
		<input type="file" class="add_file" value="" />
		<button class="save" id="ajax_save_company">Сохранить</button>

	</div><!--//Modal-->