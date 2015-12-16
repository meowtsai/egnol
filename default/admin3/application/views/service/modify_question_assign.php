<style>
<!--
#main table td {padding:4px;}
-->
</style>

<div style="padding:20px;">
	
	<form method="post" action="<?=site_url("service/modify_question_assign_json")?>" enctype="multipart/form-data">
		<input type="hidden" name="id" value="<?=$question_assigns ? $question_assigns->id : ''?>">
		<input type="hidden" name="back_url" value="<?=$back_url?>">
		
	<table cellspacing="0" cellpadding="0" style="width:660px;">
		<tr>
			<td>來源：</td>
			<td>
				<select name="source">
					<option value="信件">信件</option>
				</select>				
			</td>
		</tr>		
		<tr>
			<td style="vertical-align:top">交接事項：</td>
			<td><textarea rows="8" minlength="2" maxlength="500" style="width:100%;" name="desc" class="required"><?=$question_assigns ? preg_replace('/<br\\s*?\/??>/i','',$question_assigns->desc): ''?></textarea></td>
		</tr>
		<tr>
			<td style="vertical-align:top">處理狀況：</td>
			<td><textarea rows="8" minlength="2" maxlength="500" style="width:100%;" name="result" class="required"><?=$question_assigns ? preg_replace('/<br\\s*?\/??>/i','',$question_assigns->result): ''?></textarea></td>
		</tr>
		<tr>
			<td style="vertical-align:top">案件狀態：</td>
			<td>
				<select name="status">
					<option value="1" <?=$question_assigns ? ($question_assigns->status == "1" ? 'selected="selected"' : '') : '' ?>>處理中</option>
					<option value="2" <?=$question_assigns ? ($question_assigns->status == "2" ? 'selected="selected"' : '') : '' ?> >結案</option>
				</select>
			</td>
		</tr>	
		<tr>
			<td>
				<? if ($question_assigns) echo '重新交付人員 (未勾選，則不變更)';
					else echo '預訂交接人員';
				?>				
			</td>
			<td>
				<? foreach($users->result() as $row):?>
					<label><input type="checkbox" name="targets[]" value="<?=$row->uid?>"> <?=$row->name?></label>
				<? endforeach;?>
				
				<label><input type="checkbox" name="targets[]" value="8"> bean</label>
			</td>
		</tr>	
	</table>
	
	<div class="form-actions">
  		<button type="submit" class="btn">確認送出</button>
  	</div>

  	</form>
  		
</div>
