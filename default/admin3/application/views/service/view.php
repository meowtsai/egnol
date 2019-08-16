<style>
<!--
#question {}
#question table.officail {background:#e7efe5;}
#content {width:600px;}
-->
</style>


<?if($q_batch_info):?>
<div class="alert alert-error">
	<strong>鎖定中</strong> 本提問單目前由<?=$q_batch_info[0]->admin_name?>鎖定中
</div>

<?endif;?>



<legend>
<?

if ($add_favor_ok):
if ($question->is_favorite =='0'):?>
<a href="javascript:;" class="json_post" url="<?=site_url("service/add_to_favorites/{$question->id}")?>"><i class="far fa-star text-muted" ></i></a>
<? else:?>
<a href="javascript:;" class="json_post" url="<?=site_url("service/remove_favorites/{$question->id}")?>"><i class="fas fa-star text-warning"></i></a>
<? endif;?>


<?if ($question->status<>"4" && $question->status<>"7"):?>
<?if (!$q_batch_info): //沒有被鎖定?>
  <div class="btn-group">
      <button type="button" class="btn btn-mini dropdown-toggle" data-toggle="dropdown">
          <span class="caret"></span>
      </button>
      <ul class="dropdown-menu pull-right">
          <?if ($add_favor_ok): //是否有特殊權限?>
            <li class="dropdown-submenu">
              <a tabindex="-1" href="#">加入批次處理區</a>
              <ul class="dropdown-menu">
                <?
                $task_count = 0;
                foreach($tasks as $task_row):
                  if ($question->game_name==$task_row->game_name):?>
                    <li><a href="javascript:;" class="json_post" url="<?=site_url("service/add_to_batch/{$task_row->id}/{$question->id}")?>"><i class="fas fa-check-square" style="color:white;"></i><?=$task_row->id?> - <?=$task_row->title?></a></li>
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
<? else:?>
  <a href="javascript:;" class="json_post" url="<?=site_url("service/remove_from_batch/{$question->id}")?>"><i class="fas fa-tasks text-warning" title="取消批次處理"></i></a>
<? endif;?>
<? endif;?>


<?endif;?>
案件編號 #<?=$question->id?>
</legend>

<div id="func_bar">
  <? if ($question->status <> '4'):?>

	  <form id="allocate_form" method="post" action="<?=site_url("service/allocate_json")?>" style="margin:0; display:inline-block">
		<input type="hidden" name="question_id" value="<?=$question->id?>">

		<input type="hidden" name="allocate_result" value="<?=$question->allocate_result?>">


		<div style="line-height:32px;">
			後送給
			<select name="allocate_admin_uid" style="width:120px; margin-bottom:0;">
				<? foreach($allocate_users->result() as $row):?>
				<option value="<?=$row->uid?>"><?=$row->name?></option>
				<? endforeach;?>
			</select>
			<br>
			<input type="text" name="result" style="width:200px; margin:0;" placeholder="後送描述">
			<br>
			<div style="text-align:right;"><input type="submit" class="btn" value="送出"/></div>
		</div>
	  </form>

	<? if ($question->type == '9'):?>
	 | <a href="<?=site_url("service/edit/{$question->id}")?>" class="btn btn-primary">編輯</a>
	<? endif;?>
  <? endif;?>
</div>


