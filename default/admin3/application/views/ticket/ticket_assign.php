<?php 
	$question_type = $this->config->item('question_type');
	$question_status = $this->config->item('question_status');
?>

<div id="func_bar">
	<a class="btn btn-primary" href="<?=site_url("service/modify_question_assign")?>">+新增</a>
</div>

<ul class="nav nav-pills">
  <li <?=$type == 'not' ? 'class="active"' : ''?>><a href="<?=site_url("service/question_assign/not")?>">未讀</a></li>
  <li style="line-height:34px;"><span class="sptl"></span></li>
  <li <?=$this->input->get("date") == date("Y-m-d",time()) ? 'class="active"' : ''?>><a href="<?=site_url("service/question_assign?date=".date("Y-m-d",time()))?>">今天</a></li>  
  <li <?=$this->input->get("date") == date("Y-m-d",strtotime("-1 day")) ? 'class="active"' : ''?>><a href="<?=site_url("service/question_assign?date=".date("Y-m-d",strtotime("-1 day")))?>">昨天</a></li>
  <li <?=$this->input->get("date") == date("Y-m-d",strtotime("-2 day")) ? 'class="active"' : ''?>><a href="<?=site_url("service/question_assign?date=".date("Y-m-d",strtotime("-2 day")))?>"><?=date("m/d",strtotime("-2 day"))?></a></li>
  <li <?=$this->input->get("date") == date("Y-m-d",strtotime("-3 day")) ? 'class="active"' : ''?>><a href="<?=site_url("service/question_assign?date=".date("Y-m-d",strtotime("-3 day")))?>"><?=date("m/d",strtotime("-3 day"))?></a></li>
  <li <?=$this->input->get("date") == date("Y-m-d",strtotime("-4 day")) ? 'class="active"' : ''?>><a href="<?=site_url("service/question_assign?date=".date("Y-m-d",strtotime("-4 day")))?>"><?=date("m/d",strtotime("-4 day"))?></a></li>
  <li <?=$this->input->get("date") == date("Y-m-d",strtotime("-5 day")) ? 'class="active"' : ''?>><a href="<?=site_url("service/question_assign?date=".date("Y-m-d",strtotime("-5 day")))?>"><?=date("m/d",strtotime("-5 day"))?></a></li>
  <li <?=$this->input->get("date") == date("Y-m-d",strtotime("-6 day")) ? 'class="active"' : ''?>><a href="<?=site_url("service/question_assign?date=".date("Y-m-d",strtotime("-6 day")))?>"><?=date("m/d",strtotime("-6 day"))?></a></li>
  <li <?=$this->input->get("date") == date("Y-m-d",strtotime("-7 day")) ? 'class="active"' : ''?>><a href="<?=site_url("service/question_assign?date=".date("Y-m-d",strtotime("-7 day")))?>"><?=date("m/d",strtotime("-7 day"))?></a></li>
  <li <?=$this->input->get("date") == date("Y-m-d",strtotime("-8 day")) ? 'class="active"' : ''?>><a href="<?=site_url("service/question_assign?date=".date("Y-m-d",strtotime("-8 day")))?>"><?=date("m/d",strtotime("-8 day"))?></a></li>
  <li style="line-height:34px; padding-right:12px;">...</li>
  <li style="line-height:34px;"><span class="sptl"></span></li>
  <li <?=($this->input->get()==false) ? 'class="active"' : ''?>><a href="<?=site_url("service/question_assign")?>">所有的</a></li>
  <li <?=($this->input->get("status")=='1' && $this->input->get("date")==false) ? 'class="active"' : ''?>><a href="<?=site_url("service/question_assign?status=1")?>">所有未結案</a></li>
</ul>


