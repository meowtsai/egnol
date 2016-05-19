<form method="get" action="<?=site_url("trade/vip")?>" class="form-search">
	
	<div class="control-group">	
		
		交易結果
		<select name="status" style="width:75px">
			<option value="">--</option>
			<option value="F" <?=($this->input->get("status")=='F' ? 'selected="selected"' : '')?>>失敗</option>
			<option value="S" <?=($this->input->get("status")=='S' ? 'selected="selected"' : '')?>>成功</option>
		</select>	

		<span class="sptl"></span>	
		
		測試帳號
		<select name="test" style="width:100px">
			<option value="">--</option>
			<option value="only" <?=($this->input->get("test")=='only' ? 'selected="selected"' : '')?>>只列測試</option>
			<option value="no" <?=($this->input->get("test")=='no' ? 'selected="selected"' : '')?>>不包含</option>
		</select>			
		
		<span class="sptl"></span>				
		
		建單時間
		<input type="text" name="start_date" value="<?=$this->input->get("start_date")?>" style="width:120px"> 至
		<input type="text" name="end_date" value="<?=$this->input->get("end_date")?>" style="width:120px" placeholder="現在">
		<a href="javascript:;" class="clear_date"><i class="icon-remove-circle" title="清除"></i></a>
		
	</div>
	
	<div class="control-group">
	
		<input type="text" name="vip_ticket_id" value="<?=$this->input->get("vip_ticket_id")?>" class="input-medium" placeholder="VIP訂單編號">		
		<input type="text" name="billing_account" value="<?=$this->input->get("billing_account")?>" class="input-medium" placeholder="匯款帳戶末5碼">
		<input type="text" name="billing_name" value="<?=$this->input->get("billing_name")?>" class="input-medium" placeholder="匯款姓名">	
        
		匯款時間
		<input type="text" name="billing_start_date" value="<?=$this->input->get("billing_start_date")?>" style="width:120px"> 至
		<input type="text" name="billing_end_date" value="<?=$this->input->get("billing_end_date")?>" style="width:120px" placeholder="現在">
		<a href="javascript:;" class="clear_date"><i class="icon-remove-circle" title="清除"></i></a>
	
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
			<th style="width:70px;">龍邑單號</th>
			<th style="width:100px;">VIP訂單編號</th>	
			<th style="width:80px;">遊戲伺服器</th>					
			<th style="width:35px;">金額</th>
			<th style="width:50px;">結果</th>		
			<th style="width:35px;">匯款帳戶末5碼</th>		
			<th style="width:35px;">匯款姓名</th>		
			<th style="width:35px;">匯款時間</th>
			<th style="width:70px;">建立日期</th>	
		</tr>
	</thead>
	<tbody>
		<? foreach($query->result() as $row):?>
		<tr class="<?=$row->status>='2' ? 'success' : ''?>">
			<td><?=$row->id?></td>
			<td title="帳號: 
				<?
	            if (!$row->email && !$row->mobile) {
		            $ex_id = explode("@",$row->external_id); 
		            if ('device' == $ex_id[1]) echo "快速登入";
		            else echo $ex_id[1];
	            } else {
		            if ($row->email) echo $row->email;
		            echo $row->mobile;
	            }
				?>">
				<a href="<?=site_url("member/view/{$row->uid}")?>"><?=$row->uid?></a>
				<a href="<?=site_url("trade/vip?uid={$row->uid}&action=查詢")?>"><i class="icon-search"></i></a>
				<div style="color:#777;"><?=$this->g_user->encode($row->uid)?></div>
			</td>
			<td><?=$row->ubid?></td>
			<td><a href="<?=site_url("vip/event_view/{$row->vip_event_id}?ticket_status={$row->status}#tickets")?>"><?=$row->id?></a></td>
			<td><?= "({$row->game_abbr_name}){$row->server_name}" ?></td>
			<td><?=$row->cost?></td>
			<td><?=$row->status>='2' ? '成功' : '失敗'?></td>

			<td><?=$row->billing_account?></td>
			<td><?=$row->billing_name?></td>
			<td><?=date("Y-m-d H:i", strtotime($row->billing_time))?></td>
			<td><?=date("Y-m-d H:i", strtotime($row->create_time))?></td>
			<!--td>
				<div class="btn-group">
					<button type="button" class="btn btn-mini dropdown-toggle" data-toggle="dropdown">
						<span class="caret"></span>
					</button>	
					<ul class="dropdown-menu pull-right">						
						<? if ($row->status=='2'):?>
						<li><a href="javascript:;" class="json_post_alert" url="/ajax/redo_gash_billing/<?=$row->id?>"> 重做儲值</a></li>
						<? else:?>
						<li><a href="javascript:;" class="json_post_alert" url="/ajax/resend_gash_billing/<?=$row->id?>"> 重送交易</a></li>
						<? endif;?>
					</ul>
				</div>			
			</td-->								
		</tr>
		<? endforeach;?>
	</tbody>
</table>

<?=tran_pagination($this->pagination->create_links());?>

		<? }?>
	<? endif;?>
<? endif;?>

