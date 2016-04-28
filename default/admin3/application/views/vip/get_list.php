<?php 
	$vip_type = $this->config->item('vip_type');
	$vip_status = $this->config->item('vip_status');
	$vip_urgency = $this->config->item('vip_urgency');
?>
<div id="func_bar">
</div>

<form method="get" action="<?=site_url("vip/get_list")?>" class="form-search">
	<input type="hidden" name="game_id" value="<?=$this->game_id?>">
	
	<div class="control-group">	
		
		遊戲
		<select name="game" style="width:120px">
			<option value="">--</option>
			<? foreach($games->result() as $row):?>
			<option value="<?=$row->game_id?>" <?=($this->input->get("game")==$row->game_id ? 'selected="selected"' : '')?>><?=$row->name?><?=($row->is_active)?"":" (停)"?></option>
			<? endforeach;?>
		</select>		
			
		<span class="sptl"></span>
		
		類型 
		<select name="type" style="width:100px">
			<option value="">--</option>
			<? foreach($vip_type as $key => $type):?>
			<option value="<?=$key?>" <?=($this->input->get("type")==$key ? 'selected="selected"' : '')?>><?=$type?></option>
			<? endforeach;?>
		</select>
		
		<span class="sptl"></span>
		
		狀態
		<select name="status" style="width:90px">
			<option value="">--</option>		
						
			<? foreach($vip_status as $key => $status):?>
			<option value="<?=$key?>" <?=($this->input->get("status")===strval($key) ? 'selected="selected"' : '')?>><?=$status?></option>
			<? endforeach;?>
		</select>
		
		<span class="sptl"></span>
		
		需求程度
		<select name="urgency" style="width:90px">
			<option value="">--</option>		
						
			<? foreach($vip_urgency as $key => $urgency):?>
			<option value="<?=$key?>" <?=($this->input->get("urgency")===strval($key) ? 'selected="selected"' : '')?>><?=$urgency?></option>
			<? endforeach;?>
		</select>
		
	</div>
	
	<div class="control-group">
		
		<input type="text" name="title" value="<?=$this->input->get("title")?>" style="width:120px" placeholder="標題">
		<input type="text" name="content" value="<?=$this->input->get("content")?>" style="width:120px" placeholder="提問描述">
		<input type="text" name="vip_id" value="<?=$this->input->get("vip_id")?>" style="width:90px" placeholder="#id">
		
		<span class="sptl"></span>
				
		建檔時間
		<input type="text" name="start_date" value="<?=$this->input->get("start_date")?>" style="width:120px"> 至
		<input type="text" name="end_date" value="<?=$this->input->get("end_date")?>" style="width:120px" placeholder="現在">
		<a href="javascript:;" class="clear_date"><i class="icon-remove-circle" title="清除"></i></a>
        
	</div>
	
	<div class="control-group">
        需求人員
        <select name="admin" style="width:150px;">
            <option value="">--請選擇--</option>
            <? foreach($admin_users->result() as $row):?>
            <option value="<?=$row->uid?>" <?=($this->input->get("vip_id")==$row->uid ? 'selected="selected"' : '')?>><?=$row->name?> (<?=$row->role_desc?>)</option>
            <? endforeach;?>
        </select>	
        指派人員
        <select name="allocate_admin" style="width:150px;">
            <option value="">--請選擇--</option>
            <? foreach($admin_users->result() as $row):?>
            <option value="<?=$row->uid?>" <?=($this->input->get("vip_id")==$row->uid ? 'selected="selected"' : '')?>><?=$row->name?> (<?=$row->role_desc?>)</option>
            <? endforeach;?>
        </select>	
        通知人員
        <select name="cc_admin" style="width:150px;">
            <option value="">--請選擇--</option>
            <? foreach($admin_users->result() as $row):?>
            <option value="<?=$row->uid?>" <?=($this->input->get("vip_id")==$row->uid ? 'selected="selected"' : '')?>><?=$row->name?> (<?=$row->role_desc?>)</option>
            <? endforeach;?>
        </select>	
		
		<span class="sptl"></span>
		
		<input type="submit" class="btn btn-small btn-inverse" name="action" value="查詢">		
		
		<? if ($this->input->get("use_default") == false):?>
		<span class="sptl"></span>		
		<a href="?" class="btn btn-small"><i class="icon-remove"></i> 重置條件</a>
		<? endif;?>
		
	</div>
		
</form>

<? if ($query):?>

<? 
		switch ($this->input->get("action")) 
		{
			case "查詢":
				$get = $this->input->get();
				$get['sort'] = 'expense';
				$query_string = http_build_query($get);
		?>
	
<span class="label label-warning">總筆數<?=$total_rows?></span>

<?=tran_pagination($this->pagination->create_links());?>

<table class="table table-striped table-bordered" style="width:auto;">
	<thead>
		<tr>
			<th style="width:60px;">#</th>
			<th style="width:80px">遊戲</th>
			<th style="width:80px">需求程度</th>
			<th style="width:120px">標題</th>
			<th style="width:80px;">狀態</th>
			<th style="width:80px;">需求人員</th>
			<th style="width:80px;">處理人員</th>
			<th style="width:100px;">日期</th>		
		</tr>
	</thead>
	<tbody>
		<? if ($query->num_rows() == 0):?>
				
		<tr>
			<td colspan="10">
				<div style="padding:10px; color:#777;">查無記錄</div>
			</td>
		</tr>

		<? else:?>
		
		<? foreach($query->result() as $row):?>
		<tr class="<?=($row->status==1 && $row->allocate_admin_uid==$_SESSION['admin_uid'])?"warning":""?>">
			<td><a href="<?=site_url("vip/view/{$row->id}")?>"><?=$row->id?></a></td>
			<td><?=$row->game_name?></td>
			<td><?=$vip_urgency[$row->urgency]?></td>
			<td style="word-break: break-all">
				<span style="font-size:12px;">【<?=$vip_type[$row->type]?>】</span>
				<a href="<?=site_url("vip/view/{$row->id}")?>"><?=mb_strimwidth(strip_tags($row->title), 0, 66, '...', 'utf-8')?></a>
			</td>
			<td><?=$vip_status[$row->status]?>
				<div style="font-size:11px;"> 
				
				<?  if ($row->status == '2' || $row->status == '4'):?>				
					<?= $row->is_read ? '<span style="color:#090">(已讀)</span>' : '<span style="color:#999">(未讀)</span>'; ?>				
				<? endif;?>
				
				</div>
			</td>	
			<td><?=$row->name?></td>
			<td><?=$row->allocate_user_name?></td>
			<td><?=date("Y-m-d H:i", strtotime($row->create_time))?></td>
		</tr>
		<? endforeach;?>
		
		<? endif;?>
		
	</tbody>
</table>

		<? 
			break;				
		?>

		<? } ?>
<? endif;?>