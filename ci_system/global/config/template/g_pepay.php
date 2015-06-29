<?php

$config["pepay"] = array(
	"amount" => array("100", "300", "500", "1000", "3000", "5000", "10000", "20000", "30000"),
	"m_amount" => array("60", "150", "300", "660", "790", "1490", "3590", "5990", "9990", "14900", "29900"),			
	"ShopID" => "PPS_149055",
	"SysTrustCode" => "b3ATduvt7n", //系統信任碼
	"ShopTrustCode" => "dzbq99tkAc",	//廠商信任碼
	"PaySelectUrl" => "https://gate.pepay.com.tw/pepay/paysel_amt.php",
	"Prod_ids" => array(
		"PD-BILL-APBW" => "亞太行動電話小額付費",
		"PD-BILL-CHTAAA-839" => "中華電信行動電話小額付費",
		"PD-BILL-CHTAAA-HINET" => "中華電信Hinet小額付費",
		"PD-BILL-CHTAAA-LOCAL" => "中華電信市內電話小額付費",
		"PD-BILL-FET" => "遠傳電信小額付費",
		"PD-BILL-SONET" => "So-Net ADSL小額付費",
		"PD-BILL-TCC" => "台灣大哥大小額付費",
		"PD-BILL-VIBO" => "威寶行動電話小額付費",
		"PD-CREDIT-ALLPAY" => "歐付寶信用卡",
		"PD-CREDIT-CTCB" => "中國信託信用卡",
		"PD-CREDIT-HNCB" => "華南銀行線上信用卡",
		"PD-CREDIT-REDSUN" => "紅陽信用卡",
		"PD-CREDIT-TSCB" => "台新銀行信用卡",
		"PD-CREDIT-TSCB-AMS" => "台新信用卡(月繳制)",
		"PD-ATM-CTCB" => "中國信託實體ATM",
		"PD-ATM-SCSB" => "上海商銀即時ATM",
		"PD-WEBATM-CTCB" => "中國信託WEB-ATM",
		"PD-WEBATM-ESUN" => "玉山銀行WEB-ATM",
		"PD-STORE-HILIFEET" => "萊爾富Life-ET",
		"PD-STORE-OKGO" => "OK超商OK-go",
		"PD-CREDIT-CHINAPAY-TWD" => "銀聯信用卡及金融卡代收(台幣計算)",
		"PD-EPOINT-ESUN-ALIPAY" => "支付通",
	),
);

if (ENVIRONMENT == 'development') {
	$config["gash"]["url"] = array(
			"order" => "https://stage-api.eg.gashplus.com/CP_Module/order.aspx",
			"settle" => "https://stage-api.eg.gashplus.com/CP_Module/settle.asmx?WSDL",
			"checkorder" => "https://stage-api.eg.gashplus.com/CP_Module/checkorder.asmx?WSDL",
	);
	$config["gash"]["global"]["MID"] = "M1000190";
	$config["gash"]["global"]["CID"] = "C001900000304";
	$config["gash"]["global"]["key"] = "avs45093yh98bw43jq";
	$config["gash"]["global"]["secret1"] = "ZmMB4SaOwtQOdQNyuPrgGgGr9wqIrKBa";
	$config["gash"]["global"]["secret2"] = "WLJc9+TYk9g=";	
	$config["gash"]["tw"]["MID"] = "M1000286";
	$config["gash"]["tw"]["CID"] = "C002860000460";
	$config["gash"]["tw"]["key"] = "SAHJSPO;ahnAPO";
	$config["gash"]["tw"]["secret1"] = "UitFhM7lIrCdXDaUVPefW/kxEjCNzBz1";
	$config["gash"]["tw"]["secret2"] = "x7M+YBakKpM=";
}