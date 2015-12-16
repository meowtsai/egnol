<form method="get" class="form-search" enctype="multipart/form-data">
	<input type="hidden" name="game_id" value="<?=$this->input->get("game_id")?>">
	
	<div class="control-group">	
		開始時間
		<input type="text" name="start_date" value="<?=$this->input->get("start_date")?>">
		<input type="submit" class="btn" value="確認送出">
		
		<h5><span class="label label-info">說明</span> 預設為最後創角記錄往回推半小時的緩衝時間</h5>		
	</div>
</form>

<? 
if ($query):
 
	$log_ids = '';
	foreach($query->result() as $row) {
		$log_ids .= "<li>".$row->id . "</li>";
	}
?>
<ul id="ids" style="display:none">
	<?=$log_ids?>
</ul>

<hr>

<div class="control-group">		
	<p>共 <?=$query->num_rows()?> 筆登入記錄未檢測是否創角</p>
	
	<span style="display:inline-block; width:500px; border:1px solid #888; height:20px;">
		<span style="display:inline-block; background:#d44; width:0px;" id="percent">&nbsp;</span>
	</span>
	<span id="current">0</span> / <span id="total"><?=$query->num_rows()?></span>
</div>

<input type="button" class="btn" value="開始" id="start">
<input type="button" class="btn" value="停止" id="stop">
<? endif;?>
	
		

