<?php 
	$funapp_channel = array("M" => "MyCard","C" => "信用卡","B" => "電信帳單");
?>

<form method="get" action="<?=site_url("trade/funapp")?>" class="form-search">

	<div class="control-group">
						
		交易管道 
		<select name="funapp_channel" class="span2">
			<option value="">--</option>
			<? foreach($funapp_channel as $key => $channel):?>
			<option value="<?=$key?>" <?=($this->input->get("funapp_channel")==$key ? 'selected="selected"' : '')?>><?=$channel?></option>
			<? endforeach;?>
		</select>				
				
	</div>
	
	<div class="control-group">	
		
		交易結果
		<select name="result" style="width:75px">
			<option value="">--</option>
			<option value="0" <?=($this->input->get("result")==='0' ? 'selected="selected"' : '')?>>失敗</option>
			<option value="1" <?=($this->input->get("result")=='1' ? 'selected="selected"' : '')?>>成功</option>
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
	
		<input type="text" name="trans_no" value="<?=$this->input->get("trans_no")?>" class="input-medium" placeholder="天天賺訂單號">
		
	
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
			<th style="width:80px;">轉點單號</th>
			<th style="width:100px;">交易管道</th>		
			<th style="width:80px;">天天賺訂單號</th>
			<th style="width:35px;">金額</th>
			<th style="width:50px;">結果(狀態碼)</th>
			<th style="width:90px;">訊息</th>
			<th style="width:70px;">建立日期</th>	
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
				<a href="<?=site_url("trade/funapp?uid={$row->uid}&action=查詢")?>"><i class="icon-search"></i></a>
				<div style="color:#777;"><?=$this->g_user->encode($row->uid)?></div>
			</td>
			<td><?=$row->ubtid?></td>
			<td>
				<? 
					if ( ! empty ($row->payment_type)) {
						switch($row->payment_type) {
							case "M":
								echo "MyCard";
								break;
							case "C":
								echo "信用卡";
								break;
							default:
								echo "電信帳單";
								break;
                        }
					}
				?>
			</td>
			<td><?=$row->trans_no ?></td>
			<!--td><?=strtr($row->item_code, array("long_e"=>""))?></td-->
			<td><?=$row->amount ?></td>
			<td><?=$row->result=='1' ? '成功' : '失敗'?>(<?=$row->status?>)</td>
			<td style="font-size:13px;"><?=$row->note?></td>
			<td><?=date("Y-m-d H:i", strtotime($row->create_time))?></td>							
		</tr>
		<? endforeach;?>
	</tbody>
</table>

<?=tran_pagination($this->pagination->create_links());?>

		<? }?>
	<? endif;?>
<? endif;?>

