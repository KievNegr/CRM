<div id="saveform">
	<button class="add_new_task">Новая задача</button>
	<button class="func_button" id="ajax_save_task">Сохранить</button>
</div>
<div id="content">
	<div id="main">
			<?php
				if( !empty($task) )
				{
					foreach( $task as $list_task )
					{
						if( $list_task['deadline'] == date('Y-m-d') )
						{
							$arr_today[] = Array('id' => $list_task['id'], 'type_id' => $list_task['type_id'], 'data' => $list_task['data_create'], 'author' => $list_task['author'], 'description' => $list_task['text']);
						}
						elseif( $list_task['deadline'] == date('Y-m-d', strtotime("+1 DAY")) )
						{
							$arr_tomorrow[] = Array('id' => $list_task['id'], 'type_id' => $list_task['type_id'], 'data' => $list_task['data_create'], 'author' => $list_task['author'], 'description' => $list_task['text']);
						}
						elseif( $list_task['deadline'] == date('Y-m-d', strtotime("+2 DAY")) )
						{
							$arr_after_tomorrow[] = Array('id' => $list_task['id'], 'type_id' => $list_task['type_id'], 'data' => $list_task['data_create'], 'author' => $list_task['author'], 'description' => $list_task['text']);
						}
					}
				}
			?>
			<ul id="show-status-task">
				<li class="active">Мои задачи <span class="sidebar_red"><?php echo $count; ?></span></li>
				<li><a href="<?php echo base_url('task/finish_task');?>">Выполеные задачи</a></li>
				<li><a href="<?php echo base_url('task/over_task');?>">Просроченные задачи</a></li>
			</ul>

			<div class="clear:both;"></div>

			<div class="tasks" style="border-color: #5EA359;">

			<div id="adding-task">
				<script src="<?php echo base_url('js/jquery-ui.js'); ?>"></script>
				<link rel="stylesheet" href="<?php echo base_url('css/jquery-ui.css'); ?>">

				<script>
				$(function() {
					$( "#datepicker" ).datepicker({
						dateFormat: 'dd.mm.yy',
						minDate: new Date()
					});

				});
				</script>
				<input type="text" id="datepicker" class="addField selectDate" value="<?php echo date('d.m.Y'); ?>" placeholder="Выбрать дату">
				<div class="clear:both;"></div>
				<select id="type-task">
					<option value="1">Обычная задача</option>
					<option value="2">Звонок</option>
					<option value="3">Встреча</option>
				</select>
				<div class="clear:both;"></div>
				<div class="select_username hastip" title="Выбрать для кого будет назначена задача, если не выбирать то задача назначится себе">Выбрать сотрудника <span class="sum_user"><?php echo count($users); ?></span></div>
				<div style="clear: both;"></div>
				<ul id="adding_user">
					
				</ul>
				<textarea class="textarea addField" style="margin: 0 0 0 15px; width: 85%; padding-left: 3px;" id="ajax_task_description"></textarea>
				<div class="clear:both;"></div>
			</div>

				<span>Сегодня</span>
				<?php
					if( !empty($arr_today) )
					{
						foreach( $arr_today as $list ):
							$dt = (strtotime(date('Y-m-d')) - strtotime($list['data']))/(3600*24);
							if( $dt == 0 )
							{
								$dt = 'Сегодня';
								
							}
							elseif ( $dt == 1 )
							{
								$dt = 'Вчера';
							}
							else
							{
								$dt = $list['data'];
							}

							switch( $list['type_id'] )
							{
								case 1: 
									$img = base_url('img/info.png');
									break;
								case 2: 
									$img = base_url('img/phone.png');
									break;
								case 3: 
									$img = base_url('img/people.png');
									break;
							}
						?>
							<div id="view_task_<?php echo $list['id']; ?>" class="show_task" style="background: url('<?php echo $img; ?>') no-repeat 10px 13px #FFF;">
								<p class="author"><?php echo $dt; ?>, <?php echo $list['author']; ?></p>
								<p class="text_task"><?php echo $list['description']; ?></p>
								<button title="Завершить задачу" class="ending_task hastip" id_task="<?php echo $list['id']; ?>"></button>
								<button title="Комментарии" class="comments_task hastip" id_task="<?php echo $list['id']; ?>"></button>
								<button title="Удалить задачу" class="dell_task hastip" id_task="<?php echo $list['id']; ?>"></button>
							</div>
						<?php
						endforeach;
					}
				?>
			</div>
			<div class="tasks" style="border-color: #FDFFA8;">
				<span>Завтра</span>
				<?php
					if( !empty($arr_tomorrow) )
					{
						foreach( $arr_tomorrow as $list ):
							$dt = (strtotime(date('Y-m-d')) - strtotime($list['data']))/(3600*24);
							if( $dt == 0 )
							{
								$dt = 'Сегодня';
								
							}
							elseif ( $dt == 1 )
							{
								$dt = 'Вчера';
							}
							else
							{
								$dt = $list['data'];
							}
						?>
							<div id="view_task_<?php echo $list['id']; ?>" class="show_task" style="background: url('<?php echo base_url('img/phone.png');?>') no-repeat 10px 13px #FDFFA8;">
								<p class="author"><?php echo $dt; ?>, <?php echo $list['author']; ?></p>
								<p class="text_task"><?php echo $list['description']; ?></p>
								<button title="Завершить задачу" class="ending_task hastip" id_task="<?php echo $list['id']; ?>"></button>
								<button title="Комментарии" class="comments_task hastip" id_task="<?php echo $list['id']; ?>"></button>
								<button title="Удалить задачу" class="dell_task hastip" id_task="<?php echo $list['id']; ?>"></button>
							</div>
						<?php
						endforeach;
					}
				?>
			</div>
			<div class="tasks" style="border-color: #A8DBFF;">
				<span>Послезавтра</span>
				<?php
					if( !empty($arr_after_tomorrow) )
					{
						foreach( $arr_after_tomorrow as $list ):
							$dt = (strtotime(date('Y-m-d')) - strtotime($list['data']))/(3600*24);
							if( $dt == 0 )
							{
								$dt = 'Сегодня';
								
							}
							elseif ( $dt == 1 )
							{
								$dt = 'Вчера';
							}
							else
							{
								$dt = $list['data'];
							}
						?>
							<div id="view_task_<?php echo $list['id']; ?>" class="show_task" style="background: url('<?php echo base_url('img/phone.png');?>') no-repeat 10px 13px #A8DBFF;">
								<p class="author"><?php echo $dt; ?>, <?php echo $list['author']; ?></p>
								<p class="text_task"><?php echo $list['description']; ?></p>
								<button title="Завершить задачу" class="ending_task hastip" id_task="<?php echo $list['id']; ?>"></button>
								<button title="Комментарии" class="comments_task hastip" id_task="<?php echo $list['id']; ?>"></button>
								<button title="Удалить задачу" class="dell_task hastip" id_task="<?php echo $list['id']; ?>"></button>
							</div>
						<?php
						endforeach;
					}
				?>
			</div>
			<div style="clear: both;"></div>
		</div>
	</div>
	
</div>