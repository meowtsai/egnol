<?php
	$c_game_query = $this->db->from("games")->order_by("rank")->get();

	$c_game = array();
	$c_game_menu["獨代"] = array();
	$c_game_menu["聯運"] = array();
	$c_game_menu["關閉"] = array();

	foreach($c_game_query->result() as $row) {
		$c_game[$row->game_id] = $row;
		if ($row->is_active == 0) {$c_game_menu["關閉"][] = $row; continue;}
		if (strpos($row->tags.",", "聯運,") !== false) {$c_game_menu["聯運"][] = $row; continue;}
		$c_game_menu["獨代"][] = $row;
	}

	$enable = $this->config->item("enable");
	$enable[2] = array("name"=>"置頂", "color"=>"#00a");
	$enable[3] = array("name"=>"首篇", "color"=>"#a00");
	$enable[8] = array("name"=>"關閉", "color"=>"#a99");
	$enable[9] = array("name"=>"預約中", "color"=>"#977");
?>

<? foreach($c_game_menu as $category => $c_menu):?>
	<?=$category?>
	<select class="selectRedirect" name="game">
		<option value="<?=site_url("bulletin/get_list?game_id=long_e")?>">--</option>
		<? foreach($c_menu as $key => $row):?>
		<option value="<?=site_url("bulletin/get_list?game_id={$row->game_id}")?>" <?=($this->game_id==$row->game_id ? 'selected="selected"' : '')?>><?=$row->name?>	</option>
		<? endforeach;?>
	</select>
<? endforeach;?>

<div id="func_bar">
	<a href="<?=site_url("bulletin/add?game_id={$this->game_id}&type={$bulletin_type}")?>" class="btn btn-primary">+ 新增</a>
</div>
<form method="post" action="<?=site_url("bulletin/get_list?game_id={$this->game_id}")?>" class="form-inline">
	<div class="form-group">
		<label for="input_keyword" class="sr-only" ></label>
		<input type="text" name="input_keyword" value="<?=$keyword?>" class="form-control" placeholder="關鍵字" id="input_keyword">
		<input type="submit" class="btn btn-small btn-inverse" name="action" value="查詢">
	</div>
</form>

<ul class="nav nav-tabs">
    <li class="<?=empty($bulletin_type) ? "active" : ""?>">
        <a href="<?=site_url("bulletin/get_list?game_id={$this->game_id}")?>">所有分類</a>
    </li>
    <? foreach($bulletin_type_list as $type_id => $type_name):?>
    <li class="<?=$bulletin_type == $type_id ? "active" : ""?>"><a href="<?=site_url("bulletin/get_list/{$type_id}?game_id={$this->game_id}")?>"><?=$type_name?></a></li>
    <? endforeach;?>
</ul>

<?=tran_pagination($this->pagination->create_links());?>

<? if ($query->num_rows() == 0):?>

<div class="none">尚無資料，<a href="<?=site_url("bulletin/add?game_id={$this->game_id}&type={$bulletin_type}")?>">立即新增</a>。</div>

<? else:?>

<table class="table table-striped table-bordered">
	<thead>
		<tr><th style="width:50px;">#</th><th>標題</th><th style="width:90px">發布時間</th><th style="width:70px">是否發布</th><th style="width:50px;"></th></tr>
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
			    <a href="<?=server_site_url("game", "news/detail/{$row->id}?site={$this->game_id}")?>" target="_blank" title="檢視">
				    [<?=$bulletin_type_list[$row->type]?>] <?=$row->title?></a>
			    <a href="<?=server_site_url("game", "news/preview/{$row->id}?site={$this->game_id}")?>" target="_blank" title="預覽"><i class="icon icon-search"></i></a>
			    <a href="<?=server_site_url("game", "news/detail/{$row->id}?site={$this->game_id}&bodyonly=true")?>" target="_blank" title="純內文檢視"><i class="icon icon-zoom-in"></i></a>
		    </td>
		    <td style="font-size:13px; color:#666">
			    <?=date("m/d H:i", strtotime($row->start_time))?>
			    <?=$row->end_time<'2038-01-01' ? "<Br>~ ".date("m/d H:i", strtotime($row->end_time)) : ''?>
		    </td>
		    <td style="color:<?=$enable[$row->priority]['color']?>"><?=$enable[$row->priority]['name']?></td>
		    <td>
			    <div class="btn-group">
				    <a href="<?=site_url("bulletin/edit/{$row->id}?game_id={$this->game_id}&record={$this->input->get("record")}")?>" class="btn btn-mini">
					    修改
				    </a>
				    <a class="btn btn-mini dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></a>
				    <ul class="dropdown-menu pull-right">
					    <li><a href="javascript:;" class="del" url="<?=site_url("bulletin/delete/{$row->id}")?>">
							<i class="icon icon-remove"></i>  刪除</a></li>
					    <li class="dropdown-submenu pull-left">
					        <a tabindex="-1" href="#">調整狀態</a>
					        <ul class="dropdown-menu">
							    <li><a href="javascript:;" class="json_post" url="<?=site_url("bulletin/set_priority/{$row->id}/2")?>">置頂</a></li>
							    <li><a href="javascript:;" class="json_post" url="<?=site_url("bulletin/set_priority/{$row->id}/1")?>">發布</a></li>
							    <li><a href="javascript:;" class="json_post" url="<?=site_url("bulletin/set_priority/{$row->id}/0")?>">不發布</a></li>
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
