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
    <li class="<?=empty($span) ? "active" : ""?>">
        <a href="<?=site_url("statistics/operation?game_id={$this->game_id}")?>">日報表</a>
    </li>
    <li class="<?=($span=='weekly') ? "active" : ""?>">
        <a href="<?=site_url("statistics/operation?game_id={$this->game_id}&span=weekly")?>">週報表</a>
    </li>
    <li class="<?=($span=='monthly') ? "active" : ""?>">
        <a href="<?=site_url("statistics/operation?game_id={$this->game_id}&span=monthly")?>">月報表</a>
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
	
	<p class="text-info">
			<span class="label label-info">欄位說明</span>
			廣告參數是使用like, 規則為 like '廣告%'
		</p>	
		
</form>

<?
    switch ($this->input->get("span")) {
		case "weekly":
			$retention_string          = "前1週新增用戶週回訪數";
			$retention_rate_string     = "前1週新增用戶週回訪率";
			$retention_all_string      = "前1週登入用戶週回訪數";
			$retention_all_rate_string = "前1週登入用戶週回訪率";
			break;
			
		case "monthly":
			$retention_string          = "前1月新增用戶月回訪數";
			$retention_rate_string     = "前1月新增用戶月回訪率";
			$retention_all_string      = "前1月登入用戶月回訪數";
			$retention_all_rate_string = "前1月登入用戶月回訪率";
			break;
			
		default:
			$retention_string          = "前1日新增用戶次日留存";
			$retention_rate_string     = "前1日新增用戶次日留存率";
			$retention_all_string      = "前1日登入用戶次日留存";
			$retention_all_rate_string = "前1日登入用戶次日留存率";
			break;
	}
    if ($query):?>
	<? if ($query->num_rows() == 0): echo '<div class="none">查無資料</div>'; else: ?>
	<table class="table table-striped table-bordered" style="width:auto;">
		<thead>
			<tr>
				<th nowrap="nowrap">日期</th>
				<th style="width:70px">新增用戶</th>
				<th style="width:70px">登入用戶</th>
				<th style="width:70px">登入設備</th>
				<th style="width:70px"><?=$retention_string?></th>
				<th style="width:70px"><?=$retention_rate_string?></th>
				<th style="width:70px"><?=$retention_all_string?></th>
				<th style="width:70px"><?=$retention_all_rate_string?></th>
				<th style="width:70px">新增儲值用戶</th>	
				<th style="width:70px">儲值用戶</th>
				<th style="width:70px">付費比</th>
				<th style="width:70px">新增消費用戶</th>
				<th style="width:70px">消費用戶</th>
				<th style="width:70px">商城幣總金額</th>
				<th style="width:70px">商城幣儲值金額</th>
				<th style="width:70px">儲值台幣</th>
				<th style="width:70px">消費台幣</th>
				<th style="width:70px">儲值金額ARPU</th>
				<th style="width:70px">儲值金額ARPPU</th>
				<th style="width:70px">消費金額ARPU</th>
				<th style="width:70px">峰值在線</th>
				<th style="width:70px">全用戶平均在線時長(H)</th>
				<th style="width:70px">儲值用戶平均在線時長(H)</th>
				<th style="width:70px">千人日登入用戶收益</th>				 	
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
				$y_one_retention_all_p = (($row->y_login_count)?$row->y_one_retention_all_count/$row->y_login_count*100:0);
		?>
			<tr>			
				<td nowrap="nowrap"><?=$show_date?></td>
				<td style="text-align:right"><?=number_format($row->new_login_count)?></td>
				<td style="text-align:right"><?=number_format($row->login_count)?></td>
				<td style="text-align:right"><?=number_format($row->device_count)?></td>
				<td style="text-align:right"><?=number_format($row->y_one_retention_count)?></td>
				<td style="text-align:right"><?=number_format($y_one_retention_p, 2)."%"?></td>
				<td style="text-align:right"><?=number_format($row->y_one_retention_all_count)?></td>
				<td style="text-align:right"><?=number_format($y_one_retention_all_p, 2)."%"?></td>
				<td style="text-align:right"><?=number_format($row->new_deposit_user_count)?></td>
				<td style="text-align:right"><?=number_format($row->deposit_user_count)?></td>
				<td style="text-align:right"><?=number_format(($row->login_count)?$row->deposit_user_count/$row->login_count*100:0, 2)."%"?></td>
				<td style="text-align:right"><?=number_format($row->new_consume_user_count)?></td>
				<td style="text-align:right"><?=number_format($row->consume_user_count)?></td>
				<td style="text-align:right"><?=number_format($row->currency_total)?></td>
				<td style="text-align:right"><?=number_format($exchange_rate*$row->deposit_total)?></td>
				<td style="text-align:right"><?=number_format($row->deposit_total)?></td>
				<td style="text-align:right"><?=number_format($row->consume_total)?></td>
				<td style="text-align:right"><?=number_format(($row->login_count)?$row->deposit_total/$row->login_count:0, 2)?></td>
				<td style="text-align:right"><?=number_format(($row->deposit_user_count)?$row->deposit_total/$row->deposit_user_count:0, 2)?></td>
				<td style="text-align:right"><?=number_format(($row->consume_user_count)?$row->consume_total/$row->consume_user_count:0, 2)?></td>
				<td style="text-align:right"><?=number_format($row->peak_user_count)?></td>
				<td style="text-align:right"><?=number_format(($row->login_count)?$row->total_time/$row->login_count/3600:0, 2)?></td>
				<td style="text-align:right"><?=number_format(($row->deposit_user_count)?$row->paid_total_time/$row->deposit_user_count/3600:0, 2)?></td>
				<td style="text-align:right"><?=number_format(($row->login_count)?$row->deposit_total/$row->login_count*1000:0, 2)?></td>																
			</tr>
		<? endforeach;?>
		</tbody>
	</table>
	<? endif;?>
<? endif;?>