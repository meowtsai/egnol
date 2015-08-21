<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//
// 網站會員系統付費功能
//
class Payment extends MY_Controller
{
	// 儲值中心主頁面
	//	選擇遊戲、伺服器和儲值管道
	function index()
	{		
		$this->_require_login();

		$this->load->config("g_gash");
		$this->load->config("payment");

		// 讀取遊戲列表
		$games = $this->db->from("games")->where("is_active", "1")->get();
		// 讀取伺服器列表
		$servers = $this->db->order_by("server_id")->get("servers");
		// 讀取玩家角色列表
		$characters = $this->db->from("characters")->where("uid", $this->g_user->uid)->get();

		$this->_init_layout()
			->set("games", $games)
			->set("servers", $servers)
			->set("characters", $characters)
			->add_css_link("login")
			->add_css_link("money")
			->add_js_include("payment/index")
			->standard_view();
	}	

	function result()
	{
		$this->_init_layout()
			->set("status", $this->input->get("status"))
			->set("message", urldecode($this->input->get("message")))
			->add_css_link("login")
			->add_css_link("money")
			->standard_view();
	}

	function m_index()
	{
		$this->_require_login();
			
		$this->_init_layout()
			->set("sid", $this->input->get("sid"))	
			->set("game", $this->input->get("game"))
			->render("", "mobile");
	}
	
	function m_long_e_index()
	{
		$this->_require_login();
			
		$this->_init_layout()
			->set("sid", $this->input->get("sid"))	
			->set("game", $this->input->get("game"))
			->render("", "mobile");
	}
	
	function m_ios_index()
	{
		$this->_require_login();
			
		$partner = $this->input->get("partner");
		$game = $this->input->get("game");
		
		$this->load->config("api");
		$partner_api = $this->config->item('partner_api');
		
		if (empty($partner) || empty($game)) die("參數錯誤");
		
		$products = $partner_api[$partner]['sites'][$game]['ios']['products'];		
		
		$this->_init_layout()
			->set("sid", $this->input->get("sid"))
			->set("game", $this->input->get("game"))
			->set("products", $products)	
			->render("payment/m_ios_choose", "mobile");
	}
	
	function m_google_index()
	{
		$this->_require_login();
			
		$this->_init_layout()
			->set("sid", $this->input->get("sid"))
			->set("game", $this->input->get("game"))	
			->render("", "mobile");
	}
	
// 	function m_index2()
// 	{
// 		$this->_require_login();
				
// 		$this->_init_layout()
// 			->set("sid", $this->input->get("sid"))	
// 			->set("game", $this->input->get("game"))
// 			->render("", "mobile");
// 	}

	function m_choose2()
	{
		$this->load->config("g_gash");
		$this->_require_login();
		
		$type = $this->input->get("type");				
		$this->_init_layout()
			->set("type", urldecode($type))
			->set("game", $this->input->get("game"))
			->render("", "mobile");	
	}
	
	function m_choose3()
	{
		$this->load->config("g_pepay");
		$this->_require_login();
		
		$type = $this->input->get("type");				
		$this->_init_layout()
			->set("type", urldecode($type))
			->set("game", $this->input->get("game"))
			->render("", "mobile");	
	}
	
	function m_google_choose()
	{
		$this->_require_login();
				
		$this->load->config('api');		
		$this->partner_conf = $this->config->item("partner_api");
		
		$partner = $this->input->get("partner");
		$game = $this->input->get("game");
		
		$this->load->config("api");
		$partner_api = $this->config->item('partner_api');
		
		if (empty($partner) || empty($game)) die("參數錯誤");
		
		if (empty($partner_api[$partner]['sites'][$game]['iab']['products']))
			$products = $partner_api["google_iab_products"];
		else $products = $partner_api[$partner]['sites'][$game]['iab']['products'];
				
		$this->_init_layout()
			->set("products", $products)	
			->set("sid", $this->input->get("sid"))
			->set("game", $this->input->get("game"))
			->render("", "mobile");	
	}
	
	function digi_dalent()
	{
		//product=wd&server=S12
		header('location:'.site_url("payment?game=".$this->input->get("product")));
	}
}
