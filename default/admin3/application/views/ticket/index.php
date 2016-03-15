<?php 
	$ticket_type = $this->config->item('ticket_type');
	$ticket_status = $this->config->item('ticket_status');
	$ticket_urgency = $this->config->item('ticket_urgency');
?>
<table class="table table-bordered" style="width:auto;">
	<caption>分派工作單</caption>
	<thead>
		<tr>
			<th style="width:60px;">#</th>
			<th style="width:80px">遊戲</th>
			<th style="width:80px">需求程度</th>
			<th style="width:120px">標題</th>
			<th style="width:80px;">狀態</th>
			<th style="width:80px;">分派人員</th>
			<th style="width:80px;">處理人員</th>
			<th style="width:100px;">日期</th>		
		</tr>
	</thead>
	<tbody>
		<? if ($requester->num_rows() == 0):?>
				
		<tr>
			<td colspan="10">
				<div style="padding:10px; color:#777;">查無記錄</div>
			</td>
		</tr>

		<? else:?>
		
		<? foreach($requester->result() as $row):?>
		<tr>
			<td><a href="<?=site_url("ticket/view/{$row->id}")?>"><?=$row->id?></a></td>
			<td><?=$row->game_id?></td>
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
				
				<?  if ($row->allocate_status == '1'):?>
					<span style="color:#999">(後送中)</span>
				<? elseif ($row->allocate_status == '2'):?>
					<span style="color:#090">(後送完成)</span>
				<? endif;?>
				
				</div>
			</td>	
			<td><?=$row->admin_uid?></td>
			<td><?=$row->allocate_admin_uid?></td>
			<td><?=date("Y-m-d H:i", strtotime($row->create_time))?></td>
		</tr>
		<? endforeach;?>
		<? endif;?>
		
	</tbody>
	</table>
	
<table class="table table-bordered" style="width:auto;">
	<caption>合計</caption>
		<tr>
			<th style="width:120px"><a href="<?=site_url("service/get_list?status=1&action=查詢")?>">未回覆案件：</a></th>
			<td style="width:100px"><?=$stat->new_total?></td>
		</tr>
		<tr>
			<th><a href="<?=site_url("service/get_list?status=2&action=查詢")?>">等待中案件：</a></th>
			<td><?=$stat->success_total?></td>
		</tr>
		<tr>
			<th><a href="<?=site_url("service/get_list?status=4&action=查詢")?>">結案：</a></th>
			<td><?=$stat->close_total?></td>
		</tr>			
		<tr>
			<th><a href="<?=site_url("service/get_list?status=0&action=查詢")?>">隱藏：</a></th>
			<td><?=$stat->hidden_total?></td>
		</tr>
		<tr>
			<th><a href="<?=site_url("service/get_list?type=9&action=查詢")?>">電話案件：</a></th>
			<td><?=$stat->phone_total?></td>
		</tr>							
	</table>	
	
<table class="table table-bordered" style="width:auto;">
	<caption>後送案件</caption>
<? if ( ! empty($allocate[1])):?>	
		<tr>
			<th><a href="<?=site_url("service/get_list?allocate_status=1&action=查詢")?>">後送中：</a></th>
			<td>
			<? foreach($allocate[1] as $row):?>
			<span style="display:inline-block; padding:1px 4px;"><a href="<?=site_url("service/get_list?allocate_status=1&allocate_auid={$row->uid}&action=查詢")?>"><?=$row->name?>(<?=$row->cnt?>)</a></span>
			<? endforeach;?>
			</td>
		</tr>
<? endif;?>		

<? if ( ! empty($allocate[2])):?>
		<tr>
			<th><a href="<?=site_url("service/get_list?allocate_status=2&action=查詢")?>">完成：</a></th>
			<td>
			<? foreach($allocate[2] as $row):?>
			<span style="display:inline-block; padding:1px 4px;"><a href="<?=site_url("service/get_list?allocate_status=2&allocate_auid={$row->uid}&action=查詢")?>"><?=$row->name?>(<?=$row->cnt?>)</a></span>
			<? endforeach;?>
			</td>
		</tr>	
<? endif;?>				
					
	</table>			
