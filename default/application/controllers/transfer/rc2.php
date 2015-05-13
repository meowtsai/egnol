<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rc2 extends MY_Controller {
	
	var $conf;
	
	function __construct()
	{
		parent::__construct();
				
		$this->load->library("g_wallet");
		$this->load->helper("transfer");
		
		$this->load->config("api");
		$api = $this->config->item("channel_api");
		$this->conf = $api['rc2'];
		
		//die($_SERVER["REMOTE_ADDR"]);		
	}

	function entry()
	{
		$redirect_url = urldecode($this->input->get("ref"));
		if (empty($redirect_url)) $redirect_url = 'http://www.long_e.com.tw';
		
		$this->_rc2_login_api($redirect_url);
	}
	
	function website_entry()
	{
		if ($this->input->get("game") && array_key_exists($this->input->get("game"), $this->conf['game_mapping'])) {
			$game = $this->conf['game_mapping'][$this->input->get("game")];			
		}
		else {
			$game = $this->input->get("game");
			log_message('error', 'website_entry $game:'.$game);
		}
		$this->_rc2_login_api("http://www.long_e.com.tw/gate/website/{$game}");
	}
	
	function service_entry()
	{
		$this->_rc2_login_api("http://www.long_e.com.tw/service");
	}
	
	function payment_entry()
	{
		if ($this->input->get("game") && array_key_exists($this->input->get("game"), $this->conf['game_mapping'])) {
			$game = $this->conf['game_mapping'][$this->input->get("game")];			
		}
		else {
			$game = $this->input->get("game");
			log_message('error', 'payment_entry $game:'.$game);
		}
		$this->_rc2_login_api("http://www.long_e.com.tw/payment?game={$game}");
	}
	
	function _rc2_login_api($go_url)
	{	
		if (empty($_SERVER['QUERY_STRING'])) $this->_output("-1", "參數遺失");		
		$queryParts = explode('&', $_SERVER['QUERY_STRING']);		
		
	    $params = array();
	    $flag = false;
	    foreach ($queryParts as $param)
	    {
	    	$item = explode('=', $param);	    	
	    	if ($flag) {
	    		$params["ref"] .= "&{$item[0]}={$item[1]}";
	    		continue;	
	    	}	        
	        $params[$item[0]] = $item[1];
	        if ($item[0] == "ref") $flag = true;
		}
		if ($flag) $go_url = $params['ref'];
		
		$key = $this->conf['login_key'];
    	$rc_uid = $params["uid"];
		$sid = $this->input->get("sid");
		$ltime = $this->input->get("ltime");
		$sign = $this->input->get("sign");
		$game = $this->input->get("game");		
		
       	if ($sign <> md5($rc_uid.$sid.$ltime.$game.$key)) $this->_output("-1", "驗證碼錯誤");
       	
       	$account = $rc_uid . "@rc2";
       	$password = rand(100000, 999999);
       	$game = $this->conf['game_mapping'][$this->input->get("game")];
       	 
       	if ($this->g_user->login($account, $password, "", "", $game)) {
       		header("location: ".$go_url);
       		exit();
       	}
       	else {
       		$this->_output("-1", "登入失敗");
       	}
	}
	
	function check_role_status()
	{
		$key = $this->conf['login_key'];
		
		$rc_uid = $this->input->get("uid");
		$sid = $this->input->get("sid");
		$time = $this->input->get("time");
		$sign = $this->input->get("sign");
		$game = $this->input->get("game");
		
		//die(md5($rc_uid.$sid.$time.$key));		
		//http://www.long_e.com.tw/transfer/rc2/check_role_status?game=mon&sid=0&uid=123456&time=123456789&sign=6b1d519d4f56ec9314430ae0ec0408e5
		
		if ($sign !== MD5($rc_uid.$sid.$time.$key) ) {
			$this->_output("-1", "驗證碼錯誤");
		}
		
		$this->load->model("games");
		$server_id = "{$game}_".sprintf("%02d", $sid);
		
		$server = $this->games->get_server_by_server_id($server_id);		
		if (empty($server)) { 
			$this->_output("-1", "伺服器不存在");		
		}
		else if ( ! $this->g_user->verify_account($rc_uid."@rc2")) {
			$this->_output("-1", "用戶不存在");
		}
		
		$user = (object) array("uid"=>$this->g_user->uid, "account"=>$this->g_user->account, "account"=>$this->g_user->account);
		
		$this->load->library("game_api/{$server->game_id}");
		$re = $this->{$server->game_id}->check_role_status($server, $user);
		
		if ($re == "1") $this->_output("0", "success");
    	else $this->_output("-1", "該伺服器沒有角色");
	}
		
	
	function callback()
	{				
		if ( ! in_array($_SERVER["REMOTE_ADDR"], array("211.72.192.156", "211.72.192.149", "211.72.246.68", "61.220.44.200"))) $this->_output("-1", "禁止的:".$_SERVER["REMOTE_ADDR"]);		
		
		$key = $this->conf['transfer_key'];
		
		$rc_uid = $this->input->get("uid");
		$oid = $this->input->get("oid");
		$amount = $this->input->get("amount");
		$coins = $this->input->get("coins");
		$dtime = $this->input->get("dtime");
		$sign = $this->input->get("sign");
		$ext = $this->input->get("ext");		
		
		//die($sign . " " . MD5($oid.$rc_uid.$amount.$coins.$dtime.$key));
		//http://www.long_e.com.tw/transfer/rc2/callback?uid=123456&oid=987654&amount=10&coins=10&dtime=123456789&ext=&sign=bf1959916207f6eec7303b5ea180a969&game=mon&server=0
				
		if ($sign !== MD5($oid.$rc_uid.$amount.$coins.$dtime.$key) ) {
			log_message('error', 'rc2 callback error: 驗證碼錯誤, id:'.$oid);
			$this->_output("-1", "驗證碼錯誤");
		}
		else if (empty($oid)) {
			log_message('error', 'rc2 callback error: 訂單編號遺失, id:'.$oid);
			$this->_output("-1", "訂單編號遺失");
		}
		else if ( ! $this->g_user->verify_account($rc_uid."@rc2")) {
			log_message('error', 'rc2 callback error: 用戶不存在, id:'.$oid);
			$this->_output("-1", "用戶不存在");
		}		
		
		$row = $this->db->from("user_billing")->where("order", $oid)->order_by("id desc")->get()->row();
		if ($row && $row->result == '1') $this->_output("0", "success");		
		
		$game = $this->input->get("game");
		$server = $this->input->get("server");			

		$this->load->model("games");
		$server_id = "{$game}_".sprintf("%02d", $server);
		
		$server = $this->games->get_server_by_server_id($server_id);
		if (empty($server)) $this->_output("-1", "伺服器不存在");	
		if ( $server->is_transaction_active == 0) {
			log_message('error', 'rc2 callback error: 遊戲伺服器目前暫停轉點服務, id:'.$oid);
			$this->_output("-1", "遊戲伺服器目前暫停轉點服務");
    	}
		
		$game = $this->games->get_game($server->game_id);		
				
		//轉入遊戲		
		$this->load->library("game_api/{$server->game_id}");
		$billing_id = $this->g_wallet->produce_order($this->g_user->uid, "rc_billing", "2", $amount, $server->server_id, $oid);
		if (empty($billing_id)) $this->_output("-1", $this->g_wallet->error_message);	 
		
		$order = $this->g_wallet->get_order($billing_id);
		$re = $this->{$server->game_id}->transfer($server, $order, $game->exchange_rate);
		$error_message = $this->{$server->game_id}->error_message;
		
		if ($re === "1") {
			$this->g_wallet->complete_order($order);
			$this->_output("0", "success");
		}
		else if ($re === "-1") {
			$this->g_wallet->cancel_timeout_order($order);
			$this->_output("0", "success"); //rc2逾時單直接回傳成功給rc，後續我們再處理
			//$this->_output("-1", "遊戲端未有回應(錯誤代碼: 002)");			
		}
		else if ($re === "-2") {			
			$this->g_wallet->cancel_other_order($order, $error_message);
			$this->_output("-1", "轉點未完成(錯誤代碼: 003)");
		}		
		else {
			$this->g_wallet->cancel_order($order, $error_message);
			$this->_output("-1", $error_message);	
		}
	}
	
	function _output($recode, $remsg) {
		die(json_encode(array("retcode"=>$recode, "retmsg"=>$remsg)));	
	}		
}

