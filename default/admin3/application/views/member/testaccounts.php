
<div id="func_bar">
	<a class="btn btn-primary" href="<?=site_url("member/modify_testaccounts")?>"><i class="icon-plus icon-white"></i> 新增</a>
</div>

<div class="msg">總筆數:<?=$query->num_rows()?></div>

<table class="table table-striped table-bordered" style="width:auto;">
	<thead>
		<tr>
			<th style="width:60px;">#</th>
			<th style="width:70px;">uid</th>
			<th style="width:160px">備註</th>			
			<!-- <th style="width:90px;">建立日期</th> -->	 	
			<th></th>
		</tr>
	</thead>
	<tbody>
		<? foreach($query->result() as $row):?>
		<tr>
			<td><?=$row->id?></td>
			<td><a href="<?=site_url("member/view/{$row->uid}")?>"><?=$row->uid?></a></td>
			<td><?=$row->note?></td>
			<!-- <td><?=date("Y-m-d", strtotime($row->create_time))?></td> -->
			<td>
				<a href="<?=site_url("member/modify_testaccounts/{$row->id}")?>" class="btn btn-mini">編輯</a>
				<a href="javascript:;" url="<?=site_url("member/delete_testaccounts/{$row->id}")?>" class="btn btn-mini json_del">刪除</a>			
			</td>
		</tr>
		<? endforeach;?>
	</tbody>
</table>