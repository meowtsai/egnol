<?
	$email = !empty($this->g_user->email) ? $this->g_user->email : "";
	$mobile = !empty($this->g_user->mobile) ? $this->g_user->mobile : "";
	$external_id = !empty($this->g_user->external_id) ? $this->g_user->external_id : "";

	$sex_text = array(
		0 => "尚未填寫",
		1 => "男性",
		2 => "女性"
	);
	$sex = !empty($user_info->sex) ? $user_info->sex : 0;
?>
<div id="content-login">
	<div class="login-ins">
		<div class="bread cf" typeof="v:Breadcrumb">
			<a href="<?=$game_url?>" title="首頁" rel="v:url" property="v:title">首頁</a> > <a href="<?=$longe_url?>member?site=<?=$site?>" title="會員資料" rel="v:url" property="v:title">會員資料</a>
		</div>
		<div class="login-form">
<? if(!empty($this->g_user->email) || !empty($this->g_user->mobile)): ?>
			<table class="member_info">
				<tr>
					<th>帳號類型　|　</th>
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
				<tr>
					<th>姓　　名　|　</th><td><?=$user_info->name?></td>
				</tr>
				<tr>
					<th>性　　別　|　</th><td><?=$sex_text[$sex]?></td>
				</tr>
				<tr>
					<th>生　　日　|　</th><td><?=$user_info->birthday?></td>
				</tr>
				<tr>
					<th>地　　址　|　</th><td><?=$user_info->street?></td>
				</tr>
				<tr>
					<th>E-MAIL　|　</th><td><?=$email?></td>
				</tr>
				<tr>
					<th>行動電話　|　</th><td><?=$mobile?></td>
				</tr>
			</table>

			<div class="login-button">
				<p>
					<a href="<?=$longe_url?>member/update_profile?site=<?=$site?>" title="login"><img src="<?=$longe_url?>p/image/member/info.png" class="button_info"></a>&nbsp;
					<a href="<?=$longe_url?>member/change_password?site=<?=$site?>" title="login"><img src="<?=$longe_url?>p/image/member/password.png" class="button_info"></a>&nbsp;
					<img style='cursor:pointer;' src="<?=$longe_url?>p/image/member/logout.png" class="button_info" onclick="javascript:location.href='<?=$longe_url?>member/logout?site=<?=$site?>'">
				</p>
			</div>

			<ul class="notes">
				<li>★ 個資填寫提醒訊息</li>
			</ul>
<? else: ?>
			<table class="member_info">
				<tr>
					<th>帳號類型　|　</th>
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
					?>
					</td>
				</tr>
			</table>

			<div class="login-button">
				<p>
					<a href="<?=$longe_url?>member/bind_account?site=<?=$site?>" title="login"><img src="<?=$longe_url?>p/image/member/id.png" class="button_info"></a>&nbsp;
					<img style='cursor:pointer;' src="<?=$longe_url?>p/image/member/logout.png" class="button_info" onclick="javascript:location.href='<?=$longe_url?>member/logout?site=<?=$site?>'">
				</p>
			</div>

			<ul class="notes">
				<li>★ 綁定提醒訊息</li>
			</ul>
<? endif; ?>
		</div>
	</div>
</div>
