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
    <li class="">
        <a href="<?=site_url("statistics/deposit_level?game_id={$this->game_id}&start_date={$start_date}&end_date={$end_date}")?>">儲值區間分析</a>
    </li>
    <li class="active">
        <a href="<?=site_url("statistics/deposit_analysis?game_id={$this->game_id}&start_date={$start_date}&end_date={$end_date}")?>">會員儲值分析</a>
    </li>
    <li class="">
        <a href="<?=site_url("statistics/lifetime_value?game_id={$this->game_id}&start_date={$start_date}&end_date={$end_date}")?>">LTV分析</a>
    </li>
    <li class="">
        <a href="<?=site_url("statistics/game_consumes?game_id={$this->game_id}&start_date={$start_date}&end_date={$end_date}")?>">消費分析</a>
    </li>
</ul>

<form method="get" action="<?=site_url("statistics/deposit_analysis")?>" class="form-search">
	<!--input type="hidden" name="game_id" value="<?=$this->input->get("span")?>"-->
	<input type="hidden" name="span" value="<?=$this->input->get("span")?>">
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
		
		<input type="submit" class="btn btn-small btn-inverse" name="action" value="會員儲值分析">	
	
	</div>
		
</form>

<?
    if ($query):?>
	<? if ($query->num_rows() == 0): echo '<div class="none">查無資料</div>'; else: ?>
	<table class="table table-striped table-bordered" style="width:auto;">
		<thead>
			<tr>
				<th colspan="2" rowspan="2">日期</th>
				<th colspan="3">付費比</th>
				<th colspan="3">ARPPU</th>
				<th colspan="3">ARPU</th>			 	
			</tr>
			<tr>
				<th>NEW</th>
				<th>OLD</th>
				<th>TOTAL</th>
				<th>NEW</th>
				<th>OLD</th>
				<th>TOTAL</th>
				<th>NEW</th>
				<th>OLD</th>
				<th>TOTAL</th>
			</tr>
		</thead>
		<tbody>
		<? $color = array('00aa00', '448800', '776600', 'aa4400', 'dd2200', 'ff0000');
			foreach($query->result() as $row):
				
				$old_login_count=$row->login_count-$row->new_login_count;
				$old_user_deposit_count=$row->deposit_user_count-$row->new_user_deposit_count;
				$old_user_deposit_total=$row->deposit_total-$row->new_user_deposit_total;
		?>  
			<tr>			
				<td nowrap="nowrap"><?=$row->date?></td>
				<td nowrap="nowrap"><?=date('D', strtotime($row->date))?></td>
				<td style="text-align:right"><?=number_format(($row->new_login_count)?$row->new_user_deposit_count/$row->new_login_count*100:0, 2)."%"?></td>
				<td style="text-align:right"><?=number_format(($old_login_count)?$old_user_deposit_count/$old_login_count*100:0, 2)."%"?></td>
				<td style="text-align:right"><?=number_format(($row->login_count)?$row->deposit_user_count/$row->login_count*100:0, 2)."%"?></td>
				<td style="text-align:right"><?=number_format(($row->new_user_deposit_count)?$row->new_user_deposit_total/$row->new_user_deposit_count:0, 2)?></td>
				<td style="text-align:right"><?=number_format(($old_user_deposit_count)?$old_user_deposit_total/$old_user_deposit_count:0, 2)?></td>
				<td style="text-align:right"><?=number_format(($row->deposit_user_count)?$row->deposit_total/$row->deposit_user_count:0, 2)?></td>	
				<td style="text-align:right"><?=number_format(($row->new_login_count)?$row->new_user_deposit_total/$row->new_login_count:0, 2)?></td>
				<td style="text-align:right"><?=number_format(($old_login_count)?$old_user_deposit_total/$old_login_count:0, 2)?></td>
				<td style="text-align:right"><?=number_format(($row->login_count)?$row->deposit_total/$row->login_count:0, 2)?></td>													
			</tr>
		<? endforeach;?>
		</tbody>
	</table>
	<? endif;?>
<? endif;?>