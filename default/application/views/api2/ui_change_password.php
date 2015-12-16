<div id="content-login">
	<div class="login-ins">
		<form id="change_form" action="<?=$api_url?>api2/ui_change_password_json?site=<?=$site?>" method="post">
			<div class="login-form">
				<table class="member_info">
					<tr>
						<th>舊密碼</th><td><input type="password" id="old" name="old" maxlength="18" minlength="6" size="33"></td>
					</tr>
					<tr>
						<th>新密碼</th><td><input type="password" id="pwd" name="pwd" maxlength="18" minlength="6" class="required" value="" size="33" /></td>
					</tr>
					<tr>
						<th>新密碼確認</th><td><input type="password" id="pwd2" name="pwd2" maxlength="18" minlength="6" class="required" equalTo="#pwd" value="" size="33" /></td>
					</tr>
				</table>

				<div class="login-button">
					<p>
						<input name="doLogin" type="submit" id="doSubmit" value="" style="display:none;" />
						<img style="cursor:pointer;" src="<?=$longe_url?>p/image/member/submit.png" class="button_submit" onclick="javascript:$('#doSubmit').trigger('click')" />&nbsp;
						<img style="cursor:pointer;" src="<?=$longe_url?>p/image/member/clear.png" class="button_submit" onclick="javascript:history.back();" />
					</p>
				</div>
			</div>
		</form>
	</div>
</div>
