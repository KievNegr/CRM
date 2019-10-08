<div id="content" style="margin-left: 100px;">
	<div id="notify-block">
		<div class="notify del">
			Задача удалена
		</div>
	</div>
	<div id="search_result"></div>
	<?php
		if( isset($_SERVER['HTTP_REFERER']) ):
	?>
	<div id="back" class="default_field" link="<?php echo $_SERVER['HTTP_REFERER']; ?>"></div>
	<?php
		endif;
	?>
	<div id="main" class="default_field">
		<input type="text" id="edit_fio" autocomplete ="off" style = "padding-left: 2px; width: 75%;" class="editField h1" placeholder="ФИО"  value = "<?php echo $user['name']; ?>" >
		<input type="hidden" id="edit_person" id_person="<?php echo $user['id']; ?>" />
		<div style="clear: both; height: 10px;"></div>
		<div class="right">
			<h3 class="up" style="background-color: #1D6DB8; color: #FFF; cursor: pointer;" view="tabsdeals">Сделки</h3>
			<div class="tabsdeals">
				<ul id="tabs-deal">
					<li show="active-deals" class="active">Активные</li>
					<li show="noactive-deals">Не активные</li>
				</ul>
				<?php
					
					echo '<ul class="active-deals">';
						if(count($active) > 0)
						{
							foreach($active as $activeD)
							{
								echo '<li>
										<a class="name_deal" href="' . base_url('deal/show_deal/' . $activeD['id']) . '" target="_blank">' . $activeD['name_deal'] . '</a>
										<p class="p-budget">Бюджет: ' . $activeD['budget'] . ' &euro;
									</li>';
							}
						}
						else
						{
							echo '<li class="no-deals">Активные сделки отсутствуют</li>';
						}
					echo '</ul>';

					echo '<ul class="noactive-deals">';
						if(count($noactive) > 0)
						{
							foreach($noactive as $noactiveD)
							{
								echo '<li>
										<a class="name_deal" href="' . base_url('deal/show_deal/' . $noactiveD['id']) . '" target="_blank">' . $noactiveD['name_deal'] . '</a>
										<p class="p-budget">Бюджет: ' . $noactiveD['budget'] . ' &euro;
									</li>';
							}
						}
						else
						{
							echo '<li class="no-deals">Завершенных или замороженных сделок нету</li>';
						}
					echo '</ul>';
				?>
			</div><!--tabsdeals-->
			<h3 class="up" style="background-color: #E49C00;; color: #FFF; cursor: pointer;" view="tabsclients">Клиенты</h3>
			<div class="tabsclients">
				<ul id="tabs-clients">
					<li show="company" class="active">Компании</li>
					<li show="contacts">Контактные лица</li>
				</ul>

				<?php
					
					echo '<ul class="company">';
						if(count($user['company']) > 0)
						{
							foreach($user['company'] as $company)
							{
								echo '<li>
										<a class="contact_company" href="' . base_url('clients/show_company/' . $company['id']) . '" target="_blank">' . $company['name'] . '</a>
									</li>';
							}
						}
						else
						{
							echo '<li class="no-deals">Компании отсутствуют</li>';
						}
					echo '</ul>';

					echo '<ul class="contacts">';
						if(count($user['clients']) > 0)
						{
							foreach($user['clients'] as $clients)
							{
								echo '<li>
										<a class="contact_face" href="' . base_url('clients/show_contact/' . $clients['id']) . '" target="_blank">' . $clients['fio'] . '</a>
									</li>';
							}
						}
						else
						{
							echo '<li class="no-deals">Контактные лица отсутствуют</li>';
						}
					echo '</ul>';
				?>
			</div><!--tabsclients-->

			<h3 class="up" style="background-color: #5EA359; color: #FFF; cursor: pointer;" view="tabstask">Задачи</h3>
			<div class="tabstask">
				<ul id="tabs-tasks">
					<li show="task-active" class="active">В работе</li>
					<li show="task-over">Выполненные</li>
					<li show="task-deadline">Просроченные</li>
					<li show="task-assigned">Назначенные</li>
				</ul>

				<?php
					
					echo '<ul class="task-active">';
						if(count($taskActive) > 0)
						{
							foreach($taskActive as $tActive)
							{
								echo '<li class="' . $tActive['id'] . '">
										<a class="later-task" href="' . base_url('task/show_task/' . $tActive['id']) . '" target="_blank">' . $tActive['title'] . '</a>
										<p class="p-task">Назначена: ' . substr($tActive['create_date'], 8, 2) . '.' . substr($tActive['create_date'], 5, 2) . '.' . substr($tActive['create_date'], 0, 4) . '</p>
										<p class="p-task">Срок до: ' . substr($tActive['deadline'], 8, 2) . '.' . substr($tActive['deadline'], 5, 2) . '.' . substr($tActive['deadline'], 0, 4) . '</p>
										<div class="admin-del-task" id_task="' . $tActive['id'] . '">Удалить</div>
									</li>';
							}
						}
						else
						{
							echo '<li class="no-deals">Задачи в работе отсутствуют</li>';
						}
					echo '</ul>';

					echo '<ul class="task-over">';
						if(count($taskOver) > 0)
						{
							foreach($taskOver as $tOver)
							{
								echo '<li class="' . $tOver['id'] . '">
										<a class="tomorrow-task" href="' . base_url('task/show_task/' . $tOver['id']) . '" target="_blank">' . $tOver['title'] . '</a>
										<p class="p-task">Назначена: ' . substr($tOver['create_date'], 8, 2) . '.' . substr($tOver['create_date'], 5, 2) . '.' . substr($tOver['create_date'], 0, 4) . '</p>
										<p class="p-task">Срок до: ' . substr($tOver['deadline'], 8, 2) . '.' . substr($tOver['deadline'], 5, 2) . '.' . substr($tOver['deadline'], 0, 4) . '</p>
										<p class="p-task">Завершена: ' . substr($tOver['data_finish'], 8, 2) . '.' . substr($tOver['data_finish'], 5, 2) . '.' . substr($tOver['data_finish'], 0, 4) . '</p>
										<div class="admin-del-task" id_task="' . $tOver['id'] . '">Удалить</div>
									</li>';
							}
						}
						else
						{
							echo '<li class="no-deals">Завершенных задач нету</li>';
						}
					echo '</ul>';

					echo '<ul class="task-deadline">';
						if(count($taskDeadline) > 0)
						{
							foreach($taskDeadline as $tDeadline)
							{
								echo '<li class="' . $tDeadline['id'] . '">
										<a class="hot-task" href="' . base_url('task/show_task/' . $tDeadline['id']) . '" target="_blank">' . $tDeadline['title'] . '</a>
										<p class="p-task">Назначена: ' . substr($tDeadline['create_date'], 8, 2) . '.' . substr($tDeadline['create_date'], 5, 2) . '.' . substr($tDeadline['create_date'], 0, 4) . '</p>
										<p class="p-task">Срок до: ' . substr($tDeadline['deadline'], 8, 2) . '.' . substr($tDeadline['deadline'], 5, 2) . '.' . substr($tDeadline['deadline'], 0, 4) . '</p>
										<div class="admin-del-task" id_task="' . $tDeadline['id'] . '">Удалить</div>
									</li>';
							}
						}
						else
						{
							echo '<li class="no-deals">Просроченных задач нету</li>';
						}
					echo '</ul>';

					echo '<ul class="task-assigned">';
						if(count($taskAssigned) > 0)
						{
							foreach($taskAssigned as $tAssigned)
							{
								echo '<li class="' . $tAssigned['id'] . '">
										<a class="today-task" href="' . base_url('task/show_task/' . $tAssigned['id']) . '" target="_blank">' . $tAssigned['title'] . '</a>
										<p class="p-task">Назначена: ' . substr($tAssigned['create_date'], 8, 2) . '.' . substr($tAssigned['create_date'], 5, 2) . '.' . substr($tAssigned['create_date'], 0, 4) . '</p>
										<p class="p-task">Срок до: ' . substr($tAssigned['deadline'], 8, 2) . '.' . substr($tAssigned['deadline'], 5, 2) . '.' . substr($tAssigned['deadline'], 0, 4) . '</p>';
								if($tAssigned['data_finish'] != NULL || !empty($tAssigned['data_finish']))
								{
									echo '<p class="p-task">Завершена: ' . substr($tAssigned['data_finish'], 8, 2) . '.' . substr($tAssigned['data_finish'], 5, 2) . '.' . substr($tAssigned['data_finish'], 0, 4) . '</p>';
								}
								echo '<div class="admin-del-task" id_task="' . $tAssigned['id'] . '">Удалить</div>
									</li>';
							}
						}
						else
						{
							echo '<li class="no-deals">Назначенных задач нету</li>';
						}
					echo '</ul>';
				?>
			</div><!--tabstask-->
		</div>

		<div class="left">
			
			<div class="sub_field">		
				<h5>Информация</h5>			
				<?php
					if( !empty( $user['group'] ) || $user['group'] != 0 )
					{
						$group = $user['group'];
					}
					else
					{
						$group = '';
					}
				?>
				<input type="text" id="edit_doljnost" style="margin-top: 10px;" autocomplete ="off" class="editField job" placeholder="Добавить должность"  value = "<?php echo $group; ?>" >
				<?php
					if(  !empty( $user['phone'] ) || $user['phone'] != 0 )
					{
						$phone = stripslashes($user['phone']);
					}
					else
					{
						$phone = '';
					}
				?>
				<input type="text" id="phone_edit_client" autocomplete ="off" class="editField phone" placeholder="Контактный телефон"  value = "<?php echo $phone; ?>" >
				<!--<?php
					if( !empty( $data_clients['skype'] ) || $data_clients['skype'] != 0 )
					{
						$skype = stripslashes($data_clients['skype']);
					}
					else
					{
						$skype = '';
					}
				?>
				<input type="text" id="skype_edit_client" autocomplete ="off" class="editField skype" placeholder="Добавить Skype"  value = "<?php echo $skype; ?>" >-->
				<?php
					if(  !empty( $user['email'] ) || $user['email'] != 0 )
					{
						$email = stripslashes($user['email']);
					}
					else
					{
						$email = '';
					}
				?>
				<input type="text" id="email_edit_client" autocomplete ="off" class="editField at" placeholder="Электропочта"  value = "<?php echo $email; ?>" >
				
				
			</div>
			
			<div class="sub_field">
				<h5>Активность</h5>
				<p class="p-left">Дата регистрации: <?php echo date('d.m.Y', $user['create']); ?></p>
				<p class="p-left">Крайняя активность: <?php echo date('d.m.Y', $user['lastlogin']); ?></p>
			</div>

		</div>
	</div>
</div>