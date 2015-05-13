
<div style="font-size:16px; font-weight:bold; ">
	<?=$row->title?>			
</div>

<div style="font-size:14px;"><?=date("Y-m-d", strtotime($row->create_time))?></div>

<div class="txt" style="padding-top:22px; min-height:260px; position:relative;">	
	<div class="fixck">
		<?=$row->content?>
	</div>
</div>
