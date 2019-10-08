<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Clients_md extends CI_Model {

	function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->library('ion_auth');
		$this->load->library('session');
	}
	
	//Получаем список клиентов
	function getClients($idProfile)
	{
		$this->db->order_by('id', 'desc');
		if( $idProfile != 0 )
		{
			//Вытаскиваем компании автора, а так же привязанные контактные лица к этим компаниям
			$queryCompany = $this->db->get_where('crm_company', array('id_author' => $idProfile));
			$listCompany = $queryCompany->result_array();

			//Вытаскиваем контактные лица автора, которые не привязаны к компаниям
			$queryClients = $this->db->get_where('crm_clients', array('id_author' => $idProfile));
			$listClients = $queryClients->result_array();
		}
		else
		{
			//Вытаскиваем компании, а так же привязанные контактные лица к этим компаниям
			$queryCompany = $this->db->get_where('crm_company');
			$listCompany = $queryCompany->result_array();

			//Вытаскиваем контактные лица, которые не привязаны к компаниям
			$queryClients = $this->db->get_where('crm_clients', array('id_company' => 0));
			$listClients = $queryClients->result_array();
		}

		//Временный массив для данных
		$temp = Array();

		//И массив для возврата данных
		$contacts = Array();

		//Начинаем с компаний, вытаскиваем привязанные контактные лица и вносим все во временный массив
		if( count($listCompany) > 0 )
		{
			foreach( $listCompany as $itemCompany )
			{
				//Вытаскиваем телефон, email и www для компании
				$query = $this->db->get_where('crm_contacts', array('id' => $itemCompany['id_contact']));
				$contactCompany = $query->row_array();

				$query = $this->db->get_where('crm_clients', array('id_company' => $itemCompany['id']));
				$companyClients = $query->result_array();

				//Создаем массив для контактных лиц, на предмет если их не будет 
				$contactFaces = Array();

				if( count($companyClients) > 0 )
				{
					foreach( $companyClients as $itemCompanyClients )
					{
						//Вытаскиваем телефон, email и www для контактного лица
						$query = $this->db->get_where('crm_contacts', array('id' => $itemCompanyClients['id_contact']));
						$contactCompanyClient = $query->row_array();

						$contactFaces[] = Array(
							'id_contact' => $itemCompanyClients['id'],
							'name_contact' => $itemCompanyClients['fio'],
							'adres' => $itemCompanyClients['adres'],
							'phone_contact' => $contactCompanyClient['phone'],
							'email_contact' => $contactCompanyClient['email'],
							'skype_contact' => $contactCompanyClient['skype']
						);
					}
				}

				$temp = Array(
					'id_author' => $itemCompany['id_author'],
					'id' => $itemCompany['id'],
					'contact' => $contactFaces,
					'company_id' => $itemCompany['id'],
					'company_name' => $itemCompany['name'],
					'company_contact' => $contactCompany,
					'company_adres' => $itemCompany['adres']
				);

				$contacts[] = $temp;
			}
		}

		//Ищем контактные лица без привязки к компании
		if( count($listClients) > 0 )
		{
			foreach( $listClients as $itemClients )
			{
				//Вытаскиваем телефон, email и www
				$query = $this->db->get_where('crm_contacts', array('id' => $itemClients['id_contact']));
				$contactFree = $query->row_array();

				$temp = Array(
					'company_name' => '',
					'id_contact' => $itemClients['id'],
					'name_contact' => $itemClients['fio'],
					'adres' => $itemClients['adres'],
					'phone_contact' => $contactFree['phone'],
					'email_contact' => $contactFree['email'],
					'skype_contact' => $contactFree['skype']
				);

				$contacts[] = $temp;
			}
		}

		return $contacts;
	}
	
	//Ищем своих клиентов 
	function get_my_clients($id)
	{
		$this->db->order_by('id', 'desc');
		$query = $this->db->get_where('crm_clients', array('id_author' => $id));
		$list = $query->result_array();
		
		//Если массив не пустой то идем дальше
		if( count( $list ) != 0 )
		{
			foreach( $list as $item )
			{
				//Вытаскиваем имя и ID компании
				if( $item['id_company'] != 0 )
				{
					$query = $this->db->get_where('crm_company', array('id' => $item['id_company']));
					$company = $query->row_array();
					$c_id = $company['id'];
					$c_n = $company['name'];
				}
				else
				{
					$c_id = '';
					$c_n = '';
				}
				
				//Вытаскиваем телефон и email
				$query = $this->db->get_where('crm_contacts', array('id' => $item['id_contact']));
				$contact = $query->row_array();

				$temp = Array(
					'id' => $item['id'],
					'fio' => $item['fio'],
					'company_id' => $c_id,
					'company_name' => $c_n,
					'phone' => $contact['phone'],
					'email' => $contact['email']
				);
				$client[] = $temp;
			}
			
			return $client;
		}
	}
	
	
	//Получаем данные клиента
	function get_client($id_client)
	{
		$query = $this->db->get_where('crm_clients', array('id' => $id_client));
		$list = $query->row_array();
		
		//Вытаскиваем создателя клиента
		$query = $this->db->get_where('meta', array('user_id' => $list['id_author']));
		$author = $query->row_array();
		
		//Вытаскиваем имя и ID компании
		if( $list['id_company'] != 0 )
		{
			$query = $this->db->get_where('crm_company', array('id' => $list['id_company']));
			$company_arr = $query->row_array();
			$company_name = $company_arr['name'];
			$company_id = $company_arr['id'];
		}
		else
		{
			$company_name = '';
			$company_id = '';
		}
		
		//Вытаскиваем телефон и email
		$query = $this->db->get_where('crm_contacts', array('id' => $list['id_contact']));
		$contact = $query->row_array();
		
		//Вытаскиваем список сделок по контактному лицу
		$query = $this->db->query('SELECT*FROM crm_deals WHERE sub_client RLIKE "[[:<:]]' . $id_client .'[[:>:]]"');
		//$query = $this->db->get_where('crm_deals', array('client_id' => $id_client));
		$list_deals = $query->result_array();
		if( !empty($list_deals) )
		{
			$inStr = '';
			foreach( $list_deals as $ev_deals)
			{
				$inStr .= $ev_deals['id'] . ',';
			}
			$where = substr($inStr, 0, strlen($inStr) - 1);
			
			$query = $this->db->query('SELECT*FROM crm_events WHERE id_deal in(' . $where .') ORDER BY date DESC');

			$r = $query->result_array();
			foreach( $r as $evArr )
			{
				$author_e = $this->clients_md->get_author_event($evArr['author']);

				$sub_query = $this->db->get_where('crm_events', array('sub_id' => $evArr['id']));
				$sub_res = $sub_query->result_array();
				if( !empty($sub_res) )
				{
					$sub = Array();
					foreach( $sub_res as $s )
					{
						$sub_author = $this->clients_md->get_author_event($s['author']);
						$sub[] = Array(
							'author' => $sub_author,
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

				$event[] = Array(
					'author' => $author_e,
					'author_id' => $evArr['author'],
					'description' => $evArr['description'],
					'id_event' => $evArr['id'],
					'date' => $evArr['date'],
					'id_deal' => $evArr['id_deal'],
					'sub_event' => $sub
					);
			}
		}
		//Вытаскиваем события
		$event_arr = explode(',', $list['id_event']);
		foreach( $event_arr as $list_event )
		{
			if( is_numeric($list_event) )
			{
				
				$query = $this->db->get_where('crm_events', array('id' => $list_event));
				$temp_ev = $query->row_array();
				
				$author_e = $this->clients_md->get_author_event($temp_ev['author']);

				$sub_query = $this->db->get_where('crm_events', array('sub_id' => $temp_ev['id']));
				$sub_res = $sub_query->result_array();
				if( !empty($sub_res) )
				{
					$sub = Array();
					foreach( $sub_res as $s )
					{
						$sub_author = $this->clients_md->get_author_event($s['author']);
						$sub[] = Array(
							'author' => $sub_author,
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

				$event[] = Array(
					'author' => $author_e,
					'author_id' => $temp_ev['author'],
					'description' => $temp_ev['description'],
					'id_event' => $temp_ev['id'],
					'date' => $temp_ev['date'],
					'id_deal' => $temp_ev['id_deal'],
					'sub_event' => $sub
					);
			}
		}
		
		$new = Array();

		foreach( $event as $rev )
		{
			$new[$rev['id_event']] = Array(
					'author' => $rev['author'],
					'author_id' => $rev['author_id'],
					'description' => $rev['description'],
					'id_event' => $rev['id_event'],
					'date' => $rev['date'],
					'id_deal' => $rev['id_deal'],
					'sub_event' => $rev['sub_event']
					);
		}

		$client = Array(
			'id' => $list['id'],
			'fio' => $list['fio'],
			'doljnost' => $list['doljnost'],
			'adres' => $list['adres'],
			'author' => $author['first_name'] . ' ' . $author['last_name'],
			'author_id' => $list['id_author'],
			'company_id' => $company_id,
			'company_name' => $company_name,
			'phone' => $contact['phone'],
			'email' => $contact['email'],
			'skype' => $contact['skype'],
			'www' => $contact['www'],
			'event' => $new,
			'deals' => $list_deals
		);
		
		return $client;
	}
	
	function get_author_event($idUser)
	{
		$query = $this->db->get_where('meta', array('user_id' => $idUser));
		$fio = $query->row_array();

		$query = $this->db->get_where('users', array('id' => $idUser));
		$info = $query->row_array();

		$query = $this->db->get_where('groups', array('id' => $info['group_id']));
		$doljnost = $query->row_array();

		$return = Array(
			'name' => $fio['first_name'] . ' ' . $fio['last_name'],
			'doljnost' => $doljnost['description'],
			'avatar' => $info['avatar'],
			'group' => $info['group_id'],
			'id' => $idUser
		);
		return $return;
	}
	
	//Получаем данные компании
	function get_company($id_company)
	{
		$query = $this->db->get_where('crm_company', array('id' => $id_company));
		$list = $query->row_array();
		
		//Вытаскиваем создателя компании
		$query = $this->db->get_where('meta', array('user_id' => $list['id_author']));
		$author = $query->row_array();
		
		//Вытаскиваем телефон и email
		$query = $this->db->get_where('crm_contacts', array('id' => $list['id_contact']));
		$contact = $query->row_array();
		
		//Вытаскиваем связанные контакты
		$this->db->order_by('id', 'desc');
		$query = $this->db->get_where('crm_clients', array('id_company' => $id_company));
		$clients_company = $query->result_array();

		//Вытаскиваем сделки
		$comp_deals = Array();
		$query = $this->db->get_where('crm_deals', array('company_id' => $id_company));
		$res = $query->result_array();
		if( !empty($res) )
		{
			foreach( $res as $deal_res )
			{
				$comp_deals[] = Array('id' => $deal_res['id'], 'name_deal' => $deal_res['name_deal']);
			}
		}
		
		//Вытаскиваем события
		$event_arr = explode(',', $list['id_event']);
		foreach( $event_arr as $list_event )
		{
			if( is_numeric($list_event) )
			{
				$query = $this->db->get_where('crm_events', array('id' => $list_event));
				$temp_ev = $query->row_array();

				$author_e = $this->clients_md->get_author_event($temp_ev['author']);

				$sub_query = $this->db->get_where('crm_events', array('sub_id' => $temp_ev['id']));
				$sub_res = $sub_query->result_array();
				if( !empty($sub_res) )
				{
					$sub = Array();
					foreach( $sub_res as $s )
					{
						$sub_author = $this->clients_md->get_author_event($s['author']);
						$sub[] = Array(
							'author' => $sub_author,
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

				$event[] = Array(
					'author' => $author_e,
					'author_id' => $temp_ev['author'],
					'description' => $temp_ev['description'],
					'id_event' => $temp_ev['id'],
					'date' => $temp_ev['date'],
					'id_deal' => $temp_ev['id_deal'],
					'sub_event' => $sub
					);
			}
		}
		
		$company = Array(
			'id' => $list['id'],
			'name' => $list['name'],
			'description' => $list['description'],
			'adres' => $list['adres'],
			'author' => $author['first_name'] . ' ' . $author['last_name'],
			'author_id' => $list['id_author'],
			'phone' => $contact['phone'],
			'email' => $contact['email'],
			'skype' => $contact['skype'],
			'www' => $contact['www'],
			'clients_company' => $clients_company,
			'event' => array_reverse($event),
			'deals' => $comp_deals
		);
		
		return $company;
	}
	
	//Получаем все компании
	function get_all_company()
	{
		$query = $this->db->get('crm_company');
		return $query->result_array();
	}

	//Получаем все контактные лица
	function get_all_clients()
	{
		$query = $this->db->get('crm_clients');
		return $query->result_array();
	}

	//Получаем все контактные лица
	function get_all_clients1()
	{
		$query = $this->db->get('crm_clients');
		$res = $query->result_array();
		$temp = Array();
		foreach( $res as $list )
		{
			$query = $this->db->get_where('crm_company', array('id' => $list['id_company']));
			$res2 = $query->row_array();
			if( !empty($res2) )
			{
				$temp[] = $list['fio'] . ', ' . $res2['name'];
			}
			else
			{
				$temp[] = $list['fio'] . ', Физ.Лицо';
			}
			
		}
		return $temp;
	}
	
	//Вносим нового клиента в БД
	function createCompany()
	{
		//Получаем ID создателя компании
		$profile = $this->ion_auth->profile();
		$profile = json_encode($profile, true);
		$profile = json_decode($profile, true);

		//переменные компании
		$nameCompany = $this->input->post('nameCompany');
		$emailCompany = $this->input->post('emailCompany');
		$phoneCompany = $this->input->post('phoneCompany');
		$skypeCompany = $this->input->post('skypeCompany');
		$wwwCompany = $this->input->post('wwwCompany');
		$adresCompany = $this->input->post('adresCompany');

		//переменные контактного лица
		$nameClient = $this->input->post('nameClient');
		$doljnostClient = $this->input->post('doljnostClient');
		$adresClient = $this->input->post('adresClient');
		$skypeClient = $this->input->post('skypeClient');
		$phoneClient = $this->input->post('phoneClient');
		$emailClient = $this->input->post('emailClient');

		//Создаем контакты компании
		if( !empty($nameCompany) )
		{
			$data_contact = Array(
				'email' => $emailCompany,
				'phone' => $phoneCompany,
				'skype' => $skypeCompany,
				'www' => $wwwCompany
			);
			
			//Вносим контакты компании в БД и узнаем ID
			$this->db->insert('crm_contacts', $data_contact); 
			$idContact = mysql_insert_id();
			
			//Создаем событие создания новой компании
			$data_event = Array(
				'author' => $profile['id'],
				'date' => date('Y-m-d H-i-s'),
				'description' => '<p>Создана компания ' . $nameCompany . '</p>'
			);
			
			//Вносим событие создания компании в БД и узнаем ID
			$this->db->insert('crm_events', $data_event); 
			$idEvent = mysql_insert_id();

			$dataCompany = Array(
				'name' => addslashes($nameCompany),
				'id_contact' => $idContact,
				'adres' => $adresCompany,
				'id_event' => $idEvent,
				'id_attach' => '',
				'id_author' => $profile['id']
			);
			
			//Вносим контакты компании в БД и узнаем ID
			$this->db->insert('crm_company', $dataCompany);
			$idCompany = mysql_insert_id();
		}
		else
		{
			$idCompany = 0;
		}

		if( !empty($nameClient) )
		{
			//Создаем контакты
			$dataContact = Array(
				'email' => $emailClient,
				'phone' => $phoneClient,
				'skype' => $skypeClient
			);
			
			//Вносим контакты клиента в БД и узнаем ID
			$this->db->insert('crm_contacts', $dataContact); 
			$idContact = mysql_insert_id();
			
			//Создаем событие создания клиента
			$data_event = Array(
				'author' => $profile['id'],
				'date' => date('Y-m-d H-i-s'),
				'description' => '<p>Создан клиент ' . $nameClient . '</p>'
			);
			
			//Вносим событие создания клиента в БД и узнаем ID
			$this->db->insert('crm_events', $data_event); 
			$idEvent = mysql_insert_id();

			$dataClient = Array(
				'fio' => $nameClient,
				'id_contact' => $idContact,
				'adres' => $adresClient,
				'doljnost' => $doljnostClient,
				'id_event' => $idEvent,
				'id_attach' => '',
				'id_author' => $profile['id'],
				'id_company' => $idCompany
			);
			
			//Вносим контакты клиента в БД и узнаем ID
			$this->db->insert('crm_clients', $dataClient); 
		}
	}
	
	//Вносим нового клиента в БД
	function createClient( $nameClient, $idCompany )
	{
		
		//Получаем ID создателя контактного лица
		$profile = $this->ion_auth->profile();
		$profile = json_encode($profile, true);
		$profile = json_decode($profile, true);
		
		$doljnost = $this->input->post('doljnostClient');
		$phone = $this->input->post('phoneClient');
		$skype = $this->input->post('skypeClient');
		$email = $this->input->post('emailClient');
		$adres = $this->input->post('adresClient');
		
		//Создаем контакты
		$dataContact = Array(
			'email' => $email,
			'phone' => $phone,
			'skype' => $skype
		);
		
		//Вносим контакты клиента в БД и узнаем ID
		$this->db->insert('crm_contacts', $dataContact); 
		$idContact = mysql_insert_id();
		
		//Создаем событие создания клиента
		$dataEvent = Array(
			'author' => $profile['id'],
			'date' => date('Y-m-d H-i-s'),
			'description' => '<p>Создан клиент ' . $nameClient . '</p>'
		);
		
		//Вносим событие создания клиента в БД и узнаем ID
		$this->db->insert('crm_events', $dataEvent); 
		$idEvent = mysql_insert_id();

		$dataClient = Array(
				'fio' => $nameClient,
				'id_contact' => $idContact,
				'adres' => $adres,
				'doljnost' => $doljnost,
				'id_event' => $idEvent,
				'id_attach' => '',
				'id_author' => $profile['id'],
				'id_company' => $idCompany
			);
			
		//Вносим контакты клиента в БД и узнаем ID
		$this->db->insert('crm_clients', $dataClient); 
		$dataClient['idClient'] = mysql_insert_id();

		return $dataClient;
	}
	
	//Обновление данных компании
	function editCompany()
	{
		$idCompany = $this->input->post('id');
		$name = $this->input->post('name');
		$phone = $this->input->post('phone');
		$skype = $this->input->post('skype');
		$email = $this->input->post('email');
		$www = $this->input->post('www');
		$adres = $this->input->post('adres');

		//Загрузка данных профиля
		$profile = $this->ion_auth->profile();
		$profile = json_encode($profile, true);
		$profile = json_decode($profile, true);
		
		//Обновляем контакты
		$query = $this->db->get_where('crm_company', array('id' => $idCompany));
		$contactArr = $query->row_array();

		if( $contactArr['id_author'] == $profile['id'] )
		{
			$data_contact = Array(
				'email' => $email, 
				'phone' => $phone, 
				'skype' => $skype, 
				'www' => $www
				);
				
			$this->db->where('id', $contactArr['id_contact']);
			$this->db->update('crm_contacts', $data_contact); 
			
			$data = Array(
				'name' => addslashes($name),
				'adres' => $adres
			);
			
			//Заносим обновления в БД
			$this->db->where('id', $idCompany);
			$this->db->update('crm_company', $data);

			$return = array_merge($data, $data_contact);

			return $return;
		}
	}
	
	//Обновление данных контактного лица
	function editClient()
	{
		$idClient = $this->input->post('id_client');
		$name = $this->input->post('name_client');
		$company = $this->input->post('client_edit_company');
		$adres = $this->input->post('adres_edit_client');
		$doljnost = $this->input->post('edit_doljnost');
		$phone = $this->input->post('phone_edit_client');
		$skype = $this->input->post('skype_edit_client');
		$email = $this->input->post('email_edit_client');
		
		//Получаем ID обновлятора
		$profile = $this->ion_auth->profile();
		$profile = json_encode($profile, true);
		$profile = json_decode($profile, true);

		//Узнаем ID таблицы контактов и ID создателя
		$query = $this->db->get_where('crm_clients', array('id' => $idClient));
		$contArr = $query->row_array();

		//Если ID автора совпадает с ID пользователя то обновляем
		if( $contArr['id_author'] == $profile['id'] )
		{
			//Обновляем контакты
			$data_contact = Array(
				'email' => $email, 
				'phone' => $phone, 
				'skype' => $skype
			);

			$this->db->where('id', $contArr['id_contact']);
			$this->db->update('crm_contacts', $data_contact); 
			
			//Узнаем ID новой компании если она введена
			$nameCompany = trim(addslashes($company));
			$idCompany = FALSE;

			if( !empty($nameCompany) )
			{
				$query = $this->db->get_where('crm_company', array('name' => $nameCompany));
				$companyArr = $query->row_array();
				//Если такой компании нету то добавляем новую
				if( count( $companyArr ) == 0 )
				{
					$idCompany = $this->clients_md->create_company($nameCompany, FALSE);

					$data = Array(
						'fio' => $name,
						'adres' => $adres,
						'doljnost' => $doljnost,
						'id_company' => $idCompany
					);
				}
				else //Иначе обновляем
				{
					$idCompany = $companyArr['id'];
					$data = Array(
						'fio' => $name,
						'adres' => $adres,
						'doljnost' => $doljnost,
						'id_company' => $idCompany
					);
				}
			}
			else
			{
				$data = Array(
					'fio' => $name,
					'adres' => $adres,
					'doljnost' => $doljnost
				);
			}
			
			//Заносим обновления в БД
			$this->db->where('id', $idClient);
			$this->db->update('crm_clients', $data); 
			
			$return = Array(
				'email' => $email, 
				'phone' => $phone,
				'skype' => $skype,
				'fio' => $name,
				'adres' => $adres,
				'doljnost' => $doljnost,
				'nameCompany' => $nameCompany,
				'idCompany' => $idCompany
			);

			return $return;
		}
	}

	//Добавление события со страницы компании
	function add_event_company()
	{
		$id_company = $this->input->post('id');
		$description = $this->input->post('description');
		
		//Получаем ID автора события
		$author_array_json = $this->ion_auth->profile();

		$author1 = json_encode($author_array_json, true);
		$author = json_decode($author1, true);
		
		//Вносим новые события
		$time = date('Y-m-d H:i:s');
		$data_event = Array(
			'author' => $author['id'],
			'date' => $time,
			'description' => $description
		);
		
		//Вносим событие в БД и узнаем ID
		$this->db->insert('crm_events', $data_event); 
		$id_event = mysql_insert_id();
		
		$query = $this->db->get_where('crm_company', array('id' => $id_company));
		$arr_company = $query->row_array();
		$company_events = $arr_company['id_event'];
		$data_event_company = Array(
			'id_event' => $company_events . ',' . $id_event
		);
		$this->db->where('id', $id_company);
		$this->db->update('crm_company', $data_event_company);

		$result = Array(
			'author' => $author['first_name'] . ' ' . $author['last_name'],
			'data' => $time,
			'description' => $description,
			'id_event' => $id_event
			);

		return $result;
	}

	//Добавление события со страницы контактного лица
	function add_event_client()
	{
		$id_client = $this->input->post('id');
		$description = $this->input->post('description');
		
		//Получаем ID автора события
		$author_array_json = $this->ion_auth->profile();

		$author1 = json_encode($author_array_json, true);
		$author = json_decode($author1, true);
		
		//Вносим новые события
		$time = date('Y-m-d H:i:s');
		$data_event = Array(
			'author' => $author['id'],
			'date' => $time,
			'description' => $description
		);
		
		//Вносим событие в БД и узнаем ID
		$this->db->insert('crm_events', $data_event); 
		$id_event = mysql_insert_id();
		
		$query = $this->db->get_where('crm_clients', array('id' => $id_client));
		$arr_client = $query->row_array();
		$client_events = $arr_client['id_event'];
		$data_event_client = Array(
			'id_event' => $client_events . ',' . $id_event
		);
		$this->db->where('id', $id_client);
		$this->db->update('crm_clients', $data_event_client); 

		$result = Array(
			'author' => $author['first_name'] . ' ' . $author['last_name'],
			'data' => $time,
			'description' => $description
			);

		return $result;
	}

	//Получаем количество компаний для информации профиля
	function get_profile_company( $id = '')
	{
		$query = $this->db->get_where('crm_company', array('id_author' => $id));
		return $query->result_array();
	}

	//Получаем количество контактных лиц для информации профиля
	function get_profile_clients( $id = '')
	{
		$query = $this->db->get_where('crm_clients', array('id_author' => $id));
		return $query->result_array();
	}

	//Функция поиска клиентов
	function search( $search = '' )
	{
		$arr_company = Array();
		$arr_faces = Array();
		$arr = Array();

		$this->db->like('fio', $search);
		$query = $this->db->get('crm_clients');
		$client_res = $query->result_array();

		if( !empty($client_res) )
		{	
			foreach($client_res as $list_client)
			{
				$name = $list_client['fio'];
				$id = $list_client['id_company'];
				$c = $this->db->get_where('crm_company', array('id' => $id));
				$res = $c->row_array();
				$res['fio'] = $list_client['fio'];
				$res['id_face'] = $list_client['id'];
				//$arr_faces[] = $res;
				$arr[] = $res;
			}
		}


		$this->db->like('name', $search);
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

		$arr_res = Array('company' =>  $arr_company, 'faces' => $arr_faces);
		//return $arr_res;
		return $arr;
	}

	//Открепление контакта от компании
	function DelFromCompany()
	{
		$id = $this->input->post('id_contact');

		$data = Array(
			'id_company' => 0
		);
		$this->db->where('id', $id);
		$this->db->update('crm_clients', $data); 

		return $id;
	}

	//Удаление компании
	function delete_company($id)
	{
		$query = $this->db->get_where('crm_company', array('id' => $id));
		$res = $query->row_array();
		$id_contact = $res['id_contact'];
		$this->db->where('id', $id_contact);
		$this->db->delete('crm_contacts');

		$this->db->where('id', $id);
		$this->db->delete('crm_company');

		$data = Array('id_company' => 0);
		$this->db->where('id_company', $id);
		$this->db->update('crm_clients', $data);

		$data = Array('company_id' => 0);
		$this->db->where('company_id', $id);
		$this->db->update('crm_deals', $data);
	}

	//Удаление контакта
	function delete_contact($id)
	{
		$query = $this->db->get_where('crm_clients', array('id' => $id));
		$res = $query->row_array();
		$id_contact = $res['id_contact'];
		$this->db->where('id', $id_contact);
		$this->db->delete('crm_contacts');

		$this->db->where('id', $id);
		$this->db->delete('crm_clients');

		$data = Array('client_id' => 0);
		$this->db->where('client_id', $id);
		$this->db->update('crm_deals', $data);

		$query = $this->db->get('crm_deals');
		$arr = $query->result_array();

		foreach( $arr as $list )
		{
			$id_deal = $list['id'];
			$temp = explode(',', $list['sub_client']);
			if( in_array($id, $temp) )
			{
				$sub = Array();
				foreach( $temp as $sub_list )
				{
					if( $sub_list != $id AND !empty($sub_list) )
					{
						$sub[] = $sub_list;
					}
				}
				$in = implode(',', $sub) . ',';

				$data = Array('sub_client' => $in);
				$this->db->where('id', $id_deal);
				$this->db->update('crm_deals', $data);
			}
		}
	}
}