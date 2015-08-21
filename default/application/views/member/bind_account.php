<div id="content-login">
	<div class="login-ins">
		<div class="bread cf" typeof="v:Breadcrumb">
			<a href="<?=$game_url?>" title="首頁" rel="v:url" property="v:title">首頁</a> > <a href="<?=$longe_url?>member?site=<?=$site?>" title="會員資料" rel="v:url" property="v:title">會員資料</a> > <a href="<?=$longe_url?>member/bind_account?site=<?=$site?>" title="綁定帳號" rel="v:url" property="v:title">綁定帳號</a>
		</div>
		<form method="post" id="bind_form" action="<?=$longe_url?>member/bind_account_json?site=<?=$site?>">
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
						<img style="cursor:pointer;" src="<?=$longe_url?>p/image/member/submit.png" class="button_submit" onclick="javascript:$('#doSubmit').trigger('click')" />&nbsp;
						<img style="cursor:pointer;" src="<?=$longe_url?>p/image/member/clear.png" class="button_submit" onclick="javascript:history.back();" />
					</p>
				</div>
			</div>
		</form>
	</div>
</div>
