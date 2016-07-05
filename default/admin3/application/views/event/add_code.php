
<div>
	<form method="post" class="validation">
        <input type="text" name="title" value="<?=$this->input->get("title")?>" class="input-medium required" placeholder="名稱"><br>
        遊戲
		<select name="game" class="span2 required">
			<option value="">--</option>
			<? foreach($games->result() as $row):?>
			<option value="<?=$row->game_id?>" <?=($this->input->get("game")==$row->game_id ? 'selected="selected"' : '')?>><?=$row->name?></option>
			<? endforeach;?>
		</select><br>
		<textarea name="codes" style="width:260px; height:320px;" class="required"></textarea><br>
		<div style="">一行放一組序號</div>
		<input type="submit" class="btn" value="確認送出">
	</form>
</div>