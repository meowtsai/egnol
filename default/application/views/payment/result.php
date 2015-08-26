<div id="content-login">
	<div class="login-ins">
		<div class="bread cf" typeof="v:Breadcrumb">
			<a href="<?=$game_url?>" title="首頁" rel="v:url" property="v:title">首頁</a> > <a href="<?=$longe_url?>payment?site=<?=$site?>" title="儲值中心" rel="v:url" property="v:title">儲值結果</a>
		</div>
		<div>
<?
/*
	$status = $this->input->get("s");
	$transfer_status = $this->input->get("ts");
	$price = $this->input->get("p");
	$message = $this->input->get("m");

	$result_message = "您本次儲值".($price ? "龍邑{$price}點" : "").($status=='1' ? "成功" : "失敗");
	$tip = "";

	if ($status == '1' && $transfer_status == '1') {
		$result_message .= "，兌換遊戲幣{$this->input->get("gp")}成功";
	}
	else if ($status == '1' && $transfer_status == '0') {
		$result_message .= "，龍邑點數剩餘{$this->input->get("rm")}點<br>但兌換失敗".($message ? "，因{$message}" : "");
		$tip = "※提醒您尚有龍邑點數未轉換成遊戲幣，若您多次嘗試均無法轉換，請聯繫<a href='".site_url("service")."' target='_blank'>客服中心</a>※";
	}
	else if ($status == '0') {
		$result_message .= ($message ? "，因{$message}" : "");
		$tip = "※提醒您，若您多次嘗試均無法儲值，請聯繫<a href='".site_url("service")."' target='_blank'>客服中心</a>※";
	}
*/
?>
		</div>
	</div>
	<ul class="notes">
		<li></li>
	</ul>
</div>