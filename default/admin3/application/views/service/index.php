
<table class="table table-bordered" style="width:auto;">
	<caption>本日案件</caption>
		<tr>
			<th style="width:120px"><a href="<?=site_url("service/get_list?action=查詢&start_date=".date("Y-m-d", time()))?>">新增案件：</a></th>
			<td style="width:100px"><?=$stat->all?> 
				(<a href="<?=site_url("service/get_list?type=9&action=查詢&start_date=".date("Y-m-d", time()))?>">電話</a> <?=$stat->phone?>)</td>
		</tr>
		<tr class="<?=$stat->new ? 'warning' : ''?>">
			<th><a href="<?=site_url("service/get_list?status=1&action=查詢&start_date=".date("Y-m-d", time()))?>">未回覆案件：</a></th>
			<td><?=$stat->new?></td>
		</tr>		
		<tr>
			<th><a href="<?=site_url("service/get_list?status=2&action=查詢&start_date=".date("Y-m-d", time()))?>">等待中案件：</a></th>
			<td><?=$stat->success?></td>
		</tr>
		<tr>
			<th><a href="<?=site_url("service/get_list?status=4&action=查詢&start_date=".date("Y-m-d", time()))?>">結案：</a></th>
			<td><?=$stat->close?></td>
		</tr>
	</table>
	
<table class="table table-bordered" style="width:auto;">
	<caption>合計</caption>
		<tr>
			<th style="width:120px"><a href="<?=site_url("service/get_list?status=1&action=查詢")?>">未回覆案件：</a></th>
			<td style="width:100px"><?=$stat->new_total?></td>
		</tr>
		<tr>
			<th><a href="<?=site_url("service/get_list?status=2&action=查詢")?>">等待中案件：</a></th>
			<td><?=$stat->success_total?></td>
		</tr>
		<tr>
			<th><a href="<?=site_url("service/get_list?status=4&action=查詢")?>">結案：</a></th>
			<td><?=$stat->close_total?></td>
		</tr>			
		<tr>
			<th><a href="<?=site_url("service/get_list?status=0&action=查詢")?>">隱藏：</a></th>
			<td><?=$stat->hidden_total?></td>
		</tr>
		<tr>
			<th><a href="<?=site_url("service/get_list?type=9&action=查詢")?>">電話案件：</a></th>
			<td><?=$stat->phone_total?></td>
		</tr>							
	</table>	
	
<table class="table table-bordered" style="width:auto;">
	<caption>後送案件</caption>
<? if ( ! empty($allocate[1])):?>	
		<tr>
			<th><a href="<?=site_url("service/get_list?allocate_status=1&action=查詢")?>">後送中：</a></th>
			<td>
			<? foreach($allocate[1] as $row):?>
			<span style="display:inline-block; padding:1px 4px;"><a href="<?=site_url("service/get_list?allocate_status=1&allocate_auid={$row->uid}&action=查詢")?>"><?=$row->name?>(<?=$row->cnt?>)</a></span>
			<? endforeach;?>
			</td>
		</tr>
<? endif;?>		

<? if ( ! empty($allocate[2])):?>
		<tr>
			<th><a href="<?=site_url("service/get_list?allocate_status=2&action=查詢")?>">完成：</a></th>
			<td>
			<? foreach($allocate[2] as $row):?>
			<span style="display:inline-block; padding:1px 4px;"><a href="<?=site_url("service/get_list?allocate_status=2&allocate_auid={$row->uid}&action=查詢")?>"><?=$row->name?>(<?=$row->cnt?>)</a></span>
			<? endforeach;?>
			</td>
		</tr>	
<? endif;?>				
					
	</table>		
