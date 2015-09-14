<?php 
	$channels = $this->config->item('channels');	
	$ad_channels = $this->config->item('ad_channels');
	$extra_ad_channels = $this->config->item('extra_ad_channels');
	
	if ($this->game_id && array_key_exists($this->game_id, $extra_ad_channels)) {
		$ad_channels = array_merge($ad_channels, $extra_ad_channels[$this->game_id]);
	}
?>

<form method="get" action="<?=site_url("log/game_login")?>" class="form-search">
	<input type="hidden" name="game_id" value="<?=$this->game_id?>">
	
		<div class="control-group">
	
	<? if ($this->game_id == false):?>
		
		遊戲
		<select name="game" style="width:90px">
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
		<select name="server" style="width:90px;">
			<option value="">--</option>
		</select>
		
		<select id="server_pool" style="display:none;">
			<? foreach($servers->result() as $row):?>
			<option value="<?=$row->id?>" <?=($this->input->get("server")==$row->id ? 'selected="selected"' : '')?> class="<?=$row->game_id?>"><?=$row->name?></option>
			<? endforeach;?>
		</select>	
		
		<span class="sptl"></span>	
		
		通路來源 
		<select name="channel" style="width:120px">
			<option value="">--</option>
			<? foreach($channels as $key => $channel):?>
			<option value="<?=$key?>" <?=($this->input->get("channel")==$key ? 'selected="selected"' : '')?>><?=$channel?></option>
			<? endforeach;?>
		</select>		
			
		<input type="text" name="ad_channel" value="<?=$this->input->get("ad_channel")?>" style="width:90px;" placeholder="廣告參數">
		
		<span class="sptl"></span>	
							
		時間
		<input type="text" name="start_date" value="<?=$this->input->get("start_date")?>" style="width:120px"> 至
		<input type="text" name="end_date" value="<?=$this->input->get("end_date")?>" style="width:120px" placeholder="現在">
		<a href="javascript:;" class="clear_date"><i class="icon-remove-circle" title="清除"></i></a>		
	
	</div>	
	
	<div class="control-group">		
	
		<input type="text" name="uid" value="<?=$this->input->get("uid")?>" class="input-small" placeholder="uid">		
		<input type="text" name="euid" value="<?=$this->input->get("euid")?>" class="input-small" placeholder="euid">
		<input type="text" name="mobile" value="<?=$this->input->get("mobile")?>" class="input-medium" placeholder="手機">
		<input type="text" name="email" value="<?=$this->input->get("email")?>" class="input-medium" placeholder="信箱">
		<input type="text" name="ip" value="<?=$this->input->get("ip")?>" style="width:110px"  placeholder="IP">
		
		<span class="sptl"></span>	
		
		是否創角
		<select name="role_exist" style="width:66px">
			<option value="">--</option>
			<option value="1" <?=($this->input->get("role_exist")=='1' ? 'selected="selected"' : '')?>>是</option>
			<option value="2" <?=($this->input->get("role_exist")=='2' ? 'selected="selected"' : '')?>>否</option>
		</select>
		
		<span class="sptl"></span>		
		
		<input type="checkbox" id="distnict" name="distinct" value="1" <?=$this->input->get("distinct") ? 'checked="checked"' : ''?>>
		<label for="distnict">去重覆(留最新)</label>
			
	</div>
	
	<div class="control-group">	

		<input type="submit" class="btn btn-small btn-inverse" name="action" value="查詢">	
		<input type="submit" class="btn btn-small btn-warning" name="action" value="輸出">
		
		<span class="sptl"></span>	
							
		顯示
		<select name="display_game" style="width:120px">
			<option value="">--</option>
			<option value="game" <?=($this->input->get("display_game")=='game' ? 'selected="selected"' : '')?>>區分遊戲</option>
			<option value="server" <?=($this->input->get("display_game")=='server' ? 'selected="selected"' : '')?>>展開伺服器</option>
		</select>	
		
		<span class="sptl"></span>	
		
		<input type="submit" class="btn btn-small btn-inverse" name="action" value="通路統計">
		<input type="submit" class="btn btn-small btn-inverse" name="action" value="廣告統計">
		<input type="submit" class="btn btn-small btn-inverse" name="action" value="IP統計">
		
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
		<a href="?game_id=<?=$this->game_id?>" class="btn btn-small"><i class="icon-remove"></i> 重置條件</a>
		<? endif;?>
		
	</div>

	<!--
	<p class="text-info">
		<span class="label label-info">說明</span>
		去重覆：同帳號只會留最新一筆
	</p>
	-->	
	
