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
    
    if (empty($action)) $action='營運數據';
?>
<div id="func_bar">
	
</div>

<ul class="nav nav-tabs">
    <li class="<?=(empty($span)) ? "active" : ""?>">
        <a href="<?=site_url("statistics/operation?game_id={$this->game_id}&start_date={$start_date}&end_date={$end_date}")?>">日報表</a>
    </li>
    <li class="<?=($span=='weekly') ? "active" : ""?>">
        <a href="<?=site_url("statistics/operation?game_id={$this->game_id}&start_date={$start_date}&end_date={$end_date}&span=weekly")?>">週報表</a>
    </li>
    <li class="<?=($span=='monthly') ? "active" : ""?>">
        <a href="<?=site_url("statistics/operation?game_id={$this->game_id}&start_date={$start_date}&end_date={$end_date}&span=monthly")?>">月報表</a>
    </li>
    <li class="">
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

<form method="get" action="<?=site_url("statistics/operation")?>" class="form-search">
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
		
		<input type="submit" class="btn btn-small btn-inverse" name="action" value="營運數據">	
	
	</div>

<?if ($query):?>
	<? if ($query->num_rows() == 0): echo '<div class="none">查無資料</div>'; else: ?>
    
    <div><img src="<?=base_url()?>/p/jpgraphs/<?=$span?>_operation_graph" alt=""></div>
    <div>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <input type="submit" class="btn btn-small <?=($this->input->get("action")=='註冊' || $this->input->get("action")=='營運數據')?'btn-inverse':''?>" name="action" value="註冊">
        <input type="submit" class="btn btn-small <?=($this->input->get("action")=='登入用戶')?'btn-inverse':''?>" name="action" value="登入用戶">
        <input type="submit" class="btn btn-small <?=($this->input->get("action")=='付費比')?'btn-inverse':''?>" name="action" value="付費比">
        <input type="submit" class="btn btn-small <?=($this->input->get("action")=='儲值人數')?'btn-inverse':''?>" name="action" value="儲值人數">
        <input type="submit" class="btn btn-small <?=($this->input->get("action")=='ARPPU')?'btn-inverse':''?>" name="action" value="ARPPU">
        <input type="submit" class="btn btn-small <?=($this->input->get("action")=='日營收')?'btn-inverse':''?>" name="action" value="日營收">
        <input type="submit" class="btn btn-small <?=($this->input->get("action")=='註冊留存')?'btn-inverse':''?>" name="action" value="註冊留存">
        <input type="submit" class="btn btn-small <?=($this->input->get("action")=='行銷花費')?'btn-inverse':''?>" name="action" value="行銷花費">
        <input type="submit" class="btn btn-small <?=($this->input->get("action")=='新用戶付費')?'btn-inverse':''?>" name="action" value="新用戶付費">
        <input type="submit" class="btn btn-small <?=($this->input->get("action")=='付費ROI')?'btn-inverse':''?>" name="action" value="付費ROI">
        <input type="submit" class="btn btn-small <?=($this->input->get("action")=='整體ROI')?'btn-inverse':''?>" name="action" value="整體ROI">
    </div>
    <div>&nbsp;</div>
    
	<table class="table table-striped table-bordered" style="width:auto;">
		<thead>
			<tr>
				<th nowrap="nowrap">日期</th>
				<th style="width:70px">註冊</th>
				<th style="width:70px">登入用戶</th>
				<th style="width:70px">付費比</th>
				<th style="width:70px">儲值人數</th>
				<th style="width:70px">ARPPU</th>
				<th style="width:70px">日營收</th>
				<th style="width:70px">註冊留存</th>
				<th style="width:70px">行銷花費</th>
				<th style="width:70px">新用戶付費</th>
				<th style="width:70px">付費ROI</th>
				<th style="width:70px">整體ROI</th>			 	
			</tr>
		</thead>
		<tbody>
		<? $color = array('00aa00', '448800', '776600', 'aa4400', 'dd2200', 'ff0000');
			foreach($query->result() as $row):
				$startdate = strtotime($row->date);
				$enddate = time();
				$days = round(($enddate-$startdate)/3600/24) ; 
				
				switch($this->input->get("span")) {
					case "weekly":
						$year = substr($row->date, 0, 4);
						$week = substr($row->date, 4, 2);
						$show_date =  date("Y-m-d",strtotime($year."W".sprintf('%02d', $week))) 
						      . "~" . date("m-d",strtotime($year."W".sprintf('%02d',$week)."7"));
						break;
					
					case "monthly":
						$show_date = $row->year . "-" . $row->date;
						break;
						
					default:
						$show_date = $row->date;
						break;
				}
				
				$y_one_retention_p = (($row->y_new_login_count)?$row->y_one_retention_count/$row->y_new_login_count*100:0);
		?>
			<tr>			
				<td nowrap="nowrap"><?=$show_date?></td>
				<td style="text-align:right"><?=number_format($row->new_login_count)?></td>
				<td style="text-align:right"><?=number_format($row->login_count)?></td>
				<td style="text-align:right"><?=number_format(($row->login_count)?$row->deposit_user_count/$row->login_count*100:0, 2)."%"?></td>
				<td style="text-align:right"><?=number_format($row->deposit_user_count)?></td>
				<td style="text-align:right"><?=number_format(($row->deposit_user_count)?$row->deposit_total/$row->deposit_user_count:0, 2)?></td>
				<td style="text-align:right"><?=number_format($row->deposit_total)?></td>
				<td style="text-align:right"><?=number_format($y_one_retention_p, 2)."%"?></td>
				<td style="text-align:right"><?=number_format(0)?></td>
				<td style="text-align:right"><?=number_format($row->new_user_deposit_total)?></td>
				<td style="text-align:right"><?=number_format(0)?></td>
				<td style="text-align:right"><?=number_format(0)?></td>														
			</tr>
		<? endforeach;?>
		</tbody>
	</table>
	<? endif;?>
<? endif;?>

</form>