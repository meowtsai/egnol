<? 
	$arr = array();
	$bulletin && $arr = explode(",", strval($bulletin->target));
	
	$games = $this->db->from("games")->where("is_active", "1")->get();
	$servers = $this->config->item("servers");
?>

<form action="<?=site_url('bulletin/modify')?>" method="POST">
  	<input type="hidden" name="bulletin_id" value="<?=$bulletin ? $bulletin->id : ''?>">
  	<input type="hidden" name="game_id" value="<?=$this->game_id?>">
  	<input type="hidden" name="back_url" value="<?=$back_url?>">
 
 	<label>標題</label>
 	<input type="text" name="bulletin_title" class="required input-xxlarge" value="<?=$bulletin ? $bulletin->title : ''?>" size="80" maxlength="80">
 
	<label>分類</label>
	<select name="bulletin_type">
		<? foreach($bulletin_type_list as $type_id => $type_name):
			$def_category_id = $bulletin ? $bulletin->type : $this->input->get("bulletin_type");
		?>
		<option value="<?=$type_id?>" <?= $def_category_id == $type_id ? 'selected="selected"' : ''?>><?=$type_name?></option>		
		<? endforeach;?>
	</select>
	
	<div id="choose_target_server" style="display:none;">
		<label>發布伺服器
			<span class="help-inline">(若無選擇，則為全發布)</span>	
		</label>
		<div style="margin-bottom:13px;">
			<label class="checkbox"><input type="checkbox" id="clickAll"> 全選</label>
			<div id="servers">
				<? foreach($server_list->result() as $row):?>
				<label class="checkbox inline"><input type="checkbox" name="target[]" value="<?=$row->server_id?>" <?=in_array($row->server_id, $arr) ? 'checked="checked"' : ''?>> <?=$row->name?></label>
				<? endforeach;?>
			</div>
		</div>
	</div>
	
	<? if ($this->game_id == 'long_e'):?>
	<div id="choose_target_site">
		<label>發布平台
			<span class="help-inline">(若無選擇，則為全發布)</span>	
		</label>
		<div style="margin-bottom:13px;">
			<label class="checkbox inline"><input type="checkbox" name="target[]" value="long_e" <?=in_array('long_e', $arr) ? 'checked="checked"' : ''?>> 龍邑遊戲</label>
			<?	foreach($games->result() as $row): ?>
			<label class="checkbox inline"><input type="checkbox" name="target[]" value="<?=$row->game_id?>" <?=in_array($row->game_id, $arr) ? 'checked="checked"' : ''?>> <?=$row->name?></label>
			<? endforeach;?>
		</div>
	</div>
	<? endif;?>
	
	<label>內文</label>
	<textarea name="bulletin_content" class="ckeditor"><?=$bulletin ? $bulletin->content : ''?></textarea>	
	
	<div class="clearfix" style="margin-bottom:10px;"></div>
	
	<label>是否發布</label>
	<label class="radio inline"><input type="radio" value="3" name="priority" <?=$bulletin ? ($bulletin->priority=='3' ? 'checked="checked"' : '') : ''?>> 首篇</label>
	<label class="radio inline"><input type="radio" value="2" name="priority" <?=$bulletin ? ($bulletin->priority=='2' ? 'checked="checked"' : '') : ''?>> 置頂</label>
	<label class="radio inline"><input type="radio" value="1" name="priority" <?=$bulletin ? ($bulletin->priority=='1' ? 'checked="checked"' : '') : 'checked="checked"'?>> 發布</label>
	<label class="radio inline"><input type="radio" value="0" name="priority" <?=$bulletin ? ($bulletin->priority=='0' ? 'checked="checked"' : '') : ''?>> 不發布</label>
	
	<div class="clearfix" style="margin-bottom:10px;"></div>
	
	<label>發布時間</label>
	<input type="text" name="publish_date" class="" value="<?=$bulletin ? date('Y-m-d H:i', strtotime($bulletin->publish_date)) : ''?>">
	<span class="help-inline">(非必填，設定未來時間即可預約發布)</span>
	
	<label>~ 關閉時間</label>
	<input type="text" name="close_date" class="" value="<?=$bulletin && $bulletin->close_date<'2038-01-01' ? date('Y-m-d H:i', strtotime($bulletin->close_date)) : ''?>">
	<span class="help-inline">(非必填，設定後消息將於指定時間關閉)</span>	
	
	<div class="form-actions">
  		<button type="submit" class="btn ">確認送出</button>
  	</div>
</form>