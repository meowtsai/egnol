<div id="game_list">		
	<div class="list">
		<ul>
			<li class="type">即時</li>
			<? $i=1; foreach($game_a->result() as $row):
				if ($i++%4==0) {echo '<li class="type">&nbsp;</li>'; $i++;}
			?>
				<li><?=output_game_item($row)?></li>
			<? endforeach;
				while ($i++%4<>0) echo '<li>&nbsp;</li>'; ?>
		</ul>
	</div>
	<div class="list">
		<ul>
			<li class="type">策略</li>
			<? $i=1; foreach($game_b->result() as $row):
				if ($i++%4==0) {echo '<li class="type">&nbsp;</li>'; $i++;}
			?>
				<li><?=output_game_item($row)?></li>
			<? endforeach;
				while ($i++%4<>0) echo '<li>&nbsp;</li>'; ?>
		</ul>
	</div>
	<div class="list">
		<ul>
			<li class="type">回合</li>
			<? $i=1; foreach($game_c->result() as $row):
				if ($i++%4==0) {echo '<li class="type">&nbsp;</li>'; $i++;}
			?>
				<li><?=output_game_item($row)?></li>
			<? endforeach;
				while ($i++%4<>0) echo '<li>&nbsp;</li>'; ?>
		</ul>
	</div>
	<? if ($game_d->num_rows() > 0):?>
	<div class="list">
		<ul>
			<li class="type">其它</li>
			<? $i=1; foreach($game_d->result() as $row):
				if ($i++%4==0) {echo '<li class="type">&nbsp;</li>'; $i++;}
			?>
				<li><?=output_game_item($row)?></li>
			<? endforeach;
				while ($i++%4<>0) echo '<li>&nbsp;</li>'; ?>
		</ul>
	</div>	
	<? endif;?>
	<? if (! empty($game_e) && $game_e->num_rows() > 0):?>
	<div class="list">
		<ul>
			<li class="type">手遊</li>
			<? $i=1; foreach($game_e->result() as $row):
				if ($i++%4==0) {echo '<li class="type">&nbsp;</li>'; $i++;}
			?>
				<li><?=output_game_item($row)?></li>
			<? endforeach;
				while ($i++%4<>0) echo '<li>&nbsp;</li>'; ?>
		</ul>
	</div>	
	<? endif;?>	
	<div style="clear:both;"></div>
</div>

<style type="text/css">
#game_list {margin:auto; width:706px;}
#game_list .index_items {background:url(http://www.long_e.com.tw/p/img/index_items.png); display:inline-block; width:12px; height:12px; vertical-align:middle; margin-left:3px;}
#game_list .title {margin-bottom:10px;}
#game_list .list {margin-right:0px; margin-left:2px; clear:both;}
#game_list .list li {background:#f6f6f6; margin:0 1px 1px; padding:5px; font-size:14px; line-height:21px; height:21px; float:left; width:174px; display:inline-block; text-align:left}
#game_list .list li.type {background:#c7c6c6; text-align:center; font-size:15px; font-weight:bold; color:#915454; width:134px; }
#game_list .list li a {font-size:14px;}

#game_list .icon_n_game {background-position:-16px -140px; }
#game_list .icon_b_game {background-position:0 -140px; }
#game_list .icon_h_game {background-position:-32px -140px; }
</style>

<? 
	function output_game_item($row)
	{
		if (file_exists(g_conf("http_document_root")."long_e/p/img/game/{$row->game_id}.gif")) {
			echo "<img src='http://www.long_e.com.tw/p/img/game/{$row->game_id}.gif' style='height:16px; vertical-align:middle; margin-right:3px;'>";
		}
		
		$output = "<a href='http://{$row->game_id}.long_e.com.tw' target='_blank'>{$row->name}</a>";
		$tags = explode(",", $row->tags);
		foreach($tags as $tags) {
			if ($tags == '最新') $output .= '<span class="index_items icon_n_game"></span>';
//			if ($tags == '測試') $output .= '<span class="index_items icon_b_game"></span>';
			if ($tags == '熱門') $output .= '<span class="index_items icon_h_game"></span>';
		}
		return $output;
	}
?>