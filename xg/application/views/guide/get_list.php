<div class="list">
<ul>
	<? foreach($query->result() as $row):?>
	<li><a href="<?=site_url("guide/detail/{$row->id}")?>"><?=$row->guide_title?></a></li>
	<? endforeach;?>
</ul>

<?=$this->pagination->create_links();?>
</div>