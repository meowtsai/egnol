<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

define("RESPONSE_OK", "1");
define("RESPONSE_FAILD", "0");

//
// 會員系統廠商協作功能 API
//  - ui 前置的 function 為有提供 web 畫面的 API
//  - 裡面有執行 Javascript 的 LongeAPI.* function 為呼叫 SDK 的串接功能
//
class Api extends MY_Controller
{
	var $partner_conf;
	
	var $partner, $game, $time, $hash, $key;
	
	function __construct()
	{
		parent::__construct();
		$this->load->config('api');		
		$this->partner_conf = $this->config->item("partner_api");
	}

	function _output_json($result, $err="", $arr=array())
	{
		$output_arr = array("result" => $result, "error" => $err);
		if ( ! empty($arr))
		{
			$output_arr = array_merge($output_arr, $arr);
		}

		die(json_encode($output_arr));
	}

	// 帳號登入
	// - 帳號功能主要入口, 其他 ui 前置 function 由 web 呼叫
	function ui_login()
	{
		if(!$this->g_user->is_login())
		{
			// 未登入, 直接進入登入畫面
			$device_id = $this->input->get("deviceid");

			// 載入第三方登入通道種類
			$this->load->config("api");
			$channel_api = $this->config->item("channel_api");
			$channel_item = array();
			foreach($channel_api as $key => $channel)
			{
				$channel['channel'] = $key;
				array_push($channel_item, $channel);
			}

			$this->_init_layout()
				->add_js_include("api/login")
				->set("device_id", $device_id)
				->set("channel_item", $channel_item)
				->api_view();
		}
		else
		{
			// 已登入, 改顯示會員畫面
			$this->_init_layout()
				->api_view("api/ui_member");
		}
	}

	function ui_login_json()
	{
		header('content-type:text/html; charset=utf-8');

		$site = $this->_get_site();

		$_SESSION['site'] = $site;

		// 檢查 e-mail or mobile
		$account = $this->input->post("account");
		if(empty($account))
		{
			die(json_failure('電子郵件或行動電話未填寫'));
		}

		$pwd = $this->input->post("pwd");
		if (empty($pwd))
		{
			die(json_failure('密碼尚未填寫'));
		}

		$email = '';
		$mobile = '';
		if(filter_var($account, FILTER_VALIDATE_EMAIL))
		{
			$email = $account;
		}
		else
		{
			$mobile = $account;
		}

		if ( $this->g_user->verify_account($email, $mobile, $pwd) === true )
		{
			$res = array("uid" => $this->g_user->uid,
						"email" => !empty($this->g_user->email) ? $this->g_user->email : "",
						"mobile" => !empty($this->g_user->mobile) ? $this->g_user->mobile : "",
						"externalId" => !empty($this->g_user->external_id) ? $this->g_user->external_id : "",
						"site" => $site);
			die(json_message($res, true));
		}
		else
		{
			die(json_failure($this->g_user->error_message));
		}
	}

	// 直接登入
	function ui_quick_login()
	{
		//header('Content-type:text/html; Charset=UTF-8');

        $site = $this->input->get('site');
        $device_id = $this->input->get('deviceid');
		$external_id = "mobile.".$device_id;

		$boolResult = $this->g_user->verify_account('', '', '', $external_id);
		if ($boolResult != true)
		{
			$boolResult = $this->g_user->create_account('', '', '', $external_id);
		}

		if ($boolResult==true)
		{
			$this->g_user->verify_account('', '', '', $external_id);
			echo "<script type='text/javascript'>location.href='/api/ui_login?site={$site}';</script>";
		}
		else
		{
			echo "<script type='text/javascript'>alert('登入失敗!');location.href='/api/ui_login?site={$site}';</script>";
		}
	}

	// 帳號登出
	function ui_logout()
	{
		$this->g_user->logout();

		header('Content-type:text/html; Charset=UTF-8');
		echo "<script type='text/javascript'>LongeAPI.onLogoutSuccess()</script>";
	}

