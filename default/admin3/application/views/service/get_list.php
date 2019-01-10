<?php
	$question_type = $this->config->item('question_type');
	$question_status = $this->config->item('question_status');

    $admin_repliers = array();

    if ($reply_query) {
        foreach ($reply_query->result() as $row) {
            if (isset($admin_repliers[$row->question_id]))
                $admin_repliers[$row->question_id] .= $row->name.'('.$row->cnt.')<br>';
            else
                $admin_repliers[$row->question_id] = $row->name.'('.$row->cnt.')<br>';
        }
    }


?>
<div id="func_bar">
</div>

<form method="get" action="<?=site_url("service/get_list")?>" class="form-search">
	<input type="hidden" name="game_id" value="<?=$this->game_id?>">

	<div class="control-group">

<?
$s_flag = false;
?>
		遊戲
		<select name="game" style="width:150px">
			<option value="">--</option>
			<? foreach($games->result() as $row):?>
			<?if ($row->is_active==2 && !$s_flag):?>
			<option value="">------內測------</option>
			<?
			$s_flag=true;
			endif;
			?>

			<?if ($row->is_active==1 && $s_flag):?>
			<option value="">------上線中------</option>
			<?
			$s_flag=false;
			endif;
			?>

			<?if ($row->is_active==0 && !$s_flag):?>
			<option value="">------未上架------</option>
			<?
			$s_flag=true;
			endif;
			?>

			<?if (!strpos($row->name,"FAQ")):?>
			<option value="<?=$row->game_id?>" <?=($this->input->get("game")==$row->game_id ? 'selected="selected"' : '')?>> <?=$row->name?> - <?=$row->game_id?></option>
			<?endif;?>

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
			<?
			$q_status = $this->input->get("status");
			 if ($_SERVER['REQUEST_URI'] == '/service/todo' || ($this->input->get("todo"))){
				$q_status = '1';
			 }


			?>


			<? foreach($question_status as $key => $status):?>
			<option value="<?=$key?>" <?=($q_status===strval($key) ? 'selected="selected"' : '')?>><?=$status?></option>
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

		建檔時間
		<input type="text" name="start_date" value="<?=$this->input->get("start_date")?>" style="width:120px"> 至
		<input type="text" name="end_date" value="<?=$this->input->get("end_date")?>" style="width:120px" placeholder="現在">
		<a href="javascript:;" class="clear_date"><i class="icon-remove-circle" title="清除"></i></a>

		<span class="sptl"></span>

		回覆時間
		<input type="text" name="reply_start_date" value="<?=$this->input->get("reply_start_date")?>" style="width:120px"> 至
		<input type="text" name="reply_end_date" value="<?=$this->input->get("reply_end_date")?>" style="width:120px" placeholder="現在">
		<a href="javascript:;" class="clear_another_date"><i class="icon-remove-circle" title="清除"></i></a>

	</div>

	<div class="control-group">

		<input type="text" name="content" value="<?=$this->input->get("content")?>" style="width:120px" placeholder="提問描述">
		<input type="text" name="replies" value="<?=$this->input->get("replies")?>" style="width:120px" placeholder="回覆內容">
		<input type="text" name="question_id" value="<?=$this->input->get("question_id")?>" style="width:90px" placeholder="#id">
		<input type="text" name="partner_uid" value="<?=$this->input->get("partner_uid")?>" style="width:90px" placeholder="原廠uid">
		<input type="text" name="character_name" value="<?=$this->input->get("character_name")?>" style="width:90px" placeholder="角色名稱">
		<input type="text" name="check_id" value="<?=$this->input->get("check_id")?>" style="width:90px" placeholder="問題檢核碼">
		<span class="sptl"></span>

		<input type="text" name="email" value="<?=$this->input->get("email")?>" style="width:90px" placeholder="Email">
		<input type="text" name="mobile" value="<?=$this->input->get("mobile")?>" style="width:90px" placeholder="手機">
		<span class="sptl"></span>
		每頁顯示
		<select name="page_size" style="width:60px">
			<?
			$get_size = ($_SESSION['page_size'])?$_SESSION['page_size']:10;

			$size_var = [10,25,50,100];
			foreach($size_var as $size):?>
			<option value="<?=$size ?>" <?=($get_size==$size ? 'selected="selected"' : '')?>><?=$size ?></option>
			<? endforeach;?>

		</select>

		<span class="sptl"></span>

		<input type="submit" class="btn btn-small btn-inverse" name="action" value="查詢">
		<input type="submit" class="btn btn-small btn-warning" name="action" value="輸出">

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
				$get['sort'] = 'update_time';
				$query_string = http_build_query($get);
		?>

