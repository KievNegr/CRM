<div id="content">
			<div class="page-info">
				<ul id="ul-info">
					<li><h1>Сделки</h1></li>
					<li style="padding: 4px 20px 0 0; height: 23px;">
						<a href="<?php echo base_url('deals');?>" <?php echo $subMenu['active']; ?>>Активные</a>&nbsp;
						<a href="<?php echo base_url('deals/my');?>" <?php echo $subMenu['my']; ?>>Мои</a>&nbsp;
						<?php
							foreach( $subMenu['sub'] as $subItem )
							{
								echo $subItem . '&nbsp;';
							}
						?>
					</li>
					<li>
						<button class="add_new">+ Добавить сделку</button>
						<button class="func_button" id="ajax-create-deal">Сохранить</button>
						<button class="cancel">Отменить</button>
					</li>
				</ul>
				<div id="modal">
					<div class="left">
						<input type="text" id="ajax-name-deal" autocomplete="off" class="addField title" placeholder="Добавить название сделки">
						<textarea placeholder="Добавить описание сделки" id="ajax-description-deal" class="addField textarea"></textarea>
						<div class="sub_field">
							<h5>Детали сделки</h5>
							<input type="text" id="ajax-budget" autocomplete="off" class="addField budget" placeholder="Добавить бюджет сделки">
							<div style="clear: both;"></div>
							<span id="fast-ajax-status-deal" class="status" style="margin: 10px 0 0 2px; display: inline-block; background: #52a50e">В разработке</span>
							<ul id="ajax-status-deal">
								<?php
									$id_status = 1;
									foreach( $state_deal as $edit_list )
									{
										if( $edit_list['id'] == $id_status )
										{
											echo '<li class="active" status_id="' . $edit_list['id'] . '" style="background-color: #' . $edit_list['color'] . ';">' . $edit_list['value'] . '</li>';
										}
										else
										{
											echo '<li status_id="' . $edit_list['id'] . '" style="background-color: #' . $edit_list['color'] . ';">' . $edit_list['value'] . '</li>';
										}
									}
								?>				
							</ul>
						</div>
			
						<div class="sub_field">
							<h5>Контактное лицо</h5>
							<input type="text" 
								id="ajax-client" 
								autocomplete ="off"
								class="addField client text" 
								placeholder="Добавить контактное лицо" 
								data-provide="typeahead"
								data-items="20" 
								data-source='[
									<?php
										$str = '';
										foreach( $all_clients as $list_clients )
										{
											$str = '"' . $list_clients .  '",' . $str;
										}
										echo  substr($str, 0, strlen($str) - 1) ;
									?>
								]'>
						</div>
					</div>
					<div class="right">		
						
					</div>					
				</div>
			</div>

			<?php
				$surrentStatus = $deal['0']['status_id'];
				$color = $deal['0']['color'];
				$statusName = $deal['0']['status_deal'];
			?>

			<div class="column">
				<div class="deal-title">
					<h4 style="color: #<?php echo $color;?>;"><?php echo $statusName; ?></h4>
					<p class="count-deals">Сделок: 10</p>
				</div>

				<?php
					foreach( $deal as $list_deal )
					{
						if( $surrentStatus != $list_deal['status_id'] )
						{
							echo '</div>';
							echo '<div class="column">';
							echo '<div class="deal-title">';
								echo '<h4 style="color: #' . $list_deal['color'] . '">' . $list_deal['status_deal'] . '</h4>';
								echo '<p class="count-deals">Сделок: 10</p>';
							echo '</div>';
							$surrentStatus = $list_deal['status_id'];
						}

						if( !empty($list_deal['company_id']) && $list_deal['company_id'] != 0 )
						{
							$c = '<strong><a href="' . base_url('clients/company/' . $list_deal['company_id']) .'" class="client-link company">' . $list_deal['company_name'] . '</a></strong><br />';
						}
						else
						{
							if( !empty($list_deal['name_contact_face']) && $list_deal['name_contact_face'] != 0 )
							{
								$c = '';
							}
							else
							{
								$c = '';
							}
						}
						$dt = (strtotime(date('Y-m-d')) - strtotime($list_deal['data']))/(3600*24);
						if( $dt == 0){
							$data = 'Сегодня';
						}
						elseif( $dt == 1 ){
							$data = 'Вчера';
						}
						else{
							$data = substr($list_deal['data'], 8, 2 ) . '.' . substr($list_deal['data'], 5, 2 ) . '.' . substr($list_deal['data'], 0, 4 );
						}	


						echo '<div class="box-deal">';
							echo '<div class="avatar" style="background-image: url(' . base_url('img/avatar.png') . ');"></div>';
							echo '<a href="' . base_url('deals/deal/' . $list_deal['id']) .'" class="name_deal">' . $list_deal['name_deal'] . '</a>';
							echo '<p class="list-budget">' . $list_deal['budget'] . ' ' . $currency[$list_deal['currencyId'] - 1]['attr'] . '</p>';
							echo $c;
							if( !empty($list_deal['sub_contact']) )
							{
								$links_contact = '';
								$n = 0;
								foreach( $list_deal['sub_contact'] as $view_contact )
								{
									$links_contact .= '<a href="' . base_url('clients/contact/' . $view_contact['id']) .'" class="client-link client">' . $view_contact['fio'] . '</a><br />';
									$n++;
									if( $n > 2 )
									{
										$links_contact = $links_contact . '<br />';
										$n = 0;
									}
								}
								echo substr($links_contact, 0, -6);
							}
							echo '<p class="author-deal">' . $list_deal['author_deal']['name'] . '</p>';
						echo '</div>';
					}
				?>
				</div>
		</div>