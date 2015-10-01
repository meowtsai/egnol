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

	// AJAX 回應 function 檢查是否已登入
	function _check_login_json()
	{
		if (!$this->g_user->is_login())
		{
			die(json_failure("尚未登入，請重新進行登入"));
		}
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
		$site	= $this->_get_site();

		if(!$this->g_user->is_login())
		{
			// 未登入, 直接進入登入畫面
			$partner    = !empty($_SESSION['login_partner']) ? $_SESSION['login_partner'] : $this->input->get_post("partner");
			$game_key   = !empty($_SESSION['login_gamekey']) ? $_SESSION['login_gamekey'] : $this->input->get_post("gamekey");
			$device_id	= !empty($_SESSION['login_deviceid']) ? $_SESSION['login_deviceid'] : $this->input->get_post("deviceid");
			$login_key	= !empty($_SESSION['login_key']) ? $_SESSION['login_key'] : $this->input->get_post("loginkey");
			$_SESSION['login_partner']	= $partner;
			$_SESSION['login_gamekey']	= $game_key;
			$_SESSION['login_deviceid']	= $device_id;
			$_SESSION['login_key']		= $login_key;

			// server 登入選擇模式, 0 = 不選擇(default), 1 = 登入後選擇
			$server_mode = $this->input->get_post("servermode");
			if(empty($server_mode))
			{
				$server_mode = 0;
			}
			$_SESSION['server_mode'] = $server_mode;

			$change_account = !empty($this->input->get("change_account")) ? $this->input->get("change_account") : 0;
			
			// 免輸入登入機制(Session 失效時使用)
			if(!empty($login_key) && $change_account != 1)
			{
				$keys = explode(",", $login_key);
				if(count($keys) == 5)
				{
					$check_key = md5($partner.$keys[1].$keys[2].$keys[3].$game_key.$device_id);
					$new_check = "";
					for($cnt1 = 0; $cnt1 < 16; $cnt1++)
					{
						$ch = substr($check_key, $cnt1 * 2, 1);
						if($ch != "0")
							$new_check .= substr($check_key, $cnt1 * 2, 1);
						
						$new_check .= substr($check_key, $cnt1 * 2 + 1, 1);
					}
					if($new_check == $keys[0])
					{
						// 登入資訊正確, 自動登入
						if($this->g_user->verify_account($keys[1], $keys[2], '', $keys[3]))
						{
							$_SESSION['site'] = $site;
							$this->_ui_member();
							return;
						}
					}
				}
			}
			
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
				->add_css_link("login_api")
				->add_js_include("api/login")
				->set("partner", $partner)
				->set("game_key", $game_key)
				->set("server_mode", $server_mode)
				->set("device_id", $device_id)
				->set("channel_item", $channel_item)
				->api_view();
		}
		else
		{
			$this->_ui_member();
		}
	}
	
	function _ui_member()
	{
		$site	= $this->_get_site();

		if (!isset($_SESSION['server_id']))
		{
			// 已登入, 改顯示會員畫面
			$partner    = !empty($_SESSION['login_partner']) ? $_SESSION['login_partner'] : $this->input->get_post("partner");
			$game_key   = !empty($_SESSION['login_gamekey']) ? $_SESSION['login_gamekey'] : $this->input->get_post("gamekey");
			$device_id	= !empty($_SESSION['login_deviceid']) ? $_SESSION['login_deviceid'] : $this->input->get_post("deviceid");
			$server_mode = !empty($_SESSION['server_mode']) ? $_SESSION['server_mode'] : 0;
			$servers = null;
			
			// 讀取伺服器列表
			$servers = $this->db->from("servers")->where("game_id", $site)->order_by("server_id")->get();

			$this->_init_layout()
				->set("partner", $partner)
				->set("game_key", $game_key)
				->set("device_id", $device_id)
				->set("server_mode", $server_mode)
				->set("servers", $servers)
				->add_css_link("login")
				->add_js_include("api/login_game")
				->api_view("api/ui_member");
		} 
		else
		{	
			$email = !empty($this->g_user->email) ? $this->g_user->email : "";
			$mobile = !empty($this->g_user->mobile) ? $this->g_user->mobile : "";
			$external_id = !empty($this->g_user->external_id) ? $this->g_user->external_id : "";
	
			header('Content-type:text/html; Charset=UTF-8');
			//echo "<script type='text/javascript'>LongeAPI.onLogoutSuccess()</script>";
			$ios_str = $this->g_user->uid."-_-".$email."-_-".$mobile."-_-".$external_id."-_-".$_SESSION['server_id'];
			echo "<script type='text/javascript'>
				if (typeof LongeAPI != 'undefined') {
				    LongeAPI.onLoginSuccess('{$this->g_user->uid}', '{$email}', '{$mobile}', '{$external_id}', '{$_SESSION['server_id']}');
				} else {
					window.location = \"ios://loginsuccess-_-\" + encodeURIComponent('{$ios_str}');
				}
			</script>";
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
			die(json_message(array("message"=>"成功", "site"=>$site), true));
		}
		else
		{
			die(json_failure($this->g_user->error_message));
		}
	}
	
	function ui_login_game_json()
	{
		header('content-type:text/html; charset=utf-8');

		$site = $this->_get_site();

		if (isset($_SESSION['server_mode']) && $_SESSION['server_mode'] == 1) {
			$server = $this->input->post("server");
			if(empty($server))
			{
				die(json_failure('請選擇伺服器'));
			}
		} else {
			$single_server = $this->db->from("servers")->where("game_id", $site)->order_by("server_id")->get()->row();
			
			$server = $single_server->server_id;
		}

		$query = $this->db->from("log_game_logins")
		           ->where("uid", $_SESSION['user_id'])
				   ->where("is_first", "1")
				   ->where("server_id", $server)
				   ->where("game_id", $site)->get();
		if (empty($query) || $query->num_rows() == 0)
		{
			$is_first = '1';
		} else {
			$is_first = '0';
	        $this->db->where("uid", $_SESSION['user_id'])
			  ->where("is_recent", '1')
			  ->where("server_id", $server)
			  ->where("game_id", $site)->update("log_game_logins", array("is_recent" => '0'));
		}	
		$ad = $this->input->get('ad') ? $this->input->get('ad') : (empty($_SESSION['ad']) ? '' : $_SESSION['ad']);
		
		$data = array(
			'uid' => $_SESSION['user_id'],
			'ip' => $_SERVER["REMOTE_ADDR"],
			//'create_time' => now(),
			'is_recent' => '1',
			'is_first' => $is_first,
			'ad' => $ad,
			'server_id' => $server,
			'game_id' => $site,
		);

		$this->db->insert("log_game_logins", $data);	
		if ( $this->db->insert_id() )
		{
		    $_SESSION['server_id'] = $server;
			die(json_message(array("message"=>"成功", "site"=>$site), true));
		}
		else
		{
			die(json_failure('登入伺服器錯誤'));
		}
	}

	// 直接登入
	function ui_quick_login()
	{
		header('Content-type:text/html; Charset=UTF-8');

		$site = $this->_get_site();
        $device_id = $this->input->get('deviceid');
		$external_id = $device_id."@device";

		if(!empty($device_id))
		{
			$server_mode = empty($_SESSION['server_mode']) ? 0 : $_SESSION['server_mode'];

			$_SESSION['site'] = $site;

			$boolResult = $this->g_user->verify_account('', '', '', $external_id);
			if ($boolResult != true)
			{
				$boolResult = $this->g_user->create_account('', '', '', $external_id);
			}

			if ($boolResult==true)
			{
				$this->g_user->verify_account('', '', '', $external_id);
			}
			else
			{
				echo "<script type='text/javascript'>alert('登入失敗!');</script>";
			}
		}
		else
		{
			echo "<script type='text/javascript'>alert('無法取得行動裝置資訊，登入失敗!');</script>";
		}

		echo "<script type='text/javascript'>location.href='/api/ui_login?site={$site}';</script>";
	}

	// 第三方登入
	function ui_channel_login()
	{
		$site = $this->_get_site();
		$channel = $this->input->get("channel", true);

		$_SESSION['site'] = $site;
		$_SESSION['redirect_url'] = 'https://api.longeplay.com.tw/api/ui_login';

		$param = $login_param = array();

		$this->load->config("api");
		$channel_api = $this->config->item("channel_api");
		if (array_key_exists($channel, $channel_api) == false)
		{
			die("未串接此通道({$channel})");
		}

		if (isset($channel_api[$channel]['lib_name']))
		{ //lib重命名
			$lib = $channel_api[$channel]['lib_name'];
		}
		else
		{
			$lib = $channel;
		}

		if ($channel == "facebook")
		{
	    	$fb_app_conf = $this->config->item("fb_app");
	    	if ( !empty($ad) && array_key_exists($ad, $fb_app_conf))
	    	{
	    		$param = array(
					'appId'  => $fb_app_conf[$ad]['appId'],
					'secret' => $fb_app_conf[$ad]['secret'],
	    		);
	    		$login_param = array('scope' => '',);
	    	}
		}

		$this->load->library("channel_api/{$lib}", $param);
		$result = $this->{$lib}->login($site, $login_param);
		if ($result == false)
		{
			die($this->{$lib}->error_message);
		}
	}

	// 更換帳號
	function ui_change_account()
	{
		// 登出然後跳回登入畫面
		header('content-type:text/html; charset=utf-8');

		$site = $this->_get_site();
		
		$this->g_user->logout();
		
        die("<script type='text/javascript'>location.href='/api/ui_login?site={$site}&change_account=1'</script>");
	}
	
	// 帳號登出
	function ui_logout()
	{
		$this->g_user->logout();

		$_SESSION['site'] = '';
		$_SESSION['server_mode'] = '';
		$_SESSION['server_id'] = '';

		unset($_SESSION['site']);
		unset($_SESSION['server_mode']);
		unset($_SESSION['server_id']);

		header('Content-type:text/html; Charset=UTF-8');
		//echo "<script type='text/javascript'>LongeAPI.onLogoutSuccess()</script>";
        echo "<script type='text/javascript'>
	        if (typeof LongeAPI != 'undefined') {
                LongeAPI.onLogoutSuccess()
            } else {
                window.location = \"ios://logoutsuccess\";
	        }
		</script>";
	}

	// 帳號註冊
	function ui_register()
	{
		$this->_init_layout()
			->add_css_link("login_api")
			->add_js_include("api/register")
			->api_view();
	}

	function ui_register_json()
	{
		header('content-type:text/html; charset=utf-8');

		$site = $this->_get_site();
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
			->add_css_link("login_api")
			->add_js_include("api/bind_account")
			->api_view();
	}

	function ui_bind_account_json()
	{
		$this->_check_login_json();

		$site = $this->_get_site();
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
			->add_css_link("login_api")
			->add_js_include("api/forgot_password")
			->api_view();
	}

	function ui_reset_password_json()
	{
		header('content-type:text/html; charset=utf-8');

		$site = $this->_get_site();
		$email = $this->input->post("account");

		// 檢查 e-mail or mobile
		if(empty($email))
		{
			die(json_failure('Email或手機號碼未填寫'));
		}

	    $new = rand(100000, 999999);
	    $md5_new = md5($new);

		if(filter_var($email, FILTER_VALIDATE_EMAIL))
		{
			// 使用 email
			$cnt = $this->db->from("users")->where("email", $email)->count_all_results();
			if ($cnt == 0)
			{
				die(json_failure("沒有這位使用者或資料填寫錯誤。"));
			}
		    $this->db->where("email", $email)->update("users", array("password" => $md5_new));

			$this->load->library("g_send_mail");

			if($this->g_send_mail->passwdResetMail($email, $new))
			{
				die(json_message(array("message"=>"新密碼已發送到您的 E-Mail 信箱。", "site"=>$site)));
			}
			else
			{
				die(json_failure("E-Mail 發送失敗。"));
			}
		}
		else
		{
			// 使用手機號碼
			$mobile = $email;
			$cnt = $this->db->from("users")->where("mobile", $mobile)->count_all_results();
			if ($cnt == 0)
			{
				die(json_failure("沒有這位使用者或資料填寫錯誤。"));
			}
		    $this->db->where("mobile", $mobile)->update("users", array("password" => $md5_new));

			// 手機號碼的話要發送簡訊
            $msg = "親愛的龍邑會員您好：您的新密碼為 {$new}，請妥善保管。龍邑遊戲敬上";

			$this->load->library("g_send_sms");

			if($this->g_send_sms->send($site, $mobile, $msg))
			{
				die(json_message(array("message"=>"已使用簡訊發送新密碼至您的手機。", "site"=>$site)));
			}
			else
			{
				die(json_failure($this->g_send_sms->get_message()));
			}
		}
	}

	// 修改密碼
	function ui_change_password()
	{
		$this->_require_login();

		$this->_init_layout()
			->add_css_link("login_api")
			->add_js_include("api/change_password")
			->api_view();
	}

	function ui_change_password_json()
	{
		$this->_check_login_json();

		$site = $this->_get_site();
		$old = $this->input->post("old");
		$pwd = $this->input->post("pwd");
		$pwd2 = $this->input->post("pwd2");

		if ( empty($old) )
		{
			die(json_failure("請輸入舊密碼"));
		}
		else if ( empty($pwd) )
		{
			die(json_failure("請輸入新密碼"));
		}
		else if ($pwd != $pwd2)
		{
			die(json_failure("密碼與驗證密碼不同"));
		}

		if ($this->g_user->is_from_3rd_party())
		{
			$row = $this->g_user->get_user_data($this->g_user->uid);
			if(empty($row->email) && empty($row->mobile))
			{
                die(json_failure("尚未綁定帳號"));
			}
		}

		$user_data = $this->g_user->get_user_data();
		if($user_data->password != md5($old))
		{
            die(json_failure("舊密碼錯誤"));
		}

		$this->db->where("uid", $this->g_user->uid)->update("users", array("password" => md5(trim($pwd))));
		die(json_message(array("message"=>"修改成功", "site"=>$site)));
	}

	// 服務條款
	function ui_service_agreement()
	{
		$this->_init_layout()
			->add_css_link("login_api")
			->api_view('member/service_agreement');
	}

	// 隱私權政策
	function ui_privacy_agreement()
	{
		$this->_init_layout()
			->add_css_link("login_api")
			->api_view('member/privacy_agreement');
	}

	// 個資同意書
	function ui_member_agreement()
	{
		$this->_init_layout()
			->add_css_link("login_api")
			->api_view('member/member_agreement');
	}

	// 點數儲值
	function ui_payment()
	{
		$site = $this->_get_site();
		$server_id = $this->input->get_post("serverid");
		
		if(!$this->g_user->is_login())
		{
			$_SESSION['site'] = $site;
			
			if(!empty($this->input->get_post("pcode")))
			{
				// 免輸入登入機制(Session 失效時使用)
				$partner		= $this->input->get_post("partner");
				$email			= $this->input->get_post("email");
				$mobile			= $this->input->get_post("mobile");
				$device_id		= $this->input->get_post("deviceid");
				$external_id	= $this->input->get_post("externalid");
				$pcode			= $this->input->get_post("pcode");
				/*
				$this->load->config("api");
				$partner_conf = $this->config->item("partner_api");
				if(!array_key_exists($partner, $partner_conf))
				{
					die();
				}
				if(!array_key_exists($site, $partner_conf[$partner]["sites"]))
				{
					die();
				}
				$partner_game = $partner_api[$partner]["sites"][$site];

				//$chk_code = md5($partner.$email.$server_id.$mobile.$partner_game['key'].$device_id);
				//if($chk_code != $pcode)
				//	die();
				*/
				if(!$this->g_user->verify_account($email, $mobile, '', $external_id))
					die();
			}
			else
				$this->_require_login();
		}

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
			->set("server_id", $server_id)
			->set("characters", $characters)
			->add_css_link("login_api")
			->add_css_link("money")
			->add_js_include("api/payment")
			->api_view();
	}

	function ui_payment_result()
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
			->add_css_link("login_api")
			->add_css_link("money")
			->api_view();
	}

	// 點數儲值測試
	function ui_payment_test()
	{
		$this->_require_login();

		$this->load->config("g_gash");

		$site = $this->_get_site();
		$server_id = $this->input->get("serverid");

		// 讀取遊戲列表
		$games = $this->db->from("games")->where("is_active", "1")->get();
		// 讀取伺服器列表
		$servers = $this->db->order_by("server_id")->get("servers");
		// 讀取玩家角色列表
		$characters = $this->db->from("characters")->where("uid", $this->g_user->uid)->get();

		$this->_init_layout()
			->set("games", $games)
			->set("servers", $servers)
			->set("server_id", $server_id)
			->set("characters", $characters)
			->add_js_include("api/payment_test")
			->api_view();
	}

	function ui_payment_test_result()
	{
		header('Content-type:text/html; Charset=UTF-8');

		$game_id = $this->input->post("game");
		$server_id = $this->input->post("server");
		$character_id = $this->input->post("character");
		$billingType = $this->input->post("billing_type");
		$payType = "";
		$money = $this->input->post("billing_money");
		$get_point = $money;

		$character = $this->db->from("characters")->where("id", $character_id)->get()->row();

		//echo "<script type='text/javascript'>LongeAPI.onPaymentSuccess('{$game_id}','{$server_id}','{$character->name}','{$billingType}','{$payType}',parseInt('{$money}',10),parseInt('{$get_point}',10));</script>";
        echo "\"ios://paymentresult-_-{$game_id}-_-{$server_id}-_-{$character->name}-_-{$billingType}-_-{$payType}-_-{$money}-_-{$get_point}\"
		<script type='text/javascript'>
	        if (typeof LongeAPI != 'undefined') {
                LongeAPI.onPaymentSuccess('{$game_id}','{$server_id}','{$character->name}','{$billingType}','{$payType}',parseInt('{$money}',10),parseInt('{$get_point}',10));
            } else {
                window.location = \"ios://paymentresult-_-{$game_id}-_-{$server_id}-_-{$character->name}-_-{$billingType}-_-{$payType}-_-{$money}-_-{$get_point}\";
	        }
		</script>";
	}

	// 設定登入伺服器
	function set_login_server()
	{
		$partner = $this->input->post("partner");
		$game = $this->input->post("game");
		$server = $this->input->post("server");
		$uid = $this->input->post("uid");

		if (empty($partner) || empty($game))
		{
			die(json_encode(array("result"=>"0", "error"=>"參數錯誤")));
		}

		$servers = $this->db->from("servers")->where("game_id", $game)->order_by("id")->get();

		$partner_conf = $this->config->item("partner_api");
		if ( ! array_key_exists($partner, $partner_conf))
		{
			die(json_encode(array("result"=>"0", "error"=>"無串接此partner")));
		}
		if ( ! array_key_exists($game, $partner_conf[$partner]["sites"]))
		{
			die(json_encode(array("result"=>"0", "error"=>"無串接此遊戲")));
		}

		$query = $this->db->from("servers")->where("server_id", $server)->get();
		if($query->num_rows() == 0)
		{
			die(json_encode(array("result"=>"0", "error"=>"無此伺服器")));
		}

		// 登入 log
		$this->load->library("game");
        if($this->game->login($query->row(), $uid) == false)
		{
			die(json_encode(array("result"=>"0", "error"=>$this->game->error_message)));
		}

		echo json_encode(array("result"	=> 1));
		exit();
	}

	// 取得伺服器列表
	function get_server_list()
	{
		$partner = $this->input->post("partner");
		$game = $this->input->post("game");

		if (empty($partner) || empty($game))
		{
			die(json_encode(array("result"=>"0", "error"=>"參數錯誤")));
		}

		$servers = $this->db->from("servers")->where("game_id", $game)->order_by("id")->get();

		$partner_conf = $this->config->item("partner_api");
		if ( ! array_key_exists($partner, $partner_conf))
		{
			die(json_encode(array("result"=>"0", "error"=>"無串接此partner")));
		}
		if ( ! array_key_exists($game, $partner_conf[$partner]["sites"]))
		{
			die(json_encode(array("result"=>"0", "error"=>"無串接此遊戲")));
		}

		$query = $this->db->from("servers")->where("game_id", $game)->get();
		$server_list = array();
		foreach($query->result() as $row)
		{
			$server = array("server_id"=>$row->server_id,
							"name"=>$row->name,
							"status"=>0);
			$server_list[] = $server;
		}

		echo json_encode(array("result"	=> 1, "servers" => $server_list));
		exit();
	}

	// 取得伺服器角色列表
	function get_character_list()
	{
		$partner = $this->input->post("partner");
		$game = $this->input->post("game");
		$server = $this->input->post("server");
		$uid = $this->input->post("uid");

		if (empty($partner) || empty($game) || empty($uid) || empty($server))
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

		$query = $this->db->from("characters")->where("server_id", $server)->where("uid", $uid)->order_by("id")->get();
		$character_list = array();
		foreach($query->result() as $row)
		{
			//
			// query billing
			//

			$character = array(
						"id"=>$row->id,
						"character_name"=>$row->name);
			$character_list[] = $character;
		}

		echo json_encode(array("result"	=> 1, "characters" => $character_list));
		exit();
	}

	// 建立遊戲角色
	function create_character()
	{
		$partner = $this->input->post("partner");
		$game = $this->input->post("site");
		// 暫時增加檢查, 之後須修正 SDK 統一規格
		if(empty($game))
		{
			$game = $this->input->post("game");
		}
		$server = $this->input->post("server");
		$uid = $this->input->post("uid");
		$character_name = $this->input->post("character_name");

		if (empty($uid) || empty($server) || empty($game) || empty($character_name))
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

		$server_info = $this->db->from("servers")->where("server_id", $server)->get()->row();

		if (empty($server_info))
		{
			die(json_encode(array("result"=>"0", "error"=>"伺服器不存在")));
		}

		$query = $this->db->from("users")->where("uid", $uid)->get();
		if ($query->num_rows() == 0)
		{
			die(json_encode(array("result"=>"0", "error"=>"uid不存在")));
		}

		$this->load->model("g_characters");

		if ($this->g_characters->chk_character_exists($server_info, $uid, $character_name))
		{
			die(json_encode(array("result"=>"0", "error"=>"角色已存在")));
		}

		$insert_id = $this->g_characters->create_character($server_info,
			array(
				"uid" => $uid,
				'name' => $character_name,
			));

		if (empty($insert_id))
		{
			die(json_encode(array("result"=>"0", "error"=>"資料庫新增錯誤")));
		}

		echo json_encode(array("result"	=> 1,
								"id" => $insert_id,
								"character_name" => $character_name,
								"points" => 0));
		exit();
	}

	// 取得遊戲角色資料
	function get_character()
	{
		$partner = $this->input->post("partner");
		$game = $this->input->post("game");
		$server = $this->input->post("server");
		$uid = $this->input->post("uid");
		$character_name = $this->input->post("character_name");

		// 檢查參數
		if (empty($partner) || empty($uid) || empty($game) || empty($server))
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

		$server_row = $this->db->from("servers")->where("server_id", "{$server}")->get()->row();
		if (empty($server_row))
		{
			die(json_encode(array("result"=>"0", "error"=>"無此伺服器")));
		}

		$query = $this->db->where("uid", $uid)->get("users");
		if($query->num_rows() == 0)
		{
			die(json_encode(array("result"=>"0", "error"=>"無此帳號")));
		}
		$user_row = $query->row();

		$this->load->model("g_characters");
		$character = $this->g_characters->get_character($server, $uid, $character_name);

		//
		// query billing
		//
		$character_points = 0;

		echo json_encode(array("result"				=> "1",
								"id"				=> $character->id,
								"uid"				=> $character->uid,
								"character_name"	=> $character->name,
								"points"            => $character_points,
								"server_id"         => $character->server_id,
								"create_time"       => $character->create_time
								));
		exit();
	}

	// 取得遊戲角色尚未轉進遊戲點數
	function get_character_points()
	{
		$partner = $this->input->post("partner");
		$game = $this->input->post("game");
		$server = $this->input->post("server");
		$uid = $this->input->post("uid");
		$character_name = $this->input->post("character_name");

		// 檢查參數
		if (empty($partner) || empty($uid) || empty($game) || empty($server))
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

		$server_row = $this->db->from("servers")->where("server_id", "{$server}")->get()->row();
		if (empty($server_row))
		{
			die(json_encode(array("result"=>"0", "error"=>"無此伺服器")));
		}

		$query = $this->db->where("uid", $uid)->get("users");
		if($query->num_rows() == 0)
		{
			die(json_encode(array("result"=>"0", "error"=>"無此帳號")));
		}
		$user_row = $query->row();

		$this->load->model("games");
		$game_info = $this->games->get_game($game);

		$this->load->model("g_characters");
		$character = $this->g_characters->get_character($server, $uid, $character_name);

		$billing_query = $this->db->from("user_billing")
									->where("uid", $uid)
									->where("result", "5")
									->where("character_id", $character->id)
									->get();
		$character_points = 0;

		foreach($billing_query->result() as $row)
		{
			$character_points += floatval($row->amount);
		}

		echo json_encode(array("result"				=> "1",
								"id"				=> $character->id,
								"character_name"	=> $character->name,
								"get_points"        => $character_points * $game_info->exchange_rate
								));
		exit();
	}

	// 提取遊戲角色點數
	function withdraw_character_points()
	{
		$partner = $this->input->post("partner");
		$game = $this->input->post("game");
		$server = $this->input->post("server");
		$uid = $this->input->post("uid");
		$character_name = $this->input->post("character_name");

		// 檢查參數
		if (empty($partner) || empty($uid) || empty($game) || empty($server))
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

		$server_row = $this->db->from("servers")->where("server_id", "{$server}")->get()->row();
		if (empty($server_row))
		{
			die(json_encode(array("result"=>"0", "error"=>"無此伺服器")));
		}

		$query = $this->db->where("uid", $uid)->get("users");
		if($query->num_rows() == 0)
		{
			die(json_encode(array("result"=>"0", "error"=>"無此帳號")));
		}
		$user_row = $query->row();

		$this->load->model("games");
		$game_info = $this->games->get_game($game);

		$this->load->model("g_characters");
		$character = $this->g_characters->get_character($server, $uid, $character_name);

		$billing_query = $this->db->from("user_billing")
									->where("uid", $uid)
									->where("result", "5")
									->where("character_id", $character->id)
									->get();
		$character_points = 0;

		$this->load->library("g_wallet");

		foreach($billing_query->result() as $row)
		{
			$character_points += floatval($row->amount);

			// 更新訂單狀態
			$this->g_wallet->complete_order($row);
		}

		echo json_encode(array("result"				=> "1",
								"id"				=> $character->id,
								"character_name"	=> $character->name,
								"get_points"        => $character_points * $game_info->exchange_rate
								));
		exit();
	}

	// 檢查伺服器是否存活
	function check_server_alive($server_id)
	{
		$this->load->library("game");
		$res = $this->game->check_server_alive($server_id);
		die("Res:".($res == true ? 'true' : 'false'));
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
