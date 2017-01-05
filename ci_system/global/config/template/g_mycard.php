<?php

$mycard_url = base_url()."mycard/order";
$config["mycard_url"] = $mycard_url;

if (ENVIRONMENT == 'development') {
	$b2b_domain = 'http://test.b2b.mycard520.com.tw';
} else $b2b_domain = 'https://b2b.mycard520.com.tw';
if (ENVIRONMENT == 'development') {
	$domain = 'http://test.mycard520.com.tw';
} else $domain = 'https://www.mycard520.com.tw';

$config["mycard"] = array(
	"amount" => array("50", "150", "300", "350", "400", "450", "500", "1000", "1150", "2000", "3000", "5000"),
    "payment_type" => array(
    
    ),
	
	"facId" => "",
	"hash_key" => "",
	"key1" => "",
	"key2" => "",
	"key1_new" => (ENVIRONMENT == 'development' ? "mycardcooz" : "mycard2008"),
	"key2_new" => (ENVIRONMENT == 'development' ? "cooz2012" : "cooz2010"),
	"auth_url" => "{$b2b_domain}/MyBillingPay/api/AuthGlobal",
	"pay_url" => "{$domain}/MyCardPay/",
	"confirm_url" => "{$b2b_domain}/MyBillingPay/api/TradeQuery",
	"billing_url" => "{$b2b_domain}/MyBillingPay/api/PaymentConfirm"
);
