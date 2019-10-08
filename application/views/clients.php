<div id="content">
			<div class="page-info">	
				<ul id="ul-info">
					<li><h1>Клиенты</h1></li>
					<li style="padding: 4px 20px 0 0; height: 23px;"><a href="<?php echo base_url('clients'); ?>" <?php echo $subMenu['my']; ?>>Мои</a> <a href="<?php echo base_url('clients/all'); ?>" <?php echo $subMenu['all']; ?>>Все</a></li>
					<li>
						<button class="add_new">+ Добавить клиента</button>
						<button class="func_button" id="ajax-save-client">Сохранить</button>
						<button class="cancel">Отменить</button>
					</li>
				</ul>
				<div id="modal">
					<div class="left" style="width: 46%; margin: 0;">
						<h5>Контактное лицо</h5>
						<div class="sub_field">
							<input type="text" id="ajax-name-client" autocomplete ="off" class="client text" placeholder="Имя фамилия" />
							<input type="text" id="ajax-doljnost-client" autocomplete ="off" class="text job" placeholder="Должность" />
							<input type="text" id="ajax-phone-client" autocomplete ="off" class="text phone" placeholder="Контактный телефон"/>
							<input type="text" id="ajax-skype-client" autocomplete ="off" class="text skype" placeholder="Skype"/>
							<input type="text" id="ajax-email-client" autocomplete ="off" class="text at" placeholder="E-mail" />
							<input type="text" id="ajax-adres-client" autocomplete ="off" class="text street" placeholder="адрес" />
						</div>
					</div>
					<div class="middle"></div>
					<div class="right" style="width: 46%;">		
						<h5>Компания</h5>
						<div class="sub_field" style="margin: 0 auto; padding: 0; background: none;">
							<input type="text" 
								id="ajax-name-company" 
								autocomplete ="off"
								class="text company"
								placeholder="Добавить название компании"
								data-provide="typeahead" 
								data-items="20" 
								>
							<input type="text" id="ajax-phone-company" autocomplete ="off" class="text phone" placeholder="Контактный телефон" />
							<input type="text" id="ajax-skype-company" autocomplete ="off" class="text skype" placeholder="Skype" />
							<input type="text" id="ajax-email-company" autocomplete ="off" class="text at" placeholder="E-mail" />
							<input type="text" id="ajax-www-company" autocomplete ="off" class="text www" placeholder="www" />
							<input type="text" id="ajax-adres-company" autocomplete ="off" class="text street" placeholder="Добавить адрес" />
						</div>
					</div>					
				</div>
			</div>

			<table id="content-table">
				<tr class="title">
					<th>Клиент</th>
					<th>Телефон</th>
					<th>E-mail</th>
					<th>Адрес</th>
				</tr>
				<?php
					$freeFace = 1;
					foreach( $data_clients as $arr_list )
					{
						if( !empty($arr_list['company_name']) )
						{
							echo '<tr>';
								echo '<td class="td-company"><a href="' . base_url('clients/company/' . $arr_list['company_id']) . '" class="table-link-company">' . stripcslashes($arr_list['company_name']) . '</a></td>';
								if( !empty($arr_list['company_contact']['phone']) || $arr_list['company_contact']['phone'] != 0 )
								{
									echo '<td>' . $arr_list['company_contact']['phone'] . '</td>';
								}
								else
								{
									echo '<td></td>';
								}
								if( !empty($arr_list['company_contact']['email']) || $arr_list['company_contact']['email'] != 0 )
								{
									echo '<td>' . $arr_list['company_contact']['email'] . '</td>';
								}
								else
								{
									echo '<td></td>';
								}
								if( !empty($arr_list['company_adres']) || $arr_list['company_adres'] != 0 )
								{
									echo '<td>' . $arr_list['company_adres'] . '</td>';
								}
								else
								{
									echo '<td></td>';
								}
							echo '</tr>';

							if( !empty($arr_list['contact']) )
							{
								foreach( $arr_list['contact'] as $contact )
								{
									echo '<tr>';
										echo '<td class="td-people"><a href="' . base_url('clients/contact/' . $contact['id_contact']) . '" class="table-link-people">' . $contact['name_contact'] . '</a></td>';
										if( !empty($contact['phone_contact']) || $contact['phone_contact'] != 0 )
										{
											echo '<td>' . $contact['phone_contact'] . '</td>';
										}
										else
										{
											echo '<td></td>';
										}
										if( !empty($contact['email_contact']) || $contact['email_contact'] != 0 )
										{
											echo '<td>' . $contact['email_contact'] . '</td>';
										}
										else
										{
											echo '<td></td>';
										}
										if( !empty($contact['adres']) || $contact['adres'] != 0 )
										{
											echo '<td>' . $contact['adres'] . '</td>';
										}
										else
										{
											echo '<td></td>';
										}
									echo '</tr>';
								}
							}
						}
						else
						{
							if( $freeFace == 1 )
							{
								echo '<tr><td colspan="4">Физ.лица</td></tr>';
								$freeFace = 0;
							}

							echo '<tr>';
								echo '<td class="td-people-free client"><a href="' . base_url('clients/contact/' . $arr_list['id_contact']) . '" class="table-link-people">' . $arr_list['name_contact'] . '</a></td>';
								if( !empty($arr_list['phone_contact']) || $arr_list['phone_contact'] != 0 )
								{
									echo '<td>' . $arr_list['phone_contact'] . '</td>';
								}
								else
								{
									echo '<td></td>';
								}
								if( !empty($arr_list['email_contact']) || $arr_list['email_contact'] != 0 )
								{
									echo '<td>' . $arr_list['email_contact'] . '</td>';
								}
								else
								{
									echo '<td></td>';
								}
								if( !empty($arr_list['adres']) || $arr_list['adres'] != 0 )
								{
									echo '<td>' . $arr_list['adres'] . '</td>';
								}
								else
								{
									echo '<td></td>';
								}
							echo '</tr>';
						}
					}
				?>
			</table>
		</div>