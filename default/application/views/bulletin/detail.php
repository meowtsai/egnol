<div style="background:url('/p/img/items.png') 0 -590px; position:absolute; left:0; top:0; width:686px; line-height:36px; height:36px;">
	<div style="float:right; width:126px; font-size:14px;">分享文章
		<a href="javascript: void(window.open('http://www.facebook.com/share.php?u='.concat(encodeURIComponent(location.href)) ));"><img src="/p/img/icon/share_fb.png" style="vertical-align:middle"></a>
		<a href="javascript: void(window.open('http://www.plurk.com/?qualifier=shares&status=' .concat(encodeURIComponent(location.href)) .concat(' ') .concat('&#40;') .concat(encodeURIComponent(document.title)) .concat('&#41;')));"><img src="/p/img/icon/share_plurk.png" style="vertical-align:middle"></a>
		<a href="javascript: void(window.open('http://twitter.com/home/?status='.concat(encodeURIComponent(document.title)) .concat(' ') .concat(encodeURIComponent(location.href))));"><img src="/p/img/icon/share_twitter.png" style="vertical-align:middle"></a>
	</div>
	<div style="padding-left:80px;">
		<span style="font-size:16px; font-weight:bold; margin-right:10px; line-height:40px;" title="<?=$row->bulletin_title?>">
			<?=mb_strimwidth($row->bulletin_title, 0, 48, '...', 'utf-8')?>
			
		</span>
	</div>
</div>

<div class="txt" style="padding-top:42px; min-height:260px; position:relative;">
	<div style="font-size:14px; text-align:right; position:absolute; right:3px; top:26px;"><?=date("Y-m-d", strtotime($row->create_time))?></div>
	<div class="fixck">
	<?=$row->bulletin_content?>
	</div>
</div>

<div style="padding:20px; margin-top:20px;">
	<div class="fb-comments" data-href="<?='http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']?>" data-width="600" data-num-posts="5"></div>
</div>

<div style="">
	<? if ($pre_bulletin):?>
	<div style="border-bottom:1px dotted #999; padding:3px;"><a href="<?=site_url("bulletin/detail/{$pre_bulletin->id}")?>">上一則 | <?=mb_strimwidth($pre_bulletin->bulletin_title, 0, 66, '...', 'utf-8')?></a></div>
	<? endif;?>
	<? if ($next_bulletin):?>
	<div style="border-bottom:1px dotted #999; padding:3px;"><a href="<?=site_url("bulletin/detail/{$next_bulletin->id}")?>">下一則 | <?=mb_strimwidth($next_bulletin->bulletin_title, 0, 66, '...', 'utf-8')?></a></div>
	<? endif;?>
	<div style="margin-top:20px; text-align:center;"><a href="<?=site_url("bulletin/get_list")?>"><img src="/p/img/b01.png"></a></div>
</div>