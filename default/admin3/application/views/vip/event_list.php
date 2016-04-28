<?php 
	//$vip_type = $this->config->item('vip_type');
	$vip_status = $this->config->item('vip_event_status');
?>
<div id="func_bar">
	<a href="<?=site_url("vip/add_event")?>" class="btn btn-primary">+VIP活動</a>
</div>

<form method="get" action="<?=site_url("vip/event_list")?>" class="form-search">
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
		
		活動狀態
		<select name="status" style="width:90px">
			<option value="">--</option>		
						
			<? foreach($vip_status as $key => $status):?>
			<option value="<?=$key?>" <?=($this->input->get("status")===strval($key) ? 'selected="selected"' : '')?>><?=$status?></option>
			<? endforeach;?>
		</select>
		
		<span class="sptl"></span>
				
        活動鮮度
        <select name="is_old" style="width:150px;">
            <option value="new" <?=($this->input->get("is_old")=='new' ? 'selected="selected"' : '')?>>未過期</option>
            <option value="old" <?=($this->input->get("is_old")=='old' ? 'selected="selected"' : '')?>>已過期</option>
            <option value="all" <?=($this->input->get("is_old")=='all' ? 'selected="selected"' : '')?>>全部</option>
        </select>
        
		<span class="sptl"></span>
		<input type="submit" class="btn btn-small btn-inverse" name="action" value="查詢">		
	</div>
	
</form>

<? if ($query):?>

<table class="table table-striped table-bordered" style="width:auto;">
	<thead>
		<tr>
			<th style="width:60px;">開始</th>
			<th style="width:60px;">結束</th>
			<th style="width:80px">遊戲</th>
			<th style="width:120px;">活動名稱</th>
			<th style="width:80px;">累計金額</th>
			<th style="width:60px;">訂單-取消</th>
			<th style="width:60px;">訂單-待匯款</th>
			<th style="width:60px;">訂單-匯款完成</th>
			<th style="width:60px;">訂單-派發完成</th>
			<th style="width:60px;">訂單-結案</th>	
			<th style="width:60px;">活動狀態</th>	
		</tr>
	</thead>
	<tbody>
		<? if ($query->num_rows() == 0):?>
				
		<tr>
			<td colspan="11">
				<div style="padding:10px; color:#777;">查無記錄</div>
			</td>
		</tr>

		<? else:?>
		
		<? foreach($query->result() as $row):?>
		<tr>
			<td><?=date('Y-m-d', strtotime($row->start_date))?></td>
			<td><?=($row->end_date=='0000-00-00 00:00:00')?"":date('Y-m-d', strtotime($row->end_date))?></td>
			<td><?=$row->game_name?></td>
			<td style="word-break: break-all">
            <?
				$type = $this->config->item("vip_event_type");
				echo "【".$type[$row->type]."】";
			?>
				<a href="<?=site_url("vip/event_view/{$row->id}")?>"><?=mb_strimwidth(strip_tags($row->title), 0, 66, '...', 'utf-8')?></a>
			</td>
			<td><?=$row->total?></td>
			<td><a href="<?=site_url("vip/event_view/{$row->id}?ticket_status=0")?>"><?=$row->cancelled_count?></a></td>
			<td><a href="<?=site_url("vip/event_view/{$row->id}?ticket_status=1")?>"><?=$row->pending_count?></a></td>
			<td><a href="<?=site_url("vip/event_view/{$row->id}?ticket_status=2")?>"><?=$row->complete_count?></a></td>
			<td><a href="<?=site_url("vip/event_view/{$row->id}?ticket_status=3")?>"><?=$row->delivered_count?></a></td>
			<td><a href="<?=site_url("vip/event_view/{$row->id}?ticket_status=4")?>"><?=$row->closed_count?></a></td>
			<td style="color:<?=($row->status==2)?"green":"red"?>">
			<?
				$status = $this->config->item("vip_event_status");
				echo $status[$row->status];
			?>
			</td>
		</tr>
		<? endforeach;?>
		
		<? endif;?>
		
	</tbody>
</table>
<? endif;?>