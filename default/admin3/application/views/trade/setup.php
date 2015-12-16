<? 
	$trade_type = array(
		"網路ATM" => array("BNK80802", array("pay_type" => "TY-ATM", "prod_id" => "PD-WEBATM-ESUN", "sub_pay_type" => "ST-WEB"), 10000), //玉山
		"臨櫃ATM" => array("BNK80803", array("pay_type" => "TY-ATM", "prod_id" => "PD-ATM-CTCB", "sub_pay_type" => "ST-ATM"), 10000), //中國
		
		"信用卡-歐付寶" => array("BNK82201", array("pay_type" => "TY-CREDIT", "prod_id" => "PD-CREDIT-ALLPAY", "sub_pay_type" => ""), 3000),
		"信用卡-中國信託" => array("BNK82201", array("pay_type" => "TY-CREDIT", "prod_id" => "PD-CREDIT-CTCB", "sub_pay_type" => ""), 3000),
		"信用卡-華南" => array("BNK82201", array("pay_type" => "TY-CREDIT", "prod_id" => "PD-CREDIT-HNCB", "sub_pay_type" => ""), 3000),
		"信用卡-紅陽" => array("BNK82201", array("pay_type" => "TY-CREDIT", "prod_id" => "PD-CREDIT-REDSUN", "sub_pay_type" => ""), 3000),
		"信用卡-台新" => array("BNK82201", array("pay_type" => "TY-CREDIT", "prod_id" => "PD-CREDIT-TSCB", "sub_pay_type" => ""), 3000),
			
		"中華電信Hinet" => array("TELCHT06", array("pay_type" => "TY-BILL", "prod_id" => "PD-BILL-CHTAAA-HINET", "sub_pay_type" => "ST-ISP"), 1000),
		"中華電信市話" => array("TELCHT07", array("pay_type" => "TY-BILL", "prod_id" => "PD-BILL-CHTAAA-LOCAL", "sub_pay_type" => "ST-LOCAL"), 3000),
		"中華電信839" => array("TELCHT05", array("pay_type" => "TY-BILL", "prod_id" => "PD-BILL-CHTAAA-839", "sub_pay_type" => "ST-MOBILE"), 3000),
		"台灣大哥大" => array("TELTCC01", array("pay_type" => "TY-BILL", "prod_id" => "PD-BILL-TCC", "sub_pay_type" => "ST-MOBILE"), 3000),
		"遠傳電信" => array("TELFET01", array("pay_type" => "TY-BILL", "prod_id" => "PD-BILL-FET", "sub_pay_type" => "ST-MOBILE"), 3000),
		"亞太電信" => array("TELSON04", array("pay_type" => "TY-BILL", "prod_id" => "PD-BILL-APBW", "sub_pay_type" => "ST-MOBILE"), 1000),
		"支付寶" => array("BNK80804", array("pay_type" => "TY-CHINA", "prod_id" => "PD-EPOINT-ESUN-ALIPAY", "sub_pay_type" => ""), 10000),  		
	);
	$amount = array("1", "100", "300", "500", "1000", "3000", "5000", "10000");
?>
<style type="text/css">
.table label {padding:2px; margin:0px; display:inline-block; line-height:10px;}
.table label:hover {background:#cfe;}
.table label select {margin:0px;}
.table th, .table td {padding:5px;}
.table ul {display:inline-block; margin:0; padding:4px; margin-bottom:0;}
.table li {list-style: none;}
</style>
<p class="text-info">
	<span class="label label-info">說明</span>
	Gash+ 信用卡上限只到3000、中華電信Hinet 1000、中華電信市話 3000
</p>	
<form action="" method="post">
<table class="table table-striped" style="width:auto">
	<thead>
		<tr>
			<th style="width:120px; text-align:center;">gash交易類型</th>
			<th style="width:820px; text-align:center;">設為pepay</th>	 	
		</tr>
	</thead>
	<tbody>
		<? foreach($trade_type as $type => $arr):?>
		<tr>
			<td style="text-align:center;"><?=$type?></td>
			<td style="text-align:left;">
				<? foreach($amount as $mval):
					if ($mval > $arr[2]) continue;
				
					$item = $arr[0]."|".$arr[1]['prod_id']."|".$arr[1]['pay_type']."|".$arr[1]['sub_pay_type']."|".$mval;
					$is_pepay = in_array($item."|0", $pepay_table);
					$is_close = in_array($item."|1", $pepay_table);
				?>
					<ul>
						<li> $<?=$mval;?></li>
						<li>
							<label style="<?=$is_pepay || $is_close ? 'background:#afd; ' : '' ?>">
							<select name="choose[]" style="width:80px;">
								<option value="">--</option>
								<option value="<?=$item?>|0" <?=$is_pepay ? "selected='selected'" : ""?>>使用pepay</option>
								<option value="<?=$item?>|1" <?=$is_close ? "selected='selected'" : ""?>>設為維護</option>
							</select>
							</label>
						</li>
					</ul>						
				<? endforeach;?>
			</td>
		</tr>
		<? endforeach;?>
	</tbody>
</table>


<div class="form-actions"><input type="submit" value="確認送出" class="btn"></div>	
</form>