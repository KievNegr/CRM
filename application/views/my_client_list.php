<div id="content">
	<button class="add_new" style="float: left">Добавить компанию</button>
			<div class="search">
				<input type="text" class="search_text" placeholder="Искать в клиентах" />
				<button class="search_button" style="float: left">Найти</button>
			</div>
			<div style="clear: both;"></div>
			<div class="field">
				<h1>Мои клиенты</h1>
			<?php
				if( count( $data_clients ) == 0 ):
					echo '<div class="span_info">Моих клиентов еще нету</div>';
				else:
			?>
		<table>
			<tr class="title">
				<td>Компания</td>
				<td>Контактные лица</td>
				<td>Телефон</td>
				<td>Web-сайт</td>
				<td>Адрес</td>
			</tr>
				<?php
					$back = 0;
					foreach( $data_clients as $arr_list )
					{
						if( $arr_list['phone'] != 0 || !empty( $arr_list['phone'] ) )
						{
							$phone = str_replace(',' , '<br />', $arr_list['phone']);
						}
						else
						{
							$phone = '';
						}
						if( $back == 0 )
						{
							echo '<tr class="blue">';
							$back = 1;
						}
						else
						{
							echo '<tr class="white">';
							$back = 0;
						}
						echo '<td><a href="' . base_url('clients/show_company/' . $arr_list['company_id']) . '" class="name_deal">' . stripslashes($arr_list['company_name']) . '</a></td>';
						echo '<td><ul id="persons">';
						foreach( $arr_list['person'] as $person )
						{
							echo '<li><a href="' . base_url('clients/show_contact/' . $person['id']) . '" class="contact_face">' . $person['fio'] . '</a></li>';
						}
						echo '</ul></td>';
						echo '<td>' . $phone . '</td>';
						
						if( !empty( $arr_list['www'] ) )
						{
							if( substr($arr_list['www'], 0, 7) != 'http://' )
							{
								$url = 'http://' . $arr_list['www'];
							}
							else
							{
								$url = $arr_list['www'];
							}
							echo '<td><a href="' . $url . '" class="contact_www">website</a></td>';
						}
						else
						{
							echo '<td></td>';
						}
						
						if( empty( $arr_list['city'] ) && empty( $arr_list['country'] ) )
						{
							echo '<td></td>';
						}
						elseif( empty( $arr_list['country'] ) )
						{
							echo '<td>г. ' . $arr_list['city'] . '</td>';
						}
						elseif( empty( $arr_list['city'] ) )
						{
							echo '<td>' . $arr_list['country'] . '</td>';
						}
						else
						{
							echo '<td>' . $arr_list['country'] . ', ' . $arr_list['city'] . '</td>';
						}
						echo '</tr>';
					}
				?>
			</table>
		<?php
			endif;
		?>
	</div><!--/content-->