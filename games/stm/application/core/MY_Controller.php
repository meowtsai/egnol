<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{
	var $game_id;
	var $game_name;
	var $global_dir;
	
	function __construct() 
	{
		parent::__construct();
		
		$this->game_id = 'stm';
		$this->game_name = '';
		
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
        $this->g_layout->set("game_url", "/games/".$this->game_id."/");
        $this->g_layout->set("longe_url", g_conf('url', 'longe'));

		$redirect_url = urldecode($this->input->get("redirect_url", true));
		$this->g_layout->set("redirect_url", $redirect_url);

		// 設定名稱與粉絲專頁
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
			->add_js_include(array('jquery.validate.min', 'jquery.metadata', 'jquery.form'))
			->set_meta("title", "《{$this->game_name}》龍邑遊戲_繁體中文網站")
			->set_meta("keywords", "{$this->game_name}")
			->set_meta("description", "《{$this->game_name}》");
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
/*
function chk_server_open($row)
{		
	if ($row->server_status == 'maintenance' &&  ! IN_OFFICE) {
		if ( ! IN_OFFICE) {
			return "javascript:alert('{$row->maintenance_msg}');";
		}
	} 
	else if ($row->server_status == 'private' &&  ! IN_OFFICE) {
		return false;
	}
	else if ($row->server_status == 'hide') {
		return false;
	}
	
	return 'http://www.longeplay.com.tw/play_game?sid='.$row->id.'&ad='.@$_GET['ad'];
}
*/