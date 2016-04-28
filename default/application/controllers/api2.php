<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

define("RESPONSE_OK", "1");
define("RESPONSE_FAILD", "0");

//
// 會員系統廠商協作功能 API
//  - ui 前置的 function 為有提供 web 畫面的 API
//  - 裡面有執行 Javascript 的 LongeAPI.* function 為呼叫 SDK 的串接功能
//
class Api2 extends MY_Controller
{
	var $partner_conf;
	
	var $partner, $game, $time, $hash, $key;
    
    var $mongo_log;
	
	function __construct()
	{
		parent::__construct();
		$this->load->config('api');		
		$this->partner_conf = $this->config->item("partner_api");
        
        $this->load->config('g_mongodb');
        $g_mongodb = $this->config->item('mongo_db');
        $this->mongo_log = new MongoDB\Driver\Manager($g_mongodb['url']);
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

		$device_id	= !empty($_SESSION['login_deviceid']) ? $_SESSION['login_deviceid'] : $this->input->get_post("deviceid");
		$_SESSION['login_deviceid']	= $device_id;
		$_SESSION['old_deviceid'] = $this->input->get_post("old_deviceid");

        $is_duplicate_login = $this->_check_duplicate_login();
		//$is_duplicate_login = false;

		if(!$this->g_user->is_login() || $is_duplicate_login)
		{
			// 未登入, 直接進入登入畫面
			$partner    = !empty($_SESSION['login_partner']) ? $_SESSION['login_partner'] : $this->input->get_post("partner");
			$game_key   = !empty($_SESSION['login_gamekey']) ? $_SESSION['login_gamekey'] : $this->input->get_post("gamekey");
			$login_key	= !empty($_SESSION['login_key']) ? $_SESSION['login_key'] : $this->input->get_post("loginkey");
			$_SESSION['login_partner']	= $partner;
			$_SESSION['login_gamekey']	= $game_key;
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
				if(count($keys) == 6)
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
						if($this->g_user->verify_account($keys[1], $keys[2], '', $keys[3]) == true)
						{
							$_SESSION['site'] = $site;
							$_SESSION['login_channel'] = (int)$keys[4];
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
				->add_js_include("api2/login")
				->set("partner", $partner)
				->set("game_key", $game_key)
				->set("server_mode", $server_mode)
				->set("device_id", $device_id)
				->set("channel_item", $channel_item)
				->set("is_duplicate_login", $is_duplicate_login)
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
				->add_js_include("api2/login_game")
				->api_view("api2/ui_member");
		} 
		else
		{	
			$email = !empty($this->g_user->email) ? $this->g_user->email : "";
			$mobile = !empty($this->g_user->mobile) ? $this->g_user->mobile : "";
			$external_id = !empty($this->g_user->external_id) ? $this->g_user->external_id : "";
			
			header('Content-type:text/html; Charset=UTF-8');
			//echo "<script type='text/javascript'>LongeAPI.onLogoutSuccess()</script>";
			$ios_str = $this->g_user->uid."-_-".$email."-_-".$mobile."-_-".$external_id."-_-".$_SESSION['server_id']."-_-".$this->g_user->token."-_-".$_SESSION['login_channel'];
			echo "<script type='text/javascript'>
				if (typeof LongeAPI != 'undefined') {
				    LongeAPI.onLoginSuccess('{$this->g_user->uid}', '{$email}', '{$mobile}', '{$external_id}', '{$_SESSION['server_id']}', '{$this->g_user->token}', {$_SESSION['login_channel']});
				} else {
					//window.location = \"ios://loginsuccess-_-\" + encodeURIComponent('{$ios_str}');
					var iframe = document.createElement('IFRAME');
					iframe.setAttribute('src', \"ios://loginsuccess-_-\" + encodeURIComponent('{$ios_str}'));
					document.documentElement.appendChild(iframe);
					iframe.parentNode.removeChild(iframe);
					iframe = null;
				}
			</script>";
			
			$_SESSION['server_id'] = '';
			unset($_SESSION['server_id']);
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
			$_SESSION['login_channel'] = 1; // 帳密登入
			
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

        $is_duplicate_login = $this->_check_duplicate_login();
        if ($is_duplicate_login) die(json_failure('此帳號已於其他裝置進行遊戲中，請先將其登出。'));
        
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
            'device_id' => $_SESSION['login_deviceid'],
			'token' => $this->g_user->token
		);

        $this->_set_logout_time();
        
		$this->db->insert("log_game_logins", $data);	
        
		if ( $this->db->insert_id() )
		{
            $bulk = new MongoDB\Driver\BulkWrite;
            $bulk->insert(["uid" => intval($this->g_user->uid), "game_id" => $site, "server_id" => $server, "token" => $this->g_user->token, "device_id" => $_SESSION['login_deviceid'], "latest_update_time" => time()]);
            
            $this->mongo_log->executeBulkWrite("longe_log.users", $bulk);
            
            unset($bulk);
            
            $filter = ['device_id' => $_SESSION['login_deviceid'], 'game_id' => $site, 'uid' => null];
            $newObj = ['$set' => ['uid' => intval($this->g_user->uid)]];
            
            $options = ["multi" => true, "upsert" => false];
            
            $bulk = new MongoDB\Driver\BulkWrite;
            $bulk->update($filter, $newObj, $options);

            $this->mongo_log->executeBulkWrite("longe_log.le_AppPause", $bulk);
            $this->mongo_log->executeBulkWrite("longe_log.le_AppResume", $bulk);
            $this->mongo_log->executeBulkWrite("longe_log.le_AppStart", $bulk);
            $this->mongo_log->executeBulkWrite("longe_log.le_AppViewEnter", $bulk);
            $this->mongo_log->executeBulkWrite("longe_log.test_simplepost", $bulk);
            
            unset($bulk);
            
            $user_count_date = date('Y-m-d', time() + 59*60);
            $user_count_hour = date('G', time() + 59*60);
            $peak = 0;
            
            $uc_query = new MongoDB\Driver\Query([
                "game_id" => $site,
                "server_id" => $server
            ]);
        
            $uc_cursor = $this->mongo_log->executeQuery("longe_log.user_count", $uc_query);

            $uc_result = [];
            
            foreach ($uc_cursor as $document) {
                $uc_result[] = $document;
            }
            
            if (isset($uc_result[0]->count)) { 
                
                $new_count = $uc_result[0]->count + 1;
                
                $uc2_filter = ['game_id' => $site, "server_id" => $server];
                $uc2_newObj = ['$set' => ['count' => $new_count]];
                
                $uc2_options = ["multi" => false, "upsert" => true];
                
                $bulk = new MongoDB\Driver\BulkWrite;
                $bulk->update($uc2_filter, $uc2_newObj, $uc2_options);

                $this->mongo_log->executeBulkWrite("longe_log.user_count", $bulk);
                unset($bulk);
                
                $uo_query = new MongoDB\Driver\Query([
                    "game_id" => $site,
                    "server_id" => $server, 
                    "date" => $user_count_date, 
                    "hour" => intval($user_count_hour)
                ]);
            
                $uo_cursor = $this->mongo_log->executeQuery("longe_log.user_online", $uo_query);

                $uo_result = [];
                
                foreach ($uo_cursor as $document) {
                    $uo_result[] = $document;
                }
                if (!isset($uo_result[0]->peak) || (isset($uo_result[0]->peak) && $uo_result[0]->peak < $new_count)) $peak = $new_count;
            } else {
                $bulk = new MongoDB\Driver\BulkWrite;
                $bulk->insert(["game_id" => $site, "server_id" => $server, "count" => 1]);
                $this->mongo_log->executeBulkWrite("longe_log.user_count", $bulk);
                $peak = 1;
                unset($bulk);
            }
            
            if ($peak) {
                $uo_filter = ['game_id' => $site, "server_id" => $server, "date" => $user_count_date, "hour" => intval($user_count_hour)];
                $uo_newObj = ['$set' => ['peak' => $peak]];
                
                $uo_options = ["multi" => false, "upsert" => true];
                
                $bulk = new MongoDB\Driver\BulkWrite;
                $bulk->update($uo_filter, $uo_newObj, $uo_options);

                $this->mongo_log->executeBulkWrite("longe_log.user_online", $bulk);
                unset($bulk);
            }
            
		    $_SESSION['server_id'] = $server;
			die(json_message(array("message"=>"成功", "site"=>$site, "token"=>$this->g_user->token), true));
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

		$_SESSION['login_channel'] = 2; // 裝置直接登入
		echo "<script type='text/javascript'>location.href='/api2/ui_login?site={$site}';</script>";
	}

	// 第三方登入
	function ui_channel_login()
	{
		$site = $this->_get_site();
		$channel = $this->input->get("channel", true);

		$_SESSION['site'] = $site;
		$_SESSION['redirect_url'] = 'https://api.longeplay.com.tw/api2/ui_login';

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
			$_SESSION['login_channel'] = 3; // 網頁 Facebook 登入
		}
		else if($channel == "google")
		{
			$_SESSION['login_channel'] = 4; // 網頁 Google 登入
		}

		$this->load->library("channel_api/{$lib}", $param);
		$result = $this->{$lib}->login($site, $login_param);
		if ($result == false)
		{
			die($this->{$lib}->error_message);
		}
	}
	
	// 行動裝置 Facebook SDK 登入成功後續銜接
	function ui_mobile_facebook_login()
	{
		header('Content-type:text/html; Charset=UTF-8');

		$site = $this->_get_site();
        $facebook_uid = $this->input->get('uid');
		$external_id = $facebook_uid."@facebook";

		if(!empty($facebook_uid))
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
			echo "<script type='text/javascript'>alert('無法取得 Facebook 帳號資訊，登入失敗!');</script>";
		}

		$_SESSION['login_channel'] = 3; // 行動裝置 Facebook 登入
		echo "<script type='text/javascript'>location.href='/api2/ui_login?site={$site}';</script>";
	}
	
	// 檢查同一個 facebook 使用者的 facebook id 列表中是否已有紀錄
	function check_facebook_uid()
	{
		$uid_list = $this->input->post('uid_list');
		$uids = explode(",", $uid_list);
		
		foreach($uids as $uid)
		{
			if($this->g_user->verify_account('', '', '', $uid."@facebook")==true)
			{
				die($uid);
			}
		}
		
		die('0');
	}
	
	// 行動裝置 Google SDK 登入成功後續銜接
	function ui_mobile_google_login()
	{
		header('Content-type:text/html; Charset=UTF-8');

		$site = $this->_get_site();
        $google_uid = $this->input->get('uid');
		$external_id = $google_uid."@google";

		if(!empty($google_uid))
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
			echo "<script type='text/javascript'>alert('無法取得 Google 帳號資訊，登入失敗!');</script>";
		}

		$_SESSION['login_channel'] = 4; // 行動裝置 Google 登入
		echo "<script type='text/javascript'>location.href='/api2/ui_login?site={$site}';</script>";
	}

