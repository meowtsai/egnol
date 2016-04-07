<?php
	$channels = $this->config->item('channels'); 
	$c_game_query = $this->db->from("games")->order_by("rank")->get();

	$c_game = array();
	$c_game_menu["獨代"] = array();
	$c_game_menu["聯運"] = array();
	$c_game_menu["關閉"] = array();
	
	$exchange_rate = 1;
	
	foreach($c_game_query->result() as $row) {
		$c_game[$row->game_id] = $row;		
		if (!$row->is_active) {$c_game_menu["關閉"][] = $row; continue;}
		if (strpos($row->tags.",", "聯運,") !== false) {$c_game_menu["聯運"][] = $row; continue;}
		$c_game_menu["獨代"][] = $row;
		
		if ($row->game_id == $game_id) $exchange_rate = $row->exchange_rate;
	}
?>
<div id="func_bar">
	
</div>

<ul class="nav nav-tabs">
    <li class="">
        <a href="<?=site_url("statistics/user_new?game_id={$this->game_id}&start_date={$start_date}&end_date={$end_date}")?>">新增分析</a>
    </li>
    <li class="active">
        <a href="<?=site_url("statistics/user_new_by_login?game_id={$this->game_id}&start_date={$start_date}&end_date={$end_date}")?>">新增分析(依登入)</a>
    </li>
    <li class="">
        <a href="<?=site_url("statistics/user_retention?game_id={$this->game_id}&start_date={$start_date}&end_date={$end_date}")?>">留存率</a>
    </li>
    <li class="">
        <a href="<?=site_url("statistics/user_retention_by_login?game_id={$this->game_id}&start_date={$start_date}&end_date={$end_date}")?>">留存率(依登入)</a>
    </li>
    <li class="">
        <a href="<?=site_url("statistics/user_return?game_id={$this->game_id}&start_date={$start_date}&end_date={$end_date}")?>">回流率</a>
    </li>
    <li class="">
        <a href="<?=site_url("statistics/user_return_by_login?game_id={$this->game_id}&start_date={$start_date}&end_date={$end_date}")?>">回流率(依登入)</a>
    </li>
</ul>

<form method="get" action="<?=site_url("statistics/user_new_by_login")?>" class="form-search">
	<!--input type="hidden" name="game_id" value="<?=$this->input->get("span")?>"-->
	<input type="hidden" name="span" value="<?=$this->input->get("span")?>">
	<div class="control-group">
		
		<select name="game_id">
		    <option value="">--請選擇遊戲--</option>
			<?
			foreach($c_game_menu as $category => $c_menu):?>
				<option value=""> -------- <?=$category?> --------</option>
				<? foreach($c_menu as $key => $row):?>
				<option value="<?=$row->game_id?>" <?=($game_id==$row->game_id ? 'selected="selected"' : '')?>><?=$row->name?>	</option>
				<? endforeach;?>
			<? $i++;  
			endforeach;?>	
	    </select>
		
		時間
		<input type="text" name="start_date" class="date required" value="<?=($this->input->get("start_date"))?$this->input->get("start_date"):date("Y-m-d",strtotime("-8 days"))?>" style="width:120px"> 至
		<input type="text" name="end_date" class="date" value="<?=$this->input->get("end_date")?>" style="width:120px" placeholder="現在">
		<a href="javascript:;" class="clear_date"><i class="icon-remove-circle" title="清除"></i></a>
		
		<input type="submit" class="btn btn-small btn-inverse" name="action" value="新增用戶分析">	
	
	</div>
		
</form>

<?
    if ($query):?>
	<? if ($query->num_rows() == 0): echo '<div class="none">查無資料</div>'; else: ?>
	<table class="table table-striped table-bordered" style="width:auto;">
		<thead>
			<tr>
				<th rowspan="2">日期</th>
				<th colspan="5">新增用戶</th>
				<th colspan="5">新增登入設備</th>
				<th colspan="5">帳號轉化率</th>			 	
			</tr>
			<tr>
				<th>FB</th>
				<th>G+</th>
				<th>龍邑</th>	
				<th>快速</th>	
				<th>總和</th>	
				<th>FB</th>
				<th>G+</th>
				<th>龍邑</th>	
				<th>快速</th>	
				<th>總和</th>	
				<th>FB</th>
				<th>G+</th>
				<th>龍邑</th>	
				<th>快速</th>	
				<th>總和</th>			 	
			</tr>
		</thead>
		<tbody>
		<? $color = array('00aa00', '448800', '776600', 'aa4400', 'dd2200', 'ff0000');
			foreach($query->result() as $row):
		?>  
			<tr>			
				<td nowrap="nowrap"><?=$row->date?></td>
				<td style="text-align:right"><?=number_format(($row->new_login_facebook_count)?$row->new_login_facebook_count:0, 2)?></td>
				<td style="text-align:right"><?=number_format(($row->new_login_google_count)?$row->new_login_google_count:0, 2)?></td>
				<td style="text-align:right"><?=number_format(($row->new_login_longe_count)?$row->new_login_longe_count:0, 2)?></td>
				<td style="text-align:right"><?=number_format(($row->new_login_quick_count)?$row->new_login_quick_count:0, 2)?></td>
				<td style="text-align:right"><?=number_format(($row->new_login_count)?$row->new_login_count:0, 2)?></td>
				<td style="text-align:right"><?=number_format(($row->new_device_facebook_count)?$row->new_device_facebook_count:0, 2)?></td>
				<td style="text-align:right"><?=number_format(($row->new_device_google_count)?$row->new_device_google_count:0, 2)?></td>
				<td style="text-align:right"><?=number_format(($row->new_device_longe_count)?$row->new_device_longe_count:0, 2)?></td>
				<td style="text-align:right"><?=number_format(($row->new_device_quick_count)?$row->new_device_quick_count:0, 2)?></td>
				<td style="text-align:right"><?=number_format(($row->new_device_count)?$row->new_device_count:0, 2)?></td>
				<td style="text-align:right"><?=number_format(($row->new_device_facebook_count)?$row->new_login_facebook_count/$row->new_device_facebook_count*100:0, 2)."%"?></td>		
				<td style="text-align:right"><?=number_format(($row->new_device_google_count)?$row->new_login_google_count/$row->new_device_google_count*100:0, 2)."%"?></td>	
				<td style="text-align:right"><?=number_format(($row->new_device_longe_count)?$row->new_login_longe_count/$row->new_device_longe_count*100:0, 2)."%"?></td>	
				<td style="text-align:right"><?=number_format(($row->new_device_quick_count)?$row->new_login_quick_count/$row->new_device_quick_count*100:0, 2)."%"?></td>	
				<td style="text-align:right"><?=number_format(($row->new_device_count)?$row->new_login_count/$row->new_device_count*100:0, 2)."%"?></td>												
			</tr>
		<? endforeach;?>
		</tbody>
	</table>
	<? endif;?>
<? endif;?>