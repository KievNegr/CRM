<input type="hidden" id="id-client" value="<?php echo $data_clients['id']; ?>" />
<div id="content">
			<div class="page-info">	
				<ul id="ul-info">
					<li><h1>Контактное лицо</h1></li>
					<li style="padding: 4px 20px 0 0; height: 23px;"><a href="<?php echo base_url('clients'); ?>" <?php echo $subMenu['my']; ?>>Мои</a> <a href="<?php echo base_url('clients/all'); ?>" <?php echo $subMenu['all']; ?>>Все</a></li>
					<li>
						<button class="add_new">+ Добавить клиента</button>
						<button class="func_button" id="ajax-save-client">Сохранить</button>
						<button class="cancel">Отменить</button>
					</li>
				</ul>
				<div id="modal">
					<div class="left" style="width: 46%; margin: 0;">
						<h5>Контактное лицо</h5>
						<div class="sub_field">
							<input type="text" id="ajax-name-client" autocomplete ="off" class="client text" placeholder="Имя фамилия" />
							<input type="text" id="ajax-doljnost-client" autocomplete ="off" class="text job" placeholder="Должность" />
							<input type="text" id="ajax-phone-client" autocomplete ="off" class="text phone" placeholder="Контактный телефон"/>
							<input type="text" id="ajax-skype-client" autocomplete ="off" class="text skype" placeholder="Skype"/>
							<input type="text" id="ajax-email-client" autocomplete ="off" class="text at" placeholder="E-mail" />
							<input type="text" id="ajax-adres-client" autocomplete ="off" class="text street" placeholder="адрес" />
						</div>
					</div>
					<div class="middle"></div>
					<div class="right" style="width: 46%;">		
						<h5>Компания</h5>
						<div class="sub_field" style="margin: 0 auto; padding: 0; background: none;">
							<input type="text" 
								id="ajax-name-company" 
								autocomplete ="off"
								class="text company"
								placeholder="Добавить название компании"
								data-provide="typeahead" 
								data-items="20" 
								>
							<input type="text" id="ajax-phone-company" autocomplete ="off" class="text phone" placeholder="Контактный телефон" />
							<input type="text" id="ajax-skype-company" autocomplete ="off" class="text skype" placeholder="Skype" />
							<input type="text" id="ajax-email-company" autocomplete ="off" class="text at" placeholder="E-mail" />
							<input type="text" id="ajax-www-company" autocomplete ="off" class="text www" placeholder="www" />
							<input type="text" id="ajax-adres-company" autocomplete ="off" class="text street" placeholder="Добавить адрес" />
						</div>
					</div>					
				</div>
			</div>
			
			<div class="title-column">
				<h2 class="edit-fio">
					<?php 
						echo $data_clients['fio'];
					?>
				</h2>
					<?php	
						if( $profile['id'] == $data_clients['author_id'] )
						{
							echo '<div class="edit" id-edit="">Редактировать</div>';
						}
					?>
			</div>
			<div class="block-comments">
					<textarea id="description" placeholder="Добавить сообщение" class="textarea" style="margin: 0 0 0 px; width: 83%; padding: 5px 3%;"></textarea>
					<button class="save" id="save-event" style="float: left; margin: 3px 0 0 0;">Сохранить</button>
				<div style="clear: both;"></div>
				<?php
					krsort($data_clients['event']);
					$data_clients['event'] = array_values($data_clients['event']);

					foreach( $data_clients['event'] as $list_event )
					{
						if( !empty($list_event['id_deal']) || $list_event['id_deal'] != 0 )
						{
							$deal = $this->deal_md->get_deal_name($list_event['id_deal']);
							$adding_text = '<p class="small">В сделке: <a href="' . base_url('deal/show_deal/' . $list_event['id_deal']) . '">' . $deal . '</a></p>';
						}
						else
						{
							$adding_text = '';
						}

						$dt = (strtotime(date('Y-m-d')) - strtotime(substr($list_event['date'], 0, 10)))/(3600*24);
											
						if( $dt == 0 ){
							$data = 'Сегодня';
							$arr_today[] = Array(
								'author' => $list_event['author'],
								'id_author' => $list_event['author_id'],
								'id_event' => $list_event['id_event'],
								'description' => $list_event['description'],
								'data' => $data . ' в ' . substr($list_event['date'], -8, 5),
								'adding_text' => $adding_text,
								'sub' => $list_event['sub_event']
								);
						}else{
							$data = substr($list_event['date'], 8, 2 ) . '.' . substr($list_event['date'], 5, 2 ) . '.' . substr($list_event['date'], 0, 4 );
							$arr_other[] = Array(
								'author' => $list_event['author'],
								'id_author' => $list_event['author_id'],
								'id_event' => $list_event['id_event'],
								'description' => $list_event['description'],
								'data' => $data . ' в ' . substr($list_event['date'], -8, 5),
								'adding_text' => $adding_text,
								'sub' => $list_event['sub_event']
								);
						}
					}
				?>
				<?php
					if( !empty($arr_today) )
					{
						echo '<div id="today">';
						foreach( $arr_today as $l_event )
						{
							echo '<div class="event_transfer' . $l_event['id_event'] . '">';
							echo '<div class="show_event">';
							if( $profile['id'] == $l_event['id_author'] )
							{
								echo '<div class="functional-event-deal">';
									echo '<div class="edit-event" id_event="' . $l_event['id_event'] . '"></div>';
									echo '<div class="del-event" id_event="' . $l_event['id_event'] . '"></div>';
							  	echo '</div>';
							}
							echo '<div class="author-info">';
								echo '<div class="comment-avatar" style="background-image: url(' . base_url('img/avatar.jpg') . ');"></div>';
								echo '<p class="author">' . $l_event['author'] . ', ' . $l_event['data'] . '</p>';
								echo '<p class="doljnost">Developer</p>';
							echo '</div>';
							echo '<div class="text-comment">' . $l_event['description'] . '</div>';
							echo '</div>';

							if( $l_event['sub'] != 0 )
							{
							 	foreach( $l_event['sub'] as $sub_res )
							 	{
							 		$sub_dt = (strtotime(date('Y-m-d')) - strtotime(substr($sub_res['date'], 0, 10)))/(3600*24);
									if( $sub_dt == 0 ){
										$sub_data = 'Сегодня в ' . substr($sub_res['date'], 11, 5);
									}elseif( $sub_dt == 1 ){
										$sub_data = 'Вчера в ' . substr($sub_res['date'], 11, 5);
									}else{
										$sub_data = substr($sub_res['date'], 8, 2 ) . '.' . substr($sub_res['date'], 5, 2 ) . '.' . substr($sub_res['date'], 0, 4 ) . ' в ' . substr($sub_res['date'], 11, 5);;
									}

							 		echo '<div class="sub_event subevent' . $sub_res['id_event'] . '">';
							 			if( $profile['id'] == $sub_res['author_id'] )
										{
								 			echo '<div class="functional-sub-event-deal">';
												echo '<div class="edit-sub-event" id_event="' . $sub_res['id_event'] . '"></div>';
												echo '<div class="del-sub-event" id_event="' . $sub_res['id_event'] . '"></div>';
										  	echo '</div>';
										}
										echo '<div class="author-info">';
											echo '<div class="comment-avatar" style="background-image: url(' . base_url('img/avatar.jpg') . ');"></div>';
											echo '<p class="author">' . $sub_res['author'] . ', ' . $sub_data . '</p>';
											echo '<p class="doljnost">Developer</p>';
										echo '</div>';
						
	
										echo '<div class="text-response-comment">' . $sub_res['description'] . '</div>';
							 		echo '</div>';
							 	}
							}
						}
						echo '</div>';
						echo '</div>';
					}
					else
					{
						echo '<div id="today"></div>';
					}

					if( !empty($arr_other) )
					{
						foreach( $arr_other as $l_event )
						{
							echo '<div class="event_transfer' . $l_event['id_event'] . '">';
							echo '<div class="show_event">';
							if( $profile['id'] == $l_event['id_author'] )
							{
								echo '<div class="functional-event-deal">';
									echo '<div class="edit-event" id_event="' . $l_event['id_event'] . '"></div>';
									echo '<div class="del-event" id_event="' . $l_event['id_event'] . '"></div>';
							  	echo '</div>';
							}
							echo '<div class="author-info">';
								echo '<div class="comment-avatar" style="background-image: url(' . base_url('img/avatar.jpg') . ');"></div>';
								echo '<p class="author">' . $l_event['author']['name'] . ', ' . $l_event['data'] . '</p>';
								echo '<p class="doljnost">Developer</p>';
							echo '</div>';
							echo '<div class="text-comment">' . $l_event['description'] . '</div>';
							echo '</div>';

							if( $l_event['sub'] != 0 )
							{
							 	foreach( $l_event['sub'] as $sub_res )
							 	{
							 		$sub_dt = (strtotime(date('Y-m-d')) - strtotime(substr($sub_res['date'], 0, 10)))/(3600*24);
									if( $sub_dt == 0 ){
										$sub_data = 'Сегодня в ' . substr($sub_res['date'], 11, 5);
									}elseif( $sub_dt == 1 ){
										$sub_data = 'Вчера в ' . substr($sub_res['date'], 11, 5);
									}else{
										$sub_data = substr($sub_res['date'], 8, 2 ) . '.' . substr($sub_res['date'], 5, 2 ) . '.' . substr($sub_res['date'], 0, 4 ) . ' в ' . substr($sub_res['date'], 11, 5);;
									}

							 		echo '<div class="sub_event subevent' . $sub_res['id_event'] . '">';
							 			if( $profile['id'] == $sub_res['author_id'] )
										{
								 			echo '<div class="functional-sub-event-deal">';
												echo '<div class="edit-sub-event" id_event="' . $sub_res['id_event'] . '"></div>';
												echo '<div class="del-sub-event" id_event="' . $sub_res['id_event'] . '"></div>';
										  	echo '</div>';
										}
										echo '<div class="author-info">';
											echo '<div class="comment-avatar" style="background-image: url(' . base_url('img/avatar.jpg') . ');"></div>';
											echo '<p class="author">' . $sub_res['author']['name'] . ', ' . $sub_data . '</p>';
											echo '<p class="doljnost">Developer</p>';
										echo '</div>';
						
	
										echo '<div class="text-response-comment">' . $sub_res['description'] . '</div>';
							 		echo '</div>';
							 	}
							}
							echo '</div>';
						}
						
					}
				?>

			</div>
			<div class="block-info">
				<div class="info-field">
					<h3>Контакты</h3>
					<?php
						if( !empty( $data_clients['doljnost'] ) || $data_clients['doljnost'] != 0 )
						{
							$doljnost = $data_clients['doljnost'];
						}
						else
						{
							$doljnost = '<span style="opacity: .6">Добавить должность</span>';
						}
					?>
					<p class="info-p job edit-doljnost"><?php echo $doljnost; ?></p>
					<?php
						if(  !empty( $data_clients['phone'] ) || $data_clients['phone'] != 0 )
						{
							$phone = stripslashes($data_clients['phone']);
						}
						else
						{
							$phone = '<span style="opacity: .6">Добавить телефоны</span>';
						}
					?>
					<p class="info-p phone edit-phone"><?php echo $phone; ?></p>
					<?php
						if(  !empty( $data_clients['email'] ) || $data_clients['email'] != 0 )
						{
							$email = '<a href="mailto:' . $data_clients['email'] . '">' . $data_clients['email'] . '</a>';
						}
						else
						{
							$email = '<span style="opacity: .6">Добавить e-mail</span>';
						}
					?>
					<p class="info-p at edit-at"><?php echo $email; ?></p>
					<?php
						if( !empty( $data_clients['skype'] ) || $data_clients['skype'] != 0 )
						{
							$skype = '<a href="skype:' . $data_clients['skype'] . '?chat">' . $data_clients['skype'] . '</a>';
						}
						else
						{
							$skype = '<span style="opacity: .6">Добавить skype</span>';
						}
					?>
					<p class="info-p skype edit-skype"><?php echo $skype; ?></p>
					<?php
						if(  !empty( $data_clients['adres'] ) || $data_clients['adres'] != 0 )
						{
							$adres = $data_clients['adres'];
						}
						else
						{
							$adres = '<span style="opacity: .6">Добавить адрес</span>';
						}
					?>
					<p class="info-p street edit-street"><?php echo $adres; ?></p>
				</div>

				<div class="info-field">
					<h3>Компания</h3>
					<?php
						if( !empty( $data_clients['company_id'] ) || $data_clients['company_id'] != 0 )
						{
							$company = '<a href="' . base_url('clients/company/' . $data_clients['company_id']) . '">' . stripslashes($data_clients['company_name']) . '</a>';
							}
						else
						{
							$company = '<span style="opacity: .6">Добавить компанию</span>';
						}
					?>
					<p class="info-p busines edit-company"><?php echo $company; ?></p>
				</div>					
					

				<div class="info-field">
					<h3>Сделки</h3>
					<?php
						if( !empty($data_clients['deals']) ):
							foreach( $data_clients['deals'] as $client_deal ):
					?>
					<p class="info-p budget"><a href="<?php echo base_url('deals/deal/' . $client_deal['id']); ?>"><?php echo $client_deal['name_deal']; ?></a></p>
					<?php
							endforeach;
						else:
					?>
					<p class="info-p budget">Сделки отсутствуют</p>
					<?php
						endif;
					?>
					
				</div>

				<div class="info-field">
					<h3>Администратор</h3>
					<p class="info-p"><?php echo $data_clients['author']; ?></p>
				</div>
			</div>
			
		</div>

		<?php if( $profile['id'] == $data_clients['author_id'] ): ?>
		<div id="edit-field">
			<div class="close">×</div>
			<p>Редакторивать контактное лицо</p>
			<div class="sub_field">
				<input type="text" id="edit-name-client" autocomplete ="off" class="client text" placeholder="Имя фамилия" value="<?php echo $data_clients['fio']; ?>" />
				<input type="text" id="edit-doljnost-client" autocomplete ="off" class="text job" placeholder="Должность" value="<?php echo $data_clients['doljnost']; ?>" />
				<input type="text" id="edit-phone-client" autocomplete ="off" class="text phone" placeholder="Контактный телефон" value="<?php echo $data_clients['phone']; ?>" />
				<input type="text" id="edit-skype-client" autocomplete ="off" class="text skype" placeholder="Skype" value="<?php echo $data_clients['skype']; ?>" />
				<input type="text" id="edit-email-client" autocomplete ="off" class="text at" placeholder="E-mail" value="<?php echo $data_clients['email']; ?>" />
				<input type="text" id="edit-adres-client" autocomplete ="off" class="text street" placeholder="адрес" value="<?php echo $data_clients['adres']; ?>" />
				<?php
					if( !empty( $data_clients['company_id'] ) || $data_clients['company_id'] != 0 )
					{
						$company = stripslashes($data_clients['company_name']);
						$company_id = $data_clients['company_id'];
					}
					else
					{
						$company = '';
					}
				?>
				<input type="text" 
					id="edit-client-company" 
					autocomplete ="off"
					class="text busines" 
					placeholder="Добавить компанию" 
					value = '<?php echo $company; ?>'
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
					]'
				>
				<button id="save-edit-client" class="button">Сохранить</button>
			</div>
		</div>
		<?php endif; ?>

		<div id="notify-block">
			<div class="notify error">
				Поле добавления комментариев пустое
			</div>
			<div class="notify save">
				Данные успешно сохранены
			</div>
		</div>