<div id="question">

	<div id="content">

	<table class="table table-bordered">
		<tr>
			<th style="width:80px;">案件狀態：</th>
			<td colspan="3">

            <?=($question->status == '4' && !$question->close_admin_uid)?"玩家":"" ?>
            <?=($question->status == '4' && $question->system_closed=='1')?"系統":"" ?>
			<?
				$status = $this->config->item("question_status");
				echo $status[$question->status];
			?>

				<?  if ($question->status == '2' || $question->status == '4' || $question->status == '7'):?>
					<?= $question->is_read ? '<span style="color:#090">(玩家已讀)</span>' : '<span style="color:#999">(玩家未讀)</span>'; ?>
				<? endif;?>


	<? if ($question->allocate_status == 1):?>
	<span style="color:#999">(已後送給 <?=$question->allocate_user_name?>)</span>
	<? elseif ($question->allocate_status == 2):?>
	<span style="color:#009">(<?=$question->allocate_user_name?> 處理完畢)</span>
	<? endif;?>

	<? if ($question->allocate_result):?>
		<div style="color:#666; font-size:13px; margin:6px 0;"><?=$question->allocate_result?></div>
	<? endif;?>

	<? if ($question->allocate_status == 1):?>
	<div style="margin:8px 0 0;">
	  <form id="result_form" method="post" action="<?=site_url("service/finish_allocate_json")?>" style="margin:0;">
		<input type="hidden" name="question_id" value="<?=$question->id?>">
		<input type="hidden" name="allocate_result" value="<?=$question->allocate_result?>">

		<input type="text" name="result" style="width:300px; margin:0;" placeholder="處理描述">
		<input type="submit" class="btn" value="處理完成"/>
	  </form>
	</div>


	<? endif;?>

			</td>
		</tr>
		<tr>
			<th>提問類型：</th>
			<td colspan="3">
                <form id="type_form" method="post" action="<?=site_url("service/update_type_json")?>" style="margin:0;">
					<input type="hidden" name="update_question_id" value="<?=$question->id?>">
                    <select name="select_type" class="required" style="width:100px;">
                        <option value="">--請選擇--</option>
                        <? foreach($this->config->item("question_type") as $id => $type):?>
                        <option value="<?=$id?>" <?=($question && $question->type==$id ? 'selected="selected"' : '')?>><?=$type?></option>
                        <? endforeach;?>
                    </select>
                </form>
			</td>
		</tr>
		<tr>
			<th>遊戲名稱：</th>
			<td><?=$question->game_name?></td>
			<th style="width:80px;">伺服器：</th>
			<td><?=$question->server_name?></td>
		</tr>
		<? if ($question->type <> '9'):?>
		<tr>
		    <? if ($question->is_quick):?>
			<th>原廠uid：</th>
			<td colspan="3">
				<?=$question->partner_uid?>
				<a href="<?=site_url("service/get_list?partner_uid={$question->partner_uid}&action=查詢")?>"><i class="icon-search"></i></a>
			</td>
            <? else:?>
			<th>uid：</th>
			<td colspan="3">
				<a href="<?=site_url("member/view/{$question->uid}")?>" target="_blank"><?=$question->uid?></a>
				<a href="<?=site_url("service/get_list?uid={$question->uid}&action=查詢")?>"><i class="icon-search"></i></a>
			</td>
		    <? endif;?>
		</tr>
		<tr>
			<th>帳號：</th>
			<td colspan="3">
				<?
	            if (!$question->user_email && !$question->mobile) {
		            $ex_id = explode("@",$question->external_id);
                    if (isset($ex_id[1])) {
                        if ('device' == $ex_id[1]) echo "快速登入";
                        else echo $ex_id[1];
                    }
	            } else {
		            if ($question->user_email) echo $question->user_email;
                    echo "<br />";
		            echo $question->mobile;
	            }
				?>
			</td>
		</tr>
		<? endif;?>
		<tr>
			<th>角色名稱：</th>
			<td><?=$question->character_name?><? if (($question->partner_uid && !$question->uid && !$question->in_game_name) || !$question->partner_uid):?> <span style="font-size:11px;color:#090">(玩家填寫)</span><? endif;?></td>
			<th>原廠角色id：</th>
			<td><?=$question->in_game_id?></td>
		</tr>
		<tr>
			<th>提問日期：</th>
			<td colspan="3">
			<?=$question->create_time?>
			</td>
		</tr>
		<tr>
			<th style="vertical-align:top">提問描述：</th>
			<td colspan="3" style="word-break: break-all"><?=$question->content?></td>
		</tr>
		<tr>
			<th>截圖：</th>
			<td colspan="3">
				<? if ($question->pic_path1):?>
				<a href="<?=$question->pic_path1?>" target="_blank">
					<img src="<?=$question->pic_path1?>" style="max-width:400px;">
				</a>
				<? endif;?>
				<? if ($question->pic_path2):?>
				<a href="<?=$question->pic_path2?>" target="_blank">
					<img src="<?=$question->pic_path2?>" style="max-width:400px;">
				</a>
				<? endif;?>
				<? if ($question->pic_path3):?>
				<a href="<?=$question->pic_path3?>" target="_blank">
					<img src="<?=$question->pic_path3?>" style="max-width:400px;">
				</a>
				<? endif;?>

        <? $last_reply_id =0;
          $array_pic = array();
        ?>
        <?if ($pic_plus->num_rows() > 0):
          $p_no = 3;
          //print_r($pic_plus->result()) ;
          foreach($pic_plus->result() as $row):
            if ($row->reply_id==0):
        ?>
            <span>
              <a href="<?=$row->pic_path?>" target="_blank" style="color:blue;font-size:9pt">
                / 附圖<?=++$p_no?>
              </a>
            </span>
          <?
            else:
              $cur_reply_id = $row->reply_id;
              if ($cur_reply_id==$last_reply_id):
                array_push($array_pic[$cur_reply_id],$row->pic_path);
              else:
                $array_pic[$cur_reply_id] = [$row->pic_path];
              endif;
              $last_reply_id = $cur_reply_id;
            endif;
          endforeach;
        endif;?>

			</td>
		</tr>
		<? if ($question->type <> '9' && !$question->is_quick):?>
		<tr>
			<th>聯絡電話：</th>
			<td><?=$question->mobile?></td>
			<th>E-mail：</th>
			<td><?=$question->user_email?></td>
		</tr>
		<? else:?>
		<tr>
			<th>聯絡電話：</th>
			<td><?=$question->phone?></td>
			<th>E-mail：</th>
			<td><?=$question->email?></td>
		</tr>
		<tr>
			<th>ip:</th>
			<td><?=$question->ip?>  <a target="_blank" href="https://whatismyipaddress.com/ip/<?=$question->ip?>">查看ip資訊</a> </td>
			<th>國家：</th>
			<td><?=$question->country?></td>
		</tr>
		<? endif;?>
		<tr>
			<th>備註：</th>
			<td colspan="3">
				<form id="note_form" method="post" action="<?=site_url("service/update_note_json")?>" style="margin:0">
					<input type="hidden" name="question_id" value="<?=$question->id?>">
					<textarea name="note" rows="3" style="width:80%" ><?=$question->note?></textarea>
					<button type="submit" class="btn" style="vertical-align:top;">儲存</button>
				</form>
        <?if ($ip): ?>
        <a target="_blank" href="https://whatismyipaddress.com/ip/<?=$ip?>">查看ip資訊</a>
        <?endif;?>
			</td>
		</tr>
    <?  if ($question->status == '4'):?>
    <tr>
      <th>結案人員：</th>
      <td colspan="3">
         <?=$question->close_admin_name ?>
      </td>
    </tr>
    <? endif;?>
	</table>

	<?
	$no = 1;
	foreach($replies->result() as $row):?>
	<table class="table table-bordered <?=($row->is_official ? 'official' : '') ?>" style="position:relative;">
		<tr>
			<td style="width:120px; text-align:center;">
				NO<?=$no++?>.<?=($row->is_official ? '客服回覆' : '再次提問') ?><br>
				<?=date('Y-m-d H:i', strtotime($row->create_time))?>
				<? if ($row->admin_uname):?>
				<div style="font-size:12px; color:#129;">(<?=$row->admin_uname?>)</div>
				<? endif;?>

			</td>
			<td style="word-break:break-all"><?=$row->content?>
      <?
      if (sizeof($array_pic)>0):
        if (isset($array_pic[$row->id])):
          ?>
        <?for($count = 0; $count < sizeof($array_pic[$row->id]);$count++):?>
          <span>
            <a href="<?=$array_pic[$row->id][$count]?>" target="_blank" style="color:blue;font-size:9pt">
              <附圖>
            </a>
          </span>
        <?endfor;
          endif;
          endif;?>

				<? if ($row->is_official): ?>
				<div><a href="<?=site_url("service/edit_reply/{$row->id}")?>">編輯</a></div>
				<? endif;?>
			</td>
		</tr>
	</table>
	<? endforeach;?>


	</div>
