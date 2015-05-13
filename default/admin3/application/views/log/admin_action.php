<?php 
	$channels = $this->config->item('channels');	
	$ad_channels = $this->config->item('ad_channels');
	$extra_ad_channels = $this->config->item('extra_ad_channels');
	
	if ($this->game_id && array_key_exists($this->game_id, $extra_ad_channels)) {
		$ad_channels = array_merge($ad_channels, $extra_ad_channels[$this->game_id]);
	}
?>

<form method="get" action="<?=site_url("log/admin_action")?>" class="form-search">
	
	<div class="control-group">		
							
		記錄時間
		<input type="text" name="start_date" value="<?=$this->input->get("start_date")?>" style="width:120px"> 至
		<input type="text" name="end_date" value="<?=$this->input->get("end_date")?>" style="width:120px" placeholder="現在">
		<a href="javascript:;" class="clear_date"><i class="icon-remove-circle" title="清除"></i></a>
		
	</div>
	
	<div class="control-group">		
	
		<input type="text" name="ip" value="<?=$this->input->get("ip")?>" style="width:110px"  placeholder="IP">
		<input type="text" name="desc" value="<?=$this->input->get("desc")?>" style="width:110px"  placeholder="說明">				
	
		<input type="submit" class="btn btn-small btn-inverse" name="action" value="查詢">	
		
		<? if ($this->input->get("use_default") == false):?>
		<span class="sptl"></span>	
		<a href="?game_id=<?=$this->game_id?>" class="btn btn-small"><i class="icon-remove"></i> 清除條件</a>
		<? endif;?>		
	</div>
	
</form>


<? if ( ! empty($query)):?>
	<? if ($query->num_rows() == 0): echo '<div class="none">查無資料</div>'; else: ?>
	
		<? 
		switch ($this->input->get("action")) 
		{
			case "查詢": ?>
				
<span class="label label-warning">總筆數:<?=$total_rows?></span>
<?=tran_pagination($this->pagination->create_links());?>

<table class="table table-striped table-bordered">
	<thead>
		<tr>
			<th style="width:7%;">#</th>
			<th style="width:10%;">後台人員</th>
			<th style="width:11%;">IP位址</th>		
			<th style="width:17%;">建檔時間</th>
			<th style="width:11%;">function</th>
			<th style="width:10%;">action</th>
			<th style="width:35%;">說明</th>
		</tr>
	</thead>
	<tbody>
		<? foreach($query->result() as $row): ?>
		<tr>
			<td><?=$row->id?></td>
			<td><?=$row->name?></td>
			<td><?=$row->ip?></td>
			<td><?=$row->create_time?></td>
			<td><?=$row->function?></td>			
			<td><?=$row->action?></td>
			<td style="word-break:break-all"><?=$row->desc?></td>
		</tr>
		<? endforeach;?>
	</tbody>
</table>

<?=tran_pagination($this->pagination->create_links());?>

		<? break;
		
		case "通路統計":
			
			$field = array('通路');
			$table = array();				
			
			foreach($query->result() as $row) 
			{
				$field[$row->key] = $row->name;
				$row->title = empty($row->title) ? 'long_e' : substr($row->title, 1, 20);
				$title = isset($channels[$row->title]) ? $channels[$row->title] : $title;
				$table[$title][$row->key] = $row->cnt;					
			}
			echo output_statistics_table($field, $table, true);
			
			break;
			
		case "廣告統計":
			
			$field = array('廣告');
			$table = array();				
			
			foreach($query->result() as $row) 
			{			
				$title = empty($row->title) ? "無" : $row->title;
				$field[$row->key] = $row->name;
				$table[$title][$row->key] = $row->cnt;	
			}
			echo output_statistics_table($field, $table, true);
			
			break;			
			
		case "IP統計":
?>
	<p class="text-info">
		<span class="label label-info">說明</span>
		帳號登入同個伺服器只計1次
	</p>
<? 
			$field = array('IP');
			$table = array();				
			
			foreach($query->result() as $row) {							
				$field[$row->key] = $row->name;
				$table[$row->title][$row->key] = $row->cnt;					
			}
			echo output_statistics_table($field, $table, true);
			
			break;				
						
		case "時段統計":
			
			$field = array('時段');
			$table = array();
			
			foreach($query->result() as $row) {							
				$field[$row->key] = $row->name;
				$table[$row->title][$row->key] = $row->cnt;					
			}
			echo output_statistics_table($field, $table);
			
			break;	

		}
		?>
	<? endif;?>
<? endif;?>
