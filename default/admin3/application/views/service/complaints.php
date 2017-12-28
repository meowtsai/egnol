<?php
	$complaints_category = $this->config->item('complaints_category');
?>

<div id="func_bar">
</div>

<form method="get" action="<?=site_url("service/complaints")?>" class="form-search">
	<input type="hidden" name="game_id" value="<?=$this->game_id?>">

	<div class="control-group">


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




		<input type="text" name="character_name" value="<?=$this->input->get("character_name")?>" style="width:90px" placeholder="角色名稱">

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
		<tr>
			<td style="font-size:12px;">
      		<?=$row->server_id?> - <?=$row->server_name?>
          <i class="fas fa-user"></i> <a href="get_list?character_name=<?=$row->reporter_name?>&email=&mobile=&action=查詢"> <?=$row->reporter_name?></a><span style="font-size:11px; color:green;"> <?=$row->reporter_char_id?> </span> 舉報
          <i class="far fa-user"></i><a href="get_list?character_name=<?=$row->flagged_player_name?>&email=&mobile=&action=查詢"> <?=$row->flagged_player_name?></a> <span style="font-size:11px; color:red;"><?=$row->flagged_player_char_id?> </span>  [<?=$complaints_category[$row->category]?>]

            <? if ($row->reason): ?>

              <i>  "<?=$row->reason?>"</i>
              <i class="far fa-comment"></i>
            <? endif; ?>

            <? $tmp_date = new DateTime($row->create_time) ?>

            <div> # <?=$row->id?> - <?=ago($tmp_date)?></div>

			</td>
		</tr>
		<? endforeach;?>
		<? endif; ?>
	</tbody>
</table>


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