<?if((!$q_batch_info) || ($q_batch_info[0]->admin_uid==$_SESSION['admin_uid'])):?>
	<? if ($question->status <> '4'):?>

	<form id="reply_form" method="post" action="<?=site_url("service/modify_reply_json")?>">
		<input type="hidden" name="question_id" value="<?=$question->id?>">
		<input type="hidden" name="email" value="<?=$question->email?>">
		<input type="hidden" name="game_name" value="<?=$question->game_name?>">
		<input type="hidden" name="ip" value="<?=$question->ip?>">
		<input type="hidden" name="game_id" value="<?=$question->game_id?>">
		<input type="hidden" name="check_id" value="<?=$question->check_id?>">
		<input type="hidden" name="is_in_game" value="<?=$question->is_in_game?>">


		回覆
		<textarea name="content" rows="28" style="width:98%" class="required">親愛的玩家您好，
感謝您使用龍邑客服中心線上回報系統


倘若有任何疑問或是需要進一步瞭解的事項，請直接透過客服中心線上回報，我們將會竭誠為您服務。
***龍邑客服中心敬上***</textarea>

		<div class="form-actions">
	  		<button type="submit" class="btn ">確認送出</button>
        <a href="javascript:;" url="<?=site_url("service/close_question/{$question->id}")?>" class="json_post pull-right btn btn-danger">立即結案</a>

        <? if ($question->status == '2' && ($question->allocate_status == '0' || $question->allocate_status == '2')):?>
        <a href="javascript:;" url="<?=site_url("service/reserved_question/{$question->id}")?>" class="json_post pull-right btn btn-warning">預約結案</a>
        <? endif;?>

        <? if ($question->status == '7'):?>

        <?
        $datetime1 = new DateTime($question->system_closed_start);
        $datetime1->add(new DateInterval('P2D'));
        //$minutes_to_add = 5;
        //$datetime1->add(new DateInterval('PT' . $minutes_to_add . 'M'));
        $datetime2 = new DateTime(now());
        //echo date_format($datetime1, 'Y-m-d  H:i:s').'<br />';
        //echo date_format($datetime2, 'Y-m-d  H:i:s').'<br />';
        $interval = $datetime2->diff($datetime1);
        //echo $interval->format('%R%D天 %H小時%I分%S秒');

				?>


        <a href="javascript:;" url="<?=site_url("service/cancel_reserved_question/{$question->id}")?>" class="json_post pull-right btn btn-success">取消預約</a>
          <? if($interval->format('%R%S')>0):  ?>
            <span class="pull-right badge badge-warning"><?=$interval->format('%R%D天 %H小時%I分%S秒')?>後自動結案</span>
          <? else:  ?>
            <span class="pull-right badge badge-warning">即將自動結案</span>
          <? endif;?>
        <? endif;?>


	  	</div>
	</form>
    <? else: ?>

	<form id="reply_form" method="post" action="<?=site_url("service/modify_reply_json")?>">
		<div class="form-actions">
	  		<a href="javascript:;" url="<?=site_url("service/show_question/{$question->id}")?>" class="json_post pull-right btn btn-danger">調回處理中</a>
	  	</div>
	</form>
	<? endif;?>
<?endif;?>
</div>
