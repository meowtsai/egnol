<?
	$email = !empty($this->g_user->email) ? $this->g_user->email : "";
	$mobile = !empty($this->g_user->mobile) ? $this->g_user->mobile : "";
	$external_id = !empty($this->g_user->external_id) ? $this->g_user->external_id : "";
?>
<ul class="le_form">
	<li>會員</li>
	<li>
		<div class="field_name">會員ID：
		</div><div class="field_input"><?=$this->g_user->uid?></div>
	</li>
<? if(!empty($this->g_user->email) || !empty($this->g_user->mobile)): ?>
	<li>
		<div class="field_name">Email：
		</div><div class="field_input"><?=$email?></div>
	</li>
	<li>
		<div class="field_name">手機號碼：
		</div><div class="field_input"><?=$mobile?></div>
	</li>
<? else: ?>	
	<li>
		<input type="button" name="bind" id="bind" value="綁定帳號" onclick="javascript:location.href='/api/ui_bind_account?site=<?=$site?>'" />
	</li>
<? endif; ?>	
	<li>
		<input type="button" name="continue" id="continue" value="進入遊戲" onclick="javascript:LongeAPI.onLoginSuccess(<?
          echo "'{$this->g_user->uid}','{$email}','{$mobile}','{$external_id}'";
		?>)" />
	</li>
</ul>
