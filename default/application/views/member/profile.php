<?
	$email = !empty($this->g_user->email) ? $this->g_user->email : "";
	$mobile = !empty($this->g_user->mobile) ? $this->g_user->mobile : "";
	$external_id = !empty($this->g_user->external_id) ? $this->g_user->external_id : "";
?>
<ul class="le_form">
	<li>
		<div>視覺圖片...</div>
	</li>
	<li>會員資料</li>
<? if(!empty($this->g_user->email) || !empty($this->g_user->mobile)): ?>
	<li>
		<div class="field_name">e-mail
		</div><div class="field_input">&nbsp;<?=$email?></div>
	</li>
	<li>
		<div class="field_name">行動電話
		</div><div class="field_input">&nbsp;<?=$mobile?></div>
	</li>
	<li>
		<div class="field_name">姓名
		</div><div class="field_input"></div>
	</li>
	<li>
		<div class="field_name">性別
		</div><div class="field_input"></div>
	</li>
	<li>
		<div class="field_name">生日
		</div><div class="field_input"></div>
	</li>
	<li>
		<div class="field_name">住址
		</div><div class="field_input"></div>
	</li>
	<li>
		<div>
			<input type="button" value="修改資料" onclick="javascript:location.href='<?=$longe_url?>member/update_profile?site=<?=$site?>'" />
			<input type="button" value="修改密碼" onclick="javascript:location.href='<?=$longe_url?>member/change_password?site=<?=$site?>'" />
<? else: ?>
	<li>
	<?
  		if(strpos($external_id, "@facebook"))
		{
			echo "透過 Facebook 登入";
		}
		else if(strpos($external_id, "@google"))
		{
			echo "透過 Google 登入";
		}
	?>
	</li>
	<li>
		<div>
			<input type="button" name="bind" id="bind" value="綁定帳號" onclick="javascript:location.href='<?=$longe_url?>member/bind_account?site=<?=$site?>'" />
<? endif; ?>
		</div>
		<div style="float:right;">
			<input type="button" value="登出" onclick="javascript:location.href='<?=$longe_url?>member/logout?site=<?=$site?>'" />
		</div>
		<div style="clear:both;"></div>
	</li>
</ul>