</form>


<? if ( ! empty($query)):?>
	<? if ($query->num_rows() == 0): echo '<div class="none">查無資料</div>'; else: ?>
	
		<? 
		switch ($this->input->get("action")) 
		{
			case "查詢": ?>
				
<div class="msg">總筆數:<?=$total_rows?></div>
<?=tran_pagination($this->pagination->create_links());?>

<table class="table table-striped table-bordered">
	<thead>
		<tr>			
			<th style="width:50px;">#</th>
			<th style="width:70px;">uid
				<div style="color:#777;">euid</div></th>
			<th style="width:70px;">手機</th>	
			<th style="width:90px;">信箱</th>				
			<th style="width:60px;">IP位址</th>		
			<th style="width:70px;">建檔時間</th>
			<th style="width:60px;">登入遊戲</th>			
			<th style="width:60px;">廣告</th>			
		</tr>
	</thead>
	<tbody>
		<? foreach($query->result() as $row): ?>
		<tr>
			<td><?=$row->id?></td>
			<td><a href="<?=site_url("member/view/{$row->uid}")?>"><?=$row->uid?></a>
				<a href="/admin3/log/game_login?uid=<?=$row->uid?>&action=查詢"><i class="icon-search"></i></a>
			</td>
			<td><?=$row->mobile?></td>	
			<td><?=$row->email?></td>	
			<td><?=$row->ip?>
				<a href="/admin3/log/game_login?ip=<?=$row->ip?>&action=查詢"><i class="icon-search"></i></a>
			</td>
			<td><?=$row->create_time?></td>
			<td><?=$row->game_name."_".$row->server_name?></td>			
			<td><?=$row->ad?></td>
		</tr>
		<? endforeach;?>
	</tbody>
</table>

<?=tran_pagination($this->pagination->create_links());?>

		<? break;
		
		case "通路統計":
			
			$field = array('通路');
			$table = array();				
			
			foreach($query->result() as $row) 
			{
				$field[$row->key] = $row->name;
				$row->title = empty($row->title) ? 'long_e' : substr($row->title, 1, 20);
				$title = isset($channels[$row->title]) ? $channels[$row->title] : $title;
				$table[$title][$row->key] = $row->cnt;					
			}
			echo output_statistics_table($field, $table, true);
			
			break;
			
		case "廣告統計":
			
			$field = array('廣告');
			$table = array();				
			
			foreach($query->result() as $row) 
			{			
				$title = empty($row->title) ? "無" : $row->title;
				$field[$row->key] = $row->name;
				$table[$title][$row->key] = $row->cnt;	
			}
			echo output_statistics_table($field, $table, true);
			
			break;			
			
		case "IP統計":
?>
	<p class="text-info">
		<span class="label label-info">說明</span>
		帳號登入同個伺服器只計1次
	</p>
<? 
			$field = array('IP');
			$table = array();				
			
			foreach($query->result() as $row) {							
				$field[$row->key] = $row->name;
				$table[$row->title][$row->key] = $row->cnt;					
			}
			echo output_statistics_table($field, $table, true);
			
			break;				
						
		case "時段統計":
			
			$field = array('時段');
			$table = array();
			
			foreach($query->result() as $row) {							
				$field[$row->key] = $row->name;
				$table[$row->title][$row->key] = $row->cnt;					
			}
			echo output_statistics_table($field, $table);
			
			break;	

		}
		?>
	<? endif;?>
<? endif;?>
