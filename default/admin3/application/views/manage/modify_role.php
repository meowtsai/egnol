<? if (isset($result)):?>
	<? if ($result):?>
	<div class="alert alert-success"><i class="icon-ok-circle"></i> 修改成功</div>
	<? else:?>
	<div class="alert alert-error"><i class="icon-remove-circle"></i> 失敗</div>
	<? endif;?>
<? endif;?>

<form method="post" class="validation">
	<fieldset>
		<input type="hidden" name="key" value="<?=$row ? $row->role : ''?>">
	
		<label>角色</label>
		<? if ($row):?>
		<input type="text" value="<?=$row->role?>" disabled="disabled">
		<? else:?>
		<input type="text" name="role" placeholder="英文" value="<?=$row ? $row->role : ''?>" class="required">
		<? endif;?>
		
		<label>說明</label>
		<input type="text" name="role_desc" value="<?=$row ? $row->role_desc : ''?>" class="required">
	
		<label>隸屬群組</label>
		<? if ($child_num > 0):?>
		<p class="text-error">無法移動</p>
		<? else:?>
		<select name="parent">
			<option value="">--</option>
			<? foreach($all_role->result() as $rs):?>
			<option value="<?=$rs->role?>" <?=($row && $row->parent==$rs->role) ? "selected='selected'" : ""?>><?=$rs->role?></option>
			<? endforeach;?>
		</select>
		<? endif;?>
	
   		<div class="form-actions">
   			<button type="submit" class="btn"><i class="icon-ok"></i> 確認送出</button>
   		</div>
   		
	</fieldset>
</form>