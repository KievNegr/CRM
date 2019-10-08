<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->model('deal_md');
		$this->load->model('clients_md');
		$this->load->model('task_md');
		$this->load->model('admin_md');
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
			
			//Проверим на админство
			if( $profile['group_id'] == 1 )
			{
				//Если все заебись то выполняем код

				//header
				$header = Array(
					'header' => '#C25B5B', 
					'title_h1' => 'Администратор', 
					'index' => base_url('admin'), 
					'place' => 'admin',
					'profile' => $profile
				);
					
				//sidebar_submenu
				//$sidebar_menu = Array('sidebar' => $sidebar);
									
				$data = Array(
					'profile' => $profile,
					);

				$submenu = Array(
					'<span class="all-my">Все</span> | <a href="' . base_url('deal/my_deal') . '">Мои</a>',
				);
				
				$sidebar = Array(
					'subMenu' => $submenu,
					'location' => 'home'
				);

				//Загружаем все в шаблон
				$this->load->view('admin/header', $header);
				$this->load->view('admin/main', $data);
				$this->load->view('admin/sidebar_menu', $sidebar);
				$this->load->view('admin/footer');
			}
			else
			{
				redirect(base_url(), 'refresh');
			}
		}
	}

	public function users()
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
			
			//Проверим на админство
			if( $profile['group_id'] == 1 )
			{
				//Если все заебись то выполняем код

				//header
				$header = Array(
					'header' => '#C25B5B', 
					'title_h1' => 'Администратор', 
					'index' => base_url('admin'), 
					'place' => 'users',
					'profile' => $profile
				);
					
				$users['users'] = $this->admin_md->getUsers();
				$users['group'] = $this->admin_md->getGroup();
									
				$data = Array(
					'profile' => $profile,
					);

				$submenu = Array(
					'<span class="all-my">Все</span> | <a href="' . base_url('deal/my_deal') . '">Мои</a>',
				);
				
				$sidebar = Array(
					'subMenu' => $submenu,
					'location' => 'users'
				);

				//Загружаем все в шаблон
				$this->load->view('admin/header', $header);
				$this->load->view('admin/users', $users);
				$this->load->view('admin/sidebar_menu', $sidebar);
				$this->load->view('admin/footer');
			}
			else
			{
				redirect(base_url(), 'refresh');
			}
		}
	}

	public function showuser($id)
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
			
			//Проверим на админство
			if( $profile['group_id'] == 1 )
			{
				//Если все заебись то выполняем код

				//header
				$header = Array(
					'header' => '#C25B5B', 
					'title_h1' => 'Администратор', 
					'index' => base_url('admin'), 
					'place' => 'users',
					'profile' => $profile
				);
					
				$users['user'] = $this->admin_md->getUser($id);

				$users['active'] = Array();
				$users['noactive'] = Array();
				foreach($users['user']['deals'] as $listD)
				{
					foreach($users['user']['statedeals'] as $stateD)
					{
						if( $listD['state_id'] == $stateD['id'] )
						{
							if($stateD['active'] == 1)
							{
								$users['active'][] = $listD;
							}
							else
							{
								$users['noactive'][] = $listD;
							}
						}
					}
				}
				
				$users['taskActive'] = Array();
				$users['taskOver'] = Array();
				$users['taskDeadline'] = Array();
				$users['taskAssigned'] = Array();

				foreach($users['user']['tasks'] as $task)
				{
					switch ($task['state'])
					{
						case 1:
							$users['taskActive'][] = $task;
							break;
						case 2:
							$users['taskOver'][] = $task;
							break;
						case 3:
							$users['taskDeadline'][] = $task;
							break;
					}

					if($task['author'] == $id && $task['state'] != 0)
					{
						$users['taskAssigned'][] = $task;
					}
				}

				$data = Array(
					'profile' => $profile,
					);

				$submenu = Array(
					'<span class="all-my">Все</span> | <a href="' . base_url('deal/my_deal') . '">Мои</a>',
				);
				
				$sidebar = Array(
					'subMenu' => $submenu,
					'location' => 'users'
				);

				//Загружаем все в шаблон
				$this->load->view('admin/header', $header);
				$this->load->view('admin/showuser', $users);
				$this->load->view('admin/sidebar_menu', $sidebar);
				$this->load->view('admin/footer');
			}
			else
			{
				redirect(base_url(), 'refresh');
			}
		}
	}

	public function deal()
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
			
			//Проверим на админство
			if( $profile['group_id'] == 1 )
			{
				//Если все заебись то выполняем код

				//header
				$header = Array(
					'header' => '#C25B5B', 
					'title_h1' => 'Настройки сделок', 
					'index' => base_url('admin'), 
					'place' => 'deal',
					'profile' => $profile
				);
					
				//Производим выборку статусов сделок
				$stateDeal = $this->admin_md->getStateDeal();
				//sidebar_submenu
				//$sidebar_menu = Array('sidebar' => $sidebar);
									
				$data = Array(
					'profile' => $profile,
					'stateDeal' => $stateDeal
					);

				$submenu = Array(
					'<span class="all-my">Все</span> | <a href="' . base_url('deal/my_deal') . '">Мои</a>',
				);
				
				$sidebar = Array(
					'subMenu' => $submenu,
					'location' => 'users'
				);

				//Загружаем все в шаблон
				$this->load->view('admin/header', $header);
				$this->load->view('admin/deal', $data);
				$this->load->view('admin/sidebar_menu', $sidebar);
				$this->load->view('admin/footer');
			}
			else
			{
				redirect(base_url(), 'refresh');
			}
		}
	}
}