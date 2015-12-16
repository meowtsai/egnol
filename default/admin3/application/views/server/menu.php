<ul>
	<? foreach($this->config->item("servers") as $game_id => $server):?>
	<li><a href="<?=site_url("{$path}?game_id={$game_id}")?>"><?=$server['name']?></a></li>
	<? endforeach;?>
</ul>