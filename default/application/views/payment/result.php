<?
	$status = $this->input->get("s");
	$transfer_status = $this->input->get("ts");
	$price = $this->input->get("p");
	$message = $this->input->get("m");

    $tips = "若您儲值遇到任何問題，請向<a href='{$longe_url}service?site={$site}'>客服中心</a>反映。";
?>
<div id="content-login">
	<div class="login-ins">
		<div class="bread cf" typeof="v:Breadcrumb">
			<a href="<?=$game_url?>" title="首頁" rel="v:url" property="v:title">首頁</a> > <a href="<?=$longe_url?>payment?site=<?=$site?>" title="儲值中心" rel="v:url" property="v:title">儲值結果</a>
		</div>
		<div class="login-form">
			<table class="member_info">
				<tr>
					<th>儲值遊戲　|</th><td><?=$game->name?></td>
				</tr>
				<tr>
					<th>伺服器　|</th><td><?=$server->name?></td>
				</tr>
				<tr>
					<th>儲值角色　|</th><td><?=$character->name?></td>
				</tr>
				<tr>
					<th>儲值金額　|</th><td><?=$price?></td>
				</tr>
				<tr>
					<th>儲值結果　|</th>
					<td>
					<?
						if ($status == '1' && $transfer_status == '1')
						{
							echo "儲值成功，獲得 {$this->input->get("gp")} {$game->currency}";
							$tips = "請至遊戲中確認您的{$game->currency}。";
						}
						else if ($status == '1' && $transfer_status == '0')
						{
							echo "扣款成功但{$game->currency}尚未轉入角色({$message})";
                            $tips = "請稍候五至十分鐘於遊戲中確認是否獲得{$game->currency}，若尚未獲得請向<a href='{$longe_url}service?site={$site}'>客服中心</a>反映。";
						}
						else if ($status == '0')
						{
							echo "儲值失敗".($message ? "，因{$message}" : "");
						}
					?>
					</td>
				</tr>
			</table>

			<ul class="notes">
				<li>★ <?=$tips?></li>
			</ul>
		</div>
	</div>
</div>