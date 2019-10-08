<div id="content" style="margin-left: 100px;">
	<table class="default_field">
		<tr class="title">
			<th style="width: 10%;">Имя</th>
			<th style="width: 10%;">Телефон</th>
			<th style="width: 10%;">E-mail</th>
			<th style="width: 1%;">Группа</th>
		</tr>
		<?php
			foreach($users as $listUsers):
				$avatar = 'default.png';
				if( $listUsers['avatar'] != NULL )
				{
					$avatar = $listUsers['avatar'];
				}

				foreach( $group as $listGroup )
				{
					if($listGroup['id'] == $listUsers['group_id'])
					{
						$groupUser = $listGroup['description'];
					}
				}
		?>
		<tr>
			<td>
				<img src="<?php echo base_url('img/avatars/' . $avatar);?>" class="avatar">
				<a href="<?php echo base_url('admin/showuser/' . $listUsers['id']);?>" class="name"><?php echo $listUsers['name'];?></a></td>
			<td><?php echo $listUsers['phone'];?></td>
			<td><a href="mailto: <?php echo $listUsers['email'];?>" class="email"><?php echo $listUsers['email'];?></a></td>
			<td><?php echo $groupUser;?></td>
		</tr>
		<?php
			endforeach;
		?>
</div><!--/content-->