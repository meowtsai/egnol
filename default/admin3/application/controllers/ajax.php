<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ajax extends MY_Controller {
	
	function __construct() 
	{
		parent::__construct();				
	}
		
	function ad_channel_autocomplete()
	{
		$q = $this->input->get("q");

		$query = $this->DB2->distinct()->select('ad')->from('characters')
			->where("create_time>=date_sub(now(), interval 30 day) and ad is not null and ad <> ''", null, false)
			->like('ad', $q, 'after')
			->order_by('ad')->get();
 
		foreach ($query->result() as $row) {
			echo $row->ad."\n";
		}
	}

	function auto_close()
	{
		$this->DB1->where("status", "2")->where("is_read", "1")->where("create_time < DATE_SUB(CURDATE(), INTERVAL 3 DAY)", null, false)->update("questions", array("status"=>"4"));
		$this->DB1->where("status", "2")->where("create_time < DATE_SUB(CURDATE(), INTERVAL 7 DAY)", null, false)->update("questions", array("status"=>"4"));
	}
	
	//補轉點數用
	function resend_transfer($order_id)
	{				
		$this->load->library("g_wallet");
		$order = $this->g_wallet->get_order($order_id);
		if (empty($order)) die(json_failure("交易不存在"));
		if (empty($order->question_id)) die(json_failure("請先記錄客服單號"));
		
		$server = $this->DB2->from("servers")->where("server_id", $order->server_id)->get()->row() or die(json_failure("遊戲資訊不正確"));
		$query = $this->DB2->from("games")->where("game_id", $server->game_id)->get();
		$game = ( $query->num_rows() > 0 ? $query->row() : false) or die(json_failure("遊戲資訊不正確"));
		if ($order->billing_type <> 2) {
            $transfer_order_id = $this->g_wallet->re_complete_order($order, $order->amount, $order->order_no);
			$transfer_order = $this->g_wallet->get_order($transfer_order_id);
		} else {
			$transfer_order = $order;
		}
				
		switch ($row->transaction_type) {
			case "inapp_billing_google":
				// 呼叫遊戲入點機制
				$this->load->library("game_api/{$server->game_id}");
				$re = $this->{$server->game_id}->iap_transfer($transfer_order, $server, "google_play", $order->product_id, $order->amount, 'TWD');
				break;
			case "inapp_billing_ios":
				// 呼叫遊戲入點機制
				$this->load->library("game_api/{$server->game_id}");
				$re = $this->{$server->game_id}->iap_transfer($transfer_order, $server, "app_store", $order->product_id, $order->amount, 'TWD');
				break;
			default:
				$this->load->library("game_api/{$server->game_id}");
				$re = $this->{$server->game_id}->transfer($server, $transfer_order, $order->amount, $game->exchange_rate);		
				break;
		}		

		if ($re === "1") {
			$this->g_wallet->complete_order($transfer_order);
			die(json_success());
		}
		else if ($re === "-1") {			
			$this->g_wallet->cancel_timeout_order($transfer_order);		
			die(json_failure("遊戲伺服器無回應"));			
		}
		else {
			$error_message = $this->{$server->game_id}->error_message;
			$this->g_wallet->cancel_order($transfer_order, $error_message);
			die(json_failure($error_message));	
		}				
	}
}
