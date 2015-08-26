<div id="content-login">
	<div class="login-ins">
		<div class="bread cf" typeof="v:Breadcrumb">
			<a href="<?=$game_url?>" title="首頁" rel="v:url" property="v:title">首頁</a> > <a href="<?=$longe_url?>member?site=<?=$site?>" title="會員登入" rel="v:url" property="v:title">會員登入</a>
		</div>
		<form id="login_form" method="post" action="<?=$longe_url?>member/login_json?site=<?=$site?>">
			<input type="hidden" id="redirect_url" value="<?=$redirect_url?>">
			<div class="login-form">
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
				<p><a href="<?=$longe_url?>member/register?site=<?=$site?>&redirect_url=<?=urlencode($redirect_url)?>" title="login">註冊帳號</a>&nbsp;│&nbsp;<a href="<?=$longe_url?>member/forgot_password?site=<?=$site?>&redirect_url=<?=urlencode($redirect_url)?>" title="login">忘記密碼</a></p>
			</div>

			<div class="login-other">
			<?
				// 產生所有第三方登入按鈕
				$back_url = urlencode($redirect_url);
				foreach($channel_item as $channel)
				{
					if($channel['channel'] != "facebook" && $channel['channel'] != "google")
						continue;

					if($channel['channel'] == "facebook")
						echo "<img style='cursor:pointer;' src='/p/image/member/login-btn-fb.png' onclick='javascript:location.href=\"";
					if($channel['channel'] == "google")
						echo "<img style='cursor:pointer;' src='/p/image/member/login-btn-google.png' onclick='javascript:location.href=\"";

					echo "/member/channel_login?site={$site}&channel={$channel['channel']}&redirect_url={$redirect_url}\"'>";
				}
			?>
			</div>
		</form>
	</div>
</div>
