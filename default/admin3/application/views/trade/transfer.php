<?php 
	$channels = $this->config->item('channels');
	$transaction_type = $this->config->item('transaction_type');
?>

<form method="get" action="<?=site_url("trade/transfer")?>" class="form-search">

	<div class="control-group">

		遊戲
		<select name="game" class="span2">
			<option value="">--</option>
			<? foreach($games->result() as $row):?>
			<option value="<?=$row->game_id?>" <?=($this->input->get("game")==$row->game_id ? 'selected="selected"' : '')?>><?=$row->name?></option>
			<? endforeach;?>
		</select>	
	
		伺服器
		<select name="server" class="span2">
			<option value="">--</option>
		</select>
		
		<select id="server_pool" style="display:none;">
			<? foreach($servers->result() as $row):?>
			<option value="<?=$row->id?>" <?=($this->input->get("server")==$row->id ? 'selected="selected"' : '')?> class="<?=$row->game_id?>"><?=$row->name?></option>
			<? endforeach;?>
		</select>
		
		<span class="sptl"></span>
				
		通路來源 
		<select name="channel" class="span2">
			<option value="">--</option>
			<? foreach($channels as $key => $channel):?>
			<option value="<?=$key?>" <?=($this->input->get("channel")==$key ? 'selected="selected"' : '')?>><?=$channel?></option>
			<? endforeach;?>
		</select>				
		
		<span class="sptl"></span>
		
		金流管道
		<select name="transaction_type" style="width:120px;">
			<option value="">--</option>
			<? foreach($transaction_type as $key => $type):?>
			<option value="<?=$key?>" <?=($this->input->get("transaction_type")==$key ? 'selected="selected"' : '')?>><?=$type?></option>
			<? endforeach;?>
		</select>		
				
	</div>
	
	<div class="control-group">	
		
		交易結果
		<select name="result" style="width:75px">
			<option value="">--</option>
			<option value="r0" <?=($this->input->get("result")=='r0' ? 'selected="selected"' : '')?>>初始</option>
			<option value="r1" <?=($this->input->get("result")=='r1' ? 'selected="selected"' : '')?>>成功</option>
			<option value="r2" <?=($this->input->get("result")=='r2' ? 'selected="selected"' : '')?>>失敗</option>
			<option value="r3" <?=($this->input->get("result")=='r3' ? 'selected="selected"' : '')?>>逾時</option>
			<option value="r4" <?=($this->input->get("result")=='r4' ? 'selected="selected"' : '')?>>其它</option>
		</select>	

		<span class="sptl"></span>	
		
		測試帳號
		<select name="test" style="width:100px">
			<option value="">--</option>
			<option value="only" <?=($this->input->get("test")=='only' ? 'selected="selected"' : '')?>>只列測試</option>
			<option value="no" <?=($this->input->get("test")=='no' ? 'selected="selected"' : '')?>>不包含</option>
		</select>			
		
		<span class="sptl"></span>				
		
		轉點時間
		<input type="text" name="start_date" value="<?=$this->input->get("start_date")?>" style="width:120px"> 至
		<input type="text" name="end_date" value="<?=$this->input->get("end_date")?>" style="width:120px" placeholder="現在">
		<a href="javascript:;" class="clear_date"><i class="icon-remove-circle" title="清除"></i></a>
		
	</div>
	
	<div class="control-group">
	
		<input type="text" name="id" value="<?=$this->input->get("id")?>" class="input-small" placeholder="#id">
		<input type="text" name="uid" value="<?=$this->input->get("uid")?>" class="input-small" placeholder="uid">		
		<input type="text" name="euid" value="<?=$this->input->get("euid")?>" class="input-small" placeholder="euid">
		<input type="text" name="order_no" value="<?=$this->input->get("order_no")?>" class="input-medium" placeholder="第三方訂單號">				
	
		<input type="submit" class="btn btn-small btn-inverse" name="action" value="查詢">
		<input type="submit" class="btn btn-small btn-warning" name="action" value="輸出">		
		
		<? if ($this->input->get("use_default") == false):?>
		<span class="sptl"></span>		
		<a href="?" class="btn btn-small"><i class="icon-remove"></i> 清除條件</a>
		<? endif;?>
		
	</div>
	
	<p class="text-info">
		<span class="label label-info">說明</span>
		查詢結果"白底"為儲值記錄，供查詢參考之用。	
	</p>		
	
</form>

