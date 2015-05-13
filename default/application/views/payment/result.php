<? 
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
?>
<style type="text/css">
<? if (check_mobile()):?>
#result_msg {}
.field {width:30%; font-size:17px; font-weight:bold; text-align:center; padding:0 9px 0 0; display:inline-block; vertical-align:top;}
<? else: ?>
#result_msg {margin:50px auto; width:580px;}
.field {width:140px; font-size:17px; font-weight:bold; text-align:center; padding:0 9px 0 0; display:inline-block; vertical-align:top;}
<? endif;?>
.line_field {margin:6px 0; padding:2px 30px; color:red; font-size:15px; font-weight:bold;}
</style>

<div id="result_msg">

	<div class="line_row" style="border-top:1px solid #cacaca;">
		<span class="field" style="height:60px; line-height:60px;">儲值結果</span>
		<span class="line_field" style="height:44px; max-width:350px;"><?=$result_message?></span>
	</div>
	<? if ($tip):?>
	<div class="line_row" style="text-align:center; font-size:14px; padding:9px 0;"><?=$tip?></div>
	<? endif;?>
	<div style="text-align:right; padding-top:30px;">
		<? if (check_mobile()):?>
		
		<input type="button" value="關閉" style="padding:10px 30px;" onclick="window.CoozSDK.closeCoozBilling();">
		
		<? else:?>
		
		<a href="<?=site_url("payment")?>">我要儲值</a> |
		<a href="<?=site_url("wallet/transfer")?>">我要轉點</a>
		<? if ($this->input->get("sid")):?>
		 | <a href="<?=site_url("play_game?sid={$this->input->get("sid")}")?>">進入遊戲</a>
		<? endif;?>
		
		<? endif;?>
	</div>
        
  <div style="padding:12px 0;">
		<? $this->load->view("payment/_note")?>  
  </div>        
</div>            
	