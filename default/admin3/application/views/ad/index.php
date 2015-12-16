<?php 
	$ad_channels = $this->config->item('ad_channels');
?>

<form method="get" action="<?=site_url("ad")?>" class="form-search">
	
	<div class="control-group">
	
		廣告通路 
		<select name="ad_channel" class="span2">
			<option value="">--</option>		
						
			<? foreach($ad_channels as $key => $channel):?>
			<option value="<?=$key?>" <?=($this->input->get("ad_channel")==$key ? 'selected="selected"' : '')?>><?=$channel?></option>
			<? endforeach;?>
		</select>
		
		<span class="sptl"></span>
			
		遊戲
		<select name="game" class="span2">
			<option value="">--</option>
			<? foreach($games->result() as $row):?>
			<option value="<?=$row->game_id?>" <?=($this->input->get("game")==$row->game_id ? 'selected="selected"' : '')?>><?=$row->name?></option>
			<? endforeach;?>
		</select>	
					
	</div>
	
	<div class="control-group">
		
		建檔時間
		<input type="text" name="start_date" value="<?=$this->input->get("start_date")?>" style="width:120px"> 至
		<input type="text" name="end_date" value="<?=$this->input->get("end_date")?>" style="width:120px" placeholder="現在">
		<a href="javascript:;" class="clear_date"><i class="icon-remove-circle" title="清除"></i></a>

		<span class="sptl"></span>
		
		<input type="submit" class="btn btn-small btn-inverse" name="action" value="統計">		

	</div>
			
</form>

	
		<? 
		switch ($this->input->get("action")) 
		{
			case "統計":
		?>
		
<table class="table table-striped table-bordered" style="width:auto;">
	<tbody>
		<tr>
			<th>總點擊數</th>
			<td><?=$click_cnt?></td>
		</tr>
		<tr>
			<th>總用戶數</th>
			<td><?=$user_cnt?></td>
		</tr>
		<? if ($this->input->get("game")):?>
		<tr>
			<th>新用戶百分比</th>
			<? if ($user_cnt):?>
			<td><?=round($new_user_cnt/$user_cnt*100, 2)?>% (新<?=$new_user_cnt?>、舊<?=$old_user_cnt?>)</td>
			<? else:?>
			<td>0</td>
			<? endif;?>
		</tr>		
		<? endif;?>
	</tbody>
</table>
		
		<?
			break;
		} ?>