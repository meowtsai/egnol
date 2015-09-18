<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class Game
{
    var $CI;
    var $error_message = '';

    function __construct()
    {    
    	$this->CI =& get_instance();
    	$this->CI->load->config("api"); 	    	
    }
    
    function login($game_id, $server, $uid)
    {	    	
    	$pass_ips = array();    	
    	$partner_conf = $this->CI->config->item("partner_api");
    	foreach($partner_conf as $partner => $item)
		{
    		if (isset($item['sites']) && isset($item['ips']) && array_key_exists($server->game_id, (array) $item['sites']))
			{
    			$pass_ips = array_merge($pass_ips, $item['ips']);
    		}
    	}    
    	$pass = in_array($_SERVER["REMOTE_ADDR"], $pass_ips);

		if ($server->server_status=='hide') 
		{
			return $this->_return_error("伺服器不開放");
		}
		elseif ($server->server_status=='maintenance')
		{
			if ( ! (IN_OFFICE || $pass)) {
				return $this->_return_error($server->maintenance_msg);
			}
		}
		elseif ($server->server_status=='private')
		{
			if ( ! (IN_OFFICE || $pass)) {
				return $this->_return_error("伺服器不開放");
			}
		}

		$this->log_game_login($game_id, $uid, $server->server_id);

		return true;
    }
    
    function log_game_login($game_id, $uid, $server_id)
    {
		//log
		
		/* 暫時不刪
		if (rand(1,100)==1) { //偶爾跑就好
			$this->CI->db
				->where("DATEDIFF(NOW(), create_time)>90") //保留90天log
				->where("is_recent", "0")
				->delete("log_game_logins");
		}
		*/
    	    	
		$this->CI->db
			//->where("uid", $user->uid)
			->where("uid", $uid)
			->where("server_id", $server_id)
			->where("is_recent", "1")
			->update("log_game_logins", array("is_recent" => "0"));
		
		$query = $this->db->select("*")
					->from("log_game_logins")
					->where("uid", $uid)
					->where("game_id", $game_id)->get();
					
		if ($query->num_rows() > 0) {
			$is_first = 0;
		} else {
			$is_first = 1;
		}
		
		$this->CI->db->insert("log_game_logins", array(
					'uid' => $uid,
					'ip' => $_SERVER["REMOTE_ADDR"],
					'create_time' => now(),
					'is_recent' => '1',
					'server_id' => $server_id,
					'game_id' => $game_id,
					'is_first' => $is_first,
				));		
    }
    
	function m_login($server, $user, $ad='')
    {	    	
		if ($server->server_status=='hide') 
		{
			return $this->_return_error("伺服器不開放");
		}
		elseif ($server->server_status=='maintenance')
		{
			if ( ! (IN_OFFICE)) {
				return $this->_return_error($server->maintenance_msg);
			}
		}
		elseif ($server->server_status=='private')
		{
			if ( ! (IN_OFFICE)) {
				return $this->_return_error("伺服器不開放");
			}
		}
		
		//log
		
		/* 暫時不刪
		if (rand(1,100)==1) { //偶爾跑就好
			$this->CI->db
				->where("DATEDIFF(NOW(), create_time)>90") //保留90天log
				->where("is_recent", "0")
				->delete("log_game_logins");
		}
		*/
		
		$this->CI->db
			//->where("uid", $user->uid)
			->where("account", $user->account)
			->where("server_id", $server->server_id)
			->where("is_recent", "1")
			->update("log_game_logins", array("is_recent" => "0"));
			
		$site = $this->CI->input->get('site') ? $this->CI->input->get('site') : (empty($_SESSION['site']) ? 'long_e' : $_SESSION['site']);	
		
		$query = $this->db->select("*")
					->from("log_game_logins")
					->where("uid", $uid)
					->where("game_id", $site)->get();	
					
		if ($query->num_rows() > 0) {
			$is_first = 0;
		} else {
			$is_first = 1;
		}
		
		$this->CI->db->insert("log_game_logins", array(
					'uid' => $user->uid,
					'account' => $user->account,
					'ip' => $_SERVER["REMOTE_ADDR"],
					'create_time' => now(),
					'is_recent' => '1',
					'server_id' => $server->server_id,
					'ad' => empty($ad) ? '' : $ad,
					'game_id' => $site,
					'is_first' => $is_first,
				));
    }
    
    //儲值時直接轉入遊戲伺服器
    //$billing {uid, server_id, amount}
    function payment_transfer($uid, $server_id, $amount)
    {
		if (empty($uid) || empty($server_id) || empty($amount)) $this->_go_payment_result(1, 2, $amount);  
	
		$this->CI->load->library("g_wallet");		
		$remain = $this->CI->g_wallet->get_balance($uid);
		$args = "rm={$remain}";

		$this->CI->load->model("games");
		$server = $this->CI->games->get_server($server_id);
		if (empty($server)) $this->_go_payment_result(1, 0, $amount, "遊戲伺服器不存在", $args);					
		$game = $this->CI->games->get_game($server->game_id);		
	
		if ( $server->is_transaction_active == 0) {
			$this->_go_payment_result(1, 0, $amount, "遊戲伺服器目前暫停轉點服務", $args);
    	}

		//建單，並扣款
 		$order_id = $this->CI->g_wallet->produce_order($uid, "top_up_account", "2", $amount, $server->server_id, "");			
		if (empty($order_id)) $this->_go_payment_result(1, 0, $amount, $this->CI->g_wallet->error_message, $args);
			
		$order = $this->CI->g_wallet->get_order($order_id);

		// 若為遊戲內儲值則完成訂單
		if(!empty($_SESSION['payment_api_call']))
		{
			if($_SESSION['payment_api_call'] == 'true')
			{
				$this->CI->g_wallet->complete_order($order);
				$args = "gp=".($amount*$game->exchange_rate)."&sid={$server_id}";
				$this->_go_payment_result(1, 1, $amount, "", $args);
			}
		}

		// 若為官網儲值要先看是否有遊戲入點機制, 若有則轉點, 無則設為尚未轉進遊戲
		$this->CI->load->library("game_api");
		if($this->CI->game_api->has_billing($server->game_id))
		{
			// 呼叫遊戲入點機制
			$this->CI->load->library("game_api/{$server->game_id}");
			$res = $this->CI->{$server->game_id}->transfer($server, $order, $amount, $game->exchange_rate);
			$error_message = $this->CI->{$server->game_id}->error_message;

			if ($res === "1") {
				$this->CI->g_wallet->complete_order($order);
				$args = "gp=".($amount*$game->exchange_rate)."&sid={$server_id}";
				$this->_go_payment_result(1, 1, $amount, "", $args);
			}
			else if ($res === "-1") {
				$this->CI->g_wallet->cancel_timeout_order($order);
				$this->_go_payment_result(1, 0, $amount, "遊戲伺服器沒有回應(錯誤代碼: 002)", $args);
			}
			else if ($res === "-2") {
				$this->CI->g_wallet->cancel_other_order($order, $error_message);
				$this->_go_payment_result(1, 0, $amount, "{$error_message}(錯誤代碼: 003)", $args);
			}
			else {
				$this->CI->g_wallet->cancel_order($order, $error_message);
				$this->_go_payment_result(1, 0, $amount, "{$error_message}", $args);
			}
		}

		// 此時點數雖尚未進入遊戲, 但登入遊戲會自動轉入, 所以要設定為成功
		$this->CI->g_wallet->ready_for_game_order($order);
		$args = "gp=".($amount*$game->exchange_rate)."&sid={$server_id}";
		$this->_go_payment_result(1, 1, $amount, "", $args);

/*
		//轉入遊戲
		$this->CI->load->library("game_api");
		$re = $this->CI->game_api->transfer($server, $order, $game->exchange_rate);
		$error_message = $this->CI->game_api->error_message;

		$this->CI->db->reconnect(); //mysql wait_timeout為10秒，接口可能執行超過10秒
		
		if ($re === "1") {
			$this->CI->g_wallet->complete_order($order);
			$args = "gp=".($amount*$game->exchange_rate)."&sid={$server_id}";
			$this->_go_payment_result(1, 1, $amount, "", $args);
		}
		else if ($re === "-1") {
			$this->CI->g_wallet->cancel_timeout_order($order);			
			$this->_go_payment_result(1, 0, $amount, "遊戲伺服器沒有回應(錯誤代碼: 002)", $args);		
		}
		else if ($re === "-2") {			
			$this->CI->g_wallet->cancel_other_order($order, $error_message);
			$this->_go_payment_result(1, 0, $amount, "{$error_message}(錯誤代碼: 003)", $args);
		}
		else {
			$this->CI->g_wallet->cancel_order($order, $error_message);		
			$this->_go_payment_result(1, 0, $amount, "{$error_message}", $args);		
		}
*/
    }
    
    function _go_payment_result($status, $transfer_status, $price, $message='', $args='')
    {
		header('location: '.site_url("payment/result?s={$status}&ts={$transfer_status}&p={$price}&m=".urlencode($message)."&".$args));
		exit();
    } 
    
    function _return_error($msg) 
    {
    	$this->error_message = $msg;
    	return false;
    }
}