	// 帳號註冊
	function ui_register()
	{
		$this->_init_layout()
			->add_js_include("api/register")
			->api_view();
	}

	function ui_register_json()
	{
		header('content-type:text/html; charset=utf-8');

        $site = $this->input->get('site');
		$email = $this->input->post('email');
		$mobile = $this->input->post("mobile");
		$pwd = $this->input->post("pwd");
		$pwd2 = $this->input->post("pwd2");
		$captcha = $this->input->post('captcha');

		if ( empty($email) && empty($mobile) )
		{
			die(json_failure("電子郵件和行動電話至少需填寫一項"));
		}
		else if (empty($pwd) )
		{
			die(json_failure("請輸入密碼"));
		}
		else if ($pwd != $pwd2)
		{
			die(json_failure("兩次密碼輸入不相同"));
		}
		else if (empty($_SESSION['captcha']) || $captcha != $_SESSION['captcha'])
		{
			die(json_failure("驗證碼錯誤"));
		}

		$boolResult = $this->g_user->create_account($email, $mobile, $pwd);
		if ($boolResult==true)
		{
			$this->g_user->verify_account($email, $mobile, $pwd);
			die(json_message(array("message"=>"成功", "site"=>$site), true));
		}
		else
		{
			die(json_failure($this->g_user->error_message));
		}
	}

	// 帳號綁定
	function ui_bind_account()
	{
		$this->_init_layout()
			->add_js_include("api/bind_account")
			->api_view();
	}

	function ui_bind_account_json()
	{
		$this->_check_login_json();

        $site = $this->input->get('site');
		$email = $this->input->post("email");
		$mobile = $this->input->post("mobile");
		$pwd = $this->input->post("pwd");
		$pwd2 = $this->input->post("pwd2");

		if ( empty($email) && empty($mobile) )
		{
			die(json_failure("電子信箱和手機號碼至少需輸入一項"));
		}
		else if ( empty($pwd) )
		{
			die(json_failure("請輸入密碼"));
		}
		else if ($pwd != $pwd2)
		{
			die(json_failure("兩次密碼輸入不同"));
		}

		$result = $this->g_user->bind_account($this->g_user->uid, $email, $mobile, $pwd);
		if ($result == true)
		{
			$this->g_user->verify_account($email, $mobile, $pwd);
			die(json_message(array("message"=>"成功", "site"=>$site)));
		}
		else
		{
			die(json_failure($this->g_user->error_message));
		}
	}

	// 忘記密碼
	function ui_forgot_password()
	{
		$this->_init_layout()
			->add_js_include("api/forgot_password")
			->api_view();
	}

	function ui_reset_password_json()
	{
		header('content-type:text/html; charset=utf-8');

		$account = $this->input->post("account");
		$captcha = $this->input->post("captcha");

		if (empty($_SESSION['captcha']) || $captcha != $_SESSION['captcha'])
		{
			die(json_failure("驗證碼錯誤"));
		}

		// 檢查 e-mail or mobile
		if(empty($account))
		{
			die(json_failure('Email或手機號碼未填寫'));
		}

		$query = null;
		$send_to = 'email 信箱';
		if(filter_var($account, FILTER_VALIDATE_EMAIL))
		{
			$query = $this->db->from("users")->where("email", $account)->get();
		}
		else
		{
			$query = $this->db->from("users")->where("mobile", $account)->get();
			$send_to = '手機簡訊';
		}

		if (empty($query) || $query->num_rows() == 0)
		{
			die(json_failure("沒有這位使用者。"));
		}

	    $new = rand(100000, 999999);
	    $md5_new = md5($new);

	    $this->db->where("uid", $query->row()->uid)->update("users", array("password" => $md5_new));
/*
		$this->load->library("long_e_mailer");
		$this->long_e_mailer->passwdResetMail($email, $account, $new, $account);
*/
		die(json_success("新密碼已發送到您的".$send_to."。"));
	}

