<?
	$email = !empty($this->g_user->email) ? $this->g_user->email : "";
	$mobile = !empty($this->g_user->mobile) ? $this->g_user->mobile : "";
	$external_id = !empty($this->g_user->external_id) ? $this->g_user->external_id : "";
?>
<script type="text/javascript">
function loginSuccessButton (uid, email, mobile, external_id, server_id) {
	if (typeof LongeAPI != 'undefined') { 
        LongeAPI.onLoginSuccess(uid, email, mobile, external_id, server_id);
    } else {
        window.location = "ios://loginsuccess" + "-_-" + encodeURIComponent(uid + "-_-" + email + "-_-" + mobile + "-_-" + external_id + "-_-" + server_id);
	}
}
</script>
<ul class="le_form">
	<li>會員</li>
	<li>
		<div class="field_name">會員ID：
		</div><div class="field_input" id="user_id"><?=$this->g_user->uid?></div>
	</li>
<? if(!empty($this->g_user->email) || !empty($this->g_user->mobile)): ?>
	<li>
		<div class="field_name">Email：
		</div><div class="field_input" id="email"><?=$email?></div>
	</li>
	<li>
		<div class="field_name">手機號碼：
		</div><div class="field_input" id="mobile"><?=$mobile?></div>
	</li>
	<li>
		<input type="button" name="change_pwd" id="change_pwd" value="修改密碼" onclick="javascript:location.href='/api/ui_change_password?site=<?=$site?>'" />
	</li>
<? else: ?>
	<li>
		<input type="button" name="bind" id="bind" value="綁定帳號" onclick="javascript:location.href='/api/ui_bind_account?site=<?=$site?>'" />
	</li>
<? endif; ?>
<? if(!empty($servers)): ?>
	<li class="game_option line_row">
		<div class="field_line">
			<select id="server_selection" name="server" class="required" style="width:85%;">
				<?
					$selected = "selected";
					foreach($servers->result() as $row)
					{
						if ( IN_OFFICE == false && in_array($row->server_status, array("private", "hide")))
							continue;

						echo "<option value='{$row->server_id}' {$selected}>{$row->name}</option>";
						$selected = "";
					}
				?>
			</select>
		</div>
	</li>
<? endif; ?>
	<li>
		<input type="button" name="continue" id="continue" value="進入遊戲" onclick="loginSuccessButton(<?
			if(!empty($servers))
			{
	        	echo "'{$this->g_user->uid}','{$email}','{$mobile}','{$external_id}',$('#server_selection').find(':selected').val()";
			}
			else
			{
	        	echo "'{$this->g_user->uid}','{$email}','{$mobile}','{$external_id}',''";
			}
		?>)" />
	</li>
</ul>
