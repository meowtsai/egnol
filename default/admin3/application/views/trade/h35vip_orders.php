
<form method="get" action="<?=site_url("trade/h35vip_orders/{$account}")?>" class="form-search">

	<div class="control-group">
		角色id
		<select name="role_id" class="span2">
			<option value="">--</option>
			<? foreach($roles->result() as $row):?>
			<option value="<?=$row->char_in_game_id?>" <?=($this->input->get("role_id")==$row->char_in_game_id ? 'selected="selected"' : '')?>><?=$row->name?>-<?=$row->char_name?>(<?=$row->char_in_game_id?>)</option>
			<? endforeach;?>
		</select>

		<span class="sptl"></span>

		轉點時間
		<input type="text" name="start_date" value="<?=$this->input->get("start_date")?>" style="width:120px"> 至
		<input type="text" name="end_date" value="<?=$this->input->get("end_date")?>" style="width:120px" placeholder="現在">
		<a href="javascript:;" class="clear_date"><i class="icon-remove-circle" title="清除"></i></a>

		<span class="sptl"></span>
		<input type="submit" class="btn btn-small btn-inverse" name="action" value="查詢">

		

	</div>




</form>


<? if ( ! empty($query)):?>
	<? if ($query->num_rows() == 0): echo '<div class="none">查無資料</div>'; else: ?>

<div class="msg">總筆數:<?=$total_rows?> , 總金額:<?=$sum_total?></div>
<?=tran_pagination($this->pagination->create_links());?>

<table class="table table-bordered" style="width:auto">
	<thead>
		<tr>
			<th style="width:200px;">訂單號</th>
      <th style="width:100px;">交易方式</th>
      <th style="width:70px;">角色編號</th>
      <th style="width:70px;">角色名稱</th>
			<th style="width:35px;">品項</th>
			<th style="width:35px;">金額</th>
			<th style="width:80px;">遊戲伺服器</th>
			<th style="width:200px;">建立日期</th>
      <th style="width:70px;">IP</th>

		</tr>
	</thead>
	<tbody>
		<?foreach($query->result() as $row):?>
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
