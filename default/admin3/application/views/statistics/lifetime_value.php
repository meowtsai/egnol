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
        <a href="<?=site_url("statistics/revenue")?>">日報表</a>
    </li>
    <li class="">
        <a href="<?=site_url("statistics/revenue?span=weekly")?>">週報表</a>
    </li>
    <li class="">
        <a href="<?=site_url("statistics/revenue?span=monthly")?>">月報表</a>
    </li>
    <li class="">
        <a href="<?=site_url("statistics/deposit_behavior")?>">儲值習慣</a>
    </li>
    <li class="active">
        <a href="<?=site_url("statistics/lifetime_value")?>">玩家價值</a>
    </li>
</ul>

<form method="get" action="<?=site_url("statistics/lifetime_value")?>" class="form-search">
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
		
		<input type="submit" class="btn btn-small btn-inverse" name="action" value="玩家價值統計">	
	
	</div>
		
</form>

<? if ($query):?>
	<? if ($query->num_rows() == 0): echo '<div class="none">查無資料</div>'; else: ?>
	<table class="table table-striped table-bordered" style="width:auto;">
		<thead>
			<tr>
				<th nowrap="nowrap" >首登日期</th>
				<th style="width:140px">7日LTV</th>		
				<th style="width:140px">14日LTV</th>		
				<th style="width:140px">30日LTV</th>		
				<th style="width:140px">60日LTV</th>		
				<th style="width:140px">90日LTV</th>			 	
			</tr>
		</thead>
		<tbody>
		<? $color = array('00aa00', '448800', '776600', 'aa4400', 'dd2200', 'ff0000');
			foreach($query->result() as $row):
		?>
			<tr>			
				<td nowrap="nowrap"><?=$row->date?></td>
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