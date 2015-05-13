<div id="func_bar">
	<a href="<?=site_url("picture/add_category?game_id={$this->game_id}")?>" class="btn btn-primary">+ 新增</a>
</div>

<? if ($query->num_rows() == 0):?>

<div class="none">尚無資料，<a href="<?=site_url("picture/add_category?game_id={$this->game_id}")?>">立即新增</a>。</div>

<? else:?>

<table class="table table-striped">
	<thead>
		<tr><td style="width:80px; text-align:center;">#</td><td style="width:160px">分類名稱</td><td style="width:50px; text-align:center;">圖片數</td><td></td></tr>
	</thead>
	<tbody>
	<? foreach($query->result() as $row):?>
		<tr>
			<td style="text-align:center;">
				<?=$row->id?>
			</td>
			<td style="text-align:left;">
				<?=$row->category?>
			</td>
			<td style="text-align:center;"><?=$row->cnt?></td>
			<td>
				<a href="<?=site_url("picture/edit_category/{$row->id}?game_id={$this->game_id}")?>">修改</a> |
				<a href="javascript:;" class="del" cnt="<?=$row->cnt?>" url="<?=site_url("picture/delete_category/{$row->id}")?>">刪除</a>
			</td>
		</tr>
	<? endforeach;?>
	</tbody>
</table>

<? endif;?>