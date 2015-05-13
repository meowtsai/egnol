
<ul class="pager">
  <li class="previous">
    <a href="<?=site_url("ad/manage")?>"><i class=" icon-arrow-left"></i> 返回</a>
  </li>
</ul>

<form action="<?=site_url('ad/modify')?>" method="POST">
  	<input type="hidden" name="back_url" value="<?=$back_url?>">
  	<input type="hidden" name="group_id" value="<?=$record ? $record->group_id : $group_id?>">  

 	<label>#廣告ID</label>
 	<input type="text" name="ad" class="required" value=""  maxlength="30"  style="width:120px">
 	
	<div class="form-actions">
  		<button type="submit" class="btn ">確認送出</button>
  	</div>
</form>