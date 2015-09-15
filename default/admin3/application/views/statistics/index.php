<?php
	$channels = $this->config->item('channels'); 
?>
<div id="func_bar">
	
</div>

<form method="get" action="<?=site_url("statistics/operation")?>" class="form-search">
	<!--input type="hidden" name="game_id" value="<?=$this->input->get("span")?>"-->
	<input type="hidden" name="span" value="<?=$this->input->get("span")?>">
	<div class="control-group">
	</div>
</form>

<?
    if ($query0):?>
	<? if ($query0->num_rows() == 0):
		echo '<div class="none">查無資料</div>'; 
	else: 
		$row0 = $query0->result();
		$row1 = $query1->result();
		$row2 = $query2->result();
	?>
	<table class="table table-striped table-bordered" style="width:auto;">
		<thead>
			<tr>
				<th style="width:160px"> </th>
				<th style="width:160px">昨日<?=$row0[0]->find_date?></th>
				<th style="width:160px">前日<?=($row1[0])?$row1[0]->find_date:""?>對比</th>
				<th style="width:160px">上週同日<?=($row2[0])?$row2[0]->find_date:""?>對比</th>			 	
			</tr>
		</thead>
		<tbody>
			<tr>
				<td nowrap="nowrap">新增用戶</td>
				<td style="text-align:right"><?=number_format($row0[0]->new_login_count)?></td>
				<td style="text-align:right"><?=($row1[0])?number_format(($row1[0]->new_login_count)?100*$row0[0]->new_login_count/$row1[0]->new_login_count:0).'%':""?></td>
				<td style="text-align:right"><?=($row2[0])?number_format(($row2[0]->new_login_count)?100*$row0[0]->new_login_count/$row2[0]->new_login_count:0).'%':""?></td>																
			</tr>
			<tr>
				<td nowrap="nowrap">登入用戶</td>
				<td style="text-align:right"><?=number_format($row0[0]->login_count)?></td>
				<td style="text-align:right"><?=($row1[0])?number_format(($row1[0]->login_count)?100*$row0[0]->login_count/$row1[0]->login_count:0).'%':""?></td>
				<td style="text-align:right"><?=($row2[0])?number_format(($row2[0]->login_count)?100*$row0[0]->login_count/$row2[0]->login_count:0).'%':""?></td>																
			</tr>
			<tr>
				<td nowrap="nowrap">持續登入</td>
				<td style="text-align:right"><?=number_format($row0[0]->one_retention_all_count)?></td>
				<td style="text-align:right"><?=($row1[0])?number_format(($row1[0]->one_retention_all_count)?100*$row0[0]->one_retention_all_count/$row1[0]->one_retention_all_count:0).'%':""?></td>
				<td style="text-align:right"><?=($row2[0])?number_format(($row2[0]->one_retention_all_count)?100*$row0[0]->one_retention_all_count/$row2[0]->one_retention_all_count:0).'%':""?></td>																
			</tr>
			<tr>
				<td nowrap="nowrap">次日留存</td>
				<td style="text-align:right"><?=number_format($row0[0]->one_retention_count)?></td>
				<td style="text-align:right"><?=($row1[0])?number_format(($row1[0]->one_retention_count)?100*$row0[0]->one_retention_count/$row1[0]->one_retention_count:0).'%':""?></td>
				<td style="text-align:right"><?=($row2[0])?number_format(($row2[0]->one_retention_count)?100*$row0[0]->one_retention_count/$row2[0]->one_retention_count:0).'%':""?></td>																
			</tr>
			<tr>
				<td nowrap="nowrap">7日留存</td>
				<td style="text-align:right"><?=number_format($row0[0]->seven_retention_count)?></td>
				<td style="text-align:right"><?=($row1[0])?number_format(($row1[0]->seven_retention_count)?100*$row0[0]->seven_retention_count/$row1[0]->seven_retention_count:0).'%':""?></td>
				<td style="text-align:right"><?=($row2[0])?number_format(($row2[0]->seven_retention_count)?100*$row0[0]->seven_retention_count/$row2[0]->seven_retention_count:0).'%':""?></td>																
			</tr>
			<tr>
				<td nowrap="nowrap">登入設備</td>
				<td style="text-align:right"><?=number_format($row0[0]->device_count)?></td>
				<td style="text-align:right"><?=($row1[0])?number_format(($row1[0]->device_count)?100*$row0[0]->device_count/$row1[0]->device_count:0).'%':""?></td>
				<td style="text-align:right"><?=($row2[0])?number_format(($row2[0]->device_count)?100*$row0[0]->device_count/$row2[0]->device_count:0).'%':""?></td>																
			</tr>
			<tr>
				<td nowrap="nowrap">平均在線</td>
				<td style="text-align:right"><?=number_format(($row0[0]->login_count)?$row0[0]->total_time/$row0[0]->login_count:0)?></td>
				<td style="text-align:right"><?=($row1[0])?number_format(($row0[0]->login_count && $row1[0]->total_time && $row1[0]->login_count)?100*($row0[0]->total_time/$row0[0]->login_count)/($row1[0]->total_time/$row1[0]->login_count):0).'%':""?></td>
				<td style="text-align:right"><?=($row2[0])?number_format(($row0[0]->login_count && $row2[0]->total_time && $row2[0]->login_count)?100*($row0[0]->total_time/$row0[0]->login_count)/($row2[0]->total_time/$row2[0]->login_count):0).'%':""?></td>																
			</tr>
			<tr>
				<td nowrap="nowrap">峰值在線</td>
				<td style="text-align:right"><?=number_format($row0[0]->peak_user_count)?></td>
				<td style="text-align:right"><?=($row1[0])?number_format(($row1[0]->peak_user_count)?100*$row0[0]->peak_user_count/$row1[0]->peak_user_count:0).'%':""?></td>
				<td style="text-align:right"><?=($row2[0])?number_format(($row2[0]->peak_user_count)?100*$row0[0]->peak_user_count/$row2[0]->peak_user_count:0).'%':""?></td>																
			</tr>
			<tr>
				<td nowrap="nowrap">當日營收</td>
				<td style="text-align:right"><?=number_format($row0[0]->deposit_total)?></td>
				<td style="text-align:right"><?=($row1[0])?number_format(($row1[0]->deposit_total)?100*$row0[0]->deposit_total/$row1[0]->deposit_total:0).'%':""?></td>
				<td style="text-align:right"><?=($row2[0])?number_format(($row2[0]->deposit_total)?100*$row0[0]->deposit_total/$row2[0]->deposit_total:0).'%':""?></td>																
			</tr>
			<tr>
				<td nowrap="nowrap">總付費用戶</td>
				<td style="text-align:right"><?=number_format($row0[0]->deposit_user_count)?></td>
				<td style="text-align:right"><?=($row1[0])?number_format(($row1[0]->deposit_user_count)?100*$row0[0]->deposit_user_count/$row1[0]->deposit_user_count:0).'%':""?></td>
				<td style="text-align:right"><?=($row2[0])?number_format(($row2[0]->deposit_user_count)?100*$row0[0]->deposit_user_count/$row2[0]->deposit_user_count:0).'%':""?></td>																
			</tr>
			<tr>
				<td nowrap="nowrap">新增付費用戶</td>
				<td style="text-align:right"><?=number_format($row0[0]->new_deposit_user_count)?></td>
				<td style="text-align:right"><?=($row1[0])?number_format(($row1[0]->new_deposit_user_count)?100*$row0[0]->new_deposit_user_count/$row1[0]->new_deposit_user_count:0).'%':""?></td>
				<td style="text-align:right"><?=($row2[0])?number_format(($row2[0]->new_deposit_user_count)?100*$row0[0]->new_deposit_user_count/$row2[0]->new_deposit_user_count:0).'%':""?></td>																
			</tr>
			<tr>
				<td nowrap="nowrap">日付費比率</td>
				<td style="text-align:right"><?=number_format(($row0[0]->login_count)?100*$row0[0]->deposit_user_count/$row0[0]->login_count:0)."%"?></td>
				<td style="text-align:right"><?=($row1[0])?number_format(($row0[0]->login_count && $row1[0]->deposit_user_count && $row1[0]->login_count)?100*($row0[0]->deposit_user_count/$row0[0]->login_count)/($row1[0]->deposit_user_count/$row1[0]->login_count):0).'%':""?></td>
				<td style="text-align:right"><?=($row2[0])?number_format(($row0[0]->login_count && $row2[0]->deposit_user_count && $row2[0]->login_count)?100*($row0[0]->deposit_user_count/$row0[0]->login_count)/($row2[0]->deposit_user_count/$row2[0]->login_count):0).'%':""?></td>																
			</tr>
			<tr>
				<td nowrap="nowrap">ARPU</td>
				<td style="text-align:right"><?=number_format(($row0[0]->login_count)?100*$row0[0]->deposit_total/$row0[0]->login_count:0)."%"?></td>
				<td style="text-align:right"><?=($row1[0])?number_format(($row0[0]->login_count && $row1[0]->deposit_total && $row1[0]->login_count)?100*($row0[0]->deposit_total/$row0[0]->login_count)/($row1[0]->deposit_total/$row1[0]->login_count):0).'%':""?></td>
				<td style="text-align:right"><?=($row2[0])?number_format(($row0[0]->login_count && $row2[0]->deposit_total && $row2[0]->login_count)?100*($row0[0]->deposit_total/$row0[0]->login_count)/($row2[0]->deposit_total/$row2[0]->login_count):0).'%':""?></td>																
			</tr>
			<tr>
				<td nowrap="nowrap">ARPPU</td>
				<td style="text-align:right"><?=number_format(($row0[0]->deposit_user_count)?100*$row0[0]->deposit_total/$row0[0]->deposit_user_count:0)."%"?></td>
				<td style="text-align:right"><?=($row1[0])?number_format(($row0[0]->deposit_user_count && $row1[0]->deposit_total && $row1[0]->deposit_user_count)?100*($row0[0]->deposit_total/$row0[0]->deposit_user_count)/($row1[0]->deposit_total/$row1[0]->deposit_user_count):0).'%':""?></td>
				<td style="text-align:right"><?=($row2[0])?number_format(($row0[0]->deposit_user_count && $row2[0]->deposit_total && $row2[0]->deposit_user_count)?100*($row0[0]->deposit_total/$row0[0]->deposit_user_count)/($row2[0]->deposit_total/$row2[0]->deposit_user_count):0).'%':""?></td>																
			</tr>
			<tr>
				<td nowrap="nowrap">新用戶1日價值</td>
				<td style="text-align:right"><?=number_format(($row0[0]->new_login_count)?$row0[0]->one_ltv/$row0[0]->new_login_count:0)?></td>
				<td style="text-align:right"><?=($row1[0])?number_format(($row0[0]->new_login_count && $row1[0]->one_ltv && $row1[0]->new_login_count)?100*($row0[0]->one_ltv/$row0[0]->new_login_count)/($row1[0]->one_ltv/$row1[0]->new_login_count):0).'%':""?></td>
				<td style="text-align:right"><?=($row2[0])?number_format(($row0[0]->new_login_count && $row2[0]->one_ltv && $row2[0]->new_login_count)?100*($row0[0]->one_ltv/$row0[0]->new_login_count)/($row2[0]->one_ltv/$row2[0]->new_login_count):0).'%':""?></td>																
			</tr>
			<tr>
				<td nowrap="nowrap">累積安裝數</td>
				<td style="text-align:right"></td>
				<td style="text-align:right"></td>
				<td style="text-align:right"></td>																
			</tr>
			<tr>
				<td nowrap="nowrap">累積用戶</td>
				<td style="text-align:right"><?=number_format($row0[0]->total_users)?></td>
				<td style="text-align:right"><?=($row1[0])?number_format(($row1[0]->total_users)?100*$row0[0]->total_users/$row1[0]->total_users:0).'%':""?></td>
				<td style="text-align:right"><?=($row2[0])?number_format(($row2[0]->total_users)?100*$row0[0]->total_users/$row2[0]->total_users:0).'%':""?></td>																
			</tr>
			<tr>
				<td nowrap="nowrap">累積營收</td>
				<td style="text-align:right"><?=number_format($row0[0]->historical_revenue_sum)?></td>
				<td style="text-align:right"><?=($row1[0])?number_format(($row1[0]->historical_revenue_sum)?100*$row0[0]->historical_revenue_sum/$row1[0]->historical_revenue_sum:0).'%':""?></td>
				<td style="text-align:right"><?=($row2[0])?number_format(($row2[0]->historical_revenue_sum)?100*$row0[0]->historical_revenue_sum/$row2[0]->historical_revenue_sum:0).'%':""?></td>																
			</tr>
			<tr>
				<td nowrap="nowrap">整體付費率</td>
				<td style="text-align:right"><?=number_format(($row0[0]->total_users)?100*$row0[0]->historical_deposit_user_count/$row0[0]->total_users:0).'%'?></td>
				<td style="text-align:right"><?=($row1[0])?number_format(($row0[0]->total_users && $row1[0]->historical_deposit_user_count && $row1[0]->total_users)?100*($row0[0]->historical_deposit_user_count/$row0[0]->total_users)/($row1[0]->historical_deposit_user_count/$row1[0]->total_users):0).'%':""?></td>
				<td style="text-align:right"><?=($row2[0])?number_format(($row0[0]->total_users && $row2[0]->historical_deposit_user_count && $row2[0]->total_users)?100*($row0[0]->historical_deposit_user_count/$row0[0]->total_users)/($row2[0]->historical_deposit_user_count/$row2[0]->total_users):0).'%':""?></td>																
			</tr>
			<tr>
				<td nowrap="nowrap">總付費用戶</td>
				<td style="text-align:right"><?=number_format($row0[0]->historical_deposit_user_count)?></td>
				<td style="text-align:right"><?=($row1[0])?number_format(($row1[0]->historical_deposit_user_count)?100*$row0[0]->historical_deposit_user_count/$row1[0]->historical_deposit_user_count:0).'%':""?></td>
				<td style="text-align:right"><?=($row2[0])?number_format(($row2[0]->historical_deposit_user_count)?100*$row0[0]->historical_deposit_user_count/$row2[0]->historical_deposit_user_count:0).'%':""?></td>																
			</tr>
		</tbody>
	</table>
	<? endif;?>
<? endif;?>