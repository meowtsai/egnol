<?
	$gash_conf = $this->config->item("gash");
	
	$gash_tw_url = "/gash/order?country=tw";
	$gash_global_url = "/gash/order?country=global";
	$pepay_url = "/pepay/order";

	$options = array(
		"手機市話" => array(
			"中華電信市話" => array(
				"maximum" => 3000, "minimum" => 0,
				"trade" => array("paid"=>"TELCHT07", "cuid"=>"TWD", "erp_id"=>"J990001", "convert_rate"=>"1", "action" => $gash_tw_url)
			),
			"中華電信839" => array(
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
			),
		),
		"信用卡" => array(
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
		),
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
			"ธนาคารไทยการชำระเงิน(泰銖)" => array(
				"maximum" => 1000, "minimum" => 0,
				"trade" => array("paid"=>"COPPSB01", "cuid"=>"THB", "erp_id"=>"J990001", "convert_rate"=>"1.5", "action" => $gash_global_url)
			),
			"Thanh Toán Việt Nam(越南幣)" => array(
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
		"ADSL" => array(
			"中華電信Hinet" => array(
				"maximum" => 1000, "minimum" => 0,
				"trade" => array("paid"=>"TELCHT06", "cuid"=>"TWD", "erp_id"=>"J990001", "convert_rate"=>"1", "action" => $gash_tw_url)
			),
		),
	)
?>

<script type="text/javascript">
var gash_amount = ['<?= implode("','", $gash_conf["amount"])?>'];
</script>

<form id="choose_form" class="choose_form" method="post" action="" target="_blank" >
	<input type="hidden" name="PAID">
	<input type="hidden" name="CUID">
	<input type="hidden" name="ERP_ID">

	<input type="hidden" name="pay_type">
	<input type="hidden" name="subpay_type">
	<input type="hidden" name="prod_id">

	<ul class="le_form">
		<li>儲值中心</li>
		<li class="game_option line_row">
			<div class="field_line">
				<select name="game" class="required" style="width:85%;">
					<option value="">--請選擇遊戲--</option>
					<? foreach($games->result() as $row): ?>
					<option value="<?=$row->game_id?>" rate="<?=$row->exchange_rate?>" goldname="<?=$row->currency?>" <?=($this->input->get("game")==$row->game_id ? 'selected="selected"' : '')?>><?=$row->name?></option>
					<? endforeach;?>
				</select>
			</div>
		</li>
		<li class="game_option line_row">
			<div class="field_line">
				<select name="server" class="required" style="width:85%;">
					<option value="">--請先選擇遊戲--</option>
				</select>

				<select id="server_pool" style="display:none;">
					<? foreach($servers->result() as $row):
					if ( IN_OFFICE == false && in_array($row->server_status, array("private", "hide"))) continue;?>
					<option value="<?=$row->id?>" <?=($this->input->get("server")==$row->id ? 'selected="selected"' : '')?> class="<?=$row->game_id?>"><?=$row->name?></option>
					<? endforeach;?>
				</select>
			</div>
		</li>
		<li>
			<div class="field_line">
				<select name="character" class="required" style="width:85%;">
					<option value="">--請選擇角色--</option>
				</select>

				<select id="character_pool" style="display:none;">
					<? foreach($characters->result() as $row): ?>
					<option value="<?=$row->id?>" class="<?=$row->server_id?>"><?=$row->character_name?></option>
					<? endforeach;?>
				</select>
			</div>
		</li>
		<li>
			<div style="height:10px;"></div>
		</li>
		<li>
			<div class="field_line">
				<select name="billing_type" class="required" style="width:85%;">
                    <option value=''>--請選擇儲值方式--</option>

					<? foreach($options as $tab => $arr):
						if (array_key_exists("trade", $arr)):
							$attr_str = '';
							foreach($arr['trade'] as $attr => $val) $attr_str .= " {$attr}='{$val}'";
					?>
					<option pay_type="" maximum="<?=$arr['maximum']?>" minimum="<?=$arr['minimum']?>" <?=$attr_str?>><?=$tab?></option>
					<? else:?>
					<option pay_type="<?=$tab?>"><?=$tab?></option>
					<?
						endif;
					endforeach;?>
				</select>
			</div>
		</li>
		<li id="pay_type_block" class="line_row" style="display:none;">
			<div class="field_line" style="display:inline-block;">
				<? foreach($options as $tab => $arr): ?>
				<select name="billing_channel"  class="pay_type pay_type_<?=$tab?> required" style="width:85%;">
                    <option value=''>--請選擇支付管道--</option>

					<? foreach($arr as $opt => $arr2):
						if (array_key_exists("trade", $arr)) continue;
						$attr_str = '';
						foreach($arr2['trade'] as $attr => $val) $attr_str .= " {$attr}='{$val}'";
					?>
					<option value="<?=$opt?>" name="gash_channel" class="gash_option" maximum="<?=$arr2['maximum']?>" minimum="<?=$arr2['minimum']?>" <?=$attr_str?>><?=$opt?></option>
					<? endforeach;?>
				</select>
				<? endforeach;?>
			</div>
		</li>
		<li class="line_row amount_row" style="display:none;">
			<div class="field_line" style="display:inline-block;">
				<select name="billing_money"  class="amount_block required" style="width:85%;">
                    <option value=''>--請選擇儲值金額--</option>

				</select>
			</div>
		</li>
		<li>
			<input tabindex="3" name="send" type="submit" id="send" value="確定" />
		</li>
	</ul>
</form>