	// 更換帳號
	function ui_change_account()
	{
	    $this->_set_logout_time();

		$device_id	= $_SESSION['login_deviceid'];
		
		// 登出然後跳回登入畫面
		header('content-type:text/html; charset=utf-8');

		$site = $this->_get_site();
		
		$this->g_user->logout();
		
		$_SESSION['login_key'] = '';
		$_SESSION['server_id'] = '';
		unset($_SESSION['login_key']);
		unset($_SESSION['server_id']);
		
        die("<script type='text/javascript'>location.href='/api2/ui_login?deviceid={$device_id}&site={$site}&change_account=1'</script>");
	}
	
	// 帳號登出
	function ui_logout()
	{
	    $this->_set_logout_time();
        
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
                LongeAPI.onLogoutSuccess();
            } else {
                //window.location = \"ios://logoutsuccess\";
				var iframe = document.createElement('IFRAME');
				iframe.setAttribute('src', \"ios://logoutsuccess\");
				document.documentElement.appendChild(iframe);
				iframe.parentNode.removeChild(iframe);
				iframe = null;
	        }
		</script>";
	}

	// 帳號註冊
	function ui_register()
	{
		$this->_init_layout()
			->add_css_link("login_api")
			->add_js_include("api2/register")
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
			->add_js_include("api2/bind_account")
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
			->add_js_include("api2/forgot_password")
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
			->add_js_include("api2/change_password")
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
		$partner_order_id = $this->input->get_post("poid");
		
		$_SESSION['site'] = $site;
			
		if(!$this->g_user->is_login())
		{
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
				$partner_game = $partner_conf[$partner]["sites"][$site];

				//$chk_code = md5($partner.$email.$server_id.$mobile.$partner_game['key'].$device_id);
				//if($chk_code != $pcode)
				//	die();
				*/
				if($this->g_user->verify_account($email, $mobile, '', $external_id) != true)
					die();
			}
			else
				$this->_require_login();
		}

		$this->load->config("g_gash");
		$this->load->config("g_payment");

		// 讀取遊戲列表
		$games = $this->db->from("games")->where("is_active", "1")->get();
		// 讀取伺服器列表
		$servers = $this->db->where("is_transaction_active", "1")->order_by("server_id")->get("servers");
		// 讀取玩家角色列表
		$characters = $this->db->from("characters")->where("uid", $this->g_user->uid)->get();

