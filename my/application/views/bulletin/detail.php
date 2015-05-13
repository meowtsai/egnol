	<div id="news">
		<div class="hd" >
		</div>
		<div class="bd" >
			<div style="font-size:18px; color:#222; font-weight:bold;">
				<?=$row->bulletin_title?>
				<span style="font-size:13px; color:#191;"><?=date("Y-m-d", strtotime($row->publish_date))?></span>
			</div>
			<div class="fixck">
				<?=$row->bulletin_content?>
			</div>
		</div>
		<div class="ft">
		</div>
	</div>
	

