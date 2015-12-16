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
	"ResCode" => array(
		"31001" => "無此用戶",
		"31002" => "用戶認證失敗",
		"31003" => "額度不足",
		"31004" => "金額異常",
		"31005" => "錯誤次數過多",
		"31006" => "黑名單用戶",
		"31007" => "交易逾時",
		"31008" => "用戶無此服務權限",
		"31009" => "無此交易",
		"31010" => "用戶取消交易",
		"31011" => "用戶驗證失敗",
		"31012" => "用戶授權失敗",
		"31013" => "用戶操作過程異常",
		"31014" => "用戶OTP驗證錯誤",
		"31015" => "3D-Secure驗證失敗",
		"31016" => "用戶資料填寫有誤",
		"31017" => "此交易已啟用過",
		"31018" => "用戶已申請此服務",
		"31019" => "額度已達上限",
		"31020" => "儲值點數不一致",
		"31021" => "交易已鎖定",
		"31022" => "用戶小額付款狀態未啟用，請先在線上完成首次啟用認証，謝謝!",
		"31023" => "卡號異常",
		"31024" => "卡號已過期",
		"32001" => "金流端連線過程異常",
		"32002" => "金流端交易異常",
		"32003" => "金流端交易失敗",
		"32004" => "金流端系統忙碌",
		"32005" => "金流端系統錯誤",
		"32006" => "金流端系統停止服務",
		"32007" => "金流端資料庫連結失敗",
		"32008" => "金流端資料庫存取失敗",
		"32009" => "金流端連線過程資料異常",
		"32010" => "金流端連線過程逾時",
		"32011" => "金流端交易已存在",
		"32012" => "交易待確認，請留存資料備查",
		"32013" => "金流端無此服務項目",
		"33001" => "系統額度不足",
		"35001" => "異常資料傳送至PEPAY系統",
		"35002" => "資料傳送至PEPAY系統失敗",
		"35003" => "PEPAY系統資料庫連結失敗",
		"35004" => "PEPAY系統資料更新失敗",
		"35005" => "系統取消交易",
		"39000" => "交易失敗",
		"39999" => "異常錯誤"
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