<span class="label label-warning">總筆數<?=$total_rows?></span>

<?=tran_pagination($this->pagination->create_links());?>

<form name="type_form" id="type_form" method="post" action="<?=site_url("service/update_type_json")?>" style="margin:0;">
    <input type="hidden" name="update_question_id" id="update_question_id" value="">
    <input type="hidden" name="select_type" id="select_type" value="">
    <table class="table table-bordered" style="width:auto;">
        <thead>
            <tr>
                <th style="width:60px;">#
									<?if ($add_favor_ok && (sizeof($tasks)>0)): //是否有特殊權限?>
									<input type="checkbox" name="chkall" value="all">
									<? endif;?>
								</th>
                <th style="width:80px">遊戲</th>
                <th style="width:120px">角色名稱</th>
                <th style="width:85px">提問類型</th>
                <th style="width:300px">描述</th>
                <th style="width:90px;">原廠uid</th>
                <th style="width:80px;">狀態</th>
                <th style="width:80px;">處理人</th>
                <th style="width:100px;">日期</th>
								<th style="width:110px;"><a href="?<?=$query_string?>">回覆日期</a><?=$this->input->get("sort") == 'update_time' ? ' <i class="icon icon-chevron-down"></i>' : ''?></th>
                <th></th>
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

						<?
						$is_locked=false;
						//批次鎖定中且未完成
						if ($row->is_batch > 0)
						{
							$is_locked=true;
						}?>

            <tr style="background-color:<?=$is_locked?'silver':''?>">
                <td>

									<?
									if ($add_favor_ok):
									if ($row->is_favorite =='0'):?>
									<a href="javascript:;" class="json_post" url="<?=site_url("service/add_to_favorites/{$row->id}/1")?>"><i class="far fa-star text-muted" title="加入珍藏"></i></a>
									<? else:?>
									<a href="javascript:;" class="json_post" url="<?=site_url("service/remove_favorites/{$row->id}/1")?>"><i class="fas fa-star text-warning" title="取消珍藏"></i></a>
									<? endif;
									if (sizeof($tasks)>0):
									if ($row->is_batch =='0' || ($row->status == '4' || $row->status == '7')):?>
									<input type="checkbox" name="chk_batch" value="<?=$row->id?>">
									<? else:?>
									<a href="javascript:;" class="json_post" url="<?=site_url("service/remove_from_batch/{$row->id}")?>"><i class="fas fa-tasks text-warning" title="取消批次處理"></i></a>
									<? endif;
									endif;?>
									<?endif;?>

									 <a href="<?=site_url("service/view/{$row->id}")?>"><?=$row->id?></a></td>
                <td><?=$row->game_name?></td>
                <td><?=$row->character_name?>(<?=$row->server_name?>)
									<? if ($row->is_in_game =='0'):?>
										<span style="font-size:11px;color:#090">(玩家填寫)</span>
									<? endif;?>
								</td>
                <? if ($row->type == '9'):?>
                <td>
                    <select name="new_type" style="width:100px">
                        <? foreach($question_type as $key => $type):?>
                        <option value="<?=$key?>" <?=($row->type==$key ? 'selected="selected"' : '')?>><?=$type?></option>
                        <? endforeach;?>
                    </select>
                </td>
                <td colspan="4">
                    <a href="<?=site_url("service/view/{$row->id}")?>"><?=mb_strimwidth(strip_tags($row->content), 0, 98, '...', 'utf-8')?></a>
                </td>
                <td><?=$question_status[$row->status]?>
                    <div style="font-size:11px;">
                    <?  if ($row->allocate_status == '1'):?>
                        <span style="color:#999">(後送中)</span>
                    <? elseif ($row->allocate_status == '2'):?>
                        <span style="color:#090">(後送完成)</span>
                    <? endif;?>
                    </div>
                </td>
                <? else:?>
                <td style="word-break: break-all">
                    <select name="new_type" class="required" style="width:100px;" question_id="<?=$row->id?>">
                        <? foreach($this->config->item("question_type") as $id => $type):?>
                        <option value="<?=$id?>" <?=($row->type==$id ? 'selected="selected"' : '')?>><?=$type?></option>
                        <? endforeach;?>
                    </select>
                </td>
                <td style="word-break: break-all">
                    <a href="<?=site_url("service/view/{$row->id}")?>"><?=mb_strimwidth(strip_tags($row->content), 0, 66, '...', 'utf-8')?></a>
                </td>
                <td>
                    <? if($row->partner_uid):?>
                    <?=$row->partner_uid?>
                    <a href="<?=site_url("service/get_list?partner_uid={$row->partner_uid}&action=查詢")?>"><i class="icon-search"></i></a>
                    <? endif;?>
                </td>

                <td><?=($row->status == '4' && !$row->close_admin_uid)?"玩家":""?><?=($row->status == '4' && $row->system_closed=='1')?"系統":"" ?><?=$question_status[$row->status]?>
                    <div style="font-size:11px;">

                    <?  if ($row->status == '2' || $row->status == '4' || $row->status == '7'):?>
                        <?= $row->is_read ? '<span style="color:#090">(已讀)</span>' : '<span style="color:#999">(未讀)</span>'; ?>
                    <? endif;?>

                    <?  if ($row->allocate_status == '1'):?>
                        <span style="color:#999">(後送中)</span>
                    <? elseif ($row->allocate_status == '2'):?>
                        <span style="color:#090">(後送完成)</span>
                    <? endif;?>

                    </div>
                </td>
                <? endif;?>
                <td><?=(isset($admin_repliers[$row->id]))?$admin_repliers[$row->id]:""?></td>
                <td><?=date("Y-m-d H:i", strtotime($row->create_time))?></td>
								<td><?=format_status($row->last_replied , $row->last_replied_time)?></td>

                <td>
                    <div class="btn-group">
                        <button type="button" class="btn btn-mini dropdown-toggle" data-toggle="dropdown">
                            <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu pull-right">
                            <? if ($row->status == '0'):?>
                            <li><a href="javascript:;" class="json_post" url="<?=site_url("service/show_question/{$row->id}")?>"><i class="icon-repeat"></i> 調回處理中</a></li>
                            <? else:?>
                            <li><a href="javascript:;" class="json_post" url="<?=site_url("service/show_question/{$row->id}")?>"><i class="icon-repeat"></i> 調回處理中</a></li>
                            <li><a href="javascript:;" class="json_post" url="<?=site_url("service/hide_question/{$row->id}")?>"><i class="icon-remove"></i> 隱藏</a></li>
                            <? endif;?>


														<?

														if ($add_favor_ok && $row->is_batch=='0' && ($row->status != '4' && $row->status != '7')): //是否有特殊權限?>
														<li class="divider"></li>
								              <li class="dropdown-submenu">
								                <a tabindex="-1" href="#">加入批次處理區</a>
								                <ul class="dropdown-menu">
																	<?
																	$task_count = 0;
																	foreach($tasks as $task_row):
																		if ($row->game_name==$task_row->game_name):?>
																			<li><a href="javascript:;" class="json_post" url="<?=site_url("service/add_to_batch/{$task_row->id}/{$row->id}")?>"><i class="fas fa-check-square" style="color:white;"></i> <?=$task_row->title?></a></li>
																	<?
																			$task_count++;
																		endif;
																	endforeach;
																	if ($task_count<1):
																	?>
																		<li>(沒有相關案件)</li>
																	<? endif;?>
								                </ul>
								              </li>
														<? endif;?>
                        </ul>
                    </div>
                </td>
            </tr>

            <? endforeach;?>

    <? if ($_SERVER['REQUEST_URI'] == '/service/todo' || ($this->input->get("todo"))):?>
    <script language="JavaScript">
    title_tmp1 = document.title;
    if (title_tmp1.indexOf(">>")!=-1) {
        title_tmp2=title_tmp1.split(">>");
        title_last=" —> "+title_tmp2[1];
        title_last=title_last + " —> " + title_tmp2[2];
    }else{
        if (title_tmp1.indexOf("——")!=-1) {
            title_tmp2=title_tmp1.split("——");
            title_last=" —> "+title_tmp2[1];
            if (title_last==" —> ") {title_last=" —> "};
            if (title_last==" —> ") {title_last=" —> "};
        }
    }
    title_new="待處理案件";
    step=0;
    function flash_title()
    {
        step++;
        if (step==5) {step=1;}
        if (step==1) {document.title='★☆☆ '+title_new+'(<?=$total_rows?>) ☆☆★';}
        if (step==2) {document.title='☆★☆ '+title_new+'(<?=$total_rows?>) ☆★☆';}
        if (step==3) {document.title='☆☆★ '+title_new+'(<?=$total_rows?>) ★☆☆';}
        if (step==4) {document.title='☆★☆ '+title_new+'(<?=$total_rows?>) ☆★☆';}
        setTimeout("flash_title()",500);
    }
    flash_title();


    </script>
    <? endif;?>

            <? endif;?>

        </tbody>
    </table>


		<?if ($add_favor_ok && (sizeof($tasks)>0)): //是否有特殊權限?>
		<select name="batch_batch" style="width:200px">
				<option value="" >加入批次處理區</option>
				<?
				$task_count = 0;
				foreach($tasks as $task_row):
					?>
					<option value="<?=$task_row->id?>" > <?="[".$task_row->game_name."]-".$task_row->title?></option>
				<?
						$task_count++;

				endforeach;
				if ($task_count<1):
				?>
				<option value="" >(沒有相關案件)</option>
					<li>(沒有相關案件)</li>
				<? endif;?>
		</select>
		<? endif;?>
