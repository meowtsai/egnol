	<div id="news">
		<div class="hd" >
		</div>
		<div class="bd" >
			<div class="title">
				<?=$row->bulletin_title?>
				<span class="date"><?=date("Y-m-d", strtotime($row->publish_date))?></span>
			</div>
			<div class="fixck">
				<?=$row->bulletin_content?>
			</div>
		</div>
		<div class="ft">
		</div>
	</div>
	

