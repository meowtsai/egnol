<? 
	$result_arr = array("0" => array("初始", "#666"), "1" => array("成功", "#0a1"), "2" => array("失敗", "#a10"), 
			"3" => array("逾時", "804"), "4" => array("其它", "#01a"));
?>
<form method="post">
	<fieldset>
	
		<legend>訂單號 <?=$row->id?></legend>
		
		<blockquote>
			<dl class="dl-horizontal">
				<dt>uid</dt>
				<dd><a href="<?=site_url("member/view/{$row->uid}")?>" target="_blank"><?=$row->uid?></a></dd>
				<dt>帳號</dt><dd><?=$row->account?></dd>
			</dl>
			<small>
				<a href="<?=site_url("trade/transfer?uid={$row->uid}&action=查詢")?>" class="btn btn-mini">轉點記錄</a>			
			</small>
		</blockquote>
		
			<dl class="dl-horizontal">	
				<dt>金額</dt><dd><?=$row->amount?></dd>				
				<dt>交易管道</dt><dd><?=$row->transaction_type?></dd>
				<dt>交易伺服器</dt><dd><?=$row->pay_server_id?></dd>
				<dt>建立日期</dt><dd><?=$row->create_time?></dd>
				<dt>結果</dt><dd style="color:<?=$result_arr[$row->result][1]?>; font-weight:bold;"><?=$result_arr[$row->result][0]?></dd>
				<dt>備註</dt><dd><?=$row->note?></dd>
			</dl>
			
		
		  
    	<label for="note">備註</label>
   		<input type="text" id="note" name="note" style="width:600px" value="<?=$row->note?>">
   		
   		<? if (in_array($row->result, array("0", "3", "4"))):?>
   		
   		<label for="result">設定結果</label>
   		<select id="result" name="result" style="width:110px">
   			<option value="">--不更動--</option>
   			<option value="1" <?= $row->result=='1' ? 'selected="selected"' : ''?>>成功</option>
   			<option value="2" <?= $row->result=='2' ? 'selected="selected"' : ''?>>失敗</option>
   		</select>
   		
   		<? endif;?>
   				
   		<div class="form-actions">
   			<button type="submit" class="btn"><i class="icon-ok"></i> 確認送出</button>
   			<? if (in_array($row->result, array("0", "3", "4"))):?>
   			<button type="button" class="btn json_post_alert" url="http://www.long_e.com.tw/ajax/resend_transfer/<?=$row->id?>" ><i class="icon-repeat"></i> 重送交易</button>
   			<? endif;?>
   		</div>
   		
	</fieldset>
</form>