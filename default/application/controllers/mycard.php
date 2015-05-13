<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mycard extends MY_Controller {
	
	var $mycard_conf;
	
	function __construct()
	{
		parent::__construct();
		
		$this->load->config("g_mycard");
		$this->load->model("mycards");
		$this->mycard_conf = $this->config->item("mycard");
	}
	
	function _init_mycard_layout()
	{
		return $this->_init_layout()
			->set_breadcrumb(array("儲值"=>"payment", "MyCard 購點"=>""))
			->set("subtitle", "Mycard購點");
	}
	
	function _make_trade_seq()
	{
		$row = $this->db->from("mycard_billing")->where("trade_seq like '".date("Ymd")."%'", null, false)->order_by("trade_seq desc")->limit(1)->get()->row();
		if ($row) {		
			$seq = substr($row->trade_seq, -6);
			$seq = intval($seq) + 1;
	  		$seq = str_pad($seq, 6, "0", STR_PAD_LEFT);
	  		$trade_seq = date("Ymd") . $seq;
		} 
		else {
			$trade_seq = date("Ymd")."000001";
		}
		return $trade_seq;
	}
	
	function ingame() //實體卡
	{
		$this->g_user->check_login('', true);
		
		//新建訂單
		$trade_seq = $this->_make_trade_seq();
		$data = array(
			'uid'			=> $this->g_user->uid,
			'trade_seq'		=> $trade_seq,
			'service_id' 	=> 'long_e',				
			'status' 		=> '0',
		);
		if ($this->input->post("server")) $data["server_id"] = $this->input->post("server");		
		$this->mycards->insert_billing($data);
		
		$data = array(
			'facId' => $this->mycard_conf["facId"],
			'facTradeSeq' => $trade_seq,
		);
		$data['hash'] = hash('sha256', $this->mycard_conf["key1_new"].$data['facId'].$data['facTradeSeq'].$this->mycard_conf["key2_new"]);
		
		$auth_url = $this->mycard_conf["auth_url"]."?".http_build_query($data);
		
		$cnt = 0;
		while ($cnt++ < 3) {
			$result = json_decode(my_curl($auth_url));
			if ( ! empty($result)) break;
			sleep(1);
		} 	
		
		if (empty($result)) {
			go_ingame_result(0, 0, "mycard伺服器無回應(ingame_auth)");	
		}
		
		if ($result->ReturnMsgNo == '1') 
		{
			$this->mycards->update_billing(array("trade_type" => $result->TradeType), array("trade_seq" => $trade_seq)); //更新trade_type
			
			if ($result->TradeType == '1') // 1為server-side Ingame topup
			{ 
				$this->load->library("g_wallet");		
				$this->_init_mycard_layout()	
					->set("remain", $this->g_wallet->get_balance($this->g_user->uid))
					->set("authCode", $result->AuthCode)
					->set("trade_seq", $trade_seq)					
					->add_js_include("mycard/card")
					->render();	//"", "inner2"
			}
			else if ($result->TradeType == '2') //2為web-side Ingame topup 
			{ 
				$data = array(
					'authCode' => $result->AuthCode,
					'facId' => $this->mycard_conf["facId"],
					'facMemId' => $this->g_user->account,
				);
				
				$data['hash'] = hash('sha256', $this->mycard_conf["key1_new"].$data['authCode'].$data['facId'].$data['facMemId'].$this->mycard_conf["key2_new"]);
				$mycard_ingame_url = $this->mycard_conf['mycard_ingame_url']."?".http_build_query($data);

				header('location:'.$mycard_ingame_url);
				exit();
			}
			else {
				go_ingame_result(0, 0, 'other:'.$result->TradeType);
			}
		}
		else {
			//die($result->ReturnMsg);
			go_ingame_result(0, 0, $result->ReturnMsg);
		}		
	}
	
	/*
	function run_ingame_callback()
	{
		$_POST['facId'] = $this->mycard_conf["facId"];
		$_POST['facMemId'] 		= '304757';
		$_POST['facTradeSeq'] 	= '20130108000001';
		$_POST['tradeSeq'] 		= 'SW130124021013';
		$_POST['CardId'] 		= 'MCCVEC0000007378';
		$_POST['oProjNo'] 		= 'A0000';
		$_POST['CardKind'] 		= '4';
		$_POST['CardPoint'] 	= '300';
		$_POST['ReturnMsgNo'] 	= '1';
		$_POST['ErrorMsgNo'] 	= '';
		$_POST['ErrorMsg'] 		= '';
		$post = $this->input->post();
		
		$_POST['hash'] = hash('sha256', $this->mycard_conf["key1"].$post['facId'].$post['facMemId'].$post['facTradeSeq']
				.$post['tradeSeq'].$post['CardId'].$post['oProjNo'].$post['CardKind'].$post['CardPoint']
				.$post['ReturnMsgNo'].$post['ErrorMsgNo'].$post['ErrorMsg'].$this->mycard_conf["key2"]);

		$this->ingame_callback();
	}*/
	
	function ingame_callback() //web-side Ingame callback 
	{
		$post = $this->input->post();
		if (empty($post)) die("未傳遞參數");
		
		$hash = hash('sha256', $this->mycard_conf["key1"].$post['facId'].$post['facMemId'].$post['facTradeSeq']
				.$post['tradeSeq'].$post['CardId'].$post['oProjNo'].$post['CardKind'].$post['CardPoint']
				.$post['ReturnMsgNo'].$post['ErrorMsgNo'].$post['ErrorMsg'].$this->mycard_conf["key2"]); 
		
		if ($post['hash'] !== $hash) {
			die("驗證碼錯誤");
		}
		
		//檢查訂單
		$mycard_billing = $this->mycards->get_billing_row(array("trade_seq" => $post['facTradeSeq']));
		if (empty($mycard_billing)) {
			die("mycard訂單不存在");
		}
		if ($mycard_billing->status <> '0') {
			die("此筆mycard訂單已結案");
		}
		if ($this->mycards->check_value_exists("mycard_card_id", $post['CardId']) === true) {
			die("此筆mycard_id已使用");
		}
		if ($this->mycards->check_value_exists("mycard_trade_seq", $post['tradeSeq']) === true) {
			die("此筆mycard序號已使用");
		}
		
		$error_message = ($post['ReturnMsgNo'] <> 1) ? $post['ErrorMsg']."[{$post['ErrorMsgNo']}]" : "";
		$data = array(
			'trade_ok'		=> $post['ReturnMsgNo'],
			'mycard_trade_seq' => $post['tradeSeq'],
			'mycard_card_id' => $post['CardId'],
			'mycard_type'	=> $post['CardKind'],
			'product_code'	=> $post['CardPoint'],
			'amount'		=> $post['CardPoint'],
			'payment_id'	=> $post['oProjNo'],
			'status' 		=> '2',
			'note' 			=> $error_message,
		);
		$this->mycards->update_billing($data, array("trade_seq" => $post['facTradeSeq']));
		
		if ($post['ReturnMsgNo'] == 1) {
			//進錢包
			$this->load->library("g_wallet");
			$this->g_wallet->produce_mycard_order($mycard_billing->uid, $mycard_billing->id, "mycard_ingame", $post['CardPoint']);
			
			$this->transfer_to_game($mycard_billing, $post['CardPoint']);
		}		
		go_ingame_result($post['ReturnMsgNo'], $post['CardPoint'], $error_message);
	}
	
	function confirm()
	{
		$this->g_user->check_login('', true);
		
		$cardId = $this->input->post("cardId");
		$cardPwd = $this->input->post("cardPwd");
		$authCode = $this->input->post("authCode");
		$trade_seq = $this->input->post("trade_seq");		
		
		if (empty($cardId) || empty($cardPwd)) {
			header("Content-type: text/html; charset=utf-8"); 
			die("<script type='text/javascript'>alert('序號或密碼尚未填寫'); history.back(-1);</script>");
		}
		if (empty($authCode) || empty($trade_seq)) die('訂單資訊遺失無法繼續');
		
		//檢查訂單
		$mycard_billing = $this->mycards->get_billing_row(array("uid"=>$this->g_user->uid, "trade_seq"=>$trade_seq));	
		if (empty($mycard_billing)) {
			 die("mycard訂單不存在");
		}
		if ($mycard_billing->status <> '0') {
			die("此筆mycard訂單已結案");
		}
		
		//更新訂單資訊
		$data = array(
			'auth_code' => $authCode,	
			'mycard_card_id' => $cardId,
			'mycard_pwd' => $cardPwd,
			'status'	=> '1',
		);
		$this->mycards->update_billing($data, array("uid"=>$this->g_user->uid, "trade_seq"=>$trade_seq));
		
		//confirm
		$data = array(
			'facId' => $this->mycard_conf["facId"],
			'authCode' => $authCode,	
			'facMemId' => $this->g_user->account,		
			'cardId' => $cardId,
			'cardPwd' => $cardPwd,
		);		
		$data['hash'] = hash('sha256', $this->mycard_conf["key1"].$data['facId'].$data['authCode'].$data['facMemId'].$data['cardId'].$data['cardPwd'].$this->mycard_conf["key2"]);	
		$confirm_url = $this->mycard_conf["confirm_url"]."?".http_build_query($data);
		//fb($confirm_url, 'confirm_url');
		
		$cnt = 0;
		while ($cnt++ < 3) {
			$result = json_decode(my_curl($confirm_url), TRUE);
			fb($result, 'confirm');
			if ( ! empty($result)) break;
			sleep(1);
		}
		 
		if (empty($result)) {
			$error_message = "mycard伺服器無回應，請稍後再試。";
			$this->mycards->update_billing_note($error_message, array("uid"=>$this->g_user->uid, "trade_seq"=>$trade_seq));
			go_ingame_result(0, 0, $error_message);	
		}
		
		$message = $result['ReturnMsg']."[{$result['ReturnMsgNo']}]";
		if ($result['ReturnMsgNo'] == '1') 
		{					
			$data = array(
				'trade_ok'		=> $result['ReturnMsgNo'],
				'mycard_trade_seq' => $result['SaveSeq'],
				'mycard_type'	=> $result['CardKind'],
				'product_code'	=> $result['CardPoint'],
				'amount'		=> $result['CardPoint'],
				'payment_id'	=> $result['oProjNo'],
				'status' 		=> '2',
				'note'			=> $message,
			);			
			$this->mycards->update_billing($data, array("uid"=>$this->g_user->uid, "trade_seq"=>$trade_seq));				
			
			//進錢包
			$this->load->library("g_wallet");
			$this->g_wallet->produce_mycard_order($this->g_user->uid, $mycard_billing->id, "mycard_ingame", $result['CardPoint']);
			
			$this->transfer_to_game($mycard_billing, $result['CardPoint']);
			
			go_ingame_result($result['ReturnMsgNo'], $result['CardPoint'], $message);
		}
		else 
		{			
			$this->mycards->update_billing_note($message, array("uid"=>$this->g_user->uid, "trade_seq"=>$trade_seq));			
			go_ingame_result($result['ReturnMsgNo'], 0, $message);
		}	
	}
	
	function ingame_result()
	{
		$this->_init_mycard_layout()
			->set("status", $this->input->get("status"))
			->set("message", urldecode($this->input->get("message")))
			->render("", "inner");	
	}
	
	function choose()
	{
		$this->g_user->check_login('', true); 
		
		$f = file_get_contents(site_url("mycard/get_payment"));
		$price = json_decode($f); 
		
		$this->load->library("g_wallet");
				
		$this->_init_mycard_layout()			
			//->set("layout_tmp", '<img src="/PayFun/img/service-icon-f.gif" width="171" height="25" border="0" usemap="#Map" />')
			->set("remain", $this->g_wallet->get_balance($this->g_user->uid))
			->set("price", $price)
			->add_js_include("mycard/choose")
			->render("");				
	}		
		
	function get_payment($pid='', $use_backup=false) 
	{
		$file = file_get_contents("p/output/mycard_payments".($use_backup ? '_backup' : '').".txt");
		if ($file) 
		{
			$data = json_decode($file);
			if (count($data) > 0 ) 
			{						
				if ($pid) {
					$data2 = array();
					$enable = array('SPS0119398','SPS0119422','SPS0015467','SPS0015460','SPS0015463','SPS0119436','SPS0119450','SPS0119464','SPS0137799','SPS0137802','SPS0137804','SPS0137798','SPS0137801','SPS0137803','SPS0137797','SPS0137800','SPS0119394','SPS0119418','SPS0119376','SPS0119377','SPS0119378','SPS0119434','SPS0119440','SPS0119379','SPS0119403','SPS0015320','SPS0015314','SPS0119380','SPS0119404','SPS0015336','SPS0015329','SPS0119386','SPS0119410','SPS0015383','SPS0015378','SPS0119387','SPS0119411','SPS0015390','SPS0015385','SPS0119385','SPS0119409','SPS0015376','SPS0015371','SPS0119390','SPS0119414','SPS0015414','SPS0015408','SPS0119395','SPS0119419','SPS0095455','SPS0095443','SPS0119396','SPS0119420','SPS0095456','SPS0095444','SPS0119402','SPS0119426','SPS0015449','SPS0015443','SPS0122489','SPS0122490','SPS0122491','SPS0122492');
					$alias = array("第一銀行WebATM"=>"01_線上ATM(免出門，需讀卡機)", "中國信託實體ATM"=>"02_實體ATM(需出門至ATM轉帳繳款)", "台灣地區信用卡付款"=>"03_信用卡", "Seednet"=>"04_Seednet", "So-Net"=>"05_So-Net", "中華電信HiNet"=>"06_中華電信HiNet", "中華電信市內電話輕鬆付"=>"07_中華電信市內電話輕鬆付", "中華電信839"=>"08_中華電信839", "台灣大哥大電信"=>"09_台灣大哥大電信", "亞太電信"=>"10_亞太電信", "威寶電信"=>"11_威寶電信", "遠傳和信電信"=>"12_遠傳和信電信", "支付寶AliPay"=>"13_支付寶AliPay");
					foreach($data->$pid->items as $key => $item) {
						if (in_array($key, $enable)) {
							$data2[$key] = strtr($item, $alias);							
						}
					}
					asort($data2);
					foreach($data2 as $key => $item) {
						$spt = explode("_", $item);
						if (count($spt)>1) {
							$data2[$key] = $spt[1];	
						} else $data2[$key] = $spt[0];						
					}
					echo json_encode($data2);
				}
				else {
					$data2 = array();
					foreach($data as $key => $item) {
						$data2[$item->price] = $item->price; //bean. follow舊規則，但很想改 $item->price => $key
					}
					echo json_encode($data2);
				}
			} 
			else echo '[]';
		} 
		else echo '[]';
	}
	
	function output_payments()
	{		
		$mycard = array();
		$file = file_get_contents($this->mycard_conf['product_query_url']);
		if ($file) 
		{
	        $file = strip_tags($file);
	        $spt = explode(",", $file);
			
			$price = array();
	        foreach($spt as $key => $value) {
	            $spt2 = explode("|", $value);
	            $mycard[$spt2[0]] = array("price" => preg_replace("/([a-z]+)/", '', $spt2[0]));
	        }

			foreach ($mycard as $pid => $value) {
				$file = file_get_contents(sprintf($this->mycard_conf['payments_query_url'], $pid));
				if ($file) 
				{
			        $file = strip_tags($file);
			        $spt = explode(",", $file);			
			        $mycard[$pid]["items"] = array();
			        foreach($spt as $key => $value) {
			            $spt2 = explode("|", $value);
			            $mycard[$pid]["items"][$spt2[0]] = $spt2[1];
			        }
				}		
			}			
			echo file_put_contents("p/output/mycard_payments.txt", json_encode($mycard)) ? '寫入成功' : '寫入失敗';
		}
		else echo 'mycard資料讀取失敗';		
	}	
	
	function print_payments()
	{
		$mycard = array();
		$file = file_get_contents($this->mycard_conf['product_query_url']);
		if ($file) 
		{
	        $file = strip_tags($file);
	        $spt = explode(",", $file);
			
			$price = array();
	        foreach($spt as $key => $value) {
	            $spt2 = explode("|", $value);
	            $mycard[$spt2[0]] = array("price" => preg_replace("/([a-z]+)/", '', $spt2[0]));
	        }

			foreach ($mycard as $pid => $value) {
				$file = file_get_contents(sprintf($this->mycard_conf['payments_query_url'], $pid));
				if ($file) 
				{
			        $file = strip_tags($file);
			        $spt = explode(",", $file);			
			        $mycard[$pid]["items"] = array();
			        foreach($spt as $key => $value) {
			            $spt2 = explode("|", $value);
			            $mycard[$pid]["items"][$spt2[0]] = $spt2[1];
			        }
				}		
			}			
			echo "<pre>";
			print_r($mycard);
			echo "</pre>";
		}
		else echo 'mycard資料讀取失敗';		
	}
	
	function get_product($payment) 
	{
		$file = file_get_contents("p/output/mycard_payments.txt");
		if ($file) 
		{
			$data = json_decode($file);
			if (count($data) > 0 ) 
			{				
				$products = array();
				foreach($data as $product) {
					foreach($product->items as $key => $value) {
						if ($value == urldecode($payment)) {
							$products[$product->price] = $key;
						}
					}
				}
				echo json_encode($products);
			} 
			else echo '[]';
		} 
		else echo '[]';
	}	
	
	//------------------------------------ mycard_billing
	
	function redirect_mycard_billing()
	{		
		$service_id = $this->input->post('service_id');
		$payment_amount = $this->input->post('payment_amount');
		$server_id = $this->input->post('server');		
		 
		if ( empty($service_id) || empty($payment_amount) ) go_payment_result(0, 0, 0, "尚未選取");
		
		//$service_id = 'SPS0003523'; //------
		//$payment_amount = '50'; //------
		
		$fatory_id = 'long_e';
		$product_id = "long_e{$payment_amount}";		
		
		//check ok insert status 交易階段記錄-第一步(剛進入交易起始，產生交易序號
		$trade_seq = $this->_make_trade_seq();
		$data = array(
			'uid'			=> $this->g_user->uid,
			'trade_seq'		=> $trade_seq,
			'service_id' 	=> 'long_e',				
			'status' 		=> '1',
			'payment_id'	=> $service_id,
			'product_code'	=> $product_id,
			'amount'		=> $payment_amount,				
		);		
		if ($server_id) $data["server_id"] = $server_id;
		$this->mycards->insert_billing($data);
		
		//交易階段記錄-第二步(檢查交易方式選項
		$this->mycards->update_billing_statuse("2", array("trade_seq"=>$trade_seq));
		//像是不需要，暫不寫
		
		//交易階段記錄-第三步(向mycard金流取回交易用認証碼，並導向至mycard 金流頁面
		//3.1.1
		//auth_code     取得Auth_Code
		//交易代碼|交易訊息|MyCard交易序號|交易授權碼
		//1|授權成功|MHI100211022023|7/oJXrxCWltON4hjIekd6RabDLWOE49zg0m/XPZ2xZA=
		
		$this->mycards->update_billing_statuse("3", array("trade_seq"=>$trade_seq));
		
		$url = sprintf($this->mycard_conf["payment_auth_url"], $service_id, $trade_seq, $payment_amount);
		
		$result = my_curl($url);
		if (empty($result)) go_payment_result(0, 0, $payment_amount, "mycard伺服器無回應");
		$pieces = explode("|", strip_tags($result));
				
		if ($pieces[0] == 1)
		{
			$auth_code = $pieces[3]; 
			$this->mycards->update_billing(array(
						'trade_code' => $pieces[2],
						'auth_code'	=> $auth_code,
					), array("trade_seq"=>$trade_seq));
			
			$url = sprintf($this->mycard_conf["mycard_billing_url"], $auth_code);
			header("Location: ".$url);
    		exit();
		}
		else  go_payment_result(0, 0, $payment_amount, $pieces[1]);
	}
	
	function mycard_trade_err()
	{		
	    $trade_seq = $this->input->post('TradeSeq');
	    $ReturnNo = $this->input->post('ReturnMsgNo');
	    $ReturnMsg = urldecode($this->input->post('ReturnMsg'));
	    
		if (isset($_POST["TradeSeq"]))
		{		
			$this->db->where("trade_seq", $trade_seq)->update("mycard_billing", 
					array(
						"status" => "6",
						"note" => "{$ReturnNo}|{$ReturnMsg}",
					));			
		}
		go_payment_result(0, 0, 0, $ReturnMsg);
	}
	
	function mycard_resend($id) 
	{
		if ( ! IN_OFFICE) die('不允許');
		
    	$row = $this->db->from("mycard_billing")->where("id", $id)->get()->row();
    	if ($row && ! empty($row->auth_code)) 
    	{	        	
    		echo "<form id='form' action='".site_url("mycard/payment_confirm")."'>
    				<input type='hidden' name='ReturnMsgNo' value='1'>
    				<input type='hidden' name='ReturnMsg' value='0'>
    				<input type='hidden' name='AuthCode' value='{$row->auth_code}'>
    			</form>
    			<script type='text/javascript'>document.getElementById('form').submit();</script>
    		";
    	}
	}
	
	//mycard billing 主動通知CP廠商交易成功
	function mycard_inform()
	{
		if ($this->input->post() == false) die("未傳遞參數");
		
		/*
		$_POST['data'] = "<BillingApplyRq> 
					<FatoryId>xxxxxx</FatoryId> 
					<TotalNum>2</TotalNum> 
					<Records> 
						<Record> 
							<ReturnMsgNo>1</ReturnMsgNo> 
							<ReturnMsg></ReturnMsg> 
							<TradeSeq>20130603000013</TradeSeq> 
						</Record> 
						<Record> 
							<ReturnMsgNo>1</ReturnMsgNo> 
							<ReturnMsg></ReturnMsg> 
							<TradeSeq>20130531000016</TradeSeq> 
						</Record> 
					</Records> 
			</BillingApplyRq>";*/
		
	    $xml = simplexml_load_string($this->input->post('data'));	
	    $fatory_id = (string)$xml->FatoryId;
	    $total_num = (int)$xml->TotalNum;

	    foreach($xml->Records->Record as $item)
	    {
	    	$row = $this->db->from("mycard_billing")->where("trade_seq", (string)$item->TradeSeq)->get()->row();
	    	if ($row && ! empty($row->auth_code)) 
	    	{	        	
	    		$data = array(
		        	"ReturnMsgNo" =>  (string)$item->ReturnMsgNo,
		        	"ReturnMsg" => (string)$item->ReturnMsg,
		        	"AuthCode" => $row->auth_code,
		        );
		        my_curl(site_url("mycard/payment_confirm"), $data);
	    	}
	    }		
	}
	
	function payment_confirm()
	{
		$msg_no = $this->input->post("ReturnMsgNo");
		$msg = $this->input->post("ReturnMsg", TRUE);
		$auth_code = $this->input->post("AuthCode");
		
		if (empty($msg_no) || empty($auth_code)) go_payment_result(0, 0, 0, "參數未傳遞");
		
		if ($msg_no == '1') 
		{
			$billing = $this->db->where("auth_code", $auth_code)->from("mycard_billing")->get()->row();
			if (empty($billing)) go_payment_result(0, 0, 0, "查無交易記錄");
			
			//retrun process 交易階段記錄-第四步(剛離開mycard金流開始檢查交易正確性
			$this->db->where("id", $billing->id)->update("mycard_billing", array("status"=>"4"));
			
			//驗證MyCard交易
			//回傳格式：查詢結果代碼|查詢結果|交易結果
			//<string xmlns="http://schemas.microsoft.com/2003/10/Serialization/">-2|無此遊戲廠商登入IP|0</string>
			$url = $this->mycard_conf['certifying_url']."?AuthCode={$auth_code}";
			$result = my_curl($url);
			//$result = '<string xmlns="http://schemas.microsoft.com/2003/10/Serialization/">1|查詢成功|3</string>'; //------
			if (empty($result)) go_payment_result(0, 0, 0, "mycard伺服器無回應:{$url}");
			$pieces = explode("|", strip_tags($result));
		
			$this->db->reconnect(); //mysql wait_timeout為10秒，接口可能執行超過10秒
			
			//confirm process 交易階段記錄-第五步(在認証mycard金流交易為正確後進行最後的記錄
			$this->db->where("id", $billing->id)->update("mycard_billing", array("status"=>"5"));
			
			if ($pieces[0] == 1)
			{
				$this->db->where("id", $billing->id)->update("mycard_billing", array("is_confirm"=>"1"));
								
				if ($pieces[2] == 3) 
				{
					$this->db->where("id", $billing->id)->update("mycard_billing", array("trade_ok"=>"1"));						
				}
				else go_payment_result(0, 0, 0, "驗證交易交敗");
			}
			else 
			{
				$this->db->where("id", $billing->id)->update("mycard_billing", array("note"=>$result));
				go_payment_result(0, 0, 0, "驗證交易失敗，{$pieces[1]}"); 
			}
			
			//確認MyCard交易，並進行請款
			//回傳格式：請款結果|請款訊息|智冠交易序號|連續扣款序號
			//<string xmlns="http://schemas.microsoft.com/2003/10/Serialization/">1|更新成功|MAP120718012405|</string>
			$url = $this->mycard_conf['payment_confirm_url']."?CPCustId={$billing->uid}&AuthCode={$auth_code}";
			$result = my_curl($url);
			//$result = '<string xmlns="http://schemas.microsoft.com/2003/10/Serialization/">1|更新成功|MAP120718012405|</string>'; //----
			if (empty($result)) go_payment_result(0, 0, 0, "mycard伺服器無回應{$url}");
			$pieces = explode("|", strip_tags($result));

			$this->db->reconnect(); //mysql wait_timeout為10秒，接口可能執行超過10秒
			
			if ($pieces[0] == 1)
			{
				$this->db->where("id", $billing->id)->update("mycard_billing", array("cash_out"=>"1", "trade_code"=>$pieces[2]));		
			}
			else
			{
				$this->db->where("id", $billing->id)->update("mycard_billing", array("note"=>$result));
				go_payment_result(0, 0, 0, "確認交易失敗，{$pieces[1]}");
			}
			
			$amount = substr($billing->product_code, 4);			
						
			//儲值至用戶
			if (empty($billing->uid)) go_payment_result(0, 0, $amount, "用戶資訊遺失");
			
			$this->load->library("g_wallet");
			$order_id = $this->g_wallet->produce_mycard_order($billing->uid, $billing->id, "mycard_billing", $amount);
			if (empty($order_id)) go_payment_result(0, 0, $amount, $this->g_wallet->error_message);
			
			$this->transfer_to_game($billing, $amount);			
		}
		else go_payment_result(0, 0, 0, $msg);
	}
	
	function transfer_to_game($billing, $point)
	{
		if (empty($billing->server_id)) return;
		
		$this->load->library("g_wallet");
		$remain = $this->g_wallet->get_balance($billing->uid);
		$args = "rm={$remain}";
					
		$this->load->model("games");
		$server = $this->games->get_server($billing->server_id);
		if (empty($server)) go_payment_result(1, 0, $point, "遊戲伺服器不存在", $args);					
		$game = $this->games->get_game($server->game_id);		
	
		if ( $server->is_transaction_active == 0) {
			go_payment_result(1, 0, $point, "遊戲伺服器目前暫停轉點服務", $args);
    	}
		if ($this->g_wallet->chk_money_enough($billing->uid, $point) == false) {
			go_payment_result(1, 0, $point, '餘額不足', $args);
		}
		if ($this->g_wallet->chk_balance($billing->uid) == false) { //不平衡
			go_payment_result(1, 0, $point, '錯誤代碼 001', $args);
		}
		
		//建單，並扣款
		$order_id = $this->g_wallet->produce_order($billing->uid, "top_up_account", "2", $point, $server->server_id, "");			
		if (empty($order_id)) go_payment_result(1, 0, $point, $this->g_wallet->error_message, $args);
			
		$order = $this->g_wallet->get_order($order_id);
		
		//轉入遊戲		
		$this->load->library("game_api/{$server->game_id}");
		$re = $this->{$server->game_id}->transfer($server, $order, $game->exchange_rate);
		$error_message = $this->{$server->game_id}->error_message;
		
		$this->db->reconnect(); //mysql wait_timeout為10秒，接口可能執行超過10秒
		
		if ($re === "1") {
			$this->g_wallet->complete_order($order);
			$args = "gp=".($point*$game->exchange_rate)."&sid={$billing->server_id}";
			go_payment_result(1, 1, $point, "", $args);
		}
		else if ($re === "-1") {
			$this->g_wallet->cancel_timeout_order($order);			
			go_payment_result(1, 0, $point, "遊戲伺服器沒有回應(錯誤代碼: 002)", $args);		
		}
		else if ($re === "-2") {			
			$this->g_wallet->cancel_other_order($order, $error_message);
			go_payment_result(1, 0, $point, "{$error_message}(錯誤代碼: 003)", $args);
		}
		else {
			$this->g_wallet->cancel_order($order, $error_message);		
			go_payment_result(1, 0, $point, "{$error_message}", $args);		
		}	
	}
	
	function payment_result()
	{
		$this->_init_mycard_layout()
			->set("status", $this->input->get("status"))
			->set("message", urldecode($this->input->get("message")))
			->render("", "inner");		
	}	
	
	function m_is_eligible()
	{
		die("m_is_eligible");
	}
	
	function m_bridge()
	{
		die("m_bridge");
	}
}

function go_ingame_result($status, $price, $message, $args='') 
{
	header('location: '.site_url("payment/result?s={$status}&ts=2&p={$price}&m=".urlencode($message)."&".$args));
	exit();
}

function go_payment_result($status, $transfer_status, $price, $message, $args='') 
{
	header('location: '.site_url("payment/result?s={$status}&ts={$transfer_status}&p={$price}&m=".urlencode($message)."&".$args));
	exit();
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */