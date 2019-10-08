<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Deals extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->model('deal_md');
		$this->load->model('clients_md');
		$this->load->model('task_md');
		$this->load->library('ion_auth');
		$this->load->library('session');
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
			
			//Получаем весь список состояния сделок для sidebar
			$state_deal = $this->deal_md->get_list_deal();

			//Получаем весь список валют
			$currency = $this->deal_md->getAllCurrency();

			//Выберем статусы для показа (активные короче говоря state_deal( active => 1 ) )
			$listSubMenu = Array();
			foreach( $state_deal as $setGetDeal )
			{
				if( $setGetDeal['active'] == 1 )
				{
					$selectActive[] = $setGetDeal['id'];
				}

				//И выберем ссылочки для меню
				if( $setGetDeal['menu'] == 1 )
				{
					$listSubMenu[] = '<a href="' . base_url('deals/state/' . $setGetDeal['rewrite']) . '">' . $setGetDeal['value'] . '</a>';
				}
			}

			$get_deal = $this->deal_md->get_deal( $selectActive );

			$headerMenu = Array(
				'index' => '',
				'deals' => 'class="active"',//Текущее глобальное местоположение
				'clients' => '',
				'tasks' => ''
			);

			$subMenu = Array(
				'active' => 'class="active"', //Текущее локальное местоположение
				'my' => '',
				'sub' => $listSubMenu
			);
								
			$data = Array(
				'profile' => $profile,
				'state_deal' => $state_deal,
				'currency' => $currency,
				'deal' => $get_deal,
				'all_company' => $this->clients_md->get_all_company(),
				'all_clients' => $this->clients_md->get_all_clients1(),
				'headerMenu' => $headerMenu,
				'subMenu' => $subMenu
				);

			$this->load->view('header', $data);
			$this->load->view('deals', $data);
			$this->load->view('sidebar');
			$this->load->view('footer');
		}
	}
	
	public function state( $input_deal )
	{
		//Загрузка данных профиля
		$profile = $this->ion_auth->profile();
		$profile = json_encode($profile, true);
		$profile = json_decode($profile, true);
		
		//Получаем весь список состояния сделок для sidebar
		$state_deal = $this->deal_md->get_list_deal();

		//Получаем весь список валют
		$currency = $this->deal_md->getAllCurrency();

		//Если существует input_deal то отображаем список
		if( !empty( $input_deal ) )
		{
			$checked_id_deal = FALSE;
			
			//Проверим существует ли такой ID вообще, что б лишний раз не лазить в базу
			foreach( $state_deal as $check )
			{
				if( $input_deal == $check['rewrite'] )
				{
					//Если существует то даем зеленый свет
					$checked_id_deal = TRUE;
					$state_id = $check['id'];
				}
			}
			
			//Если $checked_id_deal трушный то полезли в базу фильтровать сделки
			if( $checked_id_deal == TRUE )
			{
				$get_deal = $this->deal_md->get_deal( $state_id );
			}
		}

		//header
		$header = '#3465FF';
		$title_h1 = 'Сделки';
		$index = base_url('deal');
							
		$data = Array(
			'profile' => $profile,
			'state_deal' => $state_deal,
			'currency' => $currency,
			'deal' => $get_deal,
			'all_company' => $this->clients_md->get_all_company(),
			'all_clients' => $this->clients_md->get_all_clients(),
			'header' => $header,
			'title_h1' => $title_h1,
			'index' => $index,
			'place' => 'deals'
			);
		
		$submenu = Array('<a href="' . base_url('deal') . '">Все</a> | <a href="' . base_url('deal/my_deal') . '">Мои</a>');

		foreach( $state_deal as $sidebarState )
		{
			if( $sidebarState['rewrite'] == $input_deal )
			{
				$submenu[] = '<span class="state-list">' . $sidebarState['value'] . '</span>';
			}
			else
			{
				if( $sidebarState['menu'] == 1 )
				{
					$submenu[] = '<a href="' . base_url('deals/state/' . $sidebarState['rewrite']) . '">' . $sidebarState['value'] . '</a>';
				}
			}
		}
		
		$sidebar = Array(
			'subMenu' => $submenu,
			'location' => 'deal'
		);

		$this->load->view('header', $data);
		$this->load->view('deal', $data);
		$this->load->view('sidebar_menu', $sidebar);
		$this->load->view('sidebar_submenu', $sidebar);
		$this->load->view('footer');
	}
	
	public function my()
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
			
			//Получаем весь список состояния сделок для sidebar
			$state_deal = $this->deal_md->get_list_deal();

			//Получаем весь список валют
			$currency = $this->deal_md->getAllCurrency();
			
			//Выберем статусы для показа (активные короче говоря state_deal( active => 1 ) )
			$listSubMenu = Array();
			foreach( $state_deal as $setGetDeal )
			{
				if( $setGetDeal['active'] == 1 )
				{
					$selectActive[] = $setGetDeal['id'];
				}

				//И выберем ссылочки для меню
				if( $setGetDeal['menu'] == 1 )
				{
					$listSubMenu[] = '<a href="' . base_url('deals/state/' . $setGetDeal['rewrite']) . '">' . $setGetDeal['value'] . '</a>';
				}
			}

			//Полезли в базу фильтровать сделки
			$get_deal = $this->deal_md->get_deal( $selectActive, 'my', $profile['id'] );
			
			$headerMenu = Array(
				'index' => '',
				'deals' => 'class="active"',//Текущее глобальное местоположение
				'clients' => '',
				'tasks' => ''
			);

			$subMenu = Array(
				'active' => '',
				'my' => 'class="active"', //Текущее локальное местоположение
				'sub' => $listSubMenu
			);
								
			$data = Array(
				'profile' => $profile,
				'state_deal' => $state_deal,
				'currency' => $currency,
				'deal' => $get_deal,
				'all_company' => $this->clients_md->get_all_company(),
				'all_clients' => $this->clients_md->get_all_clients1(),
				'headerMenu' => $headerMenu,
				'subMenu' => $subMenu
				);

			$this->load->view('header', $data);
			$this->load->view('deals', $data);
			$this->load->view('sidebar');
			$this->load->view('footer');
		}
	}
	
	public function deal($idDeal)
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
			
			//Получаем весь список состояния сделок для sidebar
			$state_deal = $this->deal_md->get_list_deal();

			//Получаем весь список валют
			$currency = $this->deal_md->getAllCurrency();

			//Если существует id то отображаем сделку
			if( !empty( $idDeal ) )
			{
				$deal = $this->deal_md->showDeal( $idDeal );
			}

			//Выбираем всех сотрудников кроме себя
			$users = $this->task_md->get_all_users();

			//Выберем статусы для показа (активные короче говоря state_deal( active => 1 ) )
			$listSubMenu = Array();
			foreach( $state_deal as $setGetDeal )
			{
				if( $setGetDeal['active'] == 1 )
				{
					$selectActive[] = $setGetDeal['id'];
				}

				//И выберем ссылочки для меню
				if( $setGetDeal['menu'] == 1 )
				{
					$listSubMenu[] = '<a href="' . base_url('deals/state/' . $setGetDeal['rewrite']) . '">' . $setGetDeal['value'] . '</a>';
				}
			}
			
			$headerMenu = Array(
				'index' => '',
				'deals' => 'class="active"',//Текущее глобальное местоположение
				'clients' => '',
				'tasks' => ''
			);

			if( $deal['author_id'] == $profile['id'] )
			{
				$subMenu = Array(
					'active' => '',
					'my' => 'class="active"', //Текущее локальное местоположение
					'sub' => $listSubMenu
				);
			}
			else
			{
				$subMenu = Array(
					'active' => 'class="active"', //Текущее локальное местоположение
					'my' => '', 
					'sub' => $listSubMenu
				);
			}

			$data = Array(
				'profile' => $profile,
				'state_deal' => $state_deal,
				'currency' => $currency,
				'deal' => $deal,
				'all_company' => $this->clients_md->get_all_company(),
				'all_clients' => $this->clients_md->get_all_clients1(),
				'users' => $users,
				'headerMenu' => $headerMenu,
				'subMenu' => $subMenu
				);

			$submenu = Array('<span class="all-my">Все</span> | <a href="' . base_url('deal/my_deal') . '">Мои</a>');

			foreach( $state_deal as $sidebarState )
			{
				if( $sidebarState['menu'] == 1 )
				{
					$submenu[] = '<a href="' . base_url('deal/state/' . $sidebarState['rewrite']) . '">' . $sidebarState['value'] . '</a>';
				}
			}

			$this->load->view('header', $data);
			$this->load->view('deal', $data);
			$this->load->view('sidebar');
			$this->load->view('footer');
		}
	}
	
	public function get_client_add_deal()
	{
		$clients = $this->deal_md->get_ajax_clients();
		if( count($clients) != 0 )
		{
			foreach( $clients as $list )
			{
				echo '<option value="'. $list['id'] . '">' . $list['fio'] . '</option>';
			}
		}
	}
	
	public function createDeal()
	{
		echo $this->deal_md->createDeal();
	}
	
	public function editDeal()
	{
		$arr = $this->deal_md->editDeal();
		echo json_encode($arr);
	}

	public function addContactfaceDeal()
	{
		$arr = $this->deal_md->addContactfaceDeal();

		$data = '<li class="group ' . $arr['idClient'] . '">
			<span>' . $arr['nameClient'] . '</span>
			<div class="cart">
				<p>Контакт: <a href="' . base_url('clients/contact/' . $arr['idClient']) . '" class="cart_a">' . $arr['nameClient'] . '</a></p>
				<p>Компания: <a class="cart_a" href="' . base_url('clients/company/' . $arr['companyId']) . '">Допилить код</a></p><p class="cart_link del-from-deal" id_contact="' . $arr['idClient'] . '">Открепить</p>
				</div>
			<div class="cut"></div>
		</li>';

		echo $data;
	}

	public function addEventDeal()
	{
		$arr = $this->deal_md->addEventDeal();

		echo '<div class="event_transfer' . $arr['idEvent'] . '">
			<div class="show_event"> 
				<div class="functional-event-deal">
					<div class="edit-event" id-event="' . $arr['idEvent'] . '"></div>
					<div class="del-event" id-event="' . $arr['idEvent'] . '"></div>
				</div>
		
				<div class="author-info">
					<div class="comment-avatar" style="background-image: url(http://crm2/img/avatars/14179529514140.jpg);"></div>
					<p class="author">' . $arr['author'] . ', Сегодня в ' . $arr['data'] . '</p>
					<p class="doljnost">Developer</p>
				</div>
							
				<div class="text-comment">
					<p>' . $arr['description'] . '</p>
				</div>
			</div>
		</div>';
		
	}

	//Быстрое изменение статуса сделки
	public function fastEditStatusDeal()
	{
		$arr = $this->deal_md->fastEditStatusDeal();
	}

	//Открепление контактного лица со сделки
	public function disconnectFromDeal()
	{
		echo $this->deal_md->disconnectFromDeal();
	}

	//Открепление основного контактного лица со сделки
	public function offDefaultContact()
	{
		$id_contact = $this->input->post('id_contact');
		$id_deal = $this->input->post('id_deal');
		$this->deal_md->offDefaultContact($id_deal);
		echo $id_contact;
	}

	//Открепление основной компании со сделки
	public function offDefaultCompany()
	{
		$id_company = $this->input->post('id_company');
		$id_deal = $this->input->post('id_deal');
		$this->deal_md->offDefaultCompany($id_deal);
		echo $id_company;
	}

	//Удаление сделки
	public function delete_deal()
	{
		$id = $this->input->post('id');
		$this->deal_md->delete_deal($id);
	}

	

	//Загрузка формы редактирования задачи
	public function loadEditTask()
	{
		$id = $this->input->post('id_task');
		$arr = $this->task_md->getEditTaskForId( $id );
		echo json_encode($arr);
	}

	//Функция поиска
	public function search()
	{
		$search = addslashes($this->input->post('search'));
		$res = $this->deal_md->search( $search );
		if( !empty($res) )
		{
			echo '<table>';
				echo '<tr class="title">';
					echo '<th>Название сделки</th>';
					echo '<th>Контактное лицо</th>';
					echo '<th>Компания</th>';
					echo '<th>Бюджет</th>';
					echo '<th>Текущий статус</th>';
					echo '<th>Дата изменения</th>';
				echo '</tr>';
				foreach( $res as $list )
				{
					
					$list_clients = '';
					foreach( $list['sub_client'] as $person )
					{
						$list_clients .= '<a href="' . base_url('clients/show_contact/' . $person['id']) . '" class="contact_face">' . $person['fio'] . '</a>, ';
					}
					$list_clients = substr($list_clients, 0, -2);

					$link_company = '';
					if( $list['company_id'] != 0 )
					{
						$link_company = '<a href="' . base_url('clients/show_company/' . $list['company_id']) . '" class="contact_company">' . $list['company_name'] . '</a>';
					}

					echo '<tr>';
						echo '<td>';
							echo '<div class="point_deal" style="background: #' . $list['color'] . '"></div><a href="' . base_url('deal/show_deal/' . $list['id']) . '" class="name_deal">' . $list['name_deal'] . '</a>';
						echo '</td>';
						echo '<td>';
							echo $list_clients;
						echo '</td>';
						echo '<td>';
							echo $link_company;
						echo '</td>';
						echo '<td>';
							echo $list['budget'] . ' €';
						echo '</td>';
						echo '<td>';
							echo '<span class="status" style="background: #' . $list['color'] . '">' . $list['status_deal'] . '</span>';
						echo '</td>';
						echo '<td>';
							echo substr($list['data_last'], 8, 2 ) . '.' . substr($list['data_last'], 5, 2 ) . '.' . substr($list['data_last'], 0, 4 );
						echo '</td>';
					echo '</tr>';
				}
			echo '</table>';
		}
	}
}