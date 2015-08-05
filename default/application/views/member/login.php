<style>
.channel_button{
    display:inline-block;
	width:16%;
	margin:5px;
	border:1px solid #777;
	background-color:#ddd;
}
.channel_button:hover{
	background-color:#fff;
	cursor:pointer;
}
</style>
<form id="login_form" method="post" action="<?=$longe_url?>member/login_json?site=<?=$site?>">
	<input type="hidden" id="redirect_url" value="<?=$redirect_url?>">
	<ul class="le_form">
		<li>會員登入</li>
		<li>
			<div class="field_name">電子郵件或行動電話：
			</div><div class="field_input"><input tabindex="1" name="account" class="required" maxlength="128" type="text" size="18" value="<?=empty($account) ? '' : $account?>" /></div>
		</li>
		<li>
			<div class="field_name">密碼：
			</div><div class="field_input"><input tabindex="2" name="pwd" type="password"  class="required" id="txtbox" maxlength="32"  size="20" AUTOCOMPLETE='OFF'/></div>
		</li>
		<li>
			<input tabindex="3" name="doLogin" type="submit" id="doLogin3" value="送出" />
			<input tabindex="4" type="hidden" name="remember" id="remember" value="0" />
		</li>
		<li class="text-gray-light">
			<a href="/member/register?site=<?=$site?>&redirect_url=<?=urlencode($redirect_url)?>">立即註冊</a>│
			<a href="/member/forgot_password?site=<?=$site?>&redirect_url=<?=urlencode($redirect_url)?>">忘記密碼</a>
		</li>
		<li>
			<?
				// 產生所有第三方登入按鈕
				$back_url = urlencode($redirect_url);
				foreach($channel_item as $channel)
				{
					echo "<div class='channel_button' onclick='javascript:location.href="."\"/member/channel_login?site={$site}&channel={$channel['channel']}&redirect_url={$back_url}\""."'>{$channel['name']}</div>";
				}
			?>
		</li>
	</ul>
</form>
