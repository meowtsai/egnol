<?php 
	$gash_conf = $this->config->item("gash");
	$channels = $this->config->item('channels');
?>

<ul class="pager">
  <li class="previous">
    <a href="<?=site_url("ad/manage")?>"><i class=" icon-arrow-left"></i> 返回</a>
  </li>
</ul>

<form method="get" action="<?=site_url("ad/statistics")?>" class="form-search">

	<div class="control-group">
	
		<input type="text" name="ad_channel" value="<?=$this->input->get("ad_channel")?>" style="width:90px;" placeholder="廣告參數">
	
		<span class="sptl"></span>
	
		轉點時間
		<input type="text" name="start_date" value="<?=$this->input->get("start_date")?>" style="width:120px"> 至
		<input type="text" name="end_date" value="<?=$this->input->get("end_date")?>" style="width:120px" placeholder="現在">
		<a href="javascript:;" class="clear_date"><i class="icon-remove-circle" title="清除"></i></a>
		
	</div>
	
	<div class="control-group">		
		
		<input type="submit" class="btn btn-small btn-inverse" name="action" value="統計">
		
		<span class="sptl"></span>
		
		<select name="time_unit" style="width:80px">
			<option value="hour" <?=($this->input->get("time_unit")=='hour' ? 'selected="selected"' : '')?>>時</option>
			<option value="day" <?=($this->input->get("time_unit")=='day' ? 'selected="selected"' : '')?>>日</option>
			<option value="month" <?=($this->input->get("time_unit")=='month' ? 'selected="selected"' : '')?>>月</option>
			<option value="year" <?=($this->input->get("time_unit")=='year' ? 'selected="selected"' : '')?>>年</option>
		</select>		
		
		<input type="submit" class="btn btn-small btn-inverse" name="action" value="時段統計">
				
		<? if ($this->input->get("use_default") == false):?>
		<span class="sptl"></span>	
		<a href="?" class="btn btn-small"><i class="icon-remove"></i> 清除條件</a>
		<? endif;?>
		
	</div>
		
</form>

<? if ( ! empty($query)):?>
	<? if ($query->num_rows() == 0): echo '<div class="none">查無資料</div>'; else: 
	?>
	
		<? 
		switch ($this->input->get("action")) 
		{
			case "統計":
				
				$field = array('廣告');
				$table = array();
				
									
				$field["a"] = "瀏覽數";
				$field["b"] = "安裝數";
				$field["c"] = "創角數";
					
				foreach($query->result() as $row) {	
					$table[$row->title]["a"] = $row->cnt;		
					$table[$row->title]["b"] = $row->cnt2;				
				}			

				foreach($query2->result() as $row) {
					if (empty($row->title)) continue;
					$table[$row->title]["c"] = $row->cnt;					
				}							
					
				echo output_statistics_table($field, $table, false, false, true);
				
				break;		
							
			case "時段統計":
				
				$field = array('時段');
				$table = array();
				
									
				$field["a"] = "瀏覽數";
				$field["b"] = "安裝數";
				$field["c"] = "創角數";
					
				foreach($query->result() as $row) {	
					$table[$row->title]["a"] = $row->cnt;		
					$table[$row->title]["b"] = $row->cnt2;				
				}			

				foreach($query2->result() as $row) {
					if (empty($row->title)) continue;
					$table[$row->title]["c"] = $row->cnt;					
				}							
					 
				echo output_statistics_table($field, $table, false, false, true);
				
				break;		
			} 			
		?>
	<? endif;?>
<? endif;?>

