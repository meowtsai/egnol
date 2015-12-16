<?php 
	$ad_channels = $this->config->item('ad_channels');
	$extra_ad_channels = $this->config->item('extra_ad_channels');
	
	if (array_key_exists($this->game_id, $extra_ad_channels)) {
		$ad_channels = array_merge($ad_channels, $extra_ad_channels[$this->game_id]);
	}
?>

<form method="post" action="<?=site_url("character/xf_ad")?>" class="form-search">
	
	<div class="control-group">
		廣告通路 
		<select name="ad_channel">
			<option value="">--</option>		
						
			<? foreach($ad_channels as $key => $channel):?>
			<option value="<?=$key?>" <?=($this->input->post("ad_channel")==$key ? 'selected="selected"' : '')?>><?=$channel?></option>
			<? endforeach;?>
		</select>
		
		<span class="sptl"></span>
		
		建檔時間
		<input type="text" name="startDate" value="<?=$this->input->post("startDate")?>" style="width:120px"> 至
		<input type="text" name="endDate" value="<?=$this->input->post("endDate")?>" style="width:120px">
		
		
		<input type="submit" class="btn btn-small btn-inverse" name="action" value="查詢" onclick="$.blockUI.wait()">		
	</div>
		

</form>

<? if ($result):?>

<table class="table table-striped" style="width:200px">
	<thead>
		<tr>
			<td style="width:400px">總數</td> 	
		</tr>
	</thead>
	<tbody>
		<tr>
			<td style="text-align:center"><?=$result?></td>
		</tr>
	</tbody>
</table>

<? endif;?>