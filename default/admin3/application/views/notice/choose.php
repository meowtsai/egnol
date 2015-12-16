<?php 
	$channels = $this->config->item('channels');
?>

<form action="<?=site_url('notice/choose_modify')?>" method="POST">
  	<input type="hidden" name="notice_id" value="<?=$id?>">
  	<input type="hidden" name="back_url" value="<?=$back_url?>">
 
 <div style="border:1px solid #ccc; background:#f5f5f5; margin:10px; padding:12px;">
 	<label>UID</label>
 	<textarea name="uid" style="width:200px; height:260px;"></textarea>
 	<label style="font-size:12px; color:#977;">一行放一組UID</label>
 	
 	<div class="form-actions" style="margin:0;">	
 		<input type="submit" name="action" value="通知這些UID">
 	</div>
</div>

<div style="border:1px solid #ccc; background:#f5f5f5; margin:10px; padding:12px;">
 	
 	<div class="form-actions" style="margin:0;">	
 		<input type="submit" name="action" value="通知三個月內有登入的玩家">
 	</div> 	
</div>
 	
<div style="border:1px solid #ccc; background:#f5f5f5; margin:10px; padding:12px;">
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
		
	<div class="form-actions" style="margin:0;">
  		<button type="submit" class="btn ">通知這些玩家</button>
  	</div>
</div>
  	
</form>