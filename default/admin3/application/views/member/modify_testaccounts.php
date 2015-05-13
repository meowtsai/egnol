<? if (isset($result)):?>
	<? if ($result):?>
	<div class="alert alert-success"><i class="icon-ok-circle"></i> 修改成功</div>
	<? else:?>
	<div class="alert alert-error"><i class="icon-remove-circle"></i> 失敗</div>
	<? endif;?>
<? endif;?>

<form method="post" class="validation">
	<fieldset>
		<input type="hidden" name="id" value="<?=$row ? $row->id : ''?>">
						
		
		<? if ($row):?>
		
		<label>帳號</label>
		<span class="help-block"><?=$row->uid?> (<?=$row->account?>)</span>
		
		<? else:?>
		
		<label>uid</label>
		<input type="text" name="uid" value="" class="required number">
		
		<label>account</label>
		<input type="text" name="account" value="" class="required">
		
		<? endif;?>
						
		<label>備註</label>
		<input type="text" name="note" value="<?=$row ? $row->note : ''?>" class="required">
	
   		<div class="form-actions">
   			<button type="submit" class="btn"><i class="icon-ok"></i> 確認送出</button>
   		</div>
   		
	</fieldset>
</form>