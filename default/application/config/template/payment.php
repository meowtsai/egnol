<?
	$gash_tw_url = base_url()."gash/order?country=tw";
	$gash_global_url = base_url()."gash/order?country=global";
	$pepay_url = base_url()."pepay/order";

	$config["payment_options"] = array(
		"手機市話" => array(
			"亞太行動電話小額付費" => array(
				"maximum" => 3000, "minimum" => 0,
				"trade" => array("pay_type"=>"TY-BILL", "subpay_type"=>"ST-MOBILE", "prod_id"=>"PD-BILL-APBW", "convert_rate"=>"1", "action" => $pepay_url)
			),
			"中華電信839行動電話小額付費" => array(
				"maximum" => 3000, "minimum" => 0,
				"trade" => array("pay_type"=>"TY-BILL", "subpay_type"=>"ST-MOBILE", "prod_id"=>"PD-BILL-CHTAAA-839", "convert_rate"=>"1", "action" => $pepay_url)
			),
			"中華電信Hinet小額付費" => array(
				"maximum" => 3000, "minimum" => 0,
				"trade" => array("pay_type"=>"TY-BILL", "subpay_type"=>"ST-ISP", "prod_id"=>"PD-BILL-CHTAAA-HINET", "convert_rate"=>"1", "action" => $pepay_url)
			),
			"中華電信市內電話小額付費" => array(
				"maximum" => 3000, "minimum" => 0,
				"trade" => array("pay_type"=>"TY-BILL", "subpay_type"=>"ST-LOCAL", "prod_id"=>"PD-BILL-CHTAAA-LOCAL", "convert_rate"=>"1", "action" => $pepay_url)
			),
			"遠傳電信小額付費" => array(
				"maximum" => 3000, "minimum" => 0,
				"trade" => array("pay_type"=>"TY-BILL", "subpay_type"=>"ST-MOBILE", "prod_id"=>"PD-BILL-FET", "convert_rate"=>"1", "action" => $pepay_url)
			),
			"台灣大哥大小額付費" => array(
				"maximum" => 3000, "minimum" => 0,
				"trade" => array("pay_type"=>"TY-BILL", "subpay_type"=>"ST-MOBILE", "prod_id"=>"PD-BILL-TCC", "convert_rate"=>"1", "action" => $pepay_url)
			),
			"威寶行動電話小額付費" => array(
				"maximum" => 3000, "minimum" => 0,
				"trade" => array("pay_type"=>"TY-BILL", "subpay_type"=>"ST-MOBILE", "prod_id"=>"PD-BILL-VIBO", "convert_rate"=>"1", "action" => $pepay_url)
			),
		),
		/*"信用卡" => array(
			"國內信用卡1" => array(
				"maximum" => 10000, "minimum" => 0,
				"trade" => array("pay_type"=>"TY-CREDIT", "subpay_type"=>"", "prod_id"=>"PD-CREDIT-TSCB", "convert_rate"=>"1", "action" => $pepay_url)
			),
			"國內信用卡2" => array(
				"maximum" => 3000, "minimum" => 0,
				"trade" => array("paid"=>"BNK82201", "cuid"=>"TWD", "erp_id"=>"J990001", "convert_rate"=>"1", "action" => $gash_tw_url)
			),
			"全球信用卡(美金)" => array(
				"maximum" => 10000, "minimum" => 0,
				"trade" => array("paid"=>"BNKRBS01", "cuid"=>"USD", "erp_id"=>"J990001", "convert_rate"=>"0.036", "action" => $gash_global_url)
			),
			"全球信用卡(歐元)" => array(
				"maximum" => 10000, "minimum" => 0,
				"trade" => array("paid"=>"BNKRBS01", "cuid"=>"EUR", "erp_id"=>"J990001", "convert_rate"=>"0.028", "action" => $gash_global_url)
			),
		),
		"ATM" => array(
			"網路ATM1" => array(
				"maximum" => 20000, "minimum" => 1000,
				"trade" => array("pay_type"=>"TY-ATM", "subpay_type"=>"ST-WEB", "prod_id"=>"PD-WEBATM-ESUN", "convert_rate"=>"1", "action" => $pepay_url)
			),
			"網路ATM2" => array(
				"maximum" => 20000, "minimum" => 0,
				"trade" => array("paid"=>"BNK80802", "cuid"=>"TWD", "erp_id"=>"J990001", "convert_rate"=>"1", "action" => $gash_tw_url)
			),
			"臨櫃ATM1" => array(
				"maximum" => 20000, "minimum" => 1000,
				"trade" => array("pay_type"=>"TY-ATM", "subpay_type"=>"ST-ATM", "prod_id"=>"PD-ATM-CTCB", "convert_rate"=>"1", "action" => $pepay_url)
			),
			"臨櫃ATM2" => array(
				"maximum" => 20000, "minimum" => 0,
				"trade" => array("paid"=>"BNK80803", "cuid"=>"TWD", "erp_id"=>"J990001", "convert_rate"=>"1", "action" => $gash_tw_url)
			),
		),
		"電子錢包" => array(
			"支付寶" => array(
				"maximum" => 10000, "minimum" => 0,
				"trade" => array("paid"=>"BNK80804", "cuid"=>"TWD", "erp_id"=>"J990001", "convert_rate"=>"1", "action" => $gash_tw_url)
			),
			"PayPal" => array(
				"maximum" => 10000, "minimum" => 0,
				"trade" => array("paid"=>"COPPAL01", "cuid"=>"TWD", "erp_id"=>"J990001", "convert_rate"=>"1", "action" => $gash_tw_url)
			),
		),*/
		"點數卡" => array(
			"Gash+實體卡" => array(
				"maximum" => 0, "minimum" => 0,
				"trade" => array("paid"=>"COPGAM02", "cuid"=>"TWD", "erp_id"=>"PINHALL", "convert_rate"=>"1", "action" => $gash_tw_url)
			),
			"Gash+儲值卡(菲國比索)" => array(
				"maximum" => 0, "minimum" => 0,
				"trade" => array("paid"=>"COPGAM02", "cuid"=>"PHP", "erp_id"=>"PINHALL", "convert_rate"=>"1.7", "action" => $gash_global_url)
			),
			"Gash+儲值卡(馬來西亞令吉)" => array(
				"maximum" => 0, "minimum" => 0,
				"trade" => array("paid"=>"COPGAM02", "cuid"=>"MYR", "erp_id"=>"PINHALL", "convert_rate"=>"0.14", "action" => $gash_global_url)
			),
/*
			"Indonesia untuk membayar(印尼盾)" => array(
				"maximum" => 100, "minimum" => 0,
				"trade" => array("paid"=>"COPGV01", "cuid"=>"IDR", "erp_id"=>"J990001", "convert_rate"=>"460", "action" => $gash_global_url)
			),
			"Pilipinas upang bayaran(菲國比索)" => array(
				"maximum" => 1000, "minimum" => 0,
				"trade" => array("paid"=>"COPMOZ01", "cuid"=>"PHP", "erp_id"=>"J990001", "convert_rate"=>"1.7", "action" => $gash_global_url)
			),
			"????????????????????(泰銖)" => array(
				"maximum" => 1000, "minimum" => 0,
				"trade" => array("paid"=>"COPPSB01", "cuid"=>"THB", "erp_id"=>"J990001", "convert_rate"=>"1.5", "action" => $gash_global_url)
			),
			"Thanh Toan Vi?t Nam(越南幣)" => array(
				"maximum" => 100, "minimum" => 0,
				"trade" => array("paid"=>"COPPST01", "cuid"=>"THB", "erp_id"=>"J990001", "convert_rate"=>"880", "action" => $gash_global_url)
			),
			"Malaysia Bank untuk Bayar(馬來西亞令吉)" => array(
				"maximum" => 1000, "minimum" => 0,
				"trade" => array("paid"=>"COPWBC02", "cuid"=>"MYR", "erp_id"=>"J990001", "convert_rate"=>"0.14", "action" => $gash_global_url)
			),
			"Malaysia Bank untuk Bayar(馬來西亞令吉)" => array(
				"maximum" => 1000, "minimum" => 0,
				"trade" => array("paid"=>"TELDANAL01", "cuid"=>"KRW", "erp_id"=>"J990001", "convert_rate"=>"44", "action" => $gash_global_url)
			),
*/
		),
		/*"ADSL" => array(
			"中華電信Hinet" => array(
				"maximum" => 1000, "minimum" => 0,
				"trade" => array("paid"=>"TELCHT06", "cuid"=>"TWD", "erp_id"=>"J990001", "convert_rate"=>"1", "action" => $gash_tw_url)
			),
		),*/
	)
?>