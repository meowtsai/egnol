<? 
	if (isset($msg)) output_result($msg, $msg_type);
?>
<div>
	<form method="post" class="validation">
        
        <input type="text" name="uid" value="<?=$this->input->get("uid")?>" class="input-medium required" placeholder="uid"><br>
        
        遊戲
		<select name="game" class="span2">
			<option value="">--</option>
			<? foreach($games->result() as $row):?>
			<option value="<?=$row->game_id?>" <?=($this->input->get("game")==$row->game_id ? 'selected="selected"' : '')?>><?=$row->name?></option>
			<? endforeach;?>
		</select>	
	
		伺服器
		<select name="server" class="span2">
			<option value="">--</option>
		</select>
		
		<select id="server_pool" style="display:none;" class="required">
			<? foreach($servers->result() as $row):?>
			<option value="<?=$row->server_id?>" <?=($this->input->get("server")==$row->server_id ? 'selected="selected"' : '')?> class="<?=$row->game_id?>"><?=$row->name?></option>
			<? endforeach;?>
		</select>
        
        
        <!--input type="text" name="character_name" value="<?=$this->input->get("character_name")?>" class="input-medium required" placeholder="角色名稱"><br-->
        
        <input type="text" name="points" value="<?=$this->input->get("points")?>" class="input-medium required" placeholder="點數"><br>
		<input type="submit" class="btn" value="確認送出">
	</form>
</div>