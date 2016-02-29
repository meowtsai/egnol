<? 
	$game_events = $this->config->item("game_events");
?>
<form method="get" action="<?=site_url("log/game_events")?>" class="form-search">
	<input type="hidden" name="game_id" value="<?=$this->game_id?>">
	
		<div class="control-group">
		事件
		<select name="game_event" style="width:90px">
			<option value="">--</option>
			<? foreach($game_events as $key => $arr):?>
			<option value="<?=$key?>" <?=($this->input->get("game_event")==$key ? 'selected="selected"' : '')?>><?=$arr['name']?></option>
			<? endforeach;?>
		</select>	
	
	<? if ($this->game_id == false):?>
		
		遊戲
		<select name="game" style="width:90px">
			<option value="">--</option>
			<? foreach($games->result() as $row):?>
			<option value="<?=$row->game_id?>" <?=($this->input->get("game")==$row->game_id ? 'selected="selected"' : '')?>><?=$row->name?></option>
			<? endforeach;?>
		</select>	
		
	<? else:?>
	
		<select name="game" style="display:none;">
			<option value="<?=$this->game_id?>" selected="selected"></option>
		</select>
					
	<? endif;?>	
	
		<!--伺服器
		<select name="server" style="width:90px;">
			<option value="">--</option>
		</select>
		
		<select id="server_pool" style="display:none;">
			<? foreach($servers->result() as $row):?>
			<option value="<?=$row->id?>" <?=($this->input->get("server")==$row->id ? 'selected="selected"' : '')?> class="<?=$row->game_id?>"><?=$row->name?></option>
			<? endforeach;?>
		</select>	
		
		<span class="sptl"></span>	-->
							
		時間
		<input type="text" name="start_date" value="<?=$this->input->get("start_date")?>" style="width:120px"> 至
		<input type="text" name="end_date" value="<?=$this->input->get("end_date")?>" style="width:120px" placeholder="現在">
		<a href="javascript:;" class="clear_date"><i class="icon-remove-circle" title="清除"></i></a>		
	
	</div>
	
	<div class="control-group">	

		<input type="submit" class="btn btn-small btn-inverse" name="action" value="查詢">
			
		<? if ($this->input->get("use_default") == false):?>
		<span class="sptl"></span>	
		<a href="?game_id=<?=$this->game_id?>" class="btn btn-small"><i class="icon-remove"></i> 重置條件</a>
		<? endif;?>
		
	</div>
	
</form>


<? if ( ! empty($query)):?>
	<? if (count($query) == 0): echo '<div class="none">查無資料</div>'; else: ?>
	
<div class="msg">總筆數:<?=count($query)?></div>
<?=tran_pagination($this->pagination->create_links());?>

<table class="table table-striped table-bordered">
	<thead>
		<tr>	
			<? foreach($game_events[$this->input->get("game_event")]['fields'] as $key => $val):?>
			<th style="width:65px;"><?=$val?></th>
			<? endforeach;?>				
		</tr>
	</thead>
	<tbody>
		<? foreach($query as $row): ?>
		<tr>
			<? foreach($game_events[$this->input->get("game_event")]['fields'] as $key => $val):?>
			<td><?=(strpos($key, '_time'))?date("Y-m-d h:i:sa", $row->$key):$row->$key?></td>
			<? endforeach;?>
		</tr>
		<? endforeach;?>
	</tbody>
</table>
	<? endif;?>
<? endif;?>
