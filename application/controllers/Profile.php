<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Profile extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->library('ion_auth');
		$this->load->library('session');
		$this->load->helper('url');
	}
	
	public function index()
	{
		$data = $this->ion_auth->profile();
		/*echo '<ul>';
		foreach( $data as $key => $list)
		{
			echo '<li>' . $key . ' => ' . $list . '</li>';
		}
		echo '</ul>';*/
		
		$data1 = json_encode($data, true);
		$data2 = json_decode($data1, true);
		var_dump($data2);
	}
}