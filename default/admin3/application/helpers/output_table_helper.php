<?php

function output_statistics_table($field, $table, $sort=false, $subtotal=true, $total=true) 
{
	ksort($field);
	
	$sum_x = $sum_y = array();
	$sum_all = 0;
	
	foreach($table as $title => $row) {
		foreach($field as $key => $name) { 
			if ( ! array_key_exists($title, $sum_x)) $sum_x[$title] = 0;
			if ( ! array_key_exists($key, $sum_y)) $sum_y[$key] = 0;
			if ( ! array_key_exists($key, $row)) {
				$table[$title][$key] = '';
			}
			else {
				$sum_x[$title] += $row[$key];
				$sum_y[$key] += $row[$key];
				$sum_all += $row[$key];
			}
		}
	}
	if ($sort) arsort($sum_x);
	
	$field0 = $field[0];
	unset($field[0]);
	
	$title_field = explode("|", $field0);
	$title_field_cnt = count($title_field);
?>
	<span class="label label-warning">筆數 <?=count($sum_x);?></span>
	<table class="table table-striped table-bordered" style="width:auto;">
		<thead>
			<tr>
				<? foreach($title_field as $name):?>
				<th nowrap="nowrap"><?=$name?></th>
				<? endforeach;?>
				<? foreach($field as $key => $name):?>
				<th style="width:80px"><?=$name?></th>
				<? endforeach;?>
				<? if ($subtotal):?> 	
				<th>小計</th>
				<? endif;?> 	
			</tr>
		</thead>
		<? ?>
		<tbody>
			<? foreach($sum_x as $title => $value):
				$title_field = explode("|", $title);
			?>
				<tr>
					<? foreach($title_field as $name):?>
					<td nowrap="nowrap"><?=$name?></td>
					<? endforeach;?>
					<? foreach($field as $key => $name):?>
					<td style="text-align:right"><?= ! empty($table[$title][$key]) ? number_format($table[$title][$key]) : ''?></td>
					<? endforeach;?>
					<? if ($subtotal):?>
					<td style="text-align:right"><?=number_format($value)?></td>
					<? endif;?>
				</tr>
			<? endforeach;?>
			<? if ($total):?>
			<tr>
				<td colspan="<?=$title_field_cnt?>">總計</td>
				<? foreach($field as $key => $name):?>
				<td style="text-align:right"><?=number_format($sum_y[$key])?></td>
				<? endforeach;?>
				
				<? if ($subtotal):?>
				<td style="text-align:right"><?=number_format($sum_all)?></td>
				<? endif;?>
			</tr>	
			<? endif;?>
		</tbody>
	</table>	
<? 		
}