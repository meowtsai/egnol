<?php
	$channels = $this->config->item('channels'); 
?>
<div id="func_bar">
	
</div>

<legend>龍邑平台帳號數據(即時)</legend>
<? if ($account_query):?>
	<? if ($account_query->num_rows() == 0):
		echo '<div class="none">查無資料</div>'; 
	else: 
		$row = $account_query->row();
	?>
	<table class="table table-striped table-bordered" style="width:auto;">
		<thead>
			<tr>
				<th style="width:160px">帳號類型</th>
				<th style="width:160px"><?=date("m/d", strtotime("-5 days"))?>前</th>
				<th style="width:160px"><?=date("m/d", strtotime("-5 days"))?></th>
				<th style="width:160px"><?=date("m/d", strtotime("-4 days"))?></th>
				<th style="width:160px"><?=date("m/d", strtotime("-3 days"))?></th>
				<th style="width:160px"><?=date("m/d", strtotime("-2 days"))?></th>
				<th style="width:160px"><?=date("m/d", strtotime("-1 days"))?></th>
				<th style="width:160px">本日數量</th>
				<th style="width:160px">目前總數</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td>所有類型</td>
				<td style="text-align:right"><?=$row->y6_newuser_count?></td>
				<td style="text-align:right"><?=$row->y5_newuser_count?></td>
				<td style="text-align:right"><?=$row->y4_newuser_count?></td>
				<td style="text-align:right"><?=$row->y3_newuser_count?></td>
				<td style="text-align:right"><?=$row->y2_newuser_count?></td>
				<td style="text-align:right"><?=$row->y_newuser_count?></td>
				<td style="text-align:right"><?=$row->t_newuser_count?></td>
				<td style="text-align:right"><?=$row->newuser_count?></td>
			</tr>
			<tr>
				<td>直接申請</td>
				<td style="text-align:right"><?=$row->y6_newuser_longe_count?></td>
				<td style="text-align:right"><?=$row->y5_newuser_longe_count?></td>
				<td style="text-align:right"><?=$row->y4_newuser_longe_count?></td>
				<td style="text-align:right"><?=$row->y3_newuser_longe_count?></td>
				<td style="text-align:right"><?=$row->y2_newuser_longe_count?></td>
				<td style="text-align:right"><?=$row->y_newuser_longe_count?></td>
				<td style="text-align:right"><?=$row->t_newuser_longe_count?></td>
				<td style="text-align:right"><?=$row->newuser_longe_count?></td>
			</tr>
			<tr>
				<td>Facebook</td>
				<td style="text-align:right"><?=$row->y6_newuser_facebook_count?></td>
				<td style="text-align:right"><?=$row->y5_newuser_facebook_count?></td>
				<td style="text-align:right"><?=$row->y4_newuser_facebook_count?></td>
				<td style="text-align:right"><?=$row->y3_newuser_facebook_count?></td>
				<td style="text-align:right"><?=$row->y2_newuser_facebook_count?></td>
				<td style="text-align:right"><?=$row->y_newuser_facebook_count?></td>
				<td style="text-align:right"><?=$row->t_newuser_facebook_count?></td>
				<td style="text-align:right"><?=$row->newuser_facebook_count?></td>
			</tr>
			<tr>
				<td>Google</td>
				<td style="text-align:right"><?=$row->y6_newuser_google_count?></td>
				<td style="text-align:right"><?=$row->y5_newuser_google_count?></td>
				<td style="text-align:right"><?=$row->y4_newuser_google_count?></td>
				<td style="text-align:right"><?=$row->y3_newuser_google_count?></td>
				<td style="text-align:right"><?=$row->y2_newuser_google_count?></td>
				<td style="text-align:right"><?=$row->y_newuser_google_count?></td>
				<td style="text-align:right"><?=$row->t_newuser_google_count?></td>
				<td style="text-align:right"><?=$row->newuser_google_count?></td>
			</tr>
			<tr>
				<td>行動裝置</td>
				<td style="text-align:right"><?=$row->y6_newuser_quick_count?></td>
				<td style="text-align:right"><?=$row->y5_newuser_quick_count?></td>
				<td style="text-align:right"><?=$row->y4_newuser_quick_count?></td>
				<td style="text-align:right"><?=$row->y3_newuser_quick_count?></td>
				<td style="text-align:right"><?=$row->y2_newuser_quick_count?></td>
				<td style="text-align:right"><?=$row->y_newuser_quick_count?></td>
				<td style="text-align:right"><?=$row->t_newuser_quick_count?></td>
				<td style="text-align:right"><?=$row->newuser_quick_count?></td>
			</tr>
		</tbody>
	</table>
	<? endif;?>
<? endif;?>

<? if ($statistics_query):?>
	<? $game_id = '';
    foreach($statistics_query->result() as $row):?>
    <? if ($game_id <> '' && $game_id <> $row->game_id):?>
		</tbody>
	</table>
    <? endif;?>
    <? if ($game_id == '' || $game_id <> $row->game_id):
    $game_id = $row->game_id?>
    <legend><?=$row->name?>統計數據(每日更新)</legend>
	<table class="table table-striped table-bordered" style="width:auto;">
		<thead>
			<tr>
				<th style="width:160px">日期</th>
				<th style="width:160px">新增用戶數</th>
				<th style="width:160px">總創角數</th>
				<th style="width:160px">不重複創角數</th>
				<th style="width:160px">DAU</th>
				<th style="width:160px">1日留存率</th>
				<th style="width:160px">3日留存率</th>
			</tr>
		</thead>
		<tbody>
    <? endif;?>
			<tr>
				<td><?=date("m/d", strtotime($row->date))?></td>
				<td><?=$row->new_login_count?></td>
				<td><?=$row->total_new_character_count?></td>
				<td><?=$row->new_character_count?></td>
				<td><?=$row->login_count?></td>
				<td><?=number_format(($row->new_login_count)?$row->one_retention_count*100/$row->new_login_count:0, 2)?></td>
				<td><?=number_format(($row->new_login_count)?$row->three_retention_count*100/$row->new_login_count:0, 2)?></td>
			</tr>
	<? endforeach;?>
		</tbody>
	</table>
<? endif;?>

<? if ($billing_query):?>
	<? foreach($billing_query->result() as $row):?>
    <legend><?=$row->name?>儲值數據(即時)</legend>
	<table class="table table-striped table-bordered" style="width:auto;">
		<thead>
				<th style="width:160px">儲值管道</th>
				<th style="width:160px"><?=date("m/d", strtotime("-5 days"))?>前</th>
				<th style="width:160px"><?=date("m/d", strtotime("-5 days"))?></th>
				<th style="width:160px"><?=date("m/d", strtotime("-4 days"))?></th>
				<th style="width:160px"><?=date("m/d", strtotime("-3 days"))?></th>
				<th style="width:160px"><?=date("m/d", strtotime("-2 days"))?></th>
				<th style="width:160px"><?=date("m/d", strtotime("-1 days"))?></th>
				<th style="width:160px">本日數量</th>
				<th style="width:160px">目前總數</th>
		</thead>
		<tbody>
			<tr>
				<td>所有管道</td>
				<td style="text-align:right"><?=$row->y6_total?></td>
				<td style="text-align:right"><?=$row->y5_total?></td>
				<td style="text-align:right"><?=$row->y4_total?></td>
				<td style="text-align:right"><?=$row->y3_total?></td>
				<td style="text-align:right"><?=$row->y2_total?></td>
				<td style="text-align:right"><?=$row->y_total?></td>
				<td style="text-align:right"><?=$row->t_total?></td>
				<td style="text-align:right"><?=$row->total?></td>
			</tr>
			<tr>
				<td>GASH</td>
				<td style="text-align:right"><?=$row->y6_gash_total?></td>
				<td style="text-align:right"><?=$row->y5_gash_total?></td>
				<td style="text-align:right"><?=$row->y4_gash_total?></td>
				<td style="text-align:right"><?=$row->y3_gash_total?></td>
				<td style="text-align:right"><?=$row->y2_gash_total?></td>
				<td style="text-align:right"><?=$row->y_gash_total?></td>
				<td style="text-align:right"><?=$row->t_gash_total?></td>
				<td style="text-align:right"><?=$row->gash_total?></td>
			</tr>
			<tr>
				<td>AppStore</td>
				<td style="text-align:right"><?=$row->y6_ios_total?></td>
				<td style="text-align:right"><?=$row->y5_ios_total?></td>
				<td style="text-align:right"><?=$row->y4_ios_total?></td>
				<td style="text-align:right"><?=$row->y3_ios_total?></td>
				<td style="text-align:right"><?=$row->y2_ios_total?></td>
				<td style="text-align:right"><?=$row->y_ios_total?></td>
				<td style="text-align:right"><?=$row->t_ios_total?></td>
				<td style="text-align:right"><?=$row->ios_total?></td>
			</tr>
			<tr>
				<td>GooglePlay</td>
				<td style="text-align:right"><?=$row->y6_google_total?></td>
				<td style="text-align:right"><?=$row->y5_google_total?></td>
				<td style="text-align:right"><?=$row->y4_google_total?></td>
				<td style="text-align:right"><?=$row->y3_google_total?></td>
				<td style="text-align:right"><?=$row->y2_google_total?></td>
				<td style="text-align:right"><?=$row->y_google_total?></td>
				<td style="text-align:right"><?=$row->t_google_total?></td>
				<td style="text-align:right"><?=$row->google_total?></td>
			</tr>
		</tbody>
	</table>
	<? endforeach;?>
<? endif;?>