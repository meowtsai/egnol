<?php 
	$gash_conf = $this->config->item("gash");
	$channels = $this->config->item('channels');
?>

<form method="get" action="<?=site_url("trade/gash_statistics")?>" class="form-search">

	<div class="control-group">			
		
		遊戲
		<select name="game" style="width:120px;">
			<option value="">--</option>
			<? foreach($games->result() as $row):?>
			<option value="<?=$row->game_id?>" <?=($this->input->get("game")==$row->game_id ? 'selected="selected"' : '')?>><?=$row->name?></option>
			<? endforeach;?>
		</select>
	</div>
	
	<div class="control-group">
		<select name="country" style="width:70px">
			<option value="">--</option>
			<option value="tw" <?=($this->input->get("country")=="tw" ? 'selected="selected"' : '')?>>國內</option>
			<option value="global" <?=($this->input->get("country")=="global" ? 'selected="selected"' : '')?>>海外</option>
		</select>	
		
		<span class="sptl"></span>
			
		交易管道
		<select name="PAID" class="span2">
			<option value="">--</option>
			<? foreach($gash_conf["PAID"] as $key => $channel):?>
			<option value="<?=$key?>" <?=($this->input->get("PAID")==$key ? 'selected="selected"' : '')?>><?=$channel?></option>
			<? endforeach;?>
		</select>	
		
		<span class="sptl"></span>
		
		幣別
		<select name="CUID" style="width:100px;">
			<option value="">--</option>
			<? foreach($gash_conf["CUID"] as $key => $channel):?>
			<option value="<?=$key?>" <?=($this->input->get("CUID")==$key ? 'selected="selected"' : '')?>><?=$channel?></option>
			<? endforeach;?>
		</select>			
		
		<span class="sptl"></span>
		
		轉點時間
		<input type="text" name="start_date" value="<?=$this->input->get("start_date")?>" style="width:120px"> 至
		<input type="text" name="end_date" value="<?=$this->input->get("end_date")?>" style="width:120px" placeholder="現在">
		<a href="javascript:;" class="clear_date"><i class="icon-remove-circle" title="清除"></i></a>
		
	</div>
	
	<div class="control-group">		
		
		<input type="submit" class="btn btn-small btn-inverse" name="action" value="交易管道統計">
		
		<span class="sptl"></span>	
		
		<select name="time_unit" style="width:80px">
			<option value="hour" <?=($this->input->get("time_unit")=='hour' ? 'selected="selected"' : '')?>>時</option>
			<option value="day" <?=($this->input->get("time_unit")=='day' ? 'selected="selected"' : '')?>>日</option>
			<option value="month" <?=($this->input->get("time_unit")=='month' ? 'selected="selected"' : '')?>>月</option>
			<option value="year" <?=($this->input->get("time_unit")=='year' ? 'selected="selected"' : '')?>>年</option>
		</select>		
		
		<input type="submit" class="btn btn-small btn-inverse" name="action" value="時段統計">
				
		<? if ($this->input->get("use_default") == false):?>
		<span class="sptl"></span>	
		<a href="?" class="btn btn-small"><i class="icon-remove"></i> 清除條件</a>
		<? endif;?>
		
	</div>
	
	<p class="text-info">
		<span class="label label-info">說明</span>
		統計數據來源不含測試帳號
	</p>		
	
</form>

<? if ( ! empty($query)):?>
	<? if ($query->num_rows() == 0): echo '<div class="none">查無資料</div>'; else: 
	?>
	
		<? 
		switch ($this->input->get("action")) 
		{
			case "交易管道統計":
				
				$field = array('交易管道');
				$table = array();				
				
				foreach($query->result() as $row) 
				{
					$title = $row->title;
					foreach ($gash_conf["PAID"] as $key => $chnnel) {
						if (strpos($row->title, $key) === 0) {
							$title = $chnnel;
							break;
						}
					}
					$field[$row->CUID] = $gash_conf["CUID"][$row->CUID];
					$table[$title][$row->CUID] = $row->cnt;					
				}
				echo output_statistics_table($field, $table, true);
				
				break;
							
			case "時段統計":
				
				$field = array('時段');
				$table = array();
				
				foreach($query->result() as $row) {					
					$field[$row->CUID] = $gash_conf["CUID"][$row->CUID];
					$table[$row->title][$row->CUID] = $row->cnt;				
				}				
						
					
				echo output_statistics_table($field, $table);
				
				break;		
			} 			
		?>
	<? endif;?>
<? endif;?>

