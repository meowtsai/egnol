<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pepay extends MY_Controller {
	
	var $pepay_conf;
	
	function __construct()
	{
		parent::__construct();
		
		error_reporting(E_ALL);
		ini_set('display_errors','On');
		
		$this->load->config("g_pepay");
		$this->pepay_conf = $this->config->item("pepay");
	}
		
	function _make_trade_seq()
	{
		$row = $this->db->from("pepay_billing")->where("ORDER_ID like 'P".date("Ymd")."%'", null, false)->order_by("ORDER_ID desc")->limit(1)->get()->row();
		if ($row) {		
			$seq = substr($row->ORDER_ID, -6);
			$seq = intval($seq) + 1;
	  		$seq = str_pad($seq, 6, "0", STR_PAD_LEFT);
	  		$trade_seq = "P".date("Ymd") . $seq;
		} 
		else {
			$trade_seq = "P".date("Ymd")."000001";
		}
		return $trade_seq;
	}
	
	function order()
	{		
		$this->_require_login();
		
		$query = $this->db->query("SELECT count(*) > (15-1) as chk FROM pepay_billing WHERE uid={$this->g_user->uid} and create_time > date_sub(now(), INTERVAL 1 MINUTE)");
		if ($query->row()->chk) die("請勿連續送出，以免重複扣款，造成您的損失!!");			

		// 儲存儲值資料
		$_SESSION['payment_game']		= $this->input->post('game');
		$_SESSION['payment_server']		= $this->input->post('server');
		$_SESSION['payment_character']	= $this->input->post('character');
		$_SESSION['payment_type']		= $this->input->post('billing_type');
		$_SESSION['payment_channel']	= $this->input->post('billing_channel');
		$_SESSION['payment_api_call']   = $this->input->post('api_call');

		//
		$cShopID = $this->pepay_conf["ShopID"];
		$cSysTrustCode = $this->pepay_conf["SysTrustCode"];
		$cShopTrustCode = $this->pepay_conf["ShopTrustCode"];
		$cPaySelectUrl = $this->pepay_conf["PaySelectUrl"];
		
		if (check_mobile()) {
			$cPaySelectUrl = "http://mgate.pepay.com.tw/payselect_amt.php";
		}
		
		$cPayType = $this->input->post('pay_type'); //"ST-MOBILE"
		$cSubPayType = $this->input->post('subpay_type');
		$cProdID = $this->input->post('prod_id'); //"PD-BILL-FET"
		
		$server_id = $this->input->post('server');
		$nAmount = floatval($this->input->post("billing_money"));

		$cCurrency = "TWD";	
		$cOrderID = $this->_make_trade_seq();		
		//$cOrderItem = "商品xxx";
		//$cOrderItemUrlEncode = urlencode($cOrderItem);
		$cOrderItemUrlEncode = "";
				
		//如有自訂參數可由此帶入
		$cShopPara="";
		$cShopParaUrlEncode = urlencode($cShopPara);
		
		$cTmp = $cSysTrustCode."#".$cShopID."#".$cOrderID."#".$nAmount."#".$cShopTrustCode;
		$cCheckCode = md5($cTmp);
		
		$data = array(
			'uid' => $this->g_user->uid,
			"ORDER_ID" => $cOrderID,			
			"AMOUNT" => $nAmount,
			"CURRENCY" => $cCurrency,			
			"SHOP_PARA" => $cShopParaUrlEncode,
			"CHECK_CODE" => $cCheckCode,
			"PAY_TYPE" => $cPayType,
			"SUB_PAY_TYPE" => $cSubPayType,
			"PROD_ID" => $cProdID,
		);
		if ($server_id) $data["server_id"] = $server_id;
		
		$cnt = $this->db->where("ORDER_ID", $cOrderID)->from("pepay_billing")->count_all_results();
		if ($cnt > 0) die('請重新操作');
		
		$this->db->set("create_time", "NOW()", false)->set("update_time", "NOW()", false)->insert("pepay_billing", $data);		
		
		$data["SHOP_ID"] = $cShopID;
		$data["ORDER_ITEM"] = $cOrderItemUrlEncode;
		
		run_post($cPaySelectUrl, $data); 
	}
	
	function receive01()
	{		
		/*
		0:成功
		20001:廠商參數傳送失敗
		20002:檢查碼錯誤
		20003:廠商資料庫連接失敗
		20004:廠商資料庫寫入失敗
		20099:其他失敗
		*/
	
		$cShopID = $this->input->post('SHOP_ID');
		$cOrderID = $this->input->post('ORDER_ID');
		$nAmount = $this->input->post('AMOUNT');
		$cCurrency = $this->input->post('CURRENCY');
		$cSessID = $this->input->post('SESS_ID');
		$cProdID = $this->input->post('PROD_ID');
		$cCheckCode = $this->input->post('CHECK_CODE');
		
		$nRes = 0;
		$cOutput = $cUserID = $cShopParaUrlEncode = $cRetUrl = "";
		$cSysTrustCode = $this->pepay_conf["SysTrustCode"];
		$cShopTrustCode = $this->pepay_conf["ShopTrustCode"];
		
		$cTmp = $cSysTrustCode."#".$cShopID."#".$cOrderID."#".$nAmount."#".$cSessID."#".$cProdID."#".$cShopTrustCode;
		$cTrustCode=md5($cTmp);
		
		if ($cCheckCode != $cTrustCode) {
			$nRes = 20002;
		}
		else {
			$order = $this->db->where("ORDER_ID", $cOrderID)->get("pepay_billing")->row();
			if (empty($order)) {
				$nRes = 20003;
			}
			else {
				
				$this->db->where("ORDER_ID", $cOrderID)->update("pepay_billing", array("SESS_ID" => $cSessID, "status" => "1"));
			
				$cUserID = $order->uid;
				//$cShopPara = "aaa#bbb#ccc";
				//$cShopParaUrlEncode = urlencode($cShopPara);
				$cShopParaUrlEncode = "";
				$cRetUrl = site_url("pepay/returnurl");
				//$cRetUrl = "";
			}
		}
		
		//log_message("error", "receive01");
		
		//輸出此筆交易的USER_ID及結果RES_CODE
		//（如有自訂參數則再加上&SHOP_PARA=xxx（作UrlEncode）、
		//交易結果回傳網址則再加上RET_URL=http://www.xxx.com/xxx.php）
		$cOutput="USER_ID=".$cUserID."&RES_CODE=".$nRes."&SHOP_PARA=".$cShopParaUrlEncode."&RET_URL=".$cRetUrl."&";		
		echo $cOutput;		
	}
	
	function receive02()
	{
		$nRes = 0;
		$cExFlag = "";
		$cOutput = "";
		
		$cSessID = $this->input->get('SESS_ID');
		$cOrderID = $this->input->get('ORDER_ID');
		$cBillID = $this->input->get('BILL_ID');
		$cDataID = $this->input->get('DATA_ID');
		$cShopID = $this->input->get('SHOP_ID');
		$cPayType = $this->input->get('PAY_TYPE');
		$cProdID = $this->input->get('PROD_ID');
		$cUserID = $this->input->get('USER_ID');
		$nSource_Amount = $this->input->get('SOURCE_AMOUNT');
		$nAmount = $this->input->get('AMOUNT');
		$cCurrency = $this->input->get('CURRENCY');						
		$nDataCode = $this->input->get('DATA_CODE');
		$nTradeCode = $this->input->get('TRADE_CODE');	//成功:0 ； 失敗:不等於0
		$cShopPara = $this->input->get('SHOP_PARA');	//如廠商Receive01未帶則此參數為空
		$cCDate = $this->input->get('CDATE');
		$cCTime = $this->input->get('CTIME');
		$cBillDate = $this->input->get('BILL_DATE');
		$cBillTime = $this->input->get('BILL_TIME');
		$cDate = $this->input->get('DATE');
		$cTime = $this->input->get('TIME');
		$cCheckCode = $this->input->get('CHECK_CODE');
		
		$cSysTrustCode = $this->pepay_conf["SysTrustCode"];
		$cShopTrustCode = $this->pepay_conf["ShopTrustCode"];
		
		$cTmp = $cSysTrustCode."#".$cShopID."#".$cOrderID."#".$nAmount."#".$cSessID."#".$cProdID."#".$cUserID."#".$cShopTrustCode;
		$cTrustCode = md5($cTmp);
		if ($cCheckCode != $cTrustCode) {
			//檢查碼比對錯誤，有問題的資料
			$nRes = '2002';
		}
		else {
			$this->db->set("update_time", "NOW()", false)
				->where("ORDER_ID", $cOrderID)
				->update("pepay_billing", array(
							"BILL_ID" => $cBillID,
							"DATA_ID" => $cDataID,
							"DATA_CODE" => $nDataCode,
							"TRADE_CODE" => $nTradeCode,
							"BILL_DATE" => $cBillDate,
							"BILL_TIME" => $cBillTime,
							"DATE" => $cDate,
							"TIME" => $cTime,
						));

			if ($nTradeCode == '0') {
				$billing = $this->db->where("ORDER_ID", $cOrderID)->get("pepay_billing")->row();			
				$nRes = $this->_finish_payment($billing, false);
			}			
		}
		
		//log_message("error", "receive02");		
		die("RES_CODE={$nRes}&");	
		//pepay系統收到0或者20290則停止傳送，其他則視為廠商未完成接收處理而持續傳送
	}
	
	function returnurl()
	{
		//log_message("error", "returnurl");
		
		$nRes = 0;
		$cExFlag = "";
		$cOutput = "";
		
		$cSessID = $this->input->post('SESS_ID');
		$cOrderID = $this->input->post('ORDER_ID');
		$cShopID = $this->input->post('SHOP_ID');
		$cPayType = $this->input->post('PAY_TYPE');
		$cProdID = $this->input->post('PROD_ID');
		$cUserID = $this->input->post('USER_ID');
		$nSource_Amount = $this->input->post('SOURCE_AMOUNT');
		$nAmount = $this->input->post('AMOUNT');
		$cCurrency = $this->input->post('CURRENCY');							
		$nTradeCode = $this->input->post('TRADE_CODE');	//成功:0 ； 失敗:不等於0
		$cShopPara = $this->input->post('SHOP_PARA'); //如廠商Receive01未帶則此參數為空
		$cCDate = $this->input->post('CDATE');
		$cCTime = $this->input->post('CTIME');
		$cDate = $this->input->post('DATE');
		$cTime = $this->input->post('TIME');
		$cCheckCode = $this->input->post('CHECK_CODE');
		
		$cSysTrustCode = $this->pepay_conf["SysTrustCode"];
		$cShopTrustCode = $this->pepay_conf["ShopTrustCode"];
		
		$cTmp = $cSysTrustCode."#".$cShopID."#".$cOrderID."#".$nAmount."#".$cSessID."#".$cProdID."#".$cUserID."#".$cShopTrustCode;
		$cTrustCode = md5($cTmp);
		if ($cCheckCode != $cTrustCode) {
			//檢查碼比對錯誤，有問題的資料
			go_payment_result(0, 0, $nAmount, "檢查碼錯誤"); 			
		}
		else {				
			if ($nTradeCode == '0') {
				$billing = $this->db->where("ORDER_ID", $cOrderID)->get("pepay_billing")->row();
				$this->_finish_payment($billing, true);
			}
			else {
				$ResCode = $this->pepay_conf["ResCode"];
				$message = ($ResCode[(string)$nTradeCode])?$ResCode[(string)$nTradeCode]:$nTradeCode;
				
				go_payment_result(0, 0, $nAmount, $message);	
			}
		}	
	}
	
	function _finish_payment($billing, $go_result=false)
	{
		if (empty($billing)) 
			return $go_result ? go_payment_result(0, 0, $billing->AMOUNT, "訂單不存在") : '20299';
		
		if ($billing->status == "2") 
			return $go_result ? go_payment_result(0, 0, $billing->AMOUNT, "該訂單已經處理過了") : '20290';
		
		if (empty($billing->uid)) 
			return $go_result ? go_payment_result(0, 0, $billing->AMOUNT, "用戶ID遺失") : '20299';
			
		//儲值至用戶 (成功後status=2	
		$this->load->library("g_wallet");
		$order_id = $this->g_wallet->produce_income_order($billing->uid, "pepay_billing", $billing->id, $billing->AMOUNT);						
		if (empty($order_id)) return $go_result ? go_payment_result(0, 0, $billing->AMOUNT, "資料庫寫入失敗") : "20203";
		
		$this->db->set("update_time", "NOW()", false)
			->where("id", $billing->id)
			->update("pepay_billing", array("status" => "2"));			
		
		//轉入遊戲伺服器
		if ( ! empty($billing->server_id))
		{		
			$remain = $this->g_wallet->get_balance($billing->uid);
			$args = "rm={$remain}";
						
			$this->load->model("games");
			$server = $this->games->get_server($billing->server_id);
			if (empty($server)) return $go_result ? go_payment_result(1, 0, $billing->AMOUNT, "遊戲伺服器不存在", $args) : '0';					
			$game = $this->games->get_game($server->game_id);		
		
			if ($server->is_transaction_active == 0) {
				return $go_result ? go_payment_result(1, 0, $billing->AMOUNT, "遊戲伺服器目前暫停轉點服務", $args) : '0';
	    	}
			if ($this->g_wallet->chk_money_enough($billing->uid, $billing->AMOUNT) == false) {
				return $go_result ? go_payment_result(1, 0, $billing->AMOUNT, '餘額不足', $args) : '0';
			}
			if ($this->g_wallet->chk_balance($billing->uid) == false) { //不平衡
				return $go_result ? go_payment_result(1, 0, $billing->AMOUNT, '錯誤代碼 001', $args) : '0';
			}
			
			//建單，並扣款
			$order_id = $this->g_wallet->produce_order($billing->uid, "top_up_account", "2", $billing->AMOUNT, $server->server_id, "", $_SESSION['payment_character']);
			if (empty($order_id)) $go_result ? go_payment_result(1, 0, $billing->AMOUNT, $this->g_wallet->error_message, $args) : '0';
				
			$order = $this->g_wallet->get_order($order_id);
			
			//轉入遊戲		
			$this->load->library("game_api/{$server->game_id}");
			$re = $this->{$server->game_id}->transfer($server, $order, $game->exchange_rate);
			$error_message = $this->{$server->game_id}->error_message;
			
			$this->db->reconnect(); //mysql wait_timeout為10秒，接口可能執行超過10秒
			
			if ($re === "1") {
				$this->g_wallet->complete_order($order);
				$args = "gp=".($billing->AMOUNT*$game->exchange_rate)."&sid={$billing->server_id}";
				return $go_result ? go_payment_result(1, 1, $billing->AMOUNT, "", $args) : '0';
			}
			else if ($re === "-1") {
				$this->g_wallet->cancel_timeout_order($order);			
				return $go_result ? go_payment_result(1, 0, $billing->AMOUNT, "遊戲伺服器沒有回應(錯誤代碼: 002)", $args) : '0';		
			}
			else if ($re === "-2") {			
				$this->g_wallet->cancel_other_order($order, $error_message);
				return $go_result ? go_payment_result(1, 0, $billing->AMOUNT, "{$error_message}(錯誤代碼: 003)", $args) : '0';
			}
			else {
				$this->g_wallet->cancel_order($order, $error_message);		
				return $go_result ? go_payment_result(1, 0, $billing->AMOUNT, "{$error_message}", $args) : '0';		
			}
		}
		else return $go_result ? go_payment_result(1, 2, $billing->AMOUNT) : '0';		
	}
}

function go_payment_result($status, $transfer_status, $price, $message='', $args='') 
{
	$site = $_SESSION['site'];
	$api_call = $_SESSION['payment_api_call'];

    $_SESSION['payment_api_call'] = '';
	unset($_SESSION['payment_api_call']);

	if($api_call == 'true')
		header('location: '.g_conf('url', 'api')."api/ui_payment_result?s={$status}&ts={$transfer_status}&p={$price}&m=".urlencode($message)."&".$args);
	else
		header('location: '.g_conf('url', 'longe')."payment/result?site={$site}&s={$status}&ts={$transfer_status}&p={$price}&m=".urlencode($message)."&".$args);
	exit();
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */