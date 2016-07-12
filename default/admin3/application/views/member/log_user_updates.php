<div class="msg">總筆數:<?=$query->num_rows()?></div>

<table class="table table-striped table-bordered" style="width:auto;">
	<thead>
		<tr>
			<th style="width:60px;">時間</th>
			<th style="width:70px;">執行動作</th>
		</tr>
	</thead>
	<tbody>
		<? foreach($query->result() as $row):?>
		<tr>
			<td><?=$row->create_time?></td>
			<td><?=$row->content?></td>
		</tr>
		<? endforeach;?>
	</tbody>
</table>