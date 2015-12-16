<? if (isset($result)):?>
	<? if ($result):?>
	<div class="alert alert-success"><i class="icon-ok-circle"></i> 修改成功</div>
	<? else:?>
	<div class="alert alert-error"><i class="icon-remove-circle"></i> 失敗</div>
	<? endif;?>
<? endif;?>

<form method="post" class="validation">
	<fieldset>
		<input type="hidden" name="key" value="<?=$row ? $row->resource : ''?>">
	
		<label>功能</label>
		<? if ($row):?>
		<input type="text" value="<?=$row->resource?>" disabled="disabled">
		<? else:?>
		<input type="text" name="resource" placeholder="英文" value="<?=$row ? $row->resource : ''?>" class="required">
		<? endif;?>		
		
		
		<label>說明</label>
		<input type="text" name="resource_desc" value="<?=$row ? $row->resource_desc : ''?>" class="required">
		
		<label>操作</label>
		<input type="text" name="operation_list" value="<?=$row ? $row->operation_list : ''?>" style="width:360px">		
	
		<label>隸屬群組</label>
		<? if ($child_num > 0):?>
		<p class="text-error">無法移動</p>
		<? else:?>		
		<select name="parent">
			<option value="">--</option>
			<? foreach($all_resource->result() as $rs):?>
			<option value="<?=$rs->resource?>" <?=($row && $row->parent==$rs->resource) ? "selected='selected'" : ""?>><?=$rs->resource?></option>
			<? endforeach;?>
		</select>
		<? endif;?>
	
   		<div class="form-actions">
   			<button type="submit" class="btn"><i class="icon-ok"></i> 確認送出</button>
   		</div>
   		
	</fieldset>
</form>