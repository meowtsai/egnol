<div id="content-login">
	<div class="login-ins">
		<div class="bread cf" typeof="v:Breadcrumb">
			<a href="<?=$game_url?>" title="首頁" rel="v:url" property="v:title">首頁</a> > <a href="<?=$longe_url?>member?site=<?=$site?>" title="會員登入" rel="v:url" property="v:title">會員登入</a> > <a href="<?=$longe_url?>member/register?site=<?=$site?>" title="帳號註冊" rel="v:url" property="v:title">帳號註冊</a>
		</div>
		<form id="register_form" method="post" action="<?=$longe_url?>member/register_json?site=<?=$site?>">
			<div class="login-form">
				<table class="member_info">
					<tr>
						<th>E-mail</th>
						<td><input type="text" name="email" class="email" id="email" size="33"></td>
					</tr>
					<tr>
						 <th>手機號碼</th>
						 <td><input type="text" name="mobile" class="mobile isMobile" id="mobile" size="33"></td>
					<tr>
						 <th></th>
						 <td>E-mail與手機號碼至少需填寫其中一個</td>
					</tr>
					<tr>
						<th>密　　碼</th><td><input type="password" id="pwd" name="pwd" class="required" minlength="6" maxlength="18" size="33"></td>
					</tr>
					<tr>
						<th>確認密碼</th><td><input type="password" name="pwd2" class="required" equalTo='#pwd' size="33"></td>
					</tr>
					<tr>
						<th>驗證碼</th>
						<td>
							<input type="text" name="captcha" class="required" maxlength="4" minlength="4" value="" size="33" style="width:100px;">
                            <div id="captcha_area" style="display:inline-block;vertical-align:middle;"></div>
						</td>
					</tr>
				</table>

				<div class="login-button">
					<p>
						<input type="checkbox" name="chk" class="required" id="check" style="max-width:20px;"> <span style="vertical-align:text-bottom;">我已閱讀並同意</span>
						<a href="<?=$longe_url?>member/service_agreement?site=<?=$site?>" style="vertical-align:text-bottom;color:#a50000">會員服務條款</a><span style="vertical-align:text-bottom;">、</span>
						<a href="<?=$longe_url?>member/member_agreement?site=<?=$site?>" style="vertical-align:text-bottom;color:##a50000">個資同意書</a><span style="vertical-align:text-bottom;">與</span>
						<a href="<?=$longe_url?>member/privacy_agreement?site=<?=$site?>"  style="vertical-align:text-bottom;color:##a50000">隱私權政策</a>
					</p>
					<p>
                    	<input name="doLogin" type="submit" id="doSubmit" value="" class="button_submit" style="display:none;" />
                        <a href="#" title="確定"><img src="<?=$longe_url?>p/image/member/submit.png" class="button_submit" onclick="javascript:$('#doSubmit').trigger('click')"></a>
						&nbsp;<a href="<?=$longe_url?>member?site=<?=$site?>" title="取消"><img src="<?=$longe_url?>p/image/member/clear.png" class="button_submit"></a>
					</p>
				</div>
			</div>
		</form>
	</div>
</div>
