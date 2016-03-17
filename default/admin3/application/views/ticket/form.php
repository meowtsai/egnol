<style>
<!--
#main table td {padding:4px;}
-->
</style>

<div style="padding:20px;">
	
	<form method="post" action="<?=site_url("ticket/modify_json")?>" enctype="multipart/form-data">
		<input type="hidden" name="ticket_id" value="<?=$ticket ? $ticket->id : ''?>">
		
	<table cellspacing="0" cellpadding="0" style="width:660px;">
		<tr>
			<td>指派人員：</td>
			<td>
				<select name="allocate_admin" class="required" style="width:150px;">
					<option value="">--請選擇--</option>
					<? foreach($admin_users->result() as $row):?>
					<option value="<?=$row->uid?>" <?=($ticket && $ticket->allocate_admin_uid==$row->uid ? 'selected="selected"' : '')?>><?=$row->name?> (<?=$row->role_desc?>)</option>
					<? endforeach;?>
				</select>	
			</td>
			<td>通知其他人員：</td>
			<td>
				<select name="cc_admin" class="required" style="width:150px;">
					<option value="">--請選擇--</option>
					<? foreach($admin_users->result() as $row):?>
					<option value="<?=$row->uid?>" <?=($ticket && $ticket->cc_admin_uid==$row->uid ? 'selected="selected"' : '')?>><?=$row->name?> (<?=$row->role_desc?>)</option>
					<? endforeach;?>
				</select>	
			</td>
		</tr>
		<tr>
			<td>遊戲：</td>
			<td colspan="3">
				<select name="game" style="width:150px;">
					<option value="">--請選擇--</option>
					<? foreach($games->result() as $row):?>
					<option value="<?=$row->game_id?>" <?=($ticket && $ticket->game_id==$row->game_id ? 'selected="selected"' : '')?>><?=$row->name?><?=($row->is_active)?"":" (停)"?></option>
					<? endforeach;?>
				</select>	
			</td>
		</tr>
		<tr>
			<td>需求程度：</td>
			<td>
				<select name="urgency" class="required" style="width:150px;">
					<option value="0" <?=($ticket && $ticket->urgency=="0" ? 'selected="selected"' : '')?>>普</option>
					<option value="1" <?=($ticket && $ticket->urgency=="1" ? 'selected="selected"' : '')?>>高</option>
					<option value="2" <?=($ticket && $ticket->urgency=="2" ? 'selected="selected"' : '')?>>急</option>
				</select>	
			</td>
			<td>工作分類：</td>
			<td>
				<select name="type" class="required" style="width:150px;">
					<option value="">--請選擇--</option>
                    <? foreach($this->config->item("ticket_type") as $id => $type):?>
                    <option value="<?=$id?>" <?=($ticket && $ticket->type==$id ? 'selected="selected"' : '')?>><?=$type?></option>
                    <? endforeach;?>
				</select>	
			</td>
		</tr>	
		<tr>
			<td>標題：</td>
			<td colspan="3"><input type="text" name="title" class="required" maxlength="60" style="width:300px;" value="<?=$ticket ? preg_replace('/<br\\s*?\/??>/i','',$ticket->title): ''?>"></td>
		</tr>		
		<tr>
			<td style="vertical-align:top">描述：</td>
			<td colspan="3"><textarea rows="8" minlength="5" style="width:100%;" name="content" class="required"><?=$ticket ? preg_replace('/<br\\s*?\/??>/i','',$ticket->content): ''?></textarea></td>
		</tr>
        
        <tr>
            <th>附件</th><td style="white-space:pre-wrap;"><input type="file" name="file01" class="pic_input" /></td>
        </tr>
        <tr>
            <th>&nbsp;</th><td style="white-space:pre-wrap;"><input type="file" name="file02" class="pic_input"></td>
        </tr>
        <tr>
            <th>&nbsp;</th><td style="white-space:pre-wrap;"><input type="file" name="file03" class="pic_input"></td>
        </tr>
        <!--tr>
            <th></th>
            <td style="white-space:pre-wrap;">圖檔可接受格式：jpg、png、gif、bmp<br/>最大尺寸 6144x6144 畫素，容量最大 6MB。</td>
        </tr-->
	</table>
	
	<div class="form-actions">
  		<button type="submit" class="btn">確認送出</button>
  	</div>

  	</form>
  		
</div>
