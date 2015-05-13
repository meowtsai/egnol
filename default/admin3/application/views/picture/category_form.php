
<form action="<?=site_url("picture/modify_category?game_id={$this->game_id}")?>" method="POST">
  <input type="hidden" name="category_id" value="<?=$row ? $row->id : ''?>">
  
  <label>分類名稱</label>
  <input type="text" name="category" class="required" value="<?=$row ? $row->category : ''?>" size="50"></li>		

  <div class="form-actions">
  	<input type="submit" value="確認送出" class="btn">
  </div>	  
</form>
