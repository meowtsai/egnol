
<ul class="pager">
  <li class="previous">
    <a href="<?=site_url("ad/manage")?>"><i class=" icon-arrow-left"></i> 返回</a>
  </li>
</ul>

<form action="<?=site_url('ad/modify_group')?>" method="POST">
  	<input type="hidden" name="back_url" value="<?=$back_url?>">
  	<input type="hidden" name="id" value="<?=$record ? $record->id : ''?>"> 
	
 	<label>遊戲</label>
	<select name="game" style="width:200px">
		<option value="">--</option>
		<? foreach($games->result() as $row):?>
		<option value="<?=$row->game_id?>" <?=($record && $record->game==$row->game_id ? 'selected="selected"' : '')?>><?=$row->name?></option>
		<? endforeach;?>
	</select>	
		
 	<label>目標網址(Android)</label>
 	<input type="text" name="open_url_android" class="url" value="<?=$record ? $record->open_url_android : ''?>" style="width:400px">
 	
 	<label>目標網址(iOS)</label>
 	<input type="text" name="open_url_ios" class="url" value="<?=$record ? $record->open_url_ios : ''?>" style="width:400px">
	
	<div class="form-actions">
  		<button type="submit" class="btn ">確認送出</button>
  	</div>
</form>