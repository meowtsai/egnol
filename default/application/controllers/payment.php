<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Payment extends MY_Controller {	
	
	function _init_payment_layout()
	{
		$this->_init_layout();		
		return $this->g_layout
			->set("subtitle", "儲值")
			->set("submenu", "payment")
			->set_breadcrumb(array("儲值"=>"payment"));	
	}
	
	function index()
	{		
		$this->g_user->check_login('long_e', true); 	
		$this->g_user->check_account_channel('trade'); //導儲值通道
		
		if (strstr($this->g_user->account, '@rc2')) {			
			echo '<script type="text/javascript">document.write("RC大廳帳號請由RC大廳上進行儲值"); alert("RC大廳帳號請由RC大廳上進行儲值"); history.back(-1);</script>';
			exit();
		}		
				
		$this->load->config("g_gash");
		$games = $this->db->from("games")->where("is_active", "1")->get();
		$servers = $this->db->order_by("id")->get("servers");	

		$this->_init_payment_layout()	
			->set("submenu", "payment")
			->set("subtitle", "儲值")
			->set("games", $games)
			->set("servers", $servers)
			->add_js_include("payment/index")
			->render("", "inner");
	}	
	
	function guide()
	{
		$this->_init_payment_layout()
			->add_breadcrumb("教學","payment/guide")
			->set("subtitle", "儲值教學")
			->render("", "inner2");			
	}
	
	function guide_t1()
	{
		$this->_init_payment_layout()
			->add_breadcrumb("教學","payment/guide")
			->set("subtitle", "儲值教學")
			->render("", "inner2");			
	}	
	
	function guide_t2()
	{
		$this->_init_payment_layout()
			->add_breadcrumb("教學","payment/guide")
			->set("subtitle", "儲值教學")
			->render("", "inner2");			
	}		
	
	function result()
	{
		$this->_init_payment_layout()
			->set("status", $this->input->get("status"))
			->set("message", urldecode($this->input->get("message")));
		
		if (check_mobile()) {
			$this->g_layout->render("", "mobile");	
		}
		else $this->g_layout->render("", "inner");		
	}
	
	/*function choose()
	{
		$this->g_user->check_login($site='', true); 
		
		$f = file_get_contents(site_url("mycard/get_payment"));
		$price = json_decode($f); 
		
		$this->load->library("g_wallet");
				
		$this->_init_layout();
		$this->g_layout
			->set_breadcrumb(array("儲值"=>"payment", "MyCard 購點"=>""))
			//->set("layout_tmp", '<img src="/PayFun/img/service-icon-f.gif" width="171" height="25" border="0" usemap="#Map" />')
			->set("remain", $this->g_wallet->get_balance($this->g_user->uid))
			->set("price", $price)
			->add_js_include("payment/choose")
			->render();				
	}*/

	
	function m_index()
	{
		$this->g_user->check_login('', true);
			
		$this->_init_payment_layout()
			->set("sid", $this->input->get("sid"))	
			->set("game", $this->input->get("game"))
			->render("", "mobile");
	}
	
	function m_long_e_index()
	{
		$this->g_user->check_login('', true);
			
		$this->_init_payment_layout()
			->set("sid", $this->input->get("sid"))	
			->set("game", $this->input->get("game"))
			->render("", "mobile");
	}
	
	function m_ios_index()
	{
		$this->g_user->check_login('', true);
			
		$partner = $this->input->get("partner");
		$game = $this->input->get("game");
		
		$this->load->config("api");
		$partner_api = $this->config->item('partner_api');
		
		if (empty($partner) || empty($game)) die("參數錯誤");
		
		$products = $partner_api[$partner]['sites'][$game]['ios']['products'];		
		
		$this->_init_payment_layout()
			->set("sid", $this->input->get("sid"))
			->set("game", $this->input->get("game"))
			->set("products", $products)	
			->render("payment/m_ios_choose", "mobile");
	}
	
	function m_google_index()
	{
		$this->g_user->check_login('', true);
			
		$this->_init_payment_layout()
			->set("sid", $this->input->get("sid"))
			->set("game", $this->input->get("game"))	
			->render("", "mobile");
	}
	
// 	function m_index2()
// 	{
// 		$this->g_user->check_login('', true);
				
// 		$this->_init_payment_layout()
// 			->set("sid", $this->input->get("sid"))	
// 			->set("game", $this->input->get("game"))
// 			->render("", "mobile");
// 	}
	
	function m_choose()
	{
		$this->g_user->check_login('', true);
		
		$type = $this->input->get("type");
		$url = "http://www.long_e.com.tw/mycard/get_product/".urlencode($type);
		$data = json_decode(file_get_contents($url));
				
		$this->_init_payment_layout()
			->set("type", urldecode($type))
			->set("data", $data)	
			->set("sid", $this->input->get("sid"))
			->set("game", $this->input->get("game"))
			->render("", "mobile");	
	}
	
	function m_choose2()
	{
		$this->load->config("g_gash");
		$this->g_user->check_login('', true);
		
		$type = $this->input->get("type");				
		$this->_init_payment_layout()
			->set("type", urldecode($type))
			->set("game", $this->input->get("game"))
			->render("", "mobile");	
	}
	
	function m_choose3()
	{
		$this->load->config("g_pepay");
		$this->g_user->check_login('', true);
		
		$type = $this->input->get("type");				
		$this->_init_payment_layout()
			->set("type", urldecode($type))
			->set("game", $this->input->get("game"))
			->render("", "mobile");	
	}
	
	function m_google_choose()
	{
		$this->g_user->check_login('', true);
				
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
				
		$this->_init_payment_layout()
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
