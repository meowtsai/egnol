<form method="get" action="<?=site_url("trade/mycard_api")?>" class="form-search">

	<div class="control-group">
		<input type="text" name="trade_seq" value="<?=$this->input->get("trade_seq")?>" class="input-medium" placeholder="交易序號">
		<input type="submit" class="btn btn-small btn-inverse" name="action" value="查詢">	
		
		<span class="sptl"></span>
		
		查詢時間
		<input type="text" name="start_date" value="<?=$this->input->get("start_date")?>" style="width:120px"> 至
		<input type="text" name="end_date" value="<?=$this->input->get("end_date")?>" style="width:120px">
		<input type="submit" class="btn btn-small btn-inverse" name="action" value="查詢 Billing 付款成功儲值失敗">
						
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
		if ($result->type == 'ingame'):
?>

<table class="table table-bordered table-striped" style="width:auto;">
	<caption>點數卡ingame</caption>
	<tbody>
		<tr>
			<th>MyCardId</th>
			<td><?=$result->MyCardId?></td>
		</tr>
		<tr>
			<th>TradeStatus(交易狀態)</th>
			<td><?=$result->TradeStatus?></td>
		</tr>
		<tr>
			<th>CardKind(MyCard卡別)</th>
			<td><?=$result->CardKind?></td>
		</tr>	
		<tr>
			<th>CardPoint(MyCard點數)</th>
			<td><?=$result->CardPoint?></td>
		</tr>			
		<tr>
			<th>Save_Seq(MyCard 交易序號)</th>
			<td><?=$result->Save_Seq?></td>
		</tr>
		<tr>
			<th>oProjNo(活動代碼)</th>
			<td><?=$result->oProjNo?></td>
		</tr>
		<tr>
			<th>ReturnMsgNo(回傳結果)</th>
			<td><?=$result->ReturnMsgNo?></td>
		</tr>
		<tr>
			<th>ReturnMsg(回傳訊息)</th>
			<td><?=$result->ReturnMsg?></td>
		</tr>								
	</tbody>
</table>

<? 
		elseif ($result->type == 'billing'):?>
<table class="table table-bordered table-striped" style="width:auto;">
	<caption>線上購點billing</caption>
	<tbody>
		<tr>
			<th>查詢訊息</th>
			<td><?=$result->result?><?=$result->message?></td>
		</tr>
		<tr>
			<th>儲值結果</th>
			<td><?=$result->trade_status?></td>
		</tr>		
	</tbody>
</table>		
		
<? 		
		endif;
	break;
	
	case "查詢 Billing 付款成功儲值失敗":
		if (empty($result)) {
			echo ('<div class="none">查無資料</div>');
			break;	
		}						
?>
		<table class="table table-bordered table-striped">
			<caption>Billing 付款成功儲值失敗</caption>
			<thead>
				<tr>
					<th>FactoryName</th><th>tradeseq</th><th>CreateDate</th><th>PayDate</th><th>GameNo</th><th style="width:22px;"></th>
				</tr>
			</thead>
			<tbody>
			<? foreach($result->NewDataSet->Table as $obj):?>			
				<tr>
					<td><?=$obj->FactoryName?></td>
					<td><?=$obj->tradeseq?></td>
					<td><?=$obj->CreateDate?></td>
					<td><?=$obj->PayDate?></td>
					<td><a href="<?=site_url("trade/payment?trade_seq={$obj->GameNo}&action=查詢")?>" title="查詢這筆mycard交易" target="_blank"><?=$obj->GameNo?></td>
					<td>
						<div class="btn-group">
							<button type="button" class="btn btn-mini dropdown-toggle" data-toggle="dropdown">
								<span class="caret"></span>
							</button>	
							<ul class="dropdown-menu pull-right">
								<li><a href="javascript:;" class="json_post_alert" url="http://www.longeplay.com.tw/ajax/resend_payment/<?=$obj->GameNo?>" ><i class="icon-repeat"></i> 執行請款</a></li>
							</ul>
						</div>			
					</td>					
				</tr>
			<? endforeach;?>
			</tbody>
		</table>
<? 
		break;
	}?>
