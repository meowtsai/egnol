<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Platform extends MY_Controller {
	
	function __construct()
	{
		parent::__construct();
	}
		
	function index()
	{		
		$this->_init_layout()->standard_view();
	}
	
	function top_bar()
	{
		$game_a = $this->db->from("games")->like("tags", "即時")->where("is_active", "1")->order_by("rank")->get();
		$game_b = $this->db->from("games")->like("tags", "策略")->where("is_active", "1")->order_by("rank")->get();
		$game_c = $this->db->from("games")->like("tags", "回合")->where("is_active", "1")->order_by("rank")->get();
		$game_d = $this->db->from("games")->like("tags", "其它")->where("is_active", "1")->order_by("rank")->get();
		$game_e = $this->db->from("games")->like("tags", "手遊")->where("is_active", "1")->order_by("rank")->get();

		//header("Content-Type: application/json");
		
		$skybar_banner = $this->db->from("skybar_banners")->where("enable", "1")->order_by("rand()")->get()->row();
		
		$html = $this->load->view("platform/top_bar", array("game_a"=>$game_a, "game_b"=>$game_b, "game_c"=>$game_c, "game_d"=>$game_d, "game_e"=>$game_e, "skybar_banner"=>$skybar_banner), true);
		echo $this->input->get('callback').'('.json_encode(array("html"=>$html)).')';
	}
	
	function game_list()
	{
		$game_a = $this->db->from("games")->like("tags", "即時")->where("is_active", "1")->order_by("rank")->get();
		$game_b = $this->db->from("games")->like("tags", "策略")->where("is_active", "1")->order_by("rank")->get();
		$game_c = $this->db->from("games")->like("tags", "回合")->where("is_active", "1")->order_by("rank")->get();
		$game_d = $this->db->from("games")->like("tags", "其它")->where("is_active", "1")->order_by("rank")->get();
		$game_e = $this->db->from("games")->like("tags", "手遊")->where("is_active", "1")->order_by("rank")->get();
		
		$html = $this->load->view("platform/game_list", array("game_a"=>$game_a, "game_b"=>$game_b, "game_c"=>$game_c, "game_d"=>$game_d, "game_e"=>$game_e), true);
		echo "<html>";
		echo $html;
		echo "</html>";
		exit();
		
	}
	
	function inner()
	{
		$this->_init_layout()
			->render("", "inner");
	}
	
	function aboutus()
	{
		$this->_init_layout()
			->set_breadcrumb(array("關於我們"=>""))
			->set("subtitle", "關於我們")
			->render("", "inner2");
	}
	
	function privacy()
	{
		$this->_init_layout()
			->set_breadcrumb(array("隱私權保護"=>""))
			->set("subtitle", "隱私權保護")
			->render("", "inner2");
	}
	
	function member_rule()
	{
		$this->_init_layout();
		
		if (check_mobile()) {
			$this->g_layout->css_link = array("default");
			$this->g_layout					
				->render("", "mobile");	
		}
		else {
			$this->g_layout
				->set_breadcrumb(array("會員條款"=>""))
				->set("subtitle", "會員條款")
				->render("", "inner2");
		}
	}
		
	function game_rule()
	{
		$this->_init_layout()
			->set_breadcrumb(array("遊戲使用條款"=>""))
			->set("subtitle", "遊戲使用條款")
			->set("submenu", "service")
			->render("", "inner2");		
	}
}