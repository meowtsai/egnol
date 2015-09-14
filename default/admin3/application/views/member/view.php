<? 
	$chk_balance = ($balance->aq+$balance->rq+$balance->gq)==($balance->amount+$balance->balance);
	$enable = $this->config->item("enable");
	$enable[0]['color'] = "#600";
	$enable[1]['color'] = "#006";
	
	$ad_channels = $this->config->item('ad_channels');
	$extra_ad_channels = $this->config->item('extra_ad_channels');
	
	foreach($extra_ad_channels as $arr) {
		$ad_channels = array_merge($ad_channels, $arr);
	}
?>

<legend><?=$user->uid?> (<?=$user->uid?>)</legend>

<div id="func_bar" style="text-align:right;">

<? if ( ! empty($user->bind_uid)):
	$u = $this->db->where("uid", $user->bind_uid)->from("users")->get()->row(); 
?>
	<p>此帳號為 <a href="<?=site_url("member/view/{$u->uid}")?>"><?=$u->uid?> (<?=$u->uid?>)</a> 的綁定帳號</p>

<? else:?>
	
	<? if ( ! empty($bind)):?>
	<p>此帳號綁定在 <a href="<?=site_url("member/view/{$bind->uid}")?>"><?=$bind->uid?> (<?=$bind->uid?>)</a></p>
	<? endif;?>
	
	<? if ($user->is_banned == 1):?>
		<a href="javascript:;" class="btn btn-small json_post_confirm" url="<?=site_url("member/set_right/{$user->uid}/0")?>"><i class="icon icon-ok-circle"></i> 解除停權</a>
	<? else:?>	
		<input type="text" id="stop_cause" style="width:200px; margin-bottom:0;" placeholder="停權原因">
		<a href="javascript:;" class="btn btn-small" url="<?=site_url("member/set_right/{$user->uid}/1")?>"
			onclick="javascript:if(confirm('確定要執行嗎?')){$.json_post($(this).attr('url'), {cause:$('#stop_cause').val()}, function(json){location.reload();});}"><i class="icon icon-ban-circle"></i> 停權</a>
	<? endif;?>

<? endif;?>

<a href="<?=site_url("member/switch_account/{$user->uid}")?>" class="btn btn-small" target="_blank"><i class="icon icon-retweet"></i> 登入他的帳號</a>

</div>
	
<dl class="dl-horizontal">
	<dt>uid</dt><dd><?=$user->uid?></dd>
	<dt>euid</dt><dd><?=$this->g_user->encode($user->uid)?></dd>
	<dt>帳號</dt><dd><?=$user->uid?></dd>
	<dt>姓名</dt><dd><?=$user->name?>&nbsp;</dd>
	<dt>性別</dt><dd><?=$user->sex?>&nbsp;</dd>
	<dt>信箱</dt><dd><?=$user->email?>&nbsp;</dd>
	<dt>手機</dt><dd><?=$user->mobile?>&nbsp;</dd>
	<dt>身分證</dt><dd><?=$user->ident?>&nbsp;</dd>
	<dt>地址</dt><dd><?=$user->street?>&nbsp;</dd>
	<dt>註冊日期</dt><dd><?=$user->create_time?></dd>
	<dt>最後登入日期</dt><dd><?=$user->last_login_date?>&nbsp</dd>
	<dt>帳號狀態</dt><dd>
		<? if ($user->is_banned == 1):?>
			<span style="color:red">停權</span> 
			<div><?=$user->ban_date?></div>
			<div><?=$user->ban_reason?></div>
		<? else:?>
			<span style="color:#006900">正常</span><br>
		<? endif;?>
	</dd>
</dl>

儲值記錄(
<a href="<?=site_url("trade/gash?uid={$user->uid}&action=查詢")?>">Gash+</a> |
<a href="<?=site_url("trade/pepay?uid={$user->uid}&action=查詢")?>">Pepay</a> |
<a href="<?=site_url("trade/google?uid={$user->uid}&action=查詢")?>">Google</a> |
<a href="<?=site_url("trade/mycard?uid={$user->uid}&action=查詢")?>">MyCard</a>)
<a href="<?=site_url("trade/transfer?uid={$user->uid}&action=查詢")?>" class="btn btn-link btn-small">轉點記錄</a>
<a href="<?=site_url("log/login?uid={$user->uid}&action=查詢")?>" class="btn btn-link btn-small">平台登入記錄</a>
<a href="<?=site_url("log/game_login?uid={$user->uid}&action=查詢")?>" class="btn btn-link btn-small">遊戲登入記錄</a>
<a href="<?=site_url("service/get_list?uid={$user->uid}&action=查詢")?>" class="btn btn-link btn-small">客服記錄</a>

<br><br>

<table class="table table-striped table-bordered" style="width:560px">
	<caption>交易統計</caption>
	<thead>
		<tr>
			<th style="color:#060">儲值總額</th>
			<th style="color:#600">轉點總額</th>
			<th style="color:#060">回補總額</th>
			<th style="color:#060">贈點總額</th>
			<th style="color:#600">目前餘額</th>
			<th>是否平衡</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td style="color:#060"><?=$balance->aq?></td>
			<td style="color:#600"><?=$balance->amount?></td>
			<td style="color:#060"><?=$balance->rq?></td>
			<td style="color:#060"><?=$balance->gq?></td>
			<td style="color:#600"><?=$balance->balance?></td>			
			<td style="color:<?=$enable[$chk_balance]["color"]?>"><?=$enable[$chk_balance]["name"]?></td>
		</tr>
	</tbody>
</table>

<table class="table table-striped table-bordered" style="width:560px">
	<caption>遊戲角色</caption>
	<thead>
		<tr>
			<th>遊戲</th>
			<th>角色名稱</th>
			<th>廣告來源</th>
			<th>創建時間</th>
		</tr>
	</thead>
	<tbody>
	  <? if ($role->num_rows() > 0):?>
		<? foreach($role->result() as $row): fb($row)?>
		<tr>
			<td><?=$row->game_name?> - <?=$row->server_name?></td>
			<td><?=$row->name?></td>
			<td><?=array_key_exists($row->ad, $ad_channels) ? $ad_channels[$row->ad] : '無'?></td>
			<td><?=$row->create_time?></td>
		</tr>
		<? endforeach;?>
	  <? else:?>
	  	<tr>
	  		<td colspan="4">尚無角色</td>
	  	</tr>
	  <? endif;?>
	</tbody>
</table>