<? if ( ! empty($query)):?>
	<? if ($query->num_rows() == 0): echo '<div class="none">查無資料</div>'; else: ?>
	
		<? 
		switch ($this->input->get("action")) 
		{
			case "查詢":
		?>
	
<div class="msg">總筆數:<?=$total_rows?></div>
<?=tran_pagination($this->pagination->create_links());?>

<table class="table table-bordered">
	<thead>
		<tr>
			<th style="width:60px;">訂單號</th>
			<th style="width:60px;">uid<div style="color:#777">euid</div></th>	
			<th style="width:60px;">轉點管道</th>			
			<th style="width:35px;">金額</th>
			<th style="width:80px;">遊戲伺服器</th>
			<th style="width:40px;">結果</th>
			<th style="width:80px;">訊息</th>			
			<th style="width:80px;">第三方訂單號</th>
			<th style="width:90px;">建立日期</th>	
			<th style="width:22px;"></th>
		</tr>
	</thead>
	<tbody>
		<? foreach($query->result() as $row):?>
		<tr class="<?=$row->billing_type=='2' ? $result_table[$row->result]["class"] : ''?>">
			<td><?=$row->id?>
			</td>
			<td title="帳號:">
				<a href="<?=site_url("member/view/{$row->uid}")?>"><?=$row->uid?></a>
				<a href="<?=site_url("trade/transfer?uid={$row->uid}&action=查詢")?>"><i class="icon-search"></i></a>
				<div style="color:#777"><?=$this->g_user->encode($row->uid)?></div>
			</td>
			<td><?=$row->transaction_type?>
				<div style="font-size:13px;">
					<? if ( ! empty($row->mycard_billing_id)):?>
					<a href="<?=site_url("trade/mycard?id={$row->mycard_billing_id}&action=查詢")?>" target="_blank" title="查詢這筆mycard訂單">#<?=$row->mycard_billing_id?></a>
					<? elseif ( ! empty($row->gash_billing_id)):?>
					<a href="<?=site_url("trade/gash?id={$row->gash_billing_id}&action=查詢")?>" target="_blank" title="查詢這筆gash訂單">#<?=$row->gash_billing_id?></a>
					<? elseif ( ! empty($row->transaction_id)):?>
					<a href="<?=site_url("trade/".str_replace("_billing", "" ,$row->transaction_type)."?id={$row->transaction_id}&action=查詢")?>" target="_blank" title="查詢這筆訂單">#<?=$row->transaction_id?></a>					
					<? elseif ($row->transaction_type == "omg_billing"):?>
					<a href="<?=site_url("trade/omg_api?billing_id={$row->id}&action=查詢")?>" title="查詢這筆omg交易" target="_blank">查詢</a> 
					<? endif;?>
				</div>
			</td>
			<td><?=$row->amount?></td>
			<td><?= $row->billing_type=='2' ? "({$row->game_abbr_name}){$row->server_name}" : "" ?></td>
			<td><?=$result_table[$row->result]["name"]?>
			</td>
			<td>
				<div style="width:80px; overflow:hidden;" title="<?=htmlspecialchars($row->note)?>">
					<?=htmlspecialchars($row->note)?>
				</div>
			</td>
			<td><?=$row->order_no?></td>
			<td><?=date("Y-m-d H:i", strtotime($row->create_time))?></td>		
			<td>
				<div class="btn-group">
					<button type="button" class="btn btn-mini dropdown-toggle" data-toggle="dropdown">
						<span class="caret"></span>
					</button>	
					<ul class="dropdown-menu pull-right">
						<li><a href="<?=site_url("trade/modify_order/{$row->id}")?>"><i class="icon-pencil"></i> 編輯</a></li>
						<? if (in_array($row->result, array("0", "3", "4"))):?>
						<li><a href="javascript:;" class="json_post_alert" url="/ajax/resend_transfer/<?=$row->id?>" ><i class="icon-repeat"></i> 重送交易</a></li>
						<? elseif ($row->result == '2' && $row->transaction_type == 'rc_billing'):?>
						<li><a href="javascript:;" class="json_post_alert" url="/ajax/resend_failed_order/<?=$row->id?>" ><i class="icon-repeat"></i> 重建訂單發送交易</a></li>
						<? endif;?>
					</ul>
				</div>			
			</td>				
		</tr>
		<? endforeach;?>
	</tbody>
</table>

<?=tran_pagination($this->pagination->create_links());?>

		<? }?>
	<? endif;?>
<? endif;?>

