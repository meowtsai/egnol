<? if ($bind_data):?>

<div style="padding: 20px 0;">
	<div style="width:80%; max-width:480px; margin: 0 auto; text-align: center;">
		<p>帳號已綁定在 <b><?=$bind_data->account?></b></p>
	</div>
</div>

<? else:?>

<div style="width:80%; max-width:480px; margin: 0 auto; padding: 20px 0; text-align: center;">
	<form method="post" class="json_form" action="/longe/member/bind_account_json">
      	<input type="hidden" name="redirect_url" value="<?=$this->input->get("redirect_url", TRUE)?>">
		<ul class="le_form">
			<li>綁定帳號</li>
			<li>您的帳號為 <?=$this->g_user->display_account();?></li>
			<li>
				<div class="field_name">請設定帳號：
				</div><div class="field_input"><input type="text" name="account" size="24" maxlength="35" class="required" minlength="6" value="" /></div>
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
				<div class="field_name">電子信箱：
				</div><div class="field_input"><input type="text" name="email" class="required email"></div>
			</li>
			<li>
   				<input type="submit" class="btn" value="確認送出">
			</li>
			<li>
				<div>綁定帳號是將您之前使用的第三方社群帳號與龍邑帳號連結使用，讓您不用再因為第三方社群平台維護而無法登入遊戲。</div>
			</li>
		</ul>
	</form>
</div>

<? endif;?>
