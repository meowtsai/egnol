
<form action="<?=site_url("bulletin/modify_category?game_id={$this->game_id}")?>" method="POST">
  <input type="hidden" name="category_id" value="<?=$row ? $row->id : ''?>">
  
  <label>分類名稱</label>
  <input type="text" name="category" class="required" value="<?=$row ? $row->category : ''?>" size="50">  		

  <label>是否顯示</label>
  <label class="radio "><input type="radio" value="0" name="display" <?=$row ? ($row->display=='0' ? 'checked="checked"' : '') : ''?>>否</label>
  <label class="radio "><input type="radio" value="1" name="display" <?=$row ? ($row->display=='1' ? 'checked="checked"' : '') : 'checked="checked"'?>>是</label>
	  
  <br>
  <input type="submit" value="確認送出" class="btn">
</form>