	// 修改密碼
	function ui_change_password()
	{
		$this->_require_login();

		$this->_init_layout()
			->api_view();
	}

	function ui_change_password_json()
	{
		$this->_check_login_json();

		$pwd = $this->input->post("pwd");
		$pwd2 = $this->input->post("pwd2");

		if ( empty($pwd) ) die(json_failure("請輸入密碼"));
		else if ($pwd != $pwd2) die(json_failure("兩次密碼輸入不同"));

		if(empty($this->g_user->email) && empty($this->g_user->mobile))
		{
              die(json_failure("尚未綁定帳號"));
		}

		$this->db->where("uid", $this->g_user->uid)->update("users", array("password" => md5(trim($pwd))));
		die(json_message(array("message"=>"修改成功")));
	}

	// 點數儲值
	function ui_payment()
	{
		$this->_require_login();

		$this->load->config("g_gash");

		// 讀取遊戲列表
		$games = $this->db->from("games")->where("is_active", "1")->get();
		// 讀取伺服器列表
		$servers = $this->db->order_by("id")->get("servers");
		// 讀取玩家角色列表
		$characters = $this->db->from("characters")->where("uid", $this->g_user->uid)->get();

		$this->_init_layout()
			->set("games", $games)
			->set("servers", $servers)
			->set("characters", $characters)
			->add_js_include("api/payment")
			->api_view();
	}

	// 建立遊戲角色
	function create_role()
	{
		$uid = $this->input->get("uid");
		$euid = $this->input->get("euid");
		$game = $this->input->get("game");
		$server = $this->input->get("server");
		$character_name = urldecode($this->input->get("character_name"));
		$time = $this->input->get("time");
		$hash = $this->input->get("hash");

		if ((empty($uid) && empty($euid)) || (empty($server) && empty($game)) || empty($time))
		{
			die(json_encode(array("result"=>"0", "error"=>"參數錯誤")));
		}

		if (empty($uid) && $euid)
		{
			$uid = $this->g_user->decode($euid);
			$md5_p1 = $euid;
		}

		$query = $this->db->from("users")->where("uid", $uid)->get();
		if ($query->num_rows() > 0)
		{
			$account = $query->row()->account;
		}
		else
		{
			die(json_encode(array("result"=>"0", "error"=>"uid不存在")));
		}

		if (empty($md5_p1))
		{
			$md5_p1 = $uid;
		}

		$this->load->model("games");
		if ($game)
		{
			$md5_p2 = $game.$server;
			$server_id = "{$game}_".sprintf("%02d", $server);
			$server_info = $this->db->from("servers")->where("server_id", "{$server_id}")->get()->row();
		}
		else
		{
			$md5_p2 = $server;
			$server_info = $this->games->get_server_by_address($server);
		}
		if (empty($server_info))
		{
			die(json_encode(array("result"=>"0", "error"=>"伺服器不存在")));
		}

		$game_api = $this->config->item("game_api");
		$key = $game_api[$server_info->game_id]['key'];

		if ($hash <> md5($md5_p1 . $md5_p2 . $character_name . $time . $key))
		{
			die(json_encode(array("result"=>"0", "error"=>"認證碼錯誤")));
		}

		$this->load->model("g_characters");

		if ($this->g_characters->chk_role_exists($server_info, $uid, $character_name))
		{
			die(json_encode(array("result"=>"0", "error"=>"角色已存在")));
		}

		$insert_id = $this->g_characters->create_role($server_info,
			array(
				"uid" => $uid,
				'character_name' => $character_name,
			));

		if (empty($insert_id))
		{
			die(json_encode(array("result"=>"0", "error"=>"資料庫新增錯誤")));
		}

		echo json_encode(array("result"=>"1"));
		exit();
	}

