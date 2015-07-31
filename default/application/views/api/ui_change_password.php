<form id="change_form" action="/api/ui_change_password_json?site=<?=$site?>" method="post">
	<ul class="le_form">
		<li>
			<div class="field_name">輸入新密碼：
			</div><div class="field_input"><input type="password" id="pwd" name="pwd" size="24" maxlength="35" class="required" value="" minlength="6" /></div>
		</li>
		<li>
			<div class="field_name">確認新密碼：
			</div><div class="field_input"><input type="password" id="pwd2" name="pwd2" size="24" maxlength="35" class="required" equalTo="#pwd" value="" minlength="6"  /></div>
		</li>
		<li>
              <input type="submit" value="送出">
			  <input type="button" value="取消" onclick="javascript:history.back();">
		</li>
	</ul>
</form>
