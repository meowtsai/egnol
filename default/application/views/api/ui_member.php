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
<div id="content-login">
	<div class="login-ins">
		<div class="login-form">
			<table class="member_info">
				<tr>
					<th>帳號類型　|</th>
					<td>
					<?
				  		if(strpos($external_id, "@facebook"))
						{
							echo "Facebook 帳號";
						}
						else if(strpos($external_id, "@google"))
						{
							echo "Google 帳號";
						}
						else if(strpos($external_id, "@device"))
						{
							echo "行動裝置帳號";
						}
						else
						{
							echo "龍邑會員";
						}
					?>
					</td>
				</tr>
<? if(!empty($this->g_user->email) || !empty($this->g_user->mobile)): ?>
				<tr>
					<th>E-MAIL　|</th><td><?=$email?></td>
				</tr>
				<tr>
					<th>手機號碼　|</th><td><?=$mobile?></td>
				</tr>
			</table>

			<div class="login-button">
				<p>
					<a href="<?=$api_url?>api/ui_change_password?site=<?=$site?>" title="login"><img src="<?=$longe_url?>p/image/member/password.png" class="button_info"></a>&nbsp;
				</p>
<? else: ?>
			</table>

			<div class="login-button">
				<p>
					<a href="<?=$api_url?>api/ui_bind_account?site=<?=$site?>" title="login"><img src="<?=$longe_url?>p/image/member/id.png" class="button_info"></a>
				</p>
<? endif; ?>
<? if($server_mode == 1): ?>
				<p class="game_option line_row">
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
				</p>
<? endif; ?>
				<input type="button" name="continue" id="continue" value="進入遊戲" onclick="loginSuccessButton(<?
					if($server_mode == 1)
					{
			        	echo "'{$this->g_user->uid}','{$email}','{$mobile}','{$external_id}',$('#server_selection').find(':selected').val()";
					}
					else
					{
						$server_id = $servers->row()->server_id;
			        	echo "'{$this->g_user->uid}','{$email}','{$mobile}','{$external_id}','{$server_id}'";
					}
				?>)" />
			</div>
		</div>
	</div>
</div>
