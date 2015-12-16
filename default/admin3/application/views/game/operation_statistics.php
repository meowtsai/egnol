<?php 
	$c_game_query = $this->db->from("games")->order_by("rank")->get();

	$c_game = array();
	$c_game_menu["獨代"] = array();
	$c_game_menu["聯運"] = array();
	$c_game_menu["關閉"] = array();
	
	foreach($c_game_query->result() as $row) {
		$c_game[$row->game_id] = $row;		
		if ($row->is_active == 0) {$c_game_menu["關閉"][] = $row; continue;}
		if (strpos($row->tags.",", "聯運,") !== false) {$c_game_menu["聯運"][] = $row; continue;}
		$c_game_menu["獨代"][] = $row;
	}
	
	$channels = $this->config->item('channels');
	$game_id = ($this->input->get("game_id_1")?$this->input->get("game_id_1"):($this->input->get("game_id_2")?$this->input->get("game_id_2"):($this->input->get("game_id_3")?$this->input->get("game_id_3"):'')));
?>
<div id="func_bar">
	
</div>

<form method="get" action="<?=site_url("game/retention_statistics")?>" class="form-search">
	<!--input type="hidden" name="game_id" value="<?=$this->game_id?>"-->
	<div class="control-group">
		
		<? $i = 1; 
		foreach($c_game_menu as $category => $c_menu):?>
	        <?=$category?>
	        <select name="game_id_<?=$i?>">
		        <option value="">--</option>
		        <? foreach($c_menu as $key => $row):?>
		        <option value="<?=$row->game_id?>" <?=($game_id==$row->game_id ? 'selected="selected"' : '')?>><?=$row->name?>	</option>
		        <? endforeach;?>
	        </select>
        <? $i++;  
		endforeach;?>	
		
		時間
		<input type="text" name="start_date" class="date required" value="<?=$this->input->get("start_date")?>" style="width:120px"> 至
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
				<th style="width:140px" colspan="2">隔天</th>
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
				$startdate = strtotime($row->d);
				$enddate = time();
				$days = round(($enddate-$startdate)/3600/24) ; 
		?>
			<tr>			
				<td nowrap="nowrap"><?=$row->d?></td>
				<td style="text-align:right"><?=number_format($row->login_cnt)?></td>
				<td style="text-align:right"><?=number_format($row->role_cnt)?></td>
				<td style="text-align:right; color:#<?=$color[intval(($row->role_p*100)/18)]?>"><?=($row->role_p*100)."%"?></td>
				<td style="text-align:right; <?=$days<2 ? 'background:#ddd;' : ''?>"><?=number_format($row->c1)?></td>
				<td style="text-align:right; color:#<?=$color[intval(($row->c1_p*100)/18)]?>; <?=$days<2 ? 'background:#ddd;' : ''?>"><?=($row->c1_p*100)."%"?></td>
				<td style="text-align:right; <?=$days<2 ? 'background:#ddd;' : ''?>"><?=number_format($row->c3)?></td>
				<td style="text-align:right; color:#<?=$color[intval(($row->c3_p*100)/18)]?>; <?=$days<3 ? 'background:#ddd;' : ''?>"><?=($row->c3_p*100)."%"?></td>
				<td style="text-align:right; <?=$days<7 ? 'background:#ddd;' : ''?>"><?=number_format($row->c7)?></td>
				<td style="text-align:right; color:#<?=$color[intval(($row->c7_p*100)/18)]?>; <?=$days<7 ? 'background:#ddd;' : ''?>"><?=($row->c7_p*100)."%"?></td>
				<td style="text-align:right; <?=$days<15 ? 'background:#ddd;' : ''?>"><?=number_format($row->c14)?></td>
				<td style="text-align:right; color:#<?=$color[intval(($row->c14_p*100)/18)]?>; <?=$days<14 ? 'background:#ddd;' : ''?>"><?=($row->c14_p*100)."%"?></td>	
				<td style="text-align:right; <?=$days<30 ? 'background:#ddd;' : ''?>"><?=number_format($row->c30)?></td>
				<td style="text-align:right; color:#<?=$color[intval(($row->c30_p*100)/18)]?>; <?=$days<30 ? 'background:#ddd;' : ''?>"><?=($row->c30_p*100)."%"?></td>																		
			</tr>
		<? endforeach;?>
		</tbody>
	</table>
	<? endif;?>
<? endif;?>