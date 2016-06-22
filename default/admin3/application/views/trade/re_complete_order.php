<? 
	$result_arr = array("0" => array("初始", "#666"), "1" => array("成功", "#0a1"), "2" => array("失敗", "#a10"), 
			"3" => array("逾時", "804"), "4" => array("其它", "#01a"), "5" => array("等候入點", "#0a1"));
?>
<form method="post">
	<fieldset>
	
		<legend>訂單號 <?=$row->id?></legend>
		
		<blockquote>
			<dl class="dl-horizontal">
				<dt>uid</dt>
				<dd><a href="<?=site_url("member/view/{$row->uid}")?>" target="_blank"><?=$row->uid?></a></dd>
				<dt>帳號</dt><dd><?
	            if (!$row->email && !$row->mobile) {
		            $ex_id = explode("@",$row->external_id); 
		            if ('device' == $ex_id[1]) echo "快速登入";
		            else echo $ex_id[1];
	            } else {
		            if ($row->email) echo $row->email;
		            echo $row->mobile;
	            }
				?></dd>
			</dl>
			<small>
				<a href="<?=site_url("trade/transfer?uid={$row->uid}&action=查詢")?>" class="btn btn-mini">轉點記錄</a>			
			</small>
		</blockquote>
		
			<dl class="dl-horizontal">	
				<dt>金額</dt><dd><?=($row->amount)?$row->amount:0?></dd>				
				<dt>交易管道</dt><dd><?=$row->transaction_type?></dd>
				<dt>交易伺服器</dt><dd><?=$row->server_id?></dd>
				<dt>建立日期</dt><dd><?=$row->create_time?></dd>
				<dt>結果</dt><dd style="color:<?=$result_arr[$row->result][1]?>; font-weight:bold;"><?=$result_arr[$row->result][0]?></dd>
				<dt>備註</dt><dd><?=$row->note?></dd>
			</dl>
			
		
   		<? if (in_array($row->result, array("0", "2", "3", "4"))):?>
		  
    	<label for="note">金額</label>
   		<input type="text" id="amount" name="amount" style="width:600px" value="<?=$row->amount?>">
        <? if ($err_message):?>
   		
        <div class="text-error"><?=$err_message?>!!</div>
   		
   		<? endif;?>
    	<label for="note">備註</label>
   		<input type="text" id="note" name="note" style="width:600px" value="<?=$row->note?>">
   				
   		<div class="form-actions">
   			<button type="submit" class="btn"><i class="icon-ok"></i> 確定補單</button>
   		</div>
   		<? endif;?>
   		
	</fieldset>
</form>