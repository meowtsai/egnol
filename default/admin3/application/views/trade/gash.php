<?php 
	$gash_conf = $this->config->item('gash');
?>

<form method="get" action="<?=site_url("trade/gash")?>" class="form-search">

	<div class="control-group">
						
		<select name="country" style="width:70px">
			<option value="">--</option>
			<option value="tw" <?=($this->input->get("country")=="tw" ? 'selected="selected"' : '')?>>國內</option>
			<option value="global" <?=($this->input->get("country")=="global" ? 'selected="selected"' : '')?>>海外</option>
		</select>		
		
		<span class="sptl"></span>						
						
		交易管道
		<select name="PAID" class="span2">
			<option value="">--</option>
			<? foreach($gash_conf["PAID"] as $key => $channel):?>
			<option value="<?=$key?>" <?=($this->input->get("PAID")==$key ? 'selected="selected"' : '')?>><?=$channel?></option>
			<? endforeach;?>
		</select>				
				
	</div>
	
	<div class="control-group">	
		
		交易結果
		<select name="PAY_STATUS" style="width:75px">
			<option value="">--</option>
			<option value="F" <?=($this->input->get("PAY_STATUS")=='F' ? 'selected="selected"' : '')?>>失敗</option>
			<option value="S" <?=($this->input->get("PAY_STATUS")=='S' ? 'selected="selected"' : '')?>>成功</option>
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
	
		<input type="text" name="COID" value="<?=$this->input->get("COID")?>" class="input-medium" placeholder="訂單號">
		<input type="text" name="RRN" value="<?=$this->input->get("RRN")?>" class="input-medium" placeholder="GPS交易編號">				
	
	</div>
		
	<div class="control-group">
		
		<input type="text" name="id" value="<?=$this->input->get("id")?>" class="input-small" placeholder="#id">
		<input type="text" name="uid" value="<?=$this->input->get("uid")?>" class="input-small" placeholder="uid">		
		<input type="text" name="euid" value="<?=$this->input->get("euid")?>" class="input-small" placeholder="euid">
		<input type="text" name="account" value="<?=$this->input->get("account")?>" class="input-medium" placeholder="帳號">			
	
		<input type="submit" class="btn btn-small btn-inverse" name="action" value="查詢">
		<input type="submit" class="btn btn-small btn-warning" name="action" value="輸出">		
		
		<? if ($this->input->get("use_default") == false):?>
		<span class="sptl"></span>		
		<a href="?" class="btn btn-small"><i class="icon-remove"></i> 清除條件</a>
		<? endif;?>
		
	</div>
	
	<p class="text-info">
		<span class="label label-info">說明</span>
		--
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

<table class="table table-bordered" style="width:auto">
	<thead>
		<tr>
			<th style="width:50px;">#</th>
			<th style="width:70px;">uid
				<div style="color:#777;">euid</div></th>	
			<th style="width:100px;">交易管道</th>
			<th style="width:100px;">訂單號</th>
			<th style="width:100px;">GPS交易編號</th>						
			<th style="width:35px;">金額</th>
			<th style="width:50px;">結果</th>
			<th style="width:120px;">訊息</th>
			<th style="width:70px;">建立日期</th>	
			<th style="width:22px;">
		</tr>
	</thead>
	<tbody>
		<? foreach($query->result() as $row):?>
		<tr class="<?=$row->status=='2' ? 'success' : ''?>">
			<td><?=$row->id?></td>
			<td title="帳號: <?=$row->account?>">
				<a href="<?=site_url("member/view/{$row->uid}")?>"><?=$row->uid?></a>
				<a href="<?=site_url("trade/gash?uid={$row->uid}&action=查詢")?>"><i class="icon-search"></i></a>
				<div style="color:#777;"><?=$this->g_user->encode($row->uid)?></div>
			</td>
			<td><?=$gash_conf["PAID"][$row->PAID]?>(<?=$gash_conf["CUID"][$row->CUID]?>)
			</td>
			<td><?=$row->COID?></td>
			<td><?=$row->RRN?></td>
			<td><?=$row->AMOUNT?></td>
			<td><?=$row->status=='2' ? '成功' : ($row->status=='1' ? '未請款' : '失敗')?></td>
			<td>
				<? 
					if ( ! empty($row->RCODE) ) 
					{
						if ($row->RCODE == '0000') {
							 if ($row->PAY_RCODE <> '0000') echo $row->PAY_RCODE.' '.$gash_conf["RCODE"][$row->PAY_RCODE];			
						}
						else echo $row->RCODE.' '.$gash_conf["RCODE"][$row->RCODE];
					}		
					echo ' '.$row->note;						
				?>
			</td>
			<td><?=date("Y-m-d H:i", strtotime($row->create_time))?></td>
			<td>
				<div class="btn-group">
					<button type="button" class="btn btn-mini dropdown-toggle" data-toggle="dropdown">
						<span class="caret"></span>
					</button>	
					<ul class="dropdown-menu pull-right">						
						<? if ($row->status=='2'):?>
						<li><a href="javascript:;" class="json_post_alert" url="http://www.long_e.com.tw/ajax/redo_gash_billing/<?=$row->id?>"></i> 重做儲值</a></li>
						<? else:?>
						<li><a href="javascript:;" class="json_post_alert" url="http://www.long_e.com.tw/ajax/resend_gash_billing/<?=$row->id?>"></i> 重送交易</a></li>
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

