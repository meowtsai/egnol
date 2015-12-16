<?
	$email = !empty($this->g_user->email) ? $this->g_user->email : "";
	$mobile = !empty($this->g_user->mobile) ? $this->g_user->mobile : "";
	$external_id = !empty($this->g_user->external_id) ? $this->g_user->external_id : "";
?>
<div id="content-login">
	<div class="login-ins">
		<form id="login_form" method="post" action="<?=$api_url?>api2/ui_login_game_json?site=<?=$site?>">
			<input type="hidden" name="partner" value="<?=$partner?>">
			<input type="hidden" name="game_key" value="<?=$game_key?>">
			
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
					<a href="<?=$api_url?>api2/ui_change_account?site=<?=$site?>" title="更換帳號"><img src="<?=$longe_url?>p/image/member/change_usr.png" class="button_info"></a>&nbsp;
					<a href="<?=$api_url?>api2/ui_change_password?site=<?=$site?>" title="修改密碼"><img src="<?=$longe_url?>p/image/member/password.png" class="button_info"></a>
				</p>
<? else: ?>
			</table>

			<div class="login-button">
				<p>
					<a href="<?=$api_url?>api2/ui_change_account?site=<?=$site?>" title="更換帳號"><img src="<?=$longe_url?>p/image/member/change_usr.png" class="button_info"></a>&nbsp;
					<a href="<?=$api_url?>api2/ui_bind_account?site=<?=$site?>" title="綁定帳號"><img src="<?=$longe_url?>p/image/member/id.png" class="button_info"></a>
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
				<input name="doLogin" type="submit" id="doSubmit" value="" style="display:none;" />
			<!--/div>
		    </div>
		</form>
		
		<form id="login_form" method="post" action="#">
		    <div-->
				<img id="continue" style="cursor:pointer;" src="<?=$longe_url?>p/image/member/enter.png" onclick="javascript:$('#doSubmit').trigger('click')" />
		    </div>
		    </div>
		</form>
	</div>
</div>
