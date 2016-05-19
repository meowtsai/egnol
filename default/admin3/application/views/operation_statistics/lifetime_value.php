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

<ul class="nav nav-tabs">
    <li class="">
        <a href="<?=site_url("operation_statistics/overview?game_id={$this->game_id}&start_date={$start_date}&end_date={$end_date}")?>">日報表</a>
    </li>
    <li class="">
        <a href="<?=site_url("operation_statistics/overview?game_id={$this->game_id}&start_date={$start_date}&end_date={$end_date}&span=weekly")?>">週報表</a>
    </li>
    <li class="">
        <a href="<?=site_url("operation_statistics/overview?game_id={$this->game_id}&start_date={$start_date}&end_date={$end_date}&span=monthly")?>">月報表</a>
    </li>
    <li class="">
        <a href="<?=site_url("user_statistics/deposit_level?game_id={$this->game_id}&start_date={$start_date}&end_date={$end_date}")?>">儲值區間分析</a>
    </li>
    <li class="">
        <a href="<?=site_url("user_statistics/deposit_analysis?game_id={$this->game_id}&start_date={$start_date}&end_date={$end_date}")?>">會員儲值分析</a>
    </li>
    <li class="active">
        <a href="<?=site_url("operation_statistics/lifetime_value?game_id={$this->game_id}&start_date={$start_date}&end_date={$end_date}")?>">LTV分析</a>
    </li>
    <li class="">
        <a href="<?=site_url("user_statistics/game_consumes?game_id={$this->game_id}&start_date={$start_date}&end_date={$end_date}")?>">消費分析</a>
    </li>
</ul>

<form method="get" action="<?=site_url("operation_statistics/lifetime_value")?>" class="form-search">
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
		
		<input type="submit" class="btn btn-small btn-inverse" name="action" value="LTV分析">	
	
	</div>
		
</form>

<? if ($query):?>
	<? if ($query->num_rows() == 0): echo '<div class="none">查無資料</div>'; else: ?>
	<table class="table table-striped table-bordered" style="width:auto;">
		<thead>
			<tr>
				<th nowrap="nowrap" >首登日期</th>
				<th style="width:100px">1日LTV</th>	
				<th style="width:100px">2日LTV</th>	
				<th style="width:100px">3日LTV</th>	
				<th style="width:100px">4日LTV</th>	
				<th style="width:100px">5日LTV</th>	
				<th style="width:100px">6日LTV</th>	
				<th style="width:100px">7日LTV</th>		
				<th style="width:100px">14日LTV</th>		
				<th style="width:100px">30日LTV</th>		
				<th style="width:100px">60日LTV</th>		
				<th style="width:100px">90日LTV</th>			 	
			</tr>
		</thead>
		<tbody>
		<? $color = array('00aa00', '448800', '776600', 'aa4400', 'dd2200', 'ff0000');
			foreach($query->result() as $row):
		?>
			<tr>			
				<td nowrap="nowrap"><?=$row->date?></td>
				<td style="text-align:right"><?=number_format(($row->new_login_count)?$row->one_ltv/$row->new_login_count:0)?></td>
				<td style="text-align:right"><?=number_format(($row->new_login_count)?$row->two_ltv/$row->new_login_count:0)?></td>
				<td style="text-align:right"><?=number_format(($row->new_login_count)?$row->three_ltv/$row->new_login_count:0)?></td>
				<td style="text-align:right"><?=number_format(($row->new_login_count)?$row->four_ltv/$row->new_login_count:0)?></td>
				<td style="text-align:right"><?=number_format(($row->new_login_count)?$row->five_ltv/$row->new_login_count:0)?></td>
				<td style="text-align:right"><?=number_format(($row->new_login_count)?$row->six_ltv/$row->new_login_count:0)?></td>
				<td style="text-align:right"><?=number_format(($row->new_login_count)?$row->seven_ltv/$row->new_login_count:0)?></td>
				<td style="text-align:right"><?=number_format(($row->new_login_count)?$row->fourteen_ltv/$row->new_login_count:0)?></td>
				<td style="text-align:right"><?=number_format(($row->new_login_count)?$row->thirty_ltv/$row->new_login_count:0)?></td>
				<td style="text-align:right"><?=number_format(($row->new_login_count)?$row->sixty_ltv/$row->new_login_count:0)?></td>
				<td style="text-align:right"><?=number_format(($row->new_login_count)?$row->ninety_ltv/$row->new_login_count:0)?></td>
			</tr>
		<? endforeach;?>
		</tbody>
	</table>
	<? endif;?>
<? endif;?>