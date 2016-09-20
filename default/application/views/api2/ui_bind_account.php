<div id="content-login">
	<div class="login-ins">
		<form method="post" id="bind_form" action="<?=$api_url?>api2/ui_bind_account_json?site=<?=$site?>">
			<div class="login-form">
				<table class="member_info">
					<tr>
						<th>E-mail</th>
						<td><input type="text" name="email" class="required email" id="account" size="33"></td>
					</tr>
					<tr>
						<th>手機號碼</th>
						<td><input type="text" name="mobile" class="mobile isMobile" id="mobile" size="33"></td>
					<tr>
						<th></th>
						<td>e-mail與手機號碼至少需填寫其中一個</td>
					</tr>
					<tr>
						<th>密　　碼</th><td><input type="password" id="pwd" name="pwd" maxlength="35" class="required" value="" size="33" /></td>
					</tr>
					<tr>
						<th>確認密碼</th><td><input type="password" name="pwd2" maxlength="35" class="required" equalTo="#pwd" value="" size="33" /></td>
					</tr>
				</table>

				<div class="login-button">
					<p>
						<input name="doLogin" type="submit" id="doSubmit" value="" style="display:none;" />
                        <a id="submit-btn" href="#" class="button_submit" onclick="javascript:$('#doSubmit').trigger('click');">確認</a>&nbsp;
                        <a id="cancel-btn" href="#" class="button_submit" onclick="javascript:history.back();">取消</a>
					</p>
				</div>
			</div>
		</form>
	</div>
</div>
