<? if ($query):?>
<div class="msg">總筆數:<?=$query->num_rows()?></div>

<table class="table table-striped table-bordered" style="width:auto">
	<thead>
		<tr>
			<th>uid</th>
			<th>帳號</th>
			<th>停權時間</th>
			<th>停權原因</th>
		</tr>
	</thead>
	<tbody>
		<? foreach($query->result() as $row):?>
		<tr>
			<td><a href="<?=site_url("member/view/{$row->uid}")?>"><?=$row->uid?></a></td>
			<td><a href="<?=site_url("member/view/{$row->uid}")?>"><?=$row->account?></a></td>
			<td><?=$row->ban_date?></td>
			<td><span style="width:300px; display:inline-block;"><?=$row->ban_reason?></span></td>
		</tr>
		<? endforeach;?>
	</tbody>
</table>
<? endif;?>