<p class="text-info">
	<span class="label label-info">說明</span>
	程式需要約20~60秒的時間
</p>	
	
<form method="get" action="<?=site_url("member/no_balance")?>" class="form-search">
	<input type="submit" class="btn btn-small btn-inverse" name="action" value="送出">
</form>

<? if ($this->input->get("action")):?>
<div class="msg">總筆數:<?=count($result)?></div>

<table class="table table-striped table-bordered" style="width:auto">
	<thead>
		<tr>
			<th style="text-align:center;">uid</th>
			<th style="color:#090; text-align:center;">儲值總額</th>
			<th style="color:#900; text-align:center;">轉點總額</th>
			<th style="color:#090; text-align:center;">回補總額</th>
			<th style="color:#090; text-align:center;">贈點總額</th>
			<th style="color:#900; text-align:center;">目前餘額</th>
			<th style="color:#000; text-align:center;">不平衡</th>
			<th style="color:#000; text-align:center;">測試帳號</th>
		</tr>
	</thead>
	<tbody>
		<? foreach($result as $row):?>
		<tr>
			<td style="text-align:center;"><a href="<?=site_url("member/view/{$row->uid}")?>"><?=$row->uid?></a></td>
			<td style="color:#090; text-align:center;"><?=$row->aq?></td>
			<td style="color:#900; text-align:center;"><?=$row->amount?></td>
			<td style="color:#090; text-align:center;"><?=$row->rq?></td>
			<td style="color:#090; text-align:center;"><?=$row->gq?></td>
			<td style="color:#900; text-align:center;"><?=$row->balance?></td>
			<td style="color:#000; text-align:center;">
				<? $nobalance = $row->aq+$row->rq+$row->gq-$row->amount-$row->balance;?>
				<span style="font-weight:bold; color:#<?=($nobalance>0 ? '090' : '900')?>"><?=$nobalance?></span>
			</td>
			<td style="color:#000; text-align:center;"><?=$row->test ? 'v' : ''?></td>
		</tr>
		<? endforeach;?>
	</tbody>
</table>
<? endif;?>