<div style="padding:20px 0;width:80%; max-width:480px; margin:0 auto; text-align: center;">
	<form name="form1" id="form1" method="post" action="/gate/login?site=<?=$site?>&redirect_url=<?=urlencode($redirect_url)?>">
		<ul class="le_form">
			<li>會員登入</li>
			<li>
				<div class="field_name">帳號：
				</div><div class="field_input"><input tabindex="1" name="account" class="required" maxlength="18" type="text" size="18" value="<?=empty($account) ? '' : ($this->g_user->check_extra_account($account) ? '' : $account)?>" /></div>
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
				<div>Facebook Login</div>
			</li>
		</ul>
	</form>
</div>
