<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ajax extends MY_Controller {

	function get_realtime_bulletin($site, $cg_id, $limit=0, $target='')
	{
		$this->load->model("g_bulletins");
		if ($target) {
			$query = $this->g_bulletins->get_list_target($site, $cg_id, $target, $limit);
		}
		else {
			$query = $this->g_bulletins->get_list($site, $cg_id, $limit);
		}
		$data = array();
		foreach($query->result() as $row) {
			$data[] = array(
				'id' => $row->id,
				'title' => strtr(strip_tags($row->content, "<a><span>"), array("<a "=>"<a target='_blank' ")),
				'date' =>  date("Y/m/d", strtotime($row->create_time)),
			);
		}
		
		/*if ($uid = $this->g_user->uid) {
			$this->db->query("
				INSERT INTO log_online_users (uid, server_id, online_date) 
					SELECT uid, server_id, NOW() FROM characters
					WHERE uid='{$uid}' and server_id='{$target}'	 ON DUPLICATE KEY UPDATE online_date=NOW()				
			");
		}*/		
		die(json_encode($data));
	} 
	
	//補轉點數用
	function resend_transfer($order_id)
	{		
		$this->load->library("g_wallet");
		$order = $this->g_wallet->get_order($order_id);		
		if (empty($order)) die(json_failure("交易不存在"));
		if ($order->billing_type <> '2') die(json_failure("此筆訂單不是轉點交易")); 
		if (in_array($order->result, array('1', '2'))) die(json_failure("交易已結案"));
		
		if ($order->transaction_type == 'omg_billing') { //須檢查omg扣款是否成功
			$omg_order = $this->db->from("omg_billing")->where("user_billing_id", $order->id)->get()->row() 
				or die(json_failure("此筆OMG訂單不存在")); 
			if ($omg_order->status <> '1') die(json_failure("此筆OMG訂單扣款失敗"));
		}
		/*else if ($order->transaction_type <> 'top_up_account') {
			die(json_failure("不允許操作的交易類型"));
		}*/		

		//獲取資訊
		$this->load->model("games");
		$server = $this->games->get_server_by_server_id($order->pay_server_id) or die(json_failure("遊戲資訊不正確"));
		$game = $this->games->get_game($server->game_id) or die(json_failure("遊戲資訊不正確"));
			
		//執行轉點
		$this->load->library("game_api/{$server->game_id}");
		$re = $this->{$server->game_id}->transfer($server, $order, $game->exchange_rate);			
		
		if ($re === "1") {
			$this->g_wallet->complete_order($order);
			die(json_success());
		}
		else if ($re === "-1") {			
			$this->g_wallet->cancel_timeout_order($order);		
			die(json_failure("遊戲伺服器無回應"));			
		}
		else {
			$error_message = $this->{$server->game_id}->error_message;
			$this->g_wallet->cancel_order($order, $error_message);
			die(json_failure($error_message));	
		}							
	}	
	
	function resend_failed_order($order_id)
	{		
		$this->load->library("g_wallet");
		$order = $this->g_wallet->get_order($order_id);		
		if (empty($order)) die(json_failure("交易不存在"));
		if ($order->billing_type <> '2') die(json_failure("此筆訂單不是轉點交易")); 
		if ($order->result <> '2') die(json_failure("此筆訂單無法操作(not 2)"));		
		if ($order->transaction_type <> 'rc_billing') die(json_failure("此筆訂單無法操作(not rc_billing)"));
		
		//獲取資訊
		$this->load->model("games");
		$server = $this->games->get_server_by_server_id($order->pay_server_id) or die(json_failure("遊戲資訊不正確"));
		$game = $this->games->get_game($server->game_id) or die(json_failure("遊戲資訊不正確"));
		
		$new_order_id = $this->g_wallet->produce_order($order->uid, $order->transaction_type, "2", $order->amount, $order->pay_server_id, $order->order);
		if (empty($new_order_id)) die(json_failure($this->g_wallet->error_message));
		
		$order = $this->g_wallet->get_order($new_order_id);
		
		//執行轉點
		$this->load->library("game_api/{$server->game_id}");
		$re = $this->{$server->game_id}->transfer($server, $order, $game->exchange_rate);			
		
		if ($re === "1") {
			$this->g_wallet->complete_order($order);
			die(json_success());
		}
		else if ($re === "-1") {			
			$this->g_wallet->cancel_timeout_order($order);		
			die(json_failure("遊戲伺服器無回應"));			
		}
		else {
			$error_message = $this->{$server->game_id}->error_message;
			$this->g_wallet->cancel_order($order, $error_message);
			die(json_failure($error_message));	
		}							
	}	

	function redo_gash_billing($id)
	{
		$gash_billing = $this->db->from("gash_billing")->where("id", $id)->get()->row();
		if ($gash_billing) 
		{
			//儲值至用戶 (成功後status=2										
			if (empty($gash_billing->uid)) die(json_failure("用戶資訊遺失"));										
			
			$this->load->config("g_gash");
			$gash_conf = $this->config->item("gash");
			$money = floor($gash_billing->AMOUNT / $gash_conf["converter"][$gash_billing->CUID]);
			
			$this->load->library("g_wallet");
			$order_id = $this->g_wallet->produce_gash_order($gash_billing->uid, $gash_billing->id, $money);
			if (empty($order_id)) die(json_failure($this->g_wallet->error_message));
			
			$this->db->where("id", $id)->update("gash_billing", array("status" => "2"));					
			
			//一并轉入遊戲伺服器					
			if ($gash_billing->server_id) {
				$this->load->library("game");
				$this->game->payment_transfer($gash_billing->uid, $gash_billing->server_id, $money);
			}
		}
		else die(json_failure("交易不存在"));
	}
	
    function resend_gash_billing_cron()
    {
		$query = $this->db->from("gash_billing")->where("RCODE", '0000')->where("PAY_STATUS", 'S')->where("status", '1')->get();
        
        if ($query->num_rows() > 0) {
            foreach($query->result() as $row) {
                resend_gash_billing($row->id);
            }
        }
    }
    
	function resend_gash_billing($id)
	{
		require_once dirname(__FILE__).'/../libraries/gash/Common.php';
				
		$gash_billing = $this->db->from("gash_billing")->where("id", $id)->get()->row();
		if ($gash_billing) 
		{
	    	if ($gash_billing->status == '2') die(json_failure("訂單已完成"));
			
	    	$this->load->config("g_gash");
	    	$conf = $this->config->item("gash");
	    	
	    	$trans = new Trans( null );
			$trans->nodes = array(
				"MSG_TYPE" 	=> "0100", // 交易訊息代碼
				"PCODE" 	=> "200000", // 交易處理代碼_ 查詢訂單請使用 200000
				"CID" 		=> $conf[$gash_billing->country]["CID"], // 商家遊戲代碼
				"COID"		=> $gash_billing->COID, // 商家訂單編號
				"CUID"		=> $gash_billing->CUID, // 幣別 ISO Alpha Code
				"AMOUNT"	=> $gash_billing->AMOUNT, // 交易金額
				"ERQC" 		=> $gash_billing->ERQC,
			);

			try {
				// 設定查單服務位置
				$serviceURL = $conf["url"]["checkorder"];
				
				//log_message("error", "check_order:".$trans->GetSendData());
				
				// 進行查單
				$client = new SoapClient($serviceURL);
				$result =  $client->getResponse( array( "data" => $trans->GetSendData() ) );				
				
				//log_message("error", "check_order_result:".$result->getResponseResult);
				
				// 解析回傳結果
				$trans = new Trans( $result->getResponseResult );
				
				//處理
				$d = my_curl(site_url("gash/return_url?country={$gash_billing->country}"), array("data"=>$trans->GetSendData()));		
				
				die(json_success("查詢回傳".print_r($trans->nodes, true).""));
			}
			catch ( Exception $ex ) {
				die(json_failure("查單發生錯誤！".$ex->getMessage()));	
			}				    				
		}
		else die(json_failure("交易不存在"));
	}	
	
	function confirm_google_billing($id)
	{
		$google_billing = $this->db->from("google_billing")->where("id", $id)->get()->row();
		if ($google_billing) 
		{
			if ($google_billing->is_confirmed == '1') die(json_failure("此交易已confirm"));
			else if ($google_billing->purchase_state <> '0') die(json_failure("此交易失敗，不須請款"));
			
			$this->db
				->set("update_time", "NOW()", FALSE)
				->where("id", $id)
				->update("google_billing", array("is_confirmed" => "1"));
			
			if ($this->db->affected_rows()) 
			{											
				$this->load->library("g_wallet");				
				
				// 建立income交易
				$billing_id = $this->g_wallet->produce_income_order($google_billing->uid, "google_billing", $id, $google_billing->price);
				if (empty($billing_id)) die(json_failure("資料庫發生錯誤-新增google income訂單"));
				
				// 開啟轉點
				$servers = $this->db->from("servers")->where("server_id", $google_billing->server_id)->get()->row();	
				$billing_id = $this->g_wallet->produce_order($google_billing->uid, "top_up_account", "2", $google_billing->price, $servers->server_id);
				if (empty($billing_id)) die(json_failure("資料庫發生錯誤-google訂單轉點")); 
				
				// 轉點成功
				$this->g_wallet->complete_order((object)array("id"=>$billing_id));		
							
				die(json_success("成功"));
			}			
			else die(json_success("單號錯誤"));
		}
		else die(json_failure("交易不存在"));
	}
		
	function _output_json($result, $error='')
	{
		die(json_encode(array("result"=>$result, "error"=>$error)));
	}
}
