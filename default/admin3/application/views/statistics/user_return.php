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

<form method="get" action="<?=site_url("statistics/user_return")?>" class="form-search">
		
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
		<? foreach($query->result() as $row):?>
			<tr>			
				<td nowrap="nowrap"><?=$row->date?></td>
				<td style="text-align:right"><?=($row->one_return_count)?number_format($row->one_return_count):""?></td>
				<td style="text-align:right"><?=($row->one_return_percentage)?number_format($row->one_return_percentage)."%":""?></td>
				<td style="text-align:right"><?=($row->three_return_count)?number_format($row->three_return_count):""?></td>
				<td style="text-align:right"><?=($row->three_return_percentage)?number_format($row->three_return_percentage)."%":""?></td>
				<td style="text-align:right"><?=($row->weekly_return_count)?number_format($row->weekly_return_count):""?></td>
				<td style="text-align:right"><?=($row->weekly_return_percentage)?number_format($row->weekly_return_percentage)."%":""?></td>
				<td style="text-align:right"><?=($row->monthly_return_count)?number_format($row->monthly_return_count):""?></td>
				<td style="text-align:right"><?=($row->monthly_return_percentage)?number_format($row->monthly_return_percentage)."%":""?></td>
			</tr>
		<? endforeach;?>
		</tbody>
	</table>
	<? endif;?>
<? endif;?>

<h4>帳號地區分析</h4>
<div><img src="<?=base_url()?>/p/region_graph" alt=""></div>