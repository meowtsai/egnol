<?php
	// $ticket_type = $this->config->item('ticket_type');
	// $ticket_status = $this->config->item('ticket_status');
	// $ticket_urgency = $this->config->item('ticket_urgency');
	$status = $this->config->item("case_status");
?>
<div id="func_bar">
</div>


<form method="get" action="<?=site_url("cpl_case/get_list")?>" class="form-search">
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
		案件狀態
		<select name="status" style="width:120px">
			<option value="">--</option>
			<? foreach($status as $key => $val):?>
			<option value="<?=$key?>" <?=($this->input->get("status")===strval($key) ? 'selected="selected"' : '')?>><?=$val?></option>
			<? endforeach;?>
		</select>


	</div>

	<div class="control-group">

		<input type="text" name="appellant" value="<?=$this->input->get("appellant")?>" style="width:120px" placeholder="申訴人姓名">
		<input type="text" name="phone" value="<?=$this->input->get("phone")?>" style="width:120px" placeholder="連絡電話">
		<input type="text" name="o_case_id" value="<?=$this->input->get("o_case_id")?>" style="width:90px" placeholder="發文字號">

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
			<th style="width:120px">發文字號</th>
			<th style="width:80px">申訴人姓名</th>
			<th style="width:120px">申訴原因</th>
			<th style="width:100px" ><?=set_sort_URL('o_case_date',$query_string, '回文期限')?></th>
			<th style="width:120px">最新連絡</th>
			<th style="width:80px;">連絡電話</th>
			<th style="width:100px;">角色資訊</th>
			<th style="width:50px;">伺服器</th>
			<th style="width:100px" ><?=set_sort_URL('close_date',$query_string, '結案日期')?></th>
			<th style="width:50px;">狀態</th>
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

		<!-- ////select id, o_case_id,o_case_date,appellant,reason,phone,game_id,server_id,role_name,admin_uid,create_time,update_time,close_date,status

		-->

		<? foreach($query->result() as $row):?>
		<tr class="<?=($row->status==1 && $row->admin_uid==$_SESSION['admin_uid'])?"warning":""?>">
			<td><a href="<?=site_url("cpl_case/view/{$row->id}")?>"><?=$row->id?></a></td>
			<td><a href="<?=site_url("cpl_case/view/{$row->id}")?>"><?=$row->o_case_id?></a> <?=$row->has_attached?" <i class='fas fa-paperclip'></i>":""; ?></td>
			<td><?=$row->appellant?></td>
			<td><?=$row->reason?></td>
			<td><?=$row->o_due?></td>
			<td><?=$row->last_replied?date("Y-m-d H:i", strtotime($row->last_replied)):""; ?></td>
			<td><?=$row->phone?></td>
			<td>【<?=$row->game_name?>】<br /><?=$row->role_name?><br /></td>
			<td><span style="font-size:60%"><?=$row->server_name?></span></td>
			<td>
				<?if ($row->status=='4'):?>
				<?=$row->close_date?>
				<?endif;?>

			</td>
			<td><?=$status[$row->status];?></td>
			<td><?=date("Y-m-d H:i", strtotime($row->create_time))?></td>
			<td>
				<a href="<?=site_url("cpl_case/edit/{$row->id}")?>" class="btn btn-mini">編輯</a>


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

<!-- game_id=&game=&appellant=&phone=&o_case_id=&action=%E6%9F%A5%E8%A9%A2&sort=id+desc -->
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
