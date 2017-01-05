<?php

$funapp_url = base_url()."funapp/order";
$config["funapp_url"] = $funapp_url;

if (ENVIRONMENT == 'development') {
	$cash_domain = 'https://cash.funapp.com.tw';
	$api_domain = 'https://api.funapp.com.tw';
} else {
	$cash_domain = 'https://cash.funapp.com.tw';
	$api_domain = 'https://api.funapp.com.tw';
}

$config["funapp"] = array(
	"amount" => array("50", "150", "300", "350", "400", "450", "500", "1000", "1150", "2000", "3000", "5000"),
    "payment_type" => array(
    
    ),
	"cpId" => "",
	"secretKey" => "",
	"key1" => "",
	"key2" => "",
	"auth_url" => "{$cash_domain}/game-payweb/payment/payRequestWeb",
	"confirm_url" => "{$api_domain}/payment/queryOrder",
	"iap_id" => array(
		"50" => "XXXXXX00000050",
	),
);
