<div id="func_bar">
	<a href="<?=site_url("guide/add/{$parent_id}?game_id={$this->game_id}")?>" class="btn btn-primary">+ 新增</a>
</div>

<div class="guide_path"><?=get_guide_path_str($parent_id, $this->game_id)?></div>

<? if ($query->num_rows() > 0):?>
<table class="table table-striped">
	<thead>
		<tr><td style="width:80px; text-align:center">#</td><td style="width:300px">項目名稱</td><td></td></tr>
	</thead>
	<tbody>
	<? foreach($query->result() as $row):?>
		<tr>
			<td style="text-align:center"><?=$row->id?></td>
			<td><a href="<?=server_site_url($this->game_id, "guide/detail/{$row->id}")?>" target="_blank"><?=$row->guide_title?></a>
				(<a href="<?=server_site_url($this->game_id, "guide/get_list/{$row->id}")?>" target="_blank" title="查看清單模式">清單</a>)
			</td>
			<td>
				<a href="<?=site_url("guide/get_list/{$row->id}?game_id={$this->game_id}")?>">進入下一層</a> |			
				<a href="<?=site_url("guide/edit/{$row->id}?game_id={$this->game_id}")?>">修改</a>
				<!-- | <a href="javascript:;" class="del" url="<?=site_url("guide/delete/{$row->id}")?>">刪除</a> -->
			</td>
		</tr>
	<? endforeach;?>
	</tbody>
</table>
<? else:?>
<div class="none">無資料</div>
<? endif;?>

