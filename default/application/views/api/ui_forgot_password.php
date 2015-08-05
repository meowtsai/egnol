<form id="forgot_form" action="/api/ui_reset_password_json?site=<?=$site?>" method="post">
	<ul class="le_form">
		<li>
			<div class="field_name">Email或手機號碼：
			</div><div class="field_input"><input type="text" name="account" class="required"></div>
		</li>
		<li>
			<div class="field_name">輸入驗證碼：
			</div><div class="field_input">
				<div id="captcha_area"></div>
				<input type="text" name="captcha" size="5" maxlength="4" minlength="4" class="required" value="" />
			</div>
		</li>
		<li>
			<input type="submit" value="送出">
		</li>
	</ul>
</form>
