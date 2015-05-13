<table class="table table-bordered" style="width:auto;">
	<thead>
		<tr><th>UID</th><th>讀取時間</th></tr>
	</thead>
	<tbody>
	<? foreach($query->result() as $row): ?>
		<tr>
			<td>
				<a href="<?=site_url("member/view/{$row->uid}")?>"><?=$row->uid?></a>
			</td>
			<td>
				<?=$row->read_date?>
			</td>	
		</tr>
	<? endforeach;?>
	</tbody>
</table>

