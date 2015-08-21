<?php 
	$channels = $this->config->item('channels');
	$ad_channels = $this->config->item('ad_channels');
?>
<div id="func_bar">
	
	<? //if (in_array($this->game_id, array('xj', 'sg2', 'xl', 'gt','jh','eb','dxc'))):?>
	<a class="btn btn-primary btn-small" href="<?=site_url("character/create_character_job?game_id={$this->game_id}")?>">更新創角資料</a>
	<? //endif;?>
	
	<a class="btn btn-primary btn-small" href="<?=site_url("character/import?game_id={$this->game_id}")?>">匯入創角資料</a>
</div>

<form method="get" action="<?=site_url("character")?>" class="form-search">
		
	<div class="control-group">	
	
		遊戲
		<select name="game_id" style="width:120px;">
			<option value="">--</option>
			<? foreach($games->result() as $row):?>
			<option value="<?=$row->game_id?>" <?=($this->game_id==$row->game_id ? 'selected="selected"' : '')?>><?=$row->name?></option>
			<? endforeach;?>
		</select>		
	
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
	
	</div>
	
	<div class="control-group">		
		
		時間
		<input type="text" name="start_date" value="<?=$this->input->get("start_date")?>" style="width:120px"> 至
		<input type="text" name="end_date" value="<?=$this->input->get("end_date")?>" style="width:120px" placeholder="現在">
		<a href="javascript:;" class="clear_date"><i class="icon-remove-circle" title="清除"></i></a>
		
		<span class="sptl"></span>
	
		<input type="text" name="uid" value="<?=$this->input->get("uid")?>" style="width:70px" placeholder="uid">
		<input type="text" name="euid" value="<?=$this->input->get("euid")?>" style="width:70px" placeholder="euid">
		<input type="text" name="character_name" value="<?=$this->input->get("character_name")?>" style="width:90px" placeholder="角色名稱">		

	</div>
	
	<div class="control-group">
		
		<input type="submit" class="btn btn-small btn-inverse" name="action" value="查詢">
		<input type="submit" class="btn btn-small btn-warning" name="action" value="輸出">
		
		<span class="sptl"></span>
		
		帳號
		<select name="member_type" style="width:96px">
			<option value="">全列</option>
			<option value="distinct" title="帳號不重複計算" <?=$this->input->get("member_type")=='distinct'?'selected="selected"':''?>>不重複</option>
			<option value="new_character" title="初次進入創建角色的會員" <?=$this->input->get("member_type")=='new_character'?'selected="selected"':''?>>全新角色</option>
			<option value="all_new_character" title="" <?=$this->input->get("member_type")=='all_new_character'?'selected="selected"':''?>>全網全新</option>			
		</select>
		
		<input type="submit" class="btn btn-small btn-inverse" name="action" value="通路統計">
		<input type="submit" class="btn btn-small btn-inverse" name="action" value="廣告統計">
		
		<span class="sptl"></span>
		
		<select name="time_unit" style="width:70px">
			<option value="hour" <?=($this->input->get("time_unit")=='hour' ? 'selected="selected"' : '')?>>時</option>
			<option value="day" <?=($this->input->get("time_unit")=='day' ? 'selected="selected"' : '')?>>日</option>
			<option value="month" <?=($this->input->get("time_unit")=='month' ? 'selected="selected"' : '')?>>月</option>
			<option value="year" <?=($this->input->get("time_unit")=='year' ? 'selected="selected"' : '')?>>年</option>
		</select>		
		
		<input type="submit" class="btn btn-small btn-inverse" name="action" value="伺服器時段統計">
		<input type="submit" class="btn btn-small btn-inverse" name="action" value="廣告時段統計">

		<? if ($this->input->get("use_default") === false):?>
		<span class="sptl"></span>
		<a href="?game_id=<?=$this->game_id?>" class="btn btn-small"><i class="icon-remove"></i> 重置查詢</a>
		<? endif;?>
	</div>
	
		<p class="text-info">
			<span class="label label-info">欄位說明</span>
			<strong>不重覆</strong>:同伺服器只記第一隻角色; <strong>全新角色</strong>:同遊戲只記第一隻角色; <strong>全網全新</strong>:long_e會員創建的第一隻角色
		</p>
		
