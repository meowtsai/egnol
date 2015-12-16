<?php 
	$channels = $this->config->item('channels');
?>

<form method="get" action="<?=site_url("member")?>" class="form-search">

	<div class="control-group">

		<input type="text" name="uid" value="<?=$this->input->get("uid")?>" class="input-small" placeholder="uid">		
		<input type="text" name="euid" value="<?=$this->input->get("euid")?>" class="input-medium" placeholder="euid">		
		<input type="text" name="account" value="<?=$this->input->get("account")?>" class="input-large" placeholder="帳號">
		<input type="text" name="name" value="<?=$this->input->get("name")?>" class="input-small" placeholder="姓名">
		<input type="text" name="character_name" value="<?=$this->input->get("character_name")?>" class="input-large" placeholder="遊戲角色匿名">
		
	</div>
	
	<div class="control-group">
		
		通路來源 
		<select name="channel">
			<option value="">--</option>
			<? foreach($channels as $key => $channel):?>
			<option value="<?=$key?>" <?=($this->input->get("channel")==$key ? 'selected="selected"' : '')?>><?=$channel?></option>
			<? endforeach;?>
		</select>
		
		<span class="sptl"></span>		
		
		註冊時間
		<input type="text" name="start_date" value="<?=$this->input->get("start_date")?>" style="width:120px"> 至
		<input type="text" name="end_date" value="<?=$this->input->get("end_date")?>" style="width:120px" placeholder="現在">
		<a href="javascript:;" class="clear_date"><i class="icon-remove-circle" title="清除"></i></a>
		
	</div>
		
	<div class="control-group">
	
		<input type="submit" class="btn btn-small btn-inverse" name="action" value="查詢">
		
		<span class="sptl"></span>
		
		<select name="time_unit" style="width:80px">
			<option value="hour" <?=($this->input->get("time_unit")=='hour' ? 'selected="selected"' : '')?>>時</option>
			<option value="day" <?=($this->input->get("time_unit")=='day' ? 'selected="selected"' : '')?>>日</option>
			<option value="month" <?=($this->input->get("time_unit")=='month' ? 'selected="selected"' : '')?>>月</option>
			<option value="year" <?=($this->input->get("time_unit")=='year' ? 'selected="selected"' : '')?>>年</option>
		</select>		
		
		<input type="submit" class="btn btn-small btn-inverse" name="action" value="成長數統計">
						
		<? if ($this->input->get("use_default") === false):?>
		<span class="sptl"></span>
		<a href="?" class="btn btn-small"><i class="icon-remove"></i> 重置</a>
		<? endif;?>
		
	</div>
		
</form>

<? if ( ! empty($query)):?>
	<? if ($query->num_rows() == 0): echo '<div class="none">查無資料</div>'; else: ?>
	
		<? 
		switch ($this->input->get("action")) 
		{
			case "查詢":
		?>
	
<div class="msg">總筆數:<?=$total_rows?></div>
<?=tran_pagination($this->pagination->create_links());?>

<table class="table table-striped">
	<thead>
		<tr>
			<th style="width:45px; text-align:center;">uid</th>
			<th style="width:45px; text-align:center;">euid</th>
			<th style="width:70px">手機</th>
			<th style="width:90px">信箱</th>
			<th style="width:50px">通路來源</th>
			<th style="width:120px; text-align:center;">姓名</th>
			<th style="width:80px; text-align:center;">註冊日期</th>	 	
		</tr>
	</thead>
	<tbody>
		<? foreach($query->result() as $row):?>
		<tr>
			<td style="text-align:center"><a href="<?=site_url("member/view/{$row->uid}")?>"><?=$row->uid?></a></td>
			<td style="text-align:center"><?=$this->g_user->encode($row->uid)?></td>
			<td><?=$row->mobile?></td>
			<td><?=$row->email?></td>
		    <td><?
			if ($row->external_id) {
				$ex_id = explode("@",$row->external_id); 
				if ('device' == $ex_id[1]) echo "快速登入";
				else echo $ex_id[1];
			} else {
				echo '龍邑會員';
			}
			?>
			</td>
			<td style="text-align:center"><?=$row->name?></td>
			<td style="text-align:center"><?=date("Y-m-d H:i", strtotime($row->create_time))?></td>
		</tr>
		<? endforeach;?>
	</tbody>
</table>

		<? 
				break;
				
			case "成長數統計":

				$field = array('時段');
				$table = array();
				
				foreach($query->result() as $row) {							
					$field[$row->key] = $row->name;
					$table[$row->title][$row->key] = $row->cnt;					
				}
				echo output_statistics_table($field, $table);
				
				break;
				
		} ?>
	<? endif;?>
<? endif;?>