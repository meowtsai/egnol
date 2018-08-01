<?
//$array_games = (array)$games->result() ;
//$array_games["g78naxx2hmt"];

$enable = $this->config->item("enable");
$enable[99] = array("name"=>"置頂", "color"=>"#00a");
$enable[0] = array("name"=>"關閉", "color"=>"#a99");

$game_dict = array();
?>

<div id="func_bar">
	<a href="<?=site_url("game_faq/add?game_id={$this->game_id}&type={$bulletin_type}")?>" class="btn btn-primary">+ 新增</a>
</div>
<form method="post" action="<?=site_url("game_faq/get_list?game_id={$this->game_id}")?>" class="form-inline">
	<div class="form-group">
		<label for="input_keyword" class="sr-only" ></label>
		<input type="text" name="input_keyword" value="<?=$keyword?>" class="form-control" placeholder="關鍵字" id="input_keyword">
    遊戲
    <select name="game" style="width:120px">
      <option value="">--</option>
      <? foreach($games->result() as $row):?>
			<?
			$game_dict[$row->game_id] = $row->name;
			 ?>
      <option value="<?=$row->game_id?>" <?=($game==$row->game_id ? 'selected="selected"' : '')?>><?=$row->name?></option>
      <? endforeach;?>
    </select>




    問題類型
    <select name="type" style="width:100px">
      <option value="">--</option>
      <? foreach($question_type as $key => $type_text):?>

      <option value="<?=$key?>" <?=($type==$key ? 'selected="selected"' : '')?>><?=$type_text?></option>
      <? endforeach;?>
    </select>
		<input type="submit" class="btn btn-small btn-inverse" name="action" value="查詢">
	</div>
</form>



<?=tran_pagination($this->pagination->create_links());?>

<? if ($query->num_rows() == 0):?>

<div class="none">尚無資料，<a href="<?=site_url("game_faq/add?game_id={$this->game_id}&type={$type}")?>">立即新增</a>。</div>

<? else:?>

<table class="table table-striped table-bordered">
	<thead>
		<tr><th style="width:50px;">#</th>
      <th>標題</th>
      <th>遊戲</th>
      <th>分類</th>

      <th style="width:190px">發布時間</th>
      <th style="width:70px">是否發布</th>
      <th style="width:50px;"></th></tr>
	</thead>
	<tbody>
	<? foreach($query->result() as $row):
		if ($row->priority <> 0) {
			if ($row->start_time > now()) $row->priority = 9;
			if ($row->end_time < now()) $row->priority = 8;
		}
	?>
	    <tr>
		    <td><?=$row->id?></td>
		    <td>
				     <?=$row->title?>
		    </td>
        <td>
					<? foreach(explode(',',$row->games) as $game_id):?>
					<span class="badge badge-pill badge-primary"><?=$game_dict[$game_id]; ?></span>
					<?endforeach;?>
					</td>
        <td>
          <? foreach(explode(',',$row->type_ids) as $type_id):?>
          <span class="badge badge-pill badge-primary"><?=mb_substr($question_type[$type_id], 0, 1, 'utf-8') ?></span>
          <?endforeach;?>
        </td>
		    <td style="font-size:13px; color:#666">
			    <?=date("Y/m/d H:i", strtotime($row->start_time))?>
			    <?=$row->end_time<'2038-01-01' ? "<Br>~ ".date("Y/m/d H:i", strtotime($row->end_time)) : ''?>
		    </td>
		    <td style="color:<?=$enable[$row->priority]['color']?>">
          <?=$enable[$row->priority]['name']?>
        </td>
		    <td>
			    <div class="btn-group">
				    <a href="<?=site_url("game_faq/edit/{$row->id}?game_id={$this->game_id}&record={$this->input->get("record")}")?>" class="btn btn-mini">
					    修改
				    </a>
				    <a class="btn btn-mini dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></a>
				    <ul class="dropdown-menu pull-right">
					    <li><a href="javascript:;" class="del" url="<?=site_url("game_faq/delete/{$row->id}")?>">
							<i class="icon icon-remove"></i>  刪除</a></li>
					    <li class="dropdown-submenu pull-left">
					        <a tabindex="-1" href="#">調整狀態</a>
					        <ul class="dropdown-menu">
							    <li><a href="javascript:;" class="json_post" url="<?=site_url("game_faq/set_priority/{$row->id}/2")?>">置頂</a></li>
							    <li><a href="javascript:;" class="json_post" url="<?=site_url("game_faq/set_priority/{$row->id}/1")?>">發布</a></li>
							    <li><a href="javascript:;" class="json_post" url="<?=site_url("game_faq/set_priority/{$row->id}/0")?>">不發布</a></li>
					        </ul>
					    </li>
				    </ul>
			    </div>
		    </td>
	    </tr>
    <? endforeach;?>
    </tbody>
</table>

<? endif;?>
<SCRIPT LANGUAGE="JavaScript">
$('.selectRedirect').change(function() {
  // set the window's location property to the value of the option the user has selected
  window.location = $(this).val();
});
</SCRIPT>