</form>

<? if ($query):?>
	<? if ($query->num_rows() == 0): echo '<div class="none">查無資料</div>'; else: ?>
	
		<? 
		switch ($this->input->get("action")) 
		{
			case "查詢":
		?>
	
<div class="msg">總筆數:<?=$total_rows?></div>
<?=tran_pagination($this->pagination->create_links());?>

<table class="table table-striped table-bordered">
	<thead>
		<tr>
			<th style="width:80px;">uid
				<div style="color:#777;">euid</div></th>
			<th style="width:400px">信箱</th>
			<th style="width:400px">手機</th>
			<th style="width:90px;">註冊日期</th>
			<th style="width:90px;">伺服器</th>		
			<th style="width:120px;">角色名稱</th>
			<th style="width:120px;">建檔時間</th>	 	
		</tr>
	</thead>
	<tbody>
		<? foreach($query->result() as $row):?>
		<tr>
			<td>
				<a href="<?=site_url("member/view/{$row->uid}")?>"><?=$row->uid?></a>
				<a href="<?=site_url("character?uid={$row->uid}&action=查詢")?>"><i class="icon-search"></i></a>
				<div style="color:#777;"><?=$this->g_user->encode($row->uid)?></div>
			</td>
			<td><?=$row->email?></td>
			<td><?=$row->mobile?></td>
			<td><?=date("Y-m-d", strtotime($row->create_time))?></td>
			<td><?=$row->server_name?></td>
			<td><?=$row->character_name?></td>
			<td><?=$row->create_time?></td>
		</tr>
		<? endforeach;?>
	</tbody>
</table>

		<? 
			break;				
			case "通路統計":
		?>
		
<table class="table table-striped table-bordered" style="width:auto;">
	<thead>
		<tr>
			<th style="width:120px;">通路</th>
			<th style="width:80px;">小計</th> 	
		</tr>
	</thead>
	<tbody>
		<? $sum=0; foreach($query->result() as $row): $sum+=$row->cnt;?>
		<tr>
			<td>
				<?
				$key = empty($row->channel) ? 'long_e' : substr($row->channel, 1, 20);
				if (array_key_exists($key, $channels)) {
					echo $channels[$key];
				} else echo $key;
				?>
			</td>
			<td><?=$row->cnt?></td>
		</tr>
		<? endforeach;?>
		<tr>
			<td>總計</td>
			<td><?=$sum?></td>
		</tr>
	</tbody>
</table>

		<? 
			break;				
			case "廣告統計":
		?>
		
<table class="table table-striped table-bordered" style="width:auto;">
	<thead>
		<tr>
			<th style="width:120px;">廣告通路</th>
			<th style="width:80px;">小計</th> 	
		</tr>
	</thead>
	<tbody>
		<? $sum=0; foreach($query->result() as $row): $sum+=$row->cnt;?>
		<tr>
			<td>
				<?				
					$ad_channels['long_e'] = "自然增長";
					$key = empty($row->ad) ? 'long_e' : $row->ad;
					if (array_key_exists($key, $ad_channels)) {
						echo $ad_channels[$key];
					} else echo $key;
				?>
			</td>
			<td><?=$row->cnt?></td>
		</tr>
		<? endforeach;?>
		<tr>
			<td>總計</td>
			<td><?=$sum?></td>
		</tr>
	</tbody>
</table>
		
		<?
			break;
			case "伺服器時段統計":				
				
				$field = array('時段 \ 伺服器');
				$table = array();	
				
				foreach($query->result() as $row) 
				{			
					$title = $row->time;	
					$key = $row->server_id;				
					$field[$key] = $row->name;
					$table[$title][$key] = $row->cnt;
				}
				echo output_statistics_table($field, $table);					
		
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