	// 取得遊戲角色狀態
	function get_role_status()
	{
		$partner = $this->input->get("partner");
		$uid = $this->input->get("uid");
		$game = $this->input->get("game");
		$server = $this->input->get("server");
		$time = $this->input->get("time");
		$hash = $this->input->get("hash");

		// 檢查參數
		if (empty($partner) || empty($uid) || empty($game) || empty($server) || empty($time))
		{
			die(json_encode(array("result"=>"0", "error"=>"參數錯誤")));
		}

		$partner_conf = $this->config->item("partner_api");
		if ( ! array_key_exists($partner, $partner_conf))
		{
			die(json_encode(array("result"=>"0", "error"=>"無串接此partner")));
		}
		if ( ! array_key_exists($game, $partner_conf[$partner]["sites"]))
		{
			die(json_encode(array("result"=>"0", "error"=>"無串接此遊戲")));
		}
		$key = $partner_conf[$partner]["sites"][$game]['key'];
		if ($hash <> md5($partner . $uid . $game . $server . $time . $key))
		{
			die(json_encode(array("result"=>"0", "error"=>"認證碼錯誤")));
		}

		$server_id = "{$game}_".sprintf("%02d", $server);
		$server_row = $this->db->from("servers")->where("server_id", "{$server_id}")->get()->row();
		if (empty($server_row))
		{
			die(json_encode(array("result"=>"0", "error"=>"無此伺服器")));
		}

		// 登入帳號
		$query = $this->db->where("uid", $uid)->get("users");
		if($query->num_rows() == 0)
		{
			die(json_encode(array("result"=>"0", "error"=>"無此帳號")));
		}

		$user_row = $query->row();
		if ($this->g_user->verify_account($user_row->email, $user_row->mobile))
		{
			/*
			$this->load->library("game_api/{$server_row->game_id}");
			$re = $this->{$server_row->game_id}->check_role_status($server_row, $user_row);
			if ($re == "1")
			{
				die(json_encode(array("result"=>"1")));
			}
			else if ($re === "-1")
			{
				die(json_encode(array("result"=>"-1", "error"=>"遊戲伺服器無回應")));
			}
			else
			{
				die(json_encode(array("result"=>"0", "error"=>"該帳號無角色")));
			}
			*/
		}
		else
		{
			die(json_encode(array("result"=>"0", "error"=>$this->g_user->error_message)));
		}
	}

