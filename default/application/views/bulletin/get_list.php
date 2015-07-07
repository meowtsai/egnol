<style type="text/css">

#main .list {border-bottom:1px solid #222; padding-bottom:20px;}
#main .list ul {padding:12px;}
#main .list li {border-bottom:1px dotted #bbb; padding:3px 6px; line-height:21px;}
#main .list li a {text-decoration:none; color:#000;}
#main .list li a:hover {color:#921;}

#pagination {text-align:center; border-top:3px solid #333; margin-top:1px;}
#pagination a, #pagination strong {padding:3px;} 
</style>

<div class="list">
<img src="/p/img/p01.png">
<ul class="newlist">
	<? foreach($query->result() as $row):?>
	<li>
		<span style="float:right; display:inline-block; width:130px; text-align:center; color:#444;">
			<?=date("Y-m-d", strtotime($row->create_time))?>
		</span>
		<a href="<?=site_url("bulletin/detail/{$row->id}")?>">
			<?=mb_strimwidth($row->title, 0, 58, '...', 'utf-8')?></a>
	</li>
	<? endforeach;?>
</ul>
</div>

<div id="pagination">
	<?=$this->pagination->create_links();?>
</div>