		$this->_init_layout()
			->set("games", $games)
			->set("servers", $servers)
			->set("server_id", $server_id)
			->set("characters", $characters)
			->set("partner_order_id", $partner_order_id)
			->add_css_link("login_api")
			->add_css_link("money")
			->add_js_include("payment/index")
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

		//$_SESSION['site']				= '';
		$_SESSION['payment_game']		= '';
		$_SESSION['payment_server']		= '';
		$_SESSION['payment_character']	= '';
		$_SESSION['payment_type']		= '';
		$_SESSION['payment_channel']	= '';
		//unset($_SESSION['site']);
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
			->add_js_include("api2/payment_test")
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

		echo "\"ios://paymentresult-_-{$game_id}-_-{$server_id}-_-{$character->name}-_-{$billingType}-_-{$payType}-_-{$money}-_-{$get_point}\"
		<script type='text/javascript'>
	        if (typeof LongeAPI != 'undefined') { 
                LongeAPI.onPaymentSuccess('{$game_id}','{$server_id}','{$character->name}','{$billingType}','{$payType}',parseInt('{$money}',10),parseInt('{$get_point}',10)); 
            } else {
                //window.location = \"ios://paymentresult-_-{$game_id}-_-{$server_id}-_-{$character->name}-_-{$billingType}-_-{$payType}-_-{$money}-_-{$get_point}\";
				var iframe = document.createElement('IFRAME');
				iframe.setAttribute('src', \"ios://paymentresult-_-{$game_id}-_-{$server_id}-_-{$character->name}-_-{$billingType}-_-{$payType}-_-{$money}-_-{$get_point}\");
				document.documentElement.appendChild(iframe);
				iframe.parentNode.removeChild(iframe);
	        }
		</script>";
	}

	// iOS IAP 儲值選擇畫面
	function ui_ios_iap_view()
	{
		$this->_init_layout()
			->add_css_link("login_api")
			->add_css_link("money")
			->api_view();
	}
	
	// 開始 iOS IAP 訂單
	function ios_iap_start()
	{
		$product_id = $this->input->post("product_id");
		$uid = $this->input->post("uid");
		$app_id = $this->input->post("app_id");
		$server_id = $this->input->post("server_id");
		$verify_code = $this->input->post("verify_code");
		$partner_order_id = $this->input->post("partner_order_id");
		
		$server_info = $this->db->from("servers")->where("server_id", $server_id)->get()->row();
		if (empty($server_info))
		{
			$server_info = $this->db->from("servers")->where("address", $server_id)->get()->row();
			if (empty($server_info))
			{
				die(json_encode(array("result"=>"0", "error"=>"伺服器不存在")));
			}
			
			$server_id = $server_info->server_id;
		}
		
		$this->load->library("g_wallet");

		$order_id = $this->g_wallet->produce_iap_order($uid, "inapp_billing_ios", "1", $server_id, $partner_order_id, $product_id . "|" . $verify_code);
		if(empty($order_id))
			die(json_encode(array("result"=>0, "msg"=>$this->g_wallet->error_message)));
		
		die(json_encode(array("result"=>1, "productId"=>$product_id, "orderId"=>$order_id)));
	}
	
	// 取消 iOS IAP 訂單
	function ios_iap_cancel()
	{
		$order_id = $this->input->post("order_id");
		$product_id = $this->input->post("product_id");
		$verify_code = $this->input->post("verify_code");

		log_message("error", "ios_iap_cancel: {$order_id}");
		
		$this->load->library("g_wallet");
		
		$order = $this->g_wallet->get_order($order_id);
		if(!empty($order))
			$this->g_wallet->cancel_order($order);

		die(json_encode(array("result"=>1)));
	}
	
	function _send_ios_verify($url, $data)
	{
        $ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$curl_res = curl_exec($ch);
		curl_close($ch);

		return json_decode($curl_res);
	}
	
	// 驗證並完成訂單
	function ios_verify_receipt()
	{
		$receipt_data = $this->input->post("receipt_data");
		$order_id = $this->input->post("order_id");
		$product_id = $this->input->post("product_id");
		$price = $this->input->post("price");
		$currency = $this->input->post("currency");
		$transaction_id = $this->input->post("transaction_id");
		$partner_order_id = $this->input->post("partner_order_id");
		$uid = $this->input->post("uid");
		$server_id = $this->input->post("server_id");
		$character_id = $this->input->post("character_id");
		$verify_code = $this->input->post("verify_code");
		
		// 先讀取資料庫的訂單
		$this->load->library("g_wallet");
		
		$order = $this->g_wallet->get_order($order_id);
		if(empty($order))
		{
			// 訂單不存在
			die(json_encode(array("result"=>0, "msg"=>"Order not found.")));
		}

		$amount = $price;
		
		// 若不是台幣, 要取得台幣價格
		if($currency !== "TWD")
		{
			log_message("error", "ios_verify_receipt: User {$uid} using {$currency} for payment.");
			$pos = strpos($product_id, "_");
			if($pos !== false)
				$amount = intval(substr($product_id, $pos + 1));
		}
		
		// 更新訂單資料
		$this->g_wallet->update_order($order, array("amount"=>$amount,"order_no"=>$transaction_id));
		
		// 取得 server 資料
		$server_num = $server_id;
		
		$server_info = $this->db->from("servers")->where("server_id", $server_id)->get()->row();
		if (empty($server_info))
		{
			$server_info = $this->db->from("servers")->where("address", $server_id)->get()->row();
			if (empty($server_info))
			{
				// Server ID 有問題
				$this->g_wallet->cancel_other_order($order, $order->note . "|App server {$server_id} not exist.");
				
				die(json_encode(array("result"=>"0", "msg"=>"App server not exist.")));
			}
			
			$server_id = $server_info->server_id;
		}

		// 驗證訂單
		if(intval($order->result) != 0)
		{
			// 訂單狀態不符
			log_message("error", "ios_verify_receipt: Order {$order_id} status error.");
			
			die(json_encode(array("result"=>0, "msg"=>"Order status error.")));
		}
		
		$vc = $product_id . "|" . $verify_code;

		if($order->uid !== $uid ||
			$order->server_id !== $server_id ||
			$order->note !== $vc ||
			$order->partner_order_id !== $partner_order_id)
		{
			// 未通過資料核對, 關閉訂單
			$this->g_wallet->cancel_other_order($order, $order->note . "|Order information not match.");

			die(json_encode(array("result"=>0, "msg"=>"Order information not match.")));
		}
		
		// 檢查訂單號碼格式
		// *** 暫時使用 ***
		if(strpos($transaction_id, "-") !== false)
		{
			log_message("error", "ios_verify_receipt: Fake order {$transaction_id}.");
			//
			die(json_encode(array("result"=>0, "msg"=>"Order not match.")));
		}
		
		// 再向 AppStore 驗證
		$url = "https://buy.itunes.apple.com/verifyReceipt";
		$result = $this->_send_ios_verify($url, json_encode(array("receipt-data"=>$receipt_data)));
		
		if($result->status == 21007)
		{
			// Sandbox 模式
			$url = "https://sandbox.itunes.apple.com/verifyReceipt";
			$result = $this->_send_ios_verify($url, json_encode(array("receipt-data"=>$receipt_data)));
		}

		if($result->status != 0)
		{
			// 未通過 AppStore 驗證, 關閉訂單
			$this->g_wallet->cancel_other_order($order, $order->note . "|Receipt data not match.");
			
			die(json_encode(array("result"=>0, "msg"=>"Receipt data not match!")));
		}

		// 驗證返回資料
		if(empty($result->receipt))
		{
			// 訂單資料錯誤
			log_message("error", "ios_verify_receipt: Receipt result error.");
			
			die(json_encode(array("result"=>0, "msg"=>"Receipt result error.")));
		}
		if(empty($result->receipt->bid))
		{
			// 訂單資料錯誤
			log_message("error", "ios_verify_receipt: Receipt result error.");
			
			die(json_encode(array("result"=>0, "msg"=>"Receipt result error.")));
		}

		$this->load->library("game_api/{$server_info->game_id}");
		if(strcmp($result->receipt->bid, $this->{$server_info->game_id}->get_apple_bundle_id()) != 0)
		{
			// 訂單資料錯誤
			log_message("error", "ios_verify_receipt: Receipt result error.");
			
			die(json_encode(array("result"=>0, "msg"=>"Receipt result error.")));
		}
		
		// 驗證成功, 先結掉儲值訂單
		$this->g_wallet->complete_order($order);
		
		// 記錄轉點
		$transfer_id = $this->g_wallet->produce_order($uid, "top_up_account", "2", $amount, $server_id, $partner_order_id, $character_id, $transaction_id);
		if (empty($transfer_id))
		{
			// 建立轉點記錄失敗
			log_message("error", "ios_verify_receipt: Create transfer log for order-{$order_id} failed!");
			
			die(json_encode(array("result"=>0, "msg"=>"Create transfer log failed!")));
		}
		$transfer_order = $this->g_wallet->get_order($transfer_id);

		// 呼叫遊戲入點機制
		$res = $this->{$server_info->game_id}->iap_transfer($transfer_order, $server_info, "app_store", $product_id, $price, $currency);
		$error_message = $this->{$server_info->game_id}->error_message;

		if($res === "1")
		{
			// 成功, 結掉訂單
			$this->g_wallet->complete_order($transfer_order);
			
			die(json_encode(array("result"=>1, "transactionId"=>$transaction_id, "productId"=>$product_id)));
		}
		else
		{
			// 轉入遊戲伺服器失敗
			$this->g_wallet->ready_for_game_order($transfer_order, $order->note . "|" . $error_message);
			
			die(json_encode(array("result"=>0, "msg"=>$error_message)));
		}
	}
	
