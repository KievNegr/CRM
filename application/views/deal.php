<input type="hidden" id="id-deal" value="<?php echo $deal['id']; ?>" />
<div id="content">
	<div class="page-info">
		<ul id="ul-info">
			<li><h1>Сделка</h1></li>
			<li style="padding: 4px 20px 0 0; height: 23px;">
				<?php
					if( !empty( $deal['budget'] ) || $deal['budget'] != 0 )
					{
						$budget = 'Бюджет: ' . $deal['budget'] . ' ' . $currency[$deal['currencyId'] - 1]['attr'];
					}
					else
					{
						$budget = '<span style="opacity: .6">Бюджет отсутствует</span>';
					}
				?>
				<span class="show-budget edit-show-budget"><?php echo $budget; ?></span>
			</li>
			<li>
				<button class="add_new">+ Добавить сделку</button>
				<button class="func_button" id="ajax-create-deal">Сохранить</button>
				<button class="cancel">Отменить</button>
			</li>
			<li style="padding: 4px 20px 0 0; height: 23px;">
					<a href="<?php echo base_url('deals');?>" <?php echo $subMenu['active']; ?>>Активные</a>&nbsp;
					<a href="<?php echo base_url('deals/my');?>" <?php echo $subMenu['my']; ?>>Мои</a>&nbsp;
					<?php
						foreach( $subMenu['sub'] as $subItem )
						{
							echo $subItem . '&nbsp;';
						}
					?>
				</li>
			</li>
		</ul>
		<div id="modal">
			<div class="left">
				<input type="text" id="ajax-name-deal" autocomplete="off" class="addField title" placeholder="Добавить название сделки">
				<textarea placeholder="Добавить описание сделки" id="ajax-description-deal" class="addField textarea"></textarea>
				<div class="sub_field">
					<h5>Детали сделки</h5>
					<input type="text" id="ajax-budget" autocomplete="off" class="addField budget" placeholder="Добавить бюджет сделки">
					<div style="clear: both;"></div>
					<span id="fast-ajax-status-deal" class="status" style="margin: 10px 0 0 2px; display: inline-block; background: #52a50e">В разработке</span>
					<ul id="ajax-status-deal">
						<?php
							$id_status = 1;
							foreach( $state_deal as $edit_list )
							{
								if( $edit_list['id'] == $id_status )
								{
									echo '<li class="active" status_id="' . $edit_list['id'] . '" style="background-color: #' . $edit_list['color'] . ';">' . $edit_list['value'] . '</li>';
								}
								else
								{
									echo '<li status_id="' . $edit_list['id'] . '" style="background-color: #' . $edit_list['color'] . ';">' . $edit_list['value'] . '</li>';
								}
							}
						?>				
					</ul>
				</div>
	
				<div class="sub_field">
					<h5>Контактное лицо</h5>
					<input type="text" 
						id="ajax-client" 
						autocomplete ="off"
						class="addField client text" 
						placeholder="Добавить контактное лицо" 
						data-provide="typeahead"
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
				</div>
			</div>
			<div class="right">		
				
			</div>					
		</div>
	</div>
	<div class="title-column">
		<?php
			if( $profile['id'] == $deal['author_id'] )
			{
				echo '<div class="edit" id-edit="">Редактировать</div>';
			}
		?>
		<h2 class="edit-name"><?php echo $deal['title_deal']; ?></h2>
		<div style="clear: both;"></div>
		<p class="edit-description"><?php echo $deal['description']; ?></p>
	</div>
	<div class="block-comments">
		<div id="adding-task-from-deal">
			<input type="text" placeholder="Добавить тему задачи" id="title-task" class="title-task-deal" />
			<div class="fieldSelDate">
				<ul id="selDate">
					<li class="selDateActive" deadline="<?php echo date('d.m.Y'); ?>">На сегодня</li>
					<li deadline="<?php echo date('d.m.Y', strtotime("+1 DAY")); ?>">На завтра</li>
					<li deadline="<?php echo date('d.m.Y', strtotime("+2 DAY")); ?>">На послезавтра</li>
					<li class="datecalend"><input disabled type="text" class="date-pick selectDate" value="<?php echo date('d.m.Y'); ?>" placeholder="Выбрать дату"></li>
				</ul>
			</div>
			<div class="clear:both;"></div>
			
			<div class="select_username hastip" title="Выбрать для кого будет назначена задача, если не выбирать то задача назначится себе">
				Выбрать сотрудника <span class="sum_user"><?php echo count($users); ?></span>
			</div>
			<div style="clear: both;"></div>
			<ul id="adding_user">
				
			</ul>
		</div>
			<div style="clear:both;"></div>
		<div class="select-type-message">
			<div id="add-text" class="active-input-blue"></div>
			<div id="add-task"></div>
		</div>
		<div style="margin-left: 50px;">
			<textarea id="description" placeholder="Добавить сообщение" class="textarea" style="margin: 3px 0 0 -3px; width: 83%; padding: 5px 3%;"></textarea>
		</div>
		<button class="save" id="save-event-deal" style="float: left; margin: 3px 0 0 50px;">Сохранить</button>
		<div style="clear: both;"></div>

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
							'authorName' => $get_task['task']['authorName'],
							'authorDoljnost' => $get_task['task']['authorDoljnost'],
							'authorAvatar' => $get_task['task']['authorAvatar'],
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
							'authorName' => $get_task['task']['authorName'],
							'authorDoljnost' => $get_task['task']['authorDoljnost'],
							'authorAvatar' => $get_task['task']['authorAvatar'],
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
		?>

		
		<?php
			if( !empty($hot_task) ):
				foreach( $hot_task as $list_hot_task ):
					$showTask = FALSE;
				  	foreach( $list_hot_task['task']['users'] as $l_temp )
				  	{
				  		if( $l_temp['id_user'] == $profile['id'] || $list_hot_task['author_id'] == $profile['id'])
				  		{
				  			$showTask = TRUE;
				  		}
				  	}

				  	if( $showTask == TRUE ):
		?>
				
					<div class="event_task<?php echo $list_hot_task['id_task']; ?>">
						<div class="show_event">
						<?php
							if( $profile['id'] == $list_hot_task['author_id'] ):
						?>
							<div class="functional-event-deal">
								<div class="edit-task-deal" id-task="<?php echo $list_hot_task['id_task']; ?>"></div>
								<div class="del-task-deal" id-task="<?php echo $list_hot_task['id_task']; ?>"></div>
							</div>
						<?php
							endif;
						?>
							<div class="author-info">
								<?php
									if( !empty($list_hot_task['task']['authorAvatar']))
									{
										$avatar = 'style="background-image: url(' . base_url('img/avatars/' . $list_hot_task['task']['authorAvatar']) . ');"';
									}
									else
									{
										$avatar = 'style="background-image: url(' . base_url('img/avatars/avatar.jpg') . ');"';
									}
								?>
								<div class="comment-avatar" <?php echo $avatar; ?>></div>
								<p class="author"><?php echo $list_hot_task['task']['authorName'] . ', ' . $list_hot_task['data_create'];?></p>
								<p class="doljnost"><?php echo $list_hot_task['task']['authorDoljnost'];?></p>
							</div>
							<div class="task-info" style="background-color: rgb(232, 92, 92);">
								<a href="<?php echo base_url('tasks/task/' . $list_hot_task['id_task']); ?>" class="deal-title-task"><?php echo $list_hot_task['title']; ?></a>
								<div class="ending-task-deal" id_task="<?php echo $list_hot_task['id_task']; ?>">Завершить</div>
								<div class="deadline">до <?php echo $list_hot_task['data']; ?></div>
							</div>

							<div class="text-comment">
								<?php echo $list_hot_task['task']['description']; ?>
							</div>
						</div>

						<?php
							if( $list_hot_task['sub'] != 0 ):
								foreach( $list_hot_task['sub'] as $sub_res ):
									$sub_dt = (strtotime(date('Y-m-d')) - strtotime(substr($sub_res['date'], 0, 10)))/(3600*24);
									if( $sub_dt == 0 ){
										$sub_data = 'Сегодня в ' . substr($sub_res['date'], 11, 5);
									}elseif( $sub_dt == 1 ){
										$sub_data = 'Вчера в ' . substr($sub_res['date'], 11, 5);
									}else{
										$sub_data = substr($sub_res['date'], 8, 2 ) . '.' . substr($sub_res['date'], 5, 2 ) . '.' . substr($sub_res['date'], 0, 4 ) . ' в ' . substr($sub_res['date'], 11, 5);;
									}
						?>
							 	
						<div class="sub_event subevent<?php echo $sub_res['id_event']; ?>">
						<?php
							if( $profile['id'] == $sub_res['author_id'] ):
						?>
							<div class="functional-sub-event-deal">
								<div class="edit-sub-event" id_event="<?php echo $sub_res['id_event']; ?>"></div>
								<div class="del-sub-event" id_event="<?php echo $sub_res['id_event']; ?>"></div>
							</div>
						<?php
							endif;
						?>
							<div class="author-info">
								<?php
									if( !empty($sub_res['authorAvatar']))
									{
										$avatar = 'style="background-image: url(' . base_url('img/avatars/' . $sub_res['authorAvatar']) . ');"';
									}
									else
									{
										$avatar = 'style="background-image: url(' . base_url('img/avatars/avatar.jpg') . ');"';
									}
								?>
								<div class="comment-avatar" <?php echo $avatar; ?>></div>
								<p class="author"><?php echo $sub_res['authorName'] . ', ' . $sub_data; ?></p>
								<p class="doljnost"><?php echo $sub_res['authorDoljnost'];?></p>
							</div>
							<div class="text-response-comment">
								<?php echo $sub_res['description']; ?>	
							</div>
						</div>
						<?php
								endforeach;
							endif;
						?>
					</div>
		<?php
					endif;
				endforeach;
			endif;
		?>


		<?php
			$arr_today = Array();
			$arr_other = Array();
			foreach( $deal['events'] as $list_event )
			{
				$dt = (strtotime(date('Y-m-d')) - strtotime(substr($list_event['date'], 0, 10)))/(3600*24);
				if( $dt == 0 ){
					$data = 'Сегодня';
					$arr_today[] = Array(
						'authorName' => $list_event['authorName'],
						'authorDoljnost' => $list_event['authorDoljnost'],
						'authorAvatar' => $list_event['authorAvatar'],
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
						'authorName' => $list_event['authorName'],
						'authorDoljnost' => $list_event['authorDoljnost'],
						'authorAvatar' => $list_event['authorAvatar'],
						'id_author' => $list_event['id_author'],
						'description' => $list_event['description'],
						'data' => $data . ' в ' . substr($list_event['date'], 11, 5),
						'id_event' => $list_event['id_event'],
						'sub' => $list_event['sub_event'],
						'task' => $list_event['task']
						);
				}
			}
		?>

		<?php
			if( !empty($arr_today) ):
				echo '<div class="info-data">Сегодня</div>';
				echo '<div id="today"></div>';
				foreach( $arr_today as $l_event ):
		?>

		<?php
			if( $l_event['task'] != 0 ):
				if( $l_event['task']['state'] != 2 )
				{
					$style = 'style="background-color: #5EA359;"';
				}
				else
				{
					$style = 'style="background-color: #808080;"';
				}
		?>
				
				<div class="event_task<?php echo $l_event['task']['id_task']; ?>">
		<?php
			else:
		?>
			<div class="event_transfer<?php echo $l_event['id_event']; ?>">
		<?php
			endif;
		?>
			<div class="show_event"> 
				<?php
					//Если это задача
					if( $l_event['task'] != 0 ):
						if( $profile['id'] == $l_event['task']['author_id'] ):
				?>
					<div class="functional-event-deal">
						<div class="edit-task-deal" id-task="<?php echo $l_event['task']['id_task']; ?>"></div>
						<div class="del-task-deal" id-task="<?php echo $l_event['task']['id_task']; ?>"></div>
					</div>
				<?php
						endif;
					else:
					//Если это просто примечание
						if( $profile['id'] == $l_event['id_author'] ):
				?>
					<div class="functional-event-deal">
						<div class="edit-event" id-event="<?php echo $l_event['id_event']; ?>"></div>
						<div class="del-event" id-event="<?php echo $l_event['id_event']; ?>"></div>
					</div>
				<?php
						endif;
					endif;
				?>
				
				<?php
					//Если это задача
					if( $l_event['task'] != 0 ):
				?>
					<div class="author-info">
						<?php
							if( !empty($l_event['task']['authorAvatar']))
							{
								$avatar = 'style="background-image: url(' . base_url('img/avatars/' . $l_event['task']['authorAvatar']) . ');"';
							}
							else
							{
								$avatar = 'style="background-image: url(' . base_url('img/avatars/avatar.jpg') . ');"';
							}
						?>
						<div class="comment-avatar" <?php echo $avatar; ?>></div>
						<p class="author"><?php echo $l_event['task']['authorName'] . ', ' . substr($l_event['task']['data'], 8, 2 ) . '.' . substr($l_event['task']['data'], 5, 2 ) . '.' . substr($l_event['task']['data'], 0, 4 );?></p>
						<p class="doljnost"><?php echo $l_event['task']['authorDoljnost'];?></p>
					</div>
					<div class="task-info" <?php echo $style; ?>>
						<a href="<?php echo base_url('tasks/task/' . $l_event['task']['id_task']); ?>" class="deal-title-task"><?php echo $l_event['task']['title']; ?></a>
						<?php
							if( $l_event['task']['state'] != 2):
						?>
						<div class="ending-task-deal" id_task="<?php echo $l_event['task']['id_task']; ?>">Завершить</div>
						<?php
							else:
						?>
						<div class="completed-task-deal">Завершено</div>
						<?php
							endif;
						?>
						<div class="deadline">до <?php echo substr($l_event['task']['deadline'], 8, 2 ) . '.' . substr($l_event['task']['deadline'], 5, 2 ) . '.' . substr($l_event['task']['deadline'], 0, 4 ); ?></div>
					</div>
				<?php
					else:
					//Если это просто примечание
				?>
					<div class="author-info">
						<?php
							if( !empty($l_event['authorAvatar']))
							{
								$avatar = 'style="background-image: url(' . base_url('img/avatars/' . $l_event['authorAvatar']) . ');"';
							}
							else
							{
								$avatar = 'style="background-image: url(' . base_url('img/avatars/avatar.jpg') . ');"';
							}
						?>
						<div class="comment-avatar" <?php echo $avatar; ?>></div>
						<p class="author"><?php echo $l_event['authorName'] . ', ' . $l_event['data'];?></p>
						<p class="doljnost"><?php echo $l_event['authorDoljnost'];?></p>
					</div>
				<?php
					endif;
				?>
				
				<?php
					//Если это задача
					if( $l_event['task'] != 0 ):
						if( $l_event['task']['state'] != 2):						
				?>
					<div class="text-comment">
						<?php echo $l_event['task']['description'];?>
					</div>
				<?php
						else:
				?>
					<div class="text-comment">
						<div style="text-decoration: line-through;">
							<?php echo $l_event['task']['description'];?>
						</div>
						<div class="text-comment">
							<?php echo $l_event['task']['result'];?>
						</div>
					</div>
				<?php
						endif;
					else:
					//Если это просто примечание
				?>
					<div class="text-comment">
						<?php echo $l_event['description'];?>
					</div>
				<?php
					endif;
				?>
			</div>
			<?php 
				if( $l_event['sub'] != 0 ):
					foreach( $l_event['sub'] as $sub_res ):
						$sub_dt = (strtotime(date('Y-m-d')) - strtotime(substr($sub_res['date'], 0, 10)))/(3600*24);
						if( $sub_dt == 0 ){
							$sub_data = 'Сегодня в ' . substr($sub_res['date'], 11, 5);
						}elseif( $sub_dt == 1 ){
							$sub_data = 'Вчера в ' . substr($sub_res['date'], 11, 5);
						}else{
							$sub_data = substr($sub_res['date'], 8, 2 ) . '.' . substr($sub_res['date'], 5, 2 ) . '.' . substr($sub_res['date'], 0, 4 ) . ' в ' . substr($sub_res['date'], 11, 5);;
						}
			?>
				<div class="sub_event subevent<?php echo $sub_res['id_event'];?>">
					<?php
						if( $profile['id'] == $sub_res['author_id'] ):
					?>
					<div class="functional-sub-event-deal">
						<div class="edit-sub-event" id-event="<?php echo $sub_res['id_event'];?>"></div>
						<div class="del-sub-event" id-event="<?php echo $sub_res['id_event'];?>"></div>
					</div>
					<?php
						endif;
					?>
					<div class="author-info">
						<?php
							if( !empty($sub_res['authorAvatar']))
							{
								$avatar = 'style="background-image: url(' . base_url('img/avatars/' . $sub_res['authorAvatar']) . ');"';
							}
							else
							{
								$avatar = 'style="background-image: url(' . base_url('img/avatars/avatar.jpg') . ');"';
							}
						?>
						<div class="comment-avatar" <?php echo $avatar; ?>></div>
						<p class="author"><?php echo $sub_res['author'] . ', ' . $sub_data; ?></p>
						<p class="doljnost"><?php echo $sub_res['author']['doljnost'];?></p>
					</div>
					<div class="text-response-comment">
						<?php echo $sub_res['description']; ?>	
					</div>
				</div>
			<?php
					endforeach;
				endif;
			?>
		</div>
		<?php
				endforeach;
			else:
		?>
			<div class="info-data today" style="display: none;">Сегодня</div>
			<div id="today"></div>
		<?php
			endif;
		?>

		<!--Other-->
		<?php
			if( !empty($arr_other) ):
				echo '<div class="info-data">Раньше</div>';
				foreach( $arr_other as $l_event ):
		?>

		<?php
			if( $l_event['task'] != 0 ):
				if( $l_event['task']['state'] != 2 )
				{
					$style = 'style="background-color: #5EA359;"';
				}
				else
				{
					$style = 'style="background-color: #808080;"';
				}
		?>
				
				<div class="event_task<?php echo $l_event['task']['id_task']; ?>">
		<?php
			else:
		?>
			<div class="event_transfer<?php echo $l_event['id_event']; ?>">
		<?php
			endif;
		?>
				<div class="show_event"> 
					<?php
						//Если это задача
						if( $l_event['task'] != 0 ):
							if( $profile['id'] == $l_event['task']['author_id'] ):
					?>
						<div class="functional-event-deal">
							<div class="edit-task-deal" id-task="<?php echo $l_event['task']['id_task']; ?>"></div>
							<div class="del-task-deal" id-task="<?php echo $l_event['task']['id_task']; ?>"></div>
						</div>
					<?php
							endif;
						else:
						//Если это просто примечание
							if( $profile['id'] == $l_event['id_author'] ):
					?>
						<div class="functional-event-deal">
							<div class="edit-event" id-event="<?php echo $l_event['id_event']; ?>"></div>
							<div class="del-event" id-event="<?php echo $l_event['id_event']; ?>"></div>
						</div>
					<?php
							endif;
						endif;
					?>
					
					<?php
						//Если это задача
						if( $l_event['task'] != 0 ):
					?>
						<div class="author-info">
							<?php
								if( !empty($l_event['task']['authorAvatar']))
								{
									$avatar = 'style="background-image: url(' . base_url('img/avatars/' . $l_event['task']['authorAvatar']) . ');"';
								}
								else
								{
									$avatar = 'style="background-image: url(' . base_url('img/avatars/avatar.jpg') . ');"';
								}
							?>
							<div class="comment-avatar" <?php echo $avatar; ?>></div>
							<p class="author"><?php echo $l_event['task']['authorName'] . ', ' . substr($l_event['task']['data'], 8, 2 ) . '.' . substr($l_event['task']['data'], 5, 2 ) . '.' . substr($l_event['task']['data'], 0, 4 );?></p>
							<p class="doljnost"><?php echo $l_event['task']['authorDoljnost'];?></p>
						</div>
						<div class="task-info" <?php echo $style; ?>>
							<a href="<?php echo base_url('tasks/task/' . $l_event['task']['id_task']); ?>" class="deal-title-task"><?php echo $l_event['task']['title']; ?></a>
							<?php
								if( $l_event['task']['state'] != 2):
							?>
							<div class="ending-task-deal" id_task="<?php echo $l_event['task']['id_task']; ?>">Завершить</div>
							<?php
								else:
							?>
							<div class="completed-task-deal">Завершено</div>
							<?php
								endif;
							?>
							<div class="deadline">до <?php echo substr($l_event['task']['deadline'], 8, 2 ) . '.' . substr($l_event['task']['deadline'], 5, 2 ) . '.' . substr($l_event['task']['deadline'], 0, 4 ); ?></div>
						</div>
					<?php
						else:
						//Если это просто примечание
					?>
						<div class="author-info">
							<?php
								if( !empty($l_event['authorAvatar']))
								{
									$avatar = 'style="background-image: url(' . base_url('img/avatars/' . $l_event['authorAvatar']) . ');"';
								}
								else
								{
									$avatar = 'style="background-image: url(' . base_url('img/avatars/avatar.jpg') . ');"';
								}
							?>
							<div class="comment-avatar" <?php echo $avatar; ?>></div>
							<p class="author"><?php echo $l_event['authorName'] . ', ' . $l_event['data'];?></p>
							<p class="doljnost"><?php echo $l_event['authorDoljnost'];?></p>
						</div>
					<?php
						endif;
					?>
					
					<?php
						//Если это задача
						if( $l_event['task'] != 0 ):
							if( $l_event['task']['state'] != 2):						
					?>
						<div class="text-comment">
							<?php echo $l_event['task']['description'];?>
						</div>
					<?php
							else:
					?>
						<div class="text-comment">
							<div style="text-decoration: line-through;">
								<?php echo $l_event['task']['description'];?>
							</div>
							<div class="text-comment">
								<?php echo $l_event['task']['result'];?>
							</div>
						</div>
					<?php
							endif;
						else:
						//Если это просто примечание
					?>
						<div class="text-comment">
							<?php echo $l_event['description'];?>
						</div>
					<?php
						endif;
					?>
				</div>
				<?php 
					if( $l_event['sub'] != 0 ):
						foreach( $l_event['sub'] as $sub_res ):
							$sub_dt = (strtotime(date('Y-m-d')) - strtotime(substr($sub_res['date'], 0, 10)))/(3600*24);
							if( $sub_dt == 0 ){
								$sub_data = 'Сегодня в ' . substr($sub_res['date'], 11, 5);
							}elseif( $sub_dt == 1 ){
								$sub_data = 'Вчера в ' . substr($sub_res['date'], 11, 5);
							}else{
								$sub_data = substr($sub_res['date'], 8, 2 ) . '.' . substr($sub_res['date'], 5, 2 ) . '.' . substr($sub_res['date'], 0, 4 ) . ' в ' . substr($sub_res['date'], 11, 5);;
							}
				?>
					<div class="sub_event subevent<?php echo $sub_res['id_event'];?>">
						<?php
							if( $profile['id'] == $sub_res['author_id'] ):
						?>
						<div class="functional-sub-event-deal">
							<div class="edit-sub-event" id-event="<?php echo $sub_res['id_event'];?>"></div>
							<div class="del-sub-event" id-event="<?php echo $sub_res['id_event'];?>"></div>
						</div>
						<?php
							endif;
						?>
						<div class="author-info">
							<?php
								if( !empty($sub_res['authorAvatar']))
								{
									$avatar = 'style="background-image: url(' . base_url('img/avatars/' . $sub_res['authorAvatar']) . ');"';
								}
								else
								{
									$avatar = 'style="background-image: url(' . base_url('img/avatars/avatar.jpg') . ');"';
								}
							?>
							<div class="comment-avatar" <?php echo $avatar; ?>></div>
							<p class="author"><?php echo $sub_res['authorName'] . ', ' . $sub_data; ?></p>
							<p class="doljnost"><?php echo $sub_res['authorDoljnost'];?></p>
						</div>
						<div class="text-response-comment">
							<?php echo $sub_res['description']; ?>	
						</div>
					</div>
				<?php
						endforeach;
					endif;
				?>
			</div>
			<?php
					endforeach;
				endif;
			?>
	</div>
	<div class="block-info">
		<div class="info-field">
			<h3>Статус сделки</h3>
			<?php
				if( !empty( $deal['budget'] ) || $deal['budget'] != 0 )
				{
					$budget = '<strong>' . $deal['budget'] . ' ' . $currency[$deal['currencyId'] - 1]['attr'] . '</strong>';
				}
				else
				{
					$budget = '<span style="opacity: .6">Отсутствует</span>';
				}
			?>
			<p class="info-p edit-budget">Бюджет сделки: <?php echo $budget; ?></p>
			<span id="fast-edit-status" class="status" style="background: #<?php echo  $deal['color']; ?>"><?php echo $deal['status_deal']; ?></span>
			<ul id="fast-edit-status-deal">
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
							echo '<li status_id="' . $edit_list['id'] . '" style="background-color: #' . $edit_list['color'] . ';">' . $edit_list['value'] . '</li>';
						}
					}
				?>
			</ul>
		</div>

		<div class="info-field">
			<h3>Участники</h3>
			<p class="info-p">Менеджер: <strong><?php echo $deal['author']; ?></strong></p>
			<p class="info-p">Контактные лица:</p>
			<ul id="faces">
			<?php
				if( !empty($deal['sub_client']) )
				{
					foreach( $deal['sub_client'] as $list )
					{
						$company = '';
						if( !empty( $list['profile']['id_company'] ) || $list['profile']['id_company'] != 0)
						{
							$company = '<a class="cart_a" href="' . base_url('clients/company/' . $list['profile']['id_company']) . '">Компания</a>';
						}
						echo '<li class="group ' . $list['profile']['id'] . '">
								<span>' . $list['profile']['fio'] . '</span>
									<div class="cart">
										<p>Контакт: <a href="' . base_url('clients/contact/' . $list['profile']['id']) . '" class="cart_a">' . $list['profile']['fio'] . '</a></p>
										<p>Компания: <a class="cart_a" href="http://crm.korinf.com.ua/clients/show_company/29">Допилить код</a></p>';					
										if( !empty($list['profile']['doljnost']) || $list['profile']['doljnost'] != 0)
										{
											echo '<p>Должность: ' . $list['profile']['doljnost'] . '</p>';
										}
										if( !empty($list['contact']['phone']) || $list['contact']['phone'] != 0)
										{
											echo '<p>Телефон: ' . $list['contact']['phone'] . '</p>';
										}
										if( !empty($list['contact']['email']) || $list['contact']['email'] != 0)
										{
											echo '<p>E-mail: ' . $list['contact']['email'] . '</p>';
										}
										if( !empty($list['contact']['skype']) || $list['contact']['skype'] != 0)
										{
											echo '<p>Skype: ' . $list['contact']['skype'] . '</p>';
										}
									echo '<p class="cart_link del-from-deal" id_contact="' . $list['profile']['id'] . '">Открепить</p>
										</div>
									<div class="cut"></div>
								</li>';
									
					}
				}
			?>
				<li>
					<div id="addingFace">
						<input type="text" 
							id="add-contactface-deal" 
							autocomplete ="off"
							class="client addFaceField addField text" 
							placeholder="Введите имя" 
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
						<div style="clear: both; height: 10px;"></div>
						<button id="ajax-save-face-deal" class="btn_sv_face">Сохранить</button>
						<button class="btn-cncl-face">Отмена</button>
					</div>
					<div class="add_new_contact_face" id="newContactFace">+ Добавить контактное лицо</div>
				</li>
			</ul>
			<?php
				if( $deal['company_id'] != 0 )
				{
					$company = '<a href="' . base_url('clients/company/' . $deal['company_id']) . '">' . $deal['company_name'] . '</a>';
				}
				else
				{
					$company = '<span style="opacity: .6;">Компания не привязана</span>';
				}
			?>
			<p class="info-p edit-company">Компания: <?php echo $company; ?></p>
		</div>
	</div>
</div>

<?php if( $profile['id'] == $deal['author_id'] ): ?>
<div id="edit-field">
	<div class="close">×</div>
	<p>Редакторивать сделку</p>
	<div class="sub_field">
		<input type="text" id="edit-name-deal" autocomplete ="off" class="client text" style="margin-bottom: 10px;" placeholder="Название сделки" value="<?php echo $deal['title_deal']; ?>" />
		<textarea placeholder="Добавить описание сделки" id="edit-description-deal" class="addField textarea"><?php echo $deal['description']; ?></textarea>
		<input type="text" id="edit-budget-deal" autocomplete ="off" class="budget text" style="margin-bottom: 10px;" placeholder="Бюджет сделки" value="<?php echo $deal['budget']; ?>" />
		<input type="text" 
			id="edit-company-deal" 
			value="<?php echo $deal['company_name']; ?>" 
			autocomplete ="off"
			class="busines text" 
			placeholder="Добавить компанию" 
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
		/>
		<button id="save-edit-deal" class="button">Сохранить</button>
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