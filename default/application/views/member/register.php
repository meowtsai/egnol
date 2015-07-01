<div style="padding:20px 0;width:80%; max-width:480px; margin: 0 auto; text-align: center;">
	<form id="register_form" method="post" action="/member/register_json">
		<input type="hidden" id="redirect_url" value="<?=$redirect_url?>">
		<ul class="le_form">
			<li>會員登入</li>
			<li>
				<div class="field_name">會員帳號：
				</div><div class="field_input"><input type="text" name="account" class="required" minlength="6" maxlength="18">
				</div><div class="field_tip">6~18碼英文(系統會自動將大寫轉小寫)或數字組合。</div>
			</li>
			<li>
				<div class="field_name">會員密碼：
				</div><div class="field_input"><input type="password" id="pwd" name="pwd" class="required" minlength="6" maxlength="18">
				</div><div class="field_tip">6~18碼。</div>
			</li>
			<li>
				<div class="field_name">確認密碼：
				</div><div class="field_input"><input type="password" name="pwd2" class="required" equalTo='#pwd'></div>
			</li>
			<li>
				<div class="field_name">電子信箱：
				</div><div class="field_input"><input type="text" name="email" class="required email">
				</div><div class="field_tip">活動或忘記密碼時使用。</div>
			</li>
			<li>
				<div class="field_name">驗證碼：
				</div><div class="field_input">
					<div id="captcha_area"></div>
					<input class="required" style="width:50%;" type="text" name="captcha" size="5" maxlength="4" minlength="4" value="" />
				</div>
			</li>
			<li>
				<input name="chk" type="checkbox" class="required">我已閱讀並同意<div id="btn_policy">『會員服務條款』</div>及<div id="btn_agreement">『個資同意書』</div>
			</li>
			<li>
				<input tabindex="3" name="doLogin" type="submit" id="doLogin3" value="確定" />&nbsp;
				<input name="cancel" type="button" value="取消" onclick="javascript:history.back();" />
			</li>
			<li>
				<div></div>
			</li>
		</ul>
	</form>
</div>
