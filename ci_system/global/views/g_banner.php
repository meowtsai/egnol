<!--slider -->
<div class="game-sliderout">
<ul class="game-slider">
<?

	foreach($main_banners->result() as $banner)
	{
		echo "<li><a href='{$banner->link}' target='_blank'><img src='{$banner->src}' title='{$banner->title}' /></a></li>";
	}
	
?>
</ul>
</div>
