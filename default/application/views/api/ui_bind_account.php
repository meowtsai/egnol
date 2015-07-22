<form method="post" id="bind_form" action="<?=$longe_url?>member/bind_account_json?site=<?=$site?>">
	<input type="hidden" name="redirect_url" value="<?=$this->input->get("redirect_url", TRUE)?>">
	<ul class="le_form">
		<li>綁定帳號</li>
		<li>您的帳號為 <?=$this->g_user->display_account();?></li>
		<li>
			<div class="field_name">電子信箱：
			</div><div class="field_input"><input type="text" name="email" class="required email"></div>
		</li>
		<li>
			<div class="field_name">行動電話：
			</div><div class="field_input"><input type="text" name="mobile" class="mobile isMobile" id="mobile">
			</div><div class="field_tip">電子信箱與行動電話至少需填寫其中一個。</div>
		</li>
		<li>
			<div class="field_name">請設定密碼：
			</div><div class="field_input"><input type="password" id="pwd" name="pwd" size="24" maxlength="35" class="required" value="" /></div>
		</li>
		<li>
			<div class="field_name">請再次輸入密碼：
			</div><div class="field_input"><input type="password" name="pwd2" size="24" maxlength="35" class="required" equalTo="#pwd" value="" /></div>
		</li>
		<li>
 			<input type="submit" value="綁定">&nbsp;
			<input name="cancel" type="button" value="取消" onclick="javascript:history.back();" />
		</li>
	</ul>
</form>
