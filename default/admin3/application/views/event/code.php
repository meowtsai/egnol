<div id="func_bar">
	<a href="<?=site_url("event/add_code/{$event}")?>" class="btn btn-primary"><i class="icon-plus icon-white"></i> 新增序號</a>
	<a href="javascript:;" class="json_del btn btn-danger" url="<?=site_url("event/delete_remain_codes/{$event}")?>"><i class="icon-trash icon-white"></i> 刪除未發放的序號</a>
</div>

<form class="form-search" method="post">
  	<input type="text" name="code" placeholder="序號" class="input-medium" value="<?=$this->input->post("code")?>">
	<input type="text" name="uid" placeholder="uid" class="input-small" value="<?=$this->input->post("uid")?>">
	<button type="submit" class="btn"><i class="icon-search"></i> 查詢</button>
	<? if ($this->input->post()):?>
	<a class="btn btn-link" href="<?=current_url()?>"><i class="icon-remove"></i> 取消查詢</a>
	<? endif;?>
</form>

<? if ($query->num_rows() == 0):?>

<div class="none">尚無資料</div>

<? else:?>

<div>
	序號總數 <?=$total_rows?>組
</div>
	<?=tran_pagination($this->pagination->create_links());?>

<table class="table table-striped">
	<thead>
		<tr><td style="width:160px;text-align:center">序號</td><td style="width:160px;text-align:center">uid</td><td style="width:160px;text-align:center">發放日</td><td></td></tr>
	</thead>
	<tbody>
	<? foreach($query->result() as $row):?>
		<tr <?=$row->uid ? ' class="success"' : ($row->lock ? ' class="error"' : '')?>">
			<td style="text-align:center;"><?=$row->code?></td>
			<td style="text-align:center;"><?=$row->uid?></td>
			<td style="text-align:center;"><?=$row->get_date?></td>		
			<td >
				<span class="relative">
					<a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="icon-share-alt"></i></a>
					<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu">						
						<li>
							<a href="javascript:;" class="json_del" url="<?=site_url("event/delete_code/{$row->id}")?>">刪除</a>
						</li>
						<? if (empty($row->uid)):?>
						<li>
							<? if ($row->lock):?>
							<a href="javascript:;" class="json_post" url="<?=site_url("event/set_code_lock/{$row->id}/0")?>"><i class="icon-repeat"></i> 解鎖</a>
							<? else:?>
							<a href="javascript:;" class="json_post" url="<?=site_url("event/set_code_lock/{$row->id}/1")?>"><i class="icon-lock"></i> 鎖上</a>
							<? endif;?>
						</li>
						<? endif;?>
					</ul>
				</span>			
			</td>
		</tr>
	<? endforeach;?>
	</tbody>
</table>

<? endif;?>