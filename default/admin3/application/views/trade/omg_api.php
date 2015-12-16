<form method="get" action="<?=site_url("trade/omg_api")?>" class="form-search">

	<div class="control-group">
		<input type="text" name="billing_id" value="<?=$this->input->get("billing_id")?>" class="input-medium" placeholder="交易序號">
		<input type="submit" class="btn btn-small btn-inverse" name="action" value="查詢">	
	</div>
	
</form>

<? 
switch ($this->input->get("action")) 
{
	case "查詢":
		if (empty($result)) {
			echo ('<div class="none">查無資料</div>');
			break;	
		}
?>

<table class="table table-bordered table-striped" style="width:auto;">
	<tbody>
		<!-- <tr>
			<th>storeid(店家代號)</th>
			<td><?=$result->storeid?></td>
		</tr>
		 -->
		<tr>
			<th>storeorderid(店家訂單號)</th>
			<td><?=$result->storeorderid?></td>
		</tr>
		<tr>
			<th>paymentorderid(OMG金流中心訂單號)</th>
			<td><?=$result->paymentorderid?></td>
		</tr>	
		<tr>
			<th>paymentorderamt(實際交易金額)</th>
			<td><?=$result->paymentorderamt?></td>
		</tr>			
		<tr>
			<th>paymentstatus(交易狀態碼)</th>
			<td><?=$result->paymentstatus?></td>
		</tr>
		<tr>
			<th>paymentmessage(交易狀態說明)</th>
			<td><?=urldecode($result->paymentmessage)?></td>
		</tr>
		<tr>
			<th>paymentreplydate(交易成功回覆時間)</th>
			<td><?=$result->paymentreplydate?></td>
		</tr>								
	</tbody>
</table>

<? 
		break;
	}?>
