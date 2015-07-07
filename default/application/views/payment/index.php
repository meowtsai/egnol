<? 
	$gash_conf = $this->config->item("gash");
	
	$gash_tw_url = "/gash/order?country=tw";
	$gash_global_url = "/gash/order?country=global";
	$pepay_url = "/pepay/order";

	$options = array(
		"ATM" => array(
			"網路ATM1" => array(
				"maximum" => 30000, "minimum" => 1000,	
				"trade" => array("pay_type"=>"TY-ATM", "subpay_type"=>"ST-WEB", "prod_id"=>"PD-WEBATM-CTCB", "convert_rate"=>"1", "action" => $pepay_url)
				//PD-WEBATM-ESUN 玉山
				//PD-WEBATM-CTCB 中國						
			),				
			"網路ATM2" => array(
				"maximum" => 10000, "minimum" => 0,	
				"trade" => array("paid"=>"BNK80801", "cuid"=>"TWD", "erp_id"=>"J990001", "convert_rate"=>"1", "action" => $gash_tw_url)						
			),
			"臨櫃ATM1" => array(
				"maximum" => 30000, "minimum" => 1000,
				"trade" => array("pay_type"=>"TY-ATM", "subpay_type"=>"ST-ATM", "prod_id"=>"PD-ATM-CTCB", "convert_rate"=>"1", "action" => $pepay_url)
				//PD-ATM-CTCB 中國
				//PD-ATM-SCSB 上海商銀
			),		
			"臨櫃ATM2" => array(
				"maximum" => 10000, "minimum" => 0,
				"trade" => array("paid"=>"BNK80803", "cuid"=>"TWD", "erp_id"=>"J990001", "convert_rate"=>"1", "action" => $gash_tw_url)						
			),	
		),
		"信用卡" => array(
			"國內信用卡1" => array(
				"maximum" => 10000, "minimum" => 0,	
				"trade" => array("pay_type"=>"TY-CREDIT", "subpay_type"=>"", "prod_id"=>"PD-CREDIT-TSCB", "convert_rate"=>"1", "action" => $pepay_url)
				//PD-CREDIT-CTCB 中信
				//PD-CREDIT-TSCB 台新
				//PD-CREDIT-REDSUN 紅陽
			),					
			"國內信用卡2" => array(
				"maximum" => 3000, "minimum" => 0,	
				"trade" => array("paid"=>"BNK82201", "cuid"=>"TWD", "erp_id"=>"J990001", "convert_rate"=>"1", "action" => $gash_tw_url)						
			),	
		),
		"固網支付" => array(
			"中華電信Hinet" => array(
				"maximum" => 1000, "minimum" => 0,	
				"trade" => array("paid"=>"TELCHT06", "cuid"=>"TWD", "erp_id"=>"J990001", "convert_rate"=>"1", "action" => $gash_tw_url)						
			),
			"中華電信市話" => array(
				"maximum" => 3000, "minimum" => 0,	
				"trade" => array("paid"=>"TELCHT07", "cuid"=>"TWD", "erp_id"=>"J990001", "convert_rate"=>"1", "action" => $gash_tw_url)						
			),		
				
// 			"中華電信Hinet" => array(
// 				"maximum" => 1000, "minimum" => 0,	
// 				"trade" => array("pay_type"=>"TY-BILL", "subpay_type"=>"ST-ISP", "prod_id"=>"PD-BILL-CHTAAA-HINET", "convert_rate"=>"1", "action" => $pepay_url)					
// 			),	
// 			"中華電信市話" => array(
// 				"maximum" => 3000, "minimum" => 0,	
// 				"trade" => array("pay_type"=>"TY-BILL", "subpay_type"=>"ST-LOCAL", "prod_id"=>"PD-BILL-CHTAAA-LOCAL", "convert_rate"=>"1", "action" => $pepay_url)					
// 			),					
		),
		"手機支付" => array(
/*			"中華電信839" => array(
				"maximum" => 3000, "minimum" => 0,	
				"trade" => array("paid"=>"TELCHT05", "cuid"=>"TWD", "erp_id"=>"J990001", "convert_rate"=>"1", "action" => $gash_tw_url)						
			),	
			"台灣大哥大" => array(
				"maximum" => 3000, "minimum" => 0,	
				"trade" => array("paid"=>"TELTCC01", "cuid"=>"TWD", "erp_id"=>"J990001", "convert_rate"=>"1", "action" => $gash_tw_url)						
			),	
			"遠傳電信" => array(
				"maximum" => 3000, "minimum" => 0,	
				"trade" => array("paid"=>"TELFET01", "cuid"=>"TWD", "erp_id"=>"J990001", "convert_rate"=>"1", "action" => $gash_tw_url)						
			),	
			"亞太電信" => array(
				"maximum" => 1000, "minimum" => 0,	
				"trade" => array("paid"=>"TELSON04", "cuid"=>"TWD", "erp_id"=>"J990001", "convert_rate"=>"1", "action" => $gash_tw_url)						
			),*/
				
 			"中華電信839" => array(
 				"maximum" => 3000, "minimum" => 0,	
 				"trade" => array("pay_type"=>"TY-BILL", "subpay_type"=>"ST-MOBILE", "prod_id"=>"PD-BILL-CHTAAA-839", "convert_rate"=>"1", "action" => $pepay_url)					
 			),	
 			"中華電信Hinet" => array(
 				"maximum" => 3000, "minimum" => 0,	
 				"trade" => array("pay_type"=>"TY-BILL", "subpay_type"=>"ST-ISP", "prod_id"=>"PD-BILL-CHTAAA-HINET", "convert_rate"=>"1", "action" => $pepay_url)					
 			),
 			"中華電信市內電話" => array(
 				"maximum" => 3000, "minimum" => 0,	
 				"trade" => array("pay_type"=>"TY-BILL", "subpay_type"=>"ST-LOCAL", "prod_id"=>"PD-BILL-CHTAAA-LOCAL", "convert_rate"=>"1", "action" => $pepay_url)					
 			),
 			"台灣大哥大" => array(
 				"maximum" => 3000, "minimum" => 0,	
 				"trade" => array("pay_type"=>"TY-BILL", "subpay_type"=>"ST-MOBILE", "prod_id"=>"PD-BILL-TCC", "convert_rate"=>"1", "action" => $pepay_url)					
 			),	
 			"遠傳電信" => array(
 				"maximum" => 3000, "minimum" => 0,	
 				"trade" => array("pay_type"=>"TY-BILL", "subpay_type"=>"ST-MOBILE", "prod_id"=>"PD-BILL-FET", "convert_rate"=>"1", "action" => $pepay_url)					
 			),	
 			"亞太電信" => array(
 				"maximum" => 1000, "minimum" => 0,	
 				"trade" => array("pay_type"=>"TY-BILL", "subpay_type"=>"ST-MOBILE", "prod_id"=>"PD-BILL-APBW", "convert_rate"=>"1", "action" => $pepay_url)					
 			),	
 			"威寶行動電話" => array(
 				"maximum" => 1000, "minimum" => 0,	
 				"trade" => array("pay_type"=>"TY-BILL", "subpay_type"=>"ST-MOBILE", "prod_id"=>"PD-BILL-VIBO", "convert_rate"=>"1", "action" => $pepay_url)					
 			),	
		),
		"支付寶" => array(
			"maximum" => 10000, "minimum" => 0,	
			"trade" => array("paid"=>"BNK80804", "cuid"=>"TWD", "erp_id"=>"J990001", "convert_rate"=>"1", "action" => $gash_tw_url)	
		),
		"支付寶" => array(
			"maximum" => 10000, "minimum" => 0,	
			"trade" => array("pay_type"=>"PAY_TYPE=TY-CHINA", "subpay_type"=>"", "prod_id"=>"PD-EPOINT-ESUN-ALIPAY", "convert_rate"=>"1", "action" => $pepay_url)					
		),				
		"PayPal" => array(
			"maximum" => 10000, "minimum" => 0,
			"trade" => array("paid"=>"COPPAL01", "cuid"=>"TWD", "erp_id"=>"J990001", "convert_rate"=>"1", "action" => $gash_tw_url)	
		),
		"Gash儲值卡" => array(
			"台灣" => array(
				"maximum" => 0, "minimum" => 0,
				"trade" => array("paid"=>"COPGAM02", "cuid"=>"TWD", "erp_id"=>"PINHALL", "convert_rate"=>"1", "action" => $gash_tw_url)
			),
// 			"香港" => array(
// 				"maximum" => 0, "minimum" => 0,	
// 				"trade" => array("paid"=>"COPGAM02", "cuid"=>"HKD", "erp_id"=>"PINHALL", "convert_rate"=>"0.25", "action" => $gash_global_url)						
// 			),
// 			"菲律賓" => array(
// 				"maximum" => 0, "minimum" => 0,	
// 				"trade" => array("paid"=>"COPGAM02", "cuid"=>"PHP", "erp_id"=>"PINHALL", "convert_rate"=>"1.7", "action" => $gash_global_url)						
// 			),
// 			"馬來西亞" => array(
// 				"maximum" => 0, "minimum" => 0,	
// 				"trade" => array("paid"=>"COPGAM02", "cuid"=>"MYR", "erp_id"=>"PINHALL", "convert_rate"=>"0.14", "action" => $gash_global_url)						
// 			),
			"海外(香港、菲律賓、馬來西亞)" => array(
				"maximum" => 0, "minimum" => 0,	
				"trade" => array("paid"=>"COPGAM02", "cuid"=>"MYR", "erp_id"=>"PINHALL", "action" => $gash_global_url)						
			),
		),
		"其它國家" => array(
// 			"全球信用卡(美金)" => array(
// 				"maximum" => 10000, "minimum" => 0,	
// 				"trade" => array("paid"=>"BNKRBS01", "cuid"=>"USD", "erp_id"=>"J990001", "convert_rate"=>"0.036", "action" => $gash_global_url)						
// 			),
			"全球信用卡(台幣)" => array(
				"maximum" => 10000, "minimum" => 0,	
				"trade" => array("pay_type"=>"TY-CREDIT", "subpay_type"=>"", "prod_id"=>"PD-CREDIT-TSCB", "convert_rate"=>"1", "action" => $pepay_url)
			),			
			"全球信用卡(歐元)" => array(
				"maximum" => 10000, "minimum" => 0,	
				"trade" => array("paid"=>"BNKRBS01", "cuid"=>"EUR", "erp_id"=>"J990001", "convert_rate"=>"0.028", "action" => $gash_global_url)						
			),	
			"Indonesia untuk membayar(印尼盾)" => array(
				"maximum" => 100, "minimum" => 0,	
				"trade" => array("paid"=>"COPGV01", "cuid"=>"IDR", "erp_id"=>"J990001", "convert_rate"=>"460", "action" => $gash_global_url)						
			),
			"Pilipinas upang bayaran(菲國比索)" => array(
				"maximum" => 1000, "minimum" => 0,	
				"trade" => array("paid"=>"COPMOZ01", "cuid"=>"PHP", "erp_id"=>"J990001", "convert_rate"=>"1.7", "action" => $gash_global_url)						
			),
			"ธนาคารไทยการชำระเงิน(泰銖)" => array(
				"maximum" => 1000, "minimum" => 0,	
				"trade" => array("paid"=>"COPPSB01", "cuid"=>"THB", "erp_id"=>"J990001", "convert_rate"=>"1.5", "action" => $gash_global_url)						
			),
			"Thanh Toán Việt Nam(越南幣)" => array(
				"maximum" => 100, "minimum" => 0,	
				"trade" => array("paid"=>"COPPST01", "cuid"=>"VND", "erp_id"=>"J990001", "convert_rate"=>"880", "action" => $gash_global_url)						
			),
			"Malaysia Bank untuk Bayar(馬來西亞令吉)" => array(
				"maximum" => 1000, "minimum" => 0,	
				"trade" => array("paid"=>"COPWBC02", "cuid"=>"MYR", "erp_id"=>"J990001", "convert_rate"=>"0.14", "action" => $gash_global_url)						
			),
			"한국 통신 결제(韓元)" => array(
				"maximum" => 1000, "minimum" => 0,	
				"trade" => array("paid"=>"TELDANAL01", "cuid"=>"KRW", "erp_id"=>"J990001", "convert_rate"=>"44", "action" => $gash_global_url)						
			),
		),
		"GASH+會員帳戶支付" => array(
			"maximum" => 10000, "minimum" => 0,
			"trade" => array("paid"=>"COPGAM08", "cuid"=>"TWD", "erp_id"=>"", "convert_rate"=>"1", "action" => $gash_tw_url)	
		),			
	)
