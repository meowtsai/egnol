<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//
// 網站會員系統付費功能
//
class Payment extends MY_Controller
{
	// 儲值中心主頁面
	//	選擇遊戲、伺服器和儲值管道
	function index_old()
	{
		$this->_require_login();

		$site = $this->_get_site();

		$_SESSION['site'] = $site;

		$this->load->config("g_gash");
		$this->load->config("g_payment_gash");

		// 讀取遊戲列表
		$games = $this->db->from("games")->where("is_active", "1")->get();
		// 讀取伺服器列表
		$servers = $this->db->where("is_transaction_active", "1")->order_by("server_id")->get("servers");
		// 讀取玩家角色列表
		$characters = $this->db->from("characters")->where("uid", $this->g_user->uid)->get();

		$this->_init_layout()
			->set("games", $games)
			->set("servers", $servers)
			->set("characters", $characters)
			->add_css_link("login")
			->add_css_link("money")
			->add_js_include("payment/index_old")
			->standard_view();
	}

	function index()
	{
		redirect('/', 'location', 301);
		$this->_require_login();

		$site = $this->_get_site();
		$set_money = $this->input->get_post("money");

		$_SESSION['site'] = $site;

		$this->load->config("g_payment");
		$this->load->config("g_mycard");
		$this->load->config("g_funapp");

		// 讀取遊戲列表
		$games = $this->db->from("games")->where("is_active", "1")->where("game_id", "vxz")->get();

		// 讀取伺服器列表
		$servers = $this->db->where("is_transaction_active", "1")->order_by("server_id")->get("servers");
		// 讀取玩家角色列表
		$characters = $this->db->from("characters")->where("uid", $this->g_user->uid)->get();

		$this->_init_layout()
			->set("games", $games)
			->set("servers", $servers)
			->set("characters", $characters)
			->set("set_money", $set_money)
			->add_css_link("login")
			->add_css_link("money")
			->add_js_include("payment/index")
			->standard_view();
	}

	function result()
	{
		if(empty($_SESSION['site']))
		{
            die("儲值錯誤");
		}

		$site				= $_SESSION['site'];
		$payment_game		= $_SESSION['payment_game'];
		$payment_server		= $_SESSION['payment_server'];
		$payment_character	= $_SESSION['payment_character'];
		$payment_type		= $_SESSION['payment_type'];
		$payment_channel	= $_SESSION['payment_channel'];

		$_SESSION['site']				= '';
		$_SESSION['payment_game']		= '';
		$_SESSION['payment_server']		= '';
		$_SESSION['payment_character']	= '';
		$_SESSION['payment_type']		= '';
		$_SESSION['payment_channel']	= '';
		unset($_SESSION['site']);
		unset($_SESSION['payment_game']);
		unset($_SESSION['payment_server']);
		unset($_SESSION['payment_character']);
		unset($_SESSION['payment_type']);
		unset($_SESSION['payment_channel']);

		// 讀取遊戲資料
		$game = $this->db->from("games")->where("game_id", $payment_game)->get()->row();
		// 讀取伺服器資料
		$server = $this->db->from("servers")->where("server_id", $payment_server)->get()->row();
		// 讀取玩家角色資料
		$character = $this->db->from("characters")->where("id", $payment_character)->get()->row();

		$this->_init_layout()
			->set("site", $site)
			->set("game", $game)
			->set("server", $server)
			->set("character", $character)
			->set("billing_type", $payment_type)
			->set("pay_type", $payment_channel)
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

		$this->load->config("g_api");
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

		$this->load->config("g_api");
		$this->partner_conf = $this->config->item("partner_api");

		$partner = $this->input->get("partner");
		$game = $this->input->get("game");

		$this->load->config("g_api");
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

	function update_payment_disable_list()
	{
        $this->load->config("g_payment_gash");

		if ($this->input->post('disable_list') && $this->input->ip_address()==$this->config->item("payment_backend_ip"))
		{
            $disable_list = $this->input->post('disable_list');
			unset($post['submit']);

            $filename = "./p/payment_disable_list";

            unlink($filename);
            $fp = fopen($filename, 'w');
            fwrite($fp, $disable_list);
            fclose($fp);
		}
	}
}