</form>

		<?
			break;
		?>

		<? } ?>
<? endif;?>


<?php
function pluralize( $count, $text )
{
    return $count . ( ( $count == 1 ) ? ( " $text" ) : ( " ${text}" ) );
}

function ago( $datetime )
{
    $interval = date_create('now')->diff( $datetime );
    $suffix = ( $interval->invert ? ' 前' : '' );
    if ( $v = $interval->y >= 1 ) return pluralize( $interval->y, '年' ) . $suffix;
    if ( $v = $interval->m >= 1 ) return pluralize( $interval->m, '月' ) . $suffix;
    if ( $v = $interval->d >= 1 ) return pluralize( $interval->d, '日' ) . $suffix;
    if ( $v = $interval->h >= 1 ) return pluralize( $interval->h, '小時' ) . $suffix;
    if ( $v = $interval->i >= 1 ) return pluralize( $interval->i, '分' ) . $suffix;
    return pluralize( $interval->s, '秒' ) . $suffix;
}
//<td><?=format_status($row->last_replied , $row->last_replied_time)
function format_status($last_replied,$last_replied_time)
{
	$byWhom="";
	if ($last_replied=="N"){
		return "<span style='color:red'>尚未回覆</span>";
	}
	elseif ($last_replied=="U"){
		$byWhom = '<span style="color:#090">玩家 ';
	}
	elseif ($last_replied=="O"){
		$byWhom = '<span style="color:blue">官方 ';
	}

	return $byWhom.(ago(New DateTime($last_replied_time))).' 回覆</span><div style="font-size:11px;color:#999">'.$last_replied_time."</div>";
}



?>
