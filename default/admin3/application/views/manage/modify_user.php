<? if (isset($result)):?>
	<? if ($result):?>
	<div class="alert alert-success"><i class="icon-ok-circle"></i> 修改成功</div>
	<? else:?>
	<div class="alert alert-error"><i class="icon-remove-circle"></i> 失敗</div>
	<? endif;?>
<? endif;?>

<form method="post" class="validation">
	<fieldset>
		<input type="hidden" name="key" value="<?=$row ? $row->uid : ''?>">
		
		<? if ($row == false):?>	
		<label>帳號</label>
		<input type="text" name="account" value="<?=$row ? $row->account : ''?>" class="required" minlength="3">
		
		<label>密碼</label>
		<input type="password" name="password" value="<?=$row ? $row->password : ''?>" class="required" minlength="6">
		<? else:?>
		
		<label>帳號</label>
		<input type="text" value="<?=$row->account?>" disabled>
		
		<label>密碼</label>
		<span class="help-block">
			<a href="<?=site_url("platform/modify_password/{$row->uid}")?>">修改密碼</a>
		</span>
		
		<? endif;?>
				
		<label>名稱</label>
		<input type="text" name="name" value="<?=$row ? $row->name : ''?>" class="required">
	
		<label>隸屬群組</label>
		<select name="role" class="required">
			<option value="">--</option>
			<? foreach($all_role->result() as $rs):?>
			<option value="<?=$rs->role?>" <?=($row && $row->role==$rs->role) ? "selected='selected'" : ""?>><?=$rs->role?> <?=$rs->role_desc?></option>
			<? endforeach;?>
		</select>
	
   		<div class="form-actions">
   			<button type="submit" class="btn"><i class="icon-ok"></i> 確認送出</button>
   		</div>
   		
	</fieldset>
</form>