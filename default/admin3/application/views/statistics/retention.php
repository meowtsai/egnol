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

<form method="get" action="<?=site_url("statistics/retention")?>" class="form-search">
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
				<th style="width:70px" rowspan="2">登入數</th>
				<th style="width:70px" rowspan="2">創角數</th>
				<th style="width:70px" rowspan="2">創角<Br>留存率</th>
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
		<? $color = array('00aa00', '448800', '776600', 'aa4400', 'dd2200', 'ff0000');
			foreach($query->result() as $row):
				$startdate = strtotime($row->date);
				$enddate = strtotime(date("Y-m-d"));
				$days = round(($enddate-$startdate)/3600/24) ;
                $new_character_p = (($row->new_login_count>0)?$row->new_character_count/$row->new_login_count:0)*100;	
                $one_retention_p = (($row->one_retention_count>0)?$row->one_retention_count/$row->new_login_count:0)*100;
                $three_retention_p = (($row->three_retention_count>0)?$row->three_retention_count/$row->new_login_count:0)*100;
                $seven_retention_p = (($row->seven_retention_count>0)?$row->seven_retention_count/$row->new_login_count:0)*100;
                $fourteen_retention_p = (($row->fourteen_retention_count>0)?$row->fourteen_retention_count/$row->new_login_count:0)*100;
                $thirty_retention_p = (($row->thirty_retention_count>0)?$row->thirty_retention_count/$row->new_login_count:0)*100;	
		?>
			<tr>			
				<td nowrap="nowrap"><?=$row->date?></td>
				<td style="text-align:right"><?=number_format($row->new_login_count)?></td>
				<td style="text-align:right"><?=number_format($row->new_character_count)?></td>
				<td style="text-align:right; color:#<?=$color[intval($new_character_p/18)]?>">
				    <?=round($new_character_p, 2)."%"?>
				</td>
				<td style="text-align:right; <?=$days<1 ? 'background:#ddd;' : ''?>"><?=number_format($row->one_retention_count)?></td>
				<td style="text-align:right; color:#<?=$color[intval($one_retention_p/18)]?>; <?=$days<1 ? 'background:#ddd;' : ''?>">
				    <?=round($one_retention_p, 2)."%"?>
				</td>
				<td style="text-align:right; <?=$days<3 ? 'background:#ddd;' : ''?>"><?=number_format($row->three_retention_count)?></td>
				<td style="text-align:right; color:#<?=$color[intval($three_retention_p/18)]?>; <?=$days<3 ? 'background:#ddd;' : ''?>">
				    <?=round($three_retention_p, 2)."%"?>
				</td>
				<td style="text-align:right; <?=$days<7 ? 'background:#ddd;' : ''?>"><?=number_format($row->seven_retention_count)?></td>
				<td style="text-align:right; color:#<?=$color[intval($seven_retention_p/18)]?>; <?=$days<7 ? 'background:#ddd;' : ''?>">
				    <?=round($seven_retention_p, 2)."%"?>
				</td>
				<td style="text-align:right; <?=$days<14 ? 'background:#ddd;' : ''?>"><?=number_format($row->fourteen_retention_count)?></td>
				<td style="text-align:right; color:#<?=$color[intval($fourteen_retention_p/18)]?>; <?=$days<14 ? 'background:#ddd;' : ''?>">
				    <?=round($fourteen_retention_p, 2)."%"?>
				</td>	
				<td style="text-align:right; <?=$days<30 ? 'background:#ddd;' : ''?>"><?=number_format($row->thirty_retention_count)?></td>
				<td style="text-align:right; color:#<?=$color[intval($thirty_retention_p/18)]?>; <?=$days<30 ? 'background:#ddd;' : ''?>">
				    <?=round($thirty_retention_p, 2)."%"?>
				</td>																		
			</tr>
		<? endforeach;?>
		</tbody>
	</table>
	<? endif;?>
<? endif;?>