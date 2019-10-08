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
	<table id="content-table">
		<tr class="title">
			<th style="width: 3%;"><input type="checkbox" /></th>
			<th style="width: 14%;">Завершено</th>
			<th>Задача</th>
			<th>Сделка, Клиент</th>
			<th style="width: 15%;">Автор</th>
			<th style="width: 15%;">Дата создания</th>
		</tr>

	<?php
		foreach( $task as $tasklist )
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
			echo '<tr>';
			echo '<td><input type="checkbox" /></td>';
			echo '<td><span class="after_tomorrow_deadline">' . substr($tasklist['data_finish'], 8, 2) . '.' . substr($tasklist['data_finish'], 5, 2) . '.' . substr($tasklist['data_finish'], 0, 4) . '</span></td>';
			echo '<td><a href="' . base_url('tasks/task/' . $tasklist['id']) . '" class="hot-task">' . $tasklist['title'] . '</a></td>';
			echo '<td>' . $cat . '</td>';
			echo '<td>' . $tasklist['author'] . '</td>';
			echo '<td>' . substr($tasklist['data_create'], 8, 2) . '.' . substr($tasklist['data_create'], 5, 2) . '.' . substr($tasklist['data_create'], 0, 4) . '</td>';
			echo '</td>';
			echo '</tr>';
		}
	?>
	</table>
</div>