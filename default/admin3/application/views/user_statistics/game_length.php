<?php 
	$channels = $this->config->item('channels');
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
    
    switch ($type) {
        case "新增用戶":
            $this_url=site_url("user_statistics/game_length_new");
            break;
        case "新增儲值用戶":
            $this_url=site_url("user_statistics/game_length_new_deposit");
            break;
        case "儲值用戶":
            $this_url=site_url("user_statistics/game_length_deposit");
            break;
        default:
            $this_url=site_url("user_statistics/game_length_all");
            break;
    }
?>
<div id="func_bar">
	
</div>

<ul class="nav nav-tabs">
    <li class="<?=(empty($span) && $type=="新增用戶") ? "active" : ""?>">
        <a href="<?=site_url("user_statistics/game_length_new?game_id={$this->game_id}&start_date={$start_date}&end_date={$end_date}")?>">新增用戶(日)</a>
    </li>
    <li class="<?=($span=='weekly' && $type=="新增用戶") ? "active" : ""?>">
        <a href="<?=site_url("user_statistics/game_length_new?game_id={$this->game_id}&start_date={$start_date}&end_date={$end_date}&span=weekly")?>">(週)</a>
    </li>
    <li class="<?=($span=='monthly' && $type=="新增用戶") ? "active" : ""?>">
        <a href="<?=site_url("user_statistics/game_length_new?game_id={$this->game_id}&start_date={$start_date}&end_date={$end_date}&span=monthly")?>">(月)</a>
    </li>
    <li class="<?=(empty($span) && $type=="所有用戶") ? "active" : ""?>">
        <a href="<?=site_url("user_statistics/game_length_all?game_id={$this->game_id}&start_date={$start_date}&end_date={$end_date}")?>">所有用戶(日)</a>
    </li>
    <li class="<?=($span=='weekly' && $type=="所有用戶") ? "active" : ""?>">
        <a href="<?=site_url("user_statistics/game_length_all?game_id={$this->game_id}&start_date={$start_date}&end_date={$end_date}&span=weekly")?>">(週)</a>
    </li>
    <li class="<?=($span=='monthly' && $type=="所有用戶") ? "active" : ""?>">
        <a href="<?=site_url("user_statistics/game_length_all?game_id={$this->game_id}&start_date={$start_date}&end_date={$end_date}&span=monthly")?>">(月)</a>
    </li>
    <li class="<?=(empty($span) && $type=="新增儲值用戶") ? "active" : ""?>">
        <a href="<?=site_url("user_statistics/game_length_new_deposit?game_id={$this->game_id}&start_date={$start_date}&end_date={$end_date}")?>">新增儲值用戶(日)</a>
    </li>
    <li class="<?=($span=='weekly' && $type=="新增儲值用戶") ? "active" : ""?>">
        <a href="<?=site_url("user_statistics/game_length_new_deposit?game_id={$this->game_id}&start_date={$start_date}&end_date={$end_date}&span=weekly")?>">(週)</a>
    </li>
    <li class="<?=($span=='monthly' && $type=="新增儲值用戶") ? "active" : ""?>">
        <a href="<?=site_url("user_statistics/game_length_new_deposit?game_id={$this->game_id}&start_date={$start_date}&end_date={$end_date}&span=monthly")?>">(月)</a>
    </li>
    <li class="<?=(empty($span) && $type=="儲值用戶") ? "active" : ""?>">
        <a href="<?=site_url("user_statistics/game_length_deposit?game_id={$this->game_id}&start_date={$start_date}&end_date={$end_date}")?>">儲值用戶(日)</a>
    </li>
    <li class="<?=($span=='weekly' && $type=="儲值用戶") ? "active" : ""?>">
        <a href="<?=site_url("user_statistics/game_length_deposit?game_id={$this->game_id}&start_date={$start_date}&end_date={$end_date}&span=weekly")?>">(週)</a>
    </li>
    <li class="<?=($span=='monthly' && $type=="儲值用戶") ? "active" : ""?>">
        <a href="<?=site_url("user_statistics/game_length_deposit?game_id={$this->game_id}&start_date={$start_date}&end_date={$end_date}&span=monthly")?>">(月)</a>
    </li>
