<style>
#main table td {padding:4px;}
</style>

<div style="padding:20px;">

	<form method="post" action="<?=site_url("cpl_case/modify_json")?>" enctype="multipart/form-data">
		<input type="hidden" name="case_id" value="<?=$case ? $case->id : ''?>">
		<input type="hidden" name="hidden_server_id" value="<?=$case ? $case->server_id : ''?>">
	<table cellspacing="0" cellpadding="0" style="width:660px;">
		<tr>
			<td>發文字號：</td>
			<td >
				<input type="text" name="o_case_id" class="required" maxlength="20"  value="<?=$case ? $case->o_case_id: ''?>" placeholder="例:府建行二字第1073906069號" autocomplete="off">
			</td>
			<td>發文日期：</td>
			<td>
				<input type="text" name="o_case_date" value="<?=$case ? $case->o_case_date: ''?>" id="o_case_date" autocomplete="off">
			</td>
		</tr>
		<tr>
			<td>申訴人姓名：</td>
			<td >
				<input type="text" name="appellant" class="required" maxlength="10"  value="<?=$case ? $case->appellant: ''?>" placeholder="例:王大同" autocomplete="off">
			</td>
			<td>連絡電話：</td>
			<td >
				<input type="text" name="phone" maxlength="10"  value="<?=$case ? $case->phone: ''?>" placeholder="例:0922******">
			</td>
		</tr>
		<tr>
			<td>申訴原因：</td>
			<td colspan="3">
				<input type="text" name="reason" maxlength="25" style="width:360px;"  value="<?=$case ? $case->reason: ''?>" id="reason" placeholder="例:連線問題">
			</td>
		</tr>
		<tr>
			<td>遊戲：</td>
			<td colspan="3">
				<select name="game_id" style="width:150px;" id="game_id">
					<option value="">--請選擇--</option>
					<? foreach($games->result() as $row):?>
					<option value="<?=$row->game_id?>" <?=($case && $case->game_id==$row->game_id ? 'selected="selected"' : '')?>><?=$row->name?><?=($row->is_active)?"":" (停)"?></option>
					<? endforeach;?>
				</select>
				<select name="server_id" style="width:150px;" id="server_id">

				</select>
				<input type="text" name="role_name" class="required" maxlength="15"  value="<?=$case ? $case->role_name: ''?>" placeholder="角色名稱, 例:寶寶心裡苦#P✿">
			</td>
		</tr>

	</table>

	<div class="form-actions">
  		<button type="submit" class="btn">確認送出</button>
  	</div>

  	</form>

</div>
