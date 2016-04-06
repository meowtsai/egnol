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
    <li class="">
        <a href="<?=site_url("statistics/deposit_analysis?game_id={$this->game_id}&start_date={$start_date}&end_date={$end_date}")?>">會員儲值分析</a>
    </li>
    <li class="">
        <a href="<?=site_url("statistics/lifetime_value?game_id={$this->game_id}&start_date={$start_date}&end_date={$end_date}")?>">LTV分析</a>
    </li>
    <li class="active">
        <a href="<?=site_url("statistics/game_consumes?game_id={$this->game_id}&start_date={$start_date}&end_date={$end_date}")?>">消費分析</a>
    </li>
</ul>

<form method="get" action="<?=site_url("statistics/game_consumes")?>" class="form-search">
	<input type="hidden" name="game_id" value="<?=$this->game_id?>">
	
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
	
	</div>
	
	<div class="control-group">	

		<input type="submit" class="btn btn-small btn-inverse" name="action" value="消費分析">
			
		<? if ($this->input->get("use_default") == false):?>
		<span class="sptl"></span>	
		<a href="?game_id=<?=$this->game_id?>" class="btn btn-small"><i class="icon-remove"></i> 重置條件</a>
		<? endif;?>
		
	</div>
	
</form>


<? if ( ! empty($query)):?>
	<? if (count($query) == 0): echo '<div class="none">查無資料</div>'; else: ?>
	
<div class="msg">總筆數:<?=count($query)?></div>
<?=tran_pagination($this->pagination->create_links());?>

<table class="table table-striped table-bordered">
	<thead>
		<tr>	
			<th style="width:65px;">道具類別</th>
			<th style="width:65px;">道具名稱</th>
			<th style="width:65px;">累積購買數量</th>
			<th style="width:65px;">虛擬幣價值</th>
			<th style="width:65px;">消耗數量</th>
			<th style="width:65px;">剩餘數量</th>
		</tr>
	</thead>
	<tbody>
		<? foreach($query as $row): ?>
		<tr>
			<td><?=$row->_id->le_contentType?></td>
			<td><?=$row->_id->le_contentId?></td>
			<td><?=$row->le_count?></td>
			<td><?=$row->_id->le_price?></td>
			<td><?=$row->used?></td>
			<td><?=($row->le_count-$row->used)?></td>
		</tr>
		<? endforeach;?>
	</tbody>
</table>
	<? endif;?>
<? endif;?>
