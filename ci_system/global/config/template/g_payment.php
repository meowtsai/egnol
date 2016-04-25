<?
	$gash_tw_url = base_url()."gash/order?country=tw";
	$gash_global_url = base_url()."gash/order?country=global";
	$pepay_url = base_url()."pepay/order";

    $config["payment_frontend_url"] = "https://game.longeplay.com.tw/";
    $config["payment_backend_ip"] = "203.66.111.6";
    
	$config["payment_options"] = array(
		"手機市話" => array(
			"亞太行動電話小額付費" => array(
				"maximum" => 3000, "minimum" => 0, "mobile" => 2,
				"trade" => array("paid"=>"TELSON04", "cuid"=>"TWD", "erp_id"=>"J990001", "convert_rate"=>"1", "action" => $gash_tw_url)
			),
			"中華電信839行動電話小額付費" => array(
				"maximum" => 2000, "minimum" => 30, "mobile" => 2,
				"trade" => array("paid"=>"TELCHT05", "cuid"=>"TWD", "erp_id"=>"J990001", "convert_rate"=>"1", "action" => $gash_tw_url)
			),
			"中華電信Hinet小額付費" => array(
				"maximum" => 2000, "minimum" => 30, "mobile" => 2,
				"trade" => array("paid"=>"TELCHT06", "cuid"=>"TWD", "erp_id"=>"J990001", "convert_rate"=>"1", "action" => $gash_tw_url)
			),
			"中華電信市內電話小額付費" => array(
				"maximum" => 2000, "minimum" => 30, "mobile" => 2,
				"trade" => array("paid"=>"TELCHT07", "cuid"=>"TWD", "erp_id"=>"J990001", "convert_rate"=>"1", "action" => $gash_tw_url)
			),
			"遠傳電信小額付費" => array(
				"maximum" => 3000, "minimum" => 30, "mobile" => 2,
				"trade" => array("paid"=>"TELFET01", "cuid"=>"TWD", "erp_id"=>"J990001", "convert_rate"=>"1", "action" => $gash_tw_url)
			),
			/*"台灣大哥大小額付費" => array(
				"maximum" => 3000, "minimum" => 0, "mobile" => 2,
				"trade" => array("pay_type"=>"TY-BILL", "subpay_type"=>"ST-MOBILE", "prod_id"=>"PD-BILL-TCC", "convert_rate"=>"1", "action" => $pepay_url)
			),*/
			"台灣大哥大小額付費" => array(
				"maximum" => 3000, "minimum" => 0, "mobile" => 2,
				"trade" => array("paid"=>"TELTCC01", "cuid"=>"TWD", "erp_id"=>"J990001", "convert_rate"=>"1", "action" => $gash_tw_url)
			),
			"威寶行動電話小額付費" => array(
				"maximum" => 3000, "minimum" => 0, "mobile" => 2,
				"trade" => array("paid"=>"TELVIBO", "cuid"=>"TWD", "erp_id"=>"J990001", "convert_rate"=>"1", "action" => $gash_tw_url)
			),
		),
		/*"手機市話" => array(
			"亞太行動電話小額付費" => array(
				"maximum" => 3000, "minimum" => 0, "mobile" => 2,
				"trade" => array("pay_type"=>"TY-BILL", "subpay_type"=>"ST-MOBILE", "prod_id"=>"PD-BILL-APBW", "convert_rate"=>"1", "action" => $pepay_url)
			),
			"中華電信839行動電話小額付費" => array(
				"maximum" => 3000, "minimum" => 0, "mobile" => 2,
				"trade" => array("pay_type"=>"TY-BILL", "subpay_type"=>"ST-MOBILE", "prod_id"=>"PD-BILL-CHTAAA-839", "convert_rate"=>"1", "action" => $pepay_url)
			),
			"中華電信Hinet小額付費" => array(
				"maximum" => 3000, "minimum" => 0, "mobile" => 2,
				"trade" => array("pay_type"=>"TY-BILL", "subpay_type"=>"ST-ISP", "prod_id"=>"PD-BILL-CHTAAA-HINET", "convert_rate"=>"1", "action" => $pepay_url)
			),
			"中華電信市內電話小額付費" => array(
				"maximum" => 3000, "minimum" => 0, "mobile" => 2,
				"trade" => array("pay_type"=>"TY-BILL", "subpay_type"=>"ST-LOCAL", "prod_id"=>"PD-BILL-CHTAAA-LOCAL", "convert_rate"=>"1", "action" => $pepay_url)
			),
			"遠傳電信小額付費" => array(
				"maximum" => 3000, "minimum" => 0, "mobile" => 2,
				"trade" => array("pay_type"=>"TY-BILL", "subpay_type"=>"ST-MOBILE", "prod_id"=>"PD-BILL-FET", "convert_rate"=>"1", "action" => $pepay_url)
			),
			"台灣大哥大小額付費" => array(
				"maximum" => 3000, "minimum" => 0, "mobile" => 2,
				"trade" => array("pay_type"=>"TY-BILL", "subpay_type"=>"ST-MOBILE", "prod_id"=>"PD-BILL-TCC", "convert_rate"=>"1", "action" => $pepay_url)
			),
			"威寶行動電話小額付費" => array(
				"maximum" => 3000, "minimum" => 0, "mobile" => 2,
				"trade" => array("pay_type"=>"TY-BILL", "subpay_type"=>"ST-MOBILE", "prod_id"=>"PD-BILL-VIBO", "convert_rate"=>"1", "action" => $pepay_url)
			),
		),*/
		"信用卡" => array(
			"中國信託" => array(
				"maximum" => 3000, "minimum" => 100, "mobile" => 2,
				"trade" => array("paid"=>"BNK82201", "cuid"=>"TWD", "erp_id"=>"J990001", "convert_rate"=>"1", "action" => $gash_tw_url)
			),
			"銀聯卡" => array(
				"maximum" => 3000, "minimum" => 100, "mobile" => 2,
				"trade" => array("paid"=>"BNK82204", "cuid"=>"TWD", "erp_id"=>"J990001", "convert_rate"=>"1", "action" => $gash_tw_url)
			),
			"全球信用卡(USD)" => array(
				"maximum" => 10000, "minimum" => 0, "mobile" => 2,
				"trade" => array("paid"=>"BNKRBS01", "cuid"=>"USD", "erp_id"=>"J990001", "convert_rate"=>"0.036", "action" => $gash_global_url)
			),
			"全球信用卡(EUR)" => array(
				"maximum" => 10000, "minimum" => 0, "mobile" => 2,
				"trade" => array("paid"=>"BNKRBS01", "cuid"=>"EUR", "erp_id"=>"J990001", "convert_rate"=>"0.028", "action" => $gash_global_url)
			),
		),
		"ATM" => array(
			"銀行/郵局ATM提款卡 Smart Pay" => array(
				"maximum" => 30000, "minimum" => 100, "mobile" => 2,
				"trade" => array("paid"=>"BNK80802", "cuid"=>"TWD", "erp_id"=>"J990001", "convert_rate"=>"1", "action" => $gash_tw_url)
			),
			"玉山銀行網路ATM" => array(
				"maximum" => 30000, "minimum" => 100, "mobile" => 2,
				"trade" => array("paid"=>"BNK80801", "cuid"=>"TWD", "erp_id"=>"J990001", "convert_rate"=>"1", "action" => $gash_tw_url)
			),
			"玉山銀行大额付款" => array(
				"maximum" => 100000, "minimum" => 100, "mobile" => 2,
				"trade" => array("paid"=>"BNK80803", "cuid"=>"TWD", "erp_id"=>"J990001", "convert_rate"=>"1", "action" => $gash_tw_url)
			),
		),
		"電子錢包" => array(
			"GASH會員遊戲帳號綁定" => array(
				"maximum" => 10000, "minimum" => 0, "mobile" => 2,
				"trade" => array("paid"=>"COPGAM09", "cuid"=>"TWD", "erp_id"=>"", "convert_rate"=>"1", "action" => $gash_tw_url)
			),
			"支付寶(TWD)" => array(
				"maximum" => 10000, "minimum" => 0, "mobile" => 2,
				"trade" => array("paid"=>"BNK80804", "cuid"=>"TWD", "erp_id"=>"J990001", "convert_rate"=>"1", "action" => $gash_tw_url)
			),
			"支付寶(USD)" => array(
				"maximum" => 10000, "minimum" => 0, "mobile" => 2,
				"trade" => array("paid"=>"COPALI02", "cuid"=>"USD", "erp_id"=>"J990001", "convert_rate"=>"1", "action" => $gash_global_url)
			),
			"PayPal(TWD)" => array(
				"maximum" => 10000, "minimum" => 0, "mobile" => 2,
				"trade" => array("paid"=>"COPPAL01", "cuid"=>"TWD", "erp_id"=>"J990001", "convert_rate"=>"1", "action" => $gash_tw_url)
			),
			"PayPal(USD)" => array(
				"maximum" => 10000, "minimum" => 0, "mobile" => 2,
				"trade" => array("paid"=>"COPPAL02", "cuid"=>"USD", "erp_id"=>"J990001", "convert_rate"=>"1", "action" => $gash_global_url)
			),
			"PayPal(EUR)" => array(
				"maximum" => 10000, "minimum" => 0, "mobile" => 2,
				"trade" => array("paid"=>"COPPAL02", "cuid"=>"EUR", "erp_id"=>"J990001", "convert_rate"=>"1", "action" => $gash_global_url)
			),
			"PayPal(HKD)" => array(
				"maximum" => 10000, "minimum" => 0, "mobile" => 2,
				"trade" => array("paid"=>"COPPAL02", "cuid"=>"HKD", "erp_id"=>"J990001", "convert_rate"=>"1", "action" => $gash_global_url)
			),
		),
		"點數卡" => array(
			"Gash+儲值卡" => array(
				"maximum" => 0, "minimum" => 0, "mobile" => 0,
				"trade" => array("paid"=>"COPGAM02", "cuid"=>"TWD", "erp_id"=>"PINHALL", "convert_rate"=>"1", "action" => $gash_tw_url)
			),
			"Gash+儲值卡(手機)" => array(
				"maximum" => 0, "minimum" => 0, "mobile" => 1,
				"trade" => array("paid"=>"COPGAM05", "cuid"=>"TWD", "erp_id"=>"PINHALL", "convert_rate"=>"1", "action" => $gash_tw_url)
			),
			/*"Gash+儲值卡(MYR)" => array(
				"maximum" => 0, "minimum" => 0, "mobile" => 0,
				"trade" => array("paid"=>"COPGAM02", "cuid"=>"MYR", "erp_id"=>"PINHALL", "convert_rate"=>"0.14", "action" => $gash_global_url)
			),
			"Gash+儲值卡(MYR手機)" => array(
				"maximum" => 0, "minimum" => 0, "mobile" => 1,
				"trade" => array("paid"=>"COPGAM05", "cuid"=>"MYR", "erp_id"=>"PINHALL", "convert_rate"=>"0.14", "action" => $gash_global_url)
			),
			"Indonesia untuk membayar" => array(
				"maximum" => 100, "minimum" => 0, "mobile" => 2,
				"trade" => array("paid"=>"COPGV01", "cuid"=>"IDR", "erp_id"=>"J990001", "convert_rate"=>"460", "action" => $gash_global_url)
			),
			"ธนาคารไทยการชำระเงิน" => array(
				"maximum" => 1000, "minimum" => 0, "mobile" => 2,
				"trade" => array("paid"=>"COPPSB01", "cuid"=>"THB", "erp_id"=>"J990001", "convert_rate"=>"1.5", "action" => $gash_global_url)
			),
			"Thanh Toán Việt Nam" => array(
				"maximum" => 100, "minimum" => 0, "mobile" => 2,
				"trade" => array("paid"=>"COPPST01", "cuid"=>"VND", "erp_id"=>"J990001", "convert_rate"=>"880", "action" => $gash_global_url)
			),
			"Malaysia Bank untuk Bayar" => array(
				"maximum" => 1000, "minimum" => 0, "mobile" => 2,
				"trade" => array("paid"=>"COPWBC02", "cuid"=>"MYR", "erp_id"=>"J990001", "convert_rate"=>"0.14", "action" => $gash_global_url)
			),*/
		),
		"ADSL" => array(
			"中華電信Hinet" => array(
				"maximum" => 1000, "minimum" => 0, "mobile" => 2,
				"trade" => array("paid"=>"TELCHT06", "cuid"=>"TWD", "erp_id"=>"J990001", "convert_rate"=>"1", "action" => $gash_tw_url)
			),
		),
	)
?>
