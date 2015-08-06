<form id="forgot_form" action="<?=$longe_url?>member/reset_password_json?site=<?=$site?>" method="post">
	<ul class="le_form">
		<li>
			<div class="field_name">電子郵件或行動電話：
			</div><div class="field_input"><input type="text" name="email" class="required email"></div>
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
			  <input type="button" value="取消" onclick="javascript:history.back();" />
		</li>
		<li>
			<div style="text-align:left;">
				★請確認，您輸入的資料是否為建立帳號時所輸入的資料。
			</div>
		</li>
	</ul>
</form>
