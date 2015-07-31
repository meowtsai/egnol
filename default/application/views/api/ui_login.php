<form id="login_form" method="post" action="/api/ui_login_json?site=<?=$site?>">
	<input type="hidden" name="partner" value="<?=$partner?>">
	<input type="hidden" name="game_key" value="<?=$game_key?>">
<? if(!empty($server_id)) : ?>
	<input type="hidden" name="server_id" value="<?=$server_id?>">
<? endif; ?>
	<ul class="le_form">
		<li>會員登入</li>
<? if(empty($server_id)) : ?>
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
<? else : ?>
		<li>
			<div class="field_name">登入伺服器：
			</div><div class="field_input"><?=$servers->name?></div>
		</li>
<? endif; ?>
		<li>
			<div class="field_name">Email或手機號碼：
			</div><div class="field_input"><input tabindex="1" name="account" class="required" maxlength="128" type="text" size="18" /></div>
		</li>
		<li>
			<div class="field_name">密碼：
			</div><div class="field_input"><input tabindex="2" name="pwd" type="password"  class="required" id="txtbox" maxlength="32"  size="20" AUTOCOMPLETE='OFF'/></div>
		</li>
		<li>
			<input tabindex="3" name="doLogin" type="submit" id="doLogin" value="送出" />
			<input name="quickLogin" type="button" id="quickLogin" value="直接登入" onclick="OnQuickLogin('<?=$device_id?>','<?=$site?>','<?=(empty($server_id)?'':$server_id)?>');" />
		</li>
		<li class="text-gray-light">
			<a href="/api/ui_forgot_password?site=<?=$site?>">忘記密碼</a>
		</li>
		<li>
			<input type="button" name="register" id="register" value="立即註冊" onclick="javascript:location.href='/api/ui_register?site=<?=$site?>'" />
		</li>
		<li>
			<?
				// 產生所有第三方登入按鈕
				foreach($channel_item as $channel)
				{
					echo "<div style='display:inline-block;width:16%;margin:5px;border:1px solid #777;background-color:#ddd;'>{$channel['name']}</div>";
				}
			?>
		</li>
	</ul>
</form>
