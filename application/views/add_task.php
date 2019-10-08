	<div id="add_user">
		<div class="title">
			<h2>Сотрудники</h2>
			<div class="add_user_close"></div>
		</div>
		<a href="#" id="select_all">Выбрать всех</a>
		<ul id="list_user">
			<?php 
				foreach( $users as $list ):
			?>
			<li><div class="not_user_selected" id="user_<?php echo $list['id']; ?>"></div><?php echo $list['first_name'] . ' ' . $list['last_name']; ?></li>
			<?php
				endforeach;
			?>
		</ul>
	</div><!--//add_user-->