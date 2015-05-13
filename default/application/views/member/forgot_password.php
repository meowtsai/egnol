<div>
	<form id="forgot_form" action="<?=site_url("member/reset_password_json")?>" method="post">
		<ul>
			<li>請輸入帳號：<input type="text" name="account" class="required"></li>	
			<li>申請時email：<input type="text" name="email" class="required email"></li>
			<li>
				輸入驗證碼：
				<div id="captcha_area"></div>
				<input type="text" name="captcha" size="5" maxlength="4" minlength="4" class="required" value="" />
			</li>
		</ul>
	
		<input type="submit" value="送出">
	</form>
</div>