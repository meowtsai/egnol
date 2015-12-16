<?
	$status = $this->input->get("s");
	$transfer_status = $this->input->get("ts");
	$price = $this->input->get("p");
	$message = $this->input->get("m");

	$get_point = !empty($this->input->get("gp"))? $this->input->get("gp") : 0;

    $tips = "若您儲值遇到任何問題，請向<a href='{$longe_url}service?site={$site}'>客服中心</a>反映。";
?>
<script type='text/javascript'>
	function onClickPaymentOK()
	{
		if (typeof LongeAPI != 'undefined')
		{
<?
			echo "LongeAPI.onPaymentSuccess('{$game->game_id}','{$server->server_id}','{$character->name}','{$billing_type}','{$pay_type}',parseInt('{$price}',10),parseInt('{$get_point}',10));";
?>
		} else {
            encodedurl = encodeURIComponent("<? echo "ios://paymentresult-_-{$game->game_id}-_-{$server->server_id}-_-{$character->name}-_-{$billing_type}-_-{$pay_type}-_-{$price}-_-{$get_point}" ?>");
			window.location = encodedurl;
		}
	}
</script>
<div id="content-login">
	<div class="login-ins">
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
							echo "扣款成功但{$game->currency}尚未轉入角色";
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

			<div class="login-button">
				<p>
                    <img src="<?=$longe_url?>p/image/member/submit.png" class="button_submit" onclick="javascript:onClickPaymentOK();" />
				</p>
			</div>

			<ul class="notes">
				<li>★ <?=$tips?></li>
			</ul>
		</div>
	</div>
</div>
