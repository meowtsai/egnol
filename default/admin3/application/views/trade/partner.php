
<form method="get" action="<?=site_url($this->uri->uri_string())?>" class="form-search">

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
	
		<!-- 
		伺服器
		<select name="server" style="width:120px;">
			<option value="">--</option>
		</select>
		 -->
		
		<select id="server_pool" style="display:none;">
			<? foreach($servers->result() as $row):?>
			<option value="<?=$row->id?>" <?=($this->input->get("server")==$row->id ? 'selected="selected"' : '')?> class="<?=$row->game_id?>"><?=$row->name?></option>
			<? endforeach;?>
		</select>	
		
		<span class="sptl"></span>
				
		轉點時間
		<input type="text" name="start_date" value="<?=$this->input->get("start_date")?>" style="width:120px"> 至
		<input type="text" name="end_date" value="<?=$this->input->get("end_date")?>" style="width:120px" placeholder="現在">
		<a href="javascript:;" class="clear_date"><i class="icon-remove-circle" title="清除"></i></a>
		
		<!-- 
		<span class="sptl"></span>	
		
		顯示
		<select name="display_game" style="width:120px">
			<option value="game" <?=($this->input->get("display_game")=='game' ? 'selected="selected"' : '')?>>區分遊戲</option>
			<option value="server" <?=($this->input->get("display_game")=='server' ? 'selected="selected"' : '')?>>展開伺服器</option>
		</select>
		 -->		
	
	</div>
	
	<div class="control-group">		
		
<!-- 		<input type="submit" class="btn btn-small btn-inverse" name="action" value="廣告統計"> -->
		
<!-- 		<span class="sptl"></span>	 -->
		
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
		統計數據來源不含測試帳號及測服伺服器
	</p>		
	
</form>

<? if ( ! empty($query)):?>
	<? if ($query->num_rows() == 0): echo '<div class="none">查無資料</div>'; else: 
	?>
	
		<? 
		switch ($this->input->get("action")) 
		{							
			case "時段統計":
				
				$field = array('時段 \ 伺服器');
				$table = array();

				foreach($query->result() as $row) {						
					$field[$row->key] = $row->name;
					$table[$row->title][$row->key] = $row->cnt;					
				}				
				echo output_statistics_table($field, $table);
				
				break;
				
			case "廣告統計":
				
				$field = array('廣告');
				$table = array();
				
				foreach($query->result() as $row) {
					$field[$row->key] = $row->name;
					$table[$row->title][$row->key] = $row->cnt;					
				}
				echo output_statistics_table($field, $table, true);
				
				break;				
			} 			
		?>
	<? endif;?>
<? endif;?>