	// Android IAP 儲值選擇畫面
	function ui_android_iap_view()
	{
		$this->_init_layout()
			->add_css_link("login_api")
			->add_css_link("money")
			->api_view();
	}
	
	// 開始 Android IAP 訂單
	function android_iap_start()
	{
		$product_id = $this->input->post("product_id");
		$uid = $this->input->post("uid");
		$app_id = $this->input->post("app_id");
		$server_id = $this->input->post("server_id");
		$verify_code = $this->input->post("verify_code");
		$partner_order_id = $this->input->post("partner_order_id");
		
		$server_info = $this->db->from("servers")->where("server_id", $server_id)->get()->row();
		if (empty($server_info))
		{
			$server_info = $this->db->from("servers")->where("address", $server_id)->get()->row();
			if (empty($server_info))
			{
				die(json_encode(array("result"=>"0", "error"=>"伺服器不存在")));
			}
			
			$server_id = $server_info->server_id;
		}

		log_message("error", "android_iap_start: {$uid},{$server_id},{$partner_order_id}");
		
		$this->load->library("g_wallet");
		
		$order_id = $this->g_wallet->produce_iap_order($uid, "inapp_billing_google", "1", $server_id, $partner_order_id, $product_id . "|" . $verify_code);
		
		if(empty($order_id))
			die(json_encode(array("result"=>0, "msg"=>$this->g_wallet->error_message)));
		
		die(json_encode(array("result"=>1, "productId"=>$product_id, "orderId"=>$order_id)));
	}
	
	// 取消 Android IAP 訂單
	function android_iap_cancel()
	{
		$order_id = $this->input->post("order_id");
		$product_id = $this->input->post("product_id");
		$verify_code = $this->input->post("verify_code");

		log_message("error", "android_iap_cancel: {$order_id}");
		
		$this->load->library("g_wallet");
		
		$order = $this->g_wallet->get_order($order_id);
		if(!empty($order))
			$this->g_wallet->cancel_order($order);

		die(json_encode(array("result"=>1)));
	}
	
