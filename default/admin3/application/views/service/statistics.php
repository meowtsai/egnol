<?php
	$question_type = $this->config->item('question_type');
	$question_status = $this->config->item('question_status');
?>
<div id="func_bar">
</div>

<form method="get" action="<?=site_url("service/statistics")?>" class="form-search">
	<input type="hidden" name="game_id" value="<?=$this->game_id?>">

	<div class="control-group">

		遊戲
		<select name="game" style="width:120px">
			<option value="">--</option>
			<? foreach($games->result() as $row):?>
			<option value="<?=$row->game_id?>" <?=($this->input->get("game")==$row->game_id ? 'selected="selected"' : '')?>><?=$row->name?></option>
			<? endforeach;?>
		</select>

		<span class="sptl"></span>

		問題類型
		<select name="type" style="width:100px">
			<option value="">--</option>
			<? foreach($question_type as $key => $type):?>
			<option value="<?=$key?>" <?=($this->input->get("type")==$key ? 'selected="selected"' : '')?>><?=$type?></option>
			<? endforeach;?>
		</select>

		<span class="sptl"></span>

		問題狀態
		<select name="status" style="width:90px">
			<option value="">--</option>

			<? foreach($question_status as $key => $status):?>
			<option value="<?=$key?>" <?=($this->input->get("status")===strval($key) ? 'selected="selected"' : '')?>><?=$status?></option>
			<? endforeach;?>
		</select>

		<span class="sptl"></span>

        處理人員
		<select name="cs_admin" style="width:120px">
			<option value="">--</option>
			<? foreach($cs_admins->result() as $row):?>
			<option value="<?=$row->uid?>" <?=($this->input->get("cs_admin")==$row->uid ? 'selected="selected"' : '')?>><?=$row->name?></option>
			<? endforeach;?>
		</select>

		<span class="sptl"></span>


		回覆時間
		<input type="text" name="start_date" value="<?=$this->input->get("start_date")?>" style="width:120px"> 至
		<input type="text" name="end_date" value="<?=$this->input->get("end_date")?>" style="width:120px" placeholder="現在">
		<a href="javascript:;" class="clear_date"><i class="icon-remove-circle" title="清除"></i></a>

	</div>

	<div class="control-group">

		<input type="text" name="content" value="<?=$this->input->get("content")?>" style="width:120px" placeholder="提問描述">
		<input type="text" name="replies" value="<?=$this->input->get("replies")?>" style="width:120px" placeholder="回覆內容">
		<input type="text" name="question_id" value="<?=$this->input->get("question_id")?>" style="width:90px" placeholder="#id">
		<input type="text" name="uid" value="<?=$this->input->get("uid")?>" style="width:90px" placeholder="uid">
		<input type="text" name="account" value="<?=$this->input->get("account")?>" style="width:90px" placeholder="帳號">
		<input type="text" name="character_name" value="<?=$this->input->get("character_name")?>" style="width:90px" placeholder="角色名稱">

		<span class="sptl"></span>

		<input type="text" name="email" value="<?=$this->input->get("email")?>" style="width:90px" placeholder="Email">

		<span class="sptl"></span>

		<input type="submit" class="btn btn-small btn-inverse" name="action" value="查詢">

		<? if ($this->input->get("use_default") == false):?>
		<span class="sptl"></span>
		<a href="?" class="btn btn-small"><i class="icon-remove"></i> 重置條件</a>
		<? endif;?>

	</div>

</form>

<? if ($query):?>
<table class="table table-striped table-bordered" style="width:auto;">
	<thead>
		<tr>
			<th style="width:80px;">處理人</th>
			<th>回覆總數</th>
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

		<?
		$total_cnt = 0;
		foreach($query->result() as $row):?>
		<tr>
			<td width="150px"><?=$row->admin_uname?></td>
			<td>
				<a href="get_list?reply_start_date=<?=$this->input->get('start_date')?>&reply_end_date=<?=$this->input->get("end_date")?>&game=<?=$this->input->get("game")?>&status=<?=$this->input->get("status")?>&cs_admin=<?=$row->admin_uid?>&type=<?=$this->input->get("type")?>&action=查詢">


					<?
						$total_cnt += $row->cnt;
					?>
					<?=$row->cnt?>
				</a>
			</td>
		</tr>
		<? endforeach;?>
		<tr>
			<td width="150px">總計</td>
			<td>
					<?=$total_cnt?>

			</td>
		</tr>
		<? endif; ?>
	</tbody>
</table>


<? endif; ?>
