<div id="content-login">
	<div class="login-ins">
		<div class="bread cf" typeof="v:Breadcrumb">
			<a href="<?=$game_url?>" title="首頁" rel="v:url" property="v:title">首頁</a> > <a href="<?=$longe_url?>member?site=<?=$site?>" title="會員登入" rel="v:url" property="v:title">會員登入</a> > <a href="<?=$longe_url?>member/forgot_password?site=<?=$site?>" title="忘記密碼" rel="v:url" property="v:title">忘記密碼</a>
		</div>
		<form id="forgot_form" action="<?=$longe_url?>member/reset_password_json?site=<?=$site?>" method="post">
			<div class="login-form">
				<table class="member_password">
					<tr>
						<th><span class="title">E-mail或手機號碼</span><input type="text" name="email" class="required" maxlength="128" size="33"></th>
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
