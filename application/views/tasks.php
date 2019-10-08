<div id="content">
			<div class="page-info">
				<ul id="ul-info">
					<li><h1>Задачи</h1></li>
					<li style="padding: 4px 20px 0 0; height: 23px;">
						<a href="<?php echo base_url('tasks');?>" <?php echo $subMenu['active']; ?>>Мои</a> <a href="<?php echo base_url('tasks/finishTask');?>" <?php echo $subMenu['finished']; ?>>Выполненные</a> <a href="<?php echo base_url('tasks/assignedTask');?>" <?php echo $subMenu['assigned']; ?>>Назначенные</a>
					<li>
						<button class="add_new">+ Добавить задачу</button>
						<button class="func_button" id="ajax-save-task">Сохранить задачу</button>
						<button class="cancel">Отменить</button>
					</li>
				</ul>
				<div id="modal">
					<div class="left">
						<input type="text" placeholder="Добавить тему задачи" id="title-task" class="text" />
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
							Выбрать сотрудника <span class="sum_user">12</span>
						</div>
						<div style="clear: both;"></div>
						<ul id="adding_user">
							
						</ul>
						<div class="clear:both;"></div>
						<textarea id="ajax-task-description" placeholder="Добавить описание"></textarea>
					</div>
					<div class="right">		
						
					</div>					
				</div>
			</div>

				<?php
					if( !empty($task) )
					{
						foreach( $task as $list_task )
						{
							if( $list_task['state'] == 3 )
							{
								$arr_deadline[] = Array(
									'id' => $list_task['id'], 
									'type_id' => $list_task['type_id'], 
									'data' => substr($list_task['data_create'], 8, 2) . '.' . substr($list_task['data_create'], 5, 2) . '.' . substr($list_task['data_create'], 0, 4), 
									'deadline' => substr($list_task['deadline'], 8, 2) . '.' . substr($list_task['deadline'], 5, 2) . '.' . substr($list_task['deadline'], 0, 4), 
									'author' => $list_task['author'], 
									'title' => $list_task['title'], 
									'description' => $list_task['text'],
									'category' => $list_task['category']
									);
							}
							if( $list_task['deadline'] == date('Y-m-d') )
							{
								$arr_today[] = Array(
									'id' => $list_task['id'], 
									'type_id' => $list_task['type_id'], 
									'data' => substr($list_task['data_create'], 8, 2) . '.' . substr($list_task['data_create'], 5, 2) . '.' . substr($list_task['data_create'], 0, 4), 
									'deadline' => substr($list_task['deadline'], 8, 2) . '.' . substr($list_task['deadline'], 5, 2) . '.' . substr($list_task['deadline'], 0, 4), 
									'author' => $list_task['author'], 
									'title' => $list_task['title'], 
									'description' => $list_task['text'],
									'category' => $list_task['category']
									);
							}
							elseif( $list_task['deadline'] == date('Y-m-d', strtotime("+1 DAY")) )
							{
								$arr_tomorrow[] = Array(
									'id' => $list_task['id'], 
									'type_id' => $list_task['type_id'], 
									'data' => substr($list_task['data_create'], 8, 2) . '.' . substr($list_task['data_create'], 5, 2) . '.' . substr($list_task['data_create'], 0, 4), 
									'deadline' => substr($list_task['deadline'], 8, 2) . '.' . substr($list_task['deadline'], 5, 2) . '.' . substr($list_task['deadline'], 0, 4), 
									'author' => $list_task['author'], 
									'title' => $list_task['title'], 
									'description' => $list_task['text'],
									'category' => $list_task['category']
									);
							}
							elseif( (strtotime(date('Y-m-d')) - strtotime($list_task['deadline']))/(3600*24) < -1 )
							{
								
								$arr_after_tomorrow[] = Array(
									'id' => $list_task['id'], 
									'type_id' => $list_task['type_id'], 
									'data' => substr($list_task['data_create'], 8, 2) . '.' . substr($list_task['data_create'], 5, 2) . '.' . substr($list_task['data_create'], 0, 4), 
									'deadline' => substr($list_task['deadline'], 8, 2) . '.' . substr($list_task['deadline'], 5, 2) . '.' . substr($list_task['deadline'], 0, 4), 
									'author' => $list_task['author'], 
									'title' => $list_task['title'], 
									'description' => $list_task['text'],
									'category' => $list_task['category']
									);
							}
						}
					}
				?>
			<?php
				if( !empty($arr_deadline) ):
			?>
				<div class="column-task">
					<div class="deal-title">
						<h4 class="h4-deadline">Просроченные</h4>
						<p class="count-deals">Задач: 10</p>
					</div>
			<?php
					foreach( $arr_deadline as $tasklist ):
					
						if( !empty($tasklist['category']) )
						{
							if( $tasklist['category']['cat'] == 1 )
							{
								$cat = 'Сделка: <a href="' . base_url('deals/show_deal/' . $tasklist['category']['id']) . '" target="blank" class="name_deal">' . $tasklist['category']['name'] . '</a>';
							}
						}
						else
						{
							$cat = 'Задача без привязки';
						}
					?>
					<div class="box-deal">
						<div class="avatar" style="background-image: url('<?php echo base_url('img/avatar.png');?>');"></div>
						<a href="<?php echo base_url('tasks/task/' . $tasklist['id']); ?>" class="name_deal"><?php echo $tasklist['title']; ?></a>
						<p class="task-deadline">Срок: <strong><?php echo $tasklist['deadline']; ?></strong></p>
						<a href="#" class="client-link budget"><?php echo $cat; ?></a>
						<div style="clear: both;"></div>
						<?php 
							if( !empty($tasklist['description']) || $tasklist['description'] != 0 ):
						?>
						<div class="insert-description">Больше информации</div>
							<div class="list-description-tasks">
								<p><?php echo $tasklist['description']; ?></p>
							</div>
						<?php
							endif;
						?>
						<p class="author-deal"><?php echo $tasklist['author']; ?></p>
					</div>
				<?php
					endforeach;
				?>
				</div>
			<?php
				endif;
			?>

			<?php
				if( !empty($arr_today) ):
			?>
				<div class="column-task today">
					<div class="deal-title">
						<h4>На сегодня</h4>
						<p class="count-deals">Задач: 10</p>
					</div>
			<?php
					foreach( $arr_today as $tasklist ):
					
						if( !empty($tasklist['category']) )
						{
							if( $tasklist['category']['cat'] == 1 )
							{
								$cat = 'Сделка: <a href="' . base_url('deals/show_deal/' . $tasklist['category']['id']) . '" target="blank" class="name_deal">' . $tasklist['category']['name'] . '</a>';
							}
						}
						else
						{
							$cat = 'Задача без привязки';
						}
					?>
					<div class="box-deal">
						<div class="avatar" style="background-image: url('<?php echo base_url('img/avatar.png');?>');"></div>
						<a href="<?php echo base_url('tasks/task/' . $tasklist['id']); ?>" class="name_deal"><?php echo $tasklist['title']; ?></a>
						<p class="task-deadline">Срок: <strong><?php echo $tasklist['deadline']; ?></strong></p>
						<a href="#" class="client-link budget"><?php echo $cat; ?></a>
						<div style="clear: both;"></div>
						<?php 
							if( !empty($tasklist['description']) || $tasklist['description'] != 0 ):
						?>
						<div class="insert-description">Больше информации</div>
							<div class="list-description-tasks">
								<p><?php echo $tasklist['description']; ?></p>
							</div>
						<?php
							endif;
						?>
						<p class="author-deal"><?php echo $tasklist['author']; ?></p>
					</div>
				<?php
					endforeach;
				?>
				</div>
			<?php
				endif;
			?>

			<?php
				if( !empty($arr_tomorrow) ):
			?>
				<div class="column-task">
					<div class="deal-title">
						<h4>На завтра</h4>
						<p class="count-deals">Задач: 10</p>
					</div>
			<?php
					foreach( $arr_tomorrow as $tasklist ):
					
						if( !empty($tasklist['category']) )
						{
							if( $tasklist['category']['cat'] == 1 )
							{
								$cat = 'Сделка: <a href="' . base_url('deals/show_deal/' . $tasklist['category']['id']) . '" target="blank" class="name_deal">' . $tasklist['category']['name'] . '</a>';
							}
						}
						else
						{
							$cat = 'Задача без привязки';
						}
					?>
					<div class="box-deal">
						<div class="avatar" style="background-image: url('<?php echo base_url('img/avatar.png');?>');"></div>
						<a href="<?php echo base_url('tasks/task/' . $tasklist['id']); ?>" class="name_deal"><?php echo $tasklist['title']; ?></a>
						<p class="task-deadline">Срок: <strong><?php echo $tasklist['deadline']; ?></strong></p>
						<a href="#" class="client-link budget"><?php echo $cat; ?></a>
						<div style="clear: both;"></div>
						<?php 
							if( !empty($tasklist['description']) || $tasklist['description'] != 0 ):
						?>
						<div class="insert-description">Больше информации</div>
							<div class="list-description-tasks">
								<p><?php echo $tasklist['description']; ?></p>
							</div>
						<?php
							endif;
						?>
						<p class="author-deal"><?php echo $tasklist['author']; ?></p>
					</div>
				<?php
					endforeach;
				?>
				</div>
			<?php
				endif;
			?>

			<?php
				if( !empty($arr_after_tomorrow) ):
			?>
				<div class="column-task">
					<div class="deal-title">
						<h4>Будущие</h4>
						<p class="count-deals">Задач: 10</p>
					</div>
			<?php
					foreach( $arr_after_tomorrow as $tasklist ):
					
						if( !empty($tasklist['category']) )
						{
							if( $tasklist['category']['cat'] == 1 )
							{
								$cat = 'Сделка: <a href="' . base_url('deals/show_deal/' . $tasklist['category']['id']) . '" target="blank" class="name_deal">' . $tasklist['category']['name'] . '</a>';
							}
						}
						else
						{
							$cat = 'Задача без привязки';
						}
					?>
					<div class="box-deal">
						<div class="avatar" style="background-image: url('<?php echo base_url('img/avatar.png');?>');"></div>
						<a href="<?php echo base_url('tasks/task/' . $tasklist['id']); ?>" class="name_deal"><?php echo $tasklist['title']; ?></a>
						<p class="task-deadline">Срок: <strong><?php echo $tasklist['deadline']; ?></strong></p>
						<a href="#" class="client-link budget"><?php echo $cat; ?></a>
						<div style="clear: both;"></div>
						<?php 
							if( !empty($tasklist['description']) || $tasklist['description'] != 0 ):
						?>
						<div class="insert-description">Больше информации</div>
							<div class="list-description-tasks">
								<p><?php echo $tasklist['description']; ?></p>
							</div>
						<?php
							endif;
						?>
						<p class="author-deal"><?php echo $tasklist['author']; ?></p>
					</div>
				<?php
					endforeach;
				?>
				</div>
			<?php
				endif;
			?>
		</div>
