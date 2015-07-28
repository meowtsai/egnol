<ul class="le_form">
	<li>會員</li>
	<li>
	</li>
	<li>
		<input type="button" name="continue" id="continue" value="繼續" onclick="javascript:LongeAPI.onLoginSuccess(<?
			$email = !empty($this->g_user->email) ? $this->g_user->email : "";
			$mobile = !empty($this->g_user->mobile) ? $this->g_user->mobile : "";
			$external_id = !empty($this->g_user->external_id) ? $this->g_user->external_id : "";

            echo "'{$this->g_user->uid}','{$email}','{$mobile}','{$external_id}'";
		?>)" />
	</li>
	<li>
		<input type="button" name="bind" id="bind" value="綁定帳號" onclick="javascript:location.href='/api/ui_bind_account?site=<?=$site?>'" />
	</li>
</ul>
