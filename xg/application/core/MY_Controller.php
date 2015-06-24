<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Controller extends CI_Controller {
	
	var $game, $global_dir;
	
	function __construct() 
	{
		parent::__construct();
		
		$this->game = 'xg';
		
		$this->global_dir = BASEPATH.'../global/';	
		$this->load->add_package_path($this->global_dir);
		$this->load->helper("g_common");
		$this->load->library(array("g_user", "FirePHP", "Fb"));
		
		$this->load->database('long_e');
	}
	
	function _init_layout()
	{				
		$this->load->library("g_layout");
		$this->g_layout
			->set("img_url", $this->g_layout->img_url())
			->add_js_include(array('jquery.blockUI', 'jquery.validate.min', 'jquery.form', 'jquery.url', 'default'))
			//->add_js_include("jquery.slideshow.lite")->add_css_link('jquery-slideshow')
			->set_meta("title", "《仙境三國》龍邑遊戲_繁體中文網站")
			->set_meta("keywords", "仙境三國")
			->set_meta("description", "《仙境三國》");
		
		if ($this->g_user->loginCheck()) {
			$recent_server = $this->db->from("log_game_logins lgl")->join("servers gi","lgl.server_id=gi.server_id")
				->where("gi.game_id", $this->game)->where("account", $this->g_user->account)->where("is_recent", "1")->order_by("create_time desc")->limit(3)->get();
		} else $recent_server = false;
		$this->g_layout->set("recent_server", $recent_server);		
						
		$this->server = $this->_get_server();		
	}
	
	function _get_server()
	{
		$query = $this->db->where("game_id", $this->game)->get("servers");
		
		$new = false;
		foreach($query->result() as $row) {
			if ($row->is_new_server == "1") {
				$new = $row;
				break;
			}
		}
		
		return array("new" => $new, "list" => $query->result());
	}

}

function chk_server_open($row)
{		
	if ($row->server_status == 'maintaining' &&  ! IN_OFFICE) {
		if ( ! IN_OFFICE) {
			return "javascript:alert('{$row->maintaining_msg}');";
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

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */