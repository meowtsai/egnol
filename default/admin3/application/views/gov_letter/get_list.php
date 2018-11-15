<?
$status = $this->config->item("case_status");
?>
<div id="func_bar">
</div>


<form method="get" action="<?=site_url("gov_letter/get_list")?>" class="form-search">
	<input type="hidden" name="game_id" value="<?=$this->game_id?>">

	<div class="control-group">

		遊戲
		<select name="game" style="width:120px">
			<option value="">--</option>
			<? foreach($games->result() as $row):?>
			<option value="<?=$row->game_id?>" <?=($this->input->get("game")==$row->game_id ? 'selected="selected"' : '')?>><?=$row->name?><?=($row->is_active)?"":" (停)"?></option>
			<? endforeach;?>
		</select>


		<span class="sptl"></span>
		狀態
		<select name="status" style="width:120px;">
			<option value="" >全部</option>
			<option value="1" <?=($this->input->get("status")=='1' ? 'selected="selected"' : '')?>>處理中</option>
			<option value="4" <?=($this->input->get("status")=='4' ? 'selected="selected"' : '')?>>已結案</option>
		</select>


	</div>

	<div class="control-group">

		<input type="text" name="contact" value="<?=$this->input->get("contact")?>" style="width:120px" placeholder="承辦人姓名">
		<input type="text" name="o_letter_id" value="<?=$this->input->get("o_letter_id")?>" style="width:300px" placeholder="發文字號">
		<input type="text" name="role_name" value="<?=$this->input->get("role_name")?>" style="width:120px" placeholder="角色名稱">
		<span class="sptl"></span>

		<input type="submit" class="btn btn-small btn-inverse" name="action" value="查詢">

		<? if ($this->input->get("use_default") == false):?>
		<span class="sptl"></span>
		<a href="?" class="btn btn-small"><i class="icon-remove"></i> 重置條件</a>
		<? endif;?>


	</div>

</form>

<? if ($query):?>

<?
		switch ($this->input->get("action"))
		{
			case "查詢":
				$get = $this->input->get();

				if (in_array("sort", array_keys($get)))
				{
					if (strrpos($get['sort'],'desc')>0)
					{
						str_replace($get['sort'],"asc","desc");
						//$get['sort']='create_time asc';
					}
					else {
						str_replace($get['sort'],"desc","asc");
					}
				}else {
					$get['sort']='';
				}


				$query_string = http_build_query($get);
		?>

<span class="label label-warning">總筆數<?=$total_rows?></span>

<?=tran_pagination($this->pagination->create_links());?>

<table class="table table-striped table-bordered" style="width:auto;">
	<thead>
		<tr>
			<th style="width:60px;"> <?=set_sort_URL('id',$query_string, '#')?></th>
			<th style="width:80px">發文字號</th>
			<th style="width:80px">發文日期</th>
			<th style="width:80px">承辦人姓名</th>
			<th style="width:120px" ><?=set_sort_URL('o_letter_date',$query_string, '回文期限')?></th>
			<th style="width:100px;">角色資訊</th>
			<th style="width:120px" ><?=set_sort_URL('close_date',$query_string, '結案日期')?></th>
			<th style="width:100px;">狀態</th>
			<th style="width:110px;"><?=set_sort_URL('create_time',$query_string, '建立時間')?> </th>
			<th style="width:50px;"></th>
		</tr>
	</thead>
	<tbody>
		<? if ($query->num_rows() == 0):?>
			<tr>
				<td colspan="10">
					<div style="padding:10px; color:#777;">查無記錄</div>
				</td>
			</tr>
		<? else:?>
		<? foreach($query->result() as $row):?>
		<tr class="<?=($row->status==1 && $row->admin_uid==$_SESSION['admin_uid'])?"warning":""?>">
			<td><a href="<?=site_url("gov_letter/view/{$row->id}")?>"><?=$row->id?></a></td>
			<td><a href="<?=site_url("gov_letter/view/{$row->id}")?>"><?=$row->o_letter_id?></a></td>
			<td><?=$row->o_letter_date?></td>
			<td><?=$row->contact?></td>
			<td><?=$row->deadline?></td>
			<td>【<?=$row->game_name?>】<br /><?=$row->role_name?><br /><span style="font-size:60%">(<?=$row->server_name?>)</span></td>
			<td>
				<?if ($row->status=='4'):?>
				<?=$row->close_date?>
				<?endif;?>

			</td>
			<td><?=$status[$row->status];?></td>
			<td><?=date("Y-m-d H:i", strtotime($row->create_time))?></td>
			<td>
				<a href="<?=site_url("gov_letter/edit/{$row->id}")?>" class="btn btn-mini">編輯</a>
			</td>


		</tr>
		<? endforeach;?>

		<? endif;?>

	</tbody>
</table>

		<?
			break;
		?>

		<? } ?>
<? endif;?>

<?
function set_sort_URL($condition,$query_string,$title_text)
{

	$new_query_string_up ="";
	$new_query_string_down ="";
	$icon_up="<i class='fas fa-chevron-circle-up'></i>";
	$icon_down="<i class='fas fa-chevron-circle-down'></i>";
	$pos = strpos($query_string,"&sort=");

	$pos_warning = strpos($query_string,"&sort=".$condition);
	if ($pos_warning>0){
		if (strpos($query_string,"&sort=".$condition."+asc")>0){
			$icon_up="<i class='fas fa-chevron-circle-up text-warning' title='目前啟用排序'></i>";
		}
		else {
			$icon_down="<i class='fas fa-chevron-circle-down text-warning'  title='目前啟用排序'></i>";
		}

	}
	//return $query_string;
	if ($pos>0)
	{
		$new_query_string_up =	str_replace(substr($query_string,$pos, strpos($query_string,"&",$pos)), "&sort=".$condition." asc", $query_string);
		$new_query_string_down =	str_replace(substr($query_string,$pos, strpos($query_string,"&",$pos)), "&sort=".$condition." desc", $query_string);
	}
	else {
		$new_query_string = $query_string;
	}

	return "{$title_text} <a href='?{$new_query_string_down}'> {$icon_down} </a> <a href='?{$new_query_string_up}'> {$icon_up} </a>";

}
?>
