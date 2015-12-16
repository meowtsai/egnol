<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Wallet extends MY_Controller {	
		
	function __construct()
	{
		parent::__construct();
		
		$this->_require_login();
		$this->load->library("g_wallet");
		$this->load->helper("transfer");
	}
	
	function _init_transfer_layout()
	{
		$this->_init_layout();
		$this->g_layout->set_breadcrumb(array("儲值"=>"PayFun/PayMethod.php", "轉換遊戲點數"=>"eWallet/transfer_index.php"));
	}
	
	//transfer.step1
	function transfer($game_id)
	{
		if (IN_OFFICE) {
			header("location:/wallet2/transfer/{$game_id}", TRUE, 307);
			exit;
		}
		
		if (empty($game_id)) go_index("參數錯誤");
				
		$channel = $this->input->get("channel");
		if (strpos($this->g_user->account, "@omg") !== false && $channel != 'omg') {
			header("location:".site_url("wallet/transfer/{$game_id}?channel=omg"));
			exit();
		}
		
		$this->load->model("games");
		$game = $this->games->get_game($game_id);
		$game_list = $this->games->get_server_list($game_id);
		
		if (empty($game)) go_index("遊戲不存在");
		
		if ($channel == 'omg') {
			$product_point = array(100, 200, 500, 1000, 2000, 5000, 10000);
		}
		else {		    
			//設定測試帳號儲值金額
	        if (in_array($this->g_user->uid, array('304757', '300187', '440569', '433555', '433558', '433560'))) {
	    		$product_point = array(1, 10, 50, 100, 200, 500, 1000); 
			} 	
			else {
    			$product_point = array(50, 100, 200, 500, 1000, 2000, 5000, 10000);
			}
		}
	
		$this->_init_transfer_layout();
		$this->g_layout
			->add_breadcrumb($game->name)
			->set("layout_tmp", '<img src="/PayFun/img/service-icon-e.gif" width="171" height="25" border="0" usemap="#Map" />')
			->set("channel", $channel)
			->set("product_point", $product_point)
			->set("game", $game)			
			->set("game_list", $game_list)
			->set("remain", $this->g_wallet->get_balance($this->g_user->uid))
			->add_js_include("wallet/transfer")
			->render();
	}
	
	//transfer.step2
	function recheck_transfer()
	{
		if (IN_OFFICE) {
			header("location:/wallet2/recheck_transfer", TRUE, 307);
			exit;
		}		
		
		$channel = $this->input->post("channel");
		$server_id = $this->input->post("server_id");
		$price = $this->input->post("price");
		chk_price($price);

		$this->load->model("games");
		$server = $this->games->get_server($server_id);
		if (empty($server)) go_index("參數錯誤");
			
		$game = $this->games->get_game($server->game_id);
					
		$error_message = '';
				
	    if ( $server->is_transaction_active == 0 && ! IN_OFFICE ){
        	$error_message = '遊戲伺服器目前暫停轉點服務，詳情請參閱遊戲官網公告。';
    	}		
		
    	if ($channel == 'omg') {
    		$form_action =site_url("transfer/omg/submit_order");
    	}
    	else {
    		if ($this->g_wallet->chk_money_enough($this->g_user->uid, $price) == false) $error_message = '餘額不足';
    		$form_action = site_url("wallet/transfer_money");
    	}
    	
		$this->_init_transfer_layout();
		$this->g_layout
			->add_breadcrumb($game->name)
			->set("layout_tmp", '<img src="/PayFun/img/service-icon-e.gif" width="171" height="25" border="0" usemap="#Map" />')
			->set("channel", $channel)
			->set("server", $server)
			->set("game", $game)			
			->set("server_id", $server_id)
			->set("server", $server)
			->set("price", $price)
			->set("form_action", $form_action)
			->set("remain", $this->g_wallet->get_balance($this->g_user->uid))
			->set("error_message", $error_message)
			->render();		
	}
	
	//transfer.step3
	function transfer_money()
	{
		if (IN_OFFICE) {
			header("location:/wallet2/transfer_money", TRUE, 307);
			exit;
		}			
		
		$server_id = $this->input->post("server_id");
		$price = $this->input->post("price");		
		chk_price($price);	
		
		$this->load->model("games");
		$server = $this->games->get_server($server_id);
		if (empty($server)) go_index();
		$game = $this->games->get_game($server->game_id);		
						
		//$this->g_user->set_user('687201', '100000990286281@facebook', 'test', ''); //測試用，但非測試帳號，僅測試機使用 
		
		if ( $server->is_transaction_active == 0  && ! IN_OFFICE ) {
			go_result(0, '遊戲伺服器目前暫停轉點服務，詳情請參閱遊戲官網公告。', "gi_id={$server_id}");
    	}
		if ($this->g_wallet->chk_money_enough($this->g_user->uid, $price) == false) {
			go_result(0, '餘額不足。', "gi_id={$server_id}");
		}
		if ($this->g_wallet->chk_balance($this->g_user->uid) == false) { //不平衡
			go_result(0, '轉點失敗，若持續發生此問題，請至客服中心與我們聯繫。(錯誤代碼: 001)', "gi_id={$server_id}");
		}

		if ($server->game_id == 'dh') {
			$order_no = "Ts".date("YmdHis",time()).rand(11,99);
		} else $order_no = '';
		
		//建單，並扣款
		$order_id = $this->g_wallet->produce_order($this->g_user->uid, "top_up_account", "2", $price, $server->server_id, $order_no);

		if (empty($order_id)) {
			go_result(0, $this->g_wallet->error_message, "gi_id={$server_id}");
		}
		
		$order = $this->g_wallet->get_order($order_id);
		
		//轉入遊戲		
		$this->load->library("game_api/{$server->game_id}");
		$re = $this->{$server->game_id}->transfer($server, $order, $game->exchange_rate);
		$error_message = $this->{$server->game_id}->error_message;
		
		$this->db->reconnect(); //mysql wait_timeout為10秒，接口可能執行超過10秒
		
		if ($re === "1") {
			$this->g_wallet->complete_order($order);
			$remain = $this->g_wallet->get_balance($this->g_user->uid);
			go_result(1, "恭喜您！成功將龍邑點數 <b>{$price}</b> 點轉至遊戲，目前餘額 <b>{$remain}</b>。", "gi_id={$server_id}");
		}
		else if ($re === "-1") {
			$this->g_wallet->cancel_timeout_order($order);			
			go_result(0, "遊戲伺服器沒有回應，轉點未完成，請至客服中心與我們聯繫。(錯誤代碼: 002)", "gi_id={$server_id}");		
		}
		else if ($re === "-2") {			
			$this->g_wallet->cancel_other_order($order, $error_message);
			go_result(0, "{$error_message}，轉點未完成，請至客服中心與我們聯繫。(錯誤代碼: 003)", "gi_id={$server_id}");
		}
		else {
			$this->g_wallet->cancel_order($order, $error_message);		
			go_result(0, $error_message, "gi_id={$server_id}");		
		}
	}

	//transfer.step4	
	function transfer_result()
	{
		if (IN_OFFICE) {
			$get = $this->input->get();
			header("location:/wallet2/transfer_result?".http_build_query($get), TRUE, 307);
			exit;
		}					
		
		$this->load->model("games");
	
		$is_active = $this->input->get("is_active");
		$message = $this->input->get("message");
		
		$game_id = $this->input->get("game_id");
		$server_id = $this->input->get("gi_id");

		$server = $game = false;
		
		if ($server_id) {
			$server = $this->games->get_server($server_id);
			if (empty($server)) go_index("參數錯誤");
			$game = $this->games->get_game($server->game_id);
		}
		else if ($game_id) {
			$game = $this->games->get_game($game_id);
		}	

		$this->_init_transfer_layout();
		
		if ($game) $this->g_layout->add_breadcrumb($game->name);
		
		$this->g_layout
			->set("layout_tmp", '<img src="/PayFun/img/service-icon-e.gif" width="171" height="25" border="0" usemap="#Map" />')
			->set("game", $game)
			->set("server", $server)	
			->set("status", $status)
			->set("message", $message)
			->render();		
	}	
	
}
