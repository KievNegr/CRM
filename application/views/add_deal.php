<!--Модальное окно-->

	<div id="modal">
		<div class="title">
			<h2>Добавление сделки</h2>
			<div class="close"></div>
		</div>
		<div style="clear: both;"></div>
		<div class="field">
			<span class="label">Название:</span><input type="text" class="text" placeholder="Название сделки" style="width: 430px;" id="ajax_name_deal"/>
		</div>

		<div class="field">
			<span class="label">Компания:</span>
			<?php
					//По умолчанию загрузим контактные лица для первой конторы
					$id_load_clients = '';
				?>
				<input type="text" 
					id="ajax_company" 
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
			<div style="clear: both; height: 20px;"></div>
			<span class="label">Контактное лицо:</span><br />
			<input type="text" 
					id="ajax_client" 
					style="width: 430px; margin-right: 0;"
					autocomplete ="off"
					class="text" 
					placeholder="Контактное лицо" 
					data-provide="typeahead" 
					data-items="20" 
					data-source='[
						<?php
							$str = '';
							foreach( $all_clients as $list_clients )
							{
								$str = '"' . $list_clients['fio'] .  '",' . $str;
							}
							echo  substr($str, 0, strlen($str) - 1) ;
						?>
					]'>
		</div>
		<div style="clear: both;"></div>
		<div class="field">
			<span class="label">Бюджет:</span><input type="text" placeholder="Бюджет сделки в €" class="text" style="width: 430px;" id="ajax_budget"/>
		</div>
		
		<div class="field">
			<select class="select" id="ajax_status_deal">
				<?php
					foreach( $state_deal as $list_deal )
					{
						echo '<option value="' . $list_deal['id'] . '">' . $list_deal['value'] . '</option>';
					}
				?>
			</select>
		</div>
		
		<div class="field">
			<span class="label">Описание сделки:</span>
			<textarea class="add_event" style="width: 465px; height: 100px; "id="ajax_event_deal"></textarea>
		</div>

		<button class="file_btn">Приложить файл</button>
		<input type="file" class="add_file" value="" />
		<button class="save" id="ajax_save_deal">Сохранить</button>

	</div><!--//Modal-->