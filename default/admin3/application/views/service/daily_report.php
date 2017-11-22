<?php
	$question_type = $this->config->item('question_type');
	$question_status = $this->config->item('question_status');
?>
<div id="func_bar">
</div>
<form method="get" action="<?=site_url("service/daily_report")?>" class="form-search">
	<input type="hidden" name="game_id" value="<?=$this->game_id?>">

	<div class="control-group">
		指定時間
		<input type="text" name="start_date" value="<?=$this->input->get("start_date")?>" style="width:120px"> 至
		<input type="text" name="end_date" value="<?=$this->input->get("end_date")?>" style="width:120px" placeholder="現在">
		<a href="javascript:;" class="clear_date"><i class="icon-remove-circle" title="清除"></i></a>
    <input type="submit" class="btn btn-small btn-inverse" name="action" value="查詢">
    <input type="submit" class="btn btn-small btn-warning" name="action" value="輸出">
	</div>
</form>
<? if ($report_result):?>
<table class="table table-bordered" style="width:auto;">
	<caption>本日案件</caption>
		<tr>
      <? foreach($fields as $field):?>
			<th >
        <?=$field?>
      </th>
      <? endforeach;?>
		</tr>
    <? foreach($report_result as $row):?>
		<tr>
			<td><?=$row->game_name?></td>
      <td><?=$row->id?></td>
      <td><?=$row->create_time?></td>
      <td><?=$row->type?></td>
      <td><?=$row->server_name?></td>
      <td><?=$row->character_name?></td>
      <td><?=$row->partner_uid?></td>
      <td><?=$row->gid?></td>
      <td><?=$row->phone?></td>
      <td><?=$row->email?></td>
      <td><?=$row->content?></td>
      <td><?=$row->status?></td>
      <td><?=$row->adm_username?></td>
      <td><?=$row->update_time?></td>
      <td><?=$row->has_pic?></td>
		</tr>
		<? endforeach;?>
</table>
<? endif; ?>
