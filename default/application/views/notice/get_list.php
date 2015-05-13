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

<ul class="newlist">
	<? if ($query->num_rows() == 0):?>
	<li style="padding:10px; color:#888">暫無通知</li>
	<? else:?>
	
	<? foreach($query->result() as $row):?>
	<li>
		<span style="float:right; display:inline-block; width:130px; text-align:center; color:#444;">
			<?=date("Y-m-d", strtotime($row->create_time))?>
		</span>
		<? if ($row->url || $row->content):?>
		<a href="<?=site_url("notice/detail/{$row->notice_id}")?>" target="_blank" style="font-weight: <?=$row->read==0?'bold':'normal'?>">
		<? endif;?>
			<?=$row->title?>
		<? if ($row->url || $row->content):?></a><? endif;?>
	</li>
	<? endforeach;?>
	
	<? endif;?>
</ul>
</div>

<div id="pagination">
	<?=$this->pagination->create_links();?>
</div>

