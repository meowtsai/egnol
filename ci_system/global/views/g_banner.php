<!--slider -->
<div class="game-sliderout">
<ul class="game-slider">
<?

	foreach($banners->row() as $banner)
	{
		if(file_exists($banner->src))
		{
			echo "<li><a href='{$banner->link}' target='_blank'><img src='{$banner->src}' title='{$banner->title}' /></a></li>";
		}
	}
	
?>
</ul>
</div>
