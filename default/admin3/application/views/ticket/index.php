<?php 
	$ticket_type = $this->config->item('ticket_type');
	$ticket_status = $this->config->item('ticket_status');
	$ticket_urgency = $this->config->item('ticket_urgency');
?>
<table class="table table-bordered" style="width:auto;">
	<caption>追殺清單</caption>
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
		<? if ($requester->num_rows() == 0):?>
				
		<tr>
			<td colspan="10">
				<div style="padding:10px; color:#777;">已完成</div>
			</td>
		</tr>

		<? else:?>
		
		<? foreach($requester->result() as $row):?>
		<tr class="<?=($row->status=='2' || $row->status=='3')?"warning":""?>">
			<td><a href="<?=site_url("ticket/view/{$row->id}")?>"><?=$row->id?></a></td>
			<td><?=$row->game_name?></td>
			<td><?=$ticket_urgency[$row->urgency]?></td>
			<td style="word-break: break-all">
				<span style="font-size:12px;">【<?=$ticket_type[$row->type]?>】</span>
				<a href="<?=site_url("ticket/view/{$row->id}")?>"><?=mb_strimwidth(strip_tags($row->title), 0, 66, '...', 'utf-8')?></a>
			</td>
			<td><?=$ticket_status[$row->status]?>
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
	
<table class="table table-bordered" style="width:auto;">
	<caption>受指派工作單</caption>
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
		<? if ($allocated->num_rows() == 0):?>
				
		<tr>
			<td colspan="10">
				<div style="padding:10px; color:#777;">已完成</div>
			</td>
		</tr>

		<? else:?>
		
		<? foreach($allocated->result() as $row):?>
		<tr class="<?=($row->status=='1')?"warning":""?>">
			<td><a href="<?=site_url("ticket/view/{$row->id}")?>"><?=$row->id?></a></td>
			<td><?=$row->game_name?></td>
			<td><?=$ticket_urgency[$row->urgency]?></td>
			<td style="word-break: break-all">
				<span style="font-size:12px;">【<?=$ticket_type[$row->type]?>】</span>
				<a href="<?=site_url("ticket/view/{$row->id}")?>"><?=mb_strimwidth(strip_tags($row->title), 0, 66, '...', 'utf-8')?></a>
			</td>
			<td><?=$ticket_status[$row->status]?>
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
