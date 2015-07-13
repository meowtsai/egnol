<?php 
	$c_game_query = $this->db->from("games")->order_by("rank")->get();

	$c_game = array();
	$c_game_menu["獨代"] = array();
	$c_game_menu["聯運"] = array();
	$c_game_menu["關閉"] = array();
	
	foreach($c_game_query->result() as $row) {
		$c_game[$row->game_id] = $row;		
		if (!$row->is_active) {$c_game_menu["關閉"][] = $row; continue;}
		if (strpos($row->tags.",", "聯運,") !== false) {$c_game_menu["聯運"][] = $row; continue;}
		$c_game_menu["獨代"][] = $row;
	}
	
	$channels = $this->config->item('channels');
?>
<div id="func_bar">
	
</div>

<form method="get" action="<?=site_url("statistics/whale_users")?>" class="form-search">

	<div class="control-group">
		
		<select name="game_id">
		    <option value="">--</option>
			<?
			foreach($c_game_menu as $category => $c_menu):?>
				<option value=""> -------- <?=$category?> --------</option>
				<? foreach($c_menu as $key => $row):?>
				<option value="<?=$row->game_id?>" <?=($game_id==$row->game_id ? 'selected="selected"' : '')?>><?=$row->name?>	</option>
				<? endforeach;?>
			<? $i++;  
			endforeach;?>	
	    </select>
		
		<input type="submit" class="btn btn-small btn-inverse" name="action" value="鯨魚用戶統計">	
	
	</div>
		
</form>

<? if ($query):?>
	<? if ($query->num_rows() == 0): echo '<div class="none">查無資料</div>'; else: ?>
	<table class="table table-striped table-bordered" style="width:auto;">
		<thead>
			<tr>
				<th nowrap="nowrap" rowspan="2">排名</th>
				<th style="width:70px" rowspan="2">帳號</th>
				<th style="width:70px" rowspan="2">角色</th>
				<th style="width:70px" rowspan="2">所在伺服器</th>
				<th style="width:70px" rowspan="2">儲值累積金額</th>
				<th style="width:70px" rowspan="2">獲得點數</th>
				<th style="width:70px" rowspan="2">剩餘點數</th>
				<th style="width:70px" rowspan="2">累積消費點數</th>
				<th style="width:70px" rowspan="2">帳號創立時間</th>				 	
			</tr>
		</thead>
		<tbody>
		<? $color = array('00aa00', '448800', '776600', 'aa4400', 'dd2200', 'ff0000');
			$seq = 1;
			foreach($query->result() as $row):
			
		?>
			<tr>			
				<td nowrap="nowrap"><?=$seq++?></td>
				<td style="text-align:right"><?=$row->uid?></td>
				<td style="text-align:right"><?=$row->character_name?></td>
				<td style="text-align:right"><?=$row->server_name?></td>
				<td style="text-align:right"><?=number_format($row->deposit_total)?></td>
				<td style="text-align:right"><?=number_format($row->currency_total)?></td>
				<td style="text-align:right"><?=number_format($row->currency_total-$row->currency_consumed)?></td>
				<td style="text-align:right"><?=number_format($row->currency_consumed)?></td>
				<td style="text-align:right"><?=$row->create_date?></td>
			</tr>
		<? endforeach;?>
		</tbody>
	</table>
	<? endif;?>
<? endif;?>