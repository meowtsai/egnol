<style>
#main table td {padding:4px;}
</style>

<div style="padding:20px;">

	<form method="post" action="<?=site_url("gov_letter/modify_json")?>" enctype="multipart/form-data">
		<input type="hidden" name="letter_id" value="<?=$letter ? $letter->id : ''?>">
		<input type="hidden" name="hidden_server_id" value="<?=$letter ? $letter->server_id : ''?>">
	<table cellspacing="0" cellpadding="0" style="width:660px;">
		<tr>
			<td>發文字號：</td>
			<td >
				<input type="text" name="o_letter_id" class="required" maxlength="30"  value="<?=$letter ? $letter->o_letter_id: ''?>" placeholder="例:高市警旗分偵字第10771557500號" autocomplete="off">
			</td>
			<td>承辦人姓名：</td>
			<td >
				<input type="text" name="contact" class="required" maxlength="10"  value="<?=$letter ? $letter->contact: ''?>" placeholder="例:偵查佐賴鎮生" autocomplete="off">
			</td>
		</tr>
		<tr>
			<td>發文日期：</td>
			<td>
				<input type="text" name="o_letter_date" value="<?=$letter ? $letter->o_letter_date: ''?>" id="o_letter_date" autocomplete="off">
			</td>
			<td>回文期限：</td>
			<td>
				<input type="text" name="deadline" value="<?=$letter ? $letter->deadline: ''?>" id="deadline" autocomplete="off">
			</td>
		</tr>
		<tr>
			<td>處理狀態：</td>
			<td>
				<select name="status" style="width:120px;">
					<option value="1" <?=$letter ? ($letter->status=='1' ? 'selected' : '') : ''?>>處理中</option>
					<option value="4" <?=$letter ? ($letter->status=='4' ? 'selected' : '') : ''?>>已結案</option>
				</select>
			</td>
			<td>結案日期：</td>
			<td>
				<input type="text" name="close_date" value="<?=$letter ? $letter->close_date: ''?>" id="close_date" autocomplete="off">
			</td>
		</tr>

		<tr>
			<td>備註記事：</td>
			<td colspan="3">
				<textarea name="note" rows="5" style="width:98%" class="required" ><?=$letter ? preg_replace('!<br.*>!iU', "", $letter->note ): ''?> </textarea>
			</td>
		</tr>
		<tr>
			<td>遊戲：</td>
			<td colspan="3">
				<select name="game_id" style="width:150px;" id="game_id">
					<option value="">--請選擇--</option>
					<? foreach($games->result() as $row):?>
					<option value="<?=$row->game_id?>" <?=($letter && $letter->game_id==$row->game_id ? 'selected="selected"' : '')?>><?=$row->name?><?=($row->is_active)?"":" (停)"?></option>
					<? endforeach;?>
				</select>
				<select name="server_id" style="width:150px;" id="server_id">

				</select>
				<input type="text" name="role_name"  maxlength="15"  value="<?=$letter ? $letter->role_name: ''?>" placeholder="角色名稱, 例:米迦聿">
			</td>
		</tr>

		<tr>
			<td>相關檔案：</td>
			<td colspan="3">
					<?if ($letter && $letter->file_path):?>
					<a href="<?=$letter->file_path?>" target="_blank">檔案</a>
					<?endif;?>
					<input type="file" name="file01">
					<input type="hidden" name="file_path" class="" value="<?=$letter ? $letter->file_path : ''?>">

			</td>
		</tr>



	</table>
	<div class="form-actions">
		<button type="submit" class="btn">確認送出</button>
	</div>
</form>

</div>
