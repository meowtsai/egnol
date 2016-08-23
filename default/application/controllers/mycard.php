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
		$row = $this->db->from("mycard_billing")->where("fac_trade_seq like '".date("Ymd")."%'", null, false)->order_by("fac_trade_seq desc")->limit(1)->get()->row();
		if ($row) {		
			$seq = substr($row->fac_trade_seq, -6);
			$seq = intval($seq) + 1;
	  		$seq = str_pad($seq, 6, "0", STR_PAD_LEFT);
	  		$trade_seq = date("Ymd") . $seq;
		} 
		else {
			$trade_seq = date("Ymd")."000001";
		}
		return $trade_seq;
	}
	
	function order() //實體卡
	{
		$this->_require_login();
        
        $payment_type = $this->input->post("pay_type");
        //$item_code = $this->input->post("prod_id");
        //$product_name = $this->input->post("prod_id");
        $currency = $this->input->post("currency");
        $amount = $this->input->post("amount");
		
		$_SESSION['payment_game']		= $this->input->post('game');
		$_SESSION['payment_server']		= $this->input->post('server');
		$_SESSION['payment_character']	= $this->input->post('character');
		$_SESSION['payment_api_call']   = $this->input->post('api_call');
		//$_SESSION['payment_type']		= $this->input->post('billing_type');
		//$_SESSION['payment_channel']	= $this->input->post('billing_channel');
        //print_r($this->input->post());
        //die();
        
		//新建訂單
		$fac_trade_seq = $this->_make_trade_seq();
		$data = array(
			'uid'			=> $this->g_user->uid,
			'fac_trade_seq'	=> $fac_trade_seq,		
			'status' 		=> '0',
			'payment_type'	=> '',
            "trade_type"    => '2',	
            "currency"      => $currency,	
			'item_code'		=> ''
		);
		if ($this->input->post("server")) $data["server_id"] = $this->input->post("server");	
        
		$mycard_billing_id = $this->mycards->insert_billing($data);
		
		$data = array(
			'FacServiceId' => $this->mycard_conf["facId"],
			'FacTradeSeq'  => $fac_trade_seq,
			'TradeType'    => "2",
			'ServerId'     => $data["server_id"],
			'CustomerId'   => $this->g_user->uid,
			'PaymentType'  => '',
			'ItemCode'     => '',
			'ProductName'  => $amount,
			'Amount'       => $amount,
			'Currency'     => $currency,
			'SandBoxMode'  => (ENVIRONMENT == 'development')?"true":"false"
		);
        
		log_message("error", "mycard order:".implode("", $data));
        
        $urlencoded = urlencode(implode("", $data).$this->mycard_conf["hash_key"]);
		$data['Hash'] = hash('sha256', strval($urlencoded));
		
		$auth_url = $this->mycard_conf["auth_url"]."?".http_build_query($data);
        
		$cnt = 0;
		while ($cnt++ < 5) {
			$result = json_decode(my_curl($auth_url));
			if ( ! empty($result)) break;
			sleep(1);
		} 	
		
		if (empty($result)) {
			go_payment_result(0, 0, 0, "mycard伺服器無回應");	
		}
		
		if ($result->ReturnCode == '1') 
		{
			$this->mycards->update_billing(array("trade_seq" => $result->TradeSeq, "auth_code" => $result->AuthCode), array("fac_trade_seq" => $fac_trade_seq)); //更新trade_type
			
			if ($result->InGameSaveType == '1') // 1為server-side Ingame topup
			{ 
				$this->load->library("g_wallet");		
				$this->_init_mycard_layout()	
					->set("remain", $this->g_wallet->get_balance($this->g_user->uid))
					->set("authCode", $result->AuthCode)
					->set("fac_trade_seq", $fac_trade_seq)					
					->add_js_include("mycard/card")
					->render();	//"", "inner2"
			}
			else if ($result->InGameSaveType == '2') //2為web-side Ingame topup 
			{ 
				/*
                $data = array(
					'authCode' => $result->AuthCode,
					'facId' => $this->mycard_conf["facId"],
					'facMemId' => $this->g_user->account,
				);
				
				$data['hash'] = hash('sha256', $this->mycard_conf["key1_new"].$data['authCode'].$data['facId'].$data['facMemId'].$this->mycard_conf["key2_new"]);
                */
				$mycard_ingame_url = $this->mycard_conf['pay_url']."?AuthCode=".$result->AuthCode;
                $this->load->library("g_wallet");
                $this->g_wallet->produce_mycard_order($this->g_user->uid, $mycard_billing_id, "mycard_billing", $amount, $_SESSION['payment_character'], "1", $_SESSION['payment_server']);
                
				header('location:'.$mycard_ingame_url);
				exit();
			}
			else {
				go_payment_result(0, 0, 0, 'other:'.$result->InGameSaveType);
			}
		}
		else {
			go_payment_result(0, 0, 0, $result->ReturnMsg);
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
	
	function return_url() //web-side Ingame callback 
	{
		$post = $this->input->post();
		if (empty($post)) go_payment_result(0, 0, 0, "未傳遞參數");
		
		$hash = hash('sha256', $post['ReturnCode'].$post['PayResult'].$post['FacTradeSeq']
				.$post['PaymentType'].$post['Amount'].$post['Currency'].$post['MyCardTradeNo'].$post['MyCardType']
				.$post['PromoCode'].$this->mycard_conf["hash_key"]); 
		
		if ($post['Hash'] !== $hash) {
			go_payment_result(0, 0, 0, "驗證碼錯誤");
		}
		
		//檢查訂單
		$mycard_billing = $this->mycards->get_billing_row(array("fac_trade_seq" => $post['FacTradeSeq']));
		if (empty($mycard_billing)) {
			go_payment_result(0, 0, 0, "mycard訂單不存在");
		}
		if ($mycard_billing->status <> '0') {
			go_payment_result(0, 0, 0, "此筆mycard訂單已結案");
		}
		/*if ($this->mycards->check_value_exists("mycard_card_id", $post['CardId']) === true) {
			die("此筆mycard_id已使用");
		}*/
		
		$error_message = ($post['ReturnCode'] <> 1) ? urldecode($post['ReturnMsg'])."[{$post['ReturnCode']}]" : "";
        
		if ($this->mycards->check_value_exists("mycard_trade_seq", $post['MyCardTradeNo']) === true) {
			go_payment_result(0, 0, 0, $error_message);
		}
		
		$_SESSION['payment_type']		= $post['PaymentType'];
		$_SESSION['payment_channel']	= $post['MyCardType'];
		$_SESSION['cuid']	            = $post['Currency'];
		$_SESSION['oid']	            = $post['MyCardTradeNo'];
        
        $data = array(
            'result'		=> $post['ReturnCode'],
            'mycard_trade_seq' => $post['MyCardTradeNo'],
            'mycard_type'	=> $post['MyCardType'],
            'amount'		=> $post['Amount'],
			'payment_type'	=> $post['PaymentType'],
            'status' 		=> '2',
            'note' 			=> $error_message,
        );
        $this->mycards->update_billing($data, array("fac_trade_seq" => $post['FacTradeSeq']));
        
		if ($post['ReturnCode'] == 1) {
            $this->confirm($mycard_billing);
		} else {
		    go_payment_result($post['ReturnCode'], 0, $post['Amount'], $error_message);
        }
	}
	
    function confirm($mycard_billing, $is_get_order=0)
	{
        $confirm_url = $this->mycard_conf["confirm_url"]."?AuthCode=".$mycard_billing->auth_code;
        
        $cnt = 0;
        while ($cnt++ < 3) {
            $confirm_result = json_decode(my_curl($confirm_url));
            if ( ! empty($confirm_result)) break;
            sleep(1);
        } 	

        if (empty($confirm_result)) {
            if ($is_get_order) return 0;
            go_payment_result(0, 0, 0, "mycard伺服器無回應");	
        }
        
        if ($confirm_result->PayResult == "3") {
            $this->mycards->update_billing(
                array(
                    "is_confirm" => 1, 
                    "status" => 3, 
                    "amount" => $confirm_result->Amount, 
                    "mycard_trade_seq" => $confirm_result->MyCardTradeNo, 
                    "mycard_type" => $confirm_result->MyCardType, 
                    "payment_type" => $confirm_result->PaymentType), 
                array("fac_trade_seq" => $mycard_billing->fac_trade_seq));

            $billing_url = $this->mycard_conf["billing_url"]."?AuthCode=".$mycard_billing->auth_code;

            $cnt = 0;
            while ($cnt++ < 3) {
                $billing_result = json_decode(my_curl($billing_url));
                if ( ! empty($billing_result)) break;
                sleep(1);
            } 	
            
            if (empty($billing_result)) {
                if ($is_get_order) return 0;
                go_payment_result(0, 0, 0, "mycard伺服器無回應");	
            }

            if ($billing_result->ReturnCode == "1") {
                $this->mycards->update_billing(array("cash_out" => 1, "status" => 4, "cash_out_time" => date('Y-m-d H:i:s'), "result" => 1), array("fac_trade_seq" => $mycard_billing->fac_trade_seq));

                $user_billing = $this->db->where("mycard_billing_id", $mycard_billing->id)->get("user_billing")->row();

                $this->load->library("game");
                $this->game->payment_transfer($mycard_billing->uid, $mycard_billing->server_id, $confirm_result->Amount, $user_billing->partner_order_id, $user_billing->character_id, $mycard_billing->trade_seq, "", $mycard_billing->id);

                if ($is_get_order) return 1;
                go_payment_result(1, 1, $confirm_result->Amount, $error_message);
            } else {
                if ($is_get_order) return 0;
                go_payment_result(0, 0, 0, $billing_result->ReturnMsg);
            }
        } else {
            if ($is_get_order) return 0;
            go_payment_result(0, 0, 0, $confirm_result->ReturnMsg);
        }
	}
	
	function ingame_result()
	{
		$this->_init_mycard_layout()
			->set("status", $this->input->get("status"))
			->set("message", urldecode($this->input->get("message")))
			->render("", "inner");	
	}
	
	//mycard billing 主動通知CP廠商交易成功
	function get_order()
	{
        if(!IN_OFFICE) die('0');
		if (empty($this->input->post('DATA'))) die("未傳遞參數");
		log_message("error", "get_order:".$this->input->post('DATA'));
		
        $json = json_decode($this->input->post('DATA'));
        
	    $factory_id = (string)$json->FacServiceId;
	    $total_num = (int)$json->TotalNum;

	    foreach($json->FacTradeSeq as $item)
	    {
	    	$row = $this->db->from("mycard_billing")->where("fac_trade_seq", $item)->get()->row();
	    	if ($row && ! empty($row->auth_code)) 
	    	{	        	
                if ($row->cash_out == 0) $this->confirm($row, 1);
	    	}
	    }		
	}
	
    //mycard billing 交易成功資料之差異比對
	function inform()
	{
        if(!IN_OFFICE) die('0');
		if ($this->input->post() == false) die("未傳遞參數");
        
        $mycard_trade_seq = $this->input->post('MyCardTradeNo');
        $start_time = $this->input->post('StartDateTime');
        $end_time = $this->input->post('EndDateTime');

        if (!$mycard_trade_seq && !$start_time) die("未傳遞參數");
        
        if ($mycard_trade_seq) $this->db->where("mycard_trade_seq", $mycard_trade_seq);
        if ($start_time) $this->db->where("create_time >=", date('Y-m-d H:i:s', strtotime($start_time)-4800));
        if ($end_time) $this->db->where("create_time <=", date('Y-m-d H:i:s', strtotime($end_time)-4800));
        
		$mycard_billing = $this->db->from("mycard_billing")->where("result", "1")->order_by("trade_seq asc")->get();
        
		foreach($mycard_billing->result() as $row) {
        
            echo $row->payment_type.",".$row->trade_seq.",".$row->mycard_trade_seq.",".$row->fac_trade_seq.",".$row->uid.",".$row->amount.",".$row->currency.",".date('Y-m-d\TH:i:s', strtotime($row->create_time))."<BR>";
        }
	}
	
	function payment_result()
	{
		$this->_init_mycard_layout()
			->set("status", $this->input->get("status"))
			->set("message", urldecode($this->input->get("message")))
			->render("", "inner");		
	}
}

function go_payment_result($status, $transfer_status, $price, $message, $args='') 
{
	// 若沒有設定 site, 表示是系統 check_order 處理或有問題, 不用顯示結果
	if(!isset($_SESSION['site']))
	   exit();
	
	$site = $_SESSION['site'];
	$api_call = $_SESSION['payment_api_call'];

	$_SESSION['payment_api_call'] = '';
	unset($_SESSION['payment_api_call']);

	if($api_call == 'true')
		header('location: '.g_conf('url', 'api')."api2/ui_payment_result?s={$status}&ts={$transfer_status}&p={$price}&m=".urlencode($message)."&".$args);
	else
		header('location: '.g_conf('url', 'longe')."payment/result?site={$site}&s={$status}&ts={$transfer_status}&p={$price}&m=".urlencode($message)."&".$args);
	exit();
}
/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */