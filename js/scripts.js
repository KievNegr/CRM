$(document).ready(function()
{
	var close = 0;
	var base_url = 'http://crm2/';

	$('#edit_profile_field').css('height', $(document).height());
	$('#sidebar').css('height', $(document).height());
	$('#menu-deal').css('height', $(document).height() - 60);
	$('#show-status-task').css('height', $(document).height());
	$('#wrapper').css('min-height', $(window).height());
	$('#fade').css('height', $(document).height());
	$('#edit_profile_field img').load(function()
	{
		avatar_width = $(this).width();
		$(this).css('border-radius', avatar_width / 2);
	});

	//Временно$('textarea').autosize();
	//$('#descr_edit_company').autosize();
	
	
	
	setTimeout(show_graph, 1500);

	function show_graph()
	{
		$('.graphic').each(function()
		{
			w = $(this).attr('wdth');
			if( w > 0 )
			{
				$(this).animate({
				'width': w + '%',
				'padding': '6px 10px 0 10px'
				}, 1000);
				$(this).html(w + ' %');
			}
		});
	}

	var opened = 0;
	$('#open_sidebar').click(function()
	{	
		if( opened == 0 )
		{
			$('#sidebar').animate({
			'left': 0
			}, 300);
			opened = 1;
			$('#open_sidebar .line').css('background','#0C5904');
		}
		else
		{
			$('#sidebar').animate({
			'left': '-25%'
			}, 300);
			opened = 0;
			$('#open_sidebar .line').css('background','#FFF');
		}
	});

	$('#back').click(function()
	{
		$(location).attr('href', $(this).attr('link'));
	})

	$('h1').click(function()
	{
		$(location).attr('href', $(this).attr('link'));
	})

	
	$('.list').click(function()
	{
		$('.tasks_list').css({'-moz-column-count':'1', '-webkit-column-count': '1', 'column-count':'1'});
	});
	
	$('.flat').click(function()
	{
		$('.tasks_list').css({'-moz-column-count':'6', '-webkit-column-count': '6', 'column-count':'6'});
	});
	
	$('#logout').click(function()
	{
		$(location).attr('href','http://crm2/auth/logout');
	});
	
	/* Список новых событий в шапке*/
	show_alert = 0;
	$('.alert').click(function()
	{
		if( show_alert == 0 )
		{
			$('#alert_tooltip').fadeIn();
			show_alert = 1;
		}
		else
		{
			$('#alert_tooltip').hide();
			show_alert = 0;
		}
	});
	
	$('.description-field').click(function()
	{
		$('.text-description').fadeIn();
		$(this).hide(1);
	});

	/* Редактор профиля */

	//открытие окна профиля
	$('#profile').click(function()
	{		
		$('#edit_profile_field').animate({
			'right': 0		
		}, 300);

		$('#wrapper').animate({
			'opacity': '.1'			
		}, 300);

		$('#edit_profile_field .load').load('http://crm2/main/loadInfo');
	});

	//Закрытие окна профиля
	$('#edit_profile_field .close').click(function()
	{
		$('#edit_profile_field').animate({
			'right': '-272px'		
		}, 300);
		$('#wrapper').animate({
			'opacity': '1'			
		}, 300);

		$('#edit_profile_field .author').fadeIn();
		$('#edit_profile_field .profile_hide').fadeIn();

		$('#edit_profile_phone').fadeOut();
		$('#edit_profile_password').fadeOut();
		$('#edit_profile_email').fadeOut();
		$('#edit_profile_name').fadeOut();
		$('#edit_profile_lastname').fadeOut();
		$('#edit_profile_field .mini_save').fadeOut();
		$('#edit_profile_field .avatar img').css('margin-left','-60px');
	});

	//Показ окна редактора данных
	$('#edit_profile_field .author').click(function()
	{
		$('#edit_profile_field .author').fadeOut();
		$('#edit_profile_field .profile_hide').fadeOut();
		$('#edit_profile_field .avatar img').css('opacity','.2');

		$('#edit_profile_field .edit').fadeIn();
		$('#edit_profile_phone').fadeIn();
		$('#edit_profile_password').fadeIn();
		$('#edit_profile_email').fadeIn();
		$('#edit_profile_name').fadeIn();
		$('#edit_profile_lastname').fadeIn();
		$('#edit_profile_field .mini_save').fadeIn();
		$('#edit_profile_field .avatar img').css('margin-left','0');
	});

	//Нажатие кнопки сохранения данных профиля
	$('#edit_profile_field .mini_save').click(function()
	{
		name = $('#edit_profile_name').val();
		last_name = $('#edit_profile_lastname').val();
		email = $('#edit_profile_email').val();
		phone = $('#edit_profile_phone').val();
		pass = $('#edit_profile_password').val();

		if( !name )
		{
			$('#edit_profile_name').css('border', '1px solid #7b160f');
		}
		else
		{
			$('#edit_profile_name').css('border', '1px solid #FFF');
			
			$.ajax({
				type: 'post',
				url: 'http://crm2/main/edit_profile',
				data: {
					'name': name,
					'last_name': last_name,
					'email': email,
					'phone': phone,
					'pass': pass
				},
				success: ok_edit_profile
			});
		}
	});

	function ok_edit_profile(data)
	{
		alert(data);
	}

	/* Модальное окно */
	$('.add_new').click(function()
	{
		$('.func_button').fadeIn();

		$('.func_button').animate({
			backgroundColor: '#0D1BCB'			
		}, 300);

		$('.func_button').animate({
			backgroundColor: '#97BB96'			
		}, 300);

		$('.func_button').animate({
			backgroundColor: '#0D1BCB'			
		}, 300);

		$('.func_button').animate({
			backgroundColor: '#97BB96'			
		}, 300);

		$('.cancel').fadeIn(1000);

		$('.fast_add').css('display','none');
		$('.fast').css('display','none');
		$('#modal').slideDown(300);
		$('.search').hide();
		$('#search_result').html('');
		$(this).hide();
	});

	$('.func_button').hover(
		function()
		{
			$(this).css('background', '#6AA1E8');
		},
		function()
		{
			$(this).css('background', '#97BB96');
		}
	);

	$('.add_new').hover(
		function()
		{
			$(this).css('background', '#6AA1E8');
		},
		function()
		{
			$(this).css('background', '#97BB96');
		}
	);

	$('.cancel').click(function()
	{
		cancel();
	});

	function cancel()
	{
		$('.func_button').hide();
		$('.cancel').hide();

		$('.add_new').fadeIn();
		$('.add_new').animate({
			backgroundColor: '#0D1BCB'			
		}, 300);

		$('.add_new').animate({
			backgroundColor: '#97BB96'			
		}, 300);

		$('.add_new').animate({
			backgroundColor: '#0D1BCB'			
		}, 300);

		$('.add_new').animate({
			backgroundColor: '#97BB96'			
		}, 300);

		$('#modal').slideUp(300);
	}

	$('.add_new_task').click(function()
	{
		$('#adding-task').show(300);
		$('.search').hide();
		$(this).hide();
		$('.func_button').fadeIn();
		$('#search_result').html('');
	});

	$('#ajax_cancel_task').click(function()
	{
		$('#adding-task').hide(300);
		$('.search').show();
		$('.func_button').fadeOut(10);
		$('.add_new_task').fadeIn(500);
		$('#search_result').html('');
	});

	$('#modal .close').click(function()
	{
		close();
	});
	
	function close()
	{
		$('#wrapper').animate({
			'left': '0'			
			}, 300);
		
		$('#sidebar').animate({
			'left': '0'			
			}, 300);
			
		$('#modal').animate({
			'left': '-550px'			
			}, 300);
			
		$('#add_user').animate({
			'left': '-250px'			
			}, 300);
	}
	
	/*Приложить файл
	$('#edit_profile_field .avatar img').on('click', function()
	{
		$('.add_file').click();
	});
	
	$('.file_btn').on('click', function()
	{
		$('.add_file').click();
	});*/
	
	/*----------------------------------------------------------------------- Добавление в БД -------------------------------------------------------------------------*/
	
	//Добавление новой компании
	$('html').on('click', '#ajax-save-client', function()
	{
		
		nameCompany = $('#ajax-name-company').val().trim();
		nameClient = $('#ajax-name-client').val().trim();
		//if( !name_company )
		//{
			//$('#ajax_name_company').css('border', '1px solid #7b160f');
		//}
		if( !nameClient &&  !nameCompany )
		{
			$('#ajax-name-client').css('border', '1px solid #7b160f').val('');
			$('#ajax-name-company').css('border', '1px solid #7b160f').val('');
			$('.error').fadeIn(500);
		}
		else
		{
			$('#ajax-name-company').css('border', '1px solid #DADADA');
			$('#ajax-name-client').css('border', '1px solid #DADADA');
			adresCompany = $('#ajax-adres-company').val();
			skypeCompany = $('#ajax-skype-company').val();
			phoneCompany = $('#ajax-phone-company').val();
			emailCompany = $('#ajax-email-company').val();
			wwwCompany = $('#ajax-www-company').val();

			doljnostClient = $('#ajax_doljnost_client').val();
			adresClient = $('#ajax_adres_client').val();
			skypeClient = $('#ajax_skype_client').val();
			phoneClient = $('#ajax_phone_client').val();
			emailClient = $('#ajax_email_client').val();
			
			$.ajax({
				type: 'post',
				url: 'http://crm2/clients/createCompany',
				data: {
					'nameCompany': nameCompany,
					'adresCompany': adresCompany,
					'skypeCompany': skypeCompany,
					'phoneCompany': phoneCompany,
					'emailCompany': emailCompany,
					'wwwCompany': wwwCompany,
					'nameClient': nameClient,
					'doljnostClient': doljnostClient,
					'adresClient': adresClient,
					'skypeClient': skypeClient,
					'phoneClient': phoneClient,
					'emailClient': emailClient,
				},
				success: okClient
			});
		}
	});
	
	function okClient(data){
		alert(data);
	}
	
	//Добавление нового контактного лица
	$('#ajax_save_face').click(function()
	{
		name_client = $('#ajax_name_client').val();
		if( !name_client )
		{
			$('#ajax_name_client').css('border', '1px solid #7b160f');
		}
		else
		{
			$('#ajax_name_client').css('border', '1px solid #E8E8E8');
			company_client = $('#ajax_select_client_company').val();
			doljnost_client = $('#ajax_doljnost_client').val();
			adres_client = $('#ajax_adres_client').val();
			skype_client = $('#ajax_skype_client').val();
			phone_client = $('#ajax_phone_client').val();
			email_client = $('#ajax_email_client').val();

			$.ajax({
				type: 'post',
				url: 'http://crm2/clients/create_client',
				data: {
					'name_client': name_client,
					'company_client': company_client,
					'doljnost_client': doljnost_client,
					'adres_client': adres_client,
					'skype_client': skype_client,
					'phone_client': phone_client,
					'email_client': email_client
				},
				success: ok_face
			});
		}
	});
	
	function ok_face(data){
		$('#newContactFace').fadeIn(100);
		$('#addingFace').css('display','none');
		$('#faces').prepend(data);
		$('.addFaceField').val('');
	}

	$('.btn-cncl-face').click(function()
	{
		$('#newContactFace').fadeIn(100);
		$('#addingFace').slideUp(300);
		$('.addFaceField').val('');
		$('#ajax_name_client').css('border', '1px solid #E8E8E8');
	});

	/*---------------------Удаление контактного лица с основного списка -------------------------*/
	$('.del-client-table').click(function()
	{
		id = $(this).attr('delete');
		$.ajax({
			type: 'post',
			url: 'http://crm2/clients/delete_contact',
			data: {
				'id': id
			},
			success: ok_delete_contact
		});
	});

	function ok_delete_contact(data)
	{
		$('#' + data).fadeOut();
	}
	
	
	$('#ajax_company').change(function()
	{
		get_data($(this).val());
	});
	
	function get_data(name)
	{
		$.ajax({
			type: 'post',
			url: 'http://crm2/deal/get_client_add_deal',
			data: {
				'name': name
			},
			success: ok_client_deal
		});
	}
	
	function ok_client_deal(data){
		$('#ajax_client').html(data);
	}
	
	/*-------------------- Добавление сделки -------------------*/

	$('#ajax_fast_open_deal').click(function()
	{
		$(this).css('display','none');
		$('.fast').css('display','inline-block');
	});

	$('#cancel_deal').click(function()
	{
		$('#ajax_fast_open_deal').fadeIn();
		$('.fast').css('display','none');
	});

	$('.addFaceField').keyup(function()
	{
		showSaveFormFace();
	});

	$('#fast-ajax-status-deal').click(function()
	{
		$('#ajax-status-deal').fadeIn();
		$(this).css('display','none');
	});

	$('#ajax-status-deal li').click(function()
	{
		$('#ajax-status-deal li').removeClass('active');
		$(this).addClass('active');
		new_text_deal = $(this).text();
		color = $(this).css('background-color');
		$('#fast-ajax-status-deal').css({'background-color': color, 'display':'inline-block'}).html(new_text_deal);	
		$('#fast-ajax-status-deal').fadeIn();
		$('#ajax-status-deal').css('display','none');
	});

	//Добавление новой сделки
	$('html').on('click', '#ajax-create-deal', function()
	{
		nameDeal = $('#ajax-name-deal').val().trim();
		if( !nameDeal )
		{
			$('#ajax-name-deal').css('border', '1px solid #7b160f').val('');
			$('.error').fadeIn(500);
		}
		else
		{
			$('#ajax-name-deal').css('border', '1px solid #DADADA');
			descrDeal = tinyMCE.get('ajax-description-deal').getContent();
			clientDeal = $('#ajax-client').val();
			budgetDeal = $('#ajax-budget').val();
			currencyDeal = $('#ajax-currency').val();
			statusDeal = $('#ajax-status-deal .active').attr('status_id');

			$.ajax({
				type: 'post',
				url: 'http://crm2/deal/createDeal',
				data: {
					'nameDeal': nameDeal,
					'clientDeal': clientDeal,
					'budgetDeal': budgetDeal,
					'currencyDeal': currencyDeal,
					'statusDeal': statusDeal,
					'descrDeal': descrDeal
				},
				success: okCreateDeal
			});
		}
	});
	
	function okCreateDeal(data){
		alert(data);
	}

	$('#ajax_fast_save_deal').click(function()
	{
		ajax_name_deal = $('#ajax_name_fast_deal').val();
		if( !ajax_name_deal )
		{
			$('#ajax_name_fast_deal').css('border', '1px solid #7b160f');
		}
		else
		{
		$('#ajax_name_fast_deal').css('border', '1px solid #747474');
			
			$.ajax({
				type: 'post',
				url: 'http://crm2/deal/create_deal',
				data: {
					'ajax_name_deal': ajax_name_deal,
					'ajax-status-deal': '1'
				},
				success: ok_fast_deal
			});
		}
	});
	
	function ok_fast_deal(data){
		$(location).attr('href','http://crm2/deal');
	}

	/*---------------------------------------- Функции задач ---------------------------------------------*/
	
	var select = 0;
	/*правое окно сотрудники*/
	$('html').on('click', '.select_username', function()
	{
		$('#list_user li div').removeClass('user_selected');
		$('#list_user li div').addClass('not_user_selected');

		$('#adding_user li').each(function() {
		  	id = $(this).attr('class');//.substring(5);
		  	$('#' + id).removeClass('not_user_selected');
			$('#' + id).addClass('user_selected');
		});

		$('#add_user').css('height', $(window).height());
								
		$('#add_user').animate({
			'right': '0'			
		}, 300);
	});
	
	$('.add_user_close').click(function()
	{
		$('#add_user').animate({
			'right': '-251px'			
			}, 300);
	});
	
	//Добавление юзера в список и удаление
	$('#list_user li').click(function()
	{
		if( $('div', this).attr('class') == 'not_user_selected' )
		{
			$('div', this).removeClass('not_user_selected');
			$('div', this).addClass('user_selected');
			id = $('div', this).attr('id').substring(5);
			$('#adding_user').append('<li class = "user_' + id + '">' + $(this).text() + '</li>');
		}
		else
		{
			$('div', this).removeClass('user_selected');
			$('div', this).addClass('not_user_selected');
			id = $('div', this).attr('id').substring(5);
			$('#adding_user .user_' + id).remove();
			$('#select_all').html('Выбрать всех');
			select = 0;
		}	

		$('#ajax_save_edit_task').fadeIn();
		$('#ajax_finish_task').hide();
		close = 1;
	});
	
	//Удаление из списка
	$('html').on('click', '#adding_user li', function()
	{
		$(this).remove();
		id = $(this).attr('class').substring(5);
		$('#user_' + id).removeClass('user_selected');
		$('#user_' + id).addClass('not_user_selected');
		$('#select_all').html('Выбрать всех');
		select = 0;

		$('#ajax_save_edit_task').fadeIn();
		$('#ajax_finish_task').hide();
		close = 1;
	});
	
	//Выбрать всех сотрудников
	
	$('#select_all').click(function()
	{
		if( select == 0 )
		{
			$('#adding_user li').remove();
			$('#list_user div').removeClass('user_selected');
			$('#list_user div').addClass('not_user_selected');
			$( "#list_user li" ).each(function() {
			  	id = $('div', this).attr('id').substring(5);
				$('#adding_user').append('<li class = "user_' + id + '">' + $(this).text() + '</li>');
			});
			$('#list_user div').removeClass('not_user_selected');
			$('#list_user div').addClass('user_selected');
			$(this).html('Снять выделение');
			select = 1;
		}
		else
		{
			//$( "#list_user div" ).each(function() {
				$('#adding_user li').remove();
		//	});
			$('#list_user div').removeClass('user_selected');
			$('#list_user div').addClass('not_user_selected');
			$(this).html('Выбрать всех');
			select = 0;
		}

		$('#ajax_save_edit_task').fadeIn();
		$('#ajax_finish_task').hide();
		close = 1;
	});
	
	$('#input_data').change(function()
	{
		$('.selected').attr('data', $(this).val());
	});
	
	//Назначение дедлайна
	$('html').on('click', '#selDate li', function()
	{
		$('#selDate li').removeClass('selDateActive');
		$(this).addClass('selDateActive');
	});

	$('.selectDate').change(function()
	{
		$('#selDate li').removeClass('selDateActive');
		$(this).parent().addClass('selDateActive');
		$(this).parent().attr('deadline', $(this).val());
	});

	//Заносим весь этот пиздец в БД
	$('html').on('click', '#ajax-save-task', function()
	{
		titleTask = $('#title-task').val();
		if( !titleTask )
		{
			$('#title-task').css('border', '1px solid #7b160f');
		}
		else
		{
			$('#title-task').css('border', '1px solid #DADADA');
			taskDescription = tinyMCE.get('ajax-task-description').getContent();
			taskDeadline = $('.selDateActive').attr('deadline');
			taskUserArr = $('#adding_user li').map(function(){
				return $(this).attr('class');
			}).get();
			$.ajax({
				type: 'post',
				url: 'http://crm2/tasks/createTask',
				data: {
					'titleTask': titleTask,
					'taskDescription': taskDescription,
					'taskDeadline': taskDeadline,
					'taskUserArr': taskUserArr,
				},
				success: okAddTask
			});
		}
	});
	
	function okAddTask(data){
		cancel();
		//alert(data);
	}
	
	//Функция завершения задачи

	$('html').on('click', '.ending-task-deal', function()
	{
		id = $(this).attr('id_task');
		$('#ajax_save_task_result').attr('save_task_id', id);
		task_animate();
	});

	//Завершение с главной задач
	$('html').on('click', '.ending_task', function()
	{
		id = $(this).attr('id_task');
		$('.task_view button').attr('disabled','disabled');
		$('#ajax_save_task_result').attr('save_task_id', id);
		//task_animate();
		alert(id);
	});

	$('html').on('click', '#ajax_finish_task', function()
	{
		id = $('#edit_task').attr('id_task');
		$('#ajax_save_task_result').attr('save_task_id', id);
		task_animate();
		//alert(id);
	});
	
	function task_animate()
	{
		$('#finish_task_field').animate({
			'top': '0'			
		}, 300);
		
		$('#wrapper').animate({
			'opacity': '.1'			
		}, 300);
		
		$('#check').animate({
				'top': '-110px'			
			}, 10);
	}
	
	$('html').on('click', '#ajax-save-task-result', function()
	{
			idTask = $('#id-task').val();
			idDeal = $('#edit_deal').attr('id_deal');
			resultText = tinyMCE.get('ajax-save-finish-result').getContent();
			$.ajax({
				type: 'post',
				url: 'http://crm2/tasks/setFinishTask',
				data: {
					'idTask': idTask,
					'idDeal': idDeal,
					'resultText': resultText
				},
				success: okFinishTask
			});
			
	});
	
	function okFinishTask(data){
		cancel();
	}

	//Комментирование задач
	$('html').on('click', '.comments_task', function(){
		id = $(this).attr('id_task');
		$(location).attr('href', 'http://crm2/task/show_task/' + id);
	});

	
	/*---------------------- Удаление задач --------------------------*/
	
	$('html').on('click', '.del-task-deal', function()
	{
		idTask = $(this).attr('id-task');
		
		$.ajax({
			type: 'post',
			url: 'http://crm2/tasks/setDellTask',
			data: {
				'idTask': idTask
			},
			success: okDellTask
		});
	});

	$('html').on('click', '.dell_task', function()
	{
		task_id = $(this).attr('id_task');
		
		$.ajax({
			type: 'post',
			url: 'http://crm2/task/set_dell_task',
			data: {
				'task_id': task_id
			},
			success: ok_dell_task
		});
	});

	$('html').on('click', '.admin-del-task', function()
	{
		task_id = $(this).attr('id_task');

		$.ajax({
			type: 'post',
			url: 'http://crm2/task/set_dell_task',
			data: {
				'task_id': task_id
			},
			success: ok_dell_task
		});
	});
	
	function okDellTask(data){
		ourDiv = $('.event_task' + data);
		ourDiv.css({'width': ourDiv.width(), 'height': ourDiv.height(), 'background-color': 'rgb(232, 92, 92)'});
		ourDiv.html('');
		$('.event_task' + data).slideUp();
	}
	
	/*---------------------- Возобновление задач --------------------------*/
	
	$('.restore_task').click(function()
	{
		id = $(this).attr('id_task');
		
		$.ajax({
			type: 'post',
			url: 'http://crm2/task/set_restore_task',
			data: {
				'task_id': id
			},
			success: ok_restore_task
		});
	});
	
	function ok_restore_task(data){
		$('.task-' + data).css('background','#5EA359');
		$('.task-' + data).fadeOut(300);
		/*tsk_c = parseInt($('.sidebar_red').html());
		new_c = tsk_c + 1;
		$('.sidebar_red').html(new_c);*/
	}
	
	/*-------------- Показ задач для даты ------------------------*/
	
	$('#data_tasks li').click(function()
	{
		data = $(this).attr('date');
		$('#data_tasks li').removeClass('tasks_show');
		$(this).addClass('tasks_show');
		
		$.ajax({
				type: 'post',
				url: 'http://crm2/task/get_task_data',
				data: {
					'data': data
				},
				success: show_task_data
			});
	});
	
	function show_task_data(data)
	{
		$('.tasks_list').html(data);
	}

	//Добавления события в задачу со страницы задачи
	$('#ajax_save_new_event_task').click(function()
	{
		description = $('#new_event_description').val();
		if( !description )
		{
			$('#new_event_description').css('border', '1px solid #7b160f');
		}
		else
		{
			$('#new_event_description').css('border', '1px solid #E6E6E6');
			id = $('#edit_task').attr('id_task');
			id_deal = $('#deal_id').attr('id_deal');
			sub_id = $('#sub_id').attr('id_sub');
			$.ajax({
				type: 'post',
				url: 'http://crm2/task/add_event_task',
				data: {
					'id': id,
					'description': description,
					'id_deal': id_deal,
					'sub_id': sub_id
				},
				success: ok_event_task
			});
		}
	})

	function ok_event_task(data)
	{
		$('#today').prepend(data);
		$('#ev_h4').fadeIn();;
		$('#content .today').fadeIn();
		$('#new_event_description').val('');
	}

	
	//Назначение дедлайна
	$('html').on('click', '#selDate li', function()
	{
		$('#selDate li').removeClass('selDateActive');
		$(this).addClass('selDateActive');
	});

	$('.selectDate').change(function()
	{
		$('#selDate li').removeClass('selDateActive');
		$(this).parent().addClass('selDateActive');
		$(this).parent().attr('deadline', $(this).val());
	});

	//Сохранение отредактированной задачи
	$('#save-edit-task').click(function()
	{
		titleTask = $('#edit-name-task').val();
		if( !titleTask )
		{
			$('#edit-name-task').css('border', '1px solid #7b160f');
		}
		else
		{
			$('#edit-name-task').css('border', '1px solid #DADADA');
			nameDeal = $('#edit-name-deal-task').val();
			idTask = $('#id-task').val();
			idDeal = $('#id-deal').val();
			editDescrTask = tinyMCE.get('edit-description-task').getContent();
			taskDeadline = $('.selDateActive').attr('deadline');
			taskUserArr = $('#adding_user li').map(function(){
				return $(this).attr('class');
			}).get();
			
			$.ajax({
				type: 'post',
				url: 'http://crm2/tasks/editTask',
				data: {
					'taskTitle': titleTask,
					'nameDeal': nameDeal,
					'editDescrTask': editDescrTask,
					'taskDeadline': taskDeadline,
					'taskUserArr': taskUserArr,
					'idTask': idTask,
					'idDeal': idDeal
				},
				success: okEditTask
			});
		}
	});
	
	function okEditTask(data)
	{
		closeEditField();

		result = JSON.parse(data);

		$('.edit-name').html(result['nameTask']);

		if( result['description'].length != 0 )
		{
			$('.edit-description').html(result['description']);
		}
		else
		{
			$('.edit-description').html('<span style="opacity: .6">Добавить описание</span>');
		}

		if( result['nameDeal'].length != 0 )
		{
			$('.edit-bind').html('Сделка: <a href="http://crm2/deals/deal/' + result['idDeal'] + '">' + result['nameDeal'] + '</a>');
		}
		else
		{
			$('.edit-bind').html('<span style="opacity: .6">Задача без привязки</span>');
		}

		$('.edit-deadline').html('Срок: <strong>' + result['deadline'] + '</strong>');
		
		$('.edit-status').css('background-color', result['color']);
		$('.edit-status').html(result['stateValue']);

		$('#notify-block  .save').show(1);
		$('#notify-block  .save').fadeOut(2000);

		users = result['users'].split(',');
		$('#show-camarades').empty();
		users.forEach(function(item, i, users) {
			subUser = item.split('_');
			$('#show-camarades').append('<li><a href="#">' + subUser[0] + '</a></li>');
		});

		$('.style-buttons').fadeIn();
	}
	
	/*------------------------------------------------------- Редактирование данных ------------------------------------------------------------- */
	
	$('html').on('click', '.edit', function()
	{
		$('#edit-field').animate({
			'top': '100px'			
		}, 300);
		
		$('#fade').fadeIn();
	});

	$('html').on('click', '#edit-field .close', function()
	{
		closeEditField();
	});

	function closeEditField()
	{
		$('#edit-field').animate({
			'top': '-200%'			
		}, 300);

		$('#add_user').animate({
			'right': '-251px'			
		}, 300);

		$('#fade').fadeOut();
	}

	$('#budget_edit_deal').focus(function()
	{
		$('#ajax_currency').fadeIn();
	});

	$('.editField').focusout(function()
	{
		$('.editField').css({'background-color':'transparent', 'border-bottom': '1px solid transparent'});
	});


	//Редактирование компании
	
	//Сохранение компании
	$('html').on('click', '#save-edit-company', function()
	{
		name = $('#edit-name-company').val();
		if( !name )
		{
			$('#edit-name-company').css('border', '1px solid #7b160f');
		}
		else
		{
			$('#edit-name-company').css('border', '1px solid #DADADA');
			idCompany = $('#id-company').val();
			phone = $('#edit-phone-company').val();
			skype = $('#edit-skype-company').val();
			email = $('#edit-email-company').val();
			www = $('#edit-www-company').val();
			adres = $('#edit-adres-company').val();
			
			$.ajax({
				type: 'post',
				url: 'http://crm2/clients/editCompany',
				data: {
					'id': idCompany,
					'name': name,
					'phone': phone,
					'skype': skype,
					'email': email,
					'www': www,
					'adres': adres
				},
				success: okEditCompany
			});
		}
	});
	
	function okEditCompany(data)
	{
		closeEditField();

		result = JSON.parse(data);
		
		$('.edit-name').html(result['name']);

		if( result['www'].length != 0 )
		{
			if( result['www'].substr(0, 7) == 'http://' )
			{
				$('.edit-www').html('<a href="' + result['www'] + '">' + result['www'] + '</a>');
			}
			else
			{
				$('.edit-www').html('<a href="http://' + result['www'] + '">' + result['www'] + '</a>');
			}
		}
		else
		{
			$('.edit-www').html('<span style="opacity: .6">Добавить сайт</span>');
		}
		if( result['phone'].length != 0 )
		{
			$('.edit-phone').html(result['phone']);
		}
		else
		{
			$('.edit-phone').html('<span style="opacity: .6">Добавить телефоны</span>');
		}
		if( result['email'].length != 0 )
		{
			$('.edit-at').html('<a href="mailto:' + result['email'] + '">' + result['email'] + '</a>');
		}
		else
		{
			$('.edit-at').html('<span style="opacity: .6">Добавить e-mail</span>');
		}
		if( result['skype'].length != 0 )
		{
			$('.edit-skype').html('<a href="skype:' + result['skype'] + '?chat">' + result['skype'] + '</a>');
		}
		else
		{
			$('.edit-skype').html('<span style="opacity: .6">Добавить skype</span>');
		}
		if( result['adres'].length != 0 )
		{
			$('.edit-street').html(result['adres']);
		}
		else
		{
			$('.edit-street').html('<span style="opacity: .6">Добавить адрес</span>');
		}

		$('#notify-block  .save').show(1);
		$('#notify-block  .save').fadeOut(2000);
	}

	//Удаление своей компании
	$('#del-company').click(function()
	{
		id = $('#edit_company').attr('id_company');

		$.ajax({
			type: 'post',
			url: 'http://crm2/clients/delete_company',
			data: {
				'id': id
			},
			success: ok_delete_company
		});
	});

	function ok_delete_company(data)
	{
		$(location).attr('href', 'http://crm2/clients');
	}
	
	//Редактирование контактного лица
	
	//Сохранение контактного лица
	$('html').on('click', '#save-edit-client', function()
	{
		name = $('#edit-name-client').val();
		if( !name )
		{
			$('#edit-name-client').css('border', '1px solid #7b160f');
		}
		else
		{
			$('#edit-name-client').css('border', '1px solid #DADADA');
			id = $('#id-client').val();
			doljnost = $('#edit-doljnost-client').val();
			company = $('#edit-client-company').val();
			phone = $('#edit-phone-client').val();
			skype = $('#edit-skype-client').val();
			email = $('#edit-email-client').val();
			adres = $('#edit-adres-client').val();
			
			$.ajax({
				type: 'post',
				url: 'http://crm2/clients/editClient',
				data: {
					'id_client': id,
					'name_client': name,
					'edit_doljnost': doljnost,
					'client_edit_company': company,
					'phone_edit_client': phone,
					'skype_edit_client': skype,
					'email_edit_client': email,
					'adres_edit_client': adres
				},
				success: okEditClient
			});
		}
	});

	function okEditClient(data)
	{
		closeEditField();

		result = JSON.parse(data);
		
		$('.edit-fio').html(result['fio']);
		if( result['doljnost'].length != 0 )
		{
			$('.edit-doljnost').html(result['doljnost']);
		}
		else
		{
			$('.edit-doljnost').html('<span style="opacity: .6">Добавить должность</span>');
		}
		if( result['phone'].length != 0 )
		{
			$('.edit-phone').html(result['phone']);
		}
		else
		{
			$('.edit-phone').html('<span style="opacity: .6">Добавить телефоны</span>');
		}
		if( result['email'].length != 0 )
		{
			$('.edit-at').html('<a href="mailto:' + result['email'] + '">' + result['email'] + '</a>');
		}
		else
		{
			$('.edit-at').html('<span style="opacity: .6">Добавить e-mail</span>');
		}
		if( result['skype'].length != 0 )
		{
			$('.edit-skype').html('<a href="skype:' + result['skype'] + '?chat">' + result['skype'] + '</a>');
		}
		else
		{
			$('.edit-skype').html('<span style="opacity: .6">Добавить skype</span>');
		}
		if( result['adres'].length != 0 )
		{
			$('.edit-street').html(result['adres']);
		}
		else
		{
			$('.edit-street').html('<span style="opacity: .6">Добавить адрес</span>');
		}
		if( result['nameCompany'].length != 0 )
		{
			$('.edit-company').html('<a href="' + base_url + 'clients/company/' + result['idCompany'] + '">' + result['nameCompany'] + '</a>');
		}
		else
		{
			$('.edit-company').html('<span style="opacity: .6">Добавить компанию</span>');
		}

		$('#notify-block  .save').show(1);
		$('#notify-block  .save').fadeOut(2000);
	}
	
	//Удаление своего контакта
	$('#del-contact').click(function()
	{
		id = $('#edit_person').attr('id_person');

		$.ajax({
			type: 'post',
			url: 'http://crm2/clients/delete_contact',
			data: {
				'id': id
			},
			success: ok_delete_contact
		});
	});

	function ok_delete_contact(data)
	{
		$(location).attr('href', 'http://crm2/clients');
	}
	
	/*------- Редактирование сделки -----------------------*/


	function showSaveFormFace()
	{
		close = 1;
	}

	$('#edit_contactface_deal').change(function()
	{
		if( $(this).val().length > 2 )
		{
			res = $(this).val().split(', ');
			if( res[1] )
			{
				$('#edit_company_deal').val(res[1]).attr('disabled', 'disabled');
			}
			else
			{
				$('#edit_company_deal').val('').removeAttr('disabled');
			}
		}
	});
	
	//Сохранение отредактированной сделки
	$('html').on('click', '#save-edit-deal', function()
	{
		titleDeal = $('#edit-name-deal').val();
		if( !titleDeal )
		{
			$('#edit-name-deal').css('border', '1px solid #7b160f');
		}
		else
		{
			$('#edit-name-deal').css('border', '1px solid #DADADA');
			idDeal = $('#id-deal').val();
			descrDeal = tinyMCE.get('edit-description-deal').getContent();
			budgetDeal = $('#edit-budget-deal').val();
			currencyDeal = $('#ajax-currency').val();
			companyDeal = $('#edit-company-deal').val();

			$.ajax({
				type: 'post',
				url: 'http://crm2/deals/editDeal',
				data: {
					'idDeal': idDeal,
					'titleDeal': titleDeal,
					'descrDeal': descrDeal,
					'budgetDeal': budgetDeal,
					'currencyDeal': currencyDeal,
					'companyDeal': companyDeal
				},
				success: okEditDeal
			});
		}
	});
	
	function okEditDeal(data)
	{
		closeEditField();

		result = JSON.parse(data);
		
		$('.edit-name').html(result['fio']);
		if( result['description'].length != 0 )
		{
			$('.edit-description').html(result['doljnost']);
		}
		else
		{
			$('.edit-description').html('<span style="opacity: .6">Добавить описание</span>');
		}
		if( result['budget'].length != 0 )
		{
			$('.edit-budget').html('Бюджет сделки: <strong>' + result['budget'] + '</strong>');
			$('.edit-show-budget').html('Бюджет: ' + result['budget']);
		}
		else
		{
			$('.edit-budget').html('Бюджет сделки: <span style="opacity: .6">Отсутствует</span>');
			$('.edit-show-budget').html('<span style="opacity: .6">Бюджет отсутствует</span>');
		}
		
		if( result['company_id'] != 0 )
		{
			$('.edit-company').html('Компания: <a href="' + base_url + 'clients/company/' + result['company_id'] + '">' + result['nameCompany'] + '</a>');
		}
		else
		{
			$('.edit-company').html('Компания: <span style="opacity: .6">Добавить компанию</span>');
		}

		$('#notify-block  .save').show(1);
		$('#notify-block  .save').fadeOut(2000);
	}

	$('#newContactFace').click(function()
	{
		$(this).css('display','none');
		$('#addingFace').slideDown(300);
	});

	$('html').on('click', '#ajax-save-face-deal', function()
	{
		contactfaceDeal = $('#add-contactface-deal').val();
		idDeal = $('#id-deal').val();

		if( contactfaceDeal )
		{
			$.ajax({
				type: 'post',
				url: 'http://crm2/deals/addContactfaceDeal',
				data: {
					'idDeal': idDeal,
					'contactfaceDeal': contactfaceDeal
				},
				success: okAddContactfaceDeal
			});
		}
	});

	function okAddContactfaceDeal(data)
	{
		$('#newContactFace').fadeIn(100);
		$('#addingFace').slideDown(300);
		$('#faces').prepend(data);
		$('.addFaceField').val('');

		$('#notify-block  .save').show(1);
		$('#notify-block  .save').fadeOut(2000);
	}

	//Быстрое изменение статуса сделки
	$('#fast-edit-status').click(function()
	{
		$('#fast-edit-status-deal').fadeIn();
		$(this).css('display','none');
	});

	$('#fast-edit-status-deal li').click(function()
	{
		oldIdStatus = $('#fast-edit-status-deal li.active').attr('status_id');
		$('#fast-edit-status-deal li').removeClass('active');
		$(this).addClass('active');
		idDeal = $('#id-deal').val();
		idStatusDeal = $(this).attr('status_id');
		newTextDeal = $(this).text();
		color = $(this).css('background-color');

		if( idStatusDeal != oldIdStatus )
		{
			$.ajax({
				type: 'post',
				url: 'http://crm2/deals/fastEditStatusDeal',
				data: {
					'idDeal': idDeal,
					'idStatusDeal': idStatusDeal,
					'newTextDeal': newTextDeal,
					'color': color
				},
				success: okEditFastStatusDeal
			});	
			$('#fast-edit-status').css('background-color', color).html(newTextDeal);
		}
		else
		{
			okEditFastStatusDeal();
		}
		
	});

	function okEditFastStatusDeal(data)
	{
		$('#fast-edit-status-deal').css('display','none');
		$('#fast-edit-status').fadeIn();
		
		//$('#today').prepend(data);
		$('#ev_h4').fadeIn();

		$('#notify-block  .save').show(1);
		$('#notify-block  .save').fadeOut(2000);
	}

	//Удаление своей сделки
	$('#del-deal').click(function()
	{
		id = $('#edit_deal').attr('id_deal');

		$.ajax({
			type: 'post',
			url: 'http://crm2/deal/delete_deal',
			data: {
				'id': id
			},
			success: ok_delete_deal
		});
	});

	function ok_delete_deal(data)
	{
		$(location).attr('href', 'http://crm2/deal');
	}

	//Добавления события или задачи в сделку со страницы сделки
	$('html').on('click', '#save-event-deal', function()
	{
		description = tinyMCE.get('description').getContent();
		if( !description )
		{
			$('#description').css('border', '1px solid #7b160f');
		}
		else
		{
			$('#description').css('border', '1px solid #DADADA');
			idDeal = $('#id-deal').val();

			if( insertMessage == 'text' )
			{
				$.ajax({
					type: 'post',
					url: 'http://crm2/deals/addEventDeal',
					data: {
						'idDeal': idDeal,
						'description': description
					},
					success: okEventDeal
				});
			}
		}
	})

	function okEventDeal(data)
	{
		$('#today').prepend(data);
		$('.today').fadeIn();
		$('#new_event_description').val('');
	}

	//Редактирование события на странице сделки
	$('html').on('click', '.edit-event', function()
	{
		id = $(this).attr('id_event');
		$('.p-' + id).hide();
		$('.save-edit-response-' + id).fadeIn();
		$('.edit-deal-event-' + id).fadeIn();
	});

	$('html').on('click', '.edit-sub-event', function()
	{
		id = $(this).attr('id_event');
		$('.p-' + id).hide();
		$('.save-edit-response-' + id).fadeIn();
		$('.edit-deal-event-' + id).fadeIn();
	});

	$('html').on('click', '.edit-save-response', function()
	{
		id = $(this).attr('id_save');
		id_deal = $('#edit_deal').attr('id_deal');
		text = $('.edit-event-' + id).val();
		if( text.length == 0 )
		{
			$('.edit-deal-event-' + id).css('border', '1px solid #7b160f');
		}
		else
		{
			$('.edit-deal-event-' + id).css('border', '1px solid #E6E6E6');
			$.ajax({
				type: 'post',
				url: 'http://crm2/main/saveEditResponse',
				data: {
					'id_event': id,
					'id_deal': id_deal,
					'text': text
				},
				success: ok_save_edit_response
			});
		}
	});

	function ok_save_edit_response(data)
	{
		res = JSON.parse(data);
		$('.p-' + res['id']).html(res['text']).show();
		$('.edit-deal-event-' + res['id']).html(res['text']);
		$('.hide').fadeOut(10);
		$('.p-edit-response').show();
	}

	$('html').on('click', '.edit-cancel-response', function()
	{
		$('.hide').fadeOut(50);
		$('.p-edit-response').fadeIn(50);
	});

	//Быстрое изменение статуса задачи
	$('#show_fast_edit_status_task').click(function()
	{
		$('#fast_edit_status_task').fadeIn();
		$(this).css('display','none');
	});

	$('#fast_edit_status_task li').click(function()
	{
		$('#fast_edit_status_task li').removeClass('active');
		$(this).addClass('active');
		id = $('#edit_task').attr('id_task');
		edit_status_task = $(this).attr('status_id');
		new_text_task = $(this).text();
		color = $(this).css('background-color');
		if( edit_status_task != $('#edit_status_task .active').attr('status_id') )
		{
			$.ajax({
				type: 'post',
				url: 'http://crm2/task/fast_edit_status_task',
				data: {
					'id': id,
					'edit_status_task': edit_status_task
				},
				success: ok_edit_fast_status_task
			});	
			$('#show_fast_edit_status_task').css('background-color', color).html(new_text_task);
		}
		else
		{
			ok_edit_fast_status_task();
		}
		
	});

	function ok_edit_fast_status_task(data)
	{
		$('#fast_edit_status_task').css('display','none');
		$('#show_fast_edit_status_task').fadeIn();
		
		alert(data);
		/*$('#today').prepend(data);
		$('#ev_h4').fadeIn();*/
	}

	//Редактирование задачи со сделки
	$('html').on('click', '.edit-task-deal', function()
	{
		/*$('.show_event').css('display', 'block');
		$('.edit-task-deal-clear').empty();
		$('.edit-task-deal-clear').css('display', 'none');
		
		$('#edit-task-from-deal-' + id).fadeIn();
		$('#view_task_' + id).css('display', 'none');
		$('#edit-task-from-deal-' + id).load('http://crm2/deal/loadEditTask/' + id);*/
		$('#ajax_save_edit_task_deal').css('display','block');
		$('#cancel-task-deal').css('display','block');
		$('#ajax_save_new_task_deal').css('display','none');
		$('#adding-task-from-deal').css(
			{
				'position': 'fixed',
				'width': '600px',
				'marginLeft': '-250px',
				'border': 'none',
				'z-index': 5,
				'border-radius': '5px',
				'display': 'block',
				'background': '#FFF',
				'padding': '13px 0 13px 0'
			});
		$('#adding-task-from-deal').animate({
			'top': '100px',
			'left': '50%'
		}, 300);
		

		id = $(this).attr('id_task');
		$.ajax({
				type: 'post',
				url: 'http://crm2/deal/loadEditTask',
				data: {
					'id_task': id
				},
				success: loadEditTask
			});
	});

	function loadEditTask(data)
	{
		res = JSON.parse(data);
		$('#title-task').val(res['title']);
		$('#new_task_description').val(res['description']);

		for( var i = 0; i < res['user_id'].length; i++)
		{
			$('#adding_user').append('<li class = "user_' + res['user_id'][i]['id'] + '">' + res['user_id'][i]['name'] + '</li>');
			$('#user_' + res['user_id'][i]['id']).removeClass('not_user_selected');
			$('#user_' + res['user_id'][i]['id']).addClass('user_selected');
		}

		$('#selDate li').removeClass('selDateActive');
		$('#selDate .datecalend').addClass('selDateActive');
		$('#selDate .datecalend').attr('deadline', res['deadline']);
		$('#selDate .selectDate').val(res['deadline']);

		$('#id_task_edit').val(res['id']);

	}

	$('html').on('click', '#cancel-edit-task-deal', function()
	{
		$('.show_event').css('display', 'block');
		$('.edit-task-deal-clear').empty();
		$('.edit-task-deal-clear').css('display', 'none');
	});

	$('html').on('click', '#ajax_save_edit_task_deal', function()
	{
		title = $('#title-task').val();
		if( !title )
		{
			$('#title-task').css('border', '1px solid #7b160f');
		}
		else
		{
			$('#title-task').css('border', '1px solid #E8E8E8');

			task_description = $('#new_task_description').val();
			type = $('#type-task option:selected').val();
			task_deadline = $('.selDateActive').attr('deadline');
			id = $('#edit_deal').attr('id_deal');
			id_task = $('#id_task_edit').val();
			task_user_arr = $('#adding_user li').map(function(){
				return $(this).attr('class');
			}).get();

			$.ajax({
				type: 'post',
				url: 'http://crm2/task/edit_task',
				data: {
					'task_title': title,
					'task_description': task_description,
					'task_deadline': task_deadline,
					'task_user_arr': task_user_arr,
					'type': type,
					'id_deal': id,
					'id_task': id_task
				},
				success: ok_edit_task_to_deal
			});
		}
	});

	function ok_edit_task_to_deal(data)
	{
		/*$('.show_event').css('display', 'block');
		$('.edit-task-deal-clear').empty();
		$('.edit-task-deal-clear').css('display', 'none');
		res = JSON.parse(data);
		$('#view_task_' + res['id_task'] + ' .author_edit').html(res['author'] + ' для <strong><em>' + res['users'] + '</em></strong>');
		$('#view_task_' + res['id_task'] + ' .show_edit').html(res['description']);

		$('#other').prepend($('.transfer' + res['id_task']));

		dateObj = new Date();
		alert(dateObj);*/
		alert(data);
	}

	//Очищение полей от букв и изгнание бесов

	function clearFieldDeal()
	{
		$('#title-task').val('');
		$('#new_task_description').val('');
		$('#adding_user').empty();
		$('#selDate li').removeClass('selDateActive');
		$('#selDate li:first').addClass('selDateActive');
	}

	var insertMessage = 'text';

	$('#add-text').click(function()
	{
		$('#adding-task-from-deal').slideUp(500);
		$(this).addClass('active-input-blue');
		$('#add-task').removeClass('active-input-green');

		//Очищаем поля в сделке
		clearFieldDeal();

		//Даем знать, что сохраняется примечание
		insertMessage = 'text';
	});

	//Добавление задачи со страницы сделки
	$('#add-task').click(function()
	{
		$('#adding-task-from-deal').slideDown(500);
		$(this).addClass('active-input-green');
		$('#add-text').removeClass('active-input-blue');
		$('#cancel-task-deal').css('display','block');
		$('#ajax_save_new_task_deal').css('display','block');
		$('#ajax_save_new_event_deal').css('display','none');
		$('#new_event_description').hide();
		$('#cancel-event-deal').hide();

		//Даем знать, что сохраняется задача
		insertMessage = 'task';
	});

	$('#ajax_save_new_task_deal').click(function()
	{
		title_task = $('#title-task').val();
		if( !title_task )
		{
			$('#title-task').css('border', '1px solid #7b160f');
		}
		else
		{
			$('#title-task').css('border', '1px solid #E8E8E8');
			task_description = $('#new_task_description').val();
			type = $('#type-task option:selected').val();
			task_deadline = $('.selDateActive').attr('deadline');
			id = $('#edit_deal').attr('id_deal');
			task_user_arr = $('#adding_user li').map(function(){
				return $(this).attr('class');
			}).get();

			$.ajax({
				type: 'post',
				url: 'http://crm2/task/create_task',
				data: {
					'title': title_task,
					'task_description': task_description,
					'task_deadline': task_deadline,
					'task_user_arr': task_user_arr,
					'type': type,
					'id_deal': id
				},
				success: ok_task_to_deal
			});
		}
	});
	
	function ok_task_to_deal(data){
		res = JSON.parse(data);
		if( res['hot'] == 1 )
		{
			$('#task_hot').prepend(res['view']);
			$('#task_h4').fadeIn();
		}
		else
		{
			$('#today').prepend(res['view']);
			$('#ev_h4').fadeIn();
		}
				
		$('#new_event_description').val('');
		cancelTaskDeal();
	}

	//Отмена добавления задачи со сделки
	$('#cancel-task-deal').click(function()
	{
		cancelTaskDeal();
	});

	function cancelTaskDeal()
	{
		$('#ajax_save_new_event_deal').fadeIn(500);
		$('#cancel-event-deal').fadeIn(500);
		$('#ajax_save_new_task_deal').css('display','none');
		$('#cancel-task-deal').css('display','none');
		$('#ajax_save_edit_task_deal').css('display','none');
		$('#new_event_description').show(500);

		$('#adding-task-from-deal').css(
			{
				'position': 'static',
				'width': 'auto',
				'margin': '0',
				'border': 'none',
				'z-index': 0,
				'border-radius': '0',
				'display': 'none',
				'background': 'transparent',
				'padding': '0',
				'top': '-100%',
				'left': '-100%'
			});

		$('#fade_all').css('display', 'none');
	}

	//Удаление события со страницы сделки
	$('html').on('click', '.del-event', function()
	{
		idEvent = $(this).attr('id-event');
		
		$.ajax({
			type: 'post',
			url: 'http://crm2/main/dellEvent',
			data: {
				'idEvent': idEvent
			},
			success: okDellEvent
		});
	});
	
	function okDellEvent(data){
		ourDiv = $('.event_transfer' + data + ' .show_event');
		ourDiv.css({'width': ourDiv.width(), 'height': ourDiv.height(), 'background-color': 'rgb(232, 92, 92)'});
		ourDiv.html('');
		$('.event_transfer' + data).slideUp();
	}

	//Удаление суб-события со страницы сделки
	$('html').on('click', '.del-sub-event', function()
	{
		idEvent = $(this).attr('id-event');
		
		$.ajax({
			type: 'post',
			url: 'http://crm2/main/dellEvent',
			data: {
				'idEvent': idEvent
			},
			success: okDellSubEvent
		});
	});
	
	function okDellSubEvent(data){
		ourDiv = $('.subevent' + data);
		ourDiv.css({'width': ourDiv.width(), 'height': ourDiv.height(), 'background-color': 'rgb(232, 92, 92)'});
		ourDiv.html('');
		ourDiv.slideUp();
	}

	//Добавления события со страницы компании
	$('#ajax_save_new_event_company').click(function()
	{
		description = $('#new_event_description').val();
		if( !description )
		{
			$('#new_event_description').css('border', '1px solid #7b160f');
		}
		else
		{
			$('#new_event_description').css('border', '1px solid #E6E6E6');
			id = $('#edit_company').attr('id_company');
			
			$.ajax({
				type: 'post',
				url: 'http://crm2/clients/add_event_company',
				data: {
					'id': id,
					'description': description
				},
				success: ok_event_company
			});
		}
	})

	function ok_event_company(data)
	{
		$('#today').prepend(data);
		$('#ev_h4').fadeIn();
		$('#new_event_description').val('');
	}

	//Добавления события со страницы контактного лица
	$('#save-event').click(function()
	{
		tinyMCE.activeEditor.getContent({format : 'text'});
		text = tinyMCE.get('description').getContent();
		if( !text )
		{
			$('#notify-block  .error').fadeIn(100);
		}
		else
		{
			$('#notify-block  .error').hide(1);
			id = $('#edit_person').attr('id_person');
			
			/*$.ajax({
				type: 'post',
				url: 'http://crm2/clients/add_event_client',
				data: {
					'id': id,
					'description': description
				},
				success: ok_event_client
			});*/
		}
	})

	function ok_event_client(data)
	{
		$('#today').prepend(data);
		$('#ev_h4').fadeIn();
		$('#new_event_description').val('');
	}



	/*------------------------------- Ссылки-------------------------------*/

	var v = 0;

	//Основной контакт
	$('html').on('click', '.default-contact', function()
	{
		if( v == 0 )
		{
			$('.cart').css('display', 'none');
			$('.cut').css('display', 'none');

			

			$('.cart', this).fadeIn();
			$('.cut', this).fadeIn();
			v = 1;
		}
	});

	//Открепить основное контактное лицо от сделки

	$('html').on('click', '.del-contact-from-deal', function()
	{
		id = $(this).attr('id_default_contact');
		id_deal = $('#edit_deal').attr('id_deal');
		$.ajax({
			type: 'post',
			url: 'http://crm2/deal/offDefaultContact',
			data: {
				'id_contact': id,
				'id_deal': id_deal
			},
			success: ok_off_default_contact
		});
	});

	function ok_off_default_contact(data) 
	{
		$('.default-contact-id-' + data).remove();
		$('.cart').fadeOut();
		$('.cut').fadeOut();
		$('#fade').css('display', 'none');
		$('#edit_default_contactface_deal').val('').fadeIn();
		v = 0;
	}

	//Открепить основную компанию от сделки

	$('html').on('click', '.del-company-from-deal', function()
	{
		id = $(this).attr('id_default_company');
		id_deal = $('#edit_deal').attr('id_deal');
		$.ajax({
			type: 'post',
			url: 'http://crm2/deal/offDefaultCompany',
			data: {
				'id_company': id,
				'id_deal': id_deal
			},
			success: ok_off_default_company
		});
	});

	function ok_off_default_company(data) 
	{
		$('.default-company-id-' + data).remove();
		$('.cart').fadeOut();
		$('.cut').fadeOut();
		$('#fade').css('display', 'none');
		$('#edit_company_deal').val('').fadeIn();
		v = 0;
	}


	//Дополнительные контакты
	$('html').on('click', '.group', function()
	{
		if( v == 0 )
		{
			$('.cart').css('display', 'none');
			$('.cut').css('display', 'none');

			$(this).css('font-weight', 'bold');

			$('#fade').css({'height': $(document).height(), 'display': 'block'});

			$('.cart', this).fadeIn();
			$('.cut', this).fadeIn();
			v = 1;
		}
	});

	$('#fade').click(function()
	{
		$('.cart').fadeOut();
		$('.cut').fadeOut();
		$('.group').css('font-weight', 'normal');
		$(this).css('display', 'none');
		$('#edit-field').animate({
			'top': '-100%'			
		}, 300);
		v = 0;
	});

	$('#fade_all').click(function()
	{
		$('#edit-task').fadeOut(500);
		$(this).css('display', 'none');
		cancelTaskDeal();
	});

	//Открепить контакт от компании
	$('html').on('click', '.del-from-company', function()
	{
		id = $(this).attr('id_contact');
		$.ajax({
			type: 'post',
			url: 'http://crm2/clients/DelFromCompany',
			data: {
				'id_contact': id
			},
			success: ok_del_contact
		});
	});

	function ok_del_contact(data) 
	{
		$('.' + data).hide();
		$('.cart').fadeOut();
		$('.cut').fadeOut();
		$('#fade').css('display', 'none');
		v = 0;
	}

	
	//Открепить контактное лицо от сделки

	$('html').on('click', '.del-from-deal', function()
	{
		idContact = $(this).attr('id_contact');
		idDeal = $('#id-deal').val();
		$.ajax({
			type: 'post',
			url: 'http://crm2/deals/disconnectFromDeal',
			data: {
				'idContact': idContact,
				'idDeal': idDeal
			},
			success: okDisconnectFromDeal
		});
	});

	function okDisconnectFromDeal(data) 
	{
		$('.' + data).hide();
		$('.cart').fadeOut();
		$('.cut').fadeOut();
		$('#fade').css('display', 'none');

		$('#notify-block  .save').show(1);
		$('#notify-block  .save').fadeOut(2000);
	}

	/*---------------------------- Функция поиска -------------------------------*/

	$('.search_text').keyup(function()
	{
		if( $(this).val().length > 1 )
		{
			atr = $(this).attr('place');
			switch(atr){
				case 'clients':
					url = 'http://crm2/clients/search';
					break;
				case 'deals':
					url = 'http://crm2/deal/search';
					break;
			}

			$.ajax({
				type: 'post',
				url: url,
				data: {
					'search': $(this).val()
				},
				success: ok_search
			});
		}
		else
		{
			$('.default_field').css('opacity','1');
			$('#search_result').html('');
		}
	})

	function ok_search(data)
	{
		if( data != '' )
		{
			$('#search_result').html(data);
			$('.default_field').css('opacity','0');
		}
		else
		{
			$('.default_field').css('opacity','1');
			$('#search_result').html('');
		}
		
	}


	//Если есть несохраненные данные то спрашивать
	$(window).bind('beforeunload', function(e) {
		if( close == 1 )
		{
			alert("У вас есть несохраненные данные!");
		}
	});

	//Функции примечаний и событий

	$('html').on('click', '.response', function()
	{
		$('.response').fadeIn();
		$('.response_text').css('display','none');
		$('.save-field-response').css('display','none');
		$(this).css('display','none');
		open = $(this).attr('open-class');
		$('.' + open).fadeIn();
	});

	$('html').on('click', '.save-response', function()
	{
		id = $(this).attr('id_save');
		id_deal = $('#edit_deal').attr('id_deal');
		id_task = $(this).attr('id_task');
		text = $('.response' + id).val();
		/*if( id_task.length == 0 )
		{
			id_task = 'NULL';
		}*/
		if( text.length == 0 )
		{
			$('.response' + id).css('border', '1px solid #7b160f');
		}
		else
		{
			$('.response' + id).css('border', '1px solid #E6E6E6');
			$.ajax({
				type: 'post',
				url: 'http://crm2/main/saveResponse',
				data: {
					'id_deal': id_deal,
					'id_task': id_task,
					'sub_id': id,
					'text': text
				},
				success: ok_save_response
			});
		}
	});

	function ok_save_response(data){
		res = JSON.parse(data);
		$('#' + res['sub_id']).append(res['text']);
		$('.textarea').val('');
		$('.save-field-response').fadeOut(10);
		$('.response').fadeIn();
	}

	//Функция развертывания задач в списке сделок
	$('.insert-task').toggle(
		function(){
			$(this).next('.list-deal-tasks').slideDown(300);
		},
		function()
		{
			$(this).next('.list-deal-tasks').slideUp(300);
		}
	);

	//Функция развертывания описания в списке задач
	$('.insert-description').toggle(
		function(){
			$(this).next('.list-description-tasks').slideDown(300);
		},
		function()
		{
			$(this).next('.list-description-tasks').slideUp(300);
		}
	);
	
});