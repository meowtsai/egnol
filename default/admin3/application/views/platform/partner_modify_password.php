<? if (isset($result)):?>
	<? if ($result):?>
	<div class="alert alert-success"><i class="icon-ok-circle"></i> 修改成功</div>
	<? else:?>
	<div class="alert alert-error"><i class="icon-remove-circle"></i> 失敗</div>
	<? endif;?>
<? endif;?>

<form method="post" class="validation">
	<fieldset>
		<input type="hidden" name="key" value="<?=$row->uid?>">

		<label>帳號</label>
		<input type="text" name="account" value="<?=$row->account?>" disabled>
		
		<label>修改密碼</label>
		<input type="password" name="password" value="" class="required" minlength="6">
	
   		<div class="form-actions">
   			<button type="submit" class="btn"><i class="icon-ok"></i> 確認送出</button>
   		</div>
   		
	</fieldset>
</form>