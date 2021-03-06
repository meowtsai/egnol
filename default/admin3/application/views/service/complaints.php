<?php
	$complaints_category = $this->config->item('complaints_category');
  $complaints_status = $this->config->item('complaints_status');
?>

<div id="func_bar">
</div>

<ul class="nav nav-tabs" id="ranking_tab">
    <li class="active">
        <a href="#" onclick="get_ranking_report(1);return false;">1天</a>
    </li>
		<li class="">
        <a href="#" onclick="get_ranking_report(7);return false;">7天</a>
    </li>
		<li class="">
        <a href="#" onclick="get_ranking_report(30);return false;">30天</a>
    </li>
</ul>

<table id="ranking_table" class="table table-hover" style="width:auto;">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">伺服器</th>
      <th scope="col">角色</th>
      <th scope="col">次數</th>
    </tr>
  </thead>
	<tbody>
	</tbody>
</table>

<hr />

<form method="get" action="<?=site_url("service/complaints")?>" class="form-search">
	<input type="hidden" name="game_id" value="<?=$this->game_id?>">

	<div class="control-group">

		伺服器
		<select name="server" >
			<option value="">--</option>
			<? foreach($servers->result() as $row):?>
			<option value="<?=$row->server_id?>" <?=($this->input->get("server")==$row->server_id ? 'selected="selected"' : '')?>><?=$row->server_id?> - <?=$row->name?></option>
			<? endforeach;?>
		</select>

		<span class="sptl"></span>

		狀態
		<select name="status" style="width:100px">
			<option value="">--</option>
			<? foreach($complaints_status as $key => $status):?>
			<option value="<?=$key?>" <?=($this->input->get("status")==$key ? 'selected="selected"' : '')?>><?=$status?></option>
			<? endforeach;?>
		</select>

		<span class="sptl"></span>


		問題類型
		<select name="category" style="width:100px">
			<option value="">--</option>
			<? foreach($complaints_category as $key => $category):?>
			<option value="<?=$key?>" <?=($this->input->get("category")==$key ? 'selected="selected"' : '')?>><?=$category?></option>
			<? endforeach;?>
		</select>

		<span class="sptl"></span>

		建檔時間
		<input type="text" name="start_date" value="<?=$this->input->get("start_date")?>" style="width:120px"> 至
		<input type="text" name="end_date" value="<?=$this->input->get("end_date")?>" style="width:120px" placeholder="現在">
		<a href="javascript:;" class="clear_date"><i class="icon-remove-circle" title="清除"></i></a>

	</div>
	<div class="control-group">

		<input type="text" name="character_name" value="<?=$this->input->get("character_name")?>" style="width:90px" placeholder="角色名稱">
		<input type="text" name="character_id" value="<?=$this->input->get("character_id")?>" style="width:90px" placeholder="角色id">

		<span class="sptl"></span>


		<input type="submit" class="btn btn-small btn-inverse" name="action" value="查詢">

		<? if ($this->input->get("use_default") == false):?>
		<span class="sptl"></span>
		<a href="?" class="btn btn-small"><i class="icon-remove"></i> 重置條件</a>
		<? endif;?>

	</div>

</form>

<span class="label label-warning">總筆數<?=$total_rows?></span>

<?=tran_pagination($this->pagination->create_links());?>

<? if ($query):?>
<table class="table table-striped table-bordered" style="width:auto;">
	<thead>
		<tr>
			<th>檢舉事件</th>
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
		<tr id="tr<?=$row->id?>">
			<td style="font-size:12px;">
          <i class='<?=($row->status=="2"?"fas fa-check":"far fa-frown") ?>'></i>
      		<?=$row->server_id?> - <?=$row->server_name?>
          <i class="fas fa-user"></i> <a href="get_list?character_name=<?=$row->reporter_name?>&email=&mobile=&action=查詢"> <?=$row->reporter_name?></a><span style="font-size:11px; color:green;"> <?=$row->reporter_char_id?> </span> 舉報
          <i class="far fa-user"></i><a href="get_list?character_name=<?=$row->flagged_player_name?>&email=&mobile=&action=查詢"> <?=$row->flagged_player_name?></a> <span style="font-size:11px; color:red;"><?=$row->flagged_player_char_id?> </span>  [<?=$complaints_category[$row->category]?>]

            <? if ($row->reason): ?>
                "<?=$row->reason?>"
              <i class="far fa-comment"></i>
            <? endif; ?>
            <div> # <?=$row->id?> - <?=date("Y-m-d H:i", strtotime($row->create_time))?>  (<?=ago(New DateTime($row->create_time))?>)</div>

			</td>
      <td style="font-size:12px;">
        <?=$row->admin_comment?>
      </td>
      <td>
          <div class="btn-group">
            <? if ($row->status ==1 ): ?>
            <button type="button" class="btn btn-secondary" onclick="mark_as_read('<?=$row->id?>')">閱</button>
            <? endif; ?>
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#commentModal" onclick="open_modal('<?=$row->id?>')">註解</button>

          </div>
      </td>
		</tr>
		<? endforeach;?>
		<? endif; ?>
	</tbody>
</table>



<!-- Modal -->
<div class="modal fade" id="commentModal" tabindex="-1" role="dialog" aria-labelledby="commentModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="commentModalLabel">Modal title</h5>
      </div>
      <div class="modal-body">
				<div id="modal-alert" role="alert">

				</div>
        <form id="comment_form">
          <input type="hidden" id="complaint_id" name="complaint_id">
          <div class="form-group">
            <label for="comment" class="col-form-label">備註:</label>
            <input type="text" class="form-control" id="txt_comment" name="txt_comment" style="width:500px">
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="complaint_add_comment()">送出</button>
      </div>
    </div>
  </div>
</div>


<? endif; ?>

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
?>
