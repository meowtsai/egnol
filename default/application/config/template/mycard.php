<?php

$config["mycard"] = array(
	'product_query_url' => 'https://b2b.mycard520.com.tw/MyCardBillingRESTSrv/MyCardBillingRESTSrv.svc/ProductsQuery/long_e',
	'payment_query_url' => 'https://b2b.mycard520.com.tw/MyCardBillingRESTSrv/MyCardBillingRESTSrv.svc/PaymentQuery/long_e', 
	'payments_query_url' => 'https://b2b.mycard520.com.tw/MyCardBillingRESTSrv/MyCardBillingRESTSrv.svc/PaymentsQuery/long_e/%s',
	
	'facId' => 'GFD00235',
	'key1' => 'mycard2008',
	'key2' => 'long_e2010',
	'auth_url' => 'https://b2b.mycard520.com.tw/MyCardIngameService/Auth',
	'confirm_url' => 'https://b2b.mycard520.com.tw/MyCardIngameService/Confirm',
	'mycard_ingame_url' => 'https://redeem.mycard520.com/',
		
	'payment_auth_url' => 'https://b2b.mycard520.com.tw/MyCardBillingRESTSrv/MyCardBillingRESTSrv.svc/Auth/%s/%s/%d', //ServiceId, TradeSeq, PaymentAmount
	'mycard_billing_url' => 'https://b2b.mycard520.com.tw/MyCardBilling?AuthCode=%s',
	'certifying_url' => 'https://b2b.mycard520.com.tw/MyCardBillingRESTSrv/MyCardBillingRESTSrv.svc/TradeQuery',
	'payment_confirm_url' => 'https://b2b.mycard520.com.tw/MyCardBillingRESTSrv/MyCardBillingRESTSrv.svc/PaymentConfirm',
		
	'test' => array(
		'product_query_url' => 'http://test.b2b.mycard520.com.tw/MyCardBillingRESTSrv/MyCardBillingRESTSrv.svc/ProductsQuery/long_e',
		'payment_query_url' => 'http://test.b2b.mycard520.com.tw/MyCardBillingRESTSrv/MyCardBillingRESTSrv.svc/PaymentQuery/long_e',
		'payments_query_url' => 'http://test.b2b.mycard520.com.tw/MyCardBillingRESTSrv/MyCardBillingRESTSrv.svc/PaymentsQuery/long_e/%s',
			
		'key1' => 'mycardlong_e',
		'key2' => 'long_e2012',						
		'auth_url' => 'http://test.b2b.mycard520.com.tw/MyCardIngameService/Auth',
		'confirm_url' => 'http://test.b2b.mycard520.com.tw/MyCardIngameService/Confirm',
		'mycard_ingame_url' => 'http://test.mycard520.com.tw/MyCardIngame/',
		
		'payment_auth_url' => 'http://test.b2b.mycard520.com.tw/MyCardBillingRESTSrv/MyCardBillingRESTSrv.svc/Auth/%s/%s/%d', //ServiceId, TradeSeq, PaymentAmount
		'mycard_billing_url' => 'http://test.mycard520.com.tw/MyCardBilling?AuthCode=%s',			
		'certifying_url' => 'http://test.b2b.mycard520.com.tw/MyCardBillingRESTSrv/MyCardBillingRESTSrv.svc/TradeQuery',
		'payment_confirm_url' => 'http://test.b2b.mycard520.com.tw/MyCardBillingRESTSrv/MyCardBillingRESTSrv.svc/PaymentConfirm',
	),
);
	