<form id="login_form" method="post" action="/api/ui_login_json?site=<?=$site?>">
	<ul class="le_form">
		<li>會員登入</li>
		<li>
			<div class="field_name">Email或手機號碼：
			</div><div class="field_input"><input tabindex="1" name="account" class="required" maxlength="128" type="text" size="18" /></div>
		</li>
		<li>
			<div class="field_name">密碼：
			</div><div class="field_input"><input tabindex="2" name="pwd" type="password"  class="required" id="txtbox" maxlength="32"  size="20" AUTOCOMPLETE='OFF'/></div>
		</li>
		<li>
			<input tabindex="3" name="doLogin" type="submit" id="doLogin" value="送出" />
			<input name="quickLogin" type="button" id="quickLogin" value="直接登入" onclick="javascript:location.href='/api/ui_quick_login?deviceid=<?=$device_id?>&site=<?=$site?>'" />
		</li>
		<li class="text-gray-light">
			<a href="/api/ui_forgot_password?site=<?=$site?>">忘記密碼</a>
		</li>
		<li>
			<input type="button" name="register" id="register" value="立即註冊" onclick="javascript:location.href='/api/ui_register?site=<?=$site?>'" />
		</li>
		<li>
			<?
				// 產生所有第三方登入按鈕
				foreach($channel_item as $channel)
				{
					echo "<div style='display:inline-block;width:16%;margin:5px;border:1px solid #777;background-color:#ddd;'>{$channel['name']}</div>";
				}
			?>
		</li>
	</ul>
</form>
