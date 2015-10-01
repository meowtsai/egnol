<? 
	$tags = $row ? explode(",", $row->tags) : array();
	
	if (isset($msg)) output_result($msg);
?>
<form method="post" class="validation" enctype="multipart/form-data" action="<?=site_url("game/modify")?>">
	<fieldset>
	    <?if(isset($row->game_id)):?>
		<input type="hidden" name="id" value="<?=$row ? $row->game_id : ''?>">
		<?else:?>
		<label>遊戲代號</label>
		<input type="text" name="game_id" value="" class="required" style="width:50px">
		<?endif;?>
		
		<label>遊戲名稱</label>
		<input type="text" name="name" value="<?=$row ? $row->name : ''?>" class="required" style="width:200px">
		
		<select name="is_active" style="width:120px;">
			<option value="1" <?=$row ? ($row->is_active=='1' ? 'selected' : '') : ''?>>o開啟遊戲</option>
			<option value="0" <?=$row ? ($row->is_active=='0' ? 'selected' : '') : ''?>>x關閉遊戲</option>
		</select>
		
		<label>遊戲簡稱</label>
		<input type="text" name="abbr" value="<?=$row ? $row->abbr : ''?>" class="required" style="width:150px">
		
		<label>轉點比值</label>
		<input type="text" name="exchange_rate" value="<?=$row ? $row->exchange_rate : ''?>" class="required number" style="width:50px;">
		
		<label>遊戲中金錢名稱</label>
		<input type="text" name="currency" value="<?=$row ? $row->currency : ''?>" class="required" style="width:150px">
		<span class="help-inline">例如：元寶</span>
		
		<label>遊戲分類</label>
		<label class="radio inline"><input name="type" type="radio" value="" <?=(count($tags) == 0 ? "checked='checked'" : "")?>>--</label>
		<label class="radio inline"><input name="type" type="radio" value="即時" <?=in_array("即時", $tags) ? "checked='checked'" : ""?>> 即時</label>
		<label class="radio inline"><input name="type" type="radio" value="策略" <?=in_array("策略", $tags) ? "checked='checked'" : ""?>> 策略</label>
		<label class="radio inline"><input name="type" type="radio" value="回合" <?=in_array("回合", $tags) ? "checked='checked'" : ""?>> 回合</label>
		<label class="radio inline"><input name="type" type="radio" value="其它" <?=in_array("其它", $tags) ? "checked='checked'" : ""?>> 其它</label>
		
		<div style="margin-bottom:10px;"></div>
		
		<label>遊戲性質</label>
		<label class="checkbox inline"><input name="tags[]" type="checkbox" value="最新" <?=in_array("最新", $tags) ? "checked='checked'" : ""?>> 最新</label>
		<label class="checkbox inline"><input name="tags[]" type="checkbox" value="測試" <?=in_array("測試", $tags) ? "checked='checked'" : ""?>> 測試</label>
		<label class="checkbox inline"><input name="tags[]" type="checkbox" value="熱門" <?=in_array("熱門", $tags) ? "checked='checked'" : ""?>> 熱門</label>
		<label class="checkbox inline"><input name="tags[]" type="checkbox" value="推薦" <?=in_array("推薦", $tags) ? "checked='checked'" : ""?>> 推薦</label>
		<label class="checkbox inline"><input name="tags[]" type="checkbox" value="好朋友" <?=in_array("好朋友", $tags) ? "checked='checked'" : ""?>> 好朋友</label>
		<label class="checkbox inline"><input name="tags[]" type="checkbox" value="重點" <?=in_array("重點", $tags) ? "checked='checked'" : ""?>> 重點</label>
		<label class="checkbox inline"><input name="tags[]" type="checkbox" value="聯運" <?=in_array("聯運", $tags) ? "checked='checked'" : ""?>> 聯運</label>
		<label class="checkbox inline"><input name="tags[]" type="checkbox" value="手遊" <?=in_array("手遊", $tags) ? "checked='checked'" : ""?>> 手遊</label>
	
		<div style="margin-bottom:10px;"></div>
	
		<label>首頁遊戲圖</label>
		
		<label>重點(.jpg)
			<span style="width:1920px; height:330px; line-height:330px; background:#ddd; text-align:center; display:block; overflow:hidden;">
				<? if (file_exists(g_conf("http_document_root")."long_e/p/img/game/{$this->game_id}_01.jpg")):?>
				<img src="/p/img/game/<?=$this->game_id?>_01.jpg?<?=time()?>">
				<? else: echo '1920x330'; endif;?>
			</span>
			<input type="file" name="file01">
		</label>
		
		<label>重點縮圖(.png)
			<span style="width:160px; height:36px; line-height:36px; background:#ddd; text-align:center; display:block; overflow:hidden;">				
				<? if (file_exists(g_conf("http_document_root")."long_e/p/img/game/{$this->game_id}_02.png")):?>
				<img src="/p/img/game/<?=$this->game_id?>_02.png?<?=time()?>">
				<? else: echo '160x36'; endif;?>
			</span>
			<input type="file" name="file02">
		</label>		
		
		<label>小區塊(.png)
			<span style="width:160px; height:90px; line-height:90px; background:#ddd; text-align:center; display:block; overflow:hidden;">
				<? if (file_exists(g_conf("http_document_root")."long_e/p/img/game/{$this->game_id}_03.png")):?>
				<img src="/p/img/game/<?=$this->game_id?>_03.png?<?=time()?>">
				<? else: echo '160x90'; endif;?>				
			</span>
			<input type="file" name="file03">
		</label>
				
		<label>小圖(.gif)
			<span style="width:32px; height:32px; line-height:32px; background:#ddd; text-align:center; display:block; overflow:hidden;">
				<? if (file_exists(g_conf("http_document_root")."long_e/p/img/game/{$this->game_id}.gif")):?>
				<img src="/p/img/game/<?=$this->game_id?>.gif?<?=time()?>">
				<? else: echo '32x'; endif;?>				
			</span>
			<input type="file" name="file04">
		</label>

	
   		<div class="form-actions">
   			<button type="submit" class="btn"><i class="icon-ok"></i> 確認送出</button>
   		</div>
   		
	</fieldset>
</form>