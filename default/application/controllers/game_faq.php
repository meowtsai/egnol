<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Game_faq extends MY_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->config("service");
	}

	// function index()
	// {
	// 	$site = $this->_get_site();
	// 	$this->_init_layout('hello world')
	// 		->view("game_faq/index");
	// }

	function index()
	{
		$type = $this->input->get_post("type") ? $this->input->get_post("type") : 0;

		$this->load->model("g_bulletins");
		$this->load->library('pagination');
		$site = "long_e";
		$data['news']= $this->g_bulletins->get_list($site, $type, 7, $this->input->get("record"));
		$data['page_title'] = 'Your title';
		$this->load->view('game_faq/header');
		$this->load->view('game_faq/menu');
		$this->load->view('game_faq/index', $data);
		$this->load->view('game_faq/footer');
	}

}
