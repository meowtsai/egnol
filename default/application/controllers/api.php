<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

define("RESPONSE_OK", "1");
define("RESPONSE_FAILD", "0");

//
// 會員系統廠商協作功能 API
//  - ui 前置的 function 為有提供 web 畫面的 API
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
		// 取出 GET 參數
		$account = urldecode($this->input->get("account", true));

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
			->set("account", $account)
			->set("channel_item", $channel_item)
			->api_view();
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
			die(json_message(array("message"=>"成功", "site"=>$site), true));
		}
		else
		{
			die(json_failure($this->g_user->error_message));
		}
	}

	// 帳號登出
	function ui_logout()
	{
		$this->g_user->logout();

		header('Content-type:text/html; Charset=UTF-8');
		echo "<script type='text/javascript'>alert('成功登出系統'); </script>";
		echo "<script type='text/javascript'>history.back();</script>";
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

        $site = 'long_e';
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

		$boolResult = $this->g_user->create_account($email, $mobile, $pwd, $site);
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
	// API 的綁定頁面直接包含登入功能, 和網頁版不同
	function ui_bind_account()
	{
		$this->_init_layout()
			->add_js_include("api/bind_account")
			->api_view();
	}

	function ui_bind_account_json()
	{
		$this->_check_login_json();

		$email = $this->input->post("email");
		$mobile = $this->input->post("mobile");
		$pwd = $this->input->post("pwd");
		$pwd2 = $this->input->post("pwd2");
		$redirect_url = $this->input->post("redirect_url");

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
			die(json_message(array("message"=>"成功", "redirect_url"=>$redirect_url)));
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
		$account = $this->input->post("account");
		$email = $this->input->post("email");
		$captcha = $this->input->post("captcha");

		header('content-type:text/html; charset=utf-8');
		if ( empty($account) || empty($email) ) {
			die(json_failure("尚有資料未填"));
		}
		else if (empty($_SESSION['captcha']) || $captcha != $_SESSION['captcha']) {
			die(json_failure("驗證碼錯誤"));
		}

		$cnt = $this->db->from("users")->where("account", $account)->where("email", $email)->count_all_results();
		if ($cnt == 0) {
			die(json_failure("沒有這位使用者或mail填寫錯誤。"));
		}

	    $new = rand(100000, 999999);
	    $md5_new = md5($new);

	    $this->db->where("account", $account)->update("users", array("password" => $md5_new));
/*
		$this->load->library("long_e_mailer");
		$this->long_e_mailer->passwdResetMail($email, $account, $new, $account);
*/
		die(json_success("帳號及新密碼已發送到信箱."));
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
		$redirect_url = $this->input->post("redirect_url");

		if ( empty($pwd) ) die(json_failure("請輸入密碼"));
		else if ($pwd != $pwd2) die(json_failure("兩次密碼輸入不同"));

		if ($this->g_user->is_from_3rd_party())
		{
			$row = $this->g_user->get_user_data($this->g_user->uid);
			if(empty($row->email) && empty($row->mobile))
			{
                die(json_failure("尚未綁定帳號"));
			}
		}

		$this->db->where("uid", $this->g_user->uid)->update("users", array("password" => md5(trim($pwd))));
		die(json_message(array("message"=>"修改成功", "back_url"=>site_url("member/index"))));
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

	// 帳號登入
	function login()
	{
		header('P3P:CP="IDC DSP COR ADM DEVi TAIi PSA PSD IVAi IVDi CONi HIS OUR IND CNT"');

		$partner = $this->input->post("partner");
		$uid = $this->input->post("uid");
		$email = $this->input->post("email");
		$mobile = $this->input->post("mobile");
		$external_id = $this->input->post("external_id");
		$game = $this->input->post("game");
		$server = $this->input->post("server");
		$time = $this->input->post("time");
		$hash = $this->input->post("hash");

		// 檢查參數
		if (empty($partner) || empty($uid) || empty($game) || empty($time))
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
		if ( ! empty($server))
		{
			$server_id = "{$game}_".sprintf("%02d", $server);
			$server_row = $this->db->from("servers")->where("server_id", $server_id)->get()->row();
			if (empty($server_row))
			{
				die(json_encode(array("result"=>"0", "error"=>"無此伺服器")));
			}
		}

		// 登入帳號
		if(!empty($uid))
		{
			$query = $this->db->where("uid", $uid)->get("users");
			if($query->num_rows() > 0)
			{
				$row = $query->row();
				$email = $row->email;
				$mobile = $row->mobile;
				$external_id = $row->external_id;
			}
		}

		$password = rand(100000, 999999);

		if ($this->g_user->login($email, $mobile, $password, $game, $external_id))
		{
			$account = $this->g_user->email;
			if(empty($account))
				$account = $this->g_user->mobile;
			if(empty($account))
				$account = $this->g_user->external_id;

			$user = (object)array("uid"=>$this->g_user->uid, "euid"=>$this->g_user->euid, "account"=>$account);

			//s3.登入遊戲
			$this->load->library("game");
			$re = $this->game->login($server_row, $user, $this->input->post("ad"));
			if ($re == false)
			{
				die(json_encode(array("result"=>"0", "error"=>$this->game->error_message)));
			}
		}
		else
		{
			die(json_encode(array("result"=>"0", "error"=>"帳號連結失敗：".$this->g_user->error_message)));
		}
	}

	// 帳號登出
	function logout()
	{
		$this->g_user->logout();
	}

	// 點數儲值
	function save_point()
	{
		// 儲值與轉點合併, 玩家不會知道有轉點這件事

		// 先執行儲值流程
		//

		// 儲值成功後自動轉點
		_transfer();
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
/*
	function login_game()
	{
		header('P3P:CP="IDC DSP COR ADM DEVi TAIi PSA PSD IVAi IVDi CONi HIS OUR IND CNT"');
		
		$partner = $this->input->get("partner");
		$uid = $this->input->get("uid");
		$game = $this->input->get("game");
		$server = $this->input->get("server");
		$time = $this->input->get("time");
		$hash = $this->input->get("hash");

		//s1.檢查參數
		if (empty($partner) || empty($uid) || empty($game) || empty($time)) {
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
		if ($hash <> md5($partner . $uid . $game . $server . $time . $key)) {
			die(json_encode(array("result"=>"0", "error"=>"認證碼錯誤")));
		}	
		if ( ! empty($server)) {
			if ($game == 'xj') {
				$server_id = $game.intval($server);
			}
			else if ($game == 'sg2') {
				$server_id = "{$game}_".sprintf("%02d", ($server+1));
			}
			else {
				$server_id = "{$game}_".sprintf("%02d", $server);
			}			
			$server_row = $this->db->from("servers")->where("server_id", $server_id)->get()->row();
			if (empty($server_row)) {
				die(json_encode(array("result"=>"0", "error"=>"無此伺服器")));
			}
		}
		
		//s2.建立&登入帳號
		$account = strtolower($uid).'@'.$partner;
		$password = rand(100000, 999999);
		$name = '';
		$email = '';
		
		if ($this->g_user->login($account, $password, $email, $name, $game)) 
		{
			if (empty($server)) {
				header("location: ".base_url()."/play_game/{$game}?url=http://{$game}.longeplay.com.tw/index.php?serverin=1");
				exit();
			}
			if (in_array($partner, array('artsy'))) { //使用我方遊戲bar
				header("location: ".base_url()."/play_game?sid={$server_row->id}");
				exit();
			}
			
			$user = (object)array("uid"=>$this->g_user->uid, "euid"=>$this->g_user->euid, "account"=>$this->g_user->account);
			
			//s3.登入遊戲
			$this->load->library("game");
			$re = $this->game->login($server_row, $user, $this->input->get("ad"));
			if ($re == false) {
				die(json_encode(array("result"=>"0", "error"=>$this->game->error_message)));
			}	
		}
		else {
			die(json_encode(array("result"=>"0", "error"=>"帳號連結失敗：".$this->g_user->error_message)));
		}
			
	} 

	function create_game_role()
	{
		$uid = $this->input->get("uid");
		$euid = $this->input->get("euid");
		$account = urldecode($this->input->get("account"));
		$game = $this->input->get("game");
		$server = $this->input->get("server");
		$ad = $this->input->get("ad");
		$character_name = urldecode($this->input->get("character_name"));
		$time = $this->input->get("time");
		$hash = $this->input->get("hash");

		if ((empty($uid) && empty($euid) && empty($account)) || (empty($server) && empty($game)) || empty($time)) {
			die(json_encode(array("result"=>"0", "error"=>"參數錯誤")));
		}
		
		if (empty($uid) && $euid) {
			$uid = $this->g_user->decode($euid);
			$md5_p1 = $euid;
		}		
		
		if ($uid)  {
			$query = $this->db->from("users")->where("uid", $uid)->get();
			if ($query->num_rows() > 0) {
				$account = $query->row()->account;
			} else die(json_encode(array("result"=>"0", "error"=>"uid不存在")));		

			if (empty($md5_p1)) {
				$md5_p1 = $uid;
			}
		}
		else {
			$query = $this->db->from("users")->where("account", $account)->get();
			if ($query->num_rows() > 0) {
				$uid = $query->row()->uid;
			} else die(json_encode(array("result"=>"0", "error"=>"account不存在")));		
			
			$md5_p1 = $account;
		}
		
		$this->load->model("games");
		if ($game) {
			$md5_p2 = $game.$server;
			if ($game == 'xj') {
				$server_id = $game.intval($server);
			}
			else $server_id = "{$game}_".sprintf("%02d", $server);			
			$server_info = $this->db->from("servers")->where("server_id", "{$server_id}")->get()->row();
		}
		else {
			$md5_p2 = $server;
			$server_info = $this->games->get_server_by_address($server);		
		}
		if (empty($server_info)) {
			die(json_encode(array("result"=>"0", "error"=>"伺服器不存在")));
		}		
		
		$game_api = $this->config->item("game_api");
		$key = $game_api[$server_info->game_id]['key'];

		if ($hash <> md5($md5_p1 . $md5_p2 . $ad . $character_name . $time . $key)) {
			die(json_encode(array("result"=>"0", "error"=>"認證碼錯誤")));
		}
		
		//--

		//log_message('error', "{$ad} {$server}");
		//-----------
		if ($ad == 'winwin' && ($server == 'bw1.longeplay.com.tw' || $server == 'bw2.longeplay.com.tw')) {			
			$row = $this->db->where("uid", $this->g_user->uid)->where("game", 'bw')->from("winwin_guids")->get()->row();
			//log_message('error', print_r($row, true));
			if ($row) {
				//log_message('error', "http://tracking1.aleadpay.com/Pixel/Advertiser/178/?cid={$row->guid}");
				$re = my_curl("http://tracking1.aleadpay.com/Pixel/Advertiser/178/?cid={$row->guid}");
				//log_message('error', "http://tracking1.aleadpay.com/Pixel/Advertiser/178/?cid={$row->guid} - {$re}");
			}
		}
		//--------------------	
		
		$this->load->model("g_characters");
		
		if ($this->g_characters->chk_role_exists($server_info, $uid, $character_name)) {
			die(json_encode(array("result"=>"0", "error"=>"角色已存在")));
		}		
		
		$insert_id = $this->g_characters->create_role($server_info, 
			array(
				"uid" => $uid,
				'account' => $account,
				'character_name' => $character_name,
				'ad' => $ad,
			));

		if (empty($insert_id)) {
			die(json_encode(array("result"=>"0", "error"=>"資料庫新增錯誤")));
		}
		
		echo json_encode(array("result"=>"1"));
		exit();		
	}

	function check_role_status()
	{		
		$partner = $this->input->get("partner");
		$uid = $this->input->get("uid");
		$game = $this->input->get("game");
		$server = $this->input->get("server");
		$time = $this->input->get("time");
		$hash = $this->input->get("hash");
		
		if ($game == 'xf') die(json_encode(array("result"=>"0", "error"=>"仙府尚未製作")));
		
		//s1.檢查參數
		if (empty($partner) || empty($uid) || empty($game) || empty($server) || empty($time)) {
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
		if ($hash <> md5($partner . $uid . $game . $server . $time . $key)) {
			die(json_encode(array("result"=>"0", "error"=>"認證碼錯誤")));
		}		
		if ($game == 'xj') {
			$server_id = $game.intval($server);
		}
		else {
			$server_id = "{$game}_".sprintf("%02d", $server);
		}
		$server_row = $this->db->from("servers")->where("server_id", "{$server_id}")->get()->row();
		if (empty($server_row)) {
			die(json_encode(array("result"=>"0", "error"=>"無此伺服器")));
		}
		
		//s2.建立&登入帳號
		$account = strtolower($uid).'@'.$partner;
		if ($this->g_user->verify_account($account))
		{
			$user_row = $this->db->where("account", $account)->get("users")->row();
			$user_row->account = $user_row->account;
			
			//s4-2.轉入		
			$this->load->library("game_api/{$server_row->game_id}");		
			$re = $this->{$server_row->game_id}->check_role_status($server_row, $user_row);
			if ($re == "1") {
				die(json_encode(array("result"=>"1")));
			}
			else if ($re === "-1") {	
				die(json_encode(array("result"=>"-1", "error"=>"遊戲伺服器無回應")));	
			}
			else {
				die(json_encode(array("result"=>"0", "error"=>"該帳號無角色")));		
			}
		}
		else {
			die(json_encode(array("result"=>"0", "error"=>$this->g_user->error_message)));
		}
			
	} 	
*/
	function m_login_form()
	{	
		$this->_chk_partner();
		
		$imei = $this->input->get_post("imei");
		$euid = $this->input->get_post("euid");
		
		if ($this->hash <> md5($this->partner . $this->game . $imei . $this->time . $this->key . $euid)) {
			$this->_output_json(RESPONSE_FAILD, "認證碼錯誤");
		}

		if ($euid) { //直接登入			
			$uid = $this->g_user->decode($euid);
			$this->g_user->switch_uid($uid);
			
			parse_str($_SERVER['QUERY_STRING'], $output);
			$output['euid'] = "";
			$output['hash'] = md5($this->partner . $this->game . $imei . $this->time . $this->key);
			header('location: '.base_url().'/api/m_login_form?'.http_build_query($output));
			exit();
		}
		
		if ( ! $this->g_user->is_login()) {
			$_SESSION['channel'] = 'long_e';
		}
		
		$log_imei = $this->input->get("i");
		$log_android_id = $this->input->get("a");
		
		if ($log_imei) $_SESSION['log_imei'] = $log_imei;
		if ($log_android_id) $_SESSION['log_android_id'] = $log_android_id;
		
		$redirect_url = urldecode($this->input->get("redirect_url", true));
		
		$this->load->config("api");
		$channel_api = $this->config->item("channel_api");
		$channel_item = array();
		foreach($channel_api as $key => $channel) {
			$channel['channel'] = $key;
			if (array_key_exists("sites", $channel)) {
				if (array_key_exists($this->game, $channel["sites"])) {					
					array_push($channel_item, $channel);				
				}
			}
			else {
				array_push($channel_item, $channel);
			}
		}

		$bind_account = false;
		
		if ($this->g_user->is_login() && $this->g_user->is_channel_account()) {
			$query = $this->db->from("users")->where("bind_uid", $this->g_user->uid)->get();
			if ($query->num_rows() > 0) {
				$bind_account = $query->row()->account;
			}
		}
				
		$this->_init_layout();
		$this->g_layout->css_link = array("default");
		$this->g_layout
			->set("imei", $imei)	
			->set("bind_account", $bind_account)
			->set("redirect_url", $redirect_url)	
			->set("channel_item", $channel_item)
			->render("member/m_login", "mobile");	
	}
	
	function m_login()
	{
		//log_message('error', 'login: '.print_r($this->input->post(), true));
		
		$this->_chk_partner();
		
		$account = $this->input->post("account");
		$pwd = $this->input->post("pwd");
		
		if (empty($account) || empty($pwd)) {
			$this->_output_json(RESPONSE_FAILD, "缺少參數");
		}
		
		if ($this->hash <> md5($this->partner . $this->game . $account . $pwd . $this->time . $this->key)) {
			$this->_output_json(RESPONSE_FAILD, "認證碼錯誤");
		}	
		
		if (!preg_match("/^[A-za-z0-9_]+$/", $account)) {
		//if (!ereg("^[A-za-z0-9_]+$", $account)) {
			$this->_output_json(RESPONSE_FAILD, "帳號不得包含特殊字元");
		}
			
		if ($this->g_user->verify_account($account, $pwd) === false) {
			$this->_output_json(RESPONSE_FAILD, $this->g_user->error_message);
		}		
		
		$server = $this->db->from("servers")->where("server_id", $this->game)->get()->row();
		if (empty($server)) $this->_output_json(RESPONSE_FAILD, "無此遊戲");
		$user = (object)array("uid"=>$this->g_user->uid, "euid"=>$this->g_user->euid, "account"=>$this->g_user->account);
		
		$this->load->library("game");
		if ($this->game->m_login($server, $user, "") === false) {
			$this->_output_json(RESPONSE_FAILD, $this->game->error_message);
		}
		
		$this->_output_json(RESPONSE_OK, "", array("euid" => $this->g_user->encode($_SESSION['user_id']), "token" => $_SESSION['token']));
	}
	
	function m_register()
	{
		//log_message('error', 'register: '.print_r($this->input->post(), true));
		
		$this->_chk_partner();
		
		$account = $this->input->post("account");
		$pwd = $this->input->post("pwd");
		
		if (empty($account) || empty($pwd)) {
			$this->_output_json(RESPONSE_FAILD, "缺少參數");
		}
		
		if ($this->hash <> md5($this->partner . $this->game . $account . $pwd . $this->time . $this->key)) {
			$this->_output_json(RESPONSE_FAILD, "認證碼錯誤");
		}	
		
		if (!preg_match("/^[a-z0-9_]+$/", $account)) {
		//if (!ereg("^[a-z0-9_]+$", $account)) {
			$this->_output_json(RESPONSE_FAILD, "帳號不得包含特殊字元及大寫字母");
		}
		
		$result = $this->g_user->create_account($account, $pwd);

		if ($result == RESPONSE_FAILD) {
			$this->_output_json(RESPONSE_FAILD, $this->g_user->error_message);
		}
			
		$this->g_user->verify_account($account, $pwd);
		$this->_output_json(RESPONSE_OK, "", array("euid" => $this->g_user->encode($_SESSION['user_id']), "token" => $_SESSION['token']));
	}

	function m_use_imei()
	{
		$this->_chk_partner();
				
		$imei = $this->input->get_post("imei");
		
		if (empty($imei)) {
			$this->_output_json(RESPONSE_FAILD, "缺少參數");
		}		
		if ($this->hash <> md5($this->partner . $this->game . $imei . $this->time . $this->key)) {
			$this->_output_json(RESPONSE_FAILD, "認證碼錯誤");
		}
		
		$redirect_url = urldecode($this->input->get("redirect_url", true));
		
		$account = strtolower($imei) . "@imei";		
		$password = rand(100000, 999999);
		$re = $this->g_user->login($account, $password, '', '', $this->game);
		if ($re == false) die($this->g_user->error_message);
		
		$_SESSION['channel'] = 'imei';
		header('location: '.$redirect_url);
	}		
	
	function m_login_fb()
	{
		//log_message('error', 'login_fb: '.print_r($this->input->post(), true));
		
		$this->_chk_partner();
				
		$fb_id = $this->input->post("fb_id");
		
		if (empty($fb_id)) {
			$this->_output_json(RESPONSE_FAILD, "缺少參數");
		}
		
		if ($this->hash <> md5($this->partner . $this->game . $fb_id . $this->time . $this->key)) {
			$this->_output_json(RESPONSE_FAILD, "認證碼錯誤");
		}		
		
		//登入or創建帳號
		$account = $fb_id . "@facebook";
		$password = rand(100000, 999999);
		$this->g_user->login($account, $password, '', '', $this->game);

		//登入遊戲 and log
		$server = $this->db->from("servers")->where("server_id", $this->game)->get()->row();
		if (empty($server)) $this->_output_json(RESPONSE_FAILD, "無此遊戲");
		$user = (object)array("uid"=>$this->g_user->uid, "euid"=>$this->g_user->euid, "account"=>$this->g_user->account);
		
		$this->load->library("game");
		if ($this->game->m_login($server, $user, "") === false) {
			$this->_output_json(RESPONSE_FAILD, $this->game->error_message);
		}
				
		$this->_output_json(RESPONSE_OK, "", array("euid" => $this->g_user->encode($_SESSION['user_id']), "token" => $_SESSION['token']));
	}	
	
	function m_get_iab_info()
	{
		//log_message('error', 'get_iab_info');
		//log_message('error', print_r($this->input->post(), true));
		
		$this->_chk_partner();
				
		if ($this->hash <> md5($this->partner . $this->game . $this->time . $this->key)) {
			$this->_output_json(RESPONSE_FAILD, "認證碼錯誤");
		}		

		$game_conf = $this->partner_conf[$this->partner]["sites"][$this->game];
		
		$response = array();
		if (array_key_exists("iab", $game_conf)) {
			$game_conf['iab']['key'] = base64_encode($game_conf['iab']['key']."Cz");
			$response["iab"] = $game_conf['iab'];
		}
		if (array_key_exists("facebook",$game_conf)) $response["facebook"] = $game_conf['facebook'];
		if (array_key_exists("tapjoy", $game_conf)) {
			$game_conf['tapjoy']['key'] = base64_encode($game_conf['tapjoy']['key']."TJ");
			$response["tapjoy"] = $game_conf['tapjoy'];
		}
		if (array_key_exists("inmobi", $game_conf)) $response["inmobi"] = $game_conf['inmobi'];

		if (array_key_exists("ios", $game_conf)) {
			$response["ios"] = $game_conf['ios'];
		}
		if (array_key_exists("google", $game_conf)) {
			$response["google"] = $game_conf['google'];
		}
		
		
		//log_message('error', print_r($response, true));
		
		$this->_output_json(RESPONSE_OK, "", $response);
	}	
	
	function m_get_long_e_info()
	{
		$this->m_get_iab_info();
	}

	function m_create_ios_billing()
	{
		//log_message('error', 'm_create_ios_billing: '.print_r($this->input->post(), true));
	
		$this->_chk_partner();
		
		$euid = $this->input->post("euid");
		$uid = $this->g_user->decode($euid);
		$server = $this->input->post("server");
		$transaction_state = $this->input->post("transaction_state");		
		$transaction_receipt = $this->input->post("transaction_receipt");				
		
		//驗證ios交易 "https://buy.itunes.apple.com/verifyReceipt"
		$url = 'https://sandbox.itunes.apple.com/verifyReceipt';
		$transaction_receipt = strtr($transaction_receipt, " ", "+");
		$receipt = json_encode(array("receipt-data" => $transaction_receipt));  
	
		$re = my_curl($url, $receipt, true);
		$json = json_decode($re);
								
		if ($json->status != "0") {
			log_message('error', "ios verifyReceipt return: {$json->status}");
			$this->_output_json(RESPONSE_FAILD, "ios verifyReceipt return: {$json->status}");
		}
		
		//log_message('error', print_r($json, true));
		if ( ! empty($json->receipt->in_app)) {
			$cnt = count($json->receipt->in_app);
			$in_app = $json->receipt->in_app[$cnt-1];
		}
		else $in_app = $json->receipt;
				
		$transaction_id = $in_app->transaction_id;
		$transaction_date = $in_app->purchase_date;
		$product_id = $in_app->product_id;
		$amount = $in_app->amount;		
				
		if ($this->hash <> md5($this->partner . $this->game . $server . $euid . $this->time . $transaction_state . $transaction_receipt . $this->key . $_SESSION['token'])) {
			log_message('error', 'm_create_ios_billing: 認證碼錯誤');
			$this->_output_json(RESPONSE_FAILD, "認證碼錯誤");
		}
				
		if ($server) {
			$row = $this->db->from("servers")->where("game_id", $this->game)->where("address", $server)->get()->row();
			if ($row) $server_id = $row->server_id;
			else $server_id = "{$this->game}_".sprintf("%02d", $server);
		}
		else $server_id = $this->game;	
		
		$server_row = $this->db->from("servers")->where("server_id", $server_id)->get()->row();
		if (empty($server_row)) {
			log_message('error', 'm_create_ios_billing: 無此伺服器: '.$server_id);
			$this->_output_json(RESPONSE_FAILD, "無此伺服器");
		}				
				
		$data = array(
			"uid" => $uid,
			"server" => $server,
			"server_id" => $server_row->id,
			"transaction_state" => $transaction_state,
			"transaction_id" => $transaction_id,
			"transaction_date" => date("Y-m-d H:i:s", strtotime(substr($transaction_date, 0, -8))+28800),
			"product_id" => $product_id,
			"amount" => $amount,
		);
		
		$data["price"] = $this->partner_conf[$this->partner]["sites"][$this->game]["ios"]["products"][$product_id];
		if (empty($data["price"])) {
			log_message('error', "ios產品({$product_id})尚未設定");
			$this->_output_json(RESPONSE_FAILD, "ios產品({$product_id})尚未設定");
		}
		
		//log_message('error', print_r($data, true));
		
		$query = $this->db->select("id")->from("ios_billing")->where("transaction_id", $transaction_id)->get();
		if ($query->num_rows() > 0) {
			$ios_billing_id = $query->row()->id;			
			$this->db
				->set("update_time", "NOW()", FALSE)
				->where("id", $ios_billing_id)
				->update("ios_billing", $data);
		}
		else {
			$this->db
				->set("update_time", "NOW()", FALSE)
				->set("create_time", "NOW()", FALSE)
				->insert("ios_billing", $data);
			$ios_billing_id = $this->db->insert_id();
		}
		
		if ($ios_billing_id) {
			$order = $this->db
				->select("id, product_id, price, transaction_date as 'purchase_time', server_id, server")
				->select("'ios_billing' as type", false)
				->from("ios_billing ib")
				->where("id", $ios_billing_id)
				->where("uid", $uid)
				->get()->row();
						
			$this->load->library("g_wallet");
			
			// 建立income交易
			$billing_id = $this->g_wallet->produce_income_order($uid, "ios_billing", $ios_billing_id, $order->price);
			//log_message('error', "ios_billing_id: ".$ios_billing_id);
			if (empty($billing_id)) {
				log_message('error', $this->db->last_query());
				log_message('error', '資料庫發生錯誤-新增ios income訂單:'. $this->g_wallet->error_message);
				$this->_output_json(RESPONSE_FAILD, "資料庫發生錯誤-新增ios income訂單", array("order_json"=> (array) $order));
			}
			
			// 開啟轉點
			$servers = $this->db->from("servers")->where("id", $order->server_id)->get()->row();	
			$billing_id = $this->g_wallet->produce_order($uid, "top_up_account", "2", $order->price, $servers->server_id);
			if (empty($billing_id)) {
				log_message('error', '資料庫發生錯誤-ios 訂單轉點'. $this->g_wallet->error_message);
				$this->_output_json(RESPONSE_FAILD, "資料庫發生錯誤-google訂單轉點", array("order_json"=> (array) $order));
			}
			
			// 轉點成功
			$this->g_wallet->complete_order((object)array("id"=>$billing_id));
			$this->g_wallet->is_confirmed_order((object)array("id"=>$billing_id));
			
			$order->signature = md5($order->id.$order->price.$this->key.'bea$n'.$order->purchase_time);
			
			$this->_output_json(RESPONSE_OK, "", array("order_json" => (array) $order));
		}
		else {
			log_message('error', 'm_create_ios_billing: 資料庫新增錯誤');
			$this->_output_json(RESPONSE_FAILD, "資料庫新增錯誤");
		}
	}

	function m_create_google_billing()
	{
		//log_message('error', 'create_google_billing: '.print_r($this->input->post(), true));
		
		$this->_chk_partner();
		
		$euid = $this->input->post("euid");
		$server = $this->input->post("server");
		
		if (empty($euid)) {
			$this->_output_json(RESPONSE_FAILD, "缺少參數");
		}
		
		if ($server) {
			$row = $this->db->from("servers")->where("game_id", $this->game)->where("address", $server)->get()->row();
			if ($row) $server_id = $row->server_id;
			else $server_id = "{$this->game}_".sprintf("%02d", $server);
		}
		else $server_id = $this->game;	
		
		$server_row = $this->db->from("servers")->where("server_id", $server_id)->get()->row();
		if (empty($server_row)) $this->_output_json(RESPONSE_FAILD, "無此伺服器");
				
		if ($this->hash <> md5($this->partner . $this->game . $euid  . $server . $this->time . $this->key . $_SESSION['token'])) {
			$this->_output_json(RESPONSE_FAILD, "認證碼錯誤");
		}
		
		$data = array(
			"uid" => $this->g_user->decode($euid),
			"server_id" => $server_row->id,
			"server" => $server,
		);
		$this->db
			->set("create_time", "NOW()", FALSE)
			->set("update_time", "NOW()", FALSE)
			->insert("google_billing", $data);
		
		if ($insert_id = $this->db->insert_id())
			$this->_output_json(RESPONSE_OK, "", array("id" => $insert_id));
		else $this->_output_json(RESPONSE_FAILD, "資料庫新增錯誤");
	}
	
	function m_update_google_billing()
	{
		//log_message('error', 'update_google_billing');
		
		function verify_purchase($key, $data, $signature) {
			$publicKey = "-----BEGIN PUBLIC KEY-----\n" . chunk_split($key, 64, "\n") . '-----END PUBLIC KEY-----';		    
			$key = openssl_get_publickey( $publicKey );        
			return ( 1 == openssl_verify( $data, base64_decode( $signature ), $key, OPENSSL_ALGO_SHA1 ) );
		}
		
		//log_message('error', 'update_google_billing: '.print_r($this->input->post(), true));		
		$this->_chk_partner();
		
		// 接收參數
		$result = $this->input->post("result");
		$note = $this->input->post("note");
		$purchase_json = $this->input->post("purchase_json");
		$signature = $this->input->post("signature");		
		
		if (empty($signature)) {
			log_message('error', 'empty signature, game: '.$this->game);
		}
		if (empty($result) || empty($purchase_json) || (empty($signature) && $this->game <> 'gsg'))  {
			log_message('error', 'update_google_billing faild: 缺少參數');
			$this->_output_json(RESPONSE_FAILD, "缺少參數");
		}

		// 檢查認證碼
		if ($this->hash <> md5($this->partner . $this->game . $result . $note . $purchase_json . $signature . $this->time . $this->key . $_SESSION['token'])) {
			log_message('error', 'update_google_billing hash faild: '.$this->partner . $this->game . $result . $note . $purchase_json . $signature . $this->time);
			$this->_output_json(RESPONSE_FAILD, "認證碼錯誤");
		}
						
		//log_message('error', $purchase_json);
		//log_message('error', $signature);
		
		$purchase = json_decode($purchase_json);
		$info = json_decode($purchase->developerPayload);
		$google_billing_id = $info->id;
		$uid = $this->g_user->decode($info->euid);
		
		log_message('error', '<!-- update_google_billing: '.$google_billing_id.',  uid: '.$uid.','.$result);
					
		//檢查是否為不正常交易
		if (! preg_match('/^12\d{18}\.\d{16}$/', $purchase->orderId) && $purchase->orderId <> 'transactionId.android.test.purchased') {
			$purchase->purchaseState = "-1";
			$note = "google訂單異常";
			log_message('error', "google訂單異常(格式錯誤), id: ".$google_billing_id);
		}
		else if ($signature) {
			$key = $this->partner_conf[$this->partner]["sites"][$this->game]["iab"]["key"];
			if ( ! verify_purchase($key, $purchase_json, $signature)) {
				$purchase->purchaseState = "-1";
				$note = "google訂單異常..";
				log_message('error', "google訂單異常(驗證失敗), id: ".$google_billing_id);
			}
		}
		
		$data = array(
			"order_id"	=> $purchase->orderId,
			"product_id" => $purchase->productId,
			"purchase_time" => $purchase->purchaseTime,
			"purchase_state" => $purchase->purchaseState,
			"token" => $purchase->purchaseToken,
			"result" => $result,
			"note" => $note,
		);	
		if ($purchase->purchaseState <> '0') $data["result"] = "2"; //失敗
		
		$data["price"] = $this->partner_conf[$this->partner]["sites"][$this->game]["iab"]["products"][$purchase->productId];
		if (empty($data["price"])) {
			log_message('error', "iab產品({$purchase->productId})尚未設定");
			$this->_output_json(RESPONSE_FAILD, "iab產品({$purchase->productId})尚未設定");
		}					
		
		// 更新google交易訂單
		$this->db
			->set("update_time", "NOW()", FALSE)
			->where("id", $google_billing_id)
			->where("uid", $uid)
			->update("google_billing", $data);
		
		if ($this->db->affected_rows()) {			

			$order = $this->db
				->select("id, product_id, price, purchase_time, result, is_confirmed, server")
				->select("'google_billing' as type", false)
				->from("google_billing")
				->where("id", $google_billing_id)
				->where("uid", $uid)
				->get()->row();
			
			$order->signature = md5($order->id.$order->price.$this->key.'bea$n'.$order->purchase_time);
			
			//log_message('error', 'update_google_billing: '.print_r($order, true));	
			$this->_output_json(RESPONSE_OK, "", array("order_json" => (array) $order));
		}			
		else {
			log_message('error', "update_google_billing: 資料庫發生錯誤-更新google交易訂單");
			$this->_output_json(RESPONSE_FAILD, "資料庫發生錯誤-更新google交易訂單");
		}
	}
	
	function m_confirm_google_billing()
	{
		//log_message('error', 'confirm_google_billing..');
		
		$this->_chk_partner();
		
		// 接收參數
		$purchase_json = $this->input->post("purchase_json");	
		$signature = $this->input->post("signature");	
			
		if (empty($purchase_json))  {
			log_message('error', 'confirm_google_billing faild: 缺少參數'.$purchase_json);
			$this->_output_json(RESPONSE_FAILD, "缺少參數");
		}

		// 檢查認證碼
		if ($this->hash <> md5($this->partner . $this->game . $purchase_json. $signature . $this->time . $this->key . $_SESSION['token'])) {
			log_message('error', 'update_google_billing hash faild: '.$this->partner . $this->game . $purchase_json . $this->time);
			$this->_output_json(RESPONSE_FAILD, "認證碼錯誤");
		}
				
		$purchase = json_decode($purchase_json);
		$info = json_decode($purchase->developerPayload);
		$google_billing_id = $info->id;
		$uid = $this->g_user->decode($info->euid);		
		
		log_message('error', '@ confirm_google_billing: '.$google_billing_id.', uid: '.$uid.' -->');

		//檢查是否為不正常交易
		if (! preg_match('/^12\d{18}\.\d{16}$/', $purchase->orderId) && $purchase->orderId <> 'transactionId.android.test.purchased') {
			$purchase->purchaseState = "-1";
			$note = "google訂單異常(格式錯誤)";
			log_message('error', "google訂單異常(格式錯誤), id: ".$google_billing_id);
		}
		else if ($signature) {
			$key = $this->partner_conf[$this->partner]["sites"][$this->game]["iab"]["key"];
			if ( ! verify_purchase($key, $purchase_json, $signature)) {
				$purchase->purchaseState = "-1";
				$note = "google訂單異常(驗證失敗)";
				log_message('error', "google訂單異常(驗證失敗), id: ".$google_billing_id);
			}
		}
		
		if ($purchase->purchaseState <> '0') {
			log_message('error', '交易失敗，無須請款, id: '.$google_billing_id);
			$this->_output_json(RESPONSE_FAILD, "交易失敗，無須請款");
		}
		
		$data = array(
			"order_id"	=> $purchase->orderId,
			"product_id" => $purchase->productId,
			"purchase_time" => $purchase->purchaseTime,
			"purchase_state" => $purchase->purchaseState,
			"token" => $purchase->purchaseToken,
			"is_confirmed" => "1"
		);	
		
		$data["price"] = $this->partner_conf[$this->partner]["sites"][$this->game]["iab"]["products"][$purchase->productId];
		if (empty($data["price"])) $this->_output_json(RESPONSE_FAILD, "iab產品({$purchase->productId})尚未設定");
		
		// 更新google交易訂單，請款完成confirm=1
		$this->db
			->set("update_time", "NOW()", FALSE)
			->where("id", $google_billing_id)
			->where("uid", $uid)
			->where("result", "1")
			->where("is_confirmed", "0")
			->update("google_billing", $data);
		
		if ($this->db->affected_rows()) {			
			
			$order = $this->db
				->select("id, product_id, price, purchase_time, result, is_confirmed, server_id, server")
				->select("'google_billing' as type", false)
				->from("google_billing gb")
				->where("id", $google_billing_id)
				->where("uid", $uid)
				->get()->row();
						
			$this->load->library("g_wallet");
			
			// 建立income交易
			$billing_id = $this->g_wallet->produce_income_order($uid, "google_billing", $google_billing_id, $order->price);
			if (empty($billing_id)) $this->_output_json(RESPONSE_FAILD, "資料庫發生錯誤-新增google income訂單", array("order_json"=> (array) $order));
			
			// 開啟轉點
			$servers = $this->db->from("servers")->where("id", $order->server_id)->get()->row();	
			$billing_id = $this->g_wallet->produce_order($uid, "top_up_account", "2", $order->price, $servers->server_id);
			if (empty($billing_id)) $this->_output_json(RESPONSE_FAILD, "資料庫發生錯誤-google訂單轉點", array("order_json"=> (array) $order));
			
			// 轉點成功
			$this->g_wallet->complete_order((object)array("id"=>$billing_id));
			$this->g_wallet->is_confirmed_order((object)array("id"=>$billing_id));
			
			$order->signature = md5($order->id.$order->price.$this->key.'bea$n'.$order->purchase_time);			
						
			$this->_output_json(RESPONSE_OK, "", array("order_json" => (array) $order));
		}			
		else {
			$sql = $this->db->last_query();
			$order = $this->db
				->select("id, product_id, price, purchase_time, result, is_confirmed, server")
				->select("'google_billing' as type", false)
				->from("google_billing gb")
				->where("id", $google_billing_id)
				->where("uid", $uid)
				->get()->row();
			
			$order->signature = md5($order->id.$order->price.$this->key.'bea$n'.$order->purchase_time);
			
			log_message('error', "資料庫發生錯誤 {$sql}-google交易請款");
			$this->_output_json(RESPONSE_FAILD, "資料庫發生錯誤-google交易請款", array("order_json" => (array) $order));
		}
	}
	
	function m_get_lose_order()
	{
		//log_message('error', 'get_lose_order: '.print_r($this->input->post(), true));
		
		$this->_chk_partner();
		
		$server = $this->input->post("server");
		$euid = $this->input->post("euid");
		$log_login = $this->input->post("log_login");
		
		if (empty($euid))  $this->_output_json(RESPONSE_FAILD, "缺少參數");
				
		if ($server) {
			$row = $this->db->from("servers")->where("game_id", $this->game)->where("address", $server)->get()->row();
			if ($row) $server_id = $row->server_id;
			else $server_id = "{$this->game}_".sprintf("%02d", $server);
		}
		else $server_id = $this->game;	
		
		// 檢查認證碼
		if ($this->hash <> md5($this->partner . $this->game . $euid . $server . $log_login . $this->time . $this->key . $_SESSION['token'])) {
			$this->_output_json(RESPONSE_FAILD, "認證碼錯誤");
		}
				
		$server_row = $this->db->from("servers")->where("server_id", $server_id)->get()->row();
		if (empty($server_row)) $this->_output_json(RESPONSE_FAILD, "無此伺服器");
				
		$uid = $this->g_user->decode($euid);
		
		if ($log_login) { // log登入記錄
			//log_message('error', 'log_login');
			$_GET['site'] = $this->game;
			$user = $this->g_user->get_user_data($uid);
			$this->load->library("game");
			$this->game->log_game_login($uid, $user->account, $server_row->id);
		}
		
		$query = $this->db->from("user_billing")
			->select("id, '' as product_id, amount as price, create_time as purchase_time, result, is_confirmed, '{$server}' as server", false)
			->select("'long_e_billing' as type", false)
			->where("uid", $uid)
			->where("pay_server_id", $server_id)
			->where("transaction_type", "top_up_account")
			->where("is_confirmed", "0")->get();
		
		$json_arry = array();
		if ($query->num_rows() > 0) 
		{
			foreach($query->result() as $row) {
				$row->signature = md5($row->id.$row->price.$this->key.'bea$n'.$row->purchase_time);
				$json_arry[] = (array)$row;
			}
			
			$this->db
				->set("update_time", "NOW()", FALSE)
				->where("uid", $uid)
				->where("pay_server_id", $server_id)
				->where("transaction_type", "top_up_account")
				->where("is_confirmed", "0")
				->update("user_billing", array("is_confirmed"=>"1"));
		}
		
		//log_message('error', 'get_lose_order: '.print_r($json_arry, true));
		
		$this->_output_json(RESPONSE_OK, "", array("order_list" => $json_arry));
	}
	
	function m_open_long_e_billing()
	{
		$this->_chk_partner();
		
		$euid = $this->input->get("euid");
		if (empty($euid))  $this->_output_json(RESPONSE_FAILD, "缺少參數");
		
		// 檢查認證碼
		if ($this->hash <> md5($this->partner . $this->game . $euid . $this->time . $this->key . $_SESSION['token'])) {
			$this->_output_json(RESPONSE_FAILD, "認證碼錯誤");
		}
		
		$uid = $this->g_user->decode($euid);
		$this->g_user->switch_uid($uid);
		
		header("location: ".base_url()."/payment?game=".$this->game);
	}
	
	function m_open_long_e_billing_v2()
	{
		$this->_chk_partner();
		
		$euid = $this->input->get("euid");
		$server = $this->input->get("server");
		if (empty($euid))  $this->_output_json(RESPONSE_FAILD, "缺少參數");
		
		//log_message('error', '('.$_SERVER["REMOTE_ADDR"].')'."open_long_e_billing: ".$euid." server".$server);
		
		$type = $this->input->get("type");
		$country = $this->input->get("country");
		$operator = $this->input->get("operator");

		if (empty($country)) {
			$ctx = stream_context_create(array('http' => array('timeout' => 8)));  
			$country = file_get_contents('http://api.hostip.info/country.php?ip='.$_SERVER['REMOTE_ADDR'], 0, $ctx);
		}
				
		$eg = (in_array(strtolower($country), array('tw', 'mo')) ? "1" : "0");
		$eg = '1'; //全開
		if ($this->game == 'gsg') $eg='1';
		
		if ($server) {
			$row = $this->db->from("servers")->where("game_id", $this->game)->where("address", $server)->get()->row();
			if ($row) $server_id = $row->server_id;
			else $server_id = "{$this->game}_".sprintf("%02d", $server);
		}
		else $server_id = $this->game;	
		
		$server_row = $this->db->from("servers")->where("server_id", $server_id)->get()->row();
		//log_message('error', print_r($this->input->get(), true));		
		
		if (empty($server_row)) $this->_output_json(RESPONSE_FAILD, "無此伺服器");
		
		
		//log_message('error', print_r($this->input->get(), true)); 
		
		// 檢查認證碼
		if ($this->hash <> md5($this->partner . $this->game . $server . $euid . $this->time . $this->key . $_SESSION['token'])) {
			$this->_output_json(RESPONSE_FAILD, "認證碼錯誤");
		}
		
		
		$uid = $this->g_user->decode($euid);
		$this->g_user->switch_uid($uid);
		
		//log_message('error', $uid." ".$type);
		
		if ($type == 'ios') {
			$url = base_url()."/payment/m_ios_index?sid=".$server_row->id."&partner=".$this->partner."&game=".$this->game;
		}
		else if ($type == 'google') {
			$url = base_url()."/payment/m_google_index?sid=".$server_row->id."&partner=".$this->partner."&game=".$this->game."&eg=".$eg."&test=".$operator.$country;
		}
		else if ($type == 'long_e') {
			$url = base_url()."/payment/m_long_e_index?sid=".$server_row->id."&partner=".$this->partner."&game=".$this->game."&eg=".$eg."&test=".$operator.$country;
		}
		else {
			$url = base_url()."/payment/m_index?sid=".$server_row->id."&partner=".$this->partner."&game=".$this->game."&eg=".$eg."&test=".$operator.$country;
		}
		
		header("location: {$url}");
	}
	
	function open_long_e_page()
	{
		$this->_chk_partner();		
		$euid = $this->input->get("euid");
		$redirect_url = urldecode($this->input->get("redirect_url", true));
		if (empty($redirect_url)) die('empty');
		
		//log_message('error', print_r($_GET, true));
		// 檢查認證碼
		if ($this->hash <> md5($this->partner . $this->game . $euid . $this->time . "@" . $this->key . $_SESSION['token'])) {
			die("認證碼錯誤");
		}		
		if (time() - $this->time > 300) die('逾時');
		
		$uid = $this->g_user->decode($euid);
		$this->g_user->switch_uid($uid);
		
		header("location: {$redirect_url}");
		exit();
	}
	
	function get_euid_channel()
	{
		$this->_chk_partner();		
		$euid = $this->input->get_post("euid");

		//log_message('error', $this->partner . $this->game . $euid . $this->time . $this->key);
		if (empty($euid)) {
			return $this->_output_json(RESPONSE_FAILD, "缺少參數");
		}
		else if ($this->hash <> md5($this->partner . $this->game . $euid . $this->time . $this->key . $_SESSION['token'])) {
			return $this->_output_json(RESPONSE_FAILD, "認證碼錯誤");
		}		
		//if (time() - $this->time > 300) die('逾時');
		
		$uid = $this->g_user->decode($euid);

		if ($this->game == 'eya') {
			//檢查是否被綁，若是則使用綁定帳號
			$query = $this->db->select("uid")->from("users")->where("bind_uid", $uid)->get();
			if ($query->num_rows() > 0) {				
				$uid = $query->row()->uid;
			}
		}
		
		$account = $this->db->select("account")->from("users")->where("uid", $uid)->get()->row()->account;
		
		if (strstr($account, '@')) {
			$spt = explode('@', $account);			
			return $this->_output_json(RESPONSE_OK, "", array("channel" => $spt[1]));
		}
		else {
			return $this->_output_json(RESPONSE_OK, "", array("channel" => 'long_e'));
		}
	}
	
	function m_get_euid() 
	{
		//log_message('error', '('.$_SERVER["REMOTE_ADDR"].')'."login:".$this->g_user->euid.", ".$this->g_user->account.", ".$this->input->get("code"));
		//echo $this->input->get("code").", ";
		
		if ( ! $this->g_user->is_login()) return ;
		echo $this->g_user->euid;
		
		$token = $_SESSION['token'];
		
		if (get_mobile_os() == 'ios') {
			echo "<script src='".base_url()."/p/js/iosBridge.js'></script>
				<script type='text/javascript'>calliOSFunction('receiveEuid', ['{$this->g_user->euid}', '{$this->input->get("code")}', '{$token}']); history.back(); </script>";
		}
		else {
			echo "<script type='text/javascript'>
			alert('m_get_euid');
					//window.CoozSDK.receiveEuid('{$this->g_user->euid}', '{$this->input->get("code")}', '{$token}');
					parent.receiveEuid('{$this->g_user->euid}', '{$this->input->get("code")}', '{$token}');
				</script>
			";
		}
		exit();
	}
	
	function m_get_long_e_euid() 
	{
		if ( ! $this->g_user->is_login()) return ;
		
		$code = $this->input->get("code");
		$hash = md5($code."b~ean".$this->g_user->euid."!#$..");

		$channel = isset($_SESSION['channel']) ? $_SESSION['channel'] : 'long_e';	
        $token = $_SESSION['token'];
		
		if (get_mobile_os() == 'ios') {
			echo "<script src='".base_url()."/p/js/iosBridge.js'></script>
				<script type='text/javascript'>calliOSFunction('receiveEuid', ['{$this->g_user->euid}', '{$hash}', '{$token}', '{$channel}']); history.back(); </script>";
		}
		else {
			echo "<script type='text/javascript'>
						try {
							//window.CoozSDK.receiveEuid('{$this->g_user->euid}', '{$hash}', '{$token}', '".$channel."');
							parent.receiveEuid('{$this->g_user->euid}', '{$hash}', '{$token}', '".$channel."');
						}
						catch(e) {
							//window.CoozSDK.receiveEuid('{$this->g_user->euid}', '{$hash}', '{$token}');
							parent.receiveEuid('{$this->g_user->euid}', '{$hash}', '{$token}');
						}
				</script>
			";
		}
		exit();
	}	
	
	function m_res_facebook()
	{
		//$token = $_SESSION['token'];
		if (get_mobile_os() == 'ios') {
			echo "<script src='".base_url()."/p/js/iosBridge.js'></script>
				<script type='text/javascript'>calliOSFunction('receiveEuid', ['', '', '', 'm_facebook']); history.back(); </script>";
		}
		else {
			echo "<script type='text/javascript'>
			        var ifrm = parent.receiveEuid('', '', '', 'm_facebook'); 
					//window.CoozSDK.receiveEuid('', '', '', 'm_facebook');
				</script>
			";
		}
		exit();	
	}
	
	function m_logout()
	{
		$this->load->library('g_user');
		$this->g_user->logout();
	
		header('Content-type:text/html; Charset=UTF-8');
		echo "<script type='text/javascript'>alert('成功登出系統'); </script>";
		
		if (get_mobile_os() == 'ios') {
			echo "<script src='".base_url()."/p/js/iosBridge.js'></script>
				<script type='text/javascript'>calliOSFunction('dialogLogout');</script>";
		}
		else {
			echo "<script type='text/javascript'>
						try {
							window.CoozSDK.dialogLogout();
						}
						catch(e) {	}						
				</script>
			";
		}		
		echo "<script type='text/javascript'>history.back();</script>";
	}	

	function m_logout_mute()
	{
		$this->load->library('g_user');
		$this->g_user->logout();
	}
	
	function m_switch_uid() {
		$redirect_url = urldecode($this->input->get("redirect_url", true));
		$uid = $this->input->post('uid');
		if (empty($uid)) die('缺少參數');
		if ($this->g_user->uid == '304757') {
			$this->g_user->switch_uid($uid);
			header('location: '.$redirect_url);
			exit();
		}
		else die('沒有權限');
	}
	
	function login_long_e()
	{
		$key = $this->partner_conf['public_key'];
		$euid = $this->input->get('euid');
		$time = $this->input->get('time');
		$hash = $this->input->get('hash');
		$redirect_url = $this->input->get('redirect_url');
		
		if ($hash <> md5($time . $euid . $key . '_' . $redirect_url)) {
			die("認證碼錯誤");
		}		
		
		if (time() - $time > 300) die('逾時');
		
		$this->g_user->switch_uid($this->g_user->decode($euid));
		if ($redirect_url) {
			header('location: '.$redirect_url);
			exit();
		}
	}
	
	function m_long_e_menu()
	{	
		$this->_chk_partner();
		
		$euid = $this->input->get_post("euid");
		if (empty($euid) || $euid == 'null') {
			log_message('error', 'm_long_e_menu 缺少參數euid：'.$euid);
			$this->_output_json(RESPONSE_FAILD, "缺少參數");
		}
		
		if ($this->hash <> md5($this->partner . $this->game . $this->time . $this->key . $euid . $_SESSION['token'])) {
			$this->_output_json(RESPONSE_FAILD, "認證碼錯誤");
		}

		$uid = $this->g_user->decode($euid);
		$this->g_user->switch_uid($uid);
					
		$this->_init_layout();
		$this->g_layout->css_link = array("default");
		$this->g_layout
			->render("", "mobile");	
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
