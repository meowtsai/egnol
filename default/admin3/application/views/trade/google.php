<?php 
	//$pepay_conf = $this->config->item('pepay');
?>

<form method="get" action="<?=site_url("trade/google")?>" class="form-search">
	
	<div class="control-group">	
		
		交易結果
		<select name="result" style="width:75px">
			<option value="">--</option>
			<option value="N" <?=($this->input->get("result")=='N' ? 'selected="selected"' : '')?>>失敗</option>
			<option value="Y" <?=($this->input->get("result")=='Y' ? 'selected="selected"' : '')?>>成功</option>
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
		<input type="text" name="account" value="<?=$this->input->get("account")?>" class="input-medium" placeholder="帳號">
		
		<span class="sptl"></span>	
		
		<input type="text" name="order_no" value="<?=$this->input->get("order_no")?>" style="width:300px" placeholder="Google訂單號">			
	
		<input type="submit" class="btn btn-small btn-inverse" name="action" value="查詢">
		<input type="submit" class="btn btn-small btn-warning" name="action" value="輸出">	
		
		<? if ($this->input->get("use_default") == false):?>
		<span class="sptl"></span>		
		<a href="?" class="btn btn-small"><i class="icon-remove"></i> 清除條件</a>
		<? endif;?>
		
	</div>
			
	<p class="text-info">
		<span class="label label-info">說明</span>
		.
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
			<th style="width:100px;">訂單號</th>	
			<th style="width:35px;">品項</th>							
			<th style="width:35px;">金額</th>
			<th style="width:80px;">遊戲伺服器</th>
			<th style="width:50px;">結果</th>
			<th style="width:120px;">訊息</th>
			<th style="width:80px;">原廠單號</th>
			<th style="width:70px;">建立日期</th>	
			<th style="width:22px;"></th>
		</tr>
	</thead>
	<tbody>
		<? foreach($query->result() as $row):?>
		<tr class="<?=$row->result=='1' ? 'success' : ''?>">
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
				<a href="<?=site_url("trade/google?uid={$row->uid}&action=查詢")?>"><i class="icon-search"></i></a>
				<div style="color:#777;"><?=$this->g_user->encode($row->uid)?></div>
			</td>
			<td><?=$row->order_no?></td>
			<td><?=$row->product_id?></td>
			<td><?=$row->amount?></td>
			<td><?= "({$row->game_abbr_name}){$row->server_name}" ?></td>
			<td><? if ($row->result == '1') {
						echo '成功';
						if ($row->is_confirmed == '') {
							echo ' <div style="color:red; font-size:12px;">(未請款!!)</div>';
						}
				}
				else echo '失敗';
				?>				
			</td>
			<td><?=$row->note?></td>
			<td><?=$row->partner_order_id?></td>
			<td><?=date("Y-m-d H:i", strtotime($row->create_time))?></td>
			<td>
				<div class="btn-group">
					<button type="button" class="btn btn-mini dropdown-toggle" data-toggle="dropdown">
						<span class="caret"></span>
					</button>	
					<? if ($row->result<>'1'):?>
					<ul class="dropdown-menu pull-right">
						<li><a href="<?=site_url("trade/modify_order/{$row->id}")?>"><i class="icon-pencil"></i> 編輯</a></li>
					</ul>
					<? endif;?>
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

