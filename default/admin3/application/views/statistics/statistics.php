<?php 
	$channels = $this->config->item('channels');
?>
<div id="func_bar">
	
</div>

<form method="get" action="<?=site_url("game/statistics")?>" class="form-search">
	<input type="hidden" name="game_id" value="<?=$this->game_id?>">
		
	<div class="control-group">
		
		伺服器
		<select name="server" style="width:90px">
			<option value="">--請選擇伺服器--</option>
			<? foreach($servers->result() as $row):?>
			<option value="<?=$row->id?>" <?=($this->input->get("server")==$row->id ? 'selected="selected"' : '')?>><?=$row->name?></option>
			<? endforeach;?>
		</select>		
		
		<span class="sptl"></span> 
		
		通路來源 
		<select name="channel" style="width:120px">
			<option value="">--請選擇通路--</option>
			<? foreach($channels as $key => $channel):?>
			<option value="<?=$key?>" <?=($this->input->get("channel")==$key ? 'selected="selected"' : '')?>><?=$channel?></option>
			<? endforeach;?>
		</select>		
		
		<input type="text" name="ad_channel" value="<?=$this->input->get("ad_channel")?>" style="width:90px;" placeholder="廣告參數">
		
		<span class="sptl"></span> 
		
		時間
		<input type="text" name="start_date" class="date required" value="<?=($this->input->get("start_date"))?$this->input->get("start_date"):date("Y-m-d",strtotime("-8 days"))?>" style="width:120px"> 至
		<input type="text" name="end_date" class="date" value="<?=$this->input->get("end_date")?>" style="width:120px" placeholder="現在">
		<a href="javascript:;" class="clear_date"><i class="icon-remove-circle" title="清除"></i></a>
		
		<input type="submit" class="btn btn-small btn-inverse" name="action" value="流失率統計">	
	
	</div>
	
	<p class="text-info">
			<span class="label label-info">欄位說明</span>
			廣告參數是使用like, 規則為 like '廣告%'
		</p>	
		
</form>

<? if ($query):?>
	<? if ($query->num_rows() == 0): echo '<div class="none">查無資料</div>'; else: ?>
	
		<? 
		switch ($this->input->get("action")) 
		{			
			case "流失率統計":			
?>
	<table class="table table-striped table-bordered" style="width:auto;">
		<thead>
			<tr>
				<th nowrap="nowrap" rowspan="2">日期</th>
				<th style="width:70px" rowspan="2">登入數</th>
				<th style="width:70px" rowspan="2">創角數</th>
				<th style="width:70px" rowspan="2">創角<Br>流失率</th>
				<th style="width:140px" colspan="2">隔天</th>
				<th style="width:140px" colspan="2">7天</th>
				<th style="width:140px" colspan="2">15天</th>
				<th style="width:140px" colspan="2">30天</th>				 	
			</tr>
			<tr>
				<th>流失數</th><th>流失率</th><th>流失數</th><th>流失率</th><th>流失數</th><th>流失率</th><th>流失數</th><th>流失率</th>
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
				<td style="text-align:right; <?=$days<7 ? 'background:#ddd;' : ''?>"><?=number_format($row->c7)?></td>
				<td style="text-align:right; color:#<?=$color[intval(($row->c7_p*100)/18)]?>; <?=$days<7 ? 'background:#ddd;' : ''?>"><?=($row->c7_p*100)."%"?></td>
				<td style="text-align:right; <?=$days<15 ? 'background:#ddd;' : ''?>"><?=number_format($row->c15)?></td>
				<td style="text-align:right; color:#<?=$color[intval(($row->c15_p*100)/18)]?>; <?=$days<15 ? 'background:#ddd;' : ''?>"><?=($row->c15_p*100)."%"?></td>	
				<td style="text-align:right; <?=$days<30 ? 'background:#ddd;' : ''?>"><?=number_format($row->c30)?></td>
				<td style="text-align:right; color:#<?=$color[intval(($row->c30_p*100)/18)]?>; <?=$days<30 ? 'background:#ddd;' : ''?>"><?=($row->c30_p*100)."%"?></td>																		
			</tr>
		<? endforeach;?>
		</tbody>
	</table>
<? 
				break;			
			
			case "廣告時段統計":
				
				$field = array('廣告');
				$table = array();				
				
				foreach($query->result() as $row) 
				{			
					$title = $row->time;
					
					$ad_channels['long_e'] = "自然增長";
					$key = empty($row->ad) ? 'long_e' : $row->ad;					
					if (array_key_exists($key, $ad_channels)) {
						$ad = $ad_channels[$key];
					} else $ad = $key;
					
					$field[$key] = $ad;
					$table[$title][$key] = $row->cnt;	
				}
				echo output_statistics_table($field, $table);				
		?>
		
		
		<? } ?>
	<? endif;?>
<? endif;?>