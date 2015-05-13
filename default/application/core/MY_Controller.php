<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

	var $global_dir;

	function __construct()
	{
		parent::__construct();

		$this->global_dir = BASEPATH.'../global/';
		$this->load->add_package_path($this->global_dir);
		$this->load->helper("g_common");
		//$this->load->library(array("g_user", "FirePHP", "Fb"));
		$this->load->library(array("g_user"));

		$this->load->database('long_e');
		
		$this->game_id = $this->input->get("game_id");
	}

	function _init_layout()
	{
		$this->load->library("g_layout");
		
		if ($this->g_user->check_login()) {
			$recent_server = $this->db->select("g.name as game_name, gi.name as server_name, gi.id")
				->from("log_game_logins lgl")
				->join("servers gi","lgl.server_id=gi.id")
				->join("games g","g.game_id=gi.game_id")
				->where("lgl.uid", $this->g_user->uid)->where("is_recent", "1")->order_by("lgl.id desc")->limit(3)->get();
		} else $recent_server = false;
		$this->g_layout->set("recent_server", $recent_server);
		
		return $this->g_layout
			->add_js_include(array('jquery.validate.min', 'jquery.metadata', 'jquery.form', 'jquery.blockUI', 'jquery.easing.1.3', 'jquery-navAnimation', 'default'))
			->set_meta("title", "::: 龍邑遊戲 ‧ LongE Games :::");
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */