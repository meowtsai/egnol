<form method="post" enctype="multipart/form-data" action="<?=site_url("home/modify_skybar_banner_ajax")?>">
  <input type="hidden" name="id" value="<?=$row ? $row->id : ''?>">
  <input type="hidden" name="p1_src" value="<?=$row ? $row->p1_src : ''?>">
  <input type="hidden" name="p2_src" value="<?=$row ? $row->p2_src : ''?>">
  <input type="hidden" name="back_url" value="">
  
	<fieldset>
					
		<label>超連結</label>
		<input type="text" name="link" value="<?=$row ? $row->link : ''?>" class="required" style="width:200px">
		
		<label>預覽
			<span style="width:145px; height:42px; line-height:42px; background:#ddd; text-align:center; display:block; overflow:hidden;">				
				<? if ($row && ! empty($row->p1_src)):?>
				<img src="<?=$row->p1_src?>">
				<? else: echo '145x42'; endif;?>
			</span>
			<input type="file" name="p1">
		</label>	
				
		<label>圖示
			<span style="width:950px; height:200px; line-height:200px; background:#ddd; text-align:center; display:block; overflow:hidden;">
				<? if ($row && ! empty($row->p2_src)):?>
				<img src="<?=$row->p2_src?>">
				<? else: echo '950x200'; endif;?>
			</span>
			<input type="file" name="p2">
		</label>
		
		<label>是否發布</label>
		<label class="radio inline"><input type="radio" value="1" name="enable" <?=$row ? ($row->enable=='1' ? 'checked="checked"' : '') : ''?>> 是</label>
		<label class="radio inline"><input type="radio" value="0" name="enable" <?=$row ? ($row->enable!=='1' ? 'checked="checked"' : '') : ''?>> 否</label>
			
   		<div class="form-actions">
   			<button type="submit" class="btn"><i class="icon-ok"></i> 確認送出</button>
   		</div>
   		
	</fieldset>
</form>