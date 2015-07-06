<form id="forgot_form" action="<?=site_url("member/change_password_json")?>" method="post">
      <input type="hidden" name="redirect_url" value="<?=$this->input->get("redirect_url", TRUE)?>">
	<ul class="le_form">
		<li>
			<div class="field_name">修改密碼：
			</div><div class="field_input"><input type="password" id="pwd" name="pwd" size="24" maxlength="35" class="required" value="" minlength="6" /></div>
		</li>
		<li>
			<div class="field_name">請再次輸入密碼：
			</div><div class="field_input"><input type="password" id="pwd2" name="pwd2" size="24" maxlength="35" class="required" equalTo="#pwd" value="" minlength="6"  /></div>
		</li>
		<li>
              <input type="submit" value="確認送出" class="btn">
		</li>
	</ul>
</form>
