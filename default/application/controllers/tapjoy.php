<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tapjoy extends MY_Controller {

	function callback() 
	{		
		$key = 'jvx5s2zG1IrsPFAFk56M';
		log_message('error', print_r($this->input->get(), true));
		
		$id = $this->input->get("id");
		$snuid = $this->input->get("snuid");	
		$currency = $this->input->get("currency");
		$verifier = $this->input->get("verifier");

		if (md5("{$id}:{$snuid}:{$currency}:{$key}") !== $verifier) {
			log_message('error', 'Tapjoy, 驗證失敗, uid: '.$uid);
			header("HTTP/1.1 403 Forbidden", true, 403);
			exit();
		}
		
		if (empty($snuid)) {
			log_message('error', 'Tapjoy, $snuid is empty');
			header("HTTP/1.1 403 Forbidden", true, 403);
			exit();
		}
		
		$spt = explode(".", $snuid);
		$euid = $spt[0];	
		$uid = $this->g_user->decode($euid);
		if (empty($euid) || empty($uid)) {
			log_message('error', 'Tapjoy, $euid or $uid is empty');
			header("HTTP/1.1 403 Forbidden", true, 403);
			exit();
		}
		log_message('error', $uid);
				
		$game = $spt[1];
		log_message('error', "game:".$game);
		
		if (! empty($spt[2])) {
			$server = $spt[2];
			$row = $this->db->from("servers")->where("game_id", $game)->where("address", $server)->get()->row();
			if ($row) $server_id = $row->server_id;
			else $server_id = "{$game}_".sprintf("%02d", $server);
		}
		else $server_id = $game;
		
		log_message('error', 'server_id:'.$server_id);
		
		$server_row = $this->db->from("servers")->where("server_id", $server_id)->get()->row();
		if (empty($server_row)) {			
			log_message('error', 'Tapjoy, 無此伺服器: '.$server_id);
			header("HTTP/1.1 403 Forbidden", true, 403);
			exit();
		}
		
		$this->load->library("g_wallet");
			
		// 建立income交易
		$billing_id = $this->g_wallet->produce_income_order($uid, "tapjoy_billing", "", $currency, $id);
		log_message('error', "tapjoy_billing_id: ".$billing_id);
		if (empty($billing_id)) {
			log_message('error', $this->db->last_query());
			log_message('error', 'Tapjoy, 資料庫發生錯誤-新增tapjoy income訂單:'. $this->g_wallet->error_message);
			header("HTTP/1.1 403 Forbidden", true, 403);
			exit();
		}
			
		// 開啟轉點
		$billing_id = $this->g_wallet->produce_order($uid, "top_up_account", "2", $currency, $server_id);
		if (empty($billing_id)) {
			log_message('error', 'Tapjoy, 資料庫發生錯誤-tapjoy 訂單轉點:'. $this->g_wallet->error_message);
			header("HTTP/1.1 403 Forbidden", true, 403);
			exit();
		} 
			
		// 轉點成功
		$this->g_wallet->complete_order((object)array("id"=>$billing_id));
		
		// 放入遊戲
		/*
		$time = time();		
		$hash = md5($euid.$currency.$time.'cooZ!@#99');
		$url = "http://yiyatw.tenone.cn/yiya/yiya_taiwan/integral/Callback.php?euid={$euid}&gold={$currency}&time={$time}&hash={$hash}";
		
		$re = my_curl($url);
		log_message('error', 'Tapjoy callback, yiya re:'.print_r($re, true));
		if ($re === '1') {
			$this->g_wallet->is_confirmed_order((object)array("id"=>$billing_id));
		}
		*/
		
		die('success');
		/*
		header('HTTP/1.1 404 Bad Request', true, 404);
		exit();
		
		header("HTTP/1.1 403 Forbidden", true, 403);
		exit();
		*/
	}
	
}

