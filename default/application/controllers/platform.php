<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Platform extends MY_Controller
{
	function __construct()
	{
		parent::__construct();
	}



	function get_games_list()
	{
		$query = $this->db->query("SELECT game_id, name,logo_path,bg_path,rank,fanpage,site  FROM games WHERE is_active=1");
		$data = array();
		foreach($query->result() as $row) {
			$data[] = array(
				'game_id' => $row->game_id,
				'name' => $row->name,
				'logo_path' => $row->logo_path,
				'bg_path' => $row->bg_path,
				'fanpage' => $row->fanpage,
				'site' => $row->site,
			);
		}

		header('Access-Control-Allow-Origin: *');
		die(json_encode($data));
	}

	function index()
	{
		$user_ip = $_SERVER['REMOTE_ADDR'];

			$this->db->from("games")->where("is_active", "1");
			if ($user_ip=="61.220.44.200")
			{
				$this->db->or_where("is_active", "2");
			}

			$games = $this->db->get();
			$this->_init_layout()
			->set("games", $games)
			->g_2018_view("platform/home");


	}


	function index2019()
	{
		$user_ip = $_SERVER['REMOTE_ADDR'];

			$this->db->from("games")->where("is_active", "1");
			if ($user_ip=="61.220.44.200")
			{
				$this->db->or_where("is_active", "2");
			}

			$games = $this->db->get();
			$this->_init_layout()
			->set("games", $games)
			->g_2019_view("platform/index2019");


	}
	function support()
	{
		$user_ip = $_SERVER['REMOTE_ADDR'];

			$this->db->from("games")->where("is_active", "1");
			if ($user_ip=="61.220.44.200")
			{
				$this->db->or_where("is_active", "2");
			}

			$games = $this->db->get();
			$this->_init_layout()
			->set("games", $games)
			->g_2019_view("platform/support");


	}
}
