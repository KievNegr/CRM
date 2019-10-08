<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Deal_md extends CI_Model {

	function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->library('ion_auth');
		$this->load->library('session');
		$this->load->model('clients_md');
	}
	
	//Получаем весь список состояния сделок
	function get_list_deal()
	{
		$this->db->order_by('id', 'asc');
		$query = $this->db->get('state_deal');
		return $query->result_array();
	}
	
	//Получаем список всех сделок
	function getAllDeals()
	{
		$query = $this->db->get('crm_deals');
		return $query->result_array();
	}

	//Получаем список всех валют
	function getAllCurrency()
	{
		$query = $this->db->get('currency');
		return $query->result_array();
	}

	//Получаем список сделок
	function get_deal( $state_id = '', $my = '', $myId = '' )
	{
		
		//$this->db->order_by('id', 'desc');
		
		if( $my == 'my' )
		{
			$stateId = implode(',', $state_id);
			$query = $this->db->query('SELECT*FROM crm_deals WHERE /*state_id in (' . $stateId . ') AND*/ author = ' . $myId . ' ORDER BY state_id ASC, data DESC');
		}
		else
		{
			if( is_array( $state_id ) )
			{
				$stateId = implode(',', $state_id);
				$query = $this->db->query('SELECT*FROM crm_deals WHERE state_id in (' . $stateId . ') ORDER BY state_id ASC, data DESC');
				//$query = $this->db->get_where('', );
			}
			else
			{
				$query = $this->db->get_where('crm_deals', array('state_id' => $state_id));
			}
		}
		
		
		$deal_array = Array();
		$temp_array = $query->result_array();
	
		foreach( $temp_array as $list)
		{
			$client_name = 0;
			$client_id = 0;
			$company_name = 0;
			$company_id = 0;
			$client_id_c = 0;

			//Проверяем есть ли дополнительные контактные лица для сделки
			$res_temp = Array();
			if( $list['sub_client'] != NULL )
			{
				$sub_client = substr($list['sub_client'], 0, -1);
				//$this->db->get('crm_clients');
				$query = $this->db->query('SELECT*FROM crm_clients WHERE id in (' . $sub_client . ')');
				$res_temp = $query->result_array();
			}
			
			//Находим название конторы и его ID если они были введены при создании
			if( $list['company_id'] != 0 )
			{
				$query = $this->db->get_where('crm_company', array('id' => $list['company_id']));
				$query_company = $query->row_array();
				$company_name = $query_company['name'];
			}
			
			//Узнаем цвет сделки и ее название
			$query = $this->db->get_where('state_deal', array('id' => $list['state_id']));
			$query_color= $query->row_array();
			$color = $query_color['color'];
			$status_deal = $query_color['value'];
			$statusID = $query_color['id'];
			
			//Вытаскиваем дату изменения из события
			$event_arr = explode(',', $list['events']);
			$event_id = array_pop($event_arr);

			$query = $this->db->get_where('crm_events', array('id' => $event_id));
			$event = $query->row_array();
			$data_last = $event['date'];
			
			$arr = Array(
				'id' => $list['id'],
				'author_deal' => $this->clients_md->get_author_event($list['author']),
				'name_deal' => $list['name_deal'],
				'description' => $list['description'],
				'data' => $list['data'],
				'client_name' => $client_name,
				//'client_id' => $client_id,
				'sub_contact' => $res_temp,
				'company_name' => $company_name,
				'company_id' => $list['company_id'],
				'data_edit' => $data_last,
				'events' => $list['events'],
				'budget' => $list['budget'],
				'currencyId' => $list['currency'],
				'status_id' => $statusID,
				'color' => $color,
				'status_deal' => $status_deal
			);
			
			$deal_array[] = $arr;
		}
		return $deal_array;
	}

	//Получаем инфу о сделке
	function showDeal( $id_deal = '' )
	{
		$query = $this->db->get_where('crm_deals', array('id' => $id_deal));
		
		$deal_array = Array();
		$list = $query->row_array();

		//Проверяем есть ли дополнительные контактные лица для сделки
		$res = Array();
		$res_temp = Array();
		if( !empty($list['sub_client']) )
		{
			$sub_client = substr($list['sub_client'], 0, -1);
			//$this->db->get('crm_clients');
			$query = $this->db->query('SELECT*FROM crm_clients WHERE id in(' . $sub_client .')');
			$res = $query->result_array();

			//Вытащим ихние контакты
			foreach( $res as $res_l )
			{
				$query = $this->db->get_where('crm_contacts', array('id' => $res_l['id_contact']));
				$query_cont = $query->row_array();
				$res_temp[] = Array('profile' => $res_l, 'contact' => $query_cont);
			}
		}
		
		//Находим имя автора сделки
		$query = $this->db->get_where('meta', array('id' => $list['author']));
		$query_author = $query->row_array();
		$author_name = $query_author['first_name'] . ' ' . $query_author['last_name'];
		
		//Находим название конторы и его ID
		if( $list['company_id'] != 0 )
		{
			$query = $this->db->get_where('crm_company', array('id' => $list['company_id']));
			$query_company = $query->row_array();
			$company_name = $query_company['name'];
			$company_id = $query_company['id'];
		}
		else
		{
			$company_name = '';
			$company_id = 0;
		}
		
		//Узнаем цвет сделки и ее название
		$query = $this->db->get_where('state_deal', array('id' => $list['state_id']));
		$query_color= $query->row_array();
		$color = $query_color['color'];
		$status_deal = $query_color['value'];
		
		//Вытаскиваем события
		//$event_arr = explode(',', $list['events']);
		$this->db->order_by('id', 'desc');
		$query = $this->db->get_where('crm_events', array('id_deal' => $id_deal));
		$temp_events = $query->result_array();

		//Что б не попадались дубли если задача завершена
		$tempDouble = Array();

		if( !empty($temp_events) )
		{
			foreach( $temp_events as $temp_ev )
			{
				if( $temp_ev['sub_id'] == NULL )
				{
					$author = $this->clients_md->get_author_event($temp_ev['author']);
					$state = 1;
					//А тут еще проверим наличие задачи для сделки
					//Что б не попадались дубли если задача завершена
					if( $temp_ev['id_task'] != NULL || $temp_ev['id_task'] != 0 )
					{
						if( !in_array($temp_ev['id_task'], $tempDouble) )
						{
							$query = $this->db->get_where('crm_tasks', array('id' => $temp_ev['id_task']));
							$temp_task = $query->row_array();

							if( $temp_task['state'] != 0 )
							{
								$users = explode(',', $temp_task['user_id']);
								$l_users = Array();
								foreach( $users as $l_u )
								{
									$l_users[] = Array('name' => $this->clients_md->get_author_event($l_u), 'id_user' => $l_u);
								}
								//$l_users = substr($l_users, 0, -2);

								$authorTask = $this->clients_md->get_author_event($temp_task['author']);

								$task = Array(
									'author_id' => $temp_task['author'],
									'authorName' => $authorTask['name'],
									'authorDoljnost' => $authorTask['doljnost'],
									'authorAvatar' => $authorTask['avatar'],
									'users' => $l_users,
									'title' => $temp_task['title'],
									'description' => $temp_task['description'],
									'data' => $temp_task['create_date'],
									'deadline' => $temp_task['deadline'],
									'data_finish' => $temp_task['data_finish'],
									'id_task' => $temp_task['id'],
									'state' => $temp_task['state'],
									'result' => $temp_task['finish_result']
								);
							}
							else
							{
								$task = 0;
								$state = 0;
							}
						}
						else
						{
							$task = 0;
						}
						$tempDouble[] = $temp_task['id'];
					}
					else
					{
						$task = 0;
					}

					//Проверяем ответы на примечание
					$sub_query = $this->db->get_where('crm_events', array('sub_id' => $temp_ev['id']));
					$sub_res = $sub_query->result_array();

					if( !empty($sub_res) )
					{
						$sub = Array();
						foreach( $sub_res as $s )
						{
							$sub_author = $this->clients_md->get_author_event($s['author']);
							$sub[] = Array(
								'authorName' => $sub_author['name'],
								'authorDoljnost' => $sub_author['doljnost'],
								'authorAvatar' => $sub_author['avatar'],
								'author_id' => $s['author'],
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

					//Если $state != 1 то значит что задача удалена и выводить это событие не надо
					if( $state == 1 )
					{
						$event[] = Array(
							'authorName' => $author['name'],
							'authorDoljnost' => $author['doljnost'],
							'authorAvatar' => $author['avatar'],
							'description' => $temp_ev['description'],
							'id_event' => $temp_ev['id'],
							'date' => $temp_ev['date'],
							'sub_event' => $sub,
							'task' => $task,
							'id_author' => $temp_ev['author']
						);
					}
				}
			}
		}
		
		$new = Array();

		foreach( $event as $rev )
		{
			$new[$rev['id_event']] = Array(
					'authorName' => $rev['authorName'],
					'authorDoljnost' => $rev['authorDoljnost'],
					'authorAvatar' => $rev['authorAvatar'],
					'description' => $rev['description'],
					'id_event' => $rev['id_event'],
					'date' => $rev['date'],
					'sub_event' => $rev['sub_event'],
					'task' => $rev['task'],
					'id_author' => $rev['id_author']
					);
		}

		$arr = Array(
			'id' => $list['id'],
			'title_deal' => $list['name_deal'],
			'description' => $list['description'],
			'author' => $author_name,
			'author_id' => $list['author'],
			'temp_company_id' => $list['company_id'],
			//'client_name' => $client_name,
			//'client_doljnost' => $client_doljnost,
			//'client_id' => $client_id,
			//'client_contact' => $client_contact,
			'sub_client' => $res_temp,
			'company_name' => $company_name,
			'company_id' => $company_id,
			'events' => array_reverse($event),
			'budget' => $list['budget'],
			'currencyId' => $list['currency'],
			'color' => $color,
			'status_deal' => $status_deal,
			'status_deal_id' => $list['state_id']
		);

		return $arr;
	}
	
	function get_ajax_clients()
	{
		$query = $this->db->get_where('crm_company', array('name' => addslashes($this->input->post('name'))));
		$arr_comp = $query->row_array();
		
		if(count($arr_comp) != 0)
		{
			$query = $this->db->get_where('crm_clients', array('id_company' => $arr_comp['id']));
			return $query->result_array();
		}
	}
	
	function createDeal()
	{
		$nameDeal = $this->input->post('nameDeal');
		$clientDeal = $this->input->post('clientDeal');
		$budgetDeal = $this->input->post('budgetDeal');
		$currencyDeal = $this->input->post('currencyDeal');
		$statusDeal = $this->input->post('statusDeal');
		$descrDeal = $this->input->post('descrDeal');

		//Вытаскиваем контактное лицо и компанию
		$nameClient = '';
		$nameCompany = '';
		$temp = explode(',', $clientDeal);
		$nameClient = trim($temp['0']);
		if( isset($temp['1']) )
		{
			$nameCompany = trim($temp['1']);
		}

		//Загрузка данных профиля
		$profile = $this->ion_auth->profile();
		$profile = json_encode($profile, true);
		$profile = json_decode($profile, true);
		
		//Вносим новые события
		$data_event = Array(
			'author' => $profile['id'],
			'date' => date('Y-m-d H-i-s')
		);
		
		//Вносим событие в БД и узнаем ID
		$this->db->insert('crm_events', $data_event); 
		$idEvent = mysql_insert_id();
		
		//Обновляем список событий для контактного лица и компании если они введены
		if( !empty( $nameCompany ) )
		{
			//Если такая компания существует то обновляем
			$query = $this->db->get_where('crm_company', array('name' => addslashes($nameCompany)));
			$arrComp = $query->row_array();
			if( count( $arrComp ) != 0 )
			{
				$companyEvents = $arrComp['id_event'];
				$idComp = $arrComp['id'];
				$dataEventCompany = Array(
					'id_event' => $companyEvents . ',' . $idEvent
				);
				$this->db->where('name', $nameCompany);
				$this->db->update('crm_company', $dataEventCompany); 
				$newClient = FALSE;
			}
		}
		else
		{
			$newClient = FALSE;
			$idComp = 0;
		}
		
		$idClient = NULL;

		if( !empty( $nameClient ) )
		{
			//Если такое контактное лицо существует то обновляем
			$query = $this->db->get_where('crm_clients', array('fio' => $nameClient));
			$clientEvent = $query->row_array();
			if( count( $clientEvent ) != 0 )
			{
				$idClient = $clientEvent['id']; //Вытащием его ID
				$idComp = $clientEvent['id_company']; //Вытащим ID компании, в которой он работает или к которой привязан
				$clientEvents = $clientEvent['id_event']; //Вытащим ID's событий
				$dataEventClient = Array(
					'id_event' => $clientEvents . ',' . $idEvent //Добавим новое событие этому контактному лицу
				);
				$this->db->where('id', $idClient);
				$this->db->update('crm_clients', $dataEventClient); //И обновим его
			}
			else
			{
				//Создаем новое контактное лицо
				$newClient = $this->clients_md->createClient( $nameClient, $idComp, '', '', '', '' );
				
				$idClient = $newClient['id'];
				$idComp = $newClient['id_company'];

				//Выбираем события и обновляем их
				$query = $this->db->get_where('crm_clients', array('id' => $idClient));
				$clientEvent = $query->row_array();
				$clientEvents = $clientEvent['id_event'];
				$dataEventClient = Array(
					'id_event' => $clientEvents . ',' . $idEvent
				);
				$this->db->where('id', $nameClient);
				$this->db->update('crm_clients', $dataEventClient); 
			}
		}
		
		//Подготавливаем массив данных для вноса в таблицу сделок
		if( $idClient != NULL )
		{
			$subClientId = $idClient . ',';
		}
		else
		{
			$subClientId = NULL;
		}

		$data = Array(
			'name_deal' => $nameDeal,
			'author' => $profile['id'],
			'state_id' => $statusDeal,
			'sub_client' => $subClientId,
			'company_id' => $idComp,
			'events' => $idEvent,
			'budget' => $budgetDeal,
			'currency' => $currencyDeal,
			'description' => $descrDeal,
			'data_create' => date('Y-m-d'),
			'data' => date('Y-m-d')
		);
		
		$this->db->insert('crm_deals', $data);
		$idDeal = mysql_insert_id();

		$data['id'] = $idDeal;
		
		//Добавляем ID сделки в событие
		$descriptionEvent = 'Создана сделка ' . $this->input->post('ajax_name_deal');
		$dataEventUpdate = Array(
			'description' => $descriptionEvent,
			'id_deal' => $idDeal
		);
		
		$this->db->where('id', $idEvent);
		$this->db->update('crm_events', $dataEventUpdate); 
		return $data;
	}
	
	//Редактирование сделки
	function editDeal()
	{
		$titleDeal = $this->input->post('titleDeal');
		$idDeal = $this->input->post('idDeal');
		$descrDeal = $this->input->post('descrDeal');
		$budgetDeal = $this->input->post('budgetDeal');
		$currencyDeal = $this->input->post('currencyDeal');
		$nameCompany = $this->input->post('companyDeal');
		
		
		//Получаем ID создателя сделки
		$profile = $this->ion_auth->profile();
		$profile = json_encode($profile, true);
		$profile = json_decode($profile, true);
		
		//Если ID автора не совпадает с ID залогиненого то нихуя не обновляем
		$safetyQuery = $this->db->get_where('crm_deals', array('id' => $idDeal, 'author' => $profile['id']));
		if( count($safetyQuery->row_array()) > 0 )
		{
			//Обновляем список событий для контактного лица и компании если они введены
			if( !empty( $nameCompany ) )
			{
				//Если такая компания существует то вытаскиваем ее ID для обновления таблицы сделок
				$query = $this->db->get_where('crm_company', array('name' => addslashes($nameCompany)));
				$arrComp = $query->row_array();
				if( count( $arrComp ) != 0 )
				{
					$idComp = $arrComp['id'];

				}
				else
				{
					//Создаем новую компанию
					//$idComp = $this->clients_md->createCompany( $nameCompany, FALSE );
					//$new_client = TRUE;
					//$id_comp = 0;
				}
			}
			else
			{
				$new_client = TRUE;
				$idComp = 0;
			}
			

			//Подготавливаем массив данных для вноса в таблицу сделок

			$data = Array(
				'name_deal' => $titleDeal,
				'company_id' => $idComp,
				'budget' => $budgetDeal,
				'currency' => 2,//$currencyDeal,
				'description' => $descrDeal,
				'data' => date('Y-m-d')
			);
			
			$this->db->where('id', $idDeal);
			$this->db->update('crm_deals', $data);

			$data['nameCompany'] = $nameCompany;

			return $data;
		}
	}

	//Добавление нового контактного лица в сделку
	function addContactfaceDeal()
	{
		$idDeal = $this->input->post('idDeal');
		$contactfaceDeal = $this->input->post('contactfaceDeal');

		$temp = explode(', ', $contactfaceDeal);
		$nameClient = $temp['0'];
		if( isset($temp['1']))
		{
			$nameCompany = $temp['1'];
		}
		
		//Получаем ID автора
		$profile = $this->ion_auth->profile();
		$profile = json_encode($profile, true);
		$profile = json_decode($profile, true);

		//Ищем контакное лицо в базе
		$query = $this->db->get_where('crm_clients', array('fio' => $nameClient));
		$clientEvent = $query->row_array();

		//Если такой найден то выбираем его ID
		if( count($clientEvent) != 0 )
		{
			$clientId = $clientEvent['id'];
			$clientContact = $clientEvent['id_contact'];
			$companyId = $clientEvent['id_company'];
			$doljnost = $clientEvent['doljnost'];
		}
		elseif( !empty($nameClient) ) //Если нет то создаем новое контактное лицо
		{
			$client = $this->clients_md->createClient( $nameClient, 0 );
			$clientId = $client['idClient'];

			$clientContact = $client['id_contact'];
			$companyId = $client['id_company'];
			$doljnost = $client['doljnost'];
		}

		//Обновляем список субконтактных лиц в сделке
		$query = $this->db->get_where('crm_deals', array('id' => $idDeal));
		$deal = $query->row_array();

		$sub_contact = $deal['sub_client'] . $clientId . ',';

		$data = Array('sub_client' => $sub_contact, 'data' => date('Y-m-d'));

		$this->db->where('id', $idDeal);
		$this->db->update('crm_deals', $data); 


		$query = $this->db->get_where('crm_contacts', array('id' => $clientContact));
		$queryContact = $query->row_array();

		$arr = Array(
				'nameClient' => $nameClient, 
				'idClient' => $clientId, 
				'companyId' => $companyId, 
				'doljnost' => $doljnost, 
				'phone' => $queryContact['phone'],
				'email' => $queryContact['email'],
				'skype' => $queryContact['skype'],
			);

		return $arr;
	}

	//Быстрое редактирование статуса сделки
	function fastEditStatusDeal()
	{
		$idDeal = $this->input->post('idDeal');
		$idStatusDeal = $this->input->post('idStatusDeal');
		$newTextDeal = $this->input->post('newTextDeal');
		$color = $this->input->post('color');
		
		//Получаем ID автора события
		$profile = $this->ion_auth->profile();
		$profile = json_encode($profile, true);
		$profile = json_decode($profile, true);
		
		//Вносим новые события
		$description = '<p>Статус сделки изменен на <span class="status" style="display: inline; background-color: ' . $color . '">' . $newTextDeal . '</span></p>';
		$dataEvent = Array(
			'author' => $profile['id'],
			'date' => date('Y-m-d H-i-s'),
			'description' => $description,
			'id_deal' => $idDeal
		);
		//Вносим событие в БД и узнаем ID
		$this->db->insert('crm_events', $dataEvent); 
		$idEvent = mysql_insert_id();

		//Лезем в сделку и вытаскиваем ID контактного лица, компании и список событий
		//Обновляем события для сделки
		$query = $this->db->get_where('crm_deals', array('id' => $idDeal));
		$arr_e = $query->row_array();
		if( count( $arr_e ) != 0 )
		{
			$deal_events = $arr_e['events'];
			$events = $deal_events . ',' . $idEvent;
		}
		//$id_contact = $arr_e['client_id'];
		$idCompany = $arr_e['company_id'];
		$id_deal_events = $arr_e['events'];

		/* !!!!!!!!!!!!! Тут будет обновление для всех контактных лиц, прикрепленных к сделке
		//Обновим события для контактного лица если он есть
		/*if( !empty($id_contact) )
		{
			$query = $this->db->get_where('crm_clients', array('id' => $id_contact));
			$arr_contact = $query->row_array();
			$contact_events = $arr_contact['id_event'];
			$data_event_contact = Array(
				'id_event' => $contact_events . ',' . $id_event
			);
			$this->db->where('id', $id_contact);
			$this->db->update('crm_clients', $data_event_contact); 
		}*/

		//Обновим события для компании если она есть
		if( !empty($idCompany) )
		{
			$query = $this->db->get_where('crm_company', array('id' => $idCompany));
			$arr_company = $query->row_array();
			$company_events = $arr_company['id_event'];
			$dataEventCompany = Array(
				'id_event' => $company_events . ',' . $idEvent
			);
			$this->db->where('id', $idCompany);
			$this->db->update('crm_company', $dataEventCompany); 
		}

		//Подготавливаем массив данных для вноса в таблицу сделок
		$data = Array(
			'state_id' => $idStatusDeal,
			'events' => $events,
			'data' => date('Y-m-d')
		);
		
		$this->db->where('id', $idDeal);
		$this->db->update('crm_deals', $data); 

		$ret = Array(
			'author' => $profile['first_name'] . ' ' . $profile['last_name'],
			'data' => 'Сегодня в ' . date('H:i'),
			'description' => $description,
			'id_event' => $idEvent
			);

		return $ret;
	}

	//Добавление события со страницы сделки
	function addEventDeal()
	{
		$idDeal = $this->input->post('idDeal');
		$description = $this->input->post('description');
		
		//Получаем ID автора события
		$profile = $this->ion_auth->profile();
		$profile = json_encode($profile, true);
		$profile = json_decode($profile, true);
		
		//Вносим новые события
		$data_event = Array(
			'author' => $profile['id'],
			'date' => date('Y-m-d H-i-s'),
			'description' => $description,
			'id_deal' => $idDeal
		);
		
		//Вносим событие в БД и узнаем ID
		$this->db->insert('crm_events', $data_event); 
		$id_event = mysql_insert_id();
		
		//Лезем в сделку и вытаскиваем ID контактного лица, компании и список событий
		$query = $this->db->get_where('crm_deals', array('id' => $idDeal));
		$deal_array = $query->row_array();
		//$id_contact = $deal_array['client_id'];
		$id_company = $deal_array['company_id'];
		$id_deal_events = $deal_array['events'];

		//Обновим события для контактного лица если он есть
		/*if( !empty($id_contact) )
		{
			$query = $this->db->get_where('crm_clients', array('id' => $id_contact));
			$arr_contact = $query->row_array();
			$contact_events = $arr_contact['id_event'];
			$data_event_contact = Array(
				'id_event' => $contact_events . ',' . $id_event
			);
			$this->db->where('id', $id_contact);
			$this->db->update('crm_clients', $data_event_contact); 
		}*/

		//Обновим события для компании если она есть
		if( !empty($id_company) )
		{
			$query = $this->db->get_where('crm_company', array('id' => $id_company));
			$arr_company = $query->row_array();
			$company_events = $arr_company['id_event'];
			$data_event_company = Array(
				'id_event' => $company_events . ',' . $id_event
			);
			$this->db->where('id', $id_company);
			$this->db->update('crm_company', $data_event_company); 
		}
		
		//Подготавливаем массив данных для вноса в таблицу сделок
		$data = Array(
			'events' => $id_deal_events . ',' . $id_event,
			'data' => date('Y-m-d')
		);
		
		$this->db->where('id', $idDeal);
		$this->db->update('crm_deals', $data); 

		$ret = Array(
			'author' => $profile['first_name'] . ' ' . $profile['last_name'],
			'data' => date('H:i'),
			'description' => $description,
			'idEvent' => $id_event
			);

		return $ret;
	}

	//Получаем название сделки
	function get_deal_name( $id_deal = '' )
	{
		$query = $this->db->get_where('crm_deals', array('id' => $id_deal));
		$deal = $query->row_array();
		return $deal['name_deal'];
	}

	//Открепление контактного лица со сделки
	function disconnectFromDeal()
	{
		$idContact = $this->input->post('idContact');
		$idDeal = $this->input->post('idDeal');

		$query = $this->db->get_where('crm_deals', array('id' => $idDeal));
		$deal = $query->row_array();

		$temp = explode(',', $deal['sub_client']);
		$arr = Array();
		foreach( $temp as $list )
		{
			if( !empty($list) && $list != $idContact )
			{
				$arr[] = $list;
			}
		}
		$str = implode(',', $arr) . ',';
		if( $str == ',')
		{
			$str = NULL;
		}

		$data = Array('sub_client' => $str, 'data' => date('Y-m-d'));
		$this->db->where('id', $idDeal);
		$this->db->update('crm_deals', $data);

		return $idContact;
	}

	//Открепление основного контактного лица со сделки
	function offDefaultContact($id_deal)
	{
		$data = Array('client_id' => 0, 'data' => date('Y-m-d'));
		$this->db->where('id', $id_deal);
		$this->db->update('crm_deals', $data);
	}

	//Открепление основной компании со сделки
	function offDefaultCompany($id_deal)
	{
		$data = Array('company_id' => 0, 'data' => date('Y-m-d'));
		$this->db->where('id', $id_deal);
		$this->db->update('crm_deals', $data);
	}

	//Удаление сделки
	function delete_deal($id)
	{
		$this->db->where('id', $id);
		$this->db->delete('crm_deals'); 

		$this->db->where('id_deal', $id);
		$this->db->delete('crm_events'); 
	}

	//Выбираем сотрудников с задачи 
	function get_edit_users($id)
	{
		$query = $this->db->get_where('crm_tasks', array('id' => $id));
		$temp_task = $query->row_array();

		if( $temp_task['state'] != 0 )
		{
			$users = explode(',', $temp_task['user_id']);
			$l_users = Array();
			foreach( $users as $l_u )
			{
				$l_users[] = Array('name' => $this->clients_md->get_author_event($l_u), 'id_user' => $l_u);
			}
			$info = $temp_task['description'];

			$ret = Array('users' => $l_users, 'info' => $info, 'deadline' => $temp_task['deadline']);
			return $ret;
		}
	}

	//Функция редактирования события в сделке
	function saveEditResponse($id, $text)
	{
		$data = Array('description' => $text);

		$this->db->where('id', $id);
		$this->db->update('crm_events', $data);

		return Array('id' => $id, 'text' => $text);
	}

	//Функция поиска сделок
	function search( $search = '' )
	{
		$arr_deals = Array();
		$arr_faces = Array();
		$arr = Array();

		$this->db->like('name_deal', $search);
		$query = $this->db->get('crm_deals');
		$deal_res = $query->result_array();

		if( !empty($deal_res) )
		{	
			foreach($deal_res as $list_deal)
			{
				$res['name_deal'] = $list_deal['name_deal'];
				$res['id'] = $list_deal['id'];
				$res['budget'] = $list_deal['budget'];

				//Проверяем есть ли дополнительные контактные лица для сделки
				$res_temp = Array();
				if( $list_deal['sub_client'] != NULL )
				{
					$sub_client = substr($list_deal['sub_client'], 0, -1);
					//$this->db->get('crm_clients');
					$query = $this->db->query('SELECT*FROM crm_clients WHERE id in (' . $sub_client . ')');
					$res_temp = $query->result_array();
				}

				$res['sub_client'] = $res_temp;

				//Находим название конторы и его ID если они были введены при создании
				$res['company_name'] = '';
				$res['company_id'] = $list_deal['company_id'];
				if( $list_deal['company_id'] != 0 )
				{
					$query = $this->db->get_where('crm_company', array('id' => $list_deal['company_id']));
					$query_company = $query->row_array();
					$res['company_name'] = $query_company['name'];
					$res['company_id'] = $list_deal['company_id'];
				}

				//Узнаем цвет сделки и ее название
				$query = $this->db->get_where('state_deal', array('id' => $list_deal['state_id']));
				$query_color= $query->row_array();
				$res['color'] = $query_color['color'];
				$res['status_deal'] = $query_color['value'];

				//Вытаскиваем дату изменения из события
				$event_arr = explode(',', $list_deal['events']);
				$event_id = array_pop($event_arr);
				$query = $this->db->get_where('crm_events', array('id' => $event_id));
				$event = $query->row_array();
				$res['data_last'] = $event['date'];

				$arr[] = $res;
			}
		}


		/*$this->db->like('name', $search);
		$query = $this->db->get('crm_company');
		$company_res = $query->result_array();

		if( !empty($company_res) )
		{	
			foreach($company_res as $list_company)
			{
				//$name = $list_client['fio'];
				//$id = $list_client['id_company'];
				$c = $this->db->get_where('crm_clients', array('id_company' => $list_company['id']));
				$faces = $c->result_array();
				$res = $list_company;
				$res['fio'] = $faces;
				//$arr_company[] = $res;
				$arr[] = $res;
			}
		}

		$arr_res = Array('company' =>  $arr_company, 'faces' => $arr_faces);*/
		//return $arr_res;
		return $arr;
	}
}