<? if ($type <> 'not'):?>
<form method="get" action="<?=site_url("service/question_assign")?>" class="form-search">
	<input type="hidden" name="game_id" value="<?=$this->game_id?>">
	
	<div class="control-group">	
		
		來源
		<select name="source" style="width:100px">
			<option value="">--</option>
			<option value="電話" <?=$this->input->get("source")=="電話" ? 'selected="selected"' : ''?>>電話</option>
			<option value="信件" <?=$this->input->get("source")=="信件" ? 'selected="selected"' : ''?>>信件</option>
			<option value="營運人員" <?=$this->input->get("source")=="營運人員" ? 'selected="selected"' : ''?>>營運人員</option>
		</select>
		
		<span class="sptl"></span>
		
		交接人員
		<select name="admin_uid" style="width:90px">
			<option value="">--</option>		
						
			<? foreach($cs_user->result() as $row):?>
			<option value="<?=$row->uid?>" <?=($this->input->get("admin_uid")==$row->uid ? 'selected="selected"' : '')?>><?=$row->name?></option>
			<? endforeach;?>
		</select>
		
		<span class="sptl"></span>
		
		案件狀態
		<select name="status" style="width:90px">
			<option value="">--</option>		
			<option value="1" <?=$this->input->get("status")=="1" ? 'selected="selected"' : ''?>>進行中</option>
			<option value="2" <?=$this->input->get("status")=="2" ? 'selected="selected"' : ''?>>結案</option>
		</select>		
		
	</div>
	
	<div class="control-group">

		建檔日期
		<input type="text" name="date" value="<?=$this->input->get("date")?>" style="width:120px">
	
		<span class="sptl"></span>
	
		<input type="text" name="desc" value="<?=$this->input->get("desc")?>" style="width:100px" placeholder="交接事項">
		<input type="text" name="result" value="<?=$this->input->get("result")?>" style="width:100px" placeholder="處理狀況">
		
		<span class="sptl"></span>
		
		<input type="submit" class="btn btn-small btn-inverse" name="action" value="查詢">		
		
		<? if ($this->input->get("use_default") == false):?>
		<span class="sptl"></span>		
		<a href="?" class="btn btn-small"><i class="icon-remove"></i> 重置條件</a>
		<? endif;?>
		
	</div>
		
</form>
<? endif;?>

<? if ($query):?>

<? 
				$get = $this->input->get();
				$get['sort'] = 'expense';
				$query_string = http_build_query($get);
?>
	
<span class="label label-warning">總筆數<?=$total_rows?></span>

<?=tran_pagination($this->pagination->create_links());?>

<table class="table table-striped table-bordered">
	<thead>
		<tr>
			<th style="width:40px;">#</th>
			<th style="width:80px">記錄時間</th>
			<th style="width:80px">來源</th>
			<th style="width:80px">交接人員</th>
			<th style="width:200px;">交接事項</th>
			<th style="width:200px;">處理狀況</th>
			<th style="width:80px;">案件狀態</th>
			<th style="width:100px;">已讀人員</th>
			<th style="width:30px;"></th>		
		</tr>
	</thead>
	<tbody>		
		<? if ($query->num_rows() == 0):?>
				
		<tr>
			<td colspan="9">
				<div style="padding:10px; color:#777;">查無記錄</div>
			</td>
		</tr>

		<? else:?>
			
		<? 
		$status[1] = "進行中";
		$status[2] = "結案";
		$color[1] = "#558";
		$color[2] = "#aaa";
		foreach($query->result() as $row):?>
		<tr>
			<td><?=$row->id?></a></td>
			<td><?=date("Y-m-d H:i", strtotime($row->create_time))?></td>
			<td><?=$row->source?></td>
			<td><?=$row->name?></td>			
			<td style="word-break:break-all"><?=$row->desc?></td>
			<td style="word-break:break-all"><?=$row->result?></td>	
			<td style="word-break:break-all; color:<?=$color[$row->status]?>"><?=$status[$row->status]?></td>		
			<td>
<? 
	$query2 = $this->db->query("select id, admin_uid, `is_read`, `name` from long_e.question_assignees dlv_t
		left join admin_users au on dlv_t.admin_uid = au.uid
		where question_assign_id={$row->id};");
	foreach($query2->result() as $row2):			
		if ($row2->admin_uid == $_SESSION['admin_uid']) echo '<a href="javascript:;" class="json_post" url="'.site_url("service/read_question_assign_json?id={$row2->id}").'" title="我知道了。">';
?>
		<span style="color:<?=$row2->is_read ? '#090' : '#aaa'?>; margin-right:5px; display:inline-block;"><?=$row2->name?></span>
<? 
		if ($row2->admin_uid == $_SESSION['admin_uid']) echo '</a>';
	endforeach;?>
	
			</td>
			<td>
				<div class="btn-group">
					<button type="button" class="btn btn-mini dropdown-toggle" data-toggle="dropdown">
						<span class="caret"></span>
					</button>	
					<ul class="dropdown-menu pull-right">
						<li><a href="<?=site_url("service/modify_question_assign/{$row->id}")?>"><i class="icon-pencil"></i> 編輯</a></li>
						<li><a href="javascript:;" url="<?=site_url("service/delete_question_assign_json?id={$row->id}")?>" class="json_del" ><i class="icon-remove"></i> 刪除</a></li>
					</ul>
				</div>			
			</td>
		</tr>
		<? endforeach;?>

		<? endif;?>
		
	</tbody>
</table>

<? endif;?>