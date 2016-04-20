<div id="func_bar">
	
</div>

<!--ul class="nav nav-tabs">
    <li class="<?=(empty($span)) ? "active" : ""?>">
        <a href="<?=site_url("statistics/operation?game_id={$this->game_id}&start_date={$start_date}&end_date={$end_date}")?>">日報表</a>
    </li>
    <li class="<?=($span=='weekly') ? "active" : ""?>">
        <a href="<?=site_url("statistics/operation?game_id={$this->game_id}&start_date={$start_date}&end_date={$end_date}&span=weekly")?>">週報表</a>
    </li>
    <li class="<?=($span=='monthly') ? "active" : ""?>">
        <a href="<?=site_url("statistics/operation?game_id={$this->game_id}&start_date={$start_date}&end_date={$end_date}&span=monthly")?>">月報表</a>
    </li>
    <li class="">
        <a href="<?=site_url("statistics/deposit_level?game_id={$this->game_id}&start_date={$start_date}&end_date={$end_date}")?>">儲值區間分析</a>
    </li>
    <li class="">
        <a href="<?=site_url("statistics/deposit_analysis?game_id={$this->game_id}&start_date={$start_date}&end_date={$end_date}")?>">會員儲值分析</a>
    </li>
    <li class="">
        <a href="<?=site_url("statistics/lifetime_value?game_id={$this->game_id}&start_date={$start_date}&end_date={$end_date}")?>">LTV分析</a>
    </li>
    <li class="">
        <a href="<?=site_url("statistics/game_consumes?game_id={$this->game_id}&start_date={$start_date}&end_date={$end_date}")?>">消費分析</a>
    </li>
</ul-->

<form method="get" action="<?=site_url("statistics/appsflyer")?>" class="form-search">
	<!--input type="hidden" name="game_id" value="<?=$this->input->get("span")?>"-->
	<input type="hidden" name="span" value="<?=$this->input->get("span")?>">
	<div class="control-group">
		
		時間
		<input type="text" name="start_date" class="date required" value="<?=($this->input->get("start_date"))?$this->input->get("start_date"):date("Y-m-d",strtotime("-8 days"))?>" style="width:120px"> 至
		<input type="text" name="end_date" class="date" value="<?=$this->input->get("end_date")?>" style="width:120px" placeholder="現在">
		<a href="javascript:;" class="clear_date"><i class="icon-remove-circle" title="清除"></i></a>
		
		<input type="submit" class="btn btn-small btn-inverse" name="action" value="Appsflyer追蹤">	
	
	</div>
		
</form>

<?if ($query):?>
	<? if ($query->num_rows() == 0): echo '<div class="none">查無資料</div>'; else: ?>
	<table class="table table-striped table-bordered" style="width:auto;">
		<thead>
			<tr>
				<th nowrap="nowrap">日期</th>
				<th style="width:70px">遊戲</th>
				<th style="width:70px">媒體</th>
				<th style="width:70px">裝置</th>
				<th style="width:70px">點擊</th>
				<th style="width:70px">下載/安裝</th>
				<th style="width:70px">地區</th>
				<th style="width:70px">額外設置-<br>登入(首次)</th>
				<th style="width:70px">額外設置-<br>登入</th>
				<th style="width:70px">額外設置-<br>登入(銷售)</th>
				<th style="width:70px">額外設置-<br>創角(首次)</th>
				<th style="width:70px">額外設置-<br>創角</th>
				<th style="width:70px">額外設置-<br>創角(銷售)</th>
				<th style="width:70px">額外設置-<br>升等(首次)</th>
				<th style="width:70px">額外設置-<br>升等</th>
				<th style="width:70px">額外設置-<br>升等(銷售)</th>		 	
			</tr>
		</thead>
		<tbody>
		<? $color = array('00aa00', '448800', '776600', 'aa4400', 'dd2200', 'ff0000');
			foreach($query->result() as $row):?>
			<tr>			
				<td nowrap="nowrap"><?=$row->date?></td>
				<td nowrap="nowrap"><?=$row->game_id?></td>
				<td nowrap="nowrap"><?=$row->media?></td>
				<td nowrap="nowrap"><?=($row->platform=='google')?'android':$row->platform?></td>
				<td style="text-align:right"><?=number_format($row->click_count)?></td>
				<td style="text-align:right"><?=number_format($row->install_count)?></td>
				<td nowrap="nowrap"><?=$row->country_code?></td>
				<td style="text-align:right"><?=number_format($row->af_login_unique)?></td>	
				<td style="text-align:right"><?=number_format($row->af_login)?></td>	
				<td style="text-align:right"><?=number_format($row->af_login_sales)?></td>	
				<td style="text-align:right"><?=number_format($row->le_usercharactercreate_unique)?></td>	
				<td style="text-align:right"><?=number_format($row->le_usercharactercreate)?></td>	
				<td style="text-align:right"><?=number_format($row->le_usercharactercreate_sales)?></td>	
				<td style="text-align:right"><?=number_format($row->le_usercharacterlevelup_unique)?></td>	
				<td style="text-align:right"><?=number_format($row->le_usercharacterlevelup)?></td>	
				<td style="text-align:right"><?=number_format($row->le_usercharacterlevelup_sales)?></td>												
			</tr>
		<? endforeach;?>
		</tbody>
	</table>
	<? endif;?>
<? endif;?>