</ul>

<form method="get" action="<?=$this_url?>" class="form-search">
	<!--input type="hidden" name="game_id" value="<?=$this->game_id?>"-->
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
		
		<input type="submit" class="btn btn-small btn-inverse" name="action" value="在線時長">	
	
	</div>
	
	<p class="text-info">
			<span class="label label-info">欄位說明</span>
			廣告參數是使用like, 規則為 like '廣告%'
		</p>	
		
</form>

<? if ($query):?>
	<? if ($query->num_rows() == 0): echo '<div class="none">查無資料</div>'; else: ?>
	<table class="table table-striped table-bordered" style="width:auto;">
		<thead>
			<tr>
				<th nowrap="nowrap">日期</th>
				<th style="width:70px"><?=($type=="新增用戶")?"新增登入數":"DAU"?></th>
				<th style="width:140px" colspan="2">在線15分鐘以下</th>
				<th style="width:140px" colspan="2">在線15~30分鐘</th>	
				<th style="width:140px" colspan="2">在線30~60分鐘</th>
				<th style="width:140px" colspan="2">在線60~90分鐘</th>
				<th style="width:140px" colspan="2">在線90~120分鐘</th>
				<th style="width:140px" colspan="2">在線120分鐘以上</th>	 	
			</tr>
		</thead>
		<tbody>
		<? $color = array('00aa00', '448800', '776600', 'aa4400', 'dd2200', 'ff0000');
			foreach($query->result() as $row):
				$startdate = strtotime($row->date);
				$enddate = time();
				$days = round(($enddate-$startdate)/3600/24) ; 
				
				switch($this->input->get("span")) {
					case "weekly":
						$year = substr($row->date, 0, 4);
						$week = substr($row->date, 4, 2);
						$show_date =  date("Y-m-d",strtotime($year."W".sprintf('%02d', $week))) 
						      . "~" . date("m-d",strtotime($year."W".sprintf('%02d',$week)."7"));
						break;
					
					case "monthly":
						$show_date = $row->year . "-" . $row->date;
						break;
						
					default:
						$show_date = $row->date;
						break;
				}
		?>
			<tr>			
				<td nowrap="nowrap"><?=$show_date?></td>
				<td style="text-align:right"><?=($row->login_count)?$row->login_count:0?></td>
				<td style="text-align:right"><?=($row->login_count_15)?$row->login_count_15:0?></td>
				<td style="text-align:right"><?=number_format(($row->login_count)?$row->login_count_15*100/$row->login_count:0, 2).'%'?></td>
				<td style="text-align:right"><?=($row->login_count_30)?$row->login_count_30:0?></td>
				<td style="text-align:right"><?=number_format(($row->login_count)?$row->login_count_30*100/$row->login_count:0, 2).'%'?></td>
				<td style="text-align:right"><?=($row->login_count_60)?$row->login_count_60:0?></td>
				<td style="text-align:right"><?=number_format(($row->login_count)?$row->login_count_60*100/$row->login_count:0, 2).'%'?></td>
				<td style="text-align:right"><?=($row->login_count_90)?$row->login_count_90:0?></td>
				<td style="text-align:right"><?=number_format(($row->login_count)?$row->login_count_90*100/$row->login_count:0, 2).'%'?></td>
				<td style="text-align:right"><?=($row->login_count_120)?$row->login_count_120:0?></td>
				<td style="text-align:right"><?=number_format(($row->login_count)?$row->login_count_120*100/$row->login_count:0, 2).'%'?></td>
				<td style="text-align:right"><?=($row->login_count_more)?$row->login_count_more:0?></td>
				<td style="text-align:right"><?=number_format(($row->login_count)?$row->login_count_more*100/$row->login_count:0, 2).'%'?></td>													
			</tr>
		<? endforeach;?>
		</tbody>
	</table>
	<? endif;?>
<? endif;?>