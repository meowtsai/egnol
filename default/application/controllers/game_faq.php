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

	function get_faq_list($site)
	{

		$this->load->library("Parsedown");
    $Parsedown = new Parsedown();


		$ip = $_SERVER['REMOTE_ADDR'];
		$priority = 1;
		if ($ip=="61.220.44.200")
		{
			$priority=0;
		}

		$query = $this->db->query("
		select faq.id,faq.start_time, title,content, type_id,priority
		from faq inner join faq_types on faq.id = faq_types.faq_id
		inner join faq_games on faq_games.faq_id=faq.id
		where game_id ='{$site}'
		and priority >= {$priority} and now() between start_time and end_time order by priority desc,start_time desc
		;");




		$data = array();
		foreach($query->result() as $row) {
			$data[] = array(
				'id' => $row->id,
				'title' => $row->title,
				'content' => $Parsedown->text($row->content),
				'date' =>  date("Y/m/d", strtotime($row->start_time)),
				'type_id' =>  $row->type_id,
				'priority' =>  $row->priority,

			);
		}

		header('Access-Control-Allow-Origin: *');
		die(json_encode($data));
	}
}
