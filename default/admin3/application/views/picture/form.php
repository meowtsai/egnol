<? if ($category_query->num_rows() == 0):?>

<div class="none">尚無分類選項，請先<a href="<?=site_url("picture/add_category?game_id={$this->game_id}")?>">前往新增分類</a>。</div>

<? else: ?>

<form action="<?=site_url('picture/modify')?>" method="POST" enctype="multipart/form-data">
  <input type="hidden" name="picture_id" value="<?=$picture ? $picture->id : ''?>">
  <input type="hidden" name="game_id" value="<?=$this->game_id?>">
  <input type="hidden" name="back_url" value="<?=$back_url?>">
  
  <label>分類</label>
  <select name="category_id">
	<? 
	$category_id = $picture ? $picture->category_id : $this->input->get("cgid");
	foreach($category_query->result() as $row):?>
	<option value="<?=$row->id?>" <?=$category_id==$row->id ? 'selected="selected"' : ''?>><?=$row->category?></option>		
	<? endforeach;?>
  </select>
  
  <label>圖片網址</label>
	<input type="text" name="src" class="" value="<?=$picture ? $picture->src : ''?>" size="40">
	or 上傳圖片:		
	<input type="file" name="userfile" size="20" />
	
	<label>大小:</label>
	<input type="text" name="width" class="input-mini" value="<?=$picture ? $picture->width : ''?>"> x
	<input type="text" name="height" class="input-mini" value="<?=$picture ? $picture->height : ''?>">
	<span class="text-info"><span class="icon-info-sign"></span> 一般圖片不設大小時，會自動偵測；Flash，則<b>必須</b>設定大小。</span>
	
	
	<? if ($picture && $picture->src):?>
	<div style="margin:5px 0 0 0;">		
		<span style="border:1px solid #ccc; padding:6px; background:#eee; display:inline-block;">
		<?=make_pic_item($picture)?>
		</span>
	</div>
	<? endif;?>
	
	<label>超連結</label>
	<input type="text" name="link" size="40" value="<?=$picture ? $picture->link : ''?>">
	
	<label>描述</label>
	<input type="text" name="title" size="40" value="<?=$picture ? $picture->title : ''?>">
	
	<label>是否發布</label>
	<label class="radio"><input type="radio" value="1" name="is_active" <?=$picture ? ($picture->is_active=='1' ? 'checked="checked"' : '') : 'checked="checked"'?>>發布</label>
	<label class="radio"><input type="radio" value="0" name="is_active" <?=$picture ? ($picture->is_active=='0' ? 'checked="checked"' : '') : ''?>>不發布</label>
 
	<div class="clearfix" style="margin-bottom:10px;"></div>
	
	<label>發布時間</label>
	<input type="text" name="start_time" class="" value="<?=$picture ? date('Y-m-d H:i', strtotime($picture->start_time)) : ''?>">
	<span class="help-inline">(非必填，設定未來時間即可預約發布)</span>
	
	<label>~ 關閉時間</label>
	<input type="text" name="end_time" class="" value="<?=$picture && $picture->end_time<'2038-01-01' ? date('Y-m-d H:i', strtotime($picture->end_time)) : ''?>">
	<span class="help-inline">(非必填，設定後消息將於指定時間關閉)</span>	

  <div class="form-actions"><input type="submit" value="確認送出" class="btn"></div>	  
</form>

<? endif;?>
