<div id="news">
	<div class="hd" id="bulletin_menu"></div>
	<div class="bd">
	
<div class="list">
<ul>
	<? foreach($query->result() as $row):?>
	<li style="margin:5px 0; font-size:14px;">
		<span><?=date("Y-m-d", strtotime($row->publish_date))?></span>
		<a href="<?=site_url("bulletin/detail/{$row->id}")?>">
			【<?=$row->category?>】
			<?=$row->bulletin_title?></a>
	</li>
	<? endforeach;?>
</ul>
</div>

	</div>	
	<div class="ft">
		<?=$this->pagination->create_links();?>
	</div>
</div>

