<?php 
	$channels = $this->config->item('channels');
?>
<div id="func_bar">
	
</div>

<ul class="nav nav-tabs">
    <li class="<?=empty($span) ? "active" : ""?>">
        <a href="<?=site_url("user_statistics/revenue")?>">日報表</a>
    </li>
    <li class="<?=($span=='weekly') ? "active" : ""?>">
        <a href="<?=site_url("user_statistics/revenue?span=weekly")?>">週報表</a>
    </li>
    <li class="<?=($span=='monthly') ? "active" : ""?>">
        <a href="<?=site_url("user_statistics/revenue?span=monthly")?>">月報表</a>
    </li>
    <li class="<?=($span=='deposit_behavior') ? "active" : ""?>">
        <a href="<?=site_url("user_statistics/deposit_behavior")?>">儲值習慣</a>
    </li>
</ul>

<form method="get" action="<?=site_url("user_statistics/revenue")?>" class="form-search">
	<input type="hidden" name="span" value="<?=$this->input->get("span")?>">
		
	<div class="control-group">
		
		時間
		<input type="text" name="start_date" class="date required" value="<?=($this->input->get("start_date"))?$this->input->get("start_date"):date("Y-m-d",strtotime("-8 days"))?>" style="width:120px"> 至
		<input type="text" name="end_date" class="date" value="<?=$this->input->get("end_date")?>" style="width:120px" placeholder="現在">
		<a href="javascript:;" class="clear_date"><i class="icon-remove-circle" title="清除"></i></a>
		
		<input type="submit" class="btn btn-small btn-inverse" name="action" value="營收統計">	
	
	</div>

<? if ($query):?>
	<? if ($query->num_rows() == 0): echo '<div class="none">查無資料</div>'; else: ?>
    
    <div><img src="<?=base_url()?>/p/jpgraphs/<?=$span?>_revenue_graph" alt=""></div>
    <div>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <input type="submit" class="btn btn-small <?=($this->input->get("action")=='總儲點' || $this->input->get("action")=='營收統計')?'btn-inverse':''?>" name="action" value="總儲點">
        <input type="submit" class="btn btn-small <?=($this->input->get("action")=='iOS')?'btn-inverse':''?>" name="action" value="iOS">
        <input type="submit" class="btn btn-small <?=($this->input->get("action")=='Android')?'btn-inverse':''?>" name="action" value="Android">
        <input type="submit" class="btn btn-small <?=($this->input->get("action")=='MyCard')?'btn-inverse':''?>" name="action" value="MyCard">
        <input type="submit" class="btn btn-small <?=($this->input->get("action")=='天天賺')?'btn-inverse':''?>" name="action" value="天天賺">
        <input type="submit" class="btn btn-small <?=($this->input->get("action")=='VIP')?'btn-inverse':''?>" name="action" value="VIP">
    </div>
    <div>&nbsp;</div>

	<table class="table table-striped table-bordered" style="width:auto;">
		<thead>
			<tr>
				<th nowrap="nowrap">日期<?=($span=='weekly')?"(顯示週一)":""?></th>
				<th style="width:70px">總儲點</th>
				<th style="width:70px">iOS</th>
				<th style="width:70px">Android</th>
				<th style="width:70px">MyCard</th>
				<th style="width:70px">天天賺</th>
				<th style="width:70px">VIP</th>
				<th style="width:70px">儲值地區-台灣</th>
				<th style="width:70px">儲值地區-香港</th>
				<th style="width:70px">儲值地區-澳門</th>
				<th style="width:70px">儲值地區-新加坡</th>
				<th style="width:70px">儲值地區-馬來西亞</th>
				<th style="width:70px">儲值地區-其他</th>
			</tr>
		</thead>
		<tbody>
		<?
        $expected_date;
        $row_cnt = 0; 
        foreach($query->result() as $row):
            $row_cnt++;
            if (empty($span)){
                if ($row_cnt>1) {
                    for($next_date=strtotime((string)$row->date); $next_date<$expected_date; $expected_date=strtotime('-1 day', $expected_date)) {
                        $row_cnt++;
                    ?>
			<tr>			
				<td nowrap="nowrap"><?=date('Y-m-d', $expected_date)?></td>
				<td style="text-align:right">0</td>
				<td style="text-align:right">0</td>
				<td style="text-align:right">0</td>
				<td style="text-align:right">0</td>
				<td style="text-align:right">0</td>
				<td style="text-align:right">0</td>
				<td style="text-align:right">0</td>
				<td style="text-align:right">0</td>
				<td style="text-align:right">0</td>
				<td style="text-align:right">0</td>
				<td style="text-align:right">0</td>
			</tr>
                    <?
                    }
                }
                $expected_date = strtotime('-1 day', strtotime((string)$row->date));       
            }
        ?>
			<tr>			
				<td nowrap="nowrap"><?=($span=='weekly')?date('Y-m-d', strtotime(sprintf("%4dW%02d", (string)$row->year, (string)$row->date))):$row->date?></td>
				<td style="text-align:right"><?=number_format($row->sum)?></td>
				<td style="text-align:right"><?=number_format($row->ios_sum)?></td>
				<td style="text-align:right"><?=number_format($row->android_sum)?></td>
				<td style="text-align:right"><?=number_format($row->mycard_sum)?></td>
				<td style="text-align:right"><?=number_format($row->funapp_sum)?></td>
				<td style="text-align:right"><?=number_format($row->vip_sum)?></td>
				<td style="text-align:right"><?=number_format($row->twn_sum)?></td>
				<td style="text-align:right"><?=number_format($row->hkg_sum)?></td>
				<td style="text-align:right"><?=number_format($row->mac_sum)?></td>
				<td style="text-align:right"><?=number_format($row->sgp_sum)?></td>
				<td style="text-align:right"><?=number_format($row->mys_sum)?></td>
				<td style="text-align:right"><?=number_format($row->other_country_sum)?></td>
			</tr>
		<? 
        endforeach;?>
		</tbody>
	</table>
	<? endif;?>
<? endif;?>
		
</form>