<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Member extends MY_Controller
{
	function _check_login_json()
	{
		if (empty($this->uid))
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
			$this->_init_layout()->standard_view("member/profile");
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
		$redirect_url = urldecode($this->input->get("redirect_url", true));

		if (empty($redirect_url))
		{
			$redirect_url = site_url("/");
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
			->set("account", $account)
			->set("redirect_url", $redirect_url)
			->set("channel_item", $channel_item)
			->standard_view("member/login");
	}
/*
		$game_name = '';
		if ($site !== 'long_e') 
		{
			$this->load->model('games');
			$game_row = $this->games->get_game($site);
			$game_name = $game_row ? $game_row->name : '';
		}
*/
	function bind_account()
	{
		$this->_require_login();
		
		$user_data = $this->g_user->get_user_data();
		
		if (strstr($this->g_user->account, '@') == FALSE)
		{
			die($this->g_user->account.'你的帳號不需要綁定');
		}
				
		$query = $this->db->from("users")->where("bind_uid", $this->g_user->uid)->get();
		$bind_data = ($query->num_rows() > 0 ? $query->row() : false);
			
		$this->_init_layout()
				->set("user_data", $user_data)
				->set("bind_data", $bind_data)
				->standard_view();
	}
	
	function bind_account_json()
	{
		$this->_check_login_json();
		
		$account = $this->input->post("account");
		$mobile = $this->input->post("mobile");
		$pwd = $this->input->post("pwd");
		$pwd2 = $this->input->post("pwd2");
		$redirect_url = $this->input->post("redirect_url");
	
		if ( empty($account)|| empty($pwd) )
		{
			die(json_failure("請輸入帳號及密碼"));
		}
		else if ($pwd != $pwd2)
		{
			die(json_failure("兩次密碼輸入不同"));
		}
	
		$result = $this->g_user->create_account($account, $pwd, '', '', 'long_e', $this->g_user->uid);
	
		if ($result == true)
		{
			$this->g_user->verify_account($account, $pwd);
			die(json_message(array("message"=>"成功", "back_url"=>$redirect_url)));
		}
		else
		{
			die(json_failure($this->g_user->error_message));
		}	
	}
/*
	function m_bind_account_json()
	{
		$this->_check_login_json();
		
		$account = $this->input->post("account");
		$mobile = $this->input->post("mobile");
		$pwd = $this->input->post("pwd");
		$pwd2 = $this->input->post("pwd2");
		$redirect_url = $this->input->post("redirect_url");
	
		if ( empty($mobile) || empty($pwd) ) {
			die(json_failure("請輸入手機號碼及密碼"));
		}
		else if ($pwd != $pwd2) {
			die(json_failure("兩次密碼輸入不同"));
		}
	
		$result = $this->g_user->set_mobile($account, $pwd, $mobile);
	
		if ($result == true){
			$this->g_user->verify_account($account, $pwd);
			die(json_message(array("message"=>"成功", "back_url"=>$redirect_url)));
		}
		else {
			die(json_failure($this->g_user->error_message));
		}	
	}
*/

	// 修改會員資料
	function update_profile()
	{
		$this->_require_login();

		if ($this->g_user->check_extra_account($this->g_user->account)) 
		{			
			$row = $this->db->from("users")->where("bind_uid", $this->g_user->uid)->get()->row();		
			if ( empty($row) ) { 
				header("location: ".base_url()."/member/bind_account");
				exit();
			}
		}	
		else
		{
			$row = $this->db->from("users")->where("uid", $this->g_user->uid)->get()->row();
		}		
		
		$this->_init_layout()
			->set("data", $row)
			->add_js_include("member/update_profile")
			->standard_view();
	}
	
	function update_profile_json()
	{			
		$data = array(
			'name' => $this->input->post("name"),
			'sex' => $this->input->post("sex"),
			'phone_address' => $this->input->post("phone_address"),
			'mobile' => $this->input->post("mobile"),
			'address_road' => $this->input->post("address_road"),	
		);		
		if ($this->input->post("email")) $data["email"] = $this->input->post("email");
		if ($this->input->post("ident")) $data["ident"] = $this->input->post("ident");
				
		if ($this->input->post("birthday_y"))
		{
			$data['birthday'] = "{$this->input->post("birthday_y")}-{$this->input->post("birthday_m")}-{$this->input->post("birthday_d")}"; 
		}

		function clear(&$value)
		{
			$value = trim(strip_tags($value));
		}
		array_walk($data, 'clear');
			
		if ($this->g_user->check_extra_account())
		{
			$row = $this->db->from("users")->where("bind_uid", $this->g_user->uid)->get()->row();
			if (empty($row)) die(json_failure("尚未綁定帳號"));
			
			$target_uid = $row->uid;
		}
		else
		{
			$target_uid = $this->g_user->uid;
		}
		
		$this->db->where("uid", $target_uid)->update("users", $data);		
		die(json_success());
	}

	// 註冊新帳號
	//	GET 輸入參數:
	//      redirect_url- String    註冊完成後要返回的網址
	function register()
	{
		$redirect_url = urldecode($this->input->get("redirect_url", true));

		if (empty($redirect_url))
		{
			$redirect_url = site_url("/");
		}

		$this->_init_layout()
			->add_js_include("member/register")
			->set("redirect_url", $redirect_url)
			->standard_view();
	}

	function register_json()
	{
        $site = 'long_e';
		$account = $this->input->post("account");
		$pwd = $this->input->post("pwd");
		$pwd2 = $this->input->post("pwd2");
		$email = $this->input->post('email');
		$name = $this->input->post("name");
		$captcha = $this->input->post('captcha');

		header('content-type:text/html; charset=utf-8');
		if ( empty($account) || empty($pwd) ) {
			die(json_failure("請輸入帳號及密碼進行登入"));
		}
		else if (!preg_match("/^[a-z0-9_]+$/", $account))
		//else if (!ereg("^[a-z0-9_]+$", $account))
		{
			die(json_failure("帳號不得包含特殊字元及大寫字母"));
		}
		else if ($pwd != $pwd2) {
			die(json_failure("兩次密碼輸入不相同"));
		}
		else if (empty($_SESSION['captcha']) || $captcha != $_SESSION['captcha']) {
			die(json_failure("驗證碼錯誤"));
		}

		$boolResult = $this->g_user->create_account($account, $pwd, $email, $name, $site);

		if ($boolResult==true)
		{
			$this->g_user->verify_account($account, $pwd);
			die(json_message(array("message"=>"成功", "site"=>$site), true));
		}
		else
		{
			die(json_failure($this->error_message));
		}
	}

	// 忘記密碼處理
	function forgot_password()
	{
		$this->_init_layout()
			->add_js_include("member/forgot_password")
			->standard_view();
	}

	function reset_password_json()
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
	function change_password()
	{
		$this->_require_login();

		$redirect_url = urldecode($this->input->get("redirect_url", true));

		if ($this->g_user->check_extra_account($this->g_user->account)) 
		{			
			$row = $this->db->from("users")->where("bind_uid", $this->g_user->uid)->get()->row();		
			if ( empty($row) )
			{
				header("location: ".base_url()."/member/bind_account");
				exit();
			}
		}	
		
		$bind_data = false;
		if ($this->g_user->check_extra_account())
		{
			$bind_data = $this->db->from("users")->where("bind_uid", $this->g_user->uid)->get()->row();
		}
		
		$this->_init_layout()
			->set("redirect_url", $redirect_url)
			->set("bind_data", $bind_data)
			->standard_view();
	}
	
	function change_password_json()
	{
		$this->_check_login_json();
		
		$pwd = $this->input->post("pwd");
		$pwd2 = $this->input->post("pwd2");
		$redirect_url = $this->input->post("redirect_url");
			
		if ( empty($pwd) ) die(json_failure("請輸入密碼"));
		else if ($pwd != $pwd2) die(json_failure("兩次密碼輸入不同"));
		
		if ($this->g_user->check_extra_account()) {
			$row = $this->db->from("users")->where("bind_uid", $this->g_user->uid)->get()->row();
			if (empty($row)) die(json_failure("尚未綁定帳號"));
			
			$target_uid = $row->uid;
		}
		else {
			$target_uid = $this->g_user->uid;
		}
		
		$this->db->where("uid", $target_uid)->update("users", array("password" => md5(trim($pwd))));
		die(json_message(array("message"=>"修改成功", "back_url"=>site_url("member/index"))));
	}	
}
