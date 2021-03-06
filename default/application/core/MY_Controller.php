<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{
	var $global_dir;

	function __construct()
	{
		parent::__construct();

		$this->global_dir = BASEPATH.'../global/';
		$this->load->add_package_path($this->global_dir);
		$this->load->helper("g_common");
		$this->load->library(array("g_user"));

		$this->load->database('long_e');
	}

	function _init_layout($title="龍邑遊戲‧LongE Play")
	{
		$this->load->library("g_layout");
		
		/*if ($this->g_user->is_login())
		{
			$recent_server = $this->db->select("g.name as game_name, gi.name as server_name, gi.server_id")
				->from("log_game_logins lgl")
				->join("servers gi","lgl.server_id=gi.server_id")
				->join("games g","g.game_id=gi.game_id")
				->where("lgl.uid", $this->g_user->uid)->where("is_recent", "1")->order_by("lgl.id desc")->limit(3)->get();
		}
		else */$recent_server = false;

		$this->g_layout->set("recent_server", $recent_server);

		// 取出指定的 site, 沒有指定的話就是 long_e
		$site = $this->_get_site();
		$this->g_layout->set("site", $site);
        $this->g_layout->set("game_url", ($site == "long_e" ? g_conf('url', 'longe') : "https://".$site.".longeplay.com.tw/"));
        $this->g_layout->set("longe_url", g_conf('url', 'longe'));
        $this->g_layout->set("api_url", g_conf('url', 'api'));
		$this->g_layout->set("is_system_page", true);

		$redirect_url = urldecode($this->input->get("redirect_url", true));
		$this->g_layout->set("redirect_url", $redirect_url);

		// 設定粉絲專頁
		$fan_page = "https://facebook.com";
		if($site != "long_e")
		{
			$query = $this->db->from("games")->where("game_id", $site)->get();
			if($query->num_rows() > 0)
			{
				$fan_page = $query->row()->fanpage;
				if(empty($fan_page))
					$fan_page = "not set!";
			}
		}
		$this->g_layout->set("fan_page", $fan_page);

		return $this->g_layout
			->add_js_include(array('jquery-1.7.2.min', 'jquery.validate.min', 'jquery.metadata', 'jquery.form', 'default'))
			->set_meta("title", $title);
	}

	// 檢查並要求登入
	function _require_login()
	{
		$redirect_url = $this->input->get("redirect_url") ? $this->input->get("redirect_url", true) : "";

		return $this->g_user->require_login($this->_get_site(), $redirect_url);
	}

	// 取得目前的遊戲
	function _get_site()
	{
		return $this->input->get("site") ? $this->input->get("site", true) : "long_e";
	}
}
