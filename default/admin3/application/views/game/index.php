<div id="func_bar">
	<a href="<?=site_url("game/sort")?>" class="btn btn-info">排序</a>
	<a class="btn btn-primary" href="<?=site_url("game/add")?>">新增</a>
</div>

<table class="table table-striped table-bordered" style="width:auto;">
	<thead>
		<tr>
			<th>順序</th>
			<th>game_id</th>
			<th>遊戲名稱</th>
			<th>遊戲簡稱</th>			
			<th>轉點比值</th>
			<th>遊戲狀態</th>
			<th></th> 	
		</tr>
	</thead>
	<tbody>
		<? $i=1; foreach($query->result() as $row):?>
		<tr>
			<td><?=$i++?></td>
			<td><?=$row->game_id?></td>
			<td><?=$row->name?></td>
			<td><?=$row->abbr?></td>
			<td><?=$row->exchange_rate?></td>
			<td><?=$row->is_active==TRUE?'<span style="color:green">開</span>':'<span style="color:red">關</span>'?></td>
			<td>
				<a href="<?=site_url("game/modify?game_id={$row->game_id}")?>" class="btn btn-mini">編輯</a>			
			</td>
		</tr>
		<? endforeach;?>
	</tbody>
</table>