	// 驗證並完成訂單
	function android_verify_receipt()
	{
		$order_id = $this->input->post("order_id");
		$product_id = $this->input->post("product_id");
		$price = $this->input->post("price");
		$currency = $this->input->post("currency");
		$transaction_id = $this->input->post("transaction_id");
		$partner_order_id = $this->input->post("partner_order_id");
		$uid = $this->input->post("uid");
		$server_id = $this->input->post("server_id");
		$character_id = $this->input->post("character_id");
		$verify_code = $this->input->post("verify_code");

		log_message("error", "android_verify_receipt: order_id-{$order_id}");
		
		// 先讀取資料庫的訂單
		$this->load->library("g_wallet");
		
		$order = $this->g_wallet->get_order($order_id);
		if(empty($order))
		{
			// 訂單不存在
			die(json_encode(array("result"=>0, "msg"=>"Order not found.")));
		}

		$amount = $price;
		
		// 若不是台幣, 要取得台幣價格
		if($currency !== "TWD")
		{
			log_message("error", "android_verify_receipt: User {$uid} using {$currency} for payment.");
			$pos = strpos($product_id, ".");
			if($pos === false)
				$pos = strpos($product_id, "_");

			if($pos !== false)
				$amount = intval(substr($product_id, $pos + 1));
		}
		
		$this->g_wallet->update_order($order, array("amount"=>$amount,"order_no"=>$transaction_id));
		
		// 取得 server 資料
		$server_num = $server_id;
		
		$server_info = $this->db->from("servers")->where("server_id", $server_id)->get()->row();
		if (empty($server_info))
		{
			$server_info = $this->db->from("servers")->where("address", $server_id)->get()->row();
			if (empty($server_info))
			{
				die(json_encode(array("result"=>"0", "msg"=>"App server not exist.")));
			}
			
			$server_id = $server_info->server_id;
		}
		
		// 驗證訂單
		if(intval($order->result) != 0)
		{
			// 訂單狀態不符
			log_message("error", "android_verify_receipt: Order {$order_id} status error.");
			
			die(json_encode(array("result"=>0, "msg"=>"Order status error.")));
		}
		
		$vc = $product_id . "|" . $verify_code;

		if($order->uid !== $uid ||
			$order->server_id !== $server_id ||
			$order->note !== $vc ||
			$order->partner_order_id !== $partner_order_id)
		{
			// 未通過資料核對, 關閉訂單
			$this->g_wallet->cancel_other_order($order, $order->note . "|Order information not match.");

			die(json_encode(array("result"=>0, "msg"=>"Order information not match.")));
		}

		// 檢查訂單號碼格式
		// *** 暫時使用 ***
		if(strpos($transaction_id, "GPA.") !== 0)
		{
			log_message("error", "android_verify_receipt: Fake order {$transaction_id}.");
			//
			die(json_encode(array("result"=>0, "msg"=>"Order not match.")));
		}
		
		// 驗證成功, 先結掉儲值訂單
		$this->g_wallet->complete_order($order);
		
		// 記錄轉點
		$transfer_id = $this->g_wallet->produce_order($uid, "top_up_account", "2", $amount, $server_id, $partner_order_id, $character_id, $transaction_id);
		if (empty($transfer_id))
		{
			// 建立轉點記錄失敗
			log_message("error", "android_verify_receipt: Create transfer log for order-{$order_id} failed!");
			
			die(json_encode(array("result"=>0, "msg"=>"Create transfer log failed!")));
		}
		$transfer_order = $this->g_wallet->get_order($transfer_id);

		// 呼叫遊戲入點機制
		$this->load->library("game_api/{$server_info->game_id}");
		$res = $this->{$server_info->game_id}->iap_transfer($transfer_order, $server_info, "google_play", $product_id, $price, $currency);
		$error_message = $this->{$server_info->game_id}->error_message;

		if($res === "1")
		{
			// 成功, 結掉訂單
			$this->g_wallet->complete_order($transfer_order);

			die(json_encode(array("result"=>1, "transactionId"=>$transaction_id, "productId"=>$product_id)));
		}
		else
		{
			// 轉入遊戲伺服器失敗
			$this->g_wallet->ready_for_game_order($transfer_order, $order->note . "|" . $error_message);
			
			die(json_encode(array("result"=>0, "msg"=>$error_message)));
		}
	}
	
	// 客服頁面
	function ui_service()
	{
		$this->_init_layout()
			->add_css_link("login_api")
			->add_css_link("money")
			->api_view();
	}

	function ui_service_question()
	{
		$this->load->config("service");
		
		$server = $this->db->from("servers gi")
			->join("games g", "gi.game_id=g.game_id")->get();
		
		$games = $this->db->from("games")->where("is_active", "1")->get();
		//$servers = $this->db->where_in("server_status", array("public", "maintaining"))->order_by("server_id")->get("servers");
		$servers = $this->db->where("is_transaction_active", "1")->order_by("server_id")->get("servers");

		// 讀取玩家角色列表
		$characters = $this->db->from("characters")->where("uid", $this->g_user->uid)->get();

		$this->_init_layout()
			->add_js_include("api2/question")
			->set("games", $games)
			->set("servers", $servers)
			->set("characters", $characters)
			->add_css_link("login_api")
			->add_css_link("money")
			->add_css_link("server")
			->api_view();
	}

	function ui_service_list()
	{
		$this->db->select("q.*")
			->where("q.uid", $this->g_user->uid)->from("questions q")
			->order_by("id", "desc");
		
		if ($this->input->get("status")) {
			$this->db->where("status", $this->input->get("status"));
		}
		else {
			$this->db->where("status >", "0");
		} 
		
		$query = $this->db->get();
		
		$this->_init_layout()
			->set("query", $query)
			->add_css_link("login_api")
			->add_css_link("server")
			->api_view();
	}
	
	function service_question_ajax()
	{
		if ( ! $this->input->post("content")) die(json_encode(array("status"=>"failure", "message"=>"無內文")));
		
		$query = $this->db->query("SELECT count(*) > (3-1) as chk FROM questions WHERE uid={$this->g_user->uid} and create_time > date_sub(now(), INTERVAL 1 MINUTE)");		
		if ($query->row()->chk) die(json_encode(array("status"=>"failure", "message"=>"請勿重覆提問，若有未說明問題，請以原提問進行補述!")));

		$data = array(
			"uid" => $this->g_user->uid,				
			'type' => $this->input->post("question_type"),
			'server_id' => $this->input->post("server"),
			'character_name' => htmlspecialchars($this->input->post("character_name")),
			'content' => nl2br(htmlspecialchars($this->input->post("content"))),
		);
		
		$this->load->library('upload');
		$config['upload_path'] = realpath("p/upload");
		$config['allowed_types'] = 'gif|jpg|bmp|png';
		$config['max_size']	= '6144'; //1MB
		$config['max_width'] = '6144';
		$config['max_height'] = '6144';
		$config['encrypt_name'] = true;
		
		$upload_cnt = 0;
		if ( ! empty($_FILES["file01"]["name"]))
		{
			$this->upload->initialize($config);
			if ( ! $this->upload->do_upload("file01"))
			{
				die(json_encode(array("status"=>"failure", "message"=>$this->upload->display_errors('', ''))));
			}
			else
			{
				$upload_data = $this->upload->data();
				$data['pic_path'.(++$upload_cnt)] = site_url("p/upload/{$upload_data['file_name']}");					
			}
		}
		
		if ( ! empty($_FILES["file02"]["name"]))
		{
			$this->upload->initialize($config);
			if ( ! $this->upload->do_upload("file02"))
			{
				die(json_encode(array("status"=>"failure", "message"=>$this->upload->display_errors('', ''))));
			}
			else
			{
				$upload_data = $this->upload->data();
				$data['pic_path'.(++$upload_cnt)] = site_url("p/upload/{$upload_data['file_name']}");					
			}
		}
		if ( ! empty($_FILES["file03"]["name"]))
		{
			$this->upload->initialize($config);
			if ( ! $this->upload->do_upload("file03"))
			{
				die(json_encode(array("status"=>"failure", "message"=>$this->upload->display_errors('', ''))));
			}
			else
			{
				$upload_data = $this->upload->data();
				$data['pic_path'.(++$upload_cnt)] = site_url("p/upload/{$upload_data['file_name']}");					
			}
		}

		$this->db
			->set("create_time", "now()", false)
			->set("update_time", "now()", false)
			->insert("questions", $data);

		die(json_encode(array("status"=>"success", "site"=> $site)));
	}
	
	function ui_service_view($id)
	{
		$question = $this->db->select("q.*, g.name as game_name, gi.name as server_name, u.mobile, u.email")
					->where("q.uid", $this->g_user->uid)
					->where("q.id", $id)
					->where("q.status >", "0")
					->from("questions q")
					->join("servers gi", "gi.server_id=q.server_id")
					->join("games g", "g.game_id=gi.game_id")
					->join("users u", "u.uid=q.uid")
					->get()->row();
		
		if ($question)
		{
			if ($question->status == '2' || $question->status == '4') {
				$this->db->where("id", $id)->update("questions", array("is_read"=>'1'));
			}		
			$replies = $this->db->from("question_replies")->where("question_id", $id)->order_by("id", "asc")->get();
		}
		else {
			$replies = false;
		}
		
		$this->_init_layout()
			->add_css_link("service")
			->add_css_link("server")
			->add_js_include("api2/view")
			->add_js_include("jquery.blockUI")
			->add_js_include("default")
			->set("replies", $replies)
			->set("question", $question)
			->api_view();
	}
	
	function service_reply_json()
	{
		$query = $this->db->query("SELECT count(*) > (3-1) as chk FROM question_replies WHERE uid={$this->g_user->uid} and create_time > date_sub(now(), INTERVAL 1 MINUTE)");		
		if ($query->row()->chk) die(json_encode(array("status"=>"failure", "message"=>"請勿重覆提問!")));		
		
		$question_id = $this->input->post("question_id");
		
		$data = array(
			"uid" => $this->g_user->uid,
			"question_id" => $question_id,
			'content' => nl2br(htmlspecialchars($this->input->post("content"))),
		);		
		
		$this->db
			->set("create_time", "now()", false)
			->insert("question_replies", $data);
		
		$this->db->where("id", $question_id)->update("questions", array("is_read"=>'0', "status"=>'1'));		
		
		die(json_encode(array("status"=>"success")));		
	}
	
	function service_close($id)
	{
		$question = $this->db->where("id", $id)->from("questions q")->get()->row();
		if ($question->uid <> $this->g_user->uid) die(json_encode(array("status"=>"failure", "message"=>"權限不足")));
		
		$this->db->set("status", "4")->where("id", $id)->update("questions");
		die(json_encode(array("status"=>"success")));	
	}
	
	// 檢查合作廠商與串接遊戲
	function _check_partner_game($partner, $game_id)
	{
		$partner_conf = $this->config->item("partner_api");
		if ( ! array_key_exists($partner, $partner_conf))
		{
			return "無串接此partner";
		}
		if ( ! array_key_exists($game_id, $partner_conf[$partner]["sites"]))
		{
			return "無串接此遊戲";
		}
		
		return '1';
	}

	// 設定登入伺服器
	function set_login_server()
	{
		// *** 2016-04-21 準備將功能轉移到 server_api
		//
		/*
		$partner = $this->input->post("partner");
		$game_id = $this->input->post("site");
		// 暫時增加檢查, 之後須修正 SDK 統一規格
		if(empty($game_id))
		{
			$game_id = $this->input->post("game");
		}
		$server_id = $this->input->post("server");
		$uid = $this->input->post("uid");
		
		if (empty($partner) || empty($game_id))
		{
			die(json_encode(array("result"=>"0", "error"=>"參數錯誤")));
		}

		$msg = $this->_check_partner_game($partner, $game_id);
		if($msg != '1')
		{
			die(json_encode(array("result"=>"0", "error"=>$msg)));
		}
		
		$query = $this->db->from("servers")->where("server_id", $server_id)->get();
		if($query->num_rows() == 0)
		{
			die(json_encode(array("result"=>"0", "error"=>"無此伺服器")));
		}
		$server = $query->row();
		if($server->game_id != $game_id)
		{
			die(json_encode(array("result"=>"0", "error"=>"無此伺服器")));
		}
		*/
/*
		// 登入 log
		$this->load->library("game");
        if($this->game->login($server, $uid) == false)
		{
			die(json_encode(array("result"=>"0", "error"=>$this->game->error_message)));
		}
*/
		echo json_encode(array("result"	=> 1));
		exit();
	}

	// 取得伺服器列表
	function get_server_list()
	{
		$partner = $this->input->post("partner");
		$game_id = $this->input->post("site");
		// 暫時增加檢查, 之後須修正 SDK 統一規格
		if(empty($game_id))
		{
			$game_id = $this->input->post("game");
		}

		if (empty($partner) || empty($game_id))
		{
			die(json_encode(array("result"=>"0", "error"=>"參數錯誤")));
		}

		$msg = $this->_check_partner_game($partner, $game_id);
		if($msg != '1')
		{
			die(json_encode(array("result"=>"0", "error"=>$msg)));
		}

		$query = $this->db->from("servers")->where("game_id", $game_id)->get();
		$server_list = array();
		foreach($query->result() as $row)
		{
			$server = array("server_id"=>$row->server_id,
							"name"=>$row->name,
							"key"=>$row->server_connection_key,
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
		$game_id = $this->input->post("site");
		// 暫時增加檢查, 之後須修正 SDK 統一規格
		if(empty($game_id))
		{
			$game_id = $this->input->post("game");
		}
		$server_id = $this->input->post("server");
		$uid = $this->input->post("uid");

		if (empty($partner) || empty($game_id) || empty($uid) || empty($server_id))
		{
			die(json_encode(array("result"=>"0", "error"=>"參數錯誤")));
		}

		$msg = $this->_check_partner_game($partner, $game_id);
		if($msg != '1')
		{
			die(json_encode(array("result"=>"0", "error"=>$msg)));
		}

		$query = $this->db->from("characters")->where("server_id", $server_id)->where("uid", $uid)->order_by("id")->get();
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
		// *** 2016-04-21 將功能轉移到 server_api
		//
		/*
		$partner = $this->input->post("partner");
		$game_id = $this->input->post("site");
		// 暫時增加檢查, 之後須修正 SDK 統一規格
		if(empty($game_id))
		{
			$game_id = $this->input->post("game");
		}
		$server_id = $this->input->post("server");
		$uid = $this->input->post("uid");
		$character_id = $this->input->post("character_id");
		$character_name = $this->input->post("character_name");
		if(empty($character_name))
			$character_name = $this->input->post("caracter_name");
		
		// 若沒設定 server_id, 則找出最近一次登入的 server
		if(empty($server_id))
		{
			$login_game = $this->db->from("log_game_logins")->where("uid", $uid)->order_by("create_time desc")->limit(1)->get()->row();
			if(!empty($login_game))
			{
				$server_id = $login_game->server_id;
			}
		}
		
		if (empty($uid) || empty($server_id) || empty($game_id) || empty($character_name))
		{
			die(json_encode(array("result"=>"0", "error"=>"參數錯誤")));
		}
		
		$msg = $this->_check_partner_game($partner, $game_id);
		if($msg != '1')
		{
			die(json_encode(array("result"=>"0", "error"=>$msg)));
		}

		$server_info = $this->db->from("servers")->where("server_id", $server_id)->get()->row();
		if (empty($server_info))
		{
			$server_info = $this->db->from("servers")->where("address", $server_id)->get()->row();
			if (empty($server_info))
			{
				die(json_encode(array("result"=>"0", "error"=>"伺服器不存在")));
			}
			
			$server_id = $server_info->server_id;
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
				'in_game_id' => $character_id
			));

		if (empty($insert_id))
		{
			die(json_encode(array("result"=>"0", "error"=>"資料庫新增錯誤")));
		}

		echo json_encode(array("result"	=> 1,
								"id" => $insert_id,
								"character_name" => $character_name,
								"points" => 0));
		*/
		echo json_encode(array("result"	=> 1));
		
		exit();
	}

	// 取得遊戲角色資料
	function get_character()
	{
		$partner = $this->input->post("partner");
		$game_id = $this->input->post("site");
		// 暫時增加檢查, 之後須修正 SDK 統一規格
		if(empty($game_id))
		{
			$game_id = $this->input->post("game");
		}
		$server_id = $this->input->post("server");
		$uid = $this->input->post("uid");
		$character_name = $this->input->post("character_name");

		// 檢查參數
		if (empty($partner) || empty($uid) || empty($game_id) || empty($server_id))
		{
			die(json_encode(array("result"=>"0", "error"=>"參數錯誤")));
		}

		$msg = $this->_check_partner_game($partner, $game_id);
		if($msg != '1')
		{
			die(json_encode(array("result"=>"0", "error"=>$msg)));
		}

		$server_row = $this->db->from("servers")->where("server_id", "{$server_id}")->get()->row();
		if (empty($server_row))
		{
			die(json_encode(array("result"=>"0", "error"=>"無此伺服器")));
		}

		$query = $this->db->where("uid", $uid)->get("users");
		if($query->num_rows() == 0)
		{
			die(json_encode(array("result"=>"0", "error"=>"無此帳號")));
		}

		$this->load->model("g_characters");
		$character = $this->g_characters->get_character($server_id, $uid, $character_name);

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
		$game_id = $this->input->post("site");
		// 暫時增加檢查, 之後須修正 SDK 統一規格
		if(empty($game_id))
		{
			$game_id = $this->input->post("game");
		}
		$server_id = $this->input->post("server");
		$uid = $this->input->post("uid");
		$character_name = $this->input->post("character_name");

		// 檢查參數
		if (empty($partner) || empty($uid) || empty($game_id) || empty($server_id))
		{
			die(json_encode(array("result"=>"0", "error"=>"參數錯誤")));
		}

		$msg = $this->_check_partner_game($partner, $game_id);
		if($msg != '1')
		{
			die(json_encode(array("result"=>"0", "error"=>$msg)));
		}

		$server_row = $this->db->from("servers")->where("server_id", "{$server_id}")->get()->row();
		if (empty($server_row))
		{
			die(json_encode(array("result"=>"0", "error"=>"無此伺服器")));
		}

		$query = $this->db->where("uid", $uid)->get("users");
		if($query->num_rows() == 0)
		{
			die(json_encode(array("result"=>"0", "error"=>"無此帳號")));
		}

		$this->load->model("games");
		$game_info = $this->games->get_game($game_id);

		$this->load->model("g_characters");
		$character = $this->g_characters->get_character($server_id, $uid, $character_name);

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
		$game_id = $this->input->post("site");
		// 暫時增加檢查, 之後須修正 SDK 統一規格
		if(empty($game_id))
		{
			$game_id = $this->input->post("game");
		}
		$server_id = $this->input->post("server");
		$uid = $this->input->post("uid");
		$character_name = $this->input->post("character_name");

		// 檢查參數
		if (empty($partner) || empty($uid) || empty($game_id) || empty($server_id))
		{
			die(json_encode(array("result"=>"0", "error"=>"參數錯誤")));
		}

		$msg = $this->_check_partner_game($partner, $game_id);
		if($msg != '1')
		{
			die(json_encode(array("result"=>"0", "error"=>$msg)));
		}

		$server_row = $this->db->from("servers")->where("server_id", "{$server_id}")->get()->row();
		if (empty($server_row))
		{
			die(json_encode(array("result"=>"0", "error"=>"無此伺服器")));
		}

		$query = $this->db->where("uid", $uid)->get("users");
		if($query->num_rows() == 0)
		{
			die(json_encode(array("result"=>"0", "error"=>"無此帳號")));
		}

		$this->load->model("games");
		$game_info = $this->games->get_game($game_id);

		$this->load->model("g_characters");
		$character = $this->g_characters->get_character($server_id, $uid, $character_name);

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

	// 紀錄遊戲內儲值(AppStore, GooglePlay inapp payment)
	function log_inapp_billing()
	{
		$partner = $this->input->post("partner");
		$game_id = $this->input->post("site");
		$server_id = $this->input->post("server");
		$uid = $this->input->post("uid");
		$character_name = $this->input->post("character_name");
		$channel = $this->input->post("channel");
		
		$msg = $this->_check_partner_game($partner, $game_id);
		if($msg != '1')
		{
			die(json_encode(array("result"=>"0", "error"=>$msg)));
		}

		$server_row = $this->db->from("servers")->where("server_id", "{$server_id}")->get()->row();
		if (empty($server_row))
		{
			die(json_encode(array("result"=>"0", "error"=>"無此伺服器")));
		}

		$query = $this->db->where("uid", $uid)->get("users");
		if($query->num_rows() == 0)
		{
			die(json_encode(array("result"=>"0", "error"=>"無此帳號")));
		}

		// 檢查參數
		if (empty($partner) || empty($uid) || empty($game_id) || empty($server_id) || empty($channel))
		{
			die(json_encode(array("result"=>"0", "error"=>"參數錯誤")));
		}

		$this->load->model("games");
		$game_info = $this->games->get_game($game_id);

		// 若角色名稱為空字串, 表示遊戲固定每個帳號只能有一個角色
		$this->load->model("g_characters");
		if($character_name != null && $character_name != "")
		{
			$character = $this->g_characters->get_character($server_id, $uid, $character_name);
		}
		else
		{
			$character = $this->g_characters->get_latest_character($server_id, $uid);
		}

		$order_id = $this->input->post("order_id");
		$product_id = $this->input->post("product_id");
		$money = $this->input->post("money");
        
		$country_code = geoip_country_code3_by_name($_SERVER['REMOTE_ADDR']);
		$country_code = ($country_code) ? $country_code : null;

		// 設定紀錄資料
		$user_billing_data = array(
			'uid' 			=> $uid,
			'transaction_type' => "inapp_billing_".$channel,
			'billing_type'	=> '1',
			'amount' 		=> $money,
			'server_id' 	=> $server_id,
			'ip'		 	=> $_SERVER['REMOTE_ADDR'],
			'result'		=> '1',
			'note'			=> $product_id,
			'country_code'  => $country_code,
			'order_no'		=> $order_id,
			'character_id'	=> $character.id
		);    	

		// 寫入資料庫
		$this->db
			->set("create_time", "now()", false)
			->set("update_time", "now()", false)
			->insert("user_billing", $user_billing_data);
			
		// 設定紀錄資料
		$user_billing_transfer_data = array(
			'uid' 			=> $uid,
			'transaction_type' => "top_up_account",
			'billing_type'	=> '2',
			'amount' 		=> $money,
			'server_id' 	=> $server_id,
			'ip'		 	=> $_SERVER['REMOTE_ADDR'],
			'result'		=> '1',
			'note'			=> $product_id,
			'country_code'  => $country_code,
			'order_no'		=> $order_id,
			'character_id'	=> $character.id
		);    	

		// 寫入資料庫
		$this->db
			->set("create_time", "now()", false)
			->set("update_time", "now()", false)
			->insert("user_billing", $user_billing_transfer_data);

		die(json_encode(array("result" => "1")));
	}
	
	// 檢查伺服器是否存活
	function check_server_alive($server_id)
	{
		$this->load->library("game");
		$res = $this->game->check_server_alive($server_id);
		die("Res:".($res == true ? 'true' : 'false'));
	}
    
    function _check_duplicate_login()
    {
		$site = $this->_get_site();
        
        if ($this->g_user->uid && !$_SESSION['old_deviceid']) {
            
            $log_game_logins = $this->db->from("log_game_logins")
                ->where("uid", $this->g_user->uid)
                ->where("game_id", $site)
                ->where('logout_time', '0000-00-00 00:00:00')->get()->row();
			
			if(empty($log_game_logins))
				return false;
                
            $check_deviceid = ($_SESSION['old_deviceid'])?$_SESSION['old_deviceid']:$_SESSION['login_deviceid'];
                
            if (isset($log_game_logins->device_id) && $check_deviceid <> $log_game_logins->device_id) {

                //$log_user = $this->mongo_log->where(array("uid" => (string)$this->g_user->uid, "game_id" => $site))->select(array('latest_update_time'))->get('users');
                $query = new MongoDB\Driver\Query([
                    "uid" => intval($this->g_user->uid),
                    "game_id" => $site
                ]);
            
                $cursor = $this->mongo_log->executeQuery("longe_log.users", $query);

                $result = [];
                
                foreach ($cursor as $document) {
                    $result[] = $document;
                }
                
                if (isset($result[0]->latest_update_time))
				{
                    $idle_time = time() - $result[0]->latest_update_time;
                    
                    if ($idle_time < 6*60*60)
					{
						return true;
					}
                }
            }
        }
        
        return false;
    }
    
    function _set_logout_time()
    { 
		$site = $this->_get_site();
        
		$query = $this->db->from("log_game_logins")->where("uid", $this->g_user->uid)
          ->where("game_id", $site)
          ->where("logout_time", "0000-00-00 00:00")->get()->row();
        if (isset($query->server_id)) {
            $server = $query->server_id;
            $uc_query = new MongoDB\Driver\Query([
                "game_id" => $site,
                "server_id" => $server
            ]);
        
            $uc_cursor = $this->mongo_log->executeQuery("longe_log.user_count", $uc_query);

            $uc_result = [];
            
            foreach ($uc_cursor as $document) {
                $uc_result[] = $document;
            }
            
            if (isset($uc_result[0]->count) && $uc_result[0]->count > 0) { 
                $new_count = $uc_result[0]->count - 1;
                
                $uc2_filter = ['game_id' => $site, "server_id" => $server];
                $uc2_newObj = ['$set' => ['count' => $new_count]];
                
                $uc2_options = ["multi" => false, "upsert" => true];
                
                $bulk = new MongoDB\Driver\BulkWrite;
                $bulk->update($uc2_filter, $uc2_newObj, $uc2_options);

                $this->mongo_log->executeBulkWrite("longe_log.user_count", $bulk);
                unset($bulk);
            }
        }
        
        $this->db->where("uid", $this->g_user->uid)
          ->where("game_id", $site)
          ->where("logout_time", "0000-00-00 00:00")->update("log_game_logins", array("logout_time" => now()));
              
        //$this->mongo_log->where(array("uid" => (string)$this->g_user->uid, "game_id" => $site))->delete_all('users');
        $filter = ["uid" => intval($this->g_user->uid), "game_id" => $site];
        $options = ["limit" => 0];

        $bulk = new MongoDB\Driver\BulkWrite;
        $bulk->delete($filter, $options);

        $result = $this->mongo_log->executeBulkWrite("longe_log.users", $bulk);
    }
	
	function get_app_info()
	{
		$partner_id = $this->input->get_post("pid");
		$app_id = $this->input->get_post("app");
		$app_key = $this->input->get_post("key");
		
		if(empty($partner_id) || empty($app_id) || empty($app_key))
		{
			die('');
		}
		
		$partner_conf = $this->config->item("partner_api");
		if(!array_key_exists($partner_id, $partner_conf))
		{
			die('');
		}
		if(!array_key_exists($app_id, $partner_conf[$partner_id]["sites"]))
		{
			die('');
		}
		
		if($partner_conf[$partner_id]["sites"][$app_id]["key"] == $app_key)
		{
			die(json_encode($partner_conf[$partner_id]["sites"][$app_id]));
		}
		
		die('');
	}
	
	function set_app_resume()
	{
		$partner_id = $this->input->get_post("partner");
		$app_id = $this->input->get_post("site");
		$uid = $this->input->get_post("uid");
		$token = $this->input->get_post("token");
		
		if(empty($partner_id) || empty($app_id) || empty($uid) || empty($token))
		{
			die(json_encode(array("result"=>"0", "error"=>"參數錯誤!")));
		}
		
		die(json_encode(array("result"=>"0", "error"=>"參數錯誤!")));
		//die(json_encode(array("result"=>"1")));
	}
	
	function set_app_pause()
	{
		$partner_id = $this->input->get_post("partner");
		$app_id = $this->input->get_post("site");
		$uid = $this->input->get_post("uid");
		$token = $this->input->get_post("token");
		
		if(empty($partner_id) || empty($app_id) || empty($uid) || empty($token))
		{
			die(json_encode(array("result"=>"0", "error"=>"參數錯誤!")));
		}
		
		die(json_encode(array("result"=>"1")));
	}
}
