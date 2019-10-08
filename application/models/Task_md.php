<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Task_md extends CI_Model {

	function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->library('ion_auth');
		$this->load->library('session');
	}
	
	//Проверяем дедлайны задач, если просрочено то обновим статус
	function update_stat()
	{
		$query = $this->db->get_where('crm_tasks', array('state' => 1));
		
		//Заносим во временный массив
		$temp_array = $query->result_array();
		
		//прогоняем задачи, если просрочены то пздц и обновим в базу
		foreach( $temp_array as $list )
		{
			$dt = (strtotime($list['deadline']) - strtotime(date('Y-m-d')))/(3600*24);
			if( $dt < 0 )
			{
				$data = Array(
					'state' => 3
				);
				
				$this->db->where('id', $list['id']);
				$this->db->update('crm_tasks', $data); 
			}
		}
	}
	
	//Получаем список моих активных задач
	function getMyTask( $id_user = '' )
	{
		//$this->db->order_by('deadline', 'desc');
		//$query = $this->db->get_where('crm_tasks', array('user_id' => $id_user, 'state' => 1));
		$query = $this->db->query('SELECT*FROM crm_tasks WHERE user_id RLIKE "[[:<:]]' . $id_user . '[[:>:]]" AND state != 2 AND state != 0 ORDER BY deadline ASC');
		
		//Заносим во временный массив
		$temp_array = $query->result_array();
		
		//Если в массиве есть значения то продолжаем
		if( count( $temp_array ) != 0 )
		{
			foreach( $temp_array as $list )
			{
				//Вытаскиваем создателя задачи
				$query = $this->db->get_where('meta', array('user_id' => $list['author']));
				$author = $query->row_array();
				
				//$type_task = $this->db->get_where('type_task', array('id' => $list['type']));
				//$type = $type_task->row_array();
				//echo $list['type'] . ', ';
				//Проверим к чему относится задача (обычная, сделка, клиент)
				$query_category_task = $this->db->get_where('crm_events', array('id_task' => $list['id']));
				$category_task = $query_category_task->row_array();

				$category = Array();

				if( !empty($category_task) )
				{
					if( $category_task['id_deal'] != NULL && $category_task['id_deal'] != 0 )
					{
						//echo $category_task['id_deal'];
						//Загрузим NAME сделки
						$query_deal = $this->db->get_where('crm_deals', array('id' => $category_task['id_deal']));
						$deal = $query_deal->row_array();

						$category = Array('id' => $category_task['id_deal'], 'name' => $deal['name_deal'], 'cat' => 1);
					}
					else
					{
						//echo 'Клиент' . $category_task['id_client'] . '<br>';
					}
				}
				

				$arr = Array(
					//'type' => $type['name'],
					'type_id' => $list['type'],
					'data_create' => $list['create_date'],
					'deadline' => $list['deadline'],
					'author' => $author['first_name'] . ' ' . $author['last_name'],
					'author_id' => $list['author'],
					'title' => $list['title'],
					'text' => $list['description'],
					'user_id' => $list['user_id'],
					'id' => $list['id'],
					'state' => $list['state'],
					'category' => $category
				);
				
				$task_array[] = $arr;
			}
			return $task_array;
		}
	}

	//Открываем комментарии для задачи
	function getTaskForId( $id, $idProfile )
	{
		//Выберем задачу по предложеным ID задачи и залогиненому ID профилю
		$query = $this->db->get_where('crm_tasks', array('id' => $id));
		$res = $query->row_array();
		
		//Загружаем адресатов задачи
		$users = explode(',', $res['user_id']);

		//Если в исполнителях такого профиля нету то товарищ идет нахуй
		if( in_array($idProfile, $users) || $res['author'] == $idProfile )
		{
			//Вытаскиваем примечания для задачи
			$this->db->order_by('id', 'desc');
			$query = $this->db->get_where('crm_events', array('id_task' => $id));
			$temp_events = $query->result_array();

			$events = Array();
			foreach($temp_events as $list_events)
			{
				//Проверяем ответы на примечание
				$sub_query = $this->db->get_where('crm_events', array('sub_id' => $list_events['id']));
				$sub_res = $sub_query->result_array();

				if( !empty($sub_res) )
				{
					$sub = Array();
					foreach( $sub_res as $s )
					{
						$sub_author = $this->clients_md->get_author_event($s['author']);
						$sub[] = Array(
							'author' => $sub_author,
							'description' => $s['description'],
							'id_event' => $s['id'],
							'date' => $s['date']
						);
					}
				}
				else
				{
					$sub = 0;
				}

				$events[] = Array(
					'id_event' => $list_events['id'],
					'id_author' => $list_events['author'],
					'author' => $this->clients_md->get_author_event($list_events['author']),
					'description' => $list_events['description'],
					'date' => $list_events['date'],
					'sub_event' => 0 //Тут принудительно ставим 0 для задач
				);
			}

			$list_users = Array();
			foreach($users as $list)
			{
				$list_users[] = Array('id' => $list, 'name' => $this->clients_md->get_author_event($list));
			}

			//Проверим к чему относится задача (обычная, сделка, клиент)
			$query_category_task = $this->db->get_where('crm_events', array('id_task' => $res['id'], 'sub_id' => NULL));
			$category_task = $query_category_task->result_array();
			
			$category = Array();

			if( count($category_task) != 0 )
			{
				foreach($category_task as $linking)
				{
					if( $linking['id_deal'] != NULL && $linking['id_deal'] != 0 )
					{
						//echo $category_task['id_deal'];
						//Загрузим NAME сделки
						$query_deal = $this->db->get_where('crm_deals', array('id' => $linking['id_deal']));
						$deal = $query_deal->row_array();

						$category = Array('id' => $linking['id_deal'], 'sub' => $linking['id'], 'name' => $deal['name_deal'], 'cat' => 1);
					}
					else
					{
						//echo 'Клиент' . $category_task['id_client'] . '<br>';
					}
				}
			}

			//Проверим статус задачи
			$query_status = $this->db->get_where('state_task', array('id' => $res['state']));
			$status = $query_status->row_array();

			$arr = Array(
				'id' => $res['id'],
				'author' => $this->clients_md->get_author_event($res['author']),
				'author_id' => $res['author'],
				'title' => $res['title'],
				'description' => $res['description'],
				'finish_result' => $res['finish_result'],
				'user_id' => $list_users,
				'create_date' => substr($res['create_date'], 8, 2) . '.' . substr($res['create_date'], 5, 2) . '.' . substr($res['create_date'], 0, 4),
				'deadline' => substr($res['deadline'], 8, 2) . '.' . substr($res['deadline'], 5, 2) . '.' . substr($res['deadline'], 0, 4),
				'data_finish' => substr($res['data_finish'], 8, 2) . '.' . substr($res['data_finish'], 5, 2) . '.' . substr($res['data_finish'], 0, 4),
				'events' => $events,
				'category' => $category,
				'status' => $status
			);

			return $arr;
		}
	}

	//Получаем весь список состояния задач
	function get_list_task()
	{
		$this->db->order_by('id', 'asc');
		$query = $this->db->get('state_task');
		return $query->result_array();
	}

	//Редактирование задач
	function getEditTaskForId( $id )
	{
		$query = $this->db->get_where('crm_tasks', array('id' => $id));
		$res = $query->row_array();
		
		//Загружаем адресатов задачи
		$users = explode(',', $res['user_id']);
		$list_users = Array();

		foreach($users as $list)
		{
			$list_users[] = Array('name' => $this->clients_md->get_author_event($list), 'id' => $list);
		}

		$arr = Array(
			'id' => $res['id'],
			'author' => $this->clients_md->get_author_event($res['author']),
			'title' => $res['title'],
			'description' => $res['description'],
			'user_id' => $list_users,
			'create_date' => substr($res['create_date'], 8, 2) . '.' . substr($res['create_date'], 5, 2) . '.' . substr($res['create_date'], 0, 4),
			'deadline' => substr($res['deadline'], 8, 2) . '.' . substr($res['deadline'], 5, 2) . '.' . substr($res['deadline'], 0, 4)
		);

		return $arr;
	}
	
	//Получаем список завершенных моих задач
	function get_finish_task( $id_user )
	{
		//$this->db->order_by('data_finish', 'desc');
		//$query = $this->db->get_where('crm_tasks', array('user_id' => $id_user, 'state' => 2));
		$query = $this->db->query('SELECT*FROM crm_tasks WHERE user_id RLIKE "[[:<:]]' . $id_user .'[[:>:]]" AND state = 2 ORDER BY data_finish DESC');
		
		//Заносим во временный массив
		$temp_array = $query->result_array();
		
		//Если массив не пустой то продолжаем
		if( count( $temp_array ) != 0 )
		{
			foreach( $temp_array as $list )
			{
				//Вытаскиваем создателя задачи
				$query = $this->db->get_where('meta', array('user_id' => $list['author']));
				$author = $query->row_array();

				$type_task = $this->db->get_where('type_task', array('id' => $list['type']));
				$type = $type_task->row_array();
				
				//Проверим к чему относится задача (обычная, сделка, клиент)
				$query_category_task = $this->db->get_where('crm_events', array('id_task' => $list['id'], 'sub_id' => NULL));
				$category_task = $query_category_task->row_array();

				$category = Array();

				if( !empty($category_task) )
				{
					if( $category_task['id_deal'] != NULL && $category_task['id_deal'] != 0 )
					{
						//echo $category_task['id_deal'];
						//Загрузим NAME сделки
						$query_deal = $this->db->get_where('crm_deals', array('id' => $category_task['id_deal']));
						$deal = $query_deal->row_array();

						$category = Array('id' => $category_task['id_deal'], 'sub' => $category_task['id'], 'name' => $deal['name_deal'], 'cat' => 1);
					}
					else
					{
						//echo 'Клиент' . $category_task['id_client'] . '<br>';
					}
				}

				$arr = Array(
					//'type' => $type['name'],
					//'type_id' => $list['type'],
					'data_create' => $list['create_date'],
					'deadline' => $list['deadline'],
					'data_finish' => $list['data_finish'],
					'title' => $list['title'],
					'description' => $list['description'],
					'author' => $author['first_name'] . ' ' . $author['last_name'],
					'author_id' => $list['author'],
					'text' => $list['description'],
					'text_result' => $list['finish_result'],
					'user_id' => $list['user_id'],
					'id' => $list['id'],
					'category' => $category
				);
				
				$task_array[] = $arr;
			}
			
			return $task_array;
		}
	}
	
	//Получаем список просроченных моих задач
	function get_over_task( $id_user = '' )
	{
		$this->db->order_by('id', 'desc');
		//$query = $this->db->get_where('crm_tasks', array('user_id' => $id_user, 'state' => 3));
		$query = $this->db->query('SELECT*FROM crm_tasks WHERE user_id RLIKE "[[:<:]]' . $id_user .'[[:>:]]" AND state = 3');
		
		//Заносим во временный массив
		$temp_array = $query->result_array();
		
		//Если массив не пустой то продолжаем
		if( count( $temp_array ) != 0 )
		{
			foreach( $temp_array as $list )
			{
				//Вытаскиваем создателя задачи
				$query = $this->db->get_where('meta', array('user_id' => $list['author']));
				$author = $query->row_array();
				
				$type_task = $this->db->get_where('type_task', array('id' => $list['type']));
				$type = $type_task->row_array();

				//Проверим к чему относится задача (обычная, сделка, клиент)
				$query_category_task = $this->db->get_where('crm_events', array('id_task' => $list['id'], 'sub_id' => NULL));
				$category_task = $query_category_task->row_array();

				$category = Array();

				if( !empty($category_task) )
				{
					if( $category_task['id_deal'] != NULL && $category_task['id_deal'] != 0 )
					{
						//echo $category_task['id_deal'];
						//Загрузим NAME сделки
						$query_deal = $this->db->get_where('crm_deals', array('id' => $category_task['id_deal']));
						$deal = $query_deal->row_array();

						$category = Array('id' => $category_task['id_deal'], 'sub' => $category_task['id'], 'name' => $deal['name_deal'], 'cat' => 1);
					}
					else
					{
						//echo 'Клиент' . $category_task['id_client'] . '<br>';
					}
				}

				$arr = Array(
					//'type' => $type['name'],
					//'type_id' => $list['type'],
					'data_create' => $list['create_date'],
					'deadline' => $list['deadline'],
					'data_finish' => $list['data_finish'],
					'title' => $list['title'],
					'description' => $list['description'],
					'author' => $author['first_name'] . ' ' . $author['last_name'],
					'author_id' => $list['author'],
					'text' => $list['description'],
					'text_result' => $list['finish_result'],
					'user_id' => $list['user_id'],
					'id' => $list['id'],
					'category' => $category
				);
				
				$task_array[] = $arr;
			}
			
			return $task_array;
		}
	}

	//Получаем список назначенных задач
	function getAssignedTask( $idProfile )
	{
		$this->db->order_by('id', 'desc');
		$this->db->where('state !=', 0);
		$this->db->where('author', $idProfile);
		$query = $this->db->get('crm_tasks');
		
		//Заносим во временный массив
		$temp_array = $query->result_array();
		
		//Если массив не пустой то продолжаем
		if( count( $temp_array ) != 0 )
		{
			foreach( $temp_array as $list )
			{
				//Вытаскиваем исполнителей задачи
				$query = $this->db->query('SELECT*FROM meta WHERE user_id in(' . $list['user_id'] .')');
				$users = $query->result_array();
				
				//$type_task = $this->db->get_where('type_task', array('id' => $list['type']));
				//$type = $type_task->row_array();

				//Проверим к чему относится задача (обычная, сделка, клиент)
				$query_category_task = $this->db->get_where('crm_events', array('id_task' => $list['id'], 'sub_id' => NULL));
				$category_task = $query_category_task->row_array();

				$category = Array();

				if( !empty($category_task) )
				{
					if( $category_task['id_deal'] != NULL && $category_task['id_deal'] != 0 )
					{
						//echo $category_task['id_deal'];
						//Загрузим NAME сделки
						$query_deal = $this->db->get_where('crm_deals', array('id' => $category_task['id_deal']));
						$deal = $query_deal->row_array();

						$category = Array('id' => $category_task['id_deal'], 'sub' => $category_task['id'], 'name' => $deal['name_deal'], 'cat' => 1);
					}
					else
					{
						//echo 'Клиент' . $category_task['id_client'] . '<br>';
					}
				}
				
				$arr = Array(
					'data_create' => $list['create_date'],
					'deadline' => $list['deadline'],
					'author_id' => $list['author'],
					'data_finish' => $list['data_finish'],
					'title' => $list['title'],
					'description' => $list['description'],
					'state' => $list['state'],
					'text' => $list['description'],
					'text_result' => $list['finish_result'],
					'users' => $users,
					'id' => $list['id'],
					'category' => $category
				);
				
				$task_array[] = $arr;
			}
			
			return $task_array;
		}
	}
	
	//Получаем список моих всех задач
	/*function get_my_task( $id_user = '' )
	{
		$this->db->order_by('id', 'desc');
		$query = $this->db->get_where('crm_tasks', array('user_id' => $id_user, 'state' => 1));
		
		//Заносим во временный массив
		$temp_array = $query->result_array();
		
		//Если в массиве есть значения то продолжаем
		if( count( $temp_array ) != 0 )
		{
			foreach( $temp_array as $list )
			{
				//Вытаскиваем создателя задачи
				$query = $this->db->get_where('meta', array('user_id' => $list['author']));
				$author = $query->row_array();
				
				$arr = Array(
					'data_create' => $list['create_date'],
					'deadline' => $list['deadline'],
					'author' => $author['first_name'] . ' ' . $author['last_name'],
					'author_id' => $list['author'],
					'text' => $list['description'],
					'user_id' => $list['user_id'],
					'id' => $list['id']
				);
				
				$task_array[] = $arr;
			}
			return $task_array;
		}
	}*/
	
	//Получаем всех юзеров
	function get_all_users()
	{	
		$this->db->order_by('id', 'desc');
		$query = $this->db->get('meta');
		return $query->result_array();
	}
	
	function createTask()
	{
		//Загрузка данных профиля
		$profile = $this->ion_auth->profile();
		$profile = json_encode($profile, true);
		$profile = json_decode($profile, true);
		
		$array_users = $this->input->post('taskUserArr');
		$titleTask = $this->input->post('titleTask');
		$taskDescription = $this->input->post('taskDescription');
		$deadlineTemp = $this->input->post('taskDeadline');
		$idDeal = $this->input->post('idDeal');
		
		$deadline = substr($deadlineTemp, 6, 4) . '-' . substr($deadlineTemp, 3, 2) . '-' . substr($deadlineTemp, 0, 2);

		if( count( $array_users ) == 1 && substr($array_users['0'], 0, 5) != 'user_' )
		{
			//Создаем событие создания новой задачи
			$data_event = Array(
				'author' => $profile['id'],
				'date' => date('Y-m-d H-i-s'),
				'description' => $taskDescription,
				'id_deal' => $idDeal
			);
			
			//Вносим событие создания компании в БД и узнаем ID
			$this->db->insert('crm_events', $data_event); 
			$id_event = mysql_insert_id();

			$data = Array(
				'author' => $profile['id'],
				'user_id' => $profile['id'],
				'create_date' => date('Y-m-d'),
				'deadline' => $deadline,
				'title' => $titleTask,
				'description' => $taskDescription,
				'state' => 1,
				'events' => $id_event,
				'all_or_one' => 1
			);
			
			$this->db->insert('crm_tasks', $data); 
			$id_task = mysql_insert_id();

			$user_id = $profile['id'];

			$users = Array($profile['id']);
		}
		else
		{
			//Создаем событие создания новой компании
			$data_event = Array(
				'author' => $profile['id'],
				'date' => date('Y-m-d H-i-s'),
				'description' => $taskDescription,
				'id_deal' => $idDeal
			);
			
			//Вносим событие создания компании в БД и узнаем ID
			$this->db->insert('crm_events', $data_event); 
			$id_event = mysql_insert_id();
			
			//Заносим задачи для каждого получателя
			$user_id = '';
			$users = Array();
			foreach( $array_users as $id )
			{
				$user_id .= substr($id, 5) . ',';
				$users[] = substr($id, 5);
			}
			$user_id = substr($user_id, 0, -1);
				
			$data = Array(
				'author' => $profile['id'],
				'user_id' => $user_id,
				'create_date' => date('Y-m-d'),
				'title' => $titleTask,
				'deadline' => $deadline,
				'description' => $taskDescription,
				'state' => 1,
				'events' => $id_event,
				'all_or_one' => 1
			);
			
			$this->db->insert('crm_tasks', $data); 
			$id_task = mysql_insert_id();
		}
		
		if( $idDeal != 0 || !empty($idDeal) )
		{
			$data_d = Array('data' => date('Y-m-d'));
			$this->db->where('id', $idDeal);
			$this->db->update('crm_deals', $data_d);
		}

		//обновим события, добавим ID задачи
		$data_event = Array(
			'id_task' => $id_task
		);

		$this->db->where('id', $id_event);
		$this->db->update('crm_events', $data_event);

		$ret = Array(
				'author' => $profile['id'],
				'create_date' => date('Y-m-d'),
				'deadline' => $deadlineTemp,
				'description' => $taskDescription,
				'title' => $titleTask,
				'events' => $id_event,
				'id_task' => $id_task,
				'user_mail' => $user_id,
				'user_id' => $users
			);

		//return $ret;
	}
	
	function setFinishTask()
	{
		//Загрузка данных профиля
		$profile = $this->ion_auth->profile();
		$profile = json_encode($profile, true);
		$profile = json_decode($profile, true);
		
		$idTask = $this->input->post('idTask');
		$idDeal = $this->input->post('idDeal');
		$resultText = $this->input->post('resultText');

		//Сделаем запрос в БД по этой задаче
		$query = $this->db->query('SELECT*FROM crm_tasks WHERE user_id RLIKE "[[:<:]]' . $profile['id'] . '[[:>:]]" AND id = ' . $idTask);
		$res = $query->row_array();

		//Если ID профиля совпало с одним из исполнителем задачи то продолжаем
		if( count($res) != 0 )
		{
			//Создаем событие завершения задачи
			$data_event = Array(
				'author' => $profile['id'],
				'date' => date('Y-m-d H-i-s'),
				'id_task' => $idTask,
				'description' => 'Задача завершена: ' . $resultText
			);

			//Если задача со сделки то добавим

			if( isset($idDeal) && !empty($idDeal) )
			{
				$data_event['id_deal'] = $idDeal;
			}
			
			//Вносим событие создания компании в БД и узнаем ID
			$this->db->insert('crm_events', $data_event); 
			$id_event = mysql_insert_id();
			
			//Выбираем задачу
			//Задача уже выбрана при проверке
			//просто заюзаем готовый массив $res;
			
			//Обновляем данные
			$data = Array(
				'finish_result' => $resultText,
				'data_finish' => date('Y-m-d'),
				'state' => 2,
				'events' => $res['events'] . ',' . $id_event
			);
			
			$this->db->where('id', $idTask);

			if( $this->db->update('crm_tasks', $data) )
			{
				$query = $this->db->get_where('users', array('id' => $res['author']) );
				$author = $query->row_array();

				$res['finish_result'] = $resultText;
				$res['data_finish'] = $data['data_finish'];

				$array = Array(
					'task' => $res,
					'author' => $author
					);

				return $array;
			}
		}
	}
	
	function setDellTask()
	{
		$idTask = $this->input->post('idTask');

		//Загрузка данных профиля
		$profile = $this->ion_auth->profile();
		$profile = json_encode($profile, true);
		$profile = json_decode($profile, true);
		
		//Выбираем пользователя
		$query = $this->db->get_where('crm_tasks', array('id' => $idTask));
		$arr = $query->row_array();

		//Проверим автор ли удаляет

		if( $arr['author'] == $profile['id'])
		{
			//Создаем событие удаления задачи
			$data_event = Array(
				'author' => $profile['id'],
				'date' => date('Y-m-d H-i-s'),
				'description' => '<p>Задача удалена</p>'
			);
			
			//Вносим событие удаления задачи в БД и узнаем ID
			$this->db->insert('crm_events', $data_event); 
			$idEvent = mysql_insert_id();
			
			//Обновляем данные
			$data = Array(
				'state' => 0,
				'events' => $arr['events'] . ',' . $idEvent
			);
			
			$this->db->where('id', $idTask);
			$this->db->update('crm_tasks', $data);
			return $idTask;
		}
	}
	
	function get_task_data()
	{
		//Загрузка данных профиля
		$profile = $this->ion_auth->profile();
		$profile = json_encode($profile, true);
		$profile = json_decode($profile, true);
		$id_user = $profile['id'];
		$data = $this->input->post('data');
		
		$this->db->order_by('id', 'desc');
		$query = $this->db->get_where('crm_tasks', array('user_id' => $id_user, 'state' => 1, 'deadline' => $data));
		
		//Заносим во временный массив
		$temp_array = $query->result_array();
		
		//Если в массиве есть значения то продолжаем
		if( count( $temp_array ) != 0 )
		{
			foreach( $temp_array as $list )
			{
				//Вытаскиваем создателя задачи
				$query = $this->db->get_where('meta', array('user_id' => $list['author']));
				$author = $query->row_array();
				
				$arr = Array(
					'data_create' => $list['create_date'],
					'deadline' => $list['deadline'],
					'author' => $author['first_name'] . ' ' . $author['last_name'],
					'author_id' => $list['author'],
					'text' => $list['description'],
					'user_id' => $list['user_id'],
					'id' => $list['id']
				);
				
				$task_array[] = $arr;
			}
			return $task_array;
		}
	}
	
	function set_restore_task()
	{
		$id = $this->input->post('task_id');
		
		$profile = $this->ion_auth->profile();
		$profile = json_encode($profile, true);
		$profile = json_decode($profile, true);
		
		//Создаем событие удаления задачи
		$data_event = Array(
			'author' => $profile['id'],
			'date' => date('Y-m-d H-i-s'),
			'description' => 'Задача восстановлена'
		);
		
		//Выбираем задачу
		$query = $this->db->get_where('crm_tasks', array('id' => $id));
		$arr = $query->row_array();
		
		//Вносим событие восстановления задачи в БД и узнаем ID
		$this->db->insert('crm_events', $data_event); 
		$id_event = mysql_insert_id();
		
		//Обновляем данные
		$data = Array(
			'state' => 1,
			'finish_result' => '',
			'create_date' => date('Y-m-d'),
			'deadline' => date('Y-m-d'),
			'data_finish' => NULL,
			'events' => $arr['events'] . ',' . $id_event
		);
		
		$this->db->where('id', $id);
		if( $this->db->update('crm_tasks', $data) )
		{
			return $id;
		}
	}

	//Функция редактирования задач
	function editTask()
	{
		//Загрузка данных профиля
		$profile = $this->ion_auth->profile();
		$profile = json_encode($profile, true);
		$profile = json_decode($profile, true);

		$arrayUsers = $this->input->post('taskUserArr');
		$title = $this->input->post('taskTitle');
		$description = $this->input->post('editDescrTask');
		$deadlineTemp = $this->input->post('taskDeadline');
		$idDeal = $this->input->post('idDeal');
		$idTask = $this->input->post('idTask');
		$nameDeal = $this->input->post('nameDeal');

		//Если ID автора не совпадает с ID залогиненого то нихуя не обновляем
		$safety_query = $this->db->get_where('crm_tasks', array('id' => $idTask, 'author' => $profile['id']));

		if( count($safety_query->row_array()) > 0 )
		{
			$deadline = substr($deadlineTemp, 6, 4) . '-' . substr($deadlineTemp, 3, 2) . '-' . substr($deadlineTemp, 0, 2);
			$state = (strtotime($deadline) - strtotime(date('Y-m-d')))/(3600*24);

			if( empty($arrayUsers) )
			{
				
				$data = Array(
					'user_id' => $profile['id'],
					'deadline' => $deadline,
					'title' => $title,
					'description' => $description,
					'all_or_one' => 1,
					'state' => 3
				);

				if( $state >= 0 )
				{
					$data['state'] = 1;
				}

				$this->db->where('id', $id);
				$this->db->update('crm_tasks', $data); 

				$userNameData = $this->clients_md->get_author_event($profile['id']);
				$user_name = $userNameData['name'] . '_' . $profile['id'];

				$state = $data['state'];
			}
			else
			{			
				//Заносим задачи для каждого получателя
				$user_id = '';
				$user_name = '';

				foreach( $arrayUsers as $id_u )
				{
					$user_id .= substr($id_u, 5) . ',';
					$userNameData = $this->clients_md->get_author_event(substr($id_u, 5));
					$user_name .=  $userNameData['name'] . '_' . substr($id_u, 5) . ', ';
				}
				$user_id = substr($user_id, 0, -1);
				$user_name = substr($user_name, 0, -2);
					
				$data = Array(
					'user_id' => $user_id,
					'deadline' => $deadline,
					'title' => $title,
					'description' => $description,
					'state' => 3,
					'all_or_one' => 1
				);

				if( $state >= 0 )
				{
					$data['state'] = 1;
				}
				
				$this->db->where('id', $idTask);
				$this->db->update('crm_tasks', $data); 

				$state = $data['state'];
			}

			//Изменяем дату, для поднятия сделки вверх списка
			if( $idDeal != 0 )
			{
				$data_d = Array('data' => date('Y-m-d'));
				$this->db->where('id', $idDeal);
				$this->db->update('crm_deals', $data_d);
			}

			$errLinkDeal = 0;

			if( isset($nameDeal) && !empty($nameDeal) )
			{
				$query = $this->db->get_where('crm_deals', array('name_deal' => $nameDeal));
				$res = $query->row_array();
				if(count($res) == 0)
				{
					$errLinkDeal = 1;
				}
				else
				{
					//Обновляем ID сделки везде где есть эта задача
					$where = Array('id_task' => $idTask);
					$this->db->where($where);
					$dataUpdateID['id_deal'] = $res['id'];
					$this->db->update('crm_events', $dataUpdateID);

					//Вносим новые события
					$data_event = Array(
						'author' => $profile['id'],
						'date' => date('Y-m-d H-i-s'),
						'description' => 'Задача ' . $title . ' привязана к сделке ' . $res['name_deal'],
						'id_task' => $idTask,
						'id_deal' => $res['id']
					);
					$idDeal = $res['id'];
					$nameDeal = $res['name_deal'];

					//Вносим событие в БД и узнаем ID
					$this->db->insert('crm_events', $data_event); 
					$id_event = mysql_insert_id();
				}
			}
			else
			{
				$where = Array('id_deal' => $idDeal, 'id_task' => $idTask);
				$this->db->where($where);
				$dataUnlink['id_task'] = NULL;
				$this->db->update('crm_events', $dataUnlink);
			}
			
			$query = $this->db->get_where('state_task', array('id' => $state));
			$res = $query->row_array();
			
			$arr = Array(
				'nameTask' => $title,
				'id_task' => $idTask, 
				'nameDeal' => $nameDeal, 
				'idDeal' => $idDeal, 
				'deadline' => $deadlineTemp, 
				'state' => $state,
				'stateValue' => $res['value'], 
				'color' => '#' . $res['color'], 
				'description' => $description, 
				'users' => $user_name
				);
			return $arr;
		}
	}

	//Добавление события со страницы сделки
	function add_event_task()
	{
		$id_task = $this->input->post('id');
		$id_deal = $this->input->post('id_deal');
		$id_sub = $this->input->post('sub_id');
		$description = $this->input->post('description');
		
		//Получаем ID автора события
		$author_array_json = $this->ion_auth->profile();

		$author1 = json_encode($author_array_json, true);
		$author = json_decode($author1, true);
		
		//Вносим новые события
		$data_event = Array(
			'author' => $author['id'],
			'date' => date('Y-m-d H-i-s'),
			'description' => $description,
			'id_task' => $id_task,
			'id_deal' => $id_deal,
			'sub_id' => $id_sub
		);
		
		//Вносим событие в БД и узнаем ID
		$this->db->insert('crm_events', $data_event); 
		$id_event = mysql_insert_id();

		$ret = Array(
			'author' => $author['first_name'] . ' ' . $author['last_name'],
			'data' => 'Сегодня в ' . date('H:i'),
			'description' => $description,
			'id_event' => $id_event
			);

		return $ret;
	}
}