	// 儲值後自動執行轉點, 玩家不用自行操作
	function _transfer()
	{
		$partner = $this->input->get("partner");
		$uid = $this->input->get("uid");
		$game = $this->input->get("game");
		$server = $this->input->get("server");
		$order = $this->input->get("order");
		$money = $this->input->get("money");
		$time = $this->input->get("time");
		$hash = $this->input->get("hash");

		//s1.檢查參數
		if (empty($partner) || empty($uid) || empty($game) || empty($server) || empty($order) || empty($money) || empty($time))
		{
			die(json_encode(array("result"=>"0", "error"=>"參數錯誤")));
		}
		$partner_conf = $this->config->item("partner_api");
		if ( ! array_key_exists($partner, $partner_conf))
		{
			die(json_encode(array("result"=>"0", "error"=>"無串接此partner")));
		}
		if ( ! array_key_exists($game, $partner_conf[$partner]["sites"]))
		{
			die(json_encode(array("result"=>"0", "error"=>"無串接此遊戲")));
		}
		$key = $partner_conf[$partner]["sites"][$game]['key'];
		if ($hash <> md5($partner . $uid . $game . $server . $order .  $money . $time . $key))
		{
			die(json_encode(array("result"=>"0", "error"=>"認證碼錯誤")));
		}

		$server_id = "{$game}_".sprintf("%02d", $server);
		$server_row = $this->db->from("servers")->where("server_id", "{$server_id}")->get()->row();
		if (empty($server_row))
		{
			die(json_encode(array("result"=>"0", "error"=>"無此伺服器")));
		}
		if ($money < 0)
		{
			die(json_encode(array("result"=>"0", "error"=>"金額設定錯誤")));
		}

		//開ip
		$pass_ips = array();
    	if (isset($partner_conf[$partner]["ips"]))
		{
    		$pass_ips = array_merge($pass_ips, $partner_conf[$partner]["ips"]);
    	}
    	$pass = in_array($_SERVER["REMOTE_ADDR"], $pass_ips);

		//s2.檢查帳號
		$user_row = $this->g_user->get_user_data($uid);
		if(empty($user_row))
		{
			 die(json_encode(array("result"=>"0", "error"=>"帳號不存在")));
		}
		if ( ! $this->g_user->verify_account($user_row->email, $user_row->mobile, '', $user_row->external_id))
		{
			 die(json_encode(array("result"=>"0", "error"=>"帳號不存在")));
		}

		//s3.檢查訂單狀況
		$chk_billing = $this->db->from("user_billing ub")->where("order", $order)->where("transaction_type", "{$partner}_billing")->get()->row();
		if ( ! empty($chk_billing))
		{
			//if ($chk_billing->result <> "0")
			die(json_encode(array("result"=>"-2", "error"=>"該訂單已結案")));
		}

		//s4.轉進遊戲
		$this->load->helper("transfer");
		$this->load->model("games");
		$game = $this->games->get_game($server_row->game_id);

		if ( $server_row->is_transaction_active == 0 && ! (IN_OFFICE || $pass) )
		{
			die(json_encode(array("result"=>"0", "error"=>'遊戲伺服器目前暫停轉點服務，詳情請參閱遊戲官網公告。')));
    	}

		//s4-1.建單
		$this->load->library("g_wallet");
		$billing_id = $this->g_wallet->produce_order($this->g_user->uid, "{$partner}_billing", "2", $money, $server_row->server_id, $order);
		if (empty($billing_id)) {
			die(json_encode(array("result"=>"0", "error"=>$this->g_wallet->error_message)));
		}
		$order_row = $this->g_wallet->get_order($billing_id);

		//s4-2.轉入
		$this->load->library("game_api/{$server_row->game_id}");
		$re = $this->{$server_row->game_id}->transfer($server_row, $order_row, $game->exchange_rate);

		//s4-3.回傳結果
		if ($re === "1") {
			$this->g_wallet->complete_order($order_row);
			die(json_encode(array("result"=>"1", "order_id"=>$order_row->id)));
		}
		else if ($re === "-1") {
			$this->g_wallet->cancel_timeout_order($order_row);
			die(json_encode(array("result"=>"-1", "error"=>"遊戲伺服器無回應")));
		}
		else {
			$error_message = $this->{$server_row->game_id}->error_message;
			$this->g_wallet->cancel_order($order_row, $error_message);
			die(json_encode(array("result"=>"0", "error"=>$error_message)));
		}
		/*
		$partner = $this->input->get("partner");
		$uid = $this->input->get("uid");
		$game = $this->input->get("game");
		$server = $this->input->get("server");
		$order = $this->input->get("order");
		$money = $this->input->get("money");
		$time = $this->input->get("time");
		$hash = $this->input->get("hash");
		
		//s1.檢查參數
		if (empty($partner) || empty($uid) || empty($game) || empty($server) || empty($order) || empty($money) || empty($time)) {
			die(json_encode(array("result"=>"0", "error"=>"參數錯誤")));
		}
		$partner_conf = $this->config->item("partner_api");
		if ( ! array_key_exists($partner, $partner_conf)) {
			die(json_encode(array("result"=>"0", "error"=>"無串接此partner")));
		}
		if ( ! array_key_exists($game, $partner_conf[$partner]["sites"])) {
			die(json_encode(array("result"=>"0", "error"=>"無串接此遊戲")));
		}
		$key = $partner_conf[$partner]["sites"][$game]['key'];		
		if ($hash <> md5($partner . $uid . $game . $server . $order .  $money . $time . $key)) {
			die(json_encode(array("result"=>"0", "error"=>"認證碼錯誤")));
		}	
		if ($game == 'xj') {
			$server_id = $game.intval($server);
		}
		else if ($game == 'sg2') {
			$server_id = "{$game}_".sprintf("%02d", ($server+1));
		}
		else {
			$server_id = "{$game}_".sprintf("%02d", $server);
		}					
		$server_row = $this->db->from("servers")->where("server_id", "{$server_id}")->get()->row();
		if (empty($server_row)) {
			die(json_encode(array("result"=>"0", "error"=>"無此伺服器")));
		}
		if ($money < 0) {
			die(json_encode(array("result"=>"0", "error"=>"金額設定錯誤")));
		}		
		
		//開ip
		$pass_ips = array();    	
    	if (isset($partner_conf[$partner]["ips"])) {
    		$pass_ips = array_merge($pass_ips, $partner_conf[$partner]["ips"]);
    	}
    	$pass = in_array($_SERVER["REMOTE_ADDR"], $pass_ips);
		
		//s2.檢查帳號
		$account = "{$uid}@{$partner}";
		if ( ! $this->g_user->verify_account($account)) {
			 die(json_encode(array("result"=>"0", "error"=>"帳號不存在")));
		}
		
		//s3.檢查訂單狀況
		$chk_billing = $this->db->from("user_billing ub")->where("order", $order)->where("transaction_type", "{$partner}_billing")->get()->row();
		if ( ! empty($chk_billing)) {
			//if ($chk_billing->result <> "0") 
			die(json_encode(array("result"=>"-2", "error"=>"該訂單已結案")));
		}
		
		//s4.轉進遊戲
		$this->load->helper("transfer");		
		$this->load->model("games");
		$game = $this->games->get_game($server_row->game_id);		    			
		
		if ( $server_row->is_transaction_active == 0 && ! (IN_OFFICE || $pass) ) {
			die(json_encode(array("result"=>"0", "error"=>'遊戲伺服器目前暫停轉點服務，詳情請參閱遊戲官網公告。')));
    	}

		//s4-1.建單
		$this->load->library("g_wallet");
		$billing_id = $this->g_wallet->produce_order($this->g_user->uid, "{$partner}_billing", "2", $money, $server_row->server_id, $order);
		if (empty($billing_id)) {
			die(json_encode(array("result"=>"0", "error"=>$this->g_wallet->error_message)));
		}
		$order_row = $this->g_wallet->get_order($billing_id);
		
		//s4-2.轉入		
		$this->load->library("game_api/{$server_row->game_id}");		
		$re = $this->{$server_row->game_id}->transfer($server_row, $order_row, $game->exchange_rate);
		
		//s4-3.回傳結果	
		if ($re === "1") {
			$this->g_wallet->complete_order($order_row);
			die(json_encode(array("result"=>"1", "order_id"=>$order_row->id)));
		}
		else if ($re === "-1") {
			$this->g_wallet->cancel_timeout_order($order_row);			
			die(json_encode(array("result"=>"-1", "error"=>"遊戲伺服器無回應")));	
		}
		else {
			$error_message = $this->{$server_row->game_id}->error_message;
			$this->g_wallet->cancel_order($order_row, $error_message);			
			die(json_encode(array("result"=>"0", "error"=>$error_message)));		
		}
		*/
	}

	function _chk_partner()
	{
		$this->partner = $this->input->get_post("partner");
		$this->game = $this->input->get_post("game");		
		$this->time = $this->input->get_post("time");
		$this->hash = $this->input->get_post("hash");
		
		if (empty($this->partner) || empty($this->game) || empty($this->time) || empty($this->hash)) 
			$this->_output_json(RESPONSE_FAILD, "缺少參數");
		
		if ( ! array_key_exists($this->partner, $this->partner_conf)) 
			$this->_output_json(RESPONSE_FAILD, "無串接此partner");
		
		if ( ! array_key_exists($this->game, $this->partner_conf[$this->partner]["sites"])) 
			$this->_output_json(RESPONSE_FAILD, "無串接此遊戲");
		
		$this->key = $this->partner_conf[$this->partner]["sites"][$this->game]['key'];
	}	
}
