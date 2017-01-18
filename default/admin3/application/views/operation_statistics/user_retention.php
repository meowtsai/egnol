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
        <a href="<?=site_url("user_statistics/new_users?game_id={$this->game_id}&start_date={$start_date}&end_date={$end_date}")?>">新增分析</a>
    </li>
    <li class="">
        <a href="<?=site_url("user_statistics/new_users_by_login?game_id={$this->game_id}&start_date={$start_date}&end_date={$end_date}")?>">新增分析(依登入)</a>
    </li>
    <li class="active">
        <a href="<?=site_url("operation_statistics/user_retention?game_id={$this->game_id}&start_date={$start_date}&end_date={$end_date}")?>">留存率</a>
    </li>
    <li class="">
        <a href="<?=site_url("operation_statistics/user_retention_by_login?game_id={$this->game_id}&start_date={$start_date}&end_date={$end_date}")?>">留存率(依登入)</a>
    </li>
    <li class="">
        <a href="<?=site_url("operation_statistics/user_return?game_id={$this->game_id}&start_date={$start_date}&end_date={$end_date}")?>">回流率</a>
    </li>
    <li class="">
        <a href="<?=site_url("operation_statistics/user_return_by_login?game_id={$this->game_id}&start_date={$start_date}&end_date={$end_date}")?>">回流率(依登入)</a>
    </li>
    <li class="">
        <a href="<?=site_url("user_statistics/user_count_by_country?game_id={$this->game_id}&start_date={$start_date}&end_date={$end_date}")?>">DAU(依國別)</a>
    </li>
</ul>

<form method="get" action="<?=site_url("operation_statistics/user_retention")?>" class="form-search">
	<!--input type="hidden" name="game_id" value="<?=$this->game_id?>"-->
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
		
		<input type="submit" class="btn btn-small btn-inverse" name="action" value="留存率統計">	
	
	</div>
	
	<p class="text-info">
			<span class="label label-info">欄位說明</span>
			廣告參數是使用like, 規則為 like '廣告%'
		</p>	
		
</form>

<? if ($query):?>
	<? if ($query->num_rows() == 0): echo '<div class="none">查無資料</div>'; else: ?>
	<table class="table table-striped table-bordered" style="width:auto;">
		<thead>
			<tr>
				<th nowrap="nowrap" rowspan="2">日期</th>
				<th style="width:70px" rowspan="2">新增登入數</th>
				<th style="width:70px" rowspan="2">新增創角數</th>
				<th style="width:70px" rowspan="2">創角<Br>轉換率</th>
				<th style="width:140px" colspan="2">次日</th>
				<th style="width:140px" colspan="2">3天</th>
				<th style="width:140px" colspan="2">7天</th>
				<th style="width:140px" colspan="2">14天</th>
				<th style="width:140px" colspan="2">30天</th>				 	
			</tr>
			<tr>
				<th>留存數</th><th>留存率</th><th>留存數</th><th>留存率</th><th>留存數</th><th>留存率</th><th>留存數</th><th>留存率</th><th>留存數</th><th>留存率</th>
			</tr>
		</thead>
		<tbody>
		<? $color = array('dd2200', 'aa4400', 'aa4400', 'aa4400', '448800', '448800', '448800', '448800', '00aa00', '00aa00', '00aa00');
			foreach($query->result() as $row):
				$startdate = strtotime($row->date);
				$enddate = strtotime(date("Y-m-d"));
				$days = floor(($enddate-$startdate)/3600/24) ;
                $new_character_p = (($row->new_login_count>0)?$row->new_character_count/$row->new_login_count:0)*100;	
                $one_retention_p = (($row->one_retention_count>0)?$row->one_retention_count/$row->new_login_count:0)*100;
                $three_retention_p = (($row->three_retention_count>0)?$row->three_retention_count/$row->new_login_count:0)*100;
                $seven_retention_p = (($row->seven_retention_count>0)?$row->seven_retention_count/$row->new_login_count:0)*100;
                $fourteen_retention_p = (($row->fourteen_retention_count>0)?$row->fourteen_retention_count/$row->new_login_count:0)*100;
                $thirty_retention_p = (($row->thirty_retention_count>0)?$row->thirty_retention_count/$row->new_login_count:0)*100;	
		?>
		<!--<?=$startdate?> <?=$enddate?> <?=$days?>-->
			<tr>			
				<td nowrap="nowrap"><?=$row->date?></td>
				<td style="text-align:right"><?=number_format($row->new_login_count)?></td>
				<td style="text-align:right"><?=number_format($row->new_character_count)?></td>
				<td style="text-align:right; color:#<?=$color[intval($new_character_p/10)]?>">
				    <?=round($new_character_p, 2)."%"?>
				</td>
				<td style="text-align:right; <?=$days<2 ? 'background:#ddd;color:#ddd;' : ''?>"><?=number_format($row->one_retention_count)?></td>
				<td style="text-align:right; color:#<?=$days<2?'ddd':$color[intval($one_retention_p/10)]?>; <?=$days<2 ? 'background:#ddd;' : ''?>">
				    <?=round($one_retention_p, 2)."%"?>
				</td>
				<td style="text-align:right; <?=$days<4 ? 'background:#ddd;color:#ddd;' : ''?>"><?=number_format($row->three_retention_count)?></td>
				<td style="text-align:right; color:#<?=$days<4?'ddd':$color[intval($three_retention_p/10)]?>; <?=$days<4 ? 'background:#ddd;' : ''?>">
				    <?=round($three_retention_p, 2)."%"?>
				</td>
				<td style="text-align:right; <?=$days<8 ? 'background:#ddd;color:#ddd;' : ''?>"><?=number_format($row->seven_retention_count)?></td>
				<td style="text-align:right; color:#<?=$days<8?'ddd':$color[intval($seven_retention_p/10)]?>; <?=$days<8 ? 'background:#ddd;' : ''?>">
				    <?=round($seven_retention_p, 2)."%"?>
				</td>
				<td style="text-align:right; <?=$days<15 ? 'background:#ddd;color:#ddd;' : ''?>"><?=number_format($row->fourteen_retention_count)?></td>
				<td style="text-align:right; color:#<?=$days<15?'ddd':$color[intval($fourteen_retention_p/10)]?>; <?=$days<15 ? 'background:#ddd;' : ''?>">
				    <?=round($fourteen_retention_p, 2)."%"?>
				</td>	
				<td style="text-align:right; <?=$days<31 ? 'background:#ddd;color:#ddd;' : ''?>"><?=number_format($row->thirty_retention_count)?></td>
				<td style="text-align:right; color:#<?=$days<31?'ddd':$color[intval($thirty_retention_p/10)]?>; <?=$days<31 ? 'background:#ddd;' : ''?>">
				    <?=round($thirty_retention_p, 2)."%"?>
				</td>																		
			</tr>
		<? endforeach;?>
		</tbody>
	</table>
	<? endif;?>
<? endif;?>