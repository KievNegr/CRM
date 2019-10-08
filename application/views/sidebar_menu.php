echo '<div class="transfer' . $list_hot_task['id_task'] . '">';
						echo '<div class="edit-task-deal-clear" id="edit-task-from-deal-' . $list_hot_task['id_task'] . '"></div>';
						echo '<div class="show_event" id="view_task_' . $list_hot_task['id_task'] . '">';
						echo '<div class="functional-task-deal">';
							if( $list_hot_task['state'] != 2)
							{
								echo '';
							}
							else
							{
								echo '<div style="width: 15px; height: 15px; float: left;"></div>';
							}
							if( $profile['id'] == $list_hot_task['author_id'] )
							{
								echo '<div class="edit-task-deal" id_task="' . $list_hot_task['id_task'] .'"></div>';
								echo '<div class="del-task-deal" id_task="' . $list_hot_task['id_task'] . '"></div>';
							}
							
					  	echo '</div>';
					  	$l_users = '';
					  	$ending = '';
					  	$width = '88px';
					  	foreach( $list_hot_task['task']['users'] as $l_temp )
					  	{
					  		$l_users .= $l_temp['name'] . ', ';
					  		if( $l_temp['id_user'] == $profile['id'] )
					  		{
					  			$ending = '<div class="ending-task-deal hastip" title="Завершить задачу" id_task="' . $list_hot_task['id_task'] . '">Завершить</div>';
					  			$width = '160px';
					  		}
					  	}
					  	$l_users = substr($l_users, 0, -2);