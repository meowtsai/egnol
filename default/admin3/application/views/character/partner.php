
<form method="get" action="<?=site_url($this->uri->uri_string())?>" class="form-search">
	<input type="hidden" name="game_id" value="<?=$this->game_id?>">
	
	<div class="control-group">
	
		遊戲
		<select name="game" style="width:120px;">
			<option value="">--</option>
			<? foreach($games->result() as $row):
				if ($this->zacl->check_acl("all_game", "all") == false) {
					if ($this->zacl->check_acl($row->game_id, "read") == false) continue;
				}
			?>
			<option value="<?=$row->game_id?>" <?=($this->input->get("game")==$row->game_id ? 'selected="selected"' : '')?>><?=$row->name?></option>
			<? endforeach;?>
		</select>	
	
<!-- 		伺服器 -->
<!-- 		<select name="server" style="width:120px;"> -->
<!-- 			<option value="">--</option> -->
<!-- 		</select> -->
		
		<select id="server_pool" style="display:none;">
			<? foreach($servers->result() as $row):?>
			<option value="<?=$row->id?>" <?=($this->input->get("server")==$row->id ? 'selected="selected"' : '')?> class="<?=$row->game_id?>"><?=$row->name?></option>
			<? endforeach;?>
		</select>
		
		<span class="sptl"></span>
		
		建檔時間
		<input type="text" name="start_date" value="<?=$this->input->get("start_date")?>" style="width:120px"> 至
		<input type="text" name="end_date" value="<?=$this->input->get("end_date")?>" style="width:120px" placeholder="現在">
		<a href="javascript:;" class="clear_date"><i class="icon-remove-circle" title="清除"></i></a>
		
		
		
<!-- 		顯示 
<span class="sptl"></span>
		<select name="display_game" style="width:120px">
			<option value="game" <?=($this->input->get("display_game")=='game' ? 'selected="selected"' : '')?>>區分遊戲</option>
			<option value="server" <?=($this->input->get("display_game")=='server' ? 'selected="selected"' : '')?>>展開伺服器</option>
 		</select>	 -->
		
	</div>
	
	<div class="control-group">
			
		<select name="time_unit" style="width:80px">
			<option value="hour" <?=($this->input->get("time_unit")=='hour' ? 'selected="selected"' : '')?>>時</option>
			<option value="day" <?=($this->input->get("time_unit")=='day' ? 'selected="selected"' : '')?>>日</option>
			<option value="month" <?=($this->input->get("time_unit")=='month' ? 'selected="selected"' : '')?>>月</option>
			<option value="year" <?=($this->input->get("time_unit")=='year' ? 'selected="selected"' : '')?>>年</option>
		</select>		
		
		<input type="submit" class="btn btn-small btn-inverse" name="action" value="時段統計">
<!-- 		<input type="submit" class="btn btn-small btn-inverse" name="action" value="廣告統計"> -->
		
		<? if ($this->input->get("use_default") === false):?>
		<span class="sptl"></span>
		<a href="?game_id=<?=$this->game_id?>" class="btn btn-small"><i class="icon-remove"></i> 清除查詢</a>
		<? endif;?>
		
	</div>
	
	<div class="control-group">

	</div>
		
</form>

<? if ($query):?>
	<? if ($query->num_rows() == 0): echo '<div class="none">查無資料</div>'; else: ?>
	
		<? 
		switch ($this->input->get("action")) 
		{
			case "時段統計":
				
				$servers = array();
				$table = array();
				$sum_t = $sum_s = array();
				$sum_all = 0;
				foreach($query->result() as $row) {
					if ( ! in_array($row->key, $servers)) {			
						$servers[$row->key] = "{$row->name}";
					}
					$table[$row->time][$row->key] = $row->cnt;
					
					if (key_exists($row->time, $sum_t)) {
						$sum_t[$row->time] += $row->cnt;
					} else $sum_t[$row->time] = $row->cnt;
					
					if (key_exists($row->key, $sum_s)) {
						$sum_s[$row->key] += $row->cnt;
					} else $sum_s[$row->key] = $row->cnt;
					
					$sum_all += $row->cnt;
				}
				ksort($servers);
		?>
		
<table class="table table-striped table-bordered" style="width:auto;">
	<thead>
		<tr>
			<th style="width:130px;">時段 \ 伺服器</th>
			<? foreach($servers as $server):?>
			<th style="width:70px;"><?=$server?></th>
			<? endforeach;?> 	
			<th style="width:80px">小計</th> 	
		</tr>
	</thead>
	<tbody>
		<? foreach($table as $time => $row):?>
			<tr>
				<td><?=$time?></td>
				<? foreach($servers as $key => $name):?>
				<td><?=array_key_exists($key, $row) ? $row[$key] : ''?></td>
				<? endforeach;?>
				<td><?=$sum_t[$time]?></td>
			</tr>
		<? endforeach;?>
		<tr>
			<td>總計</td>
			<? foreach($servers as $key => $name):?>
			<td><?=$sum_s[$key]?></td>
			<? endforeach;?>
			<td><?=$sum_all?></td>
		</tr>	
	</tbody>
</table>	
		
		<? 
				break;
				
			case "廣告統計":
				
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
		} ?>
	<? endif;?>
<? endif;?>