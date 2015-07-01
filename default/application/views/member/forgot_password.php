<div style="padding:20px 0;width:80%; max-width:480px; margin: 0 auto; text-align: center;">
	<form id="forgot_form" action="<?=site_url("member/reset_password_json")?>" method="post">
		<ul class="le_form">
			<li>
				<div class="field_name">請輸入帳號：
				</div><div class="field_input"><input type="text" name="account" class="required"></div>
			</li>
			<li>
				<div class="field_name">申請時email：
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
			</li>
			<li>
				<div style="text-align:left;">
					請輸入帳號與對應的E-MAIL，系統將發送密碼重置郵件到信箱中。<br />
					★請確認，您輸入的資料是否為建立帳號時所輸入的資料。
				</div>
			</li>
		</ul>
	</form>
</div>