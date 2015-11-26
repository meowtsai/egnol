<div id="content-login">
	<div class="login-ins">
		<form id="forgot_form" action="<?=$api_url?>api2/ui_reset_password_json?site=<?=$site?>" method="post">
			<div class="login-form">
				<table class="member_password">
					<tr>
						<th><span class="title">E-mail或手機號碼</span><input type="text" name="account" class="required" maxlength="128" size="33"></th>
					</tr>
				</table>

				<div class="login-button">
					<input name="doLogin" type="submit" id="doSubmit" value="" style="display:none;" />
					<img style="cursor:pointer;" src="<?=$longe_url?>p/image/member/member_check.png" class="button_submit" onclick="javascript:$('#doSubmit').trigger('click')" />
				</div>

				<ul class="notes">
					<li>★ 請輸入帳號與對應的E-MAIL或手機號碼，系統將發送密碼重置郵件到信箱或手機簡訊中。</li>
					<li>★ 請確認，您輸入的資料是否為建立帳號時所輸入的資料。</li>
				</ul>
			</div>
		</form>
	</div>
</div>
