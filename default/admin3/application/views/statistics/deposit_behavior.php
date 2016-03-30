<?php 
	$channels = $this->config->item('channels');
?>
<div id="func_bar">
	
</div>

<ul class="nav nav-tabs">
    <li class="">
        <a href="<?=site_url("statistics/revenue")?>">日報表</a>
    </li>
    <li class="">
        <a href="<?=site_url("statistics/revenue?span=weekly")?>">週報表</a>
    </li>
    <li class="">
        <a href="<?=site_url("statistics/revenue?span=monthly")?>">月報表</a>
    </li>
    <li class="active">
        <a href="<?=site_url("statistics/deposit_behavior")?>">儲值習慣</a>
    </li>
</ul>

<form method="get" action="<?=site_url("statistics/deposit_behavior")?>" class="form-search">
	<input type="hidden" name="span" value="<?=$this->input->get("span")?>">
		
	<div class="control-group">
		
		時間
		<input type="text" name="start_date" class="date required" value="<?=($this->input->get("start_date"))?$this->input->get("start_date"):date("Y-m-d",strtotime("-8 days"))?>" style="width:120px"> 至
		<input type="text" name="end_date" class="date" value="<?=$this->input->get("end_date")?>" style="width:120px" placeholder="現在">
		<a href="javascript:;" class="clear_date"><i class="icon-remove-circle" title="清除"></i></a>
		
		<input type="submit" class="btn btn-small btn-inverse" name="action" value="儲值習慣">	
	
	</div>
		
</form>

<h4>儲值額度區分</h4>
<div><img src="<?=base_url()?>/p/jpgraphs/deposit_amount_graph" alt=""></div>

<h4>儲值次數區分</h4>
<div><img src="<?=base_url()?>/p/jpgraphs/deposit_count_graph" alt=""></div>

<h4>付費帳號地區分析</h4>
<div><img src="<?=base_url()?>/p/jpgraphs/region_count_graph" alt=""></div>