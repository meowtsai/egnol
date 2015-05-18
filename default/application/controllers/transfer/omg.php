<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Omg extends MY_Controller {
	
	var $storeid;
	var $hashkey;
	
	function __construct()
	{
		parent::__construct();
		
		$this->storeid = 'O2012071801';
	    $this->hashkey = '9b77d5e353a9';
		
		$this->load->library("g_wallet");
		$this->load->helper("transfer");
	}	
	
	function index()
	{		
		$this->_init_layout()
			->set_breadcrumb(array("OMG儲值"=>"transfer/omg"))	
			->set("subtitle", "OMG儲值")
			->set("submenu", "payment")		
			->render("", "inner2");	  
	}

	function submit_order()
	{		
		$this->g_user->check_login('', true);
				
		$server_id = $this->input->post("server_id");
		$price = $this->input->post("price");
		chk_price($price);
		chk_trade_limit('omg_billing', '200000', true); //設定交易上限額，防洗點
		
		$this->load->model("games");
		$server = $this->games->get_server($server_id);
		if (empty($server)) go_index();
		$game = $this->games->get_game($server->game_id);      
    	
		if ( $server->is_transaction_active == 0  && ! IN_OFFICE ) {
			go_result(0, "遊戲伺服器目前暫停轉點服務，詳情請參閱遊戲官網公告", "gi_id={$server_id}");
    	}		
		
		//建單，並扣款
		$billing_id = $this->g_wallet->produce_order($this->g_user->uid, "omg_billing", "2", $price, $server->server_id);
		if (empty($billing_id)) {
			go_result(0, $this->g_wallet->error_message, "gi_id={$server_id}");
		}

		//判斷有無角色
		$this->load->library("game_api/{$server->game_id}");
		if (method_exists($this->{$server->game_id}, "check_role_status")) {
			$user = (object) array(
				"uid" => $this->g_user->uid, 
				"account" => $this->g_user->account,
				"account" => $this->g_user->account, 
				"euid" => $this->g_user->euid
			);				
			$re = $this->{$server->game_id}->check_role_status($server, $user);
			if ($re == "-1") $error_message = "伺服器無回應，請稍候再試";
    		else if ($re == "0") $error_message = "該伺服器沒有角色(".$this->{$server->game_id}->error_message.")";
    		else $error_message = "";
    		if ($error_message) {
    			$order = $this->db->where("id", $billing_id)->get("user_billing")->row();
    			$this->g_wallet->cancel_order($order, $error_message);	
    			go_result(0, $error_message, "gi_id={$server_id}");
    		}
		}
    
	    header("Content-type: text/html; charset=big5");
	    
	    $cashtype = '1';
	    $tradeno = $billing_id;
	    $tradeamt = $price;
	    $currency = 'TWD';
	    $returnurl = site_url('transfer/omg/callback');
	    $tradedesc = mb_convert_encoding('龍邑科技《'.$game->name.'》', 'big5', 'utf-8');
	    
	    $tradeusedsite = $game->game_id;
	    $hash = MD5($this->storeid . "|" . $tradeno . "|" . $currency . "|" . $tradeamt . "|" . time() . "|" . $this->hashkey . "|" . $tradeusedsite);
?>
<form id="omg_form" name="omg_form" method="post" action="https://pay.omg.com.tw/Code/API/PaymentInterface/interface.aspx">
	<input type="hidden" name="storeid" value="<?=$this->storeid?>">
	<input type="hidden" name="cashtype" value="<?=$cashtype?>">
	<input type="hidden" name="tradeno" value="<?=$tradeno?>">
	<input type="hidden" name="tradeamt" value="<?=$tradeamt?>">
	<input type="hidden" name="currency" value="<?=$currency?>">
	<input type="hidden" name="returnurl" value="<?=$returnurl?>">
	<input type="hidden" name="tradedesc" value="<?=$tradedesc?>">
	<input type="hidden" name="hash" value="<?=$hash?>">
	<input type="hidden" name="timestamp" value="<?=time()?>">
	<input type="hidden" name="tradeusedsite" value="<?=$tradeusedsite?>">	
</form>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script type="text/javascript">
$(function(){
	$("#omg_form").submit();
})	
</script>
<?		
	}
	
	function callback()
	{
		$this->g_user->check_login('', true);
		
		$storeid = $_POST['storeid'];
		$tradeno = $_POST['tradeno'];
		$tradeamt = $_POST['tradeamt'];
		$paymentorderid = $_POST['paymentorderid'];
		$paymentorderamt = $_POST['paymentorderamt'];
		$paymentstatus = $_POST['paymentstatus'];
		$paymentmessage = urldecode($_POST['paymentmessage']);
		$timestamp = $_POST['timestamp'];
		$hash = $_POST['hash'];
		
		if ($hash !== MD5($storeid . "|" . $tradeno . "|" . $tradeamt . "|" . $paymentorderid . "|" . $paymentorderamt . "|" . $paymentstatus . "|" . $timestamp . "|" . $this->hashkey) ) {
			go_result(0, "驗證碼錯誤");
		}
		else if (empty($tradeno)) {
			go_result(0, "訂單編號遺失");
		}
		
		$billing_id = $tradeno;
		$order = $this->g_wallet->get_order($billing_id);
		if (empty($order)) {
			go_result(0, "訂單不存在");
		}		
		
		//更新第三方訂單號
		$this->g_wallet->update_order($order, array("order"=>$paymentorderid));
		
		$key_name = $order->pay_server_id;
		$price = $order->amount;

		$cnt = $this->db->from("omg_billing")->where("user_billing_id", $billing_id)->count_all_results();				
		if ($cnt == 0) {
			//新增omg交易記錄
			$this->db
				->set('trade_date', 'now()', false)
				->set('create_time', 'now()', false)
				->insert("omg_billing", array(
					'u_id' => $this->g_user->uid,
					'tradeamt' => $tradeamt,
					'user_billing_id' => $billing_id,
					'status' => $paymentstatus,
					'paymentorderamt' => $paymentorderamt,
					'paymentorderid' => $paymentorderid,
			));
			$insert_id = $this->db->insert_id();
			if (empty($insert_id)) {
				go_result(0, "omg_billing 記錄新增錯誤");
			}
		}
		else {
			go_result(0, "交易已結束");
		}

		$this->load->model("games");
		$server = $this->games->get_server_by_server_id($key_name);
		if (empty($server)) go_index();
		$game = $this->games->get_game($server->game_id);		
		
		if ($paymentstatus != '1') {
			$this->g_wallet->cancel_order($order, $paymentmessage);
			go_result(0, $paymentmessage, "gi_id={$server->server_id}");
		}						
		
		//轉入遊戲		
		$this->load->library("game_api/{$server->game_id}");
		$re = $this->{$server->game_id}->transfer($server, $order, $game->exchange_rate);
		$error_message = $this->{$server->game_id}->error_message;
		
		if ($re === "1") {
			$this->g_wallet->complete_order($order);
			go_result(1, "恭喜您！成功將OMG點數 <b>{$price}</b> 點轉至遊戲。", "gi_id={$server->server_id}");
		}
		else if ($re === "-1") {
			$this->g_wallet->cancel_timeout_order($order);			
			go_result(0, "遊戲端未有回應，請至客服中心與我們聯繫。(錯誤代碼: 002)", "gi_id={$server->server_id}");		
		}
		else if ($re === "-2") {			
			$this->g_wallet->cancel_other_order($order, $error_message);
			go_result(0, "{$error_message}，轉點未完成，請至客服中心與我們聯繫。(錯誤代碼: 003)", "gi_id={$server->server_id}");
		}		
		else {
			$this->g_wallet->cancel_order($order, $error_message);			
			go_result(0, $error_message, "gi_id={$server->server_id}");	
		}
	}
	
	function omg_check_order()
	{		
		$p = $this->input->post();
		header("Content-type: text/html; charset=utf-8");
			
		if ($p['hash'] !== MD5($p['storeid'] . "|" . $p['tradeno'] . "|" . $p['tradeamt'] . "|" . $p['paymentorderid'] . "|" . $p['paymentorderamt'] . "|" . $p['paymentstatus'] . "|" . $p['tradeusedsite'] . "|" . $p['timestamp'] . "|" . $this->hashkey) ) {
			die("0|驗證碼錯誤");
		}
		else if (empty($p['tradeno'])) {
			die("0|訂單編號遺失");
		}
		
		$billing = $this->db->from("user_billing")->where("id", $p['tradeno'])->get()->row();
		if (empty($billing)) {
			die("0|訂單不存在");
		}
		
		if ($billing->result == '1') $message = 'OK';
		else {
			$message = empty($billing->note) ? '交易過期' : $billing->note;
		}
		
		die("{$billing->result}|{$message}");
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */