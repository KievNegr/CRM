<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Clients extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->library('ion_auth');
		$this->load->library('session');
		$this->load->model('clients_md');
		$this->load->model('deal_md');
		$this->load->model('task_md');
	}
	
	//Показать моих клиентов
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
			
			//Загрузка компаний
			$clients = $this->clients_md->getClients($profile['id']);

			$all_company = $this->clients_md->get_all_company();
			
			$headerMenu = Array(
				'index' => '',
				'deals' => '',
				'clients' => 'class="active"',  //Текущее глобальное местоположение
				'tasks' => ''
			);

			$subMenu = Array(
				'my' => 'class="active"', //Текущее локальное местоположение
				'all' => ''
			);

			$data = Array(
				'profile' => $profile,
				'data_clients' => $clients,
				'all_company' => $all_company,
				'headerMenu' => $headerMenu,
				'subMenu' => $subMenu
				);
							
			$this->load->view('header', $data);
			$this->load->view('clients', $data);
			$this->load->view('sidebar');
			$this->load->view('footer');
		}
	}

	public function all()
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
			
			//Загрузка компаний
			$clients = $this->clients_md->getClients(0);

			$all_company = $this->clients_md->get_all_company();
			
			$headerMenu = Array(
				'index' => '',
				'deals' => '',
				'clients' => 'class="active"',  //Текущее глобальное местоположение
				'tasks' => ''
			);

			$subMenu = Array(
				'my' => '', 
				'all' => 'class="active"' //Текущее локальное местоположение
			);

			$data = Array(
				'profile' => $profile,
				'data_clients' => $clients,
				'all_company' => $all_company,
				'headerMenu' => $headerMenu,
				'subMenu' => $subMenu
				);
							
			$this->load->view('header', $data);
			$this->load->view('clients', $data);
			$this->load->view('sidebar');
			$this->load->view('footer');
		}
	}
	
	public function contact( $id = '')
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
			
			//Загрузка информации о клиенте
			$client = $this->clients_md->get_client($id);
			
			//Получаем список компаний для добавления новых контактов
			$all_company = $this->clients_md->get_all_company();
			
			//Выбираем всех сотрудников кроме себя
			$users = $this->task_md->get_all_users();

			$headerMenu = Array(
				'index' => '',
				'deals' => '',
				'clients' => 'class="active"',  //Текущее глобальное местоположение
				'tasks' => ''
			);

			if( $profile['id'] == $client['author_id'] )
			{
				$subMenu = Array(
					'my' => 'class="active"', //Текущее локальное местоположение
					'all' => ''
				);
			}
			else
			{
				$subMenu = Array(
					'my' => '', 
					'all' => 'class="active"' //Текущее локальное местоположение
				);
			}
		
			$data = Array(
				'profile' => $profile,
				'data_clients' => $client,
				'all_company' => $all_company,
				'headerMenu' => $headerMenu,
				'subMenu' => $subMenu
				);
			
			$submenu = Array(
				'<a href="' . base_url('clients') . '">Все</a>',
				'<a href="' . base_url('clients/my_clients') . '">Мои</a>'
			);
			
			$sidebar = Array(
				'subMenu' => $submenu,
				'location' => 'clients'
			);
							
			$this->load->view('header', $data);
			$this->load->view('client', $data);
			$this->load->view('sidebar', $sidebar);
			$this->load->view('add_task', $data);
			$this->load->view('footer');
		}
	}
	
	public function company( $id = '')
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
			
			//Загрузка информации о компании
			$company = $this->clients_md->get_company($id);
			
			//Получаем список компаний для добавления новых контактов
			$all_company = $this->clients_md->get_all_company();
			
			//Выбираем всех сотрудников кроме себя
			$users = $this->task_md->get_all_users();

			$headerMenu = Array(
				'index' => '',
				'deals' => '',
				'clients' => 'class="active"',  //Текущее глобальное местоположение
				'tasks' => ''
			);

			if( $profile['id'] == $company['author_id'] )
			{
				$subMenu = Array(
					'my' => 'class="active"', //Текущее локальное местоположение
					'all' => ''
				);
			}
			else
			{
				$subMenu = Array(
					'my' => '', 
					'all' => 'class="active"' //Текущее локальное местоположение
				);
			}
		
			$data = Array(
				'profile' => $profile,
				'data_clients' => $company,
				'all_company' => $all_company,
				'headerMenu' => $headerMenu,
				'subMenu' => $subMenu
				);
				
			$this->load->view('header', $data);
			$this->load->view('company', $data);
			$this->load->view('sidebar');
			$this->load->view('footer');
		}
	}
	
	//Создание компании
	function createCompany()
	{
		echo $this->clients_md->createCompany();
	}
	
	//Создание контактного лица
	function create_client()
	{
		$res = $this->clients_md->create_client( $this->input->post('name_client'), $this->input->post('company_client'), $this->input->post('doljnost_client'), $this->input->post('phone_client'), $this->input->post('skype_client'), $this->input->post('email_client'));
		echo '<li class="group ' . $res['id'] . '">';
			echo '<span>' . $res['name'] . '</span>';
			echo '<div class="cut"></div>';
			echo '<div class="cart">';
				echo '<a href="' . base_url('clients/show_contact/' . $res['id']) . '" class="cart_a">Перейти в профиль</a>';
				echo '<!--<div style="clear:both; height: 6px;"></div>';
				echo '<span class="cart_link">Редактировать</span>-->';
				echo '<div style="clear:both; height: 6px;"></div>';
				echo '<span class="cart_link del-from-company" id_contact="' . $res['id'] . '">Открепить</span>';
			echo '</div>';
		echo '</li>';
	}
	
	//Обновление компании
	function editCompany()
	{
		echo json_encode($this->clients_md->editCompany());
	}
	
	//Обновление контактного лица
	function editClient()
	{
		echo json_encode($this->clients_md->editClient());
	}

	//Добавление события по компании со страницы компании
	public function add_event_company()
	{
		$arr = $this->clients_md->add_event_company();

		$time = 'Сегодня в ' . substr($arr['data'], -8, 5);
		echo '<div class="event_transfer' . $arr['id_event'] . '">';
		echo '<div class="show_event">';
			echo '<div class="functional-event-company">';
				echo '<div class="edit-event" id_event="' . $arr['id_event'] . '"></div>';
				echo '<div class="del-event" id_event="' . $arr['id_event'] . '"></div>';
			echo '</div>';
			echo '<p class="human author">' . $arr['author'] . ', ' . $time . '</p>';
			echo '<p class="p-' . $arr['id_event'] . ' p-edit-response">' . $arr['description'] . '</p>';
			//echo $l_event['adding_text'];
			echo '<div class="save-edit-response-' . $arr['id_event'] . ' hide" style="margin: 0 0 10px 3%;">
					<textarea class="response_edit_text edit-event-' . $arr['id_event'] . '" style="display: block; margin: 5px 0 5px 0;">' . $arr['description'] . '</textarea>
					<div style="clear: both;"></div>
					<button class="edit-save-response" id_save="' . $arr['id_event'] . '">Сохранить</button>
					<button class="edit-cancel-response">Отмена</button>
				</div>';
			echo '<div id="' . $arr['id_event'] . '"></div>';
			echo '<div class="response_block" id_event="' . $arr['id_event'] . '">
					<div class="response" open-class="' . $arr['id_event'] . '">Добавить комментарий</div>
						<div style="clear:both;"></div>	
						<div class="save-field-response ' . $arr['id_event'] . '">
							<textarea class="textarea response' . $arr['id_event'] . '" text-event="' . $arr['id_event'] . '"></textarea>
							<div style="clear:both;"></div>	
							<button class="save-response" id_save="' . $arr['id_event'] . '" id_task="">Сохранить</button>
							<button class="cancel-response">Отмена</button>
						</div>
					</div>';

		echo '</div>';
		echo '</div>';
	}

	public function saveEditResponse()
	{
		$id = $this->input->post('id_event');
		$text = $this->input->post('text');

		$input = nl2br($text);
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
		$temp_out = str_replace('div>', 'p>', $temp_out);

		$arr = $this->main_md->saveEditResponse($id, $temp_out);

		echo json_encode($arr);
	}

	//Добавление события по контактному лицу со страницы контактного лица
	public function add_event_client()
	{
		$arr = $this->clients_md->add_event_client();

		$time = 'Сегодня в ' . substr($arr['data'], -8, 5);
		echo '<div class="show_event">';
			echo '<p class="human author">' . $arr['author'] . ', ' . $time . '</p>';
			echo '<p>' . $arr['description'] . '</p>';
		echo '</div>';
	}

	//Удаление компании
	public function delete_company()
	{
		$id = $this->input->post('id');
		$this->clients_md->delete_company($id);
	}

	//Удаление контактного лица
	public function delete_contact()
	{
		$id = $this->input->post('id');
		$this->clients_md->delete_contact($id);
	}

	//Функция поиска
	public function search()
	{
		$search = addslashes($this->input->post('search'));
		$res = $this->clients_md->search( $search );
		if( !empty($res) )
		{
			echo '<table>';
				echo '<tr class="title">';
					echo '<th>Контактное лицо</th>';
					echo '<th>Компания</th>';
					echo '<th>Телефон</th>';
					echo '<th>Web-сайт</th>';
					echo '<th>Адрес</th>';
				echo '</tr>';
				foreach( $res as $list )
				{
					if( is_array($list['fio']) )
					{
						$fio =  '<ul id="persons">';
						foreach( $list['fio'] as $person )
						{
							$fio .= '<li><a href="' . base_url('clients/show_contact/' . $person['id']) . '" class="contact_face">' . $person['fio'] . '</a></li>';
						}
						$fio .= '</ul>';
					}
					else
					{
						$fio = '<ul id="persons"><li><a href="' . base_url('clients/show_contact/' . $list['id_face']) . '" class="contact_face">' . $list['fio'] . '</a></li></ul>';
					}

					if( isset($list['id']) )
					{
						$c = '<td><div class="point_client_company"></div><a href="' . base_url('clients/show_company/' . $list['id']) . '" class="name_deal">' . stripslashes($list['name']) . '</a></td>';
					}
					else
					{
						$c = '<td></td>';
					}
					echo '<tr>';
						echo '<td>';
							echo $fio;
						echo '</td>';
						echo $c;
						echo '<td></td>';
						echo '<td></td>';
						echo '<td></td>';
					echo '</tr>';
				}
			echo '</table>';
		}
	}

	//Открепление контакта от компании
	public function DelFromCompany()
	{
		echo $this->clients_md->DelFromCompany();
	}
}