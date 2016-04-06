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
    <li class="<?=empty($span) ? "active" : ""?>">
        <a href="<?=site_url("statistics/marketing?game_id={$this->game_id}&start_date={$start_date}&end_date={$end_date}")?>">日報表</a>
    </li>
    <li class="<?=($span=='weekly') ? "active" : ""?>">
        <a href="<?=site_url("statistics/marketing?game_id={$this->game_id}&start_date={$start_date}&end_date={$end_date}&span=weekly")?>">週報表</a>
    </li>
    <li class="<?=($span=='monthly') ? "active" : ""?>">
        <a href="<?=site_url("statistics/marketing?game_id={$this->game_id}&start_date={$start_date}&end_date={$end_date}&span=monthly")?>">月報表</a>
    </li>
</ul>

<form method="get" action="<?=site_url("statistics/marketing")?>" class="form-search">
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
		
		<input type="submit" class="btn btn-small btn-inverse" name="action" value="行銷效益數據">	
	
	</div>
		
</form>

<?
    switch ($this->input->get("span")) {
		case "weekly":
			$retention_string          = "前1週新增用戶週回訪數";
			$retention_rate_string     = "前1週新增用戶週回訪率";
			$retention_all_string      = "前1週登入用戶週回訪數";
			$retention_all_rate_string = "前1週登入用戶週回訪率";
			break;
			
		case "monthly":
			$retention_string          = "前1月新增用戶月回訪數";
			$retention_rate_string     = "前1月新增用戶月回訪率";
			$retention_all_string      = "前1月登入用戶月回訪數";
			$retention_all_rate_string = "前1月登入用戶月回訪率";
			break;
			
		default:
			$retention_string          = "前1日新增用戶次日留存";
			$retention_rate_string     = "前1日新增用戶次日留存率";
			$retention_all_string      = "前1日登入用戶次日留存";
			$retention_all_rate_string = "前1日登入用戶次日留存率";
			break;
	}
    if ($query):?>
	<? if ($query->num_rows() == 0): echo '<div class="none">查無資料</div>'; else: ?>
	<table class="table table-striped table-bordered" style="width:auto;">
		<thead>
			<tr>
				<th nowrap="nowrap">日期</th>
				<th style="width:70px">新增用戶</th>
				<th style="width:70px">登入用戶</th>
				<th style="width:70px">登入設備</th>
				<th style="width:70px">登入轉化率</th>
				<th style="width:70px">第三方APK登入</th>
				<th style="width:70px">總下載數</th>
				<th style="width:70px">ios下載數</th>
				<th style="width:70px">android下載數</th>	
				<th style="width:70px">第三方APK下載數</th>
				<th style="width:70px">下載轉化率</th>
				<th style="width:70px">下載地區-台灣</th>
				<th style="width:70px">下載地區-香港</th>
				<th style="width:70px">下載地區-澳門</th>
				<th style="width:70px">下載地區-新加坡</th>
				<th style="width:70px">下載地區-馬來西亞</th>
				<th style="width:70px">下載地區-其他</th>
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
                
                $download_count = $row->ios_download_count+$row->google_download_count+$row->apk_download_count;
                $tw_download_count = $row->ios_tw_download_count+$row->google_tw_download_count+$row->apk_tw_download_count;
                $hk_download_count = $row->ios_hk_download_count+$row->google_hk_download_count+$row->apk_hk_download_count;
                $mo_download_count = $row->ios_mo_download_count+$row->google_mo_download_count+$row->apk_mo_download_count;
                $sg_download_count = $row->ios_sg_download_count+$row->google_download_count+$row->apk_sg_download_count;
                $my_download_count = $row->ios_my_download_count+$row->google_download_count+$row->apk_my_download_count;
		?>
			<tr>			
				<td nowrap="nowrap"><?=$show_date?></td>
				<td style="text-align:right"><?=number_format($row->new_login_count)?></td>
				<td style="text-align:right"><?=number_format($row->login_count)?></td>
				<td style="text-align:right"><?=number_format($row->device_count)?></td>
				<td style="text-align:right"><?=number_format(($row->device_count)?$row->login_count/$row->device_count*100:0, 2)."%"?></td>
				<td style="text-align:right"><?=number_format($row->apk_login_count)?></td>
				<td style="text-align:right"><?=number_format($download_count)?></td>
				<td style="text-align:right"><?=number_format($row->ios_download_count)?></td>
				<td style="text-align:right"><?=number_format($row->google_download_count)?></td>
				<td style="text-align:right"><?=number_format($row->apk_download_count)?></td>
				<td style="text-align:right"><?=number_format(($row->login_count)?($download_count)/$row->login_count*100:0, 2)."%"?></td>
				<td style="text-align:right"><?=number_format($tw_download_count)?></td>
				<td style="text-align:right"><?=number_format($hk_download_count)?></td>
				<td style="text-align:right"><?=number_format($mo_download_count)?></td>
				<td style="text-align:right"><?=number_format($sg_download_count)?></td>
				<td style="text-align:right"><?=number_format($my_download_count)?></td>
				<td style="text-align:right"><?=number_format($download_count-$tw_download_count-$hk_download_count-$mo_download_count-$sg_download_count-$my_download_count)?></td>
			</tr>
		<? endforeach;?>
		</tbody>
	</table>
	<? endif;?>
<? endif;?>