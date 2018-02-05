<? if ( ! empty($query)):?>
	<? if ($query->num_rows() == 0): echo '<div class="none">查無資料</div>'; else: ?>


<div class="msg">總筆數:<?=$total_rows?></div>
<?=tran_pagination($this->pagination->create_links());?>

<table class="table table-bordered" style="width:auto">
	<thead>
		<tr>
			<th style="width:100px;">訂單號</th>
      <th style="width:100px;">交易方式</th>
      <th style="width:70px;">角色編號</th>
      <th style="width:70px;">角色名稱</th>
			<th style="width:35px;">品項</th>
			<th style="width:35px;">金額</th>
			<th style="width:80px;">遊戲伺服器</th>
			<th style="width:70px;">建立日期</th>
      <th style="width:70px;">IP</th>

		</tr>
	</thead>
	<tbody>
		<? foreach($query->result() as $row):?>
		<tr>
			<td><?=$row->transaction_id?></td>
      <td><?=$row->transaction_type?></td>
			<td><?=$row->role_id?></td>
			<td><?=$row->role_name?></td>
      <td><?=$row->product_id?></td>
      <td><?=$row->amount?></td>
      <td><?=$row->server?></td>
      <td><?=date("Y-m-d H:i", strtotime($row->create_time))?></td>
      <td><?=$row->ip?></td>


		</tr>
		<? endforeach;?>
	</tbody>
</table>

<?=tran_pagination($this->pagination->create_links());?>


	<? endif;?>
<? endif;?>
