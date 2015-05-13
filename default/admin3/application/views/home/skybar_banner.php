<style type="text/css">
#skybar_banner {}
.sb_banner2 {position:absolute; left:-150px; top:0px; display:none;}
</style>

<div id="skybar_banner">

<div id="func_bar">	
	<a class="btn btn-primary" href="<?=site_url("home/modify_skybar_banner")?>">新增</a>
</div>

	<? foreach($query->result() as $row):?>
	<p>
		<span style="display:inline-block; position:relative; width:360px;">
			<span class="sb_banner">			
				<img src="<?=$row->p1_src?>">
				<span class="sb_banner2">
					<a href="<?=$row->link?>" target="_blank"><img src="<?=$row->p2_src?>" style="max-width:1024px;"></a>
				</span>
			</span>
			<span style="display:inline-block; margin-left:12px;">
				<? if ($row->enable == '1'):?>
				<span style="color:#080">v開啟</span>
				<? else:?>
				<span style="color:#888">x關閉</span>
				<? endif;?>
			</span>
		</span>
		<span style="position:relative;">		
			<a href="<?=site_url("home/modify_skybar_banner/{$row->id}")?>" class="btn">修改</a>
			<a href="javascript:;" class="json_del btn" url="<?=site_url("home/delete_skybar_banner/{$row->id}")?>"><i class="icon icon-remove"></i>  刪除</a>
		</span>
	</p>
	<? endforeach;?>

</div>

<script type="text/javascript">

$(function(){
	$('.sb_banner').on("mouseenter", function(){$('.sb_banner2', this).show();});
	$('.sb_banner').on("mouseleave", function(){$('.sb_banner2', this).hide();});
});

</script>