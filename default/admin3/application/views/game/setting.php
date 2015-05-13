<? if (isset($result)):?>
	<? if ($result):?>
	<div class="alert alert-success"><i class="icon-ok-circle"></i> 修改成功</div>
	<? else:?>
	<div class="alert alert-error"><i class="icon-remove-circle"></i> 失敗</div>
	<? endif;?>
<? endif;?>

<form method="post" class="validation">
	<fieldset>
					
		<label>遊戲名稱</label>
		<input type="text" name="name" value="<?=$row ? $row->name : ''?>" class="required" style="width:200px">
		
		<label>遊戲簡稱</label>
		<input type="text" name="abbr" value="<?=$row ? $row->abbr : ''?>" class="required" style="width:150px">
		
		<label>轉點比值</label>
		<input type="text" name="exchange_rate" value="<?=$row ? $row->exchange_rate : ''?>" class="required number" style="width:50px;">
		
		<label>遊戲中金錢名稱</label>
		<input type="text" name="currency" value="<?=$row ? $row->currency : ''?>" class="required" style="width:150px">
		<span class="help-inline">例如：元寶</span>
	
   		<div class="form-actions">
   			<button type="submit" class="btn"><i class="icon-ok"></i> 確認送出</button>
   		</div>
   		
	</fieldset>
</form>