?>			
<style type="text/css">
.leftside {position:absolute;  left:9px; top:67px; }
.leftside li {width:157px; margin-bottom:1px;}
.leftside li a {display:block; padding:14px 8px; background:#aeaeae; line-height:22px;}
.leftside li a:hover {background:#ececec}
.leftside li a.active {background:#ccc}
.content {margin:0 30px 0 190px;}
.content li {margin-bottom:5px;}
.content label {color:#a63939}
.field {width:150px; text-align:right; padding:0 9px 0 0; display:inline-block; vertical-align:top;}
</style>

<script type="text/javascript">
var gash_amount = ['<?= implode("','", $gash_conf["amount"])?>'];
</script>

<div style="background:url('/p/img/payment/bg.png') repeat-y">
	<div style="background:url('/p/img/payment/title.png') no-repeat; width:697px; height:64px; margin-top:-2px;">
		<div style="padding:21px 0 0 190px; font-weight:bold;">您目前的選擇儲值方式是 
			<span id="choose_title" style="color:red;">ATM</span>
		</div>
	</div>

	<div class="leftside">
		<ul>
			<? foreach($options as $tab => $arr):
				if (array_key_exists("trade", $arr)):	
					$attr_str = '';
					foreach($arr['trade'] as $attr => $val) $attr_str .= " {$attr}='{$val}'"; 
			?>
			<li><a href="javascript:;" onclick="switch_pay_type()" class="gash_option" maximum="<?=$arr['maximum']?>" minimum="<?=$arr['minimum']?>" <?=$attr_str?>><?=$tab?></a></li>
			<? else:?>
			<li><a href="javascript:;" onclick="switch_pay_type('<?=$tab?>')"><?=$tab?></a></li>
			<? 
				endif;
			endforeach;?>
		</ul>
	</div>
	
	
	<div class="content">
	
	<form id="choose_form" class="choose_form" method="post" action="" target="_blank" >
		<input type="hidden" name="PAID">
		<input type="hidden" name="CUID">
		<input type="hidden" name="ERP_ID">
		
		<input type="hidden" name="pay_type">
		<input type="hidden" name="subpay_type">
		<input type="hidden" name="prod_id">
		
	  <ul style="min-height:300px;">
	  	<li class="line_row">
			<span class="field">儲值幣別</span>
			<span class="line_field">
				<label><input type="radio" name="type" value="game" checked="checked">遊戲幣</label>
				<label><input type="radio" name="type" value="long_e">龍邑平台點數</label>
			</span>
		</li>
		<li class="game_option line_row">
			<span class="field">請選擇儲值的遊戲</span>
			<span class="line_field">
				<select name="game" class="required">
					<option value="">--請選擇--</option>
					<? foreach($games->result() as $row):
						if (strpos($row->tags, "手遊") !== false) continue;
					?>
					<option value="<?=$row->game_id?>" rate="<?=$row->exchange_rate?>" goldname="<?=$row->currency?>" <?=($this->input->get("game")==$row->game_id ? 'selected="selected"' : '')?>><?=$row->name?></option>
					<? endforeach;?>
				</select>	
			</span>
		</li>
		<li class="game_option line_row">
			<span class="field">請選擇儲值的伺服器</span>
			<span class="line_field">
				<select name="server" class="required">
					<option value="">--請先選擇遊戲--</option>
				</select>
			</span>
		</li>
		<li id="pay_type_block" class="line_row">
			<span class="field">請選擇支付管道</span>			
						
			<span class="line_field" style="width:300px; display:inline-block;">
				<? foreach($options as $tab => $arr): ?>
				<span class="pay_type pay_type_<?=$tab?>">
					<? foreach($arr as $opt => $arr2):
						if (array_key_exists("trade", $arr)) continue;
						$attr_str = '';
						foreach($arr2['trade'] as $attr => $val) $attr_str .= " {$attr}='{$val}'"; 
					?>
					<label><input type="radio" name="gash_channel" class="gash_option" maximum="<?=$arr2['maximum']?>" minimum="<?=$arr2['minimum']?>" <?=$attr_str?>><?=$opt?></label><br>
					<? endforeach;?>
				</span>
				<? endforeach;?>
			</span>
		</li>				
		<li class="line_row amount_row">
			<span class="field">請選擇儲值金額</span>
			<span class="line_field">
				<span class="amount_block"></span>
				<span id="gain_tip"></span>
			</span>
		</li>
		<li class="line_row" style="height:25px;"></li>
	  </ul>	  
	  <div style="text-align:center" class="line_row">
	  	<a href="javascript:;" onclick="$('#choose_form').submit()"><img src="/p/img/payment/btn.png"></a>
	  </div>
	</form>
<!--         
	<div style="margin:3px 0 0 -29px;">
		<img src="/p/img/payment/line.png">
	</div>
	 -->	
	
  <div style="padding:12px 0;">
		<? $this->load->view("payment/_note")?>  
  </div>             
  
  <select id="server_pool" style="display:none;">
		<? foreach($servers->result() as $row):
			if ( IN_OFFICE == false && in_array($row->server_status, array("private", "hide"))) continue;?>
		<option value="<?=$row->id?>" <?=($this->input->get("server")==$row->id ? 'selected="selected"' : '')?> class="<?=$row->game_id?>"><?=$row->name?></option>
		<? endforeach;?>
	</select>	
</div>

    
        </div>