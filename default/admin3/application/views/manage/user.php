<div id="func_bar">
	<a class="btn btn-primary" href="<?=site_url("manage/modify_user")?>">
		<i class="icon-plus icon-white"></i>
		新增</a>
</div>

<table class="table table-striped table-bordered" style="width:auto">
	<caption></caption>
	<thead>
		<tr>
			<th>#</th>
			<th style="width:150px">帳號</th>
			<th style="width:150px">名稱</th>
			<th style="width:100px">群組</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		<? foreach($query->result() as $row):?>
		<tr>
			<td><?=$row->uid?></td>
			<td><?=$row->account?></td>
			<td><?=$row->name?></td>
			<td><?=$row->role_desc?></td>
			<td>
				<a href="<?=site_url("manage/modify_user/{$row->uid}")?>" class="btn btn-mini">編輯</a>
				<a href="javascript:;" url="<?=site_url("manage/delete_user/{$row->uid}")?>" class="btn btn-mini json_del">刪除</a>			
			</td>
		</tr>
		<? endforeach;?>
	</tbody>
</table>