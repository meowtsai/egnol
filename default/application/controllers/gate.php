<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Gate extends MY_Controller
{
	function fb_app($site='long_e')
	{
		$ad = $this->input->get("ad");

		$fb_app_conf = $this->config->item("fb_app");		
    	if (array_key_exists($ad, $fb_app_conf)) 
    	{
    		$param = array(
				'appId'  => $fb_app_conf[$ad]['appId'],
				'secret' => $fb_app_conf[$ad]['secret'],    				
    		);
    	}

		$this->load->library("channel_api/fb_api", $param);
		if ($this->g_user->is_login()) {
			$_GET["url"] = "http://{$site}.longeplay.com.tw/common/choose_server_form?ad={$ad}";
			$this->play_game($site);
		} 
		else {
			$_GET["channel"] = "facebook";
			$this->login($site);
		}
	}
	
	function login($site='long_e')
	{							
		header('P3P: CP=CAO PSA OUR');		
		header('Content-type:text/html; Charset=UTF-8');
		
		$channel = $this->input->get("channel", true);
		$ad = $this->input->get("ad");
		$sid = $this->input->get("sid");
		$redirect_url = urldecode($this->input->get("redirect_url", true));
		$redirect_url = strtr($redirect_url, array("%3B"=>"", ";"=>""));
		if (strpos($redirect_url, "member/login") !== false) $redirect_url = site_url("/");
		
		$_SESSION['site'] = $site;
		$_SESSION['ad'] = $ad;
		$_SESSION['channel'] = 'long_e';
						
		if ($channel) 
		{			
			$param = $login_param = array();			
			
			$_SESSION['sid'] = $sid;
			$_SESSION['redirect_url'] = $redirect_url;

			$this->load->config("api");
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
		    		$login_param = array('scope' => '',);
		    	}
			} 
					
			$this->load->library("channel_api/{$lib}", $param);
			$result = $this->{$lib}->login($site, $login_param);
			if ($result == false) {
				die($this->{$lib}->error_message);
			}
		}
		else
		{
			// 檢查 e-mail or mobile
			$account = $this->input->post("account");
			if(empty($account))
			{
				responseMsg('電子郵件或行動電話未填寫');
			}

			$pwd = $this->input->post("pwd");
			if (empty($pwd))
			{
				responseMsg('密碼尚未填寫');
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
				if ( ! empty($redirect_url))
				{
					header('location:'.$redirect_url);
				} 
				else
				{
					if ($site == 'long_e')
					{
						header('location:'.site_url("/"));
					}	
					else
					{
						$choose_server_url = "http://{$site}.longeplay.com.tw/index.php?serverin=1&ad={$ad}";
						$url = base_url()."/play_game/{$site}?url=".urlencode($choose_server_url)."&ad={$ad}";
						//die($url);
						header('location:'.$url);
					}
				}				
			}
			else
			{
				responseMsg($this->g_user->error_message);				
			}
			exit();
		}
	}

	function login_callback($channel)
	{
		header('P3P:CP="IDC DSP COR ADM DEVi TAIi PSA PSD IVAi IVDi CONi HIS OUR IND CNT"');
				
		if ($this->input->get("site"))
		{
			$_SESSION['site'] = $this->input->get("site");
		}
		if ($this->input->get("ad"))
		{
			$_SESSION['ad'] = $this->input->get("ad");
		}
		$_SESSION['channel'] = $channel;
		
		$site = empty($_SESSION['site']) ? 'long_e' : $_SESSION['site'];	
		$ad = empty($_SESSION['ad']) ? '' : $_SESSION['ad'];
		$sid = empty($_SESSION['sid']) ? '' : $_SESSION['sid'];
		$redirect_url = empty($_SESSION['redirect_url']) ? '' : $_SESSION['redirect_url'];
		$param = array();
				
		$this->load->config("api");
		$channel_api = $this->config->item("channel_api");
		if (array_key_exists($channel, $channel_api) == false)
		{
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
		
		if ($result == false) {
			if ( ! in_array($this->{$lib}->error_message, array("尚未登入"))) {
				log_message("error", '('.$_SERVER["REMOTE_ADDR"].')'." login {$lib} error: ". $this->{$lib}->error_message);	
			}			
			$this->_redirect_login_page($site, $this->{$lib}->error_message);
		}
		else {			
			$euid = $result['euid'];   // user's extend UID from yahoo, yam, google, facebook...
			if (empty($euid)) {
				$this->_redirect_login_page($site, "資料傳遞發生異常，請稍後再試，若持續發生此狀況，請透過客服中心與我們聯繫。");
			}
			
			$suffix = $channel;
			$account = $euid.'@'.$suffix;
			$password = rand(100000, 999999);
			$name = isset($result['name']) ? $result['name'] : '';
			$email = isset($result['email']) ? $result['email'] : '';

			if (isset($result['site'])) {
				$site = $result['site']; //有些廠商會直接呼叫login_callback，此時接到的site值不正確，須回傳並修正
				$_SESSION['site'] = $site;
			}
			if (isset($result['sid'])) $sid = $result['sid'];
			if (isset($result['ad'])) {
				$ad = $_SESSION['ad'] = $result['ad'];
			}
			
			//登入，若不存在，則建立
			$this->g_user->login($account, $password, $email, $name, $site)
				or $this->_redirect_login_page($site, $this->g_user->error_message); //登入或建立帳號失敗			
			
			//導頁面
			if ( ! empty($redirect_url)) {
				header("location: {$redirect_url}"); //若有設定，則直接轉
				exit();
			}
			
			//直接進遊戲
			if ( ! empty($sid)) {
				header("location: ".base_url()."/play_game?sid={$sid}&ad={$ad}");
				exit();
			}
			
			/*
			if ($channel == "facebook" && strpos($ad, "_app") !== false) {
				header("location: https://apps.facebook.com/{$ad}");
				exit();
			} */
			
			$url = base_url().'/index.php';
			switch ($site)
			{				
					
				case 'qjp':
					$url = "http://qjp.longeplay.com.tw/go_game.php?srv=L8";
					break;
				
				case 'eya':
					$url = base_url();
					break;
					
				default:					
					$choose_server_url = "http://{$site}.longeplay.com.tw/index.php?serverin=1&ad={$ad}";
					$url = base_url()."/play_game/{$site}?url=".urlencode($choose_server_url)."&ad={$ad}";
			}
			header("location: {$url}", 302);	
			exit();	
		}
	}
	
	function logout()
	{
		$this->load->library('g_user');
		$this->g_user->logout();
	
		header('Content-type:text/html; Charset=UTF-8');
		echo "<script type='text/javascript'>alert('成功登出系統'); history.back();</script>";
	}	

	//待刪，統一使用play_game
	function decide_game_entry($game_id)
	{
		$this->_require_login();
		
		if (in_array($game_id, array("qjp", "sg", "tc", "mh", "xj"))) { //舊遊戲沒有套
			$this->_redirect_web($game_id);
		}
		
		$cnt = $this->db->from("log_game_logins")
			->where("account", $this->g_user->account)
			->where("server_id in (SELECT server_id FROM `servers` WHERE game_id='{$game_id}')", null, false)
			->where("is_recent", "1")
			->where("DATEDIFF(NOW(), create_time)<=90", null, false)
			->count_all_results();
			
		if ($cnt > 0) { //old
			$url = $this->input->get("url");
			if (empty($url)) {
				$this->_redirect_web($game_id);
			}
			else {
				header("location: {$url}");
				exit();
			}
		}
		else { //新會員導入口服
			
			$this->load->config("game");
			$enable_frame = $this->config->item('enable_frame');	

			if (array_key_exists($game_id, $enable_frame)) { //使用遊戲頁框
				$ad = $this->input->get("ad"); //行銷參數
				$server_info = $this->_select_server_entry($game_id);
				header('location: '.base_url().'/play_game?sid='.$server_info->id.'&ad='.$ad);
				exit();
			}
			$this->login_game($game_id);
		}
	}	
	
	function play_game($game_id='')
	{						
		$ad = $this->input->get("ad"); //廣告參數
		
		$this->_require_login($game_id);
		
		if ($game_id) //系統選擇入口
		{
			if ($game_id == 'long_e') $this->_redirect_web();
									
			$cnt = $this->db->from("log_game_logins")
				->where("account", $this->g_user->account)
				->where("server_id in (SELECT server_id FROM `servers` WHERE game_id='{$game_id}')", null, false)
				//->where("is_recent", "1")
				->where("DATEDIFF(NOW(), create_time)<=90", null, false)
				->count_all_results();
			
			//未曾登入，導入口服；登入過，則導選服頁
			if ($cnt > 1) { //old
				$url = $this->input->get("url");
				if (empty($url)) {
					header("location: http://{$game_id}.longeplay.com.tw?serverin=1&ad={$ad}");
					//$this->_redirect_web($game_id);
				}
				else {
					header("location: {$url}");
				}
			}
			else {
				$server = $this->_select_server_entry($game_id);
				if (empty($server)) {
					header("location: http://{$game_id}.longeplay.com.tw");
				}
				else {
					header("location: ".base_url()."/play_game?sid=".$server->server_id."&ad={$ad}&");
				}
			}
			exit();
		}
		
		$sid = $this->input->get("gid") or $sid = $this->input->get("sid"); //找機會將gid刪掉
		if (empty($sid)) die('參數不正確');
					
		$this->load->model("games");
		$server = $this->games->get_server($sid);	
		if (empty($server)) die('遊戲不存在');
		
		$this->_require_login($server->game_id);

		$this->load->model(array("g_bulletins", "g_pictures"));
		$this->load->config("game");
		$enable_frame = $this->config->item('enable_frame');		

		if (in_array($server->game_id, array('sg', 'qjp'))) $this->_redirect_web($server->game_id); //沒套新程式
		
		if ( ! array_key_exists($server->game_id, $enable_frame) ) {
			$this->login_game();
			exit();
		}
				
		$frame_conf = $enable_frame[$server->game_id];

		if (strpos($this->g_user->account, "@beanfun") !== false) {
			$frame_conf['size'] = '0';
		}
		
		$this->load->view("gate/play_game", array(
			"server" => $server,
			"game_url" => base_url()."/gate/login_game?sid={$server->server_id}&ad={$ad}",
			"is_minik_user" => (strpos($this->g_user->account, "@minik") !== false ? true : false),
			"frame_conf" => $frame_conf,		
			"bulletins" => $this->g_bulletins->get_list($server->game_id, 0, 5),		
		));
	}	

	function login_game($game_id='')
	{
		header('P3P:CP="IDC DSP COR ADM DEVi TAIi PSA PSD IVAi IVDi CONi HIS OUR IND CNT"');
		
		$ad = $this->input->get("ad"); //廣告參數

		if ($game_id) {
			$server = $this->_select_server_entry($game_id);
		}
		else {
			$server = $this->input->get("server");
			$sid = $this->input->get("gid") or $sid = $this->input->get("sid"); //找機會將gid刪掉
			if (empty($server) && empty($sid)) die("參數錯誤");
						
			$this->load->model("games");
			if ($sid) $server = $this->games->get_server($sid);
			else $server = $this->games->get_server_by_address($server);
		}
		if (empty($server)) $this->_redirect_web($game_id, "伺服器不存在或未開放");			 
		
		$this->_require_login($server->game_id);
		
		/**
		if ($ad == 'winwin' && isset($_SESSION["winwin_guid"])) {
			
			$guid = $_SESSION["winwin_guid"];
			$cnt = $this->db->where("uid", $this->g_user->uid)->where("game", $server->game_id)->from("winwin_guids")->count_all_results();
			if ($cnt == 0) {
				$this->db->insert("winwin_guids", array("uid"=>$this->g_user->uid, "game"=>$server->game_id, "guid"=>$guid));
			}
		}
		*/			
		
		/** 2014.5.23 */
		if ($this->g_user->check_extra_account($this->g_user->account)) {
			$this->load->config('api');	
			$channel_api = $this->config->item("channel_api");	

			$tmp = explode("@", $this->g_user->account);
			$channel = $tmp[1];
			if ($channel == "omg" || $channel == "rc2") { // 目前僅擋omg, rc2			
				if (array_key_exists($channel, $channel_api)) {
					if (array_key_exists("sites", $channel_api[$channel])) {
						if ( ! array_key_exists($server->game_id, $channel_api[$channel]["sites"])) {
							die("<script>
								alert('".$channel_api[$channel]['name']."帳號尚未開啟此遊戲服務，請使用其他帳號登入，謝謝！');
								top.location.href = 'http://".$server->game_id.".longeplay.com.tw/';
							</script>");
						}
					}
				}						
			}				
		}
		/** end */
		
		$user = (object)array("uid"=>$this->g_user->uid, "euid"=>$this->g_user->euid, "account"=>$this->g_user->account);								
		$this->load->library("game");
		$re = $this->game->login($server, $user, $ad);
		if ($re == false) {
			$this->_redirect_web($server->game_id, $this->game->error_message);
		}
	}	
		
	function register_json($site)
	{
		return $this->g_user->register_json($site);
	}	
	
	function check_account_channel($type='')
	{
		$this->g_user->check_account_channel($type);
		
		if ($this->input->get("redirect_url")) {
			header("location: ".$this->input->get("redirect_url"));
			exit();
		}
		else 
		{
			switch ($type) 
			{
				case 'trade': header("location: /payment"); exit(); break;
				case 'service': header("location: ".base_url()."/service"); exit(); break;	
			}
		}
	}
	
	function website($game) {
		$this->_redirect_web($game);
	}
	
	function _select_server_entry($game_id)
	{
		$server_info = $this->db->from("servers")->where("game_id", $game_id)->where("is_entry_server", 1)->get()->row();
		if (empty($server_info)) {
			//沒有設entry_tag的話，則抓最新的
			$server_info = $this->db->from("servers")->where("game_id", $game_id)->where("server_status", "public")->order_by("id", "desc")->get()->row();
		}
		return $server_info;
	}
	
	function _redirect_web($game_id='', $alert_msg='')
	{
		if ($game_id) {
			$redirect_url = "http://{$game_id}.longeplay.com.tw/index.php";
		}
		else {
			$redirect_url = base_url();
		}
		
		header('Content-type:text/html; Charset=UTF-8');
		echo "<script>".( $alert_msg ? "alert('{$alert_msg}');" : "")." top.location.href='{$redirect_url}'</script>";
		exit();
	}
	
	function _redirect_login_page($game_id='', $alert_msg='')
	{		
		header('Content-type:text/html; Charset=UTF-8');
		$redirect_url = site_url("member/login?site={$game_id}");
		echo "<script>".( $alert_msg ? "alert('{$alert_msg}');" : "")." top.location.href='{$redirect_url}'</script>";
		exit();
	}	
	
}



		function responseMsg($msg)
		{
			if (check_mobile()) {
				if (get_mobile_os() == 'ios') {
					echo "<script src='".base_url()."/p/js/iosBridge.js'></script>
						<script type='text/javascript'>calliOSFunction('showMsg', ['". mb_convert_encoding($msg, "UTF-8")."']); history.back(); </script>";
				}
				else {
					echo "<script type='text/javascript'>window.CoozSDK.showMsg('{$msg}'); history.back(); </script>";
				}	
			}			
			else echo "<script type='text/javascript'>alert('".$msg."'); history.back();</script>";
			exit();	
		}