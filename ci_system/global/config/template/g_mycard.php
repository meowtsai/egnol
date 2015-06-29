<?php

if (ENVIRONMENT == 'development') {
	$domain = 'http://test.b2b.mycard520.com.tw';
} else $domain = 'https://b2b.mycard520.com.tw';

$config["mycard"] = array(
	"product_query_url" => "{$domain}/MyCardBillingRESTSrv/MyCardBillingRESTSrv.svc/ProductsQuery/long_e",
	"payment_query_url" => "{$domain}/MyCardBillingRESTSrv/MyCardBillingRESTSrv.svc/PaymentQuery/long_e", 
	"payments_query_url" => "{$domain}/MyCardBillingRESTSrv/MyCardBillingRESTSrv.svc/PaymentsQuery/long_e/%s",
	
	"facId" => "GFD00235",
	"key1" => "mycard2008",
	"key2" => "long_e2010",
	"key1_new" => (ENVIRONMENT == 'development' ? "mycardlong_e" : "mycard2008"),
	"key2_new" => (ENVIRONMENT == 'development' ? "long_e2012" : "long_e2010"),
	"auth_url" => "{$domain}/MyCardIngameService/Auth",
	"confirm_url" => "{$domain}/MyCardIngameService/Confirm",
	"mycard_ingame_url" => (ENVIRONMENT == 'development' ? "http://test.mycard520.com.tw/MyCardIngame/" : "https://redeem.mycard520.com/"),
			
	"payment_auth_url" => "{$domain}/MyCardBillingRESTSrv/MyCardBillingRESTSrv.svc/Auth/%s/%s/%d", //ServiceId, TradeSeq, PaymentAmount
	"mycard_billing_url" => (ENVIRONMENT == 'development' ? "http://test.mycard520.com.tw/MyCardBilling?AuthCode=%s" : "http://www.mycard520.com.tw/MyCardBilling?AuthCode=%s"),
	"certifying_url" => "{$domain}/MyCardBillingRESTSrv/MyCardBillingRESTSrv.svc/TradeQuery",
	"payment_confirm_url" => "{$domain}/MyCardBillingRESTSrv/MyCardBillingRESTSrv.svc/PaymentConfirm",
	
	"check_trade_status" => "{$domain}/MyCardIngameService/CheckTradeStatus?facId=%s&facTradeSeq=%s&hash=%s",
	"single_trade_query" => "{$domain}/MyCardBillingRESTSrv/MyCardBillingRESTSrv.svc/SingleTradeQuery?AuthCode=%s",
	"billing_query_data" => "{$domain}/MyCardBIllingQueryDataWebService/BillingQueryData.asmx?WSDL",
);
	
$config["mycard_channel"] = array(		
	'SW' => '點數卡',
	'MFF' => '測試金流',
	//--
	'MSN' => '速博數位(SeedNet)',
	'MSO' => 'So-Net',
	'MSH' => '上海銀行WebATM',
	'MLD' => '土地銀行WebATM',
	'7963' => '中國信託WebATM',
	'89021' => '中華郵政WebATM',
	'MHI' => '中華電信839',
	'MHA' => '中華電信HiNet',
	'MHE' => '中華電信市內電話輕鬆付',
	'MFB' => '台北富邦WebATM',
	'MTC' => '台灣大哥大電信',
	'MTS' => '台新銀行WebATM',
	'MAQ' => '台灣銀行WebATM',
	'MWA' => '玉山銀行WebATM',
	'MEG' => '兆豐銀行WebATM',
	'MNW' => '台灣地區信用卡付款',
	'MAP' => '亞太電信',
	'MVB' => '威寶電信',
	'MCA' => '國泰世華 MyATM',
	'MDE' => '第一銀行WebATM',
	'MHB' => '華南銀行WebATM',		
	'MCH' => '彰化銀行WebATM',		
	'MIS' => '遠傳和信電信',		
	'MCT' => '台灣地區信用卡付款(3D驗證)',		
	'MSK' => '新光銀行WebATM',		
	'PA' => 'PayPal',		
	'MAL' => '支付寶(Alipay)',		
	'MEA' => '支付寶AliPay',		
	'MTB' => '合作金庫WebATM',
	'MCN' => '中國信託實體ATM',
	'MCB' => '中信紅利兌換',
	'MOM' => 'Omypay',
	'MBL' => '快錢',
	'MUP' => '銀聯在線支付',
	'MFE' => '遠傳電信手機版',
);	