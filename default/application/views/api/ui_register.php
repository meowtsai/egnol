<div id="content-login">
	<div class="login-ins">
        <form id="register_form" method="post" action="<?=$api_url?>api/ui_register_json?site=<?=$site?>">
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
						<a href="<?=$api_url?>api/ui_service_agreement?site=<?=$site?>" style="vertical-align:text-bottom;color:#fffb00">會員服務條款</a><span style="vertical-align:text-bottom;">、</span>
						<a href="<?=$api_url?>api/ui_member_agreement?site=<?=$site?>" style="vertical-align:text-bottom;color:#fffb00">個資同意書</a><span style="vertical-align:text-bottom;">與</span>
						<a href="<?=$api_url?>api/ui_privacy_agreement?site=<?=$site?>"  style="vertical-align:text-bottom;color:#fffb00">隱私權政策</a>
					</p>
					<p>
                    	<input name="doLogin" type="submit" id="doSubmit" value="" class="button_submit" style="display:none;" />
                        <a href="#" title="確定"><img src="<?=$longe_url?>p/image/member/submit.png" class="button_submit" onclick="javascript:$('#doSubmit').trigger('click')"></a>
						&nbsp;<a href="<?=$api_url?>api/ui_login?site=<?=$site?>" title="取消"><img src="<?=$longe_url?>p/image/member/clear.png" class="button_submit"></a>
					</p>
				</div>
			</div>
		</form>
	</div>
</div>
