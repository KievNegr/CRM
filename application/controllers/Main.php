<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Main extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->helper('directory');
		$this->load->library('ion_auth');
		$this->load->library('session');
		$this->load->model('deal_md');
		$this->load->model('main_md');
		$this->load->model('task_md');
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
			
			//Получаем весь список состояния сделок
			$state_deal = $this->deal_md->get_list_deal();
			
			//Выберем статусы для показа (активные короче говоря state_deal( active => 1 ) )
			foreach( $state_deal as $setGetDeal )
			{
				if( $setGetDeal['active'] == 1 )
				{
					$selectActive[] = $setGetDeal['id'];
				}
			}

			//Полезли в базу фильтровать сделки
			$get_deal = $this->deal_md->get_deal( $selectActive, 'my', $profile['id'] );
			
			$headerMenu = Array(
				'index' => 'class="active"',
				'deals' => '',
				'clients' => '',  //Текущее глобальное местоположение
				'tasks' => ''
			);

			$data = Array(
				'profile' => $profile,
				'deals' => $get_deal,
				'state_deal' => $state_deal,
				'headerMenu' => $headerMenu
				);
			
			//Выбираем задачи для пользователя
			//$task = $this->task_md->get_my_task( $profile['id'] );
			$over_task = $this->task_md->get_over_task( $profile['id'] );

			$subMenu = Array();

			$sidebar = Array(
				'subMenu' => $subMenu,
				'location' => 'home'
				);
			
			$this->load->view('header', $data);
			$this->load->view('main', $data);
			$this->load->view('sidebar', $sidebar);
			//$this->load->view('sidebar_submenu', $sidebar);	
			$this->load->view('footer', $data);
		}
	}

	public function ggg()
	{
		print_r($this->main_md->get_email('1,8,10'));
	}

	public function loadInfo()
	{
		//Загрузка данных профиля
		$profile = $this->ion_auth->profile();
		$profile = json_encode($profile, true);
		$profile = json_decode($profile, true);
		
		//Узнаем количество сделок
		$get_deal = $this->deal_md->get_my_deal( $profile['id'] );
		//Проверим статусы задач
		$this->task_md->update_stat();
		//Активные задачи
		$active_task = $this->task_md->get_my_task( $profile['id'] );
		//Выполненные задачи
		$finish_task = $this->task_md->get_finish_task( $profile['id'] );
		//Просроченные задачи
		$over_task = $this->task_md->get_over_task( $profile['id'] );
		//Добавленных компаний
		$company = $this->clients_md->get_profile_company( $profile['id'] );
		//Добавленных контактных лиц
		$clients = $this->clients_md->get_profile_clients( $profile['id'] );

		echo '<p class="ajax_deals">Сделок: ' . count($get_deal) . '</p>';
		echo '<p class="ajax_active">Активных задач: ' . count($active_task) . '</p>';
		echo '<p class="ajax_finish">Выполненных задач: ' . count($finish_task) . '</p>';
		echo '<p class="ajax_over">Просроченных задач: ' . count($over_task) . '</p>';
		echo '<p class="ajax_company">Компаний: ' . count($company) . '</p>';
		echo '<p class="ajax_clients">Контактных лиц: ' . count($clients) . '</p>';
	}

	//Редактирование профиля
	public function edit_profile()
	{
		//Загрузка данных профиля
		$profile = $this->ion_auth->profile();
		$profile = json_encode($profile, true);
		$profile = json_decode($profile, true);

		echo $this->main_md->edit_profile( $profile['id'] );
	}

	//Загрузка аватара
	public function upload_avatar()
	{
		//Загрузка данных профиля
		$profile = $this->ion_auth->profile();
		$profile = json_encode($profile, true);
		$profile = json_decode($profile, true);

		$max_filesize = 2097152; // Maximum filesize in BYTES.
		 $allowed_filetypes = array('.jpg','.jpeg','.gif','.png'); // These will be the types of file that will pass the validation.
		 $filename = $_FILES['userfile']['name']; // Get the name of the file (including file extension).
		 $ext = strtolower(substr($filename, strpos($filename,'.'), strlen($filename)-1)); // Get the extension from the filename.
		 $file_strip = date('YmdHis') . $ext; //str_replace(" ","_",$filename); //Strip out spaces in filename
		 $upload_path = $_SERVER['DOCUMENT_ROOT'] . '/img/avatars/'; //Set upload path
		 
		 // Check if the filetype is allowed, if not DIE and inform the user.
		if(!in_array($ext,$allowed_filetypes)) {
		 	die('<div class="error">The file you attempted to upload is not allowed.</div>');
		}
		// Now check the filesize, if it is too large then DIE and inform the user.
		if(filesize($_FILES['userfile']['tmp_name']) > $max_filesize) {
			die('<div class="error">The file you attempted to upload is too large.</div>');
		}
		// Check if we can upload to the specified path, if not DIE and inform the user.
		if(!is_writable($upload_path)) {
			 die('<div class="error">You cannot upload to the /uploads/ folder. The permissions must be changed.</div>');
		}
		 // Move the file if eveything checks out.
		if(move_uploaded_file($_FILES['userfile']['tmp_name'],$upload_path . $file_strip)) {
			$config['image_library'] = 'gd2';
			$config['source_image']	= $_SERVER['DOCUMENT_ROOT'] . '/img/avatars/' . $file_strip;
			$config['create_thumb'] = FALSE;
			$config['maintain_ratio'] = FALSE;
			$config['width'] = 90;
			$config['height'] = 90;
			$this->load->library('image_lib', $config); 
			$this->image_lib->resize();
			$this->main_md->edit_avatar( $profile['id'], $file_strip );
			echo $file_strip;
		} 
		else 
		{
			echo '<div class="error">'. $file_strip .' was not uploaded.  Please try again.</div>'; // It failed :(.
		}

		//echo 'aa';
	}

	//схранение отредактированого примечания
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

	//Удаление события
	public function dellEvent()
	{
		echo $this->main_md->dellEvent();
	}

	//Сохранение ответа для примечания
	public function saveResponse()
	{
		$id_deal = $this->input->post('id_deal');

		$sub_id = $this->input->post('sub_id');
		$text = $this->input->post('text');
		$id_task = $this->input->post('id_task');
		$arr = $this->main_md->saveResponse($id_deal, $sub_id, $id_task, $text);

		$sub_data = 'Сегодня в ' . substr($arr['date'], 11, 2) . ':' . substr($arr['date'], 14, 2);

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
		//echo '<p>' . $temp_out . '</p>';

		$text = '<div class="sub_event subevent' . $arr['id_event'] . '">
					<div class="functional-sub-event-deal">
						<div class="edit-sub-event" id_event="' . $arr['id_event'] . '"></div>
						<div class="del-sub-event" id_event="' . $arr['id_event'] . '"></div>
						</div>
					<p class="human author">'. $arr['author'] . ', ' . $sub_data . '</p>
					<p class="p-' . $arr['id_event'] . ' p-edit-response">' . $temp_out . '</p>
					<div class="save-edit-response-' . $arr['id_event'] . ' hide" style="margin: 0 0 10px 3%;">
						<textarea class="response_edit_text edit-event-' . $arr['id_event'] . '" style="display: block; margin: 5px 0 5px 0;">' . $temp_out . '</textarea>
						<div style="clear: both;"></div>
						<button class="edit-save-response" id_save="' . $arr['id_event'] . '">Сохранить</button>
						<button class="edit-cancel-response">Отмена</button>
					</div>
				 </div>';

		$res = Array('sub_id' => $sub_id, 'text' => $text);
		echo json_encode($res);
	}
}