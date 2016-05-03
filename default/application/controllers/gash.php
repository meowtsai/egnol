<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Gash extends MY_Controller {
	
	var $gash_conf;
	
	function __construct()
	{
		parent::__construct();
		
		$this->load->config("g_gash");
		$this->gash_conf = $this->config->item("gash");
	}

	function _make_trade_seq()
	{
		$row = $this->db->from("gash_billing")->where("COID like 'G".date("Ymd")."%'", null, false)->order_by("COID desc")->limit(1)->get()->row();
		if ($row) {		
			$seq = substr($row->COID, -6);
			$seq = intval($seq) + 1;
	  		$seq = str_pad($seq, 6, "0", STR_PAD_LEFT);
	  		$trade_seq = "G".date("Ymd") . $seq;
		} 
		else {
			$trade_seq = "G".date("Ymd")."000001";
		}
		return $trade_seq;
	}
	
	function order()
	{		
		$this->_require_login();
		
		header("Content-Type:text/html; charset=utf-8");
		
		$query = $this->db->query("SELECT count(*) > (15-1) as chk FROM gash_billing WHERE uid={$this->g_user->uid} and create_time > date_sub(now(), INTERVAL 1 MINUTE)");
		if ($query->row()->chk) die("請勿連續送出，以免重複扣款，造成您的損失!!");		

		// 儲存儲值資料
		$_SESSION['payment_game']		= $this->input->post('game');
		$_SESSION['payment_server']		= $this->input->post('server');
		$_SESSION['payment_character']	= $this->input->post('character');
		$_SESSION['payment_type']		= $this->input->post('billing_type');
		$_SESSION['payment_channel']	= $this->input->post('billing_channel');
		$_SESSION['payment_api_call']   = $this->input->post('api_call');
		$_SESSION['payment_partner_order_id'] = $this->input->post('partner_order_id');

		$country = $this->input->get("country");
		
		require_once dirname(__FILE__).'/../libraries/gash/Common.php';
		
		$trans = new Trans( null );
			
		$PAID = $this->input->post("PAID");
		$CUID = $this->input->post("CUID");
		$ERP_ID = $this->input->post("ERP_ID");
		$payment_amount = floatval($this->input->post("billing_money"));
		$server_id = $this->input->post('server');

		if (empty($CUID)) die('參數未傳遞');

		$this->load->config("g_gash");
		$gash_conf = $this->config->item("gash");
					
		$money = floor($payment_amount / $gash_conf["converter"][$CUID]);
		if ($PAID <> "COPGAM02" && $PAID <> "COPGAM05" && $money < 100 && ! IN_OFFICE) die("儲值金額須大於100");
		if ($PAID == "COPGAM02") $payment_amount = 0;
				
		$trans->nodes = array(
			"MSG_TYPE"		=> "0100", // 交易訊息代碼_ 0100: 交易/訂單查詢請求Request
			"PCODE"			=> "300000", // 交易處理代碼_  一般交易請使用 300000, 月租交易請使用 303000, 月租退租請使用 330000
			"CID"			=> $this->gash_conf[$country]["CID"], // 商家遊戲代碼
			"COID"			=> $this->_make_trade_seq(), // 商家訂單編號
			"CUID"			=> $CUID, // 幣別 ISO Alpha Code
			"PAID"			=> $PAID, // 付款代收業者代碼 
			"AMOUNT"		=> $payment_amount, // 交易金額
			"RETURN_URL"	=> g_conf('url', 'payment')."gash/return_url?country={$country}", // 商家接收交易結果網址
			"ORDER_TYPE"	=> "M", // 是否指定付款代收業者_ 請固定填 M
			"MEMO"			=> "", // 交易備註 ( 此為選填 )
			"ERP_ID"		=> $ERP_ID, // 樂點卡ERP商品代碼 ( 此為選填 )
			"PRODUCT_NAME"	=> $_SESSION['payment_game']." ".$_SESSION['payment_server'], // 商家商品名稱 ( 此為選填 )
			"PRODUCT_ID"	=> "", // 商家商品代碼 ( 此為選填 )
			"USER_ACCTID"	=> $this->g_user->uid, // 玩家帳號 ( 此為選填 )
		);
		
		// 商家交易驗證壓碼
		$trans->nodes["ERQC"] = $trans->GetERQC($this->gash_conf[$country]["key"], $this->gash_conf[$country]["secret1"], $this->gash_conf[$country]["secret2"]);
		
		//echo "<pre>".print_r($trans->nodes, true)."</pre>";
		//exit();
		
        $data = array(
			'uid'		=> $trans->nodes["USER_ACCTID"],
			'MSG_TYPE' 	=> $trans->nodes["MSG_TYPE"],
			'PCODE' 	=> $trans->nodes["PCODE"],
			'COID' 		=> $trans->nodes["COID"],
			'CUID'		=> $trans->nodes["CUID"],
			'PAID'		=> $trans->nodes["PAID"],					
			'AMOUNT'	=> $trans->nodes["AMOUNT"],
			'ERQC'		=> $trans->nodes["ERQC"],					
			'ERP_ID'	=> $trans->nodes["ERP_ID"],
			'country'	=> $country,
		);
		if ($server_id) $data["server_id"] = $server_id;
		
		$cnt = $this->db->where("COID", $trans->nodes["COID"])->from("gash_billing")->count_all_results();
		if ($cnt > 0) die('請重新操作');
			
		$this->db->set("create_time", "NOW()", false)->set("update_time", "NOW()", false)->insert("gash_billing", $data);
		
        $gash_billing_id = $this->db->insert_id();
        
		$this->load->library("g_wallet");
		$order_id = $this->g_wallet->produce_gash_order($this->g_user->uid, $gash_billing_id, $trans->nodes["AMOUNT"], $_SESSION['payment_character'], $this->_make_trade_seq(), $_SESSION['payment_partner_order_id'], "0");
		if (empty($order_id)) go_payment_result(0, 0, $money, $this->g_wallet->error_message);
        
		print_r($trans->nodes);
		print_r($data);
		//die;
		
		// 取得送出之交易資料
		$data = $trans->GetSendData();
		log_message("error", "order:".$data);
		//die($data);
?>
	<html> 
	<head> 
	<title>GPS Transaction</title> 
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	</head>
	<body>
		<form name="form1" id="form1" action="<?=$this->gash_conf["url"]["order"]?>" method="post">
		<input type="hidden" name="data" value="<?php echo $data ?>">
		</form>		
		<script type="text/javascript">
			document.getElementById('form1').submit();
		</script>
		
	</body>
	</html>
<? 	
	}
	
	function return_url()
	{
		require_once dirname(__FILE__).'/../libraries/gash/Common.php';
		
		$country = $this->input->get("country");
		if (empty($country)) $country = 'tw';
		if ( ! in_array($country, array("tw", "global"))) {
			log_message('error', 'gash/return_url country error: '.$country);
			die('操作錯誤');
		}
		
		// 解析回傳結果
		$trans = new Trans( $this->input->post("data") );
		log_message("error", "return_url:".$this->input->post("data"));
		//echo "<pre>回傳".print_r($trans->nodes, true)."</pre>";
		
		/*if ($trans->nodes["RCODE"] = '1999') {
			$this->db->set("modify_date", "NOW()", false)
				->where("COID", $trans->nodes["COID"])->update("gash_billing", array(
					"RCODE" 	=> $trans->nodes["RCODE"]
				));	
			go_payment_result(0, 0, 0, "查無訂單");
		}
		*/
		
		log_message('error', $trans->nodes["COID"].", ".$trans->nodes["RCODE"]);
		
		// 檢核 GPS 交易驗證壓碼
		if ( $trans->VerifyERPC($this->gash_conf[$country]["secret1"], $this->gash_conf[$country]["secret2"]) )
		{					
			$this->db->set("update_time", "NOW()", false)
				->where("COID", $trans->nodes["COID"])->update("gash_billing", array(
					"MSG_TYPE" 	=> $trans->nodes["MSG_TYPE"],
					"PCODE" 	=> $trans->nodes["PCODE"],
					"RRN" 		=> $trans->nodes["RRN"],
					"AMOUNT" 	=> $trans->nodes["AMOUNT"], //會更新
					"ERPC" 		=> $trans->nodes["ERPC"],
					"RCODE" 	=> $trans->nodes["RCODE"],
					"PAY_RCODE" => $trans->nodes["PAY_RCODE"],
					"PAY_STATUS" => $trans->nodes["PAY_STATUS"],		
					"TXTIME"	=> $trans->nodes["TXTIME"],
					"USER_IP" 	=> $trans->nodes["USER_IP"],
					"ERQC"		=> $trans->GetERQC($this->gash_conf[$country]["key"], $this->gash_conf[$country]["secret1"], $this->gash_conf[$country]["secret2"]), //amount會更新，須重跑
				));
		
			$this->load->config("g_gash");
			$gash_conf = $this->config->item("gash");
						
			$gash_billing = $this->db->where("COID", $trans->nodes["COID"])->from("gash_billing")->get()->row();
			
			$money = (int) $gash_billing->AMOUNT / $gash_conf["converter"][$gash_billing->CUID];
									
			if ( $trans->nodes["RCODE"] == "0000") 
			{
				if ($trans->nodes["PAY_STATUS"] == "S" ) 
				{					
					if ($gash_billing->status == "2") go_payment_result(0, 0, $money, "此訂單已結案");	
					
					//請款 (成功後status=1
					if ($gash_billing->status == "0") $this->settle($gash_billing->id, $country);
					
					//儲值至用戶 (成功後status=2										
					if (empty($gash_billing->uid)) go_payment_result(0, 0, $money, "用戶資訊遺失");										
					
					$this->load->library("g_wallet");
                    
                    
					$user_billing = $this->db->where("gash_billing_id", $gash_billing->id)->get("user_billing")->row();
                    
					//$order_id = $this->g_wallet->produce_gash_order($gash_billing->uid, $gash_billing->id, $money, $_SESSION['payment_character'], $trans->nodes["COID"], $_SESSION['payment_partner_order_id']);
					//if (empty($order_id)) go_payment_result(0, 0, $money, $this->g_wallet->error_message);
                    $order_id = $user_billing->id;
                    
					$this->db->where("id", $order_id)->update("user_billing", array("result" => "1", "amount" => $money));	
					
					$this->db->where("COID", $trans->nodes["COID"])->update("gash_billing", array("status" => "2"));					
					
					//一并轉入遊戲伺服器					
					if ($gash_billing->server_id)
					{
						$args = "oid=".$order_id."&cuid=".$gash_billing->CUID;
						
						$this->load->library("game");
						$this->game->payment_transfer($gash_billing->uid, $gash_billing->server_id, $money, $user_billing->partner_order_id, $user_billing->character_id, $trans->nodes["COID"], $args, $gash_billing->id);
						go_payment_result(1, 1, $money, "", $args);
					}
					else {
						go_payment_result(1, 2, $money, "", $args);
					}										
				}		
				else go_payment_result(0, 0, $money, $this->gash_conf["RCODE"][$trans->nodes["PAY_RCODE"]]."(". $trans->nodes["PAY_RCODE"].")");	
			}		
			else go_payment_result(0, 0, $money, $this->gash_conf["RCODE"][$trans->nodes["RCODE"]]."(". $trans->nodes["RCODE"].")");
		}
		else go_payment_result(0, 0, 0, "交易驗證壓碼錯誤！");
	}
	
	//請款
	function settle($gash_billing_id, $country)
	{
		require_once dirname(__FILE__).'/../libraries/gash/Common.php';
		
		$gash_billing = $this->db->where("id", $gash_billing_id)->from("gash_billing")->get()->row();
		
		$trans = new Trans( null );			
		$trans->nodes = array(
			"MSG_TYPE" 		=> "0500", // 交易訊息代碼_ 0500: 請款服務請求Request
			"PCODE"			=> "300000", // 交易處理代碼_ 一般交易請使用 300000, 月租交易請使用 303000, 月租退租請使用 330000
			"CID"			=> $this->gash_conf[$country]["CID"], // 商家遊戲代碼
			"COID"			=> $gash_billing->COID, // 商家訂單編號
			"CUID"			=> $gash_billing->CUID, // 幣別 ISO Alpha Code
			"PAID"			=> $gash_billing->PAID, // 付款代收業者代碼
			"AMOUNT"		=> $gash_billing->AMOUNT, // 交易金額
			//"RRN" 			=> $gash_billing->RRN,
			//"ERPC" 			=> $gash_billing->ERPC,
			"ERQC" 			=> $gash_billing->ERQC,
		);
		
		//echo "<pre>請款1".print_r($trans, true)."</pre>";
		
		try {		
			// 設定請款服務位置
			$serviceURL = $this->gash_conf["url"]["settle"];
			
			log_message("error", "settle:".$trans->GetSendData());
			
			// 進行請款
			$client = new SoapClient($serviceURL);
			$result = $client->getResponse( array( "data" => $trans->GetSendData() ) );
			
			log_message("error", "settle_result:".$result->getResponseResult);
			
			// 解析回傳結果
			$trans = new Trans( $result->getResponseResult );
			
			//echo "<pre>請款2".print_r($trans, true)."</pre>";
			
			// 檢核 GPS 請款驗證壓碼
			if ( $trans->VerifyERPC($this->gash_conf[$country]["secret1"], $this->gash_conf[$country]["secret2"]) )
			{
				//echo "<pre>請款3".print_r($trans->nodes, true)."</pre>";
					
				$this->db->set("update_time", "NOW()", false)
					->where("COID", $trans->nodes["COID"])->update("gash_billing", array(
						"MSG_TYPE" 	=> $trans->nodes["MSG_TYPE"],
						"PCODE" 	=> $trans->nodes["PCODE"],
						"RCODE" 	=> $trans->nodes["RCODE"],
					));			
			
				if ( $trans->nodes["RCODE"] == "0000" ) 
				{	
					$this->db->where("COID", $trans->nodes["COID"])->update("gash_billing", array("status"=>"1"));
				}
				else go_payment_result(0, 0, 0, "請款失敗".$trans->nodes["RCODE"]);	
			}	
			else go_payment_result(0, 0, 0, "請款交易驗證壓碼錯誤！");		
		}
		catch ( Exception $ex ) {		
			go_payment_result(0, 0, 0, "請款發生錯誤！");			
		}
	}
	
	function check_order($coid="")
	{
		require_once dirname(__FILE__).'/../libraries/gash/Common.php';
		
		set_time_limit(60*10);
		if($coid) {
			$query = $this->db
					->where_in("COID", $coid)
					->from("gash_billing")->get();
		} else {
			$query = $this->db->where("create_time between date_sub(now(), interval 72 hour) and date_sub(now(), interval 3 minute)", null, false)
					->where("(PAY_STATUS='0' or PAY_STATUS='W' or RCODE='9998' or RCODE='9999' or RCODE is null)", null, false)
					->where_in("country", array("tw", "global"))
					->from("gash_billing")->get();
		}
				
		foreach($query->result() as $gash_billing) 
		{
			$trans = new Trans( null );

			$trans->nodes = array(
				"MSG_TYPE" 	=> "0100", // 交易訊息代碼
				"PCODE" 	=> "200000", // 交易處理代碼_ 查詢訂單請使用 200000
				"CID" 		=> $this->gash_conf[$gash_billing->country]["CID"], // 商家遊戲代碼
				"COID"		=> $gash_billing->COID, // 商家訂單編號
				"CUID"		=> $gash_billing->CUID, // 幣別 ISO Alpha Code
				"AMOUNT"	=> $gash_billing->AMOUNT, // 交易金額
				"ERQC" 		=> $gash_billing->ERQC,
			);

			try {
				// 設定查單服務位置
				$serviceURL = $this->gash_conf["url"]["checkorder"];
				
				log_message("error", "check_order:".$trans->GetSendData());
				
				// 進行查單
				$client = new SoapClient($serviceURL);
				$result =  $client->getResponse( array( "data" => $trans->GetSendData() ) );
				
				log_message("error", "check_order_result:".$result->getResponseResult);
				
				// 解析回傳結果
				$trans = new Trans( $result->getResponseResult );
				//echo "<pre>查單回傳".print_r($trans->nodes, true)."</pre>";
				
				//處理
				$d = my_curl(g_conf('url', 'payment')."gash/return_url?country={$gash_billing->country}", array("data"=>$trans->GetSendData()));
				print_r($d);				
				
				usleep(250000);
			}
			catch ( Exception $ex ) {
				go_payment_result(0, 0, 0, "查單發生錯誤！");			
			}			
		}
	}
	
	function gash_notice()
	{
		die('gash_notice');
	}
}

function go_payment_result($status, $transfer_status, $price, $message='', $args='') 
{
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