<style>
<!--
#question {}
#question table.officail {background:#e7efe5;}
#content {width:600px;}
-->
</style>

<legend>工作單編號 #<?=$ticket->id?></legend>	

<div id="func_bar">
  <? if ($ticket->status <> '4'):?>
  
	  <form id="allocate_form" method="post" action="<?=site_url("ticket/allocate_json")?>" style="margin:0; display:inline-block">
		<input type="hidden" name="ticket_id" value="<?=$ticket->id?>">	
		<input type="hidden" name="allocate_result" value="<?=$ticket->allocate_result?>">
		
		<div style="line-height:32px;">
			後送給 
			<select name="allocate_admin_uid" style="width:120px; margin-bottom:0;">
				<? foreach($allocate_users->result() as $row):?>
				<option value="<?=$row->uid?>"><?=$row->name?></option>
				<? endforeach;?>
			</select>
			<br>
			<input type="text" name="result" style="width:200px; margin:0;" placeholder="後送描述">
			<br>
			<div style="text-align:right;"><input type="submit" class="btn" value="送出"/></div>
		</div>
	  </form>
  
	<? if ($ticket->type == '9'):?>	
	 | <a href="<?=site_url("ticket/edit/{$ticket->id}")?>" class="btn btn-primary">編輯</a>
	<? endif;?>
  <? endif;?>	
</div>


<div id="ticket">
	
	<div id="content">
	
	<table class="table table-bordered">
		<tr>
			<th style="width:80px;">狀態：</th>
			<td colspan="3">
			<?
				$status = $this->config->item("ticket_status");
				echo $status[$ticket->status];
			?>
			
				<?  if ($ticket->status == '2' || $ticket->status == '4'):?>
					<?= $ticket->is_read ? '<span style="color:#090">(分派人員已讀)</span>' : '<span style="color:#999">(分派人員未讀)</span>'; ?>
				<? endif;?>			
				
	
	<? if ($ticket->allocate_status == 1):?>
	<span style="color:#999">(已後送給 <?=$ticket->allocate_user_name?>)</span>
	<? elseif ($ticket->allocate_status == 2):?>
	<span style="color:#009">(<?=$ticket->allocate_user_name?> 處理完畢)</span>
	<? endif;?>
	
	<? if ($ticket->allocate_result):?>
		<div style="color:#666; font-size:13px; margin:6px 0;"><?=$ticket->allocate_result?></div>
	<? endif;?>
	
	<? if ($ticket->allocate_status == 1):?>
	<div style="margin:8px 0 0;">	
	  <form id="result_form" method="post" action="<?=site_url("ticket/finish_allocate_json")?>" style="margin:0;">
		<input type="hidden" name="ticket_id" value="<?=$ticket->id?>">
		<input type="hidden" name="allocate_result" value="<?=$ticket->allocate_result?>">
		
		<input type="text" name="result" style="width:300px; margin:0;" placeholder="處理描述">	
		<input type="submit" class="btn" value="處理完成"/>
	  </form>
	</div>	
	<? endif;?>	
	
			</td>
		</tr>
		<tr>
			<th>類型：</th>
			<td colspan="3">
			<?
				$ticket_type = $this->config->item("ticket_type");
				echo $ticket_type[$ticket->type];
			?>
			</td>
		</tr>
		<tr>
			<th>遊戲名稱：</th>
			<td><?=$ticket->game_name?></td>
			<th style="width:80px;">遊戲：</th>
			<td><?=$ticket->game_name?></td>
		</tr>
		<? if ($ticket->type <> '9'):?>
		<tr>
			<th>uid：</th>
			<td colspan="3">
				<?=$ticket->admin_uid?>
			</td>
		</tr>	
		<? endif;?>		
		<tr>
			<th>建單日期：</th>
			<td colspan="3">
			<?=$ticket->create_time?>
			</td>
		</tr>			
		<tr>
			<th style="vertical-align:top">描述：</th>
			<td colspan="3" style="word-break: break-all"><?=$ticket->content?></td>
		</tr>		
		<tr>
			<th>截圖：</th>
			<td colspan="3">
				<? if ($ticket->pic_path1):?>
				<a href="<?=$ticket->pic_path1?>" target="_blank">
					<img src="<?=$ticket->pic_path1?>" style="max-width:400px;">
				</a>
				<? endif;?>
				<? if ($ticket->pic_path2):?>
				<a href="<?=$ticket->pic_path2?>" target="_blank">
					<img src="<?=$ticket->pic_path2?>" style="max-width:400px;">
				</a>
				<? endif;?>
				<? if ($ticket->pic_path3):?>
				<a href="<?=$ticket->pic_path3?>" target="_blank">
					<img src="<?=$ticket->pic_path3?>" style="max-width:400px;">
				</a>
				<? endif;?>								
			</td>
		</tr>
		<tr>
			<th>備註：</th>
			<td colspan="3">
				<form id="note_form" method="post" action="<?=site_url("ticket/update_note_json")?>" style="margin:0">
					<input type="hidden" name="ticket_id" value="<?=$ticket->id?>">	
					<textarea name="note" rows="3" style="width:80%" ><?=$ticket->note?></textarea>
					<button type="submit" class="btn" style="vertical-align:top;">儲存</button>
				</form>  			
			</td>
		</tr>
	</table>
	
	<? 
	$no = 1;
	foreach($replies->result() as $row):?>
	<table class="table table-bordered" style="position:relative;">
		<tr>
			<td style="width:120px; text-align:center;">
				NO<?=$no++?><br>
				<?=date('Y-m-d H:i', strtotime($row->create_time))?>
				<? if ($row->admin_uname):?>
				<div style="font-size:12px; color:#129;">(<?=$row->admin_uname?>)</div>
				<? endif;?>
			</td>
			<td style="word-break:break-all"><?=$row->content?></td>
		</tr>
	</table>
	<? endforeach;?>
		
	
	</div>		
		
	<? if ($ticket->status <> '4'):?>

	<form id="reply_form" method="post" action="<?=site_url("ticket/modify_reply_json")?>">
		<input type="hidden" name="ticket_id" value="<?=$ticket->id?>">	
		回覆
		<textarea name="content" rows="28" style="width:98%" class="required"></textarea>
		
		<div class="form-actions">
	  		<button type="submit" class="btn ">確認送出</button>
	  		<a href="javascript:;" url="<?=site_url("ticket/close_ticket/{$ticket->id}")?>" class="json_post pull-right btn btn-danger">結案</a>
	  	</div>
	</form> 
    <? else: ?> 

	<form id="reply_form" method="post" action="<?=site_url("ticket/modify_reply_json")?>">
		<div class="form-actions">
	  		<a href="javascript:;" url="<?=site_url("ticket/show_ticket/{$ticket->id}")?>" class="json_post pull-right btn btn-danger">調回處理中</a>
	  	</div>
	</form>
	<? endif;?>
	
</div>	