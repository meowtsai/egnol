<?php
	$channels = $this->config->item('channels');
?>

<? if ($is_game_statistics):?>
<div id="func_bar">
	<a href="<?=site_url("platform/schedule")?>" class="btn btn-primary" role="button">行事曆</a>
</div>

<legend>龍邑平台帳號數據(即時)</legend>
<? else:?>
<legend>龍邑平台</legend>
<? endif;?>
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
				<td>直接玩</td>
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

<?


if ($h35_stat_query):?>
	<? if ($h35_stat_query->num_rows() == 0):
		echo '<div class="none">查無資料</div>';
	else:

	?>
	<legend>光明之戰VIP統計數據</legend>
	<table class="table table-striped table-bordered" style="width:auto;">
		<thead>
			<tr>
				<th style="width:80px">日期</th>
				<th style="width:100px">儲值總額</th>
				<th style="width:460px">渠道分配</th>
				<th style="width:80px">儲值人次</th>
				<th >當日儲值前五名</th>
			</tr>
		</thead>

		<tbody>
			<? foreach($h35_stat_query->result() as $row):?>
			<tr>
				<td><?=date("m/d", strtotime($row->oDate))?></td>
				<td style="text-align:right">
					<?=number_format($row->oSum)?>
				</td>
				<td>
					<? foreach($h35_type_data[$row->oDate] as $type_row):
						switch ($type_row->transaction_type) {
							case 'app_store':
								echo "<i class='fab fa-apple'></i>";
								break;
							case 'google_play':
								echo "<i style='color:A4C639' class='fab fa-android'></i>";
								break;
							default:
								echo "<i class='fa fa-gamepad'></i>";
								break;
						}
						?>
					$ <?=number_format($type_row->oSum)?>
					 (<?=number_format(($type_row->oSum / $row->oSum)*100, 2, '.', ',')  ?>%)

					<? endforeach;?>

				</td>
				<td style="text-align:right"><?=$row->oCount?></td>
				<td>
					<? foreach($h35_ranking[$row->oDate] as $ranking_row):?>
					<?=$ranking_row->role_name?>($ <?=$ranking_row->oSum?>);
					<? endforeach;?>

				</td>
			</tr>
		<? endforeach;?>

		</tbody>
	</table>
	<? endif;?>
<? endif;?>


<?if ($L8na_stat_query):?>
	<? if ($L8na_stat_query->num_rows() == 0):
		echo '<div class="none">查無資料</div>';
	else:

	?>
	<legend>三少爺統計數據</legend>
	<table class="table table-striped table-bordered" style="width:auto;">
		<thead>
			<tr>
				<th style="width:80px">日期</th>
				<th style="width:100px">儲值總額</th>
				<th style="width:460px">渠道分配</th>
				<th style="width:80px">儲值人次</th>
				<th >當日儲值前五名</th>
			</tr>
		</thead>

		<tbody>
			<? foreach($L8na_stat_query->result() as $row):?>
			<tr>
				<td><?=date("m/d", strtotime($row->oDate))?></td>
				<td style="text-align:right">
					<?=number_format($row->oSum)?>
				</td>
				<td>
					<? foreach($L8na_type_data[$row->oDate] as $type_row):
						switch ($type_row->transaction_type) {
							case 'app_store':
								echo "<i class='fab fa-apple'></i>";
								break;
							case 'google_play':
								echo "<i style='color:A4C639' class='fab fa-android'></i>";
								break;
							default:
								echo "<i class='fa fa-gamepad'></i>";
								break;
						}
						?>
					$ <?=number_format($type_row->oSum)?>
					 (<?=number_format(($type_row->oSum / $row->oSum)*100, 2, '.', ',')  ?>%)

					<? endforeach;?>

				</td>
				<td style="text-align:right"><?=$row->oCount?></td>
				<td>
					<? foreach($L8na_ranking[$row->oDate] as $ranking_row):?>
					<?=$ranking_row->role_name?>($ <?=$ranking_row->oSum?>);
					<? endforeach;?>

				</td>
			</tr>
		<? endforeach;?>

		</tbody>
	</table>
	<? endif;?>
<? endif;?>
