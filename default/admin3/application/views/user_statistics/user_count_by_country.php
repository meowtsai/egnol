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
        <a href="<?=site_url("user_statistics/new_users?game_id={$this->game_id}&start_date={$start_date}&end_date={$end_date}")?>">新增分析</a>
    </li>
    <li class="">
        <a href="<?=site_url("user_statistics/new_users_by_login?game_id={$this->game_id}&start_date={$start_date}&end_date={$end_date}")?>">新增分析(依登入)</a>
    </li>
    <li class="">
        <a href="<?=site_url("operation_statistics/user_retention?game_id={$this->game_id}&start_date={$start_date}&end_date={$end_date}")?>">留存率</a>
    </li>
    <li class="">
        <a href="<?=site_url("operation_statistics/user_retention_by_login?game_id={$this->game_id}&start_date={$start_date}&end_date={$end_date}")?>">留存率(依登入)</a>
    </li>
    <li class="">
        <a href="<?=site_url("operation_statistics/user_return?game_id={$this->game_id}&start_date={$start_date}&end_date={$end_date}")?>">回流率</a>
    </li>
    <li class="">
        <a href="<?=site_url("operation_statistics/user_return_by_login?game_id={$this->game_id}&start_date={$start_date}&end_date={$end_date}")?>">回流率(依登入)</a>
    </li>
    <li class="active">
        <a href="<?=site_url("user_statistics/user_count_by_country?game_id={$this->game_id}&start_date={$start_date}&end_date={$end_date}")?>">國家別登入用戶數</a>
    </li>

</ul>


<form method="get" action="<?=site_url("user_statistics/user_count_by_country")?>" class="form-search">
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
		
		<input type="submit" class="btn btn-small btn-inverse" name="action" value="國家別登入用戶數查詢">	
	
	</div>
		
</form>
<?=$qString?>
<?
    if ($query):?>
	<? if ($query->num_rows() == 0): echo '<div class="none">查無資料</div>'; else: ?>



	<table class="table table-striped table-bordered" style="width:auto;">
		<thead>
			<tr>
				<th>國家</th>
                <?foreach ($query->list_fields() as $field):?>
				<th><?=$field?></th>
				<? endforeach;?>
			</tr>
		</thead>
		<tbody>
		<? $color = array('00aa00', '448800', '776600', 'aa4400', 'dd2200', 'ff0000');
			foreach($query->result() as $row):
		?>  
			<tr>			
				<td nowrap="nowrap"><?=$row->country?></td>
                <?foreach ($query->list_fields() as $field):?>
                <td style="text-align:right"><?=$row[$field]?></td>
				<? endforeach;?>
				
								
			</tr>
		<? endforeach;?>
		</tbody>
	</table>
	<? endif;?>
<? endif;?>