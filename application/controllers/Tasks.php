<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tasks extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->library('ion_auth');
		$this->load->library('session');
		$this->load->library('email');
		$this->load->model('task_md');
		$this->load->model('clients_md');
		$this->load->model('deal_md');
		$this->load->model('main_md');
	}
	
	public function index()
	{
		//Проверям авторизацию
		if (!$this->ion_auth->logged_in())
		{
			//redirect them to the login page
			redirect('auth/login', 'refresh');
		}
		else
		{
			//Загрузка данных профиля
			$profile = $this->ion_auth->profile();
			$profile = json_encode($profile, true);
			$profile = json_decode($profile, true);
			
			//Проверим статусы задач
			$this->task_md->update_stat();
			
			//header
			$header = '#5EA359';
			$title_h1 = 'Задачи';
			$index = base_url('task');
			
			
			//Выбираем задачи для пользователя
			$task = $this->task_md->getMyTask( $profile['id'] );
			//Выбираем задачи для пользователя
			//$task_finish = $this->task_md->get_finish_task( $profile['id'] );

			//$task_assigned = $this->task_md->get_assigned_task( $profile['id'] );
			
			$hotDeadline = 0;
			if( !empty($task) )
			{
				foreach( $task as $list )
				{
					if( $list['state'] == 3 )
					{
						$hotDeadline++;
					}
				}
			}
			

			/*$count = Array(
					'active' => count($task),
					'finish' => count($task_finish),
					'hot' => $hotDeadline,
					'assigned' => count($task_assigned)
					);*/
			
			//Выбираем всех сотрудников кроме себя
			$users = $this->task_md->get_all_users();
			
			$headerMenu = Array(
				'index' => '',
				'deals' => '',
				'clients' => '',
				'tasks' => 'class="active"',  //Текущее глобальное местоположение
			);

			$subMenu = Array(
				'active' => 'class="active"', //Текущее локальное местоположение
				'finished' => '',
				'assigned' => ''
			);

			$data = Array(
				'profile' => $profile,
				'task' => $task,
				'users' => $users,
				//'count' => $count,
				'headerMenu' => $headerMenu,
				'subMenu' => $subMenu
				);
			
			$this->load->view('header', $data);
			$this->load->view('tasks', $data);
			$this->load->view('sidebar');
			$this->load->view('footer');
		}
	}

	//Страница задачи
	public function task($id)
	{
		//Проверям авторизацию
		if (!$this->ion_auth->logged_in())
		{
			//redirect them to the login page
			redirect('auth/login', 'refresh');
		}
		else
		{
			//Загрузка данных профиля
			$profile = $this->ion_auth->profile();
			$profile = json_encode($profile, true);
			$profile = json_decode($profile, true);
			
			//Проверим статусы задач
			$this->task_md->update_stat();
			
			
			//Выбираем задачу для пользователя
			$task = $this->task_md->getTaskForId( $id, $profile['id'] );

			if( count($task) != 0 )
			{
				//Выбираем название сделок
				//Получаем весь список состояния сделок для sidebar
				$state_deal = $this->deal_md->get_list_deal();

				$deals = $this->deal_md->getAllDeals();

				//Получаем весь список состояния задач
				$state_task = $this->task_md->get_list_task();
				
				//Выбираем всех сотрудников кроме себя
				$users = $this->task_md->get_all_users();

				$headerMenu = Array(
					'index' => '',
					'deals' => '',
					'clients' => '',
					'tasks' => 'class="active"',  //Текущее глобальное местоположение
				);

				$subMenu = Array(
					'active' => 'class="active"', //Текущее локальное местоположение
					'finished' => '',
					'assigned' => ''
				);
				
				$data = Array(
					'profile' => $profile,
					'task' => $task,
					'users' => $users,
					'headerMenu' => $headerMenu,
					'subMenu' => $subMenu,
					'deals' => $deals,
					'state_task' => $state_task
					);

				$this->load->view('header', $data);
				$this->load->view('task', $data);
				$this->load->view('sidebar');
				$this->load->view('footer', $data);
			}
			else
			{
				show_404();
			}
		}
	}

	//Назначенные задачи
	public function assignedTask()
	{
		//Проверям авторизацию
		if (!$this->ion_auth->logged_in())
		{
			//redirect them to the login page
			redirect('auth/login', 'refresh');
		}
		else
		{
			//Загрузка данных профиля
			$profile = $this->ion_auth->profile();
			$profile = json_encode($profile, true);
			$profile = json_decode($profile, true);
			
			//Проверим статусы задач
			$this->task_md->update_stat();
			
						
			//Выбираем задачи для пользователя
			$task = $this->task_md->getAssignedTask( $profile['id'] );
			
			//Выбираем всех сотрудников кроме себя
			$users = $this->task_md->get_all_users();
			
			$headerMenu = Array(
				'index' => '',
				'deals' => '',
				'clients' => '',
				'tasks' => 'class="active"',  //Текущее глобальное местоположение
			);

			$subMenu = Array(
				'active' => '',
				'finished' => '',
				'assigned' => 'class="active"' //Текущее локальное местоположение
			);

			$data = Array(
				'profile' => $profile,
				'task' => $task,
				'users' => $users,
				'headerMenu' => $headerMenu,
				'subMenu' => $subMenu
				);
			
			$this->load->view('header', $data);
			$this->load->view('assignedTask', $data);
			$this->load->view('sidebar');
			$this->load->view('footer');
		}
	}

	public function finishTask()
	{
		//Проверям авторизацию
		if (!$this->ion_auth->logged_in())
		{
			//redirect them to the login page
			redirect('auth/login', 'refresh');
		}
		else
		{
			//Загрузка данных профиля
			$profile = $this->ion_auth->profile();
			$profile = json_encode($profile, true);
			$profile = json_decode($profile, true);
			
			
			//Проверим статусы задач
			$this->task_md->update_stat();
			
			//Выбираем задачи для пользователя
			$task = $this->task_md->get_finish_task( $profile['id'] );
			
			//Выбираем всех сотрудников кроме себя для добавления новой задачи
			$users = $this->task_md->get_all_users();
			
			$headerMenu = Array(
				'index' => '',
				'deals' => '',
				'clients' => '',
				'tasks' => 'class="active"',  //Текущее глобальное местоположение
			);

			$subMenu = Array(
				'active' => '',
				'finished' => 'class="active"', //Текущее локальное местоположение
				'assigned' => ''
			);

			$data = Array(
				'profile' => $profile,
				'task' => $task,
				'users' => $users,
				'headerMenu' => $headerMenu,
				'subMenu' => $subMenu
				);
			
			$this->load->view('header', $data);
			$this->load->view('finishedTasks', $data);
			$this->load->view('sidebar');
			$this->load->view('footer');
		}
	}
	
	public function over_task()
	{
			//Проверям авторизацию
		if (!$this->ion_auth->logged_in())
		{
			//redirect them to the login page
			redirect('auth/login', 'refresh');
		}
		else
		{
			//Загрузка данных профиля
			$profile = $this->ion_auth->profile();
			$profile = json_encode($profile, true);
			$profile = json_decode($profile, true);
			
			//header
			$header = '#5EA359';
			$title_h1 = 'Задачи';
			$index = base_url('task');
			
			//Проверим статусы задач
			$this->task_md->update_stat();
			
			//Выбираем задачи для пользователя
			$task_finish = $this->task_md->get_finish_task( $profile['id'] );
			
			$task_active = $this->task_md->get_my_task( $profile['id'] );

			$task_assigned = $this->task_md->get_assigned_task( $profile['id'] );
			
			$hotDeadline = 0;
			foreach( $task_active as $list )
			{
				if( $list['state'] == 3 )
				{
					$hotDeadline++;
				}
			}
			$count = Array(
					'active' => count($task_active),
					'finish' => count($task_finish),
					'hot' => $hotDeadline,
					'assigned' => count($task_assigned)
					);

			//Выбираем задачи для пользователя
			$task = $this->task_md->get_over_task( $profile['id'] );
			
			//Выбираем всех сотрудников кроме себя для добавления новой задачи
			$users = $this->task_md->get_all_users();
			
			$data = Array(
				'profile' => $profile,
				'task' => $task,
				'users' => $users,
				'header' => $header,
				'title_h1' => $title_h1,
				'index' => $index,
				'place' => 'clients',
				'count' => $count
				);

			$submenu = Array(
				'<a href="' . base_url('task') . '">Мои</a><span class="count">' . $count['active'] . '</span>',
				'<a href="' . base_url('task/finish_task') . '">Выполеные</a><span class="count">' . $count['finish'] . '</span>',
				'<span class="state-list">Просроченные</span><span class="count">' . $count['hot'] . '</span>',
				'<a href="' . base_url('task/assigned_task') . '">Назначенные</a><span class="count">' . $count['assigned'] . '</span>'
			);
			
			$sidebar = Array(
				'subMenu' => $submenu,
				'location' => 'task'
			);
			
			$this->load->view('header', $data);
			$this->load->view('task_over', $data);
			$this->load->view('sidebar_menu', $sidebar);
			$this->load->view('sidebar_submenu', $sidebar);	
			$this->load->view('add_task', $data);
			$this->load->view('footer');
		}
	}
	
	public function createTask()
	{
		$arr = $this->task_md->createTask();
		//print_r($arr);
		echo $arr;
		/*$arr_user_mail = $this->main_md->get_email($arr['user_mail']);

		//Отправляем письма
		$config['protocol'] = 'smtp';
		$config['smtp_host'] = 'ssl://smtp.yandex.ru';
		$config['smtp_user'] = 'crm@korinf.com.ua';
		$config['smtp_pass'] = 'orkgb4rnf4';
		$config['smtp_port'] = '465';
		$config['mailtype'] = 'html';
		
		$this->email->initialize($config);

		$this->email->from('crm@korinf.com.ua', 'Crm оповещение');
		$this->email->to($arr_user_mail); 
		//$this->email->cc('another@another-example.com'); 
		//$this->email->bcc('them@their-example.com'); 

		//Загрузка данных профиля
		$profile = $this->ion_auth->profile();
		$profile = json_encode($profile, true);
		$profile = json_decode($profile, true);

		$author_task = $profile['first_name'] . ' ' . $profile['last_name'];

		$text_mail = '<h2>Тема задачи: ' . $arr['title'] . '</h2>
					<h3>Автор: ' . $author_task . '</h3>
					<h3>Дата создания: ' . $arr['create_date'] . '</h3>
					<h3>Срок: ' . $arr['deadline'] . '</h3>
					<h3>Url: <a href="' . base_url('task/show_task/' . $arr['id_task']) . '" target="_blank">' . base_url('task/show_task/' . $arr['id_task']) . '</a></h3>
					<p>' . $arr['description'] . '</p>';

		$this->email->subject('Новая задача');
		$this->email->message($text_mail);	

		//$this->email->send();*/
	}
	
	public function setFinishTask()
	{
		//Загрузка данных профиля
		$profile = $this->ion_auth->profile();
		$profile = json_encode($profile, true);
		$profile = json_decode($profile, true);

		$id = $this->input->post('task_id');
		$task = $this->task_md->setFinishTask();
		print_r($task);
		/*
		//Отправляем письма
		$config['protocol'] = 'smtp';
		$config['smtp_host'] = 'ssl://smtp.yandex.ru';
		$config['smtp_user'] = 'crm@korinf.com.ua';
		$config['smtp_pass'] = 'orkgb4rnf4';
		$config['smtp_port'] = '465';
		$config['mailtype'] = 'html';
		
		$this->email->initialize($config);

		$this->email->from('crm@korinf.com.ua', 'Crm оповещение');
		$this->email->to($task['author']['email']); 
		//$this->email->cc('another@another-example.com'); 
		//$this->email->bcc('them@their-example.com'); 

		$text_mail = '<h1>Задача завершена</h1>
					<p>Завершил(а): ' . $profile['first_name'] . ' ' . $profile['last_name'] . '</p>
					<p>Дата завершения: ' . $task['task']['data_finish'] . '</p>
					<p>Результат: ' . $task['task']['finish_result'] . '</p>
					<hr />
					<h2>Тема задачи: ' . $task['task']['title'] . '</h2>
					<p>Описание: ' . $task['task']['description'] . '</p>
					<h3>Автор: ' . $task['author']['username'] . '</h3>
					<h3>Дата создания: ' . $task['task']['create_date'] . '</h3>
					<h3>Срок: ' . $task['task']['deadline'] . '</h3>
					<h3>Url: <a href="' . base_url('task/show_task/' . $id) . '" target="_blank">' . base_url('task/show_task/' . $id) . '</a></h3>';

		$this->email->subject('Задача завершена');
		$this->email->message($text_mail);	

		$this->email->send();

		//echo $this->email->print_debugger();



		echo $task['task']['id'];
		*/
	}
	
	public function setDellTask()
	{
		echo $this->task_md->setDellTask();
	}
	
	public function get_task_data()
	{
		$task = $this->task_md->get_task_data();
		$data = $this->input->post('data');
		$today = 1;
		if( count($task) != 0 ):
			foreach( $task as $list_task ):
				if( $list_task['deadline'] == $data ):
					
					$today = 0;
					
					$dt = (strtotime(date('Y-m-d')) - strtotime($list_task['data_create']))/(3600*24);
					if( $dt == 0 )
					{
						$dt = 'Сегодня';
					}
					elseif ( $dt == 1 )
					{
						$dt = 'Вчера';
					}
					else
					{
						$dt = $list_task['data_create'];
					}
					echo '<div class="task_view" id="view_task_' . $list_task['id'] . '">';
						echo '<p class="author">' . $dt . ', ' . $list_task['author'] . '</p>';
						echo '<p class="text_task">' . $list_task['text'] . '</p>';
						echo '<button title="Завершить задачу" class="ending_task hastip" id_task="' . $list_task['id'] . '"></button>';
						echo '<button title="Удалить задачу" class="dell_task hastip" id_task="' . $list_task['id'] . '"></button>';
					echo '</div>';
				
				endif;
			endforeach;
		endif;
		if( $today == 1 )
		{
			echo '<div class="span_info">На сегодня задач нету</div>';
		}
	}
	
	//Возобновление задачи
	public function set_restore_task()
	{
		echo $this->task_md->set_restore_task();
	}

	//Редактирование задачи
	public function editTask()
	{
		echo json_encode($this->task_md->editTask());
	}

	//Добавление примечания 
	public function add_event_task()
	{
		$arr = $this->task_md->add_event_task();

		echo '<div class="event_transfer' . $arr['id_event'] . '">';
		echo '<div class="show_event">';
		echo '<div class="functional-event-deal">
				<div class="edit-event-deal" id_event="' . $arr['id_event'] . '"></div>
				<div class="del-event-deal" id_event="' . $arr['id_event'] . '"></div>
			</div>';
			echo '<p class="human author">' . $arr['author'] . ', ' . $arr['data'] . '</p>';
			$input = nl2br($arr['description']);
			$repl = str_replace('<br />', ' <br/> ', $input);
			$temp = explode(' ', $repl);
			foreach( $temp as $key => $v_temp )
			{
				$v_temp = trim($v_temp);
				if( substr($v_temp, 0, 4) == 'http' || substr($v_temp, 0, 5) == 'https' )
				{
					$temp[$key] = '<a href="' . $v_temp . '" target="_blank">' . $v_temp . '</a>';
				}
				elseif( substr($v_temp, 0, 3) == 'www' )
				{
					$temp[$key] = '<a href="http://' . $v_temp . '" target="_blank">' . $v_temp . '</a>';
				}
			}
			$temp_out = implode(' ', $temp);
			echo '<p class="p-' . $arr['id_event'] . '">' . $temp_out . '</p>';

			echo '<div id="' . $arr['id_event'] . '"></div>';
			echo '<div class="save-edit-response-' . $arr['id_event'] . ' hide" style="margin: 0 0 10px 3%;">
					<textarea class="response_edit_text edit-deal-event-' . $arr['id_event'] . '" style="display: block; margin: 0 0 5px 0;">' . $temp_out . '</textarea>
					<div style="clear: both;"></div>
					<button class="edit-save-response" id_save="' . $arr['id_event'] . '">Сохранить</button>
					<button class="edit-cancel-response">Отмена</button>
				</div>';
			/*echo '<div class="response_block" id_event="' . $arr['id_event'] . '">
					<div class="response" open-class="' . $arr['id_event'] . '">Добавить комментарий</div>
					<div style="clear:both;"></div>
					<textarea class="response_text ' . $arr['id_event'] . '" text-event="' . $arr['id_event'] . '"></textarea>
					<div class="save-field-response ' . $arr['id_event'] . '">
						<button class="save-response" id_save="' . $arr['id_event'] . '">Сохранить</button>
						<button class="cancel-response">Отмена</button>
					</div>
				 </div>';*/
		echo '</div>';
		echo '</div>';
	}

	//Отвязуем задачу от сделки
	public function unlinkDealTask()
	{
		$idDeal = $this->input->post('idDeal');
		$idTask = $this->input->post('idTask');
		echo $this->task_md->unlinkDealTask($idDeal, $idTask);
	}
}