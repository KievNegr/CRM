<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin_md extends CI_Model {

	function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->library('ion_auth');
		$this->load->library('session');
	}
	
	//Получаем весь список состояния сделок
	function getStateDeal()
	{
		$this->db->order_by('id', 'asc');
		$query = $this->db->get('state_deal');
		return $query->result_array();
	}

	//Получаем весь список юзверей
	function getUsers()
	{
		$query = $this->db->get('users');
		$res = $query->result_array();
		$return = Array();
		foreach( $res as $key => $item )
		{
			$resData = $this->db->get_where('meta', array('user_id' => $item['id']));
			$data = $resData->row_array();
			$return[$key] = $res[$key];
			$return[$key]['phone'] = $data['phone'];
			$return[$key]['name'] = $data['first_name'] . ' ' . $data['last_name'];
		}

		return $return;
	}

	//Список групп
	function getGroup()
	{
		$query = $this->db->get('groups');
		return $query->result_array();
	}

	function getUser($id)
	{
		$query = $this->db->get_where('users', array('id' => $id));
		$resUsers = $query->row_array();

		$query = $this->db->get_where('meta', array('user_id' => $id));
		$resMeta = $query->row_array();

		$query = $this->db->get_where('groups', array('id' => $resUsers['group_id']));
		$resGroups = $query->row_array();

		$query = $this->db->get_where('crm_deals', array('author' => $resUsers['id']));
		$resDeals = $query->result_array();
		$query = $this->db->get('state_deal');
		$resStateDeals = $query->result_array();

		$query = $this->db->get_where('crm_clients', array('id_author' => $resUsers['id']));
		$resClients = $query->result_array();

		$query = $this->db->get_where('crm_company', array('id_author' => $resUsers['id']));
		$resCompany = $query->result_array();

		$query = $query = $this->db->query('SELECT*FROM crm_tasks WHERE user_id RLIKE "[[:<:]]' . $resUsers['id'] . '[[:>:]]" OR author = ' . $resUsers['id'] . ' ORDER BY deadline ASC');
		$resTasks = $query->result_array();
		$query = $this->db->get('state_task');
		$resStateTasks = $query->result_array();

		$return = Array(
				'id' => $resUsers['id'],
				'name' => $resUsers['username'],
				'phone' => $resMeta['phone'],
				'email' => $resUsers['email'],
				'group' => $resGroups['description'],
				'create' => $resUsers['created_on'],
				'lastlogin' => $resUsers['last_login'],
				'deals' => $resDeals,
				'statedeals' => $resStateDeals,
				'clients' => $resClients,
				'company' => $resCompany,
				'tasks' => $resTasks,
				'statetasks' => $resStateTasks
				);

		return $return;
	}
}