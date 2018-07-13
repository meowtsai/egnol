<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//
// 網站會員系統功能
//
class Member extends MY_Controller
{
	// AJAX 回應 function 檢查是否已登入
	function _check_login_json()
	{
		if (!$this->g_user->is_login())
		{
			die(json_failure("尚未登入，請重新進行登入"));
		}
	}

	// 會員資訊頁面
	// 尚未登入則導向登入頁面, 已登入則顯示會員資料和修改選項
	function index()
	{
		if(!$this->g_user->is_login())
		{
			$this->login();
		}
		else
		{
			$user_info = $this->db->from("user_info")->where("uid", $_SESSION['user_id'])->get()->row();

			$this->_init_layout()
					->set('email', $_SESSION['email'])
					->set('mobile', $_SESSION['mobile'])
					->set('user_info', $user_info)
					->add_css_link("login")
					->standard_view("member/profile");
		}
	}

	// 網頁登入介面
	//	GET 輸入參數:
	//		account - String    	預設登入帳號
	//      redirect_url- String    登入完成後要返回的網址
	function login()
	{
		// 取出 GET 參數
		$account = urldecode($this->input->get("account", true));

		// 載入第三方登入通道種類
		$this->load->config("g_api");
		$channel_api = $this->config->item("channel_api");
		$channel_item = array();
		foreach($channel_api as $key => $channel)
		{
			$channel['channel'] = $key;
			array_push($channel_item, $channel);
		}

		$this->_init_layout()
			->add_css_link("login")
			->add_js_include("member/login")
			->set("account", $account)
			->set("channel_item", $channel_item)
			->standard_view("member/login");
	}

	function login_json()
	{
		header('content-type:text/html; charset=utf-8');
		header('Access-Control-Allow-Origin: *');

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

	// 第三方登入
	function channel_login()
	{
		$site = $this->_get_site();
		$channel = $this->input->get("channel", true);

		$_SESSION['site'] = $site;

		$redirect_url = $this->input->get('redirect_url', true);
		if(empty($redirect_url))
			$_SESSION['redirect_url'] = g_conf('url', "longe")."member";
		else
			$_SESSION['redirect_url'] = $redirect_url;

		$param = $login_param = array();

		$this->load->config("g_api");
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

		/*if ($channel == "facebook")
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
		}*/

		$this->load->library("channel_api/{$lib}", $param);
		$result = $this->{$lib}->login($site, $login_param);
		if ($result == false)
		{
			die($this->{$lib}->error_message);
		}
	}

	function login_callback($channel)
	{
		header('P3P:CP="IDC DSP COR ADM DEVi TAIi PSA PSD IVAi IVDi CONi HIS OUR IND CNT"');

		$_SESSION['channel'] = $channel;

		if(!empty($_SESSION['redirect_url']))
		{
			$redirect_url = $_SESSION['redirect_url'];

            $_SESSION['redirect_url'] = '';
			unset($_SESSION['redirect_url']);
		}
		else
			$redirect_url = '';

		$site = empty($_SESSION['site']) ? 'long_e' : $_SESSION['site'];
		$param = array();

		$this->load->config("g_api");
		$channel_api = $this->config->item("channel_api");
		if (array_key_exists($channel, $channel_api) == false) {
			die("未串接此通道({$channel})");
		}

		if (isset($channel_api[$channel]['lib_name'])) { //lib重命名
			$lib = $channel_api[$channel]['lib_name'];
		}
		else {
			$lib = $channel;
		}

		if ($channel == "facebook") {
	    	$fb_app_conf = $this->config->item("fb_app");
	    	if ( !empty($ad) && array_key_exists($ad, $fb_app_conf))
	    	{
	    		$param = array(
					'appId'  => $fb_app_conf[$ad]['appId'],
					'secret' => $fb_app_conf[$ad]['secret'],
	    		);
	    	}
		}

		$this->load->library("channel_api/{$lib}", $param);
		$result = $this->{$lib}->{$this->router->fetch_method()}($site);

		header('Content-type:text/html; Charset=UTF-8');

		if ($result == false)
		{
			echo "<script type='text/javascript'>alert('登入失敗1!');</script>";
		}
		else
		{
			if(!empty($result['external_id']))
			{
				$external_id = $result['external_id']."@".$channel;
				$boolResult = $this->g_user->verify_account('', '', '', $external_id);
				if ($boolResult != true)
				{
					$boolResult = $this->g_user->create_account('', '', '', $external_id);

					if($boolResult == true )
					{
						$this->g_user->verify_account('', '', '', $external_id);

                        $log_data = array(
                            "uid" => $this->g_user->uid,
                            "content" => "NEW: [external_id]".$external_id
                        );

                        $this->db->insert("log_user_updates", $log_data);
					}
					else
					{
						echo "<script type='text/javascript'>alert('登入失敗2!');</script>";
					}
				}
			}
			else
			{
				$msg = '登入失敗3!';
				if(!empty($result['error']))
					$msg = $result['error'];
				echo "<script type='text/javascript'>alert('{$msg}');</script>";
			}
		}

		if(strpos($redirect_url, '?') == FALSE)
		{
			$redirect_url = $redirect_url."?site=".$site;
		}
		else
		{
			if(strpos($redirect_url, "site") == FALSE)
				$redirect_url = $redirect_url."&site=".$site;
		}

		echo "<script>location.href='{$redirect_url}';</script>";
	}

	// 登出
	function logout()
	{
		$site = $this->_get_site();

		$this->g_user->logout();

		header('Content-type:text/html; Charset=UTF-8');
		echo "<script type='text/javascript'>alert('成功登出系統'); location.href='/member?site={$site}';</script>";
	}

	// 註冊新帳號
	function register()
	{
		$this->_init_layout()
			->add_css_link("login")
			->add_js_include("member/register")
			->standard_view();
	}

	function register_json()
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

            $log_data = array(
                "uid" => $this->g_user->uid,
                "content" => "NEW: [email]".$email." [mobile]".$mobile
            );

            $this->db->insert("log_user_updates", $log_data);

			die(json_message(array("message"=>"成功", "site"=>$site), true));
		}
		else
		{
			die(json_failure($this->g_user->error_message));
		}
	}

	// 綁定帳號
	function bind_account()
	{
		// 先登入才能綁定
		$this->_require_login();

		$user_data = $this->g_user->get_user_data();

		if (!empty($user_data->email) || (!empty($user_data->mobile)))
		{
			die('你的帳號不需要綁定');
		}

		$this->_init_layout()
			->add_css_link("login")
			->add_js_include("member/bind_account")
			->set("user_data", $user_data)
			->standard_view();
	}

	function bind_account_json()
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

            $log_data = array(
                "uid" => $this->g_user->uid,
                "content" => "UPDATE: [email]".$email." [mobile]".$mobile
            );

            $this->db->insert("log_user_updates", $log_data);

			die(json_message(array("message"=>"成功", "site"=>$site)));
		}
		else
		{
			die(json_failure($this->g_user->error_message));
		}
	}

	// 修改會員資料
	function update_profile()
	{
		$this->_require_login();

		$row = $this->db->from("users")->where("uid", $this->g_user->uid)->get()->row();
		$user_info = $this->db->from("user_info")->where("uid", $this->g_user->uid)->get()->row();

		$this->_init_layout()
			->set("data", $row)
			->set("user_info", $user_info)
			->add_css_link("login")
			->add_css_link("money")
			->add_js_include("member/update_profile")
			->standard_view();
	}

	function update_profile_json()
	{
		$site = $this->_get_site();

		$data = array(
		);
		$user_info = array(
			'name' => $this->input->post("name"),
			'sex' => $this->input->post("sex"),
			'street' => $this->input->post("address_road"),
		);

        $log_content = "UPDATE: [name]".$user_info['name']." [sex]".$user_info['sex']." [street]".$user_info['street'];
		if ($this->input->post("email"))
		{
			$email = $this->input->post("email");
			if($email != $this->g_user->email && $this->db->from("users")->where("email", $email)->count_all_results() > 0)
			{
				die(json_failure("E-MAIL 已被使用"));
			}
			$data["email"] = $email;
            $log_content .= " [email]".$email;
		}
		if ($this->input->post("mobile"))
		{
			$mobile = $this->input->post("mobile");
			if($mobile != $this->g_user->mobile && $this->db->from("users")->where("mobile", $mobile)->count_all_results() > 0)
			{
				die(json_failure("手機號碼已被使用"));
			}
			$data["mobile"] = $mobile;
            $log_content .= " [mobile]".$mobile;
		}
		if ($this->input->post("ident")) {
            $data["ident"] = $this->input->post("ident");
            $log_content .= " [ident]".$ident;
        }
		if ($this->input->post("birthday_y"))
		{
			$user_info['birthday'] = "{$this->input->post("birthday_y")}-{$this->input->post("birthday_m")}-{$this->input->post("birthday_d")}";

            $log_content .= " [birthday]".$birthday;
		}

		function clear(&$value)
		{
			$value = trim(strip_tags($value));
		}
		array_walk($data, 'clear');

		$target_uid = $this->g_user->uid;

		$this->db->where("uid", $target_uid)->update("users", $data);
		$this->db->where("uid", $target_uid)->update("user_info", $user_info);

		if ($this->input->post("email"))
		{
			$_SESSION["email"] = $email;
			$this->g_user->email = $email;
		}
		if ($this->input->post("mobile"))
		{
			$_SESSION["mobile"] = $this->input->post("mobile");
			$this->g_user->mobile = $this->input->post("mobile");
		}

        $log_data = array(
            "uid" => $this->g_user->uid,
            "content" => $log_content
        );

        $this->db->insert("log_user_updates", $log_data);

		die(json_message(array("message"=>"成功", "site"=>$site), true));
	}

	// 忘記密碼處理
	function forgot_password()
	{
		$this->_init_layout()
			->add_css_link("login")
			->add_js_include("member/forgot_password")
			->standard_view();
	}

	function reset_password_json()
	{
		$email = $this->input->post("email");
		$site = $this->_get_site();

		header('content-type:text/html; charset=utf-8');
		if ( empty($email) ) {
			die(json_failure("尚有資料未填"));
		}

	    $new = rand(100000, 999999);
	    $md5_new = md5($new);

		if(filter_var($email, FILTER_VALIDATE_EMAIL))
		{
			// 使用 email
			$user = $this->db->from("users")->where("email", $email)->get()->row();
			if (!$user)
			{
				die(json_failure("沒有這位使用者或資料填寫錯誤。"));
			}
		    $this->db->where("email", $email)->update("users", array("password" => $md5_new));

			$this->load->library("g_send_mail");

			if($this->g_send_mail->passwdResetMail($email, $new))
			{

                $log_data = array(
                    "uid" => $user->uid,
                    "content" => "RESET PASSWORD: [email]".$email
                );

                $this->db->insert("log_user_updates", $log_data);

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
			$user = $this->db->from("users")->where("mobile", $mobile)->get()->row();
			if (!$user)
			{
				die(json_failure("沒有這位使用者或資料填寫錯誤。"));
			}
		    $this->db->where("mobile", $mobile)->update("users", array("password" => $md5_new));

			// 手機號碼的話要發送簡訊
            $msg = "親愛的龍邑會員您好：您的新密碼為 {$new}，請妥善保管。龍邑遊戲敬上";

			$this->load->library("g_send_sms");

			if($this->g_send_sms->send($site, $mobile, $msg))
			{
                $log_data = array(
                    "uid" => $user->uid,
                    "content" => "RESET PASSWORD: [mobile]".$mobile
                );

                $this->db->insert("log_user_updates", $log_data);

				die(json_message(array("message"=>"已使用簡訊發送新密碼至您的手機。", "site"=>$site)));
			}
			else
			{
				die(json_failure($this->g_send_sms->get_message()));
			}
		}
	}

	// 修改密碼
	function change_password()
	{
		$this->_require_login();

		$this->_init_layout()
			->add_css_link("login")
			->add_js_include("member/change_password")
			->standard_view();
	}

	function change_password_json()
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

        $log_data = array(
            "uid" => $this->g_user->uid,
            "content" => "CHANGE PASSWORD"
        );

        $this->db->insert("log_user_updates", $log_data);

		die(json_message(array("message"=>"修改成功", "site"=>$site)));
	}


	function login_fetchdata_json()
	{
		header('content-type:text/html; charset=utf-8');
		header('Access-Control-Allow-Origin: *');

		$site = $this->_get_site();

		$_SESSION['site'] = $site;

		// 檢查 e-mail or mobile
		$account = $this->input->post("account");
		//$account = $this->input->get("account");
		if(empty($account))
		{
			die(json_failure('電子郵件或行動電話未填寫'));
		}

		$pwd = $this->input->post("pwd");
		//$pwd = $this->input->get("pwd");
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
			$uid = $_SESSION['user_id'];
			//$uid = 49134;
			$billing_str = "SELECT SUM(amount) AS sum
						FROM user_billing
						WHERE uid='{$uid}'
							AND transaction_type='top_up_account'
							AND result = 1
							AND server_id LIKE 'vxz%'";

			$billing_sum = $this->db->query($billing_str)->row();

			$billing_list_str = " SELECT u.id,u.uid, order_no,amount,update_time FROM user_billing u WHERE u.uid='{$uid}' AND u.transaction_type='top_up_account' AND u.result = 1 AND u.server_id LIKE 'vxz%'";
			$billing_list = $this->db->query($billing_list_str);

			foreach($billing_list->result() as $row) {
				$data[] = array(
					'order_id' => $row->id,
					'order_no' => $row->order_no,
					'amount' =>  $row->amount,
					'date' =>  date("Y/m/d", strtotime($row->update_time)),
				);
			}

			$l8na_server_str = "SELECT server_id,name FROM servers WHERE game_id ='L8na' AND server_status='public'";
			$server_list = $this->db->query($l8na_server_str);
			foreach($server_list->result() as $row) {
				$server_data[] = array(
					'server_id' => $row->server_id,
					'server_name' => $row->name,
				);
			}


			$user_data =array(
					"uid" => $this->g_user->uid,
					"vxz_amount" => $billing_sum->sum,
					"vxz_billing_data" => $data,
					"L8na_server_data" => $server_data
			);



			//$user_data = $this->g_user->get_user_data($_SESSION['user_id']);

			die(json_message(array("message"=>"成功", "site"=>$site, "user_data" => $user_data), true));
		}
		else
		{
			die(json_failure($this->g_user->error_message));
		}
	}

	// 服務條款
	function service_agreement()
	{
		$this->_init_layout()
			->add_css_link("login")
			->standard_view();
	}

	// 隱私權政策
	function privacy_agreement()
	{
		$this->_init_layout()
			->add_css_link("login")
			->standard_view();
	}

	// 個資同意書
	function member_agreement()
	{
		$this->_init_layout()
			->add_css_link("login")
			->standard_view();
	}

	// 所有的同意書
	function complete_agreement()
	{
		$this->_init_layout()
			->add_css_link("login")
			->quick_view();
	}

}
