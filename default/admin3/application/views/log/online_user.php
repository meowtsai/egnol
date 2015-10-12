<?php 
	
?>

<form method="get" action="<?=site_url("log/online_user")?>" class="form-search">
	<input type="hidden" name="game_id" value="<?=$this->game_id?>">
	
		<div class="control-group">
	
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
	
		伺服器
		<select name="server" style="width:90px;">
			<option value="">--</option>
		</select>
		
		<select id="server_pool" style="display:none;">
			<? foreach($servers->result() as $row):?>
			<option value="<?=$row->id?>" <?=($this->input->get("server")==$row->id ? 'selected="selected"' : '')?> class="<?=$row->game_id?>"><?=$row->name?></option>
			<? endforeach;?>
		</select>	
					
	</div>	
		
	<div class="control-group">	

		<input type="submit" class="btn btn-small btn-inverse" name="action" value="查詢">	
		
		<span class="sptl"></span>	
							
		顯示
		<select name="display_game" style="width:120px">
			<option value="">--</option>
			<option value="game" <?=($this->input->get("display_game")=='game' ? 'selected="selected"' : '')?>>區分遊戲</option>
			<option value="server" <?=($this->input->get("display_game")=='server' ? 'selected="selected"' : '')?>>展開伺服器</option>
		</select>	
		
		<span class="sptl"></span>	
		
		<input type="submit" class="btn btn-small btn-inverse" name="action" value="人數統計">
				
		
		<? if ($this->input->get("use_default") == false):?>
		<span class="sptl"></span>	
		<a href="?game_id=<?=$this->game_id?>" class="btn btn-small"><i class="icon-remove"></i> 重置條件</a>
		<? endif;?>
		
	</div>

	<p class="text-info">
		<span class="label label-info">說明</span>
		資料為遊戲進行中有使用 遊戲資訊列 的玩家， 15分鐘內皆列為上線。
	</p>

	
</form>


<? if ( ! empty($query)):?>
	<? if ($query->num_rows() == 0): echo '<div class="none">查無資料</div>'; else: ?>
	
		<? 
		switch ($this->input->get("action")) 
		{
			case "查詢": ?>
				
<div class="msg">總筆數:<?=$total_rows?></div>
<?=tran_pagination($this->pagination->create_links());?>

<table class="table table-striped table-bordered">
	<thead>
		<tr>			
			<th style="width:70px;">uid
				<div style="color:#777;">euid</div></th>			
			<th style="width:60px;">遊戲</th>			
		</tr>
	</thead>
	<tbody>
		<? foreach($query->result() as $row): ?>
		<tr>
			<td><a href="<?=site_url("member/view/{$row->uid}")?>"><?=$row->uid?></a>
				<a href="<?=site_url("/log/game_login?uid={$row->uid}&action=查詢")?>"><i class="icon-search"></i></a>
			</td>
			<td><?=$row->game_name."_".$row->server_name?></td>
		</tr>
		<? endforeach;?>
	</tbody>
</table>

<?=tran_pagination($this->pagination->create_links());?>

		<? break;
					
		case "人數統計":
			
			$field = array('遊戲');
			$table = array();				
			
			foreach($query->result() as $row) 
			{			
				$title = empty($row->title) ? "無" : $row->title;
				$field["人數"] = '人數';
				$table[$title]["人數"] = $row->cnt;	
			}
			echo output_statistics_table($field, $table, true);
						
			break;	

		}
		?>
	<? endif;?>
<? endif;?>
