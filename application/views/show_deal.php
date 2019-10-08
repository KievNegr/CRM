
<div id="saveform">
	<button style="background: <?php echo $header; ?>;" class="func_button" id="ajax_save_edit_deal">Сохранить</button>
</div>
<div id="content" style="margin-left: 100px;">
	<?php
		if( isset($_SERVER['HTTP_REFERER']) ):
	?>
	<div id="back" class="default_field" link="<?php echo $_SERVER['HTTP_REFERER']; ?>"></div>
	<?php
		endif;
	?>
	<input type="text" id="title_edit_deal" style="width: 75%; padding-left: 2px;" autocomplete ="off" class="editField h1" placeholder="Добавить название сделки"  value = "<?php echo $deal['title_deal']; ?>" >
	<input type="hidden" id="edit_deal" id_deal="<?php echo $deal['id']; ?>" />
	<div style="clear: both;"></div>
	<div class="right">
		<?php
			if( !empty( $deal['description'] ) || $deal['description'] != 0 )
			{
				$info_deal = $deal['description'];
			}
			else
			{
				$info_deal = '';
			}
		?>
		<textarea placeholder="Добавить описание сделки" id="edit_descr_deal" style="width: 95%; padding: 0; margin: 0;" class="editField"><?php echo $info_deal; ?></textarea>
		<div style="clear: both;"></div>
		<div id="add-task"></div>
		<div id="adding-task-from-deal">
			<input type="text" placeholder="Добавить тему задачи" id="title-task" class="addField" style="margin: 0 0 0 15px; padding: 4px 1% 5px 1%; width: 87%;"/>
			<div class="fieldSelDate">
				<ul id="selDate">
					<li class="selDateActive" deadline="<?php echo date('d.m.Y'); ?>">На сегодня</li>
					<li deadline="<?php echo date('d.m.Y', strtotime("+1 DAY")); ?>">На завтра</li>
					<li deadline="<?php echo date('d.m.Y', strtotime("+2 DAY")); ?>">На послезавтра</li>
					<li class="datecalend"><input disabled type="text" class="date-pick selectDate" value="<?php echo date('d.m.Y'); ?>" placeholder="Выбрать дату"></li>
				</ul>
			</div>
			<div class="clear:both;"></div>
			
			<div class="select_username hastip" title="Выбрать для кого будет назначена задача, если не выбирать то задача назначится себе">Выбрать сотрудника <span class="sum_user"><?php echo count($users); ?></span></div>
			<div style="clear: both;"></div>
			<ul id="adding_user">
				
			</ul>
			<div style="clear:both;"></div>
			<textarea id="new_task_description" placeholder="Добавить сообщение" class="textarea" style="margin: 0 0 0 15px; width: 88%; float: left;"></textarea>
			<div style="clear:both; height: 10px;"></div>
			<input type="hidden" id="id_task_edit" />
			<button class="save" id="ajax_save_new_task_deal" style="float: left; margin: 0 0 0 15px;">Добавить задачу</button>
			<button class="save" id="ajax_save_edit_task_deal" style="float: left; display: none; margin: 0 0 0 15px;">Изменить задачу</button>
			<button class="cancel-response" id="cancel-task-deal" style="margin: 0 0 0 10px;">Отмена</button>
		</div>

		<textarea id="new_event_description" placeholder="Добавить сообщение" class="textarea" style="margin: 0 0 0 10px; width: 88%; float: left;"></textarea>
		<div style="clear:both;"></div>
		<button class="save" id="ajax_save_new_event_deal" style="float: left;">Сохранить</button>
		<button class="cancel-response" id="cancel-event-deal" style="margin: 5px 0 0 10px;">Отмена</button>
		<div style="clear:both; height: 10px;"></div>
		<?php
			krsort($deal['events']);
			$deal['events'] = array_values($deal['events']);
			//Задачи, которые просрочены или дедлайн сегодняшний
			$hot_task = Array();
			foreach( $deal['events'] as $key => $get_task )
			{
				if( $get_task['task'] != 0 )
				{
					$dtDeadline = (strtotime(date('Y-m-d')) - strtotime(substr($get_task['task']['deadline'], 0, 10)))/(3600*24);
					if( $dtDeadline == 0 && $get_task['task']['state'] != 2)
					{
						$hot_task[] = Array(
							'id_task' => $get_task['task']['id_task'],
							'state' => $get_task['task']['state'],
							'result' => $get_task['task']['result'],
							'author' => $get_task['author'],
							'author_id' => $get_task['task']['author_id'],
							'title' => $get_task['task']['title'],
							'description' => $get_task['task']['description'],
							'id_event' => $get_task['id_event'],
							'sub' => $get_task['sub_event'],
							'data_create' => substr($get_task['task']['data'], 8, 2) . '.' . substr($get_task['task']['data'], 5, 2) . '.' . substr($get_task['task']['data'], 0, 4),
							'data' => substr($get_task['task']['deadline'], 8, 2) . '.' . substr($get_task['task']['deadline'], 5, 2) . '.' . substr($get_task['task']['deadline'], 0, 4),
							'task' => $get_task['task'],
							'style' => 'style="background-color:  #FFB903;"'
							);
						unset ($deal['events'][$key]);
					}elseif ( $dtDeadline > 0 && $get_task['task']['state'] != 2 ) {
						$hot_task[] = Array(
							'id_task' => $get_task['task']['id_task'],
							'state' => $get_task['task']['state'],
							'result' => $get_task['task']['result'],
							'author' => $get_task['author'],
							'author_id' => $get_task['task']['author_id'],
							'title' => $get_task['task']['title'],
							'description' => $get_task['task']['description'],
							'id_event' => $get_task['id_event'],
							'sub' => $get_task['sub_event'],
							'data_create' => substr($get_task['task']['data'], 8, 2) . '.' . substr($get_task['task']['data'], 5, 2) . '.' . substr($get_task['task']['data'], 0, 4),
							'data' => substr($get_task['task']['deadline'], 8, 2) . '.' . substr($get_task['task']['deadline'], 5, 2) . '.' . substr($get_task['task']['deadline'], 0, 4),
							'task' => $get_task['task'],
							'style' => 'style="background-color: #EA5722;"'
							);
						unset ($deal['events'][$key]);
					}
				}
			}

			if( !empty($hot_task) )
			{
				echo '<h4 class="hot_task">Невыполненные задачи</h4>';
				echo '<div id="task_hot">';
				foreach( $hot_task as $list_hot_task )
				{
					echo '<div class="transfer' . $list_hot_task['id_task'] . '">';
						echo '<div class="edit-task-deal-clear" id="edit-task-from-deal-' . $list_hot_task['id_task'] . '"></div>';
						echo '<div class="show_event" id="view_task_' . $list_hot_task['id_task'] . '">';
						echo '<div class="functional-task-deal">';
							if( $list_hot_task['state'] != 2)
							{
								echo '';
							}
							else
							{
								echo '<div style="width: 15px; height: 15px; float: left;"></div>';
							}
							if( $profile['id'] == $list_hot_task['author_id'] )
							{
								echo '<div class="edit-task-deal" id_task="' . $list_hot_task['id_task'] .'"></div>';
								echo '<div class="del-task-deal" id_task="' . $list_hot_task['id_task'] . '"></div>';
							}
							
					  	echo '</div>';
					  	$l_users = '';
					  	$ending = '';
					  	$width = '88px';
					  	foreach( $list_hot_task['task']['users'] as $l_temp )
					  	{
					  		$l_users .= $l_temp['name'] . ', ';
					  		if( $l_temp['id_user'] == $profile['id'] )
					  		{
					  			$ending = '<div class="ending-task-deal hastip" title="Завершить задачу" id_task="' . $list_hot_task['id_task'] . '">Завершить</div>';
					  			$width = '160px';
					  		}
					  	}
					  	$l_users = substr($l_users, 0, -2);
						echo '<p class="human author author_edit">' . $list_hot_task['task']['author'] . ', ' . $list_hot_task['data_create'] . '</p>';
						echo '<div class="click-finish-task" ' . $list_hot_task['style'] . '>
								<div class="title-task-deal"><a href="' . base_url('task/show_task/' . $list_hot_task['id_task']) . '" target="_blank">' . $list_hot_task['title'] . '</a></div>
								<div style="float: right; width: ' . $width . ';">
								<div class="deadline">до ' . $list_hot_task['data'] . '</div>
								' . $ending . '
								</div>
								<div style="clear:both;"></div>
								<div class="exec hastip" title="<em>' . $l_users . '</em>">для сотрудников</div>
							</div>';
						$input = nl2br($list_hot_task['task']['description']);
						$repl = str_replace('<br />', ' <br/> ', $input);
						$temp = explode(' ', $repl);
						foreach( $temp as $key => $v_temp )
						{
							$v_temp = trim($v_temp);
							if( substr($v_temp, 0, 4) == 'http' || substr($v_temp, 0, 5) == 'https' )
							{
								$temp[$key] = '<a href="' . $v_temp . '" target="_blank">' . $v_temp . '</a>';
							}
							elseif( substr($v_temp, 0, 3) == 'www' )
							{
								$temp[$key] = '<a href="http://' . $v_temp . '" target="_blank">' . $v_temp . '</a>';
							}
						}
						$temp_out = implode(' ', $temp);
						if( $list_hot_task['state'] != 2)
						{
							echo '<p class="show_edit">' . $temp_out . '</p>';
						}
						else
						{
							echo '<p class="show_edit" style="text-decoration: line-through;">' . $temp_out . '</p>';
							echo '<p class="result" style="margin-left: 23px;">' . $list_hot_task['result'] . '</p>';
						}
						echo '<div id="' . $list_hot_task['id_event'] . '">';
							 if( $list_hot_task['sub'] != 0 )
							 {
							 	foreach( $list_hot_task['sub'] as $sub_res )
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
							 			echo '<p class="human author">' . $sub_res['author'] . ', ' . $sub_data . '</p>';
							 			$input = nl2br($sub_res['description']);
										$repl = str_replace('<br />', ' <br/> ', $input);
										$temp = explode(' ', $repl);
										foreach( $temp as $key => $v_temp )
										{
											$v_temp = trim($v_temp);
											if( substr($v_temp, 0, 4) == 'http' || substr($v_temp, 0, 5) == 'https' )
											{
												$temp[$key] = '<a href="' . $v_temp . '" target="_blank">' . $v_temp . '</a>';
											}
											elseif( substr($v_temp, 0, 3) == 'www' )
											{
												$temp[$key] = '<a href="http://' . $v_temp . '" target="_blank">' . $v_temp . '</a>';
											}
										}
										$temp_out = implode(' ', $temp);
										echo '<p class="p-' . $sub_res['id_event'] . ' p-edit-response">' . $temp_out . '</p>';
										echo '<div class="save-edit-response-' . $sub_res['id_event'] . ' hide" style="margin: 0 0 10px 3%;">
											<textarea class="response_edit_text edit-event-' . $sub_res['id_event'] . '" style="display: block; margin: 5px 0 5px 0;">' . $temp_out . '</textarea>
											<div style="clear: both;"></div>
											<button class="edit-save-response" id_save="' . $sub_res['id_event'] . '">Сохранить</button>
											<button class="edit-cancel-response">Отмена</button>
										</div>';
							 		echo '</div>';
							 	}
							 }
						echo '</div>';
							echo '<div class="response_block" id_event="' . $list_hot_task['id_event'] . '">
								<div class="response" open-class="' . $list_hot_task['id_event'] . '">Добавить комментарий</div>
								<div style="clear:both;"></div>	
								<div class="save-field-response ' . $list_hot_task['id_event'] . '">
									<textarea class="textarea response' . $list_hot_task['id_event'] . '" text-event="' . $list_hot_task['id_event'] . '"></textarea>
									<div style="clear:both;"></div>	
									<button class="save-response" id_save="' . $list_hot_task['id_event'] . '" id_task="' . $list_hot_task['id_task'] . '">Сохранить</button>
									<button class="cancel-response">Отмена</button>
								</div>
							 </div>';
						echo '</div>';
						echo '</div>';	
				}
				echo '</div>';	
				//End hot task
			}
			else
			{
				echo '<h4 id="task_h4" class="hot_task" style="display: none;">Невыполненные задачи</h4>';
				echo '<div id="task_hot"></div>';
			}

			foreach( $deal['events'] as $list_event )
			{
				$dt = (strtotime(date('Y-m-d')) - strtotime(substr($list_event['date'], 0, 10)))/(3600*24);
				if( $dt == 0 ){
					$data = 'Сегодня';
					$arr_today[] = Array(
						'author' => $list_event['author'],
						'id_author' => $list_event['id_author'],
						'description' => $list_event['description'],
						'id_event' => $list_event['id_event'],
						'sub' => $list_event['sub_event'],
						'data' => $data . ' в ' . substr($list_event['date'], 11, 5),
						'task' => $list_event['task']
						);
				}elseif( $dt == 1 ){
					$data = 'Вчера';
					$arr_yesterday[] = Array(
						'author' => $list_event['author'],
						'id_author' => $list_event['id_author'],
						'description' => $list_event['description'],
						'id_event' => $list_event['id_event'],
						'sub' => $list_event['sub_event'],
						'data' => $data . ' в ' . substr($list_event['date'], 11, 5),
						'task' => $list_event['task']
						);
				}else{
					$data = substr($list_event['date'], 8, 2 ) . '.' . substr($list_event['date'], 5, 2 ) . '.' . substr($list_event['date'], 0, 4 );
					$arr_other[] = Array(
						'author' => $list_event['author'],
						'id_author' => $list_event['id_author'],
						'description' => $list_event['description'],
						'data' => $data . ' в ' . substr($list_event['date'], 11, 5),
						'id_event' => $list_event['id_event'],
						'sub' => $list_event['sub_event'],
						'task' => $list_event['task']
						);
				}
			}

			if( !empty($arr_today) )
			{
				echo '<h4>Сегодня</h4>';
				echo '<div id="today">';
				foreach( $arr_today as $l_event )
				{
					if( $l_event['task'] != 0 )
					{
						if( $l_event['task']['state'] != 2 )
						{
							$style = 'style="background-color: #5EA359;"';
						}
						else
						{
							$style = 'style="background-color: #808080;"';
						}
						$transfer = '<div class="transfer' . $l_event['task']['id_task'] . '">';
					}
					else
					{
						$style = '';
						$transfer = '<div class="event_transfer' . $l_event['id_event'] . '">';
					}

					echo $transfer;
						if( $l_event['task'] != 0 )
						{
							echo '<div class="edit-task-deal-clear" id="edit-task-from-deal-' . $l_event['task']['id_task'] . '"></div>';
							echo '<div class="show_event" class="show_event" id="view_task_' . $l_event['task']['id_task'] . '">';
							echo '<div class="functional-task-deal">';
								if( $profile['id'] == $l_event['task']['author_id'] )
								{
									echo '<div class="edit-task-deal" id_task="' . $l_event['task']['id_task'] . '"></div>';
									echo '<div class="del-task-deal" id_task="' . $l_event['task']['id_task'] . '"></div>';
								}
							
						  	echo '</div>';
							echo '<p class="human author author_edit">' . $l_event['task']['author'] . ', ' . substr($l_event['task']['data'], 8, 2 ) . '.' . substr($l_event['task']['data'], 5, 2 ) . '.' . substr($l_event['task']['data'], 0, 4 ) . '</p>';
							if( $l_event['task']['state'] != 2)
							{
								$l_users = '';
								$ending = '';
					  			$width = '88px';
								foreach( $l_event['task']['users'] as $name )
								{
									$l_users .= $name['name'] . ', ';
							  		if( $name['id_user'] == $profile['id'] )
							  		{
							  			$ending = '<div class="ending-task-deal hastip" title="Завершить задачу" id_task="' . $l_event['task']['id_task'] . '">Завершить</div>';
							  			$width = '160px';
							  		}
								}
								$l_users = substr($l_users, 0, -2);
								echo '<div class="click-finish-task" ' . $style . '>
										<div class="title-task-deal"><a href="' . base_url('task/show_task/' . $l_event['task']['id_task']) . '" target="_blank">' . $l_event['task']['title'] . '</a></div>
										<div style="float: right; width: ' . $width . ';">
										<div class="deadline">до ' . substr($l_event['task']['deadline'], 8, 2 ) . '.' . substr($l_event['task']['deadline'], 5, 2 ) . '.' . substr($l_event['task']['deadline'], 0, 4 ) . '</div>
										' . $ending . '
										</div>
										<div style="clear:both;"></div>
										<div class="exec hastip" title="<em>' . $l_users . '</em>">для сотрудников</div>
									</div>';
							}
							else
							{
								$l_users = '';
								foreach( $l_event['task']['users'] as $name )
								{
									$l_users .= $name['name'] . ', ';
								}
								$l_users = substr($l_users, 0, -2);
								echo '<div class="click-finish-task" ' . $style . '>
										<div class="finish-task-deal">' . $l_event['task']['title'] . '</div>
										<div style="float: right; width: 140px;">
										<div class="deadline">Выполнена ' . substr($l_event['task']['data_finish'], 8, 2 ) . '.' . substr($l_event['task']['data_finish'], 5, 2 ) . '.' . substr($l_event['task']['data_finish'], 0, 4 ) . '</div>
										</div>
										<div style="clear:both;"></div>
										<div class="exec hastip" title="<em>' . $l_users . '</em>">для сотрудников</div>
									</div>';
							}
							$input = nl2br($l_event['task']['description']);
						}
						else
						{
							echo '<div class="show_event" ' . $style . '>';
							if( $profile['id'] == $l_event['id_author'] )
							{
								echo '<div class="functional-event-deal">';
									echo '<div class="edit-event" id_event="' . $l_event['id_event'] . '"></div>';
									echo '<div class="del-event" id_event="' . $l_event['id_event'] . '"></div>';
							  	echo '</div>';
							}
							echo '<p class="human author">' . $l_event['author'] . ', ' . $l_event['data'] . '</p>';
							$input = nl2br($l_event['description']);
						}
						$repl = str_replace('<br />', ' <br/> ', $input);
						$temp = explode(' ', $repl);
						foreach( $temp as $key => $v_temp )
						{
							$v_temp = trim($v_temp);
							if( substr($v_temp, 0, 4) == 'http' || substr($v_temp, 0, 5) == 'https' )
							{
								$temp[$key] = '<a href="' . $v_temp . '" target="_blank">' . $v_temp . '</a>';
							}
							elseif( substr($v_temp, 0, 3) == 'www' )
							{
								$temp[$key] = '<a href="http://' . $v_temp . '" target="_blank">' . $v_temp . '</a>';
							}
						}
						$temp_out = implode(' ', $temp);
						$temp_out = str_replace('div>', 'p>', $temp_out);

						if( $l_event['task']['state'] != 2)
						{
							echo '<p class="p-' . $l_event['id_event'] . ' p-edit-response">' . $temp_out . '</p>';
							echo '<div class="save-edit-response-' . $l_event['id_event'] . ' hide" style="margin: 0 0 10px 3%;">
									<textarea class="response_edit_text edit-event-' . $l_event['id_event'] . '" style="display: block; margin: 5px 0 5px 0;">' . $temp_out . '</textarea>
									<div style="clear: both;"></div>
									<button class="edit-save-response" id_save="' . $l_event['id_event'] . '">Сохранить</button>
									<button class="edit-cancel-response">Отмена</button>
								</div>';
						}
						else
						{
							echo '<p style="text-decoration: line-through;">' . $temp_out . '</p>';

							echo '<p class="result" style="margin-left: 23px;">' . $l_event['task']['result'] . '</p>';
						}
						echo '<div id="' . $l_event['id_event'] . '">';
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
							 			echo '<p class="human author">' . $sub_res['author'] . ', ' . $sub_data . '</p>';
							 			$input = nl2br($sub_res['description']);
										$repl = str_replace('<br />', ' <br/> ', $input);
										$temp = explode(' ', $repl);
										foreach( $temp as $key => $v_temp )
										{
											$v_temp = trim($v_temp);
											if( substr($v_temp, 0, 4) == 'http' || substr($v_temp, 0, 5) == 'https' )
											{
												$temp[$key] = '<a href="' . $v_temp . '" target="_blank">' . $v_temp . '</a>';
											}
											elseif( substr($v_temp, 0, 3) == 'www' )
											{
												$temp[$key] = '<a href="http://' . $v_temp . '" target="_blank">' . $v_temp . '</a>';
											}
										}
										$temp_out = implode(' ', $temp);
										echo '<p class="p-' . $sub_res['id_event'] . ' p-edit-response">' . $temp_out . '</p>';
										echo '<div class="save-edit-response-' . $sub_res['id_event'] . ' hide" style="margin: 0 0 10px 3%;">
											<textarea class="response_edit_text edit-event-' . $sub_res['id_event'] . '" style="display: block; margin: 5px 0 5px 0;">' . $temp_out . '</textarea>
											<div style="clear: both;"></div>
											<button class="edit-save-response" id_save="' . $sub_res['id_event'] . '">Сохранить</button>
											<button class="edit-cancel-response">Отмена</button>
										</div>';
							 		echo '</div>';
							 	}
							 }
						echo '</div>';
							 echo '<div class="response_block" id_event="' . $l_event['id_event'] . '">
								<div class="response" open-class="' . $l_event['id_event'] . '">Добавить комментарий</div>
								<div style="clear:both;"></div>	
								<div class="save-field-response ' . $l_event['id_event'] . '">
									<textarea class="textarea response' . $l_event['id_event'] . '" text-event="' . $l_event['id_event'] . '"></textarea>
									<div style="clear:both;"></div>	
									<button class="save-response" id_save="' . $l_event['id_event'] . '" id_task="' . $l_event['task']['id_task'] . '">Сохранить</button>
									<button class="cancel-response">Отмена</button>
								</div>
							 </div>';
					echo '</div>';
					echo '</div>';
				}
				echo '</div>';
			}
			else
			{
				echo '<h4 id="ev_h4" style="display: none;">Сегодня</h4>';
				echo '<div id="today"></div>';
			}

			if( !empty($arr_yesterday) )
			{
				echo '<h4>Вчера</h4>';
				echo '<div id="yesterday">';
				foreach( $arr_yesterday as $l_event )
				{
					if( $l_event['task'] != 0 )
					{
						if( $l_event['task']['state'] != 2 )
						{
							$style = 'style="background-color: #5EA359;"';
						}
						else
						{
							$style = 'style="background-color: #808080;"';
						}
						$transfer = '<div class="transfer' . $l_event['task']['id_task'] . '">';
					}
					else
					{
						$style = '';
						$transfer = '<div class="event_transfer' . $l_event['id_event'] . '">';
					}

					echo $transfer;
						if( $l_event['task'] != 0 )
						{
							echo '<div class="edit-task-deal-clear" id="edit-task-from-deal-' . $l_event['task']['id_task'] . '"></div>';
							echo '<div class="show_event" class="show_event" id="view_task_' . $l_event['task']['id_task'] . '">';
								echo '<div class="functional-task-deal">';
									if( $l_event['task']['state'] != 2)
									{
										echo '<div class="ending-task-deal" id_task="' . $l_event['task']['id_task'] . '"></div>';
									}
									else
									{
										echo '<div style="width: 15px; height: 15px; float: left;"></div>';
									}
									if( $profile['id'] == $l_event['task']['author_id'] )
									{
										echo '<div class="edit-task-deal" id_task="' . $l_event['task']['id_task'] . '"></div>';
										echo '<div class="del-task-deal" id_task="' . $l_event['task']['id_task'] . '"></div>';
									}
								
							  	echo '</div>';
								echo '<p class="human author author_edit">' . $l_event['task']['author'] . ', ' . substr($l_event['task']['data'], 8, 2 ) . '.' . substr($l_event['task']['data'], 5, 2 ) . '.' . substr($l_event['task']['data'], 0, 4 ) . '</p>';
								if( $l_event['task']['state'] != 2)
								{
									$l_users = '';
									$ending = '';
						  			$width = '88px';
									foreach( $l_event['task']['users'] as $name )
									{
										$l_users .= $name['name'] . ', ';
								  		if( $name['id_user'] == $profile['id'] )
								  		{
								  			$ending = '<div class="ending-task-deal hastip" title="Завершить задачу" id_task="' . $l_event['task']['id_task'] . '">Завершить</div>';
								  			$width = '160px';
								  		}
									}
									$l_users = substr($l_users, 0, -2);
									echo '<div class="click-finish-task" ' . $style . '>
											<div class="title-task-deal"><a href="' . base_url('task/show_task/' . $l_event['task']['id_task']) . '" target="_blank">' . $l_event['task']['title'] . '</a></div>
											<div style="float: right; width: ' . $width . ';">
											<div class="deadline">до ' . substr($l_event['task']['deadline'], 8, 2 ) . '.' . substr($l_event['task']['deadline'], 5, 2 ) . '.' . substr($l_event['task']['deadline'], 0, 4 ) . '</div>
											' . $ending . '
											</div>
											<div style="clear:both;"></div>
											<div class="exec hastip" title="<em>' . $l_users . '</em>">для сотрудников</div>
										</div>';
								}
								else
								{
									$l_users = '';
									foreach( $l_event['task']['users'] as $name )
									{
										$l_users .= $name['name'] . ', ';
									}
									$l_users = substr($l_users, 0, -2);
									echo '<div class="click-finish-task" ' . $style . '>
										<div class="finish-task-deal">' . $l_event['task']['title'] . '</div>
										<div style="float: right; width: 140px;">
										<div class="deadline">Выполнена ' . substr($l_event['task']['data_finish'], 8, 2 ) . '.' . substr($l_event['task']['data_finish'], 5, 2 ) . '.' . substr($l_event['task']['data_finish'], 0, 4 ) . '</div>
										</div>
										<div style="clear:both;"></div>
										<div class="exec hastip" title="<em>' . $l_users . '</em>">для сотрудников</div>
									</div>';
								}
							$input = nl2br($l_event['task']['description']);
						}
						else
						{
							echo '<div class="show_event" ' . $style . '>';
							if( $profile['id'] == $l_event['id_author'] )
							{
								echo '<div class="functional-event-deal">';
									echo '<div class="edit-event" id_event="' . $l_event['id_event'] . '"></div>';
									echo '<div class="del-event" id_event="' . $l_event['id_event'] . '"></div>';
							  	echo '</div>';
							}
							echo '<p class="human author">' . $l_event['author'] . ', ' . $l_event['data'] . '</p>';
							$input = nl2br($l_event['description']);
						}
						$repl = str_replace('<br />', ' <br/> ', $input);
						$temp = explode(' ', $repl);
						foreach( $temp as $key => $v_temp )
						{
							$v_temp = trim($v_temp);
							if( substr($v_temp, 0, 4) == 'http' || substr($v_temp, 0, 5) == 'https' )
							{
								$temp[$key] = '<a href="' . $v_temp . '" target="_blank">' . $v_temp . '</a>';
							}
							elseif( substr($v_temp, 0, 3) == 'www' )
							{
								$temp[$key] = '<a href="http://' . $v_temp . '" target="_blank">' . $v_temp . '</a>';
							}
						}
						$temp_out = implode(' ', $temp);
						$temp_out = str_replace('div>', 'p>', $temp_out);

						if( $l_event['task']['state'] != 2)
						{
							echo '<p class="p-' . $l_event['id_event'] . ' p-edit-response">' . $temp_out . '</p>';
							echo '<div class="save-edit-response-' . $l_event['id_event'] . ' hide" style="margin: 0 0 10px 3%;">
									<textarea class="response_edit_text edit-event-' . $l_event['id_event'] . '" style="display: block; margin: 0 0 5px 0;">' . $temp_out . '</textarea>
									<div style="clear: both;"></div>
									<button class="edit-save-response" id_save="' . $l_event['id_event'] . '">Сохранить</button>
									<button class="edit-cancel-response">Отмена</button>
								</div>';
						}
						else
						{
							echo '<p style="text-decoration: line-through;">' . $temp_out . '</p>';
							echo '<p class="result" style="margin-left: 23px;">' . $l_event['task']['result'] . '</p>';
						}
						echo '<div id="' . $l_event['id_event'] . '">';
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
							 			echo '<p class="human author">' . $sub_res['author'] . ', ' . $sub_data . '</p>';
							 			$input = nl2br($sub_res['description']);
										$repl = str_replace('<br />', ' <br/> ', $input);
										$temp = explode(' ', $repl);
										foreach( $temp as $key => $v_temp )
										{
											$v_temp = trim($v_temp);
											if( substr($v_temp, 0, 4) == 'http' || substr($v_temp, 0, 5) == 'https' )
											{
												$temp[$key] = '<a href="' . $v_temp . '" target="_blank">' . $v_temp . '</a>';
											}
											elseif( substr($v_temp, 0, 3) == 'www' )
											{
												$temp[$key] = '<a href="http://' . $v_temp . '" target="_blank">' . $v_temp . '</a>';
											}
										}
										$temp_out = implode(' ', $temp);
										echo '<p class="p-' . $sub_res['id_event'] . ' p-edit-response">' . $temp_out . '</p>';
										echo '<div class="save-edit-response-' . $sub_res['id_event'] . ' hide" style="margin: 0 0 10px 3%;">
											<textarea class="response_edit_text edit-event-' . $sub_res['id_event'] . '" style="display: block; margin: 5px 0 5px 0;">' . $temp_out . '</textarea>
											<div style="clear: both;"></div>
											<button class="edit-save-response" id_save="' . $sub_res['id_event'] . '">Сохранить</button>
											<button class="edit-cancel-response">Отмена</button>
										</div>';
							 		echo '</div>';
							 	}
							 }
						echo '</div>';
							 echo '<div class="response_block" id_event="' . $l_event['id_event'] . '">
								<div class="response" open-class="' . $l_event['id_event'] . '">Добавить комментарий</div>
								<div style="clear:both;"></div>	
								<div class="save-field-response ' . $l_event['id_event'] . '">
									<textarea class="textarea response' . $l_event['id_event'] . '" text-event="' . $l_event['id_event'] . '"></textarea>
									<div style="clear:both;"></div>	
									<button class="save-response" id_save="' . $l_event['id_event'] . '" id_task="' . $l_event['task']['id_task'] . '">Сохранить</button>
									<button class="cancel-response">Отмена</button>
								</div>
							 </div>';
					echo '</div>';
					echo '</div>';
				}
				echo '</div>';
			}

			if( !empty($arr_other) )
			{
				echo '<h4>Раньше</h4>';
				echo '<div id="other">';
				foreach( $arr_other as $l_event )
				{
					if( $l_event['task'] != 0 )
					{
						if( $l_event['task']['state'] != 2 )
						{
							$style = 'style="background-color: #5EA359;"';
						}
						else
						{
							$style = 'style="background-color: #808080;"';
						}
						$transfer = '<div class="transfer' . $l_event['task']['id_task'] . '">';
					}
					else
					{
						$style = '';
						$transfer = '<div class="event_transfer' . $l_event['id_event'] . '">';
					}

					echo $transfer;
						if( $l_event['task'] != 0 )
						{
							echo '<div class="edit-task-deal-clear" id="edit-task-from-deal-' . $l_event['task']['id_task'] . '"></div>';
							echo '<div class="show_event" class="show_event" id="view_task_' . $l_event['task']['id_task'] . '">';
								echo '<div class="functional-task-deal">';
								if( $profile['id'] == $l_event['task']['author_id'] )
								{
									echo '<div class="edit-task-deal" id_task="' . $l_event['task']['id_task'] . '"></div>';
									echo '<div class="del-task-deal" id_task="' . $l_event['task']['id_task'] . '"></div>';
								}
							
						  	echo '</div>';
							echo '<p class="human author author_edit">' . $l_event['task']['author'] . ', ' . substr($l_event['task']['data'], 8, 2 ) . '.' . substr($l_event['task']['data'], 5, 2 ) . '.' . substr($l_event['task']['data'], 0, 4 ) . '</p>';
							if( $l_event['task']['state'] != 2)
							{
								$l_users = '';
								$ending = '';
					  			$width = '88px';
								foreach( $l_event['task']['users'] as $name )
								{
									$l_users .= $l_temp['name'] . ', ';
							  		if( $l_temp['id_user'] == $profile['id'] )
							  		{
							  			$ending = '<div class="ending-task-deal hastip" title="Завершить задачу" id_task="' . $l_event['task']['id_task'] . '">Завершить</div>';
							  			$width = '160px';
							  		}
								}
								$l_users = substr($l_users, 0, -2);
								echo '<div class="click-finish-task" ' . $style . '>
										<div class="title-task-deal"><a href="' . base_url('task/show_task/' . $l_event['task']['id_task']) . '" target="_blank">' . $l_event['task']['title'] . '</a></div>
										<div style="float: right; width: ' . $width . ';">
										<div class="deadline">до ' . substr($l_event['task']['deadline'], 8, 2 ) . '.' . substr($l_event['task']['deadline'], 5, 2 ) . '.' . substr($l_event['task']['deadline'], 0, 4 ) . '</div>
										' . $ending . '
										</div>
										<div style="clear:both;"></div>
										<div class="exec hastip" title="<em>' . $l_users . '</em>">для сотрудников</div>
									</div>';
							}
							else
							{
								$l_users = '';
								foreach( $l_event['task']['users'] as $name )
								{
									$l_users .= $name['name'] . ', ';
								}
								$l_users = substr($l_users, 0, -2);
								echo '<div class="click-finish-task" ' . $style . '>
										<div class="finish-task-deal">' . $l_event['task']['title'] . '</div>
										<div style="float: right; width: 140px;">
										<div class="deadline">Выполнена ' . substr($l_event['task']['data_finish'], 8, 2 ) . '.' . substr($l_event['task']['data_finish'], 5, 2 ) . '.' . substr($l_event['task']['data_finish'], 0, 4 ) . '</div>
										</div>
										<div style="clear:both;"></div>
										<div class="exec hastip" title="<em>' . $l_users . '</em>">для сотрудников</div>
									</div>';
							}
							$input = nl2br($l_event['task']['description']);
						}
						else
						{
							echo '<div class="show_event" ' . $style . '>';
							if( $profile['id'] == $l_event['id_author'] )
							{
								echo '<div class="functional-event-deal">';
									echo '<div class="edit-event" id_event="' . $l_event['id_event'] . '"></div>';
									echo '<div class="del-event" id_event="' . $l_event['id_event'] . '"></div>';
							  	echo '</div>';
							}
							echo '<p class="human author">' . $l_event['author'] . ', ' . $l_event['data'] . '<!--<input disabled type="text" class="date-pick selectDate" value="' . $l_event['data'] . '" placeholder="Выбрать дату">--></p>';
							$input = nl2br($l_event['description']);
						}
						$repl = str_replace('<br />', ' <br/> ', $input);
						$temp = explode(' ', $repl);
						foreach( $temp as $key => $v_temp )
						{
							$v_temp = trim($v_temp);
							if( substr($v_temp, 0, 4) == 'http' || substr($v_temp, 0, 5) == 'https' )
							{
								$temp[$key] = '<a href="' . $v_temp . '" target="_blank">' . $v_temp . '</a>';
							}
							elseif( substr($v_temp, 0, 3) == 'www' )
							{
								$temp[$key] = '<a href="http://' . $v_temp . '" target="_blank">' . $v_temp . '</a>';
							}
						}
						$temp_out = implode(' ', $temp);
						$temp_out = str_replace('div>', 'p>', $temp_out);

						if( $l_event['task']['state'] != 2)
						{
							echo '<p class="p-' . $l_event['id_event'] . ' p-edit-response">' . $temp_out . '</p>';
							echo '<div class="save-edit-response-' . $l_event['id_event'] . ' hide" style="margin: 0 0 10px 3%;">
									<textarea class="response_edit_text edit-event-' . $l_event['id_event'] . '" style="display: block; margin: 0 0 5px 0;">' . $temp_out . '</textarea>
									<div style="clear: both;"></div>
									<button class="edit-save-response" id_save="' . $l_event['id_event'] . '">Сохранить</button>
									<button class="edit-cancel-response">Отмена</button>
								</div>';
						}
						else
						{
							echo '<p style="text-decoration: line-through;">' . $temp_out . '</p>';
							echo '<p class="result" style="margin-left: 23px;">' . $l_event['task']['result'] . '</p>';
						}
						echo '<div id="' . $l_event['id_event'] . '">';
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
							 			echo '<p class="human author">' . $sub_res['author'] . ', ' . $sub_data . '</p>';
							 			$input = nl2br($sub_res['description']);
										$repl = str_replace('<br />', ' <br/> ', $input);
										$temp = explode(' ', $repl);
										foreach( $temp as $key => $v_temp )
										{
											$v_temp = trim($v_temp);
											if( substr($v_temp, 0, 4) == 'http' || substr($v_temp, 0, 5) == 'https' )
											{
												$temp[$key] = '<a href="' . $v_temp . '" target="_blank">' . $v_temp . '</a>';
											}
											elseif( substr($v_temp, 0, 3) == 'www' )
											{
												$temp[$key] = '<a href="http://' . $v_temp . '" target="_blank">' . $v_temp . '</a>';
											}
										}
										$temp_out = implode(' ', $temp);
										echo '<p class="p-' . $sub_res['id_event'] . ' p-edit-response">' . $temp_out . '</p>';
										echo '<div class="save-edit-response-' . $sub_res['id_event'] . ' hide" style="margin: 0 0 10px 3%;">
											<textarea class="response_edit_text edit-event-' . $sub_res['id_event'] . '" style="display: block; margin: 5px 0 5px 0;">' . $temp_out . '</textarea>
											<div style="clear: both;"></div>
											<button class="edit-save-response" id_save="' . $sub_res['id_event'] . '">Сохранить</button>
											<button class="edit-cancel-response">Отмена</button>
										</div>';
							 		echo '</div>';
							 	}
							 }
						echo '</div>';
							 echo '<div class="response_block" id_event="' . $l_event['id_event'] . '">
								<div class="response" open-class="' . $l_event['id_event'] . '">Добавить комментарий</div>
								<div style="clear:both;"></div>	
								<div class="save-field-response ' . $l_event['id_event'] . '">
									<textarea class="textarea response' . $l_event['id_event'] . '" text-event="' . $l_event['id_event'] . '"></textarea>
									<div style="clear:both;"></div>	
									<button class="save-response" id_save="' . $l_event['id_event'] . '" id_task="' . $l_event['task']['id_task'] . '">Сохранить</button>
									<button class="cancel-response">Отмена</button>
								</div>
							 </div>';
					echo '</div>';
					echo '</div>';
				}
				echo '</div>';
			}
		?>
	</div>

	<div class="left">
		<div class="sub_field">
			<h5>Детали сделки</h5>
			<?php
				if( !empty( $deal['budget'] ) || $deal['budget'] != 0 )
				{
					$budget = $deal['budget'] . ' ' . $currency[$deal['currencyId'] - 1]['attr'];
				}
				else
				{
					$budget = '';
				}
			?>
			<input type="text" id="budget_edit_deal" autocomplete ="off" class="editField budget" placeholder="Бюджет"  value = "<?php echo $budget; ?>" style="width: 40%;">
			<script type="text/javascript">
				$(document).ready(function()
				{
					$('#ajax_currency [value="<?php echo $deal['currencyId'];?>"]').attr("selected", "selected");
				});
			</script>
			<select id="ajax_currency" style="display: none;">
				<?php 
					foreach( $currency as $itemCurrency )
					{
						echo '<option code="' . $itemCurrency['attr'] . '" value="' . $itemCurrency['id'] . '">' . $itemCurrency['value'] . ', ' . $itemCurrency['attr'] . '</option>';
					}
				?>
			</select>
			<div></div>
			<span id="fast_edit_status" class="status" style="margin: 10px 0 0 1px; display: inline-block; background: #<?php echo  $deal['color']; ?>"><?php echo $deal['status_deal']; ?></span>
			<ul id="fast_edit_status_deal">
				<?php
					$id_status = $deal['status_deal_id'];
					foreach( $state_deal as $edit_list )
					{
						if( $edit_list['id'] == $id_status )
						{
							echo '<li class="active" status_id="' . $edit_list['id'] . '" style="background-color: #' . $edit_list['color'] . ';">' . $edit_list['value'] . '</li>';
						}
						else
						{
							echo '<li status_id="' . $edit_list['id'] . '" style="background: #' . $edit_list['color'] . ';">' . $edit_list['value'] . '</li>';
						}
					}
				?>
			</ul>
		</div>
		<div class="sub_field">
			<h5>Участники</h5>
			<p class="p-left client"><?php echo $deal['author']; ?></p>
		</div>
		
		<div class="sub_field">
			<h5>Контактные лица</h5>
			<?php
				echo '<ul id="faces">';
				if( !empty($deal['sub_client']) )
				{
					foreach( $deal['sub_client'] as $list )
					{
						$c = '';
						if( !empty( $list['profile']['id_company'] ) || $list['profile']['id_company'] != 0)
						{
							$c = '<div style="clear:both; height: 6px;"></div><a class="cart_a company" href="' . base_url('clients/show_company/' . $list['profile']['id_company']) . '">Компания</a>';
						}
						echo '<li class="group ' . $list['profile']['id'] . '">
								<span>' . $list['profile']['fio'] . '</span>
								<div class="cut"></div>
								<div class="cart">
									<div style="float: left; margin-right: 30px;">
										<a href="' . base_url('clients/show_contact/' . $list['profile']['id']) . '" class="cart_a">Перейти в профиль</a>
										' . $c . '
										<!--<br />
										<span class="cart_link">Редактировать</span>-->
										<div style="clear:both; height: 6px;"></div>
										<span class="cart_link del-from-deal" id_contact="' . $list['profile']['id'] . '">Открепить</span>
										<div style="clear: both; height: 1px;"></div>
									</div>

									<div>';
										if( !empty($list['profile']['doljnost']) || $list['profile']['doljnost'] != 0)
										{
											echo '<p class="job">' . $list['profile']['doljnost'] . '</p>';
										}
										if( !empty($list['contact']['phone']) || $list['contact']['phone'] != 0)
										{
											echo '<p class="phone">' . $list['contact']['phone'] . '</p>';
										}
										if( !empty($list['contact']['email']) || $list['contact']['email'] != 0)
										{
											echo '<p class="at">' . $list['contact']['email'] . '</p>';
										}
										if( !empty($list['contact']['skype']) || $list['contact']['skype'] != 0)
										{
											echo '<p class="skype">' . $list['contact']['skype'] . '</p>';
										}
									echo '</div>
									<div style="clear: both;"></div>
								</div>
							  </li>';
					}
				}
				echo '</ul>
					  <div id="fade"></div>';
			?>
			<span class="add_new_contact_face" id="newContactFace">Добавить контактное лицо</span>
			<div id="addingFace">
				<input type="text" 
					id="add_contactface_deal" 
					autocomplete ="off"
					class="addFaceField client" 
					placeholder="Добавить контактное лицо" 
					style="margin: 5px 0 0 0;"
					data-provide="typeahead" 
					value ="" 
					data-items="20" 
					data-source='[
						<?php
							$str = '';
							foreach( $all_clients as $list_clients )
							{
								$str = '"' . $list_clients .  '",' . $str;
							}
							echo  substr($str, 0, strlen($str) - 1) ;
						?>
					]'>
				<div style="clear: both; height: 20px;"></div>
				<button id="ajax_save_face_deal" class="btn_sv_face" style="display:none;">Сохранить</button>
				<button id="ajax_cancel_face_deal" class="btn_cncl_face" style="display:none;">Отмена</button>
			</div>
		</div>
		
		<div class="sub_field">
			<h5>Компания</h5>
			<?php
				echo '<ul id="default-deal-contact">';
					
					if(  !empty( $deal['company_id'] ) || $deal['company_id'] != 0 )
					{
						$company = stripslashes($deal['company_name']);
						echo '<li class="def-c default-contact default-company-id-' . $deal['company_id'] . '"><span>' . $company . '</span>';
								echo '<div class="cut"></div>
										<div class="cart">
											<div style="float: left; padding: 1px;">
												<a class="cart_a company" href="' . base_url('clients/show_company/' . $deal['company_id']) . '">Перейти в профиль</a>
												<div style="clear:both; height: 6px;"></div>
												<span class="cart_link del-company-from-deal" id_default_company="' . $deal['company_id'] . '">Открепить</span>
											</div>
										<div style="clear: both;"></div>
									</div>';
							echo '</li>';
					}
					else
					{
						$company = '';
					}
					echo '</ul>';
			?>
			<?php
				if( $deal['company_id'] != 0 )
				{
					$style = 'margin: 0; display: none;';
				}
				else
				{
					$style = 'margin: 0;';
				}
			?>
			<input type="text" 
				id="edit_company_deal" 
				value="<?php echo $company; ?>" 
				autocomplete ="off"
				class="editField busines func" 
				placeholder="Добавить компанию" 
				style="<?php echo $style; ?>"
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
		<?php
			if( $profile['id'] == $deal['author_id'] ):
		?>
			<div class="sub_field">
				<h5>Управление</h5>
				<a href="#" id="del-deal">Удалить сделку</a>
			</div>
		<?php
			endif;
		?>
	</div>
</div>