<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Beanfun extends MY_Controller {
	
	function __construct()
	{
		parent::__construct();
		
		$this->load->library("g_wallet");
		$this->load->helper("transfer");
	}	
	
	function index($game_id)
	{
		$product_point = array(50, 100, 200, 1000); //beanfun額度限制1~1000				
			
		if ($this->input->get("str_OTP"))
		{
			$str_ServiceAccountID = $this->input->get("str_ServiceAccountID");
			$str_OTP = $this->input->get("str_OTP");
			
			//$str_ServiceAccountID = "B2201212261136130001";
  			//$str_OTP = "11111111";
		
			$this->load->library("channel_api/beanfun_api");
			$result = $this->beanfun_api->verify_auth($str_ServiceAccountID, $str_OTP);
			if ($result->Code == '1') 
	    	{
				$this->beanfun_api->login_to_long_e($result, $game_id);
				$_SESSION['beanfun_id'] = $result->ServiceAccountID;
	    	}
	    	else 
	    	{
	    		unset($_SESSION['beanfun_id']);
	    		//若驗證失敗則將玩家導回Gamania網頁(http://tw.beanfun.com/playweb/index.aspx)重新操作。
	    		die("<script type='text/javascript'>
	    			alert('{$result->Message}');
	    			location.href='http://tw.beanfun.com/playweb/index.aspx';	
				</script>");
	    	} 
		}
		
		$this->load->model("games");
		$server_list = $this->games->get_server_list($game_id);
		$game_row = $this->games->get_game($game_id);
		
		$this->_init_layout()
			->set("game_id", $game_id)
			->set("beanfun_id", $_SESSION['beanfun_id'])
			->set("server_list", $server_list)
			->set("game_row", $game_row)
			->set("product_point", $product_point)
			->view("transfer/beanfun/{$game_id}");	  	
	}
	
	function trade($game_id)
	{
		if (empty($_SESSION['beanfun_id'])) {
			die("<script type='text/javascript'>
    			alert('尚未登入');
    			location.href='http://tw.beanfun.com/playweb/index.aspx';	
			</script>");
		}
		if ($this->_require_login($game_id) == false) {
			unset($_SESSION['beanfun_id']);
    		//若驗證失敗則將玩家導回Gamania網頁(http://tw.beanfun.com/playweb/index.aspx)重新操作。
    		die("<script type='text/javascript'>
    			alert('尚未登入');
    			location.href='http://tw.beanfun.com/playweb/index.aspx';	
			</script>");			
		}
		if (strpos($this->g_user->account, "@beanfun") === false) {
			unset($_SESSION['beanfun_id']);
			die("<script type='text/javascript'>
    			alert('尚未登入');
    			location.href='http://tw.beanfun.com/playweb/index.aspx';	
			</script>");
		}
		
		$str_ServiceAccountID = $_SESSION['beanfun_id'];
		$server = $this->input->post("server");
		$point = $this->input->post("point");

		if (empty($server)) {
			$this->_alert_message("請選擇伺服器", $game_id);
		}
		else if (empty($point) || intval($point)==0 || intval($point)>1000) {
			$this->_alert_message("金額設定錯誤", $game_id);
		}
		if (chk_trade_limit('beanfun_billing', '200000')) { //設定交易上限額，防洗點
			$this->_alert_message("本日已超過金流交易上限值", $game_id);
		}
		
		$this->load->model("games");
   		$server_row = $this->games->get_server($server);
   		
		if (empty($server_row)) $this->_alert_message("伺服器不存在", $game_id);
    	if ($game_id <> $server_row->game_id) $this->_alert_message("伺服器資料不相符", $game_id);
    		
    	$game_row = $this->games->get_game($game_id);
    	if (empty($game_row)) $this->_alert_message("遊戲不存在", $game_id);	
   		
		//判斷有無角色
		$this->load->library("game_api/{$game_id}");
		if (method_exists($this->{$game_id}, "check_role_status")) {
			$user = (object) array(
				"uid" => $this->g_user->uid, 
				"account" => $this->g_user->account,
				"account" => $this->g_user->account, 
				"euid" => $this->g_user->euid
			);				
			$re = $this->{$game_id}->check_role_status($server_row, $user);
			if ($re == "-1") $error_message = "伺服器無回應，請稍候再試";
    		else if ($re == "0") $error_message = "該伺服器沒有角色(".$this->{$game_id}->error_message.")";
    		else $error_message = "";
    		if ($error_message) {
    			$this->_alert_message($error_message, $game_id);
    		}
		}		
		
		$this->load->library("channel_api/beanfun_api");
		$result = $this->beanfun_api->check_point_enough($str_ServiceAccountID, $point, $game_id);
		if ($result->Code == '1') 
    	{
    		$result2 = $this->beanfun_api->trade($str_ServiceAccountID, $point, $game_id);
    		if ($result2->Code == '1') 
	    	{	
				//建單
				$billing_id = $this->g_wallet->produce_order($this->g_user->uid, "beanfun_billing", "2", $point, $server_row->server_id, $result2->TransactionID);
				if (empty($billing_id)) $this->_alert_message("建單失敗", $game_id);
				
				$order = $this->g_wallet->get_order($billing_id);
				
				//轉入遊戲		
				$re = $this->{$game_id}->transfer($server_row, $order, $game_row->exchange_rate);	 
				if ($re === "1") {
					$this->g_wallet->complete_order($order);
					$this->_alert_message("恭喜您！成功將beanfun!樂豆點數 {$point}點轉至遊戲。", $game_id);	 
				}
				else if ($re === "-1") {
					$this->g_wallet->cancel_timeout_order($order);		
					$this->_alert_message("遊戲端未有回應，請至客服中心與我們聯繫。(錯誤代碼: 002)", $game_id);	 
				}
				else {
					$error_message = $this->{$game_id}->error_message;
					$this->g_wallet->cancel_order($order, $error_message);	
					$this->_alert_message($error_message, $game_id);		
				}
	    	}
		    else 
	    	{
	    		$this->_alert_message($result2->Message, $game_id);
	    	}
    	}
	    else 
    	{
    		$this->_alert_message($result->Message, $game_id);
    	}
	}
	
	function _alert_message($message, $game_id)
	{
		die("<script type='text/javascript'>
    			alert('{$message}');
    			location.href='".site_url("transfer/beanfun/index/{$game_id}")."';
			</script>");
	}	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */