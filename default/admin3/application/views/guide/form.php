<div class="guide_path"><?=get_guide_path_str($parent_id, $this->game_id)?></div>

<form action="<?=site_url('guide/modify')?>" method="POST">
  <input type="hidden" name="guide_id" value="<?=$guide ? $guide->id : ''?>">
  <input type="hidden" name="parent_id" value="<?=$parent_id ? $parent_id : '0'?>">
  <input type="hidden" name="game_id" value="<?=$this->game_id?>">
  
  <label>標題</label>
  <input type="text" name="guide_title" class="required" value="<?=$guide ? $guide->guide_title : ''?>">
  		
  <label>連結網址</label>
  <input type="text" name="guide_link" value="<?=$guide ? $guide->guide_link : ''?>">
  
  <label>內容</label>
  <textarea name="guide_content" class="ckeditor"><?=$guide ? $guide->guide_content : ''?></textarea>
  <br>
  
  <label>是否發布：</label>
  <label class="radio inline"><input type="radio" value="1" name="enable" <?=$guide ? ($guide->enable ? 'checked="checked"' : '') : 'checked="checked"'?>>發布</label> 
  <label class="radio inline"><input type="radio" value="0" name="enable" <?=$guide ? ( ! $guide->enable ? 'checked="checked"' : '') : ''?>>不發布</label>
	
  <div class="form-actions"><input type="submit" class="btn" value="確認送出"></div>	  
</form>
