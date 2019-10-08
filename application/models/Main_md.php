<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Main_md extends CI_Model {

	function __construct()
	{
		parent::__construct();
		$this->load->database();
	}
	
	//Получаем события
	function get_events( $limit = '' )
	{
		$this->db->order_by('id', 'desc');
		
		if( $limit != '' )
		{
			$this->db->limit($limit);
		}
		
		$query = $this->db->get('crm_events');
		
		//Собираем массив данных
		$arr = Array();
		foreach( $query->result_array() as $list )
		{
			$author = $this->get_author( $list['author'] );
			$data = Array (
				'author' => $author,
				'data' => $list['date'],
				'description' => $list['description']
			);
			$arr[] = $data;
		}
		return $arr;
	}
	
	function get_author( $id = '' )
	{
		//Находим имя автора
		$query = $this->db->get_where('meta', array('id' => $id));
		$query_author = $query->row_array();
		return $query_author['first_name'] . ' ' . $query_author['last_name'];
	}

	//Получаем списки email's пользователей
	function get_email($users)
	{
		$query = $this->db->query('SELECT*FROM users WHERE id in(' . $users .')');
		$res = $query->result_array();

		$mails = Array();

		foreach( $res as $list )
		{
			$mails[] = $list['email'];//Array('name' => $list['username'], 'mail' => $list['email']);
		}

		return $mails;
	}

	//Редактирование профиля
	function edit_profile( $id = '' )
	{
		$first_name = $this->input->post('name');
		$last_name = $this->input->post('last_name');
		$email = $this->input->post('email');
		$phone = $this->input->post('phone');
		$pass = $this->input->post('pass');

		if( !empty($email) ){
			$data = Array('email' => $email);

			//Заносим email в БД
			$this->db->where('id', $id);
			$this->db->update('users', $data); 
		}

		if( !empty($first_name) ){
			$data = Array('first_name' => $first_name, 'last_name' => $last_name, 'phone' => $phone);

			//Заносим email в БД
			$this->db->where('user_id', $id);
			$this->db->update('meta', $data); 
		}
	}

	//Смена аватара
	function edit_avatar( $id = '', $avatar = '' )
	{
		$query = $this->db->get_where('users', array('id' => $id));
		$av = $query->row_array();
		if( !empty($av['avatar']) )
		{
			unlink($_SERVER['DOCUMENT_ROOT'] . '/img/avatars/' . $av['avatar']);
		}

		$data['avatar'] = $avatar;
		$this->db->where('id', $id);
		$this->db->update('users', $data); 
	}

	//Функция редактирования события
	function saveEditResponse($id, $text)
	{
		$data = Array('description' => $text);

		$this->db->where('id', $id);
		$this->db->update('crm_events', $data);

		return Array('id' => $id, 'text' => $text);
	}

	//Удаление события
	function dellEvent()
	{
		$idEvent = $this->input->post('idEvent');

		//Загрузка данных профиля
		$profile = $this->ion_auth->profile();
		$profile = json_encode($profile, true);
		$profile = json_decode($profile, true);

		//Удаляем строчку события
		$this->db->where(array('id' => $idEvent, 'author' => $profile['id']));
		$result = $this->db->delete('crm_events'); 

		if( $result == TRUE )
		{
			//Удаляем ID события из сделок
			$query = $this->db->query('SELECT*FROM crm_deals WHERE events RLIKE "[[:<:]]' . $idEvent .'[[:>:]]"');
			$res = $query->result_array();
			if( !empty($res) )
			{
				foreach( $res as $list )
				{
					$arr = explode(',', $list['events']);
					$temp_d = Array();
					foreach( $arr as $subList )
					{
						if( $subList != $idEvent )
						{
							$temp_d[] = $subList;
						}
					}
					$events_d = implode(',', $temp_d);
					$data_p = Array('events' => $events_d);
					
					$this->db->where('id', $list['id']);
					$this->db->update('crm_deals', $data_p); 
				}
			}

			//Удаляем ID события из компаний
			$query = $this->db->query('SELECT*FROM crm_company WHERE id_event RLIKE "[[:<:]]' . $idEvent .'[[:>:]]"');
			$res = $query->result_array();
			if( !empty($res) )
			{
				foreach( $res as $list )
				{
					$arr = explode(',', $list['id_event']);
					$temp_c = Array();
					foreach( $arr as $subList )
					{
						if( $subList != $idEvent )
						{
							$temp_c[] = $subList;
						}
					}
					$events_d = implode(',', $temp_c);
					$data_c = Array('id_event' => $events_d);
					
					$this->db->where('id', $list['id']);
					$this->db->update('crm_company', $data_c); 
				}
			}

			//Удаляем ID события из контактных лиц
			$query = $this->db->query('SELECT*FROM crm_clients WHERE id_event RLIKE "[[:<:]]' . $idEvent .'[[:>:]]"');
			$res = $query->result_array();
			if( !empty($res) )
			{
				foreach( $res as $list )
				{
					$arr = explode(',', $list['id_event']);
					$temp_c = Array();
					foreach( $arr as $subList )
					{
						if( $subList != $idEvent )
						{
							$temp_c[] = $subList;
						}
					}
					$events_d = implode(',', $temp_c);
					$data_c = Array('id_event' => $events_d);
					
					$this->db->where('id', $list['id']);
					$this->db->update('crm_clients', $data_c); 
				}
			}

			return $idEvent;
		}
	}

	//Добавление ответа для примечания
	function saveResponse($id_deal = '', $sub_id, $id_task = '', $text)
	{
		//Получаем ID автора события
		$author_array_json = $this->ion_auth->profile();
		$author1 = json_encode($author_array_json, true);
		$author = json_decode($author1, true);

		$data_d = Array('data' => date('Y-m-d'));
		$this->db->where('id', $id_deal);
		$this->db->update('crm_deals', $data_d);

		$data = Array(
			'sub_id' => $sub_id,
			'author' => $author['id'],
			'id_deal' => $id_deal,
			'id_task' => $id_task,
			'date' => date('Y-m-d H-i-s'),
			'description' => $text
			);
		
		$this->db->insert('crm_events', $data);
		$id_event = mysql_insert_id();
		$data['id_event'] = $id_event;
		$data['author'] = $this->clients_md->get_author_event($author['id']);
		return $data;
	}
}