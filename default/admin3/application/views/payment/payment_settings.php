<? 
	$options = $this->config->item("payment_options");
    
    $filename = "./p/payment_disable_list";
    $handle = fopen($filename, "r");
    $payment_disable_list = fread($handle, filesize($filename));
    $payment_disable_array = explode(",", $payment_disable_list);
    fclose($handle);
?>

<? if (isset($result)):?>
	<? if ($result):?>
	<div class="alert alert-success"><i class="icon-ok-circle"></i> 修改成功</div>
	<? else:?>
	<div class="alert alert-error"><i class="icon-remove-circle"></i> 失敗</div>
	<? endif;?>
<? endif;?>

<form method="post">
<table class="table table-striped table-bordered" style="width:760px;">
	<caption></caption>
	<thead>
		<tr>
			<th style="width:15%">停用</th>
			<th style="width:25%">幣別</th>
			<th style="width:60%">付費管道</th>
		</tr>
	</thead>
	<tbody>
        <? foreach($options as $tab => $arr): ?>
        <tr>
            <td colspan="3"><?=$tab;?></td>
        </tr>
            <? foreach($arr as $opt => $arr2):
            $checked=(in_array($opt, $payment_disable_array))?'checked':'';
            ?>
        <tr>
            <td><input type="checkbox" name="disable_list[]" value="<?=$opt;?>" <?=$checked;?> /></td>
            <td><?=$arr2['trade']['cuid'];?></td>
            <td><?=$opt;?></td>
        </tr>
            <? endforeach;?>
        <? endforeach;?>
	</tbody>
</table>

<div class="form-actions">
	<button type="submit" class="btn" name="submit">確認送出</button> 
</div>
</form>