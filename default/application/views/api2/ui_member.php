<?
	$email = !empty($this->g_user->email) ? $this->g_user->email : "";
	$mobile = !empty($this->g_user->mobile) ? $this->g_user->mobile : "";
	$external_id = !empty($this->g_user->external_id) ? $this->g_user->external_id : "";
	$account_type = "龍邑會員";
	$is_bind = false;

	if(strpos($external_id, "@facebook"))
	{
		$account_type = "Facebook 帳號";
	}
	else if(strpos($external_id, "@google"))
	{
		$account_type = "Google 帳號";
	}
	else if(strpos($external_id, "@device"))
	{
		$account_type = "行動裝置帳號";
	}

	if(!empty($this->g_user->email) || !empty($this->g_user->mobile))
		$is_bind = true;
?>
<div id="content-login">
	<div id="header">
		<div class="header-ins">
			<div class="header-logo">
				<img src="https://game.longeplay.com.tw/p/image/header-logo.png" />
			</div>
		</div>
	</div>
	<div id="content-area" class="login-ins">
		<form id="login_form" method="post" action="<?=$api_url?>api2/ui_login_game_json?site=<?=$site?>">
			<input type="hidden" name="partner" value="<?=$partner?>">
			<input type="hidden" name="game_key" value="<?=$game_key?>">
			
		    <div class="login-form" style="padding-top:0px;">
				 <div id="info_block">
					<table class="member_info">
<? if($is_bind): ?>
						<tr><td><div class="info_title">帳號類型</div></td></tr>
						<tr><td><div class="info_field"><?=$account_type?></div></td></tr>
					 	<tr><td><div class="info_title">E-MAIL</div></td></tr>
						<tr><td><div class="info_field"><?=(!empty($email) ? $email : "尚未設定")?></div></td></tr>
						<tr><td><div class="info_title">手機號碼</div></td></tr>
						<tr><td><div class="info_field"><?=(!empty($mobile) ? $mobile : "尚未設定")?></div></td></tr>
<? else: ?>
						<tr><td><div class="info_title">帳號類型</div></td></tr>
						<tr><td><div class="info_field"><?=$account_type?>(尚未綁定)</div></td></tr>
<? endif; ?>
					</table>
				 </div>

				<div id="button_block" class="login-button">
					<p>
<? if($is_bind): ?>
						<img id="_change-account-btn" onclick="javascript:window.location.href='<?=$api_url?>api2/ui_change_account?site=<?=$site?>'" class="button_info" />
						<img id="_change-pwd-btn" onclick="javascript:window.location.href='<?=$api_url?>api2/ui_change_password?site=<?=$site?>'" class="button_info" />
<? else: ?>
						<img id="_change-account-btn" onclick="javascript:window.location.href='<?=$api_url?>api2/ui_change_account?site=<?=$site?>'" class="button_info" />
						<img id="_bind-account-btn" onclick="javascript:window.location.href='<?=$api_url?>api2/ui_bind_account?site=<?=$site?>'" class="button_info" />
<? endif; ?>
					</p>
<? if($server_mode == 1): ?>
					<p class="game_option line_row">
						<div class="field_line">
							<select id="server_selection" name="server" class="required" style="width:85%;">
								<?
									$selected = "selected";
									foreach($servers->result() as $row)
									{
										if ( IN_OFFICE == false && in_array($row->server_status, array("private", "hide")))
											continue;

										echo "<option value='{$row->server_id}' {$selected}>{$row->name}</option>";
										$selected = "";
									}
								?>
							</select>
						</div>
					</p>
<? endif; ?>
					<input name="doLogin" type="submit" id="doSubmit" value="" style="display:none;" />
					<img id="continue" src="<?=$longe_url?>p/image/member/enter.png" class="_continue" />
				</div>
		    </div>
		</form>
	</div>
</div>
<script>
$(function()
{
	var h = $(window).height() - $("#content-area").height() - $("#header").height();
	if(h > 0)
	{
		$("#content-area").css("padding-top", h / 2 - 10);
	}
	/*
	var ib = $("#info_block");
	var h = ($(window).height() - $("#button_block").height() - $("#header").height()) * 0.6;
	if(h > ib.height())
	{
		ib.height(h);
	}
	*/
});
</script>