<style>
<!--
#main table td {padding:4px;}
-->
</style>

<div style="padding:20px;">
	
	<form method="post" action="<?=site_url("service/modify_json")?>" enctype="multipart/form-data">
		<input type="hidden" name="question_id" value="<?=$question ? $question->id : ''?>">
		
	<table cellspacing="0" cellpadding="0" style="width:660px;">
		<tr>
			<td>遊戲名稱：</td>
			<td>
				<select name="game" class="required" style="width:150px;">
					<option value="">--請選擇--</option>
					<? foreach($games->result() as $row):?>
					<option value="<?=$row->game_id?>" <?=($question && $question->game_id==$row->game_id ? 'selected="selected"' : '')?>><?=$row->name?></option>
					<? endforeach;?>
				</select>	
			</td>
			<td>伺服器：</td>
			<td>
				<select name="server" class="required" style="width:150px;">
					<option value="">--請先選擇遊戲--</option>
				</select>
							
				<select id="server_pool" style="display:none;">
					<? foreach($servers->result() as $row):?>
					<option value="<?=$row->id?>" <?=($question && $question->server_id==$row->id ? 'selected="selected"' : '')?> class="<?=$row->game_id?>"><?=$row->name?></option>
					<? endforeach;?>
				</select>	
			</td>
		</tr>
		<tr>
			<td>角色名稱：</td>
			<td><input type="text" name="character_name" class="" maxlength="30" style="width:150px;" value="<?=$question ? $question->character_name : ''?>"></td>
			<td></td>
			<td></td>
		</tr>		
		<tr>
			<td style="vertical-align:top">提問描述：</td>
			<td colspan="3"><textarea rows="8" minlength="5" style="width:100%;" name="content" class="required"><?=$question ? preg_replace('/<br\\s*?\/??>/i','',$question->content): ''?></textarea></td>
		</tr>
		<tr>
			<td>聯絡電話：</td>
			<td>
				<input type="text" name="phone" value="<?=$question ? $question->phone : ''?>">
			</td>
			<td>E-mail：</td>
			<td>
				<input type="text" name="email" value="<?=$question ? $question->email : ''?>">
			</td>
		</tr>
	</table>
	
	<div class="form-actions">
  		<button type="submit" class="btn">確認送出</button>
  	</div>

  	</form>
  		
</div>
