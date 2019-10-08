<input type="hidden" id="id-task" value="<?php echo $task['id']; ?>" />
<div id="content">
			<div class="page-info">
				<ul id="ul-info">
					<li><h1>Задача</h1></li>
					<li style="padding: 4px 20px 0 0; height: 23px;">
						<a href="<?php echo base_url('tasks');?>" class="active">Мои</a> <a href="<?php echo base_url('tasks/finishTask');?>" <?php echo $subMenu['finished']; ?>">Выполненные</a> <a href="<?php echo base_url('tasks/assignedTask');?>">Назначенные</a>
					<?php
						if( $task['status']['id'] == 2 )
						{
							$styleButtons = 'style="display: none;"';
						}
						else
						{
							$styleButtons = '';
						}
					?>
					<li class="style-buttons" <?php echo $styleButtons; ?>>
						<button class="add_new">Завершить задачу</button>
						<button class="func_button" id="ajax-save-task-result">Сохранить результат</button>
						<button class="cancel">Отменить</button>
					</li>
				</ul>
				<div id="modal">
					<div class="left">
						<h2 class="light">Результат выполнения задачи</h2>
						<textarea id="ajax-save-finish-result" placeholder="Результат выполнения задачи"></textarea>
					</div>
					<div class="right">		
						
					</div>					
				</div>
			</div>
			<div class="title-column">
				<?php
					if( $task['author_id'] == $profile['id'] )
					{
						echo '<div class="edit" id-edit="">Редактировать</div>';
					}
				?>
				<h2 class="edit-name"><?php echo $task['title']; ?></h2>
				<div style="clear: both;"></div>
				<div class="edit-description"><?php echo $task['description']; ?></div>
			</div>
			<div class="block-comments">
				<div class="width50px"></div>
				<textarea id="description" placeholder="Добавить сообщение" class="textarea" style="margin: 3px 0 0 -3px; width: 83%; padding: 5px 3%;"></textarea>
				<button class="save" id="save-event-deal" style="float: left; margin: 3px 0 0 0;">Сохранить</button>
				<div style="clear: both;"></div>

				<?php
					foreach( $task['events'] as $list_event )
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
								'data' => $data . ' в ' . substr($list_event['date'], 11, 5)
								);
						}elseif( $dt == 1 ){
							$data = 'Вчера';
							$arr_yesterday[] = Array(
								'author' => $list_event['author'],
								'id_author' => $list_event['id_author'],
								'description' => $list_event['description'],
								'id_event' => $list_event['id_event'],
								'sub' => $list_event['sub_event'],
								'data' => $data . ' в ' . substr($list_event['date'], 11, 5)
								);
						}else{
							$data = substr($list_event['date'], 8, 2 ) . '.' . substr($list_event['date'], 5, 2 ) . '.' . substr($list_event['date'], 0, 4 );
							$arr_other[] = Array(
								'author' => $list_event['author'],
								'id_author' => $list_event['id_author'],
								'description' => $list_event['description'],
								'data' => $data . ' в ' . substr($list_event['date'], 11, 5),
								'id_event' => $list_event['id_event'],
								'sub' => $list_event['sub_event']
								);
						}
					}
				?>

				<div class="event_transfer1175">
					<div class="show_event">
						<div class="functional-event-deal">
							<div class="edit-event" id_event="1175"></div>
							<div class="del-event" id_event="1175"></div>
							</div>
						<div class="author-info">
							<div class="comment-avatar" style="background-image: url('img/avatar.jpg');"></div>
							<p class="author">Влад Сухих, 27.05.2015 в 15:03</p>
							<p class="doljnost">Developer</p>
						</div>
						<div class="text-comment">
							<p>В сделке ведем работу по самой системе. Добавлена возможность редактирования задачи.</p>
							<p>Lorem Ipsum - це текст-"риба", що використовується в друкарстві та дизайні. Lorem Ipsum є, фактично, стандартною "рибою" аж з XVI сторіччя, коли невідомий друкар взяв шрифтову гранку та склав на ній підбірку зразків шрифтів. "Риба" не тільки успішно пережила п'ять століть, але й прижилася в електронному верстуванні, залишаючись по суті незмінною. Вона популяризувалась в 60-их роках минулого сторіччя завдяки виданню зразків шрифтів Letraset, які містили уривки з Lorem Ipsum, і вдруге - нещодавно завдяки програмам комп'ютерного верстування на кшталт Aldus Pagemaker, які використовували різні версії Lorem Ipsum.</p>
						</div>
					</div>
					<div class="sub_event">
						<div class="functional-sub-event-deal">
							<div class="edit-sub-event" id_event="1210"></div>
							<div class="del-sub-event" id_event="1210"></div>
						</div>
						<div class="author-info">
							<div class="comment-avatar" style="background-image: url('img/avatar.jpg');"></div>
							<p class="author">Влад Сухих, 27.05.2015 в 15:03</p>
							<p class="doljnost">Developer</p>
						</div>
						<div class="text-response-comment">
							<p>В сделке ведем работу по самой системе. Добавлена возможность редактирования задачи.</p>
							<p>Lorem Ipsum - це текст-"риба", що використовується в друкарстві та дизайні. Lorem Ipsum є, фактично, стандартною "рибою" аж з XVI сторіччя, коли невідомий друкар взяв шрифтову гранку та склав на ній підбірку зразків шрифтів. "Риба" не тільки успішно пережила п'ять століть, але й прижилася в електронному верстуванні, залишаючись по суті незмінною. Вона популяризувалась в 60-их роках минулого сторіччя завдяки виданню зразків шрифтів Letraset, які містили уривки з Lorem Ipsum, і вдруге - нещодавно завдяки програмам комп'ютерного верстування на кшталт Aldus Pagemaker, які використовували різні версії Lorem Ipsum.</p>
						</div>
					</div>
				</div>

			</div>
			<div class="block-info">
				<div class="info-field">
					<h3>Статус задачи</h3>
					<p class="info-p">Назначена: <strong><?php echo $task['author']['name']; ?></strong></p>
					<p class="info-p">Дата назначения: <strong><?php echo $task['create_date']; ?></strong></p>
					<p class="info-p edit-deadline">Срок: <strong><?php echo $task['deadline']; ?></strong></p>
					<?php
						if( $task['status']['id'] == 2 )
						{
							$data_finish = $task['data_finish'];
						}
						else
						{
							$data_finish = '';
						}
					?>
					<p class="info-p">Текущий статус: <span class="status-show-task edit-status" style="background: #<?php echo $task['status']['color']; ?>;"><?php echo $task['status']['value'] . ' ' . $data_finish; ?></span></p>
				</div>

				<div class="info-field">
					<h3>Привязка задачи</h3>
					<?php
						if( !empty( $task['category'] ) )
						{
							if( $task['category']['cat'] == 1 )
							{
								echo '<p class="info-p edit-bind">Сделка: <a href="' . base_url('deals/deal/' . $task['category']['id']) . '" target="blank">' . $task['category']['name'] . '</a></p>';
							}
						}
						else
						{
							echo '<p class="info-p edit-bind"><span style="opacity: .6">Задача без привязки</span></p>';
						}
					?>
				</div>

				<div class="info-field">
					<h3>Исполнители</h3>
					<ul id="show-camarades">
						<?php
							foreach($task['user_id'] as $list_users):
						?>
						<li><a href="#"><?php echo $list_users['name']['name']; ?></a></li>
						<?php
							endforeach;
						?>
					</ul>
				</div>
			</div>

		<?php if( $profile['id'] == $task['author_id'] ): ?>
		<div id="edit-field">
			<div class="close">×</div>
			<p>Редакторивать задачу</p>
			<div class="sub_field">
				<input type="text" id="edit-name-task" style="margin-bottom: 10px;" autocomplete ="off" class="client text" placeholder="Добавить тему задачи" value="<?php echo $task['title']; ?>" />
				<textarea id="edit-description-task" placeholder="Добавить описание" class="textarea" style="margin: 3px 0 0 -3px; width: 83%; padding: 5px 3%;"><?php echo $task['description']; ?></textarea>
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
					<?php
						foreach($task['user_id'] as $list_users):
					?>
					<li class="user_<?php echo $list_users['id']; ?>"><?php echo $list_users['name']['name']; ?></li>
					<?php
						endforeach;
					?>
				</ul>
				<div class="clear:both;"></div>
				<?php
					if( !empty( $task['category'] ) )
					{
						if( $task['category']['cat'] == 1 )
						{
							$str = '';

							foreach( $deals as $listDeals )
							{
								$str = '"' . $listDeals['name_deal'] .  '",' . $str;
							}
							$str = substr($str, 0, strlen($str) - 1) ;

							echo '
							<input type="text" 
								id="edit-name-deal-task"
								value ="' . $task['category']['name'] . '"
								autocomplete ="off"
								class="busines text" 
								placeholder="Название сделки" 
								data-provide="typeahead" 
								data-items="20" 
								data-source=\'[' . $str . ']\'
							>
						';
						}
						echo '<input type="hidden" id="id-deal" value="' . $task['category']['id'] . '" />';
					}
					else
					{
						$str = '';

						foreach( $deals as $listDeals )
						{
							$str = '"' . $listDeals['name_deal'] .  '",' . $str;
						}
						$str = substr($str, 0, strlen($str) - 1) ;

						echo '
							<input type="text" 
								id="edit-name-deal-task" 
								autocomplete ="off"
								class="busines text" 
								placeholder="Название сделки" 
								data-provide="typeahead" 
								data-items="20" 
								data-source=\'[' . $str . ']\'
							>
						';
					}
				?>
				<button id="save-edit-task" class="button">Сохранить</button>
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