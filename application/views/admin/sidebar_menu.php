
<div id="menu-field" style="background: rgba(60, 8, 8, 1);">
	<ul id="menu">
		<?php
			if( $location == 'home')
			{
				echo '<li class="home active" style="width: 51px; padding: 38px 0 0 14px;">Admin</li>';
			}
			else
			{
				echo '<a href="' . base_url('admin') . '"><li class="home" style="width: 51px; padding: 38px 0 0 14px;">Admin</li></a>';
			}

			if( $location == 'deal')
			{
				echo '<li class="deals active">Сделки</li>';
			}
			else
			{
				echo '<a href="' . base_url('admin/deal') . '"><li class="deals">Сделки</li></a>';
			}

			if( $location == 'clients')
			{
				echo '<li class="clients active">Клиенты</li>';
			}
			else
			{
				echo '<a href="' . base_url('admin/clients') . '"><li class="clients">Клиенты</li></a>';
			}

			if( $location == 'task')
			{
				echo '<li class="tasks active">Задачи</li>';
			}
			else
			{
				echo '<a href="' . base_url('admin/task') . '"><li class="tasks">Задачи</li></a>';
			}

			if( $location == 'users')
			{
				echo '<li class="users active" style="width: 50px; padding: 38px 0 0 15px;">Рабы</li>';
			}
			else
			{
				echo '<a href="' . base_url('admin/users') . '"><li class="users" style="width: 50px; padding: 38px 0 0 15px;">Рабы</li></a>';
			}
		?>
	</ul>
</div>