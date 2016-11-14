<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{
	var $game_id;
	var $game_name;
	var $global_dir;
	
	function __construct() 
	{
		parent::__construct();
		
		$this->game_id = 'vxz';
		$this->game_name = '小李飛刀';
		
		$this->global_dir = BASEPATH.'../global/';	
		$this->load->add_package_path($this->global_dir);
		$this->load->helper("g_common");
		$this->load->library(array("g_user"));

		$this->load->database(g_conf("db", "database"));
	}
	
	function _init_layout()
	{				
		$this->load->library("g_layout");

		if ($this->g_user->is_login())
		{
			$recent_server = $this->db->select("g.name as game_name, gi.name as server_name, gi.server_id")
				->from("log_game_logins lgl")
				->join("servers gi","lgl.server_id=gi.server_id")
				->join("games g","g.game_id=gi.game_id")
				->where("lgl.uid", $this->g_user->uid)->where("is_recent", "1")->order_by("lgl.id desc")->limit(3)->get();
		}
		else
		{
			$recent_server = false;
		}

		$this->g_layout->set("recent_server", $recent_server);

		$this->server = $this->_get_server();

		$this->g_layout->set("site", $this->game_id);
        $this->g_layout->set("game_url", "https://".$this->game_id.".longeplay.com.tw/");
        $this->g_layout->set("longe_url", g_conf('url', 'longe'));

		$redirect_url = urldecode($this->input->get("redirect_url", true));
		$this->g_layout->set("redirect_url", $redirect_url);

		// 設定粉絲專頁
		$fan_page = "https://facebook.com";
		$query = $this->db->from("games")->where("game_id", $this->game_id)->get();
		if($query->num_rows() > 0)
		{
			$this->game_name = $query->row()->name;
			$fan_page = $query->row()->fanpage;
			if(empty($fan_page))
				$fan_page = "not set!";
		}
		$this->g_layout->set("fan_page", $fan_page);

		return $this->g_layout
			//->add_js_include(array('jquery.validate.min', 'jquery.metadata', 'jquery.form'))
			->set_meta("title", "絕代雙驕玩家獨享元寶活動")
			->set_meta("keywords", "小李飛刀,小李飛刀手機版,小李飛刀手遊,小李飛刀3D手遊,古龍,武俠,經典,小說,飛刀現江湖,躺著也中刀,飛刀,熱門手機遊戲,網易,龍邑,李尋歡,阿飛, PVP,合擊,Q版,小李他媽的飛刀,兵器譜,林仙兒,九陰真經,射鵰英雄傳3D,倚天屠龍記,RPG,角色扮演,動作遊戲,ARPG,動作RPG,策略遊戲,跨服對戰,情緣合擊")
			->set_meta("description", " 3D情義新動作武俠手遊《小李飛刀》！華人武俠世界的巨擘古龍再掀手機遊戲新浪潮。兵器譜排行高手-李尋歡、上官金虹、天機老人、藍蠍子，與其他知名英雄阿飛、林仙兒、孫小紅等人，各自為了情義、名利和權勢而引發了一場江湖腥風血雨…");
	}
	
	function _get_server()
	{
		$query = $this->db->where("game_id", $this->game_id)->get("servers");
		
		$new = false;
		foreach($query->result() as $row)
		{
			if ($row->is_new_server == "1")
			{
				$new = $row;
				break;
			}
		}
		
		return array("new" => $new, "list" => $query->result());
	}

	// 檢查並要求登入
	function _require_login()
	{
		$redirect_url = $this->input->get("redirect_url") ? $this->input->get("redirect_url", true) : "";

		return $this->g_user->require_login($this->game_id, $redirect_url);
	}
}
