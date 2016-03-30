<!--slider -->
<div class="game-sliderout">
<ul class="game-slider">
<?

	foreach($game_events as $event)
	{
		$img = sprintf("/p/image/banner/%1$04d.jpg", $event->id);
		if(file_exists($_SERVER['DOCUMENT_ROOT'] . $img))
		{
			echo "<li><a href='{$event->url}' target='_blank'><img src='{$img}' title='{$event->event_name}' /></a></li>";
		}
	}
	
?>
</ul>
</div>
