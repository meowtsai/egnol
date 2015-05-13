
<form action="<?=site_url('notice/modify')?>" method="POST">
  	<input type="hidden" name="notice_id" value="<?=$notice ? $notice->id : ''?>">
  	<input type="hidden" name="back_url" value="<?=$back_url?>">
 
 	<label>標題</label>
 	<input type="text" name="title" class="required input-xxlarge" value="<?=$notice ? $notice->title : ''?>" size="80" maxlength="80">
 	
 	<label>網址</label>
 	<label style="font-size:12px; color:#977;">若填寫則直接開啟網址，否則顯示內文</label>
 	<input type="text" name="url" class="input-xxlarge url" value="<?=$notice ? $notice->url : ''?>" size="80">
	
	<label>內文</label>
	<label style="font-size:12px; color:#977;">非必填</label>
	<textarea name="content" class="ckeditor"><?=$notice ? $notice->content : ''?></textarea>	
	
	<div class="clearfix" style="margin-bottom:10px;"></div>
	
	<label>是否發布</label>
	<label class="radio inline"><input type="radio" value="1" name="status" <?=$notice ? ($notice->is_active=='1' ? 'checked="checked"' : '') : 'checked="checked"'?>> 發布</label>
	<label class="radio inline"><input type="radio" value="0" name="status" <?=$notice ? ($notice->is_active=='0' ? 'checked="checked"' : '') : ''?>> 不發布</label>
		
	<div class="form-actions">
  		<button type="submit" class="btn ">確認送出</button>
  	</div>
</form>