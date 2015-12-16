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
?>

<? foreach($c_game_menu as $category => $c_menu):?>
	<?=$category?>
	<select class="selectRedirect" name="game">
		<option value="<?=site_url("picture/get_list?game_id=long_e")?>">--</option>
		<? foreach($c_menu as $key => $row):?>
		<option value="<?=site_url("picture/get_list?game_id={$row->game_id}")?>" <?=($this->game_id==$row->game_id ? 'selected="selected"' : '')?>><?=$row->name?>	</option>
		<? endforeach;?>
	</select>
<? endforeach;?>	

<div id="func_bar">
	<a href="<?=site_url("picture/category?game_id={$this->game_id}")?>" class="btn btn-info">分類管理</a>
	<a href="<?=site_url("picture/add?game_id={$this->game_id}&cgid={$category_id}")?>" class="btn btn-primary">+ 新增</a>
</div>

<? if ($category_query->num_rows() == 0):?>

<div class="none">尚無分類，請先<a href="<?=site_url("picture/add_category?game_id={$this->game_id}&category_id={$category_id}")?>">前往新增分類</a>。</div>

<? else:?>
	
	<ul class="nav nav-tabs">
	  <li class="<?=empty($category_id) ? "active" : ""?>">
	    <a href="<?=site_url("picture/get_list?game_id={$this->game_id}")?>">所有分類</a>
	  </li>
	  <? foreach($category_query->result() as $row):?>
	  <li class="<?=$category_id == $row->id ? "active" : ""?>"><a href="<?=site_url("picture/get_list/{$row->id}?game_id={$this->game_id}")?>"><?=$row->category?></a></li>
	  <? endforeach;?>
	</ul>
		
		
	<?=tran_pagination($this->pagination->create_links());?>
	
	<? if ($query->num_rows() == 0):?>
	
	<div class="none">尚無資料，<a href="<?=site_url("picture/add?game_id={$this->game_id}&category_id={$category_id}")?>">立即新增</a>。</div>
	
	<? else:?>
	
	<table class="table table-striped">
		<thead>
			<tr><td style="width:60px; text-align:center">#</td><td style="width:320px; text-align:center">圖片</td><td style="width:200px">超連結</td><td style="width:120px; text-align:center">分類</td><td style="width:60px">是否發布</td><td></td></tr>
		</thead>
		<tbody>
		<? foreach($query->result() as $row):?>
			<tr>
				<td style="text-align:center"><?=$row->id?></td>
				<td style="text-align:center">
					<span style="width:300px; height:60px; padding:2px; background:#eee; display:inline-block; overflow:hidden;">
						<?=make_pic_item($row)?>
					</span></td>
				<td><a href="<?=$row->link?>" target="_blank"><?=$row->link?></a></td>
				<td style="text-align:center"><?=$row->category?></td>
				<td style="text-align:center; color:<?=$enable[$row->is_active]['color']?>"><?=$enable[$row->is_active]['name']?></td>
				<td> 			
					<a href="<?=site_url("picture/edit/{$row->id}?game_id={$this->game_id}&record={$this->input->get("record")}")?>">修改</a> |
					<a href="javascript:;" class="del" url="<?=site_url("picture/delete/{$row->id}")?>">刪除</a>
				</td>
			</tr>
		<? endforeach;?>
		</tbody>
	</table>
	
	<? endif;?>

<? endif;?>
<SCRIPT LANGUAGE="JavaScript">
$('.selectRedirect').change(function() {
  // set the window's location property to the value of the option the user has selected
  window.location = $(this).val();
});
</SCRIPT>
