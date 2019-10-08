<div id="saveform">
	<button style="background: <?php echo $header; ?>;" class="add_new_task">Новая задача</button>
	<button style="background: <?php echo $header; ?>;" class="func_button" id="ajax_save_task">Сохранить</button>
</div>
<div id="content">
	<div id="adding-task">
		<input type="text" placeholder="Добавить тему задачи" id="title-task" class="addField" style="margin: 0 0 0 15px; padding: 4px 1% 5px 1%; width: 60%;"/>
		<div class="fieldSelDate">
			<ul id="selDate">
				<li class="selDateActive" deadline="<?php echo date('d.m.Y'); ?>">На сегодня</li>
				<li deadline="<?php echo date('d.m.Y', strtotime("+1 DAY")); ?>">На завтра</li>
				<li deadline="<?php echo date('d.m.Y', strtotime("+2 DAY")); ?>">На послезавтра</li>
				<li class="datecalend"><input disabled type="text" class="date-pick selectDate" value="<?php echo date('d.m.Y'); ?>" placeholder="Выбрать дату"></li>
			</ul>
		</div>
		<div class="clear:both;"></div>
		<select id="type-task">
			<option value="1">Обычная задача</option>
			<option value="2">Важная задача</option>
		</select>
		
		<div class="select_username hastip" title="Выбрать для кого будет назначена задача, если не выбирать то задача назначится себе">Выбрать сотрудника <span class="sum_user"><?php echo count($users); ?></span></div>
		<div style="clear: both;"></div>
		<ul id="adding_user">
			
		</ul>
		<div class="clear:both;"></div>
		<textarea id="ajax_task_description" placeholder="Добавить описание" class="textarea" style="margin: 0 0 0 15px; width: 61%;"></textarea>
	</div>

	<?php
		if( count($task) != 0 ):
	?>
	<table style="margin: 45px 0 15px 0;">
		<tr class="title">
			<th style="width: 3%;"><input type="checkbox" /></th>
			<th style="width: 13%;">Срок</th>
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
					$cat = '<strong>Сделка :</strong> <a href="' . base_url('deal/show_deal/' . $tasklist['category']['id']) . '" target="blank" class="name_deal">' . $tasklist['category']['name'] . '</a>';
				}
			}
			else
			{
				$cat = 'Задача без привязки';
			}
			echo '<tr style="border-left: 5px solid #D32222;">';
			echo '<td><input type="checkbox" /></td>';
			echo '<td><span class="hot_deadline">' . substr($tasklist['deadline'], 8, 2) . '.' . substr($tasklist['deadline'], 5, 2) . '.' . substr($tasklist['deadline'], 0, 4) . '</span></td>';
			echo '<td><a href="' . base_url('task/show_task/' . $tasklist['id']) . '" class="hot-task">' . $tasklist['title'] . '</a></td>';
			echo '<td>' . $cat . '</td>';
			echo '<td>' . $tasklist['author'] . '</td>';
			echo '<td>' . substr($tasklist['data_create'], 8, 2) . '.' . substr($tasklist['data_create'], 5, 2) . '.' . substr($tasklist['data_create'], 0, 4) . '</td>';
			echo '</tr>';
		}
	?>
	</table>
	<?php
		else:
			echo '<div class="informer">Просроченных задач нету!</div>';
		endif;
	?>
</div>