<?php
	$channels = $this->config->item('channels'); 
	$c_game_query = $this->db->from("games")->order_by("rank")->get();

	$c_game = array();
	$c_game_menu["獨代"] = array();
	$c_game_menu["聯運"] = array();
	$c_game_menu["關閉"] = array();
	
	$exchange_rate = 1;
	
	foreach($c_game_query->result() as $row) {
		$c_game[$row->game_id] = $row;		
		if (!$row->is_active) {$c_game_menu["關閉"][] = $row; continue;}
		if (strpos($row->tags.",", "聯運,") !== false) {$c_game_menu["聯運"][] = $row; continue;}
		$c_game_menu["獨代"][] = $row;
		
		if ($row->game_id == $game_id) $exchange_rate = $row->exchange_rate;
	}
?>
<div id="func_bar">
	
</div>

<ul class="nav nav-tabs">
    <li class="">
        <a href="<?=site_url("statistics/operation?game_id={$this->game_id}&start_date={$start_date}&end_date={$end_date}")?>">日報表</a>
    </li>
    <li class="">
        <a href="<?=site_url("statistics/operation?game_id={$this->game_id}&start_date={$start_date}&end_date={$end_date}&span=weekly")?>">週報表</a>
    </li>
    <li class="">
        <a href="<?=site_url("statistics/operation?game_id={$this->game_id}&start_date={$start_date}&end_date={$end_date}&span=monthly")?>">月報表</a>
    </li>
    <li class="active">
        <a href="<?=site_url("statistics/deposit_level?game_id={$this->game_id}&start_date={$start_date}&end_date={$end_date}")?>">儲值區間分析</a>
    </li>
    <li class="">
        <a href="<?=site_url("statistics/deposit_analysis?game_id={$this->game_id}&start_date={$start_date}&end_date={$end_date}")?>">會員儲值分析</a>
    </li>
    <li class="">
        <a href="<?=site_url("statistics/lifetime_value?game_id={$this->game_id}&start_date={$start_date}&end_date={$end_date}")?>">LTV分析</a>
    </li>
    <li class="">
        <a href="<?=site_url("statistics/game_consumes?game_id={$this->game_id}&start_date={$start_date}&end_date={$end_date}")?>">消費分析</a>
    </li>
</ul>

<form method="get" action="<?=site_url("statistics/deposit_level")?>" class="form-search">
	<div class="control-group">
		
		<select name="game_id">
		    <option value="">--請選擇遊戲--</option>
			<?
			foreach($c_game_menu as $category => $c_menu):?>
				<option value=""> -------- <?=$category?> --------</option>
				<? foreach($c_menu as $key => $row):?>
				<option value="<?=$row->game_id?>" <?=($game_id==$row->game_id ? 'selected="selected"' : '')?>><?=$row->name?>	</option>
				<? endforeach;?>
			<? $i++;  
			endforeach;?>	
	    </select>
		
		時間
		<input type="text" name="start_date" class="date required" value="<?=($this->input->get("start_date"))?$this->input->get("start_date"):date("Y-m-d",strtotime("-8 days"))?>" style="width:120px"> 至
		<input type="text" name="end_date" class="date" value="<?=$this->input->get("end_date")?>" style="width:120px" placeholder="現在">
		<a href="javascript:;" class="clear_date"><i class="icon-remove-circle" title="清除"></i></a>
		
		<input type="submit" class="btn btn-small btn-inverse" name="action" value="儲值區間分析">	
	
	</div>
		
</form>

<?if ($query):?>
	<? if ($query->num_rows() == 0): echo '<div class="none">查無資料</div>'; else: 
    $row=$query->row();
    ?>
	<table class="table table-striped table-bordered" style="width:auto;">
		<thead>
			<tr>
				<th nowrap="nowrap">遊戲</th>
				<th style="width:70px" colspan="3">0-499</th>
				<th style="width:70px" colspan="3">500-999</th>
				<th style="width:70px" colspan="3">1000-1999</th>
				<th style="width:70px">總人數</th>	
				<th style="width:70px">總儲值</th>			 	
			</tr>
		</thead>
		<tbody>
			<tr>			
				<td nowrap="nowrap" rowspan="5"><?=$game_id?></td>
				<td>人數</td>
				<td>金額</td>
				<td>佔比</td>
				<td>人數</td>
				<td>金額</td>
				<td>佔比</td>
				<td>人數</td>
				<td>金額</td>
				<td>佔比</td>		
				<td nowrap="nowrap" rowspan="5"><?=$row->user_count?></td>
				<td nowrap="nowrap" rowspan="5"><?=$row->total?></td>													
			</tr>
			<tr>			
				<td><?=$row->lvl1?></td>
				<td><?=$row->lvl1_sum?></td>
				<td><?=number_format(($row->user_count)?$row->lvl1/$row->user_count*100:0, 2)."%"?></td>
				<td><?=$row->lvl2?></td>
				<td><?=$row->lvl2_sum?></td>
				<td><?=number_format(($row->user_count)?$row->lvl2/$row->user_count*100:0, 2)."%"?></td>
				<td><?=$row->lvl3?></td>
				<td><?=$row->lvl3_sum?></td>
				<td><?=number_format(($row->user_count)?$row->lvl3/$row->user_count*100:0, 2)."%"?></td>											
			</tr>
			<tr>			
				<td colspan="3"><b>2000-4999</b></td>
				<td colspan="3"><b>5000-1萬</b></td>
				<td colspan="3"><b>1萬以上</b></td>													
			</tr>
			<tr>			
				<td>人數</td>
				<td>金額</td>
				<td>佔比</td>
				<td>人數</td>
				<td>金額</td>
				<td>佔比</td>
				<td>人數</td>
				<td>金額</td>
				<td>佔比</td>														
			</tr>
			<tr>
				<td><?=$row->lvl4?></td>
				<td><?=$row->lvl4_sum?></td>
				<td><?=number_format(($row->user_count)?$row->lvl4/$row->user_count*100:0, 2)."%"?></td>
				<td><?=$row->lvl5?></td>
				<td><?=$row->lvl5_sum?></td>
				<td><?=number_format(($row->user_count)?$row->lvl5/$row->user_count*100:0, 2)."%"?></td>
				<td><?=$row->lvl6?></td>
				<td><?=$row->lvl6_sum?></td>
				<td><?=number_format(($row->user_count)?$row->lvl6/$row->user_count*100:0, 2)."%"?></td>														
			</tr>
		</tbody>
	</table>
	<? endif;?>
<? endif;?>