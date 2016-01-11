<div id="content-login">
	<div class="login-ins">
		<form id="login_form" method="post" action="<?=$api_url?>api2/ui_login_json?site=<?=$site?>">
			<input type="hidden" name="partner" value="<?=$partner?>">
			<input type="hidden" name="game_key" value="<?=$game_key?>">
			<div class="login-form">
            <?if(isset($is_duplicate_login) && $is_duplicate_login):?>
                <div class="notes"><b>★ 此帳號已於其他裝置進行遊戲中，請先將其登出。</b></div>
            <?endif;?>
			<table class="member_password">
				<tr>
					<th><span class="title">E-mail或手機號碼</span><input type="text" class="required" name="account" id="name" maxlength="128" size="33"></th>
				</tr>
				<tr>
					<th><span class="title">密碼</span><input type="password" class="required" name="pwd" id="name" maxlength="18" size="33" AUTOCOMPLETE="OFF"></th>
				</tr>
			</table>

			<div class="login-button">
				<input name="doLogin" type="submit" id="doSubmit" value="" style="display:none;" />
				<p><a href="#" title="login"><img src="<?=$longe_url?>p/image/member/login-btn.png" onclick="javascript:$('#doSubmit').trigger('click')"></a></p>
				<p><a href="<?=$api_url?>api2/ui_register?site=<?=$site?>" title="login">註冊帳號</a>&nbsp;│&nbsp;<a href="<?=$api_url?>api2/ui_forgot_password?site=<?=$site?>" title="login">忘記密碼</a></p>
				<p><img src="<?=$longe_url?>p/image/member/try-btn.png" style="cursor:pointer;" onclick="OnQuickLogin('<?=$device_id?>','<?=$site?>');" /></p>
			</div>

			<div class="login-other">
			<?
				// 產生所有第三方登入按鈕
				$back_url = urlencode($redirect_url);
				foreach($channel_item as $channel)
				{
					if($channel['channel'] == "facebook")
					{
						echo "<img style='cursor:pointer;' src='{$longe_url}p/image/member/login-btn-fb.png' onclick='javascript:OnClickFacebookLogin()' />";
					}
					else if($channel['channel'] == "google")
					{
						echo "<img style='cursor:pointer;' src='{$longe_url}p/image/member/login-btn-google.png' onclick='javascript:OnClickGoogleLogin(";
						echo "'{$api_url}api2/ui_channel_login?site={$site}&channel={$channel['channel']}'";
						echo ")' />";
					}
				}
			?>
			</div>
		</form>
	</div>
</div>
