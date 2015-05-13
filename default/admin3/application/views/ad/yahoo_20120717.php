
<h1><?=$layout_breadcrumb?></h1>

<form method="post">
	開始日期<input name="start_date" type="text" value="<?=$startDate?>">
	結束日期<input name="end_date" type="text" value="<?=$endDate?>">
	<input type="submit" name="submit" value="送出">
</form>

<? if ( ! empty($query)):
	if ($query->num_rows() > 0):
	
		$count = array();
		foreach($query->result() as $row) {
			$spt = explode("@", $row->account);
			if (count($spt) == 1) $spt[1] = "long_e";
			if (array_key_exists($spt[1], $count)) {
				$count[$spt[1]]++;
			} else $count[$spt[1]] = 0;
		}
?>

<div style="padding:3px;">
	小計：
	<? foreach($count as $key => $val) {
		echo "{$key}({$val}) ";	
	}
	?>
</div>

<table class="cz_table">
  <thead>
	<tr>
		<td>帳號</td><td>日期</td>
	</tr>
  </thead>
  <tbody>
	<? foreach($query->result() as $row):?>
	<tr>
		<td><?=$row->account?></td><td><?=$row->log_date?></td>
	</tr>
	<? endforeach;?>
  </tbody>
</table>
	<? else: ?>
	<div class="none">查無資料</div>
	<? endif; ?>
<? endif; ?>

