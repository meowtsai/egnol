<?php 
	$channels = $this->config->item('channels');
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
?>
<div id="func_bar">
	
</div>

<ul class="nav nav-tabs">
    <li class="">
        <a href="<?=site_url("user_statistics/new_users?game_id={$this->game_id}&start_date={$start_date}&end_date={$end_date}")?>">新增分析</a>
    </li>
    <li class="">
        <a href="<?=site_url("user_statistics/new_users_by_login?game_id={$this->game_id}&start_date={$start_date}&end_date={$end_date}")?>">新增分析(依登入)</a>
    </li>
    <li class="">
        <a href="<?=site_url("operation_statistics/user_retention?game_id={$this->game_id}&start_date={$start_date}&end_date={$end_date}")?>">留存率</a>
    </li>
    <li class="">
        <a href="<?=site_url("operation_statistics/user_retention_by_login?game_id={$this->game_id}&start_date={$start_date}&end_date={$end_date}")?>">留存率(依登入)</a>
    </li>
    <li class="active">
        <a href="<?=site_url("operation_statistics/user_return?game_id={$this->game_id}&start_date={$start_date}&end_date={$end_date}")?>">回流率</a>
    </li>
    <li class="">
        <a href="<?=site_url("operation_statistics/user_return_by_login?game_id={$this->game_id}&start_date={$start_date}&end_date={$end_date}")?>">回流率(依登入)</a>
    </li>
</ul>

<form method="get" action="<?=site_url("operation_statistics/user_return")?>" class="form-search">
		
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
		
		<input type="submit" class="btn btn-small btn-inverse" name="action" value="回流統計">	
	
	</div>
		
</form>

<? if ($query):?>
	<? if ($query->num_rows() == 0): echo '<div class="none">查無資料</div>'; else: ?>

	<table class="table table-striped table-bordered" style="width:auto;">
		<thead>
			<tr>
				<th nowrap="nowrap">日期</th>
				<th style="width:70px">次日回流</th>
				<th style="width:70px">次日回流率</th>
				<th style="width:70px">3日回流</th>
				<th style="width:70px">3日回流率</th>
				<th style="width:70px">週回流</th>
				<th style="width:70px">週回流率</th>
				<th style="width:70px">月回流</th>
				<th style="width:70px">月回流率</th>
			</tr>
		</thead>
		<tbody>
		<? foreach($query->result() as $row):
            $startdate = strtotime($row->date);
            $enddate = strtotime(date("Y-m-d"));
            $days = floor(($enddate-$startdate)/3600/24);
            $is_sunday = ("7"==date("N", strtotime($row->date)))?true:false;
            $is_endofmonth = ($row->date==date("Y-m-t", strtotime($row->date)))?true:false;?>
			<tr>			
				<td nowrap="nowrap"><?=$row->date?></td>
				<td style="text-align:right;<?=$days<2 ? 'background:#ddd;color:#ddd;' : ''?>"><?=number_format($row->one_return_count)?></td>
				<td style="text-align:right;<?=$days<2 ? 'background:#ddd;color:#ddd;' : ''?>"><?=($row->one_return_rate)?number_format($row->one_return_rate*100,2)."%":"0%"?></td>
				<td style="text-align:right;<?=$days<4 ? 'background:#ddd;color:#ddd;' : ''?>"><?=number_format($row->three_return_count)?></td>
				<td style="text-align:right;<?=$days<4 ? 'background:#ddd;color:#ddd;' : ''?>"><?=($row->three_return_rate)?number_format($row->three_return_rate*100,2)."%":"0%"?></td>
				<td style="text-align:right;<?=!$is_sunday ? 'background:#ddd;color:#ddd;' : ''?>"><?=number_format($row->weekly_return_count)?></td>
				<td style="text-align:right;<?=!$is_sunday ? 'background:#ddd;color:#ddd;' : ''?>"><?=($row->weekly_return_rate)?number_format($row->weekly_return_rate*100,2)."%":"0%"?></td>
				<td style="text-align:right;<?=!$is_endofmonth ? 'background:#ddd;color:#ddd;' : ''?>"><?=number_format($row->monthly_return_count)?></td>
				<td style="text-align:right;<?=!$is_endofmonth ? 'background:#ddd;color:#ddd;' : ''?>"><?=($row->monthly_return_rate)?number_format($row->monthly_return_rate*100,2)."%":"0%"?></td>
			</tr>
		<? endforeach;?>
		</tbody>
	</table>
	<? endif;?>
<? endif;?>