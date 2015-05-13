<?php 
	$channels = $this->config->item('channels');
	$ad_channels = $this->config->item('ad_channels');
	$transaction_type = $this->config->item('transaction_type');	
?>

<form method="get" action="<?=site_url("trade/transfer_statistics")?>" class="form-search">
	<input type="hidden" name="game_id" value="<?=$this->game_id?>">

	<div class="control-group">

	<? if ($this->game_id == false):?>
	
		遊戲
		<select name="game" style="width:120px;">
			<option value="">--</option>
			<? foreach($games->result() as $row):?>
			<option value="<?=$row->game_id?>" <?=($this->input->get("game")==$row->game_id ? 'selected="selected"' : '')?>><?=$row->name?></option>
			<? endforeach;?>
		</select>
		
	<? else:?>
	
		<select name="game" style="display:none;">
			<option value="<?=$this->game_id?>" selected="selected"></option>
		</select>					

	<? endif;?>	
	
		伺服器
		<select name="server" style="width:120px;">
			<option value="">--</option>
		</select>
		
		<select id="server_pool" style="display:none;">
			<? foreach($servers->result() as $row):?>
			<option value="<?=$row->id?>" <?=($this->input->get("server")==$row->id ? 'selected="selected"' : '')?> class="<?=$row->game_id?>"><?=$row->name?></option>
			<? endforeach;?>
		</select>
		
		<span class="sptl"></span>
				
		通路來源 		
		<select name="channel" style="width:120px;">
			<option value="">--</option>
			<? foreach($channels as $key => $channel):?>
			<option value="<?=$key?>" <?=($this->input->get("channel")==$key ? 'selected="selected"' : '')?>><?=$channel?></option>
			<? endforeach;?>
		</select>	
		
		<input type="text" name="ad_channel" value="<?=$this->input->get("ad_channel")?>" style="width:90px;" placeholder="廣告參數">

		<span class="sptl"></span>
		
		金流管道
		<select name="transaction_type" style="width:120px;">
			<option value="">--</option>
			<? foreach($transaction_type as $key => $type):?>
			<option value="<?=$key?>" <?=($this->input->get("transaction_type")==$key ? 'selected="selected"' : '')?>><?=$type?></option>
			<? endforeach;?>
		</select>
		
	</div>
		
	<div class="control-group">	
				
		轉點時間
		<input type="text" name="start_date" value="<?=$this->input->get("start_date")?>" style="width:120px"> 至
		<input type="text" name="end_date" value="<?=$this->input->get("end_date")?>" style="width:120px" placeholder="現在">
		<a href="javascript:;" class="clear_date"><i class="icon-remove-circle" title="清除"></i></a>
		
		<span class="sptl"></span>
		
		會員註冊時間
		<input type="text" name="start_regdate" value="<?=$this->input->get("start_regdate")?>" style="width:120px"> 至
		<input type="text" name="end_regdate" value="<?=$this->input->get("end_regdate")?>" style="width:120px" placeholder="現在">
		<a href="javascript:;" class="clear_regdate"><i class="icon-remove-circle" title="清除"></i></a>
		
		<span class="sptl"></span>	
		
		顯示
		<select name="display_game" style="width:110px">
			<option value="">--</option>
			<option value="game" <?=($this->input->get("display_game")=='game' ? 'selected="selected"' : '')?>>區分遊戲</option>
			<option value="server" <?=($this->input->get("display_game")=='server' ? 'selected="selected"' : '')?>>展開伺服器</option>
		</select>			
		
	</div>
	
	<div class="control-group">		
		
		<input type="submit" class="btn btn-small btn-inverse" name="action" value="通路統計">
		<input type="submit" class="btn btn-small btn-inverse" name="action" value="廣告統計">
		<input type="submit" class="btn btn-small btn-inverse" name="action" value="會員統計">
		
		<span class="sptl"></span>
				
		<input type="submit" class="btn btn-small btn-inverse" name="action" value="儲值統計">
		
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
		統計數據來源不含測試帳號及測服伺服器
	</p>		
	
</form>

<? if ( ! empty($query)):?>
	<? if ($query->num_rows() == 0): echo '<div class="none">查無資料</div>'; else: 
	?>
	
		<? 
		switch ($this->input->get("action")) 
		{
			case "通路統計":
				
				$field = array('通路');
				$table = array();				
				
				if ($this->input->get("game")) {
					foreach($query->result() as $row) 
					{
						$field["cnt"] = "金額總計";
						$field["cnt2"] = "交易筆數";
						$field["cnt3"] = "儲值會員";
						$row->title = empty($row->title) ? 'long_e' : substr($row->title, 1, 20);
						$title = isset($channels[$row->title]) ? $channels[$row->title] : $row->title;
						$table[$title]["cnt"] = $row->cnt;
						$table[$title]["cnt2"] = $row->cnt2;
						$table[$title]["cnt3"] = $row->cnt3;					
					}
					echo output_statistics_table($field, $table, true, false);
				}
				else {
					foreach($query->result() as $row) 
					{
						$field[$row->key] = $row->name;
						$row->title = empty($row->title) ? 'long_e' : substr($row->title, 1, 20);
						$title = isset($channels[$row->title]) ? $channels[$row->title] : $row->title;
						$table[$title][$row->key] = $row->cnt;					
					}
					echo output_statistics_table($field, $table, true);
				}
				
				break;
							
			case "時段統計":
				
				$field = array('時段 \ 伺服器');
				$table = array();
				$mapping = array(
					"bw" => "a_bw",
					"dh" => "a_dh",
					"sl2" => "a_sl2",
					"yh" => "a_yh",
					"mon" => "a_mon",

					"mh" => "b_0_mh",
					"tc" => "b_1_tc",
					"xf" => "b_2_xf",
					"sg" => "b_3_sg",
					"qjp" => "b_4_qjp",
					"xl" => "b_5_xl",
					"gt" => "b_6_gt",
					"sg2" => "b_7_sg2",
					"xj" => "b_8_xj",
				);

				foreach($query->result() as $row) {		
					$row->key = strtr($row->key, $mapping);					
					$field[$row->key] = $row->name;
					$table[$row->title][$row->key] = $row->cnt;					
				}				
				echo output_statistics_table($field, $table);
				
				break;
			
			case "會員統計":
				
				$field = array('會員|euid');
				$table = array();
				
				foreach($query->result() as $row) {
					$title = "<a href='".site_url("member/view/{$row->uid}")."'>".$row->uid."</a>|".$this->g_user->encode($row->uid);
					$field[$row->key] = $row->name;
					$table[$title][$row->key] = $row->cnt;					
				}
				echo output_statistics_table($field, $table, true);
				
				break;
				
			case "廣告統計":
				
				$field = array('廣告');
				$table = array();
				
				foreach($query->result() as $row) {
					if (array_key_exists($row->title, $ad_channels)) 
						$title = $ad_channels[$row->title];
					else $title = $row->title;
					$field[$row->key] = $row->name;
					$table[$title][$row->key] = $row->cnt;					
				}
				echo output_statistics_table($field, $table, true);
				
				break;	

			case "儲值統計":
				
				$field = array('');
				$table = array();				
				
				foreach($query->result() as $row) 
				{							
					$field[$row->id] = $row->name;
					$table["金額總計"][$row->id] = $row->cnt;
					$table["交易筆數"][$row->id] = $row->cnt2;
					$table["儲值會員"][$row->id] = $row->cnt3;	
				}
				echo output_statistics_table($field, $table, false, false);	
		
				break;
								
			} 			
		?>
	<? endif;?>
<? endif;?>

