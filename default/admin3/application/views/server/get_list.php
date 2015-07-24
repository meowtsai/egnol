<?php 
	$c_game_query = $this->db->from("games")->order_by("rank")->get();

	$c_game = array();
	$c_game_menu["獨代"] = array();
	$c_game_menu["聯運"] = array();
	$c_game_menu["關閉"] = array();
	
	foreach($c_game_query->result() as $row) {
		$c_game[$row->game_id] = $row;		
		if (!$row->is_active) {$c_game_menu["關閉"][] = $row; continue;}
		if (strpos($row->tags.",", "聯運,") !== false) {$c_game_menu["聯運"][] = $row; continue;}
		$c_game_menu["獨代"][] = $row;
	}
	
	$sever_status = $this->config->item("server_status");	
	$enable = $this->config->item("enable");
	$enable[1]['name'] = '開啟';
	$enable[0]['name'] = '關閉';
?>

<? foreach($c_game_menu as $category => $c_menu):?>
	<?=$category?>
	<select class="selectRedirect" name="game">
		<option value="">--</option>
		<? foreach($c_menu as $key => $row):?>
		<option value="<?=site_url("server/get_list?game_id={$row->game_id}")?>" <?=($this->game_id==$row->game_id ? 'selected="selected"' : '')?>><?=$row->name?>	</option>
		<? endforeach;?>
	</select>
<? endforeach;?>	

<? if ($query):?>
<div id="func_bar">
	<a class="btn btn-primary" href="<?=site_url("server/add?game_id={$this->game_id}")?>">新增</a>
</div>

<table class="table table-striped table-bordered">
  <thead>
	<tr>
		<th style="width:20px;"><input type="checkbox" class="all_cb"></th>
		<th style="width:100px;">#</th>
		<th style="width:120px;">伺服器代碼</th><th>伺服器名稱</th><th>遊戲串接設定</th><th style="width:80px;">伺服器狀況</th><th style="width:80px;">金流交易</th><th style="width:50px"></th>
	</tr>
  </thead>
  <tbody>
	<? foreach($query->result() as $row):?>
	<tr>
		<td><input type="checkbox" class="cb" id="<?=$row->id?>"></td>
		<td><?=$row->id?></td>		
		<td><?=$row->server_id?></td>
		<td>
			<span style="padding-right:6px;"><?=$row->name?></span>
			<?=$row->is_new_server=='1'?'<span style="padding-left:3px; color:#d40; font-size:12px; font-weight:bold;">« 新服</span>':''?>
			<?=$row->is_entry_server=='1'?'<span style="padding-left:3px; color:#170; font-size:12px; font-weight:bold;">« 入口</span>':''?>
		</td>
		<td><?=$row->address?></td>
		<td style="color:<?=$sever_status[$row->server_status]["color"]?>"><?=$sever_status[$row->server_status]["name"]?></td>
		<td style="color:<?=$enable[($row->is_transaction_active)?1:0]["color"]?>"><?=$enable[($row->is_transaction_active)?1:0]["name"]?></td>
		<td>
			<div class="btn-group">
				<a href="<?=site_url("server/edit/{$row->id}?game_id={$this->game_id}")?>" class="btn btn-mini">修改</a>		
					<a class="btn btn-mini dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></a>
					<ul class="dropdown-menu pull-right" role="menu" aria-labelledby="dropdownMenu">
						<li><a href="javascript:;" class="json_post" url="<?=site_url("server/set_new/{$row->id}?game_id={$this->game_id}")?>">設為新服</a></li>
						<li><a href="javascript:;" class="json_post" url="<?=site_url("server/set_entry/{$row->id}?game_id={$this->game_id}")?>">設為入口</a></li>
						<li class="dropdown-submenu pull-left">
						    <a tabindex="-1" href="#">伺服器狀況</a>
						    <ul class="dropdown-menu">
								<li><a href="javascript:;" class="json_post" url="<?=site_url("server/set_status/{$row->id}/public?game_id={$this->game_id}")?>">公開</a></li>
								<li><a href="javascript:;" class="json_post" url="<?=site_url("server/set_status/{$row->id}/maintaining?game_id={$this->game_id}")?>">維護</a></li>
								<li><a href="javascript:;" class="json_post" url="<?=site_url("server/set_status/{$row->id}/private?game_id={$this->game_id}")?>">內測</a></li>
								<li><a href="javascript:;" class="json_post" url="<?=site_url("server/set_status/{$row->id}/hide?game_id={$this->game_id}")?>">關閉</a></li>
						    </ul>
						</li>
						<li class="dropdown-submenu pull-left">
							<a tabindex="-1" href="#">金流交易</a>
						    <ul class="dropdown-menu">
								<li><a href="javascript:;" class="json_post" url="<?=site_url("server/set_tran_status/{$row->id}/1?game_id={$this->game_id}")?>">開啟</a></li>
								<li><a href="javascript:;" class="json_post" url="<?=site_url("server/set_tran_status/{$row->id}/0?game_id={$this->game_id}")?>">關閉</a></li>
						    </ul>						
						</li>
					</ul>				
			</div>
		</td>
	</tr>
	<? endforeach;?>
  </tbody>
  <tfoot>
  	<tr>
  		<td colspan="60">
	  		伺服器狀況: 
  			<a href="javascript:;" class="btn btn-inverse btn-mini muti_json_post" url="<?=site_url("server/set_muti_status/public?game_id={$this->game_id}")?>">公開</a>
  			<a href="javascript:;" class="btn btn-inverse btn-mini muti_json_post" url="<?=site_url("server/set_muti_status/maintaining?game_id={$this->game_id}")?>">維護</a>
  			<a href="javascript:;" class="btn btn-inverse btn-mini muti_json_post" url="<?=site_url("server/set_muti_status/private?game_id={$this->game_id}")?>">內測</a>
  			<a href="javascript:;" class="btn btn-inverse btn-mini muti_json_post" url="<?=site_url("server/set_muti_status/hide?game_id={$this->game_id}")?>">關閉</a>
  			<span class="sptl"></span>
  			金流交易: 
  			<a href="javascript:;" class="btn btn-inverse btn-mini muti_json_post" url="<?=site_url("server/set_muti_tran_status/1?game_id={$this->game_id}")?>">開啟</a>
  			<a href="javascript:;" class="btn btn-inverse btn-mini muti_json_post" url="<?=site_url("server/set_muti_tran_status/0?game_id={$this->game_id}")?>">關閉</a>
  		</td>
  	</tr>
  </tfoot>
</table>
<? endif;?>
<SCRIPT LANGUAGE="JavaScript">
$('.selectRedirect').change(function() {
  // set the window's location property to the value of the option the user has selected
  window.location = $(this).val();
});
</SCRIPT>
