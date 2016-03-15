<style>
<!--
#main table td {padding:4px;}
-->
</style>

<div style="padding:20px;">
	
	<form method="post" action="<?=site_url("ticket/modify_json")?>" enctype="multipart/form-data">
		
	<table cellspacing="0" cellpadding="0" style="width:660px;">
		<tr>
			<td>指派人員：</td>
			<td>
				<select name="allocate_admin" class="required" style="width:150px;">
					<option value="">--請選擇--</option>
					<? foreach($admin_users->result() as $row):?>
					<option value="<?=$row->uid?>"><?=$row->name?></option>
					<? endforeach;?>
				</select>	
			</td>
			<td>通知其他人員：</td>
			<td>
				<select name="cc_admin" class="required" style="width:150px;">
					<option value="">--請選擇--</option>
					<? foreach($admin_users->result() as $row):?>
					<option value="<?=$row->uid?>"><?=$row->name?></option>
					<? endforeach;?>
				</select>	
			</td>
			<td>需求程度：</td>
			<td>
				<select name="urgency" class="required" style="width:150px;">
					<option value="0">普</option>
					<option value="1">高</option>
					<option value="2">急</option>
				</select>	
			</td>
		</tr>
		<tr>
			<td>遊戲：</td>
			<td>
				<select name="game" style="width:150px;">
					<option value="">--請選擇--</option>
					<? foreach($games->result() as $row):?>
					<option value="<?=$row->game_id?>"?><?=$row->name?></option>
					<? endforeach;?>
				</select>	
			</td>
			<td>工作分類：</td>
			<td>
				<select name="type" class="required" style="width:150px;">
					<option value="">--請選擇--</option>
                    <? foreach($this->config->item("ticket_type") as $id => $type):?>
                    <option value="<?=$id?>"><?=$type?></option>
                    <? endforeach;?>
				</select>	
			</td>
		</tr>
		<tr>
			<td>標題：</td>
			<td><input type="text" name="title" class="required" maxlength="30" style="width:150px;" value=""></td>
			<td></td>
			<td></td>
		</tr>		
		<tr>
			<td style="vertical-align:top">描述：</td>
			<td colspan="3"><textarea rows="8" minlength="5" style="width:100%;" name="content" class="required"></textarea></td>
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
