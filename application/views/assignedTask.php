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
						//'type_id' => $list_task['type_id'], 
						'data' => substr($list_task['data_create'], 8, 2) . '.' . substr($list_task['data_create'], 5, 2) . '.' . substr($list_task['data_create'], 0, 4), 
						'deadline' => substr($list_task['deadline'], 8, 2) . '.' . substr($list_task['deadline'], 5, 2) . '.' . substr($list_task['deadline'], 0, 4), 
						'users' => $list_task['users'], 
						'title' => $list_task['title'], 
						'description' => $list_task['text'],
						'category' => $list_task['category']
						);
				}
				elseif( $list_task['state'] == 1 )
				{
					$arr_active[] = Array(
						'id' => $list_task['id'], 
						//'type_id' => $list_task['type_id'], 
						'data' => substr($list_task['data_create'], 8, 2) . '.' . substr($list_task['data_create'], 5, 2) . '.' . substr($list_task['data_create'], 0, 4), 
						'deadline' => substr($list_task['deadline'], 8, 2) . '.' . substr($list_task['deadline'], 5, 2) . '.' . substr($list_task['deadline'], 0, 4), 
						'users' => $list_task['users'], 
						'title' => $list_task['title'], 
						'description' => $list_task['text'],
						'category' => $list_task['category']
						);
				}
				elseif( $list_task['state'] == 2 )
				{
					$arr_finish[] = Array(
						'id' => $list_task['id'], 
						//'type_id' => $list_task['type_id'], 
						'data' => substr($list_task['data_create'], 8, 2) . '.' . substr($list_task['data_create'], 5, 2) . '.' . substr($list_task['data_create'], 0, 4), 
						'deadline' => substr($list_task['deadline'], 8, 2) . '.' . substr($list_task['deadline'], 5, 2) . '.' . substr($list_task['deadline'], 0, 4), 
						'users' => $list_task['users'], 
						'title' => $list_task['title'], 
						'description' => $list_task['text'],
						'category' => $list_task['category']
						);
				}
			}
		}
	?>

	<table id="content-table">
		<tr class="title" style="border-left: 5px solid transparent;">
			<th style="width: 3%;"><input type="checkbox" /></th>
			<th style="width: 13%;">Срок</th>
			<th>Задача</th>
			<th>Сделка, Клиент</th>
			<th>Исполнители</th>
			<th style="width: 15%;">Дата создания</th>
			<th>Управление</th>
		</tr>
	<?php
		if( !empty($arr_deadline) )
		{
			echo '<tr class="empty"><td colspan="7" style="border:none;">Просроченные</td></tr>';
			foreach( $arr_deadline as $tasklist )
			{
				if( !empty($tasklist['category']) )
				{
					if( $tasklist['category']['cat'] == 1 )
					{
						$cat = '<strong>Сделка :</strong> <a href="' . base_url('deal/show_deal/' . $tasklist['category']['id']) . '" target="blank">' . $tasklist['category']['name'] . '</a>';
					}
				}
				else
				{
					$cat = 'Задача без привязки';
				}
				echo '<tr class="task-' . $tasklist['id'] . '" style="border-left: 5px solid #D32222;">';
				echo '<td><input type="checkbox" /></td>';
				echo '<td><span class="hot_deadline">' . $tasklist['deadline'] . '</span></td>';
				echo '<td><a href="' . base_url('tasks/task/' . $tasklist['id']) . '" class="hot-task">' . $tasklist['title'] . '</a></td>';
				echo '<td>' . $cat . '</td>';
				echo '<td><ul>';
					foreach( $tasklist['users'] as $user_perf )
					{
						echo '<li>' . $user_perf['first_name'] . ' ' . $user_perf['last_name'] . '</li>';
					}
				echo '</ul></td>';
				echo '<td>' . $tasklist['data'] . '</td>';
				echo '<td>';
					echo '<button title="Возобновить задачу" class="restore_task hastip" id_task="' . $tasklist['id'] . '"></button>';
					echo '<button title="Удалить задачу" class="dell_task hastip" id_task="' . $tasklist['id'] . '"></button>';
				echo '</td>';
				echo '</tr>';
			}
		}

		if( !empty($arr_active) )
		{
			echo '<tr class="empty"><td colspan="7" style="border:none;">Активные</td></tr>';
			
			foreach( $arr_active as $tasklist )
			{
				if( !empty($tasklist['category']) )
				{
					if( $tasklist['category']['cat'] == 1 )
					{
						$cat = '<strong>Сделка :</strong> <a href="' . base_url('deal/show_deal/' . $tasklist['category']['id']) . '" target="blank">' . $tasklist['category']['name'] . '</a>';
					}
				}
				else
				{
					$cat = 'Задача без привязки';
				}
				echo '<tr style="border-left: 5px solid #2281D3;">';
				echo '<td><input type="checkbox" /></td>';
				echo '<td><span class="today_deadline">' . $tasklist['deadline'] . '</span></td>';
				echo '<td><a href="' . base_url('tasks/task/' . $tasklist['id']) . '" class="today-task">' . $tasklist['title'] . '</a></td>';
				echo '<td>' . $cat . '</td>';
				echo '<td><ul>';
					foreach( $tasklist['users'] as $user_perf )
					{
						echo '<li>' . $user_perf['first_name'] . ' ' . $user_perf['last_name'] . '</li>';
					}
				echo '</ul></td>';
				echo '<td>' . $tasklist['data'] . '</td>';
				echo '<td>';
					echo '<button title="Возобновить задачу" class="restore_task hastip" id_task="' . $tasklist['id'] . '"></button>';
					echo '<button title="Удалить задачу" class="dell_task hastip" id_task="' . $tasklist['id'] . '"></button>';
				echo '</td>';
				echo '</tr>';
			}
		}

		if( !empty($arr_finish) )
		{
			echo '<tr class="empty"><td colspan="7" style="border:none;">Выполненные</td></tr>';
			
			foreach( $arr_finish as $tasklist )
			{
				if( !empty($tasklist['category']) )
				{
					if( $tasklist['category']['cat'] == 1 )
					{
						$cat = '<strong>Сделка :</strong> <a href="' . base_url('deal/show_deal/' . $tasklist['category']['id']) . '" target="blank">' . $tasklist['category']['name'] . '</a>';
					}
				}
				else
				{
					$cat = 'Задача без привязки';
				}
				echo '<tr style="border-left: 5px solid #4CAF6F;">';
				echo '<td><input type="checkbox" /></td>';
				echo '<td><span class="tomorrow_deadline">' . $tasklist['deadline'] . '</span></td>';
				echo '<td><a href="' . base_url('tasks/task/' . $tasklist['id']) . '" class="tomorrow-task">' . $tasklist['title'] . '</a></td>';
				echo '<td>' . $cat . '</td>';
				echo '<td><ul>';
					foreach( $tasklist['users'] as $user_perf )
					{
						echo '<li>' . $user_perf['first_name'] . ' ' . $user_perf['last_name'] . '</li>';
					}
				echo '</ul></td>';
				echo '<td>' . $tasklist['data'] . '</td>';
				echo '<td>';
					echo '<button title="Возобновить задачу" class="restore_task hastip" id_task="' . $tasklist['id'] . '"></button>';
					echo '<button title="Удалить задачу" class="dell_task hastip" id_task="' . $tasklist['id'] . '"></button>';
				echo '</td>';
				echo '</tr>';
			}
		}
	?>
	</table>

</div>