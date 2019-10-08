<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Clients_md extends CI_Model {

	function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->library('ion_auth');
		$this->load->library('session');
	}
	
	//Получаем весь список клиентов
	function get_clients($id = '0')
	{
		$this->db->order_by('id', 'desc');
		if( $id == 0 )
		{
			$query = $this->db->get('crm_clients');
		}
		else
		{
			$query = $this->db->get_where('crm_clients', array('id_author' => $id));
		}
		$list = $query->result_array();

		$arr_id = Array();
		//Если массив не пустой то идем дальше
		if( count( $list ) != 0 )
		{
			foreach( $list as $item )
			{
				//Вытаскиваем название компаний, к которым относятся контактные лица
				$query = $this->db->get_where('crm_company', array('id' => $item['id_company']));
				$company = $query->row_array();
				if( count($company) != 0 )
				{
					$name_c = $company['name'];
					$id_c = $company['id'];
					$arr_id[] = $company['id'];
				}
				else
				{
					$name_c = '';
					$id_c = 0;
				}
				
				//Вытаскиваем телефон, email и www
				$query = $this->db->get_where('crm_contacts', array('id' => $item['id_contact']));
				$contact = $query->row_array();
				
				

				$temp = Array(
					'id_author' => $item['id_author'],
					'id' => $item['id'],
					'contact' => $item['fio'],
					'company_id' => $id_c,
					'company_name' => $name_c,
					'phone' => $contact['phone'],
					'email' => $contact['email'],
					'adres' => $item['adres']
				);
				$contacts[] = $temp;
			}
		}
		$where = '';
		foreach( $arr_id as $wh )
		{
			$where .= 'id != '. $wh. ' AND ';
		}
		$where = substr($where, 0, -5);
		
		$this->db->where($where);
		$query = $this->db->get('crm_company');
		$res = $query->result_array();
		
		foreach( $res as $item )
		{
			//Вытаскиваем телефон, email и www
			$query = $this->db->get_where('crm_contacts', array('id' => $item['id_contact']));
			$contact = $query->row_array();

			$temp = Array(
				'id_author' => $item['id_author'],
				'id' => 0,
				'contact' => '',
				'company_id' => $item['id'],
				'company_name' => $item['name'],
				'phone' => $contact['phone'],
				'email' => $contact['email'],
				'adres' => $item['adres']
			);
			$contacts[] = $temp;
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
		$query = $this->db->get_where('crm_deals', array('client_id' => $id_client));
		$list_deals = $query->result_array();

		//Вытаскиваем события
		$event_arr = explode(',', $list['id_event']);
		foreach( $event_arr as $list_event )
		{
			if( is_numeric($list_event) )
			{
				$query = $this->db->get_where('crm_events', array('id' => $list_event));
				$event[] = $query->row_array();
			}
		}
		
		$client = Array(
			'id' => $list['id'],
			'fio' => $list['fio'],
			'doljnost' => $list['doljnost'],
			'adres' => $list['adres'],
			'author' => $author['first_name'] . ' ' . $author['last_name'],
			'company_id' => $company_id,
			'company_name' => $company_name,
			'phone' => $contact['phone'],
			'email' => $contact['email'],
			'skype' => $contact['skype'],
			'www' => $contact['www'],
			'event' => array_reverse($event),
			'deals' => $list_deals
		);
		
		return $client;
	}
	
	function get_author_event($id)
	{
		$query = $this->db->get_where('meta', array('user_id' => $id));
		$author = $query->row_array();
		$query = $this->db->get_where('users', array('id' => $id));
		$avatar = $query->row_array();

		$arr = Array('author' => $author['first_name'] . ' ' . $author['last_name'], 'avatar' => $avatar['avatar']);
		return $arr;
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
		//Вытаскиваем сделки по связанным контактам
		$comp_deals = Array();
		if( !empty( $clients_company ) )
		{
			foreach( $clients_company as $get_deals )
			{
				$query = $this->db->get_where('crm_deals', array('client_id' => $get_deals['id']));
				$res = $query->result_array();
				if( !empty($res) )
				{
					foreach( $res as $deal_res )
					{
						$comp_deals[] = Array('id' => $deal_res['id'], 'name_deal' => $deal_res['name_deal']);
					}
				}
			}
		}
		
		//Вытаскиваем события
		$event_arr = explode(',', $list['id_event']);
		foreach( $event_arr as $list_event )
		{
			if( is_numeric($list_event) )
			{
				$query = $this->db->get_where('crm_events', array('id' => $list_event));
				$event[] = $query->row_array();
			}
		}
		
		$company = Array(
			'id' => $list['id'],
			'name' => $list['name'],
			'description' => $list['description'],
			'adres' => $list['adres'],
			'author' => $author['first_name'] . ' ' . $author['last_name'],
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
	function create_company( $name_company = '', $new_client = TRUE )
	{
		//Получаем ID создателя компании
		$author_array_json = $this->ion_auth->profile();
		$author1 = json_encode($author_array_json, true);
		$author = json_decode($author1, true);

		//Если аргумент пустой то присваеваем название с формы
		if( $name_company == '' )
		{
			$name_company = $this->input->post('name_company');
		}

		//Создаем контакты компании
		if( !empty($name_company) )
		{
			$data_contact = Array(
				'email' => $this->input->post('email_company'),
				'phone' => $this->input->post('phone_company'),
				'skype' => $this->input->post('skype_company'),
				'www' => $this->input->post('www_company')
			);
			
			//Вносим контакты компании в БД и узнаем ID
			$this->db->insert('crm_contacts', $data_contact); 
			$id_contact = mysql_insert_id();
			
			//Создаем событие создания новой компании
			$data_event = Array(
				'author' => $author['id'],
				'date' => date('Y-m-d H-i-s'),
				'description' => 'Создана компания ' . $name_company
			);
			
			//Вносим событие создания компании в БД и узнаем ID
			$this->db->insert('crm_events', $data_event); 
			$id_event = mysql_insert_id();

			$data_company = Array(
				'name' => addslashes($name_company),
				'id_contact' => $id_contact,
				'adres' => $this->input->post('adres_company'),
				'id_event' => $id_event,
				'id_attach' => '',
				'id_author' => $author['id']
			);
			
			//Вносим контакты компании в БД и узнаем ID
			$this->db->insert('crm_company', $data_company);
			$id_company = mysql_insert_id();
		}
		else
		{
			$id_company = 0;
		}
		
		if( $new_client == TRUE )
		{
			$name_client = $this->input->post('name_client');

			if( !empty($name_client) )
			{
				//Создаем контакты
				$data_contact = Array(
					'email' => $this->input->post('email_client'),
					'phone' => $this->input->post('phone_client'),
					'skype' => $this->input->post('skype_client')
				);
				
				//Вносим контакты клиента в БД и узнаем ID
				$this->db->insert('crm_contacts', $data_contact); 
				$id_contact = mysql_insert_id();
				
				//Создаем событие создания клиента
				$data_event = Array(
					'author' => $author['id'],
					'date' => date('Y-m-d H-i-s'),
					'description' => 'Создан клиент ' . $name_client
				);
				
				//Вносим событие создания клиента в БД и узнаем ID
				$this->db->insert('crm_events', $data_event); 
				$id_event = mysql_insert_id();

				$data_client = Array(
					'fio' => $name_client,
					'id_contact' => $id_contact,
					'adres' => $this->input->post('adres_client'),
					'doljnost' => $this->input->post('doljnost_client'),
					'id_event' => $id_event,
					'id_attach' => '',
					'id_author' => $author['id'],
					'id_company' => $id_company
				);
				
				//Вносим контакты клиента в БД и узнаем ID
				$this->db->insert('crm_clients', $data_client); 
			}
		}
		else
		{
			return $id_company;
		}
	}
	
	//Вносим нового клиента в БД
	function create_client( $name_client = '', $id_company = '', $doljnost = '', $phone = '', $skype = '', $email = '' )
	{
		if( $name_client == '' )
		{
			$name_client = $this->input->post('name_client');
		}
		if( $id_company == '' )
		{
			$id_company = $this->input->post('company_client');
		}
		if( $doljnost == '' )
		{
			$doljnost = $this->input->post('doljnost_client');
		}
		if( $phone == '' )
		{
			$phone = $this->input->post('phone_client');
		}
		if( $skype == '' )
		{
			$skype = $this->input->post('skype_client');
		}
		if( $email == '' )
		{
			$email = $this->input->post('email_client');
		}
		
		//Получаем ID создателя контактного лица
		$author_array_json = $this->ion_auth->profile();
		$author1 = json_encode($author_array_json, true);
		$author = json_decode($author1, true);
		
		//Создаем контакты
		$data_contact = Array(
			'email' => $email,
			'phone' => $phone,
			'skype' => $skype
		);
		
		//Вносим контакты клиента в БД и узнаем ID
		$this->db->insert('crm_contacts', $data_contact); 
		$id_contact = mysql_insert_id();
		
		//Создаем событие создания клиента
		$data_event = Array(
			'author' => $author['id'],
			'date' => date('Y-m-d H-i-s'),
			'description' => 'Создан клиент ' . $name_client
		);
		
		//Вносим событие создания клиента в БД и узнаем ID
		$this->db->insert('crm_events', $data_event); 
		$id_event = mysql_insert_id();

		$data_client = Array(
				'fio' => $name_client,
				'id_contact' => $id_contact,
				'adres' => $this->input->post('adres_client'),
				'doljnost' => $doljnost,
				'id_event' => $id_event,
				'id_attach' => '',
				'id_author' => $author['id'],
				'id_company' => $id_company
			);
			
		//Вносим контакты клиента в БД и узнаем ID
		$this->db->insert('crm_clients', $data_client); 
		$new_id = mysql_insert_id();

		$ret = Array('name' => $name_client, 'id' => $new_id);
		return $ret;
	}
	
	//Обновление данных компании
	function edit_company()
	{
		//Получаем ID обновлятора
		$author_array_json = $this->ion_auth->profile();

		$author1 = json_encode($author_array_json, true);
		$author = json_decode($author1, true);
		
		//Обновляем контакты
		$query = $this->db->get_where('crm_company', array('id' => $this->input->post('id')));
		$contact_arr = $query->row_array();
		$data_contact = Array(
			'email' => $this->input->post('edit_at'), 
			'phone' => $this->input->post('edit_phone'), 
			'skype' => $this->input->post('edit_skype'), 
			'www' => $this->input->post('edit_www')
			);
			
		$this->db->where('id', $contact_arr['id_contact']);
		$this->db->update('crm_contacts', $data_contact); 
		
		$data = Array(
			'name' => addslashes($this->input->post('name_comp')),
			'description' => $this->input->post('descr_company'),
			'adres' => $this->input->post('comp_adres')
		);
		
		//Заносим обновления в БД
		$this->db->where('id', $this->input->post('id'));
		$this->db->update('crm_company', $data); 
	}
	
	//Обновление данных контактного лица
	function edit_contact( $doljnost = '', $phone = '', $skype = '', $email = '' )
	{
		if( $doljnost == '' )
		{
			$doljnost = $this->input->post('edit_doljnost');
		}
		if( $phone == '' )
		{
			$phone = $this->input->post('phone_edit_client');
		}
		if( $skype == '' )
		{
			$skype = $this->input->post('skype_edit_client');
		}
		if( $email == '' )
		{
			$email = $this->input->post('email_edit_client');
		}
		
		//Получаем ID обновлятора
		$author_array_json = $this->ion_auth->profile();
		$author1 = json_encode($author_array_json, true);
		$author = json_decode($author1, true);
		
		//Обновляем контакты
		$data_contact = Array(
			'email' => $email, 
			'phone' => $phone, 
			'skype' => $skype
		);

		//Узнаем ID таблицы контактов
		$query = $this->db->get_where('crm_clients', array('id' => $this->input->post('id')));
		$cont_arr = $query->row_array();

		$this->db->where('id', $cont_arr['id_contact']);
		$this->db->update('crm_contacts', $data_contact); 
		
		//Узнаем ID новой компании если она введена
		$name_c = trim(addslashes($this->input->post('client_edit_company')));
		
		if( !empty($name_c) )
		{
			$query = $this->db->get_where('crm_company', array('name' => $name_c));
			$company_arr = $query->row_array();
			//Если такой компании нету то добавляем новую
			if( count( $company_arr ) == 0 )
			{
				$id_new_comp = $this->clients_md->create_company($this->input->post('client_edit_company'), FALSE);

				$data = Array(
					'fio' => $this->input->post('name_client'),
					'adres' => $this->input->post('adres_edit_client'),
					'doljnost' => $this->input->post('edit_doljnost'),
					'id_company' => $id_new_comp
				);
				
				$return = $id_new_comp;
			}
			else //Иначе обновляем
			{
				$data = Array(
					'fio' => $this->input->post('name_client'),
					'adres' => $this->input->post('adres_edit_client'),
					'doljnost' => $this->input->post('edit_doljnost'),
					'id_company' => $company_arr['id']
				);
				
				$return = $company_arr['id'];
			}
		}
		else
		{
			$data = Array(
				'fio' => $this->input->post('name_client'),
				'adres' => $this->input->post('adres_edit_client'),
				'doljnost' => $this->input->post('edit_doljnost'),
				'id_company' => 0
			);
			
			$return = 0;
		}
		

		//Заносим обновления в БД
		$this->db->where('id', $this->input->post('id'));
		$this->db->update('crm_clients', $data); 
		return $return;
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
		$data_event = Array(
			'author' => $author['id'],
			'date' => date('Y-m-d H-i-s'),
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
		$data_event = Array(
			'author' => $author['id'],
			'date' => date('Y-m-d H-i-s'),
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

	//Удаление контактного лица
	function delete_contact()
	{
		$id = $this->input->post('id');
		$this->db->where('id', $id);
		$this->db->delete('crm_clients');

		$query = $this->db->get_where('crm_deals', array('client_id' => $id));
		$d_arr = $query->result_array();
		if( !empty($d_arr) )
		{
			$client = '';
			foreach( $d_arr as $list )
			{
				$client .= $list['id'] . ','; 
			}
			$client = substr($client, 0, -1);
			$this->db->query('UPDATE `crm_deals` SET `client_id` = NULL WHERE `id` in(' . $client . ')');
		}

		return $id;
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
}