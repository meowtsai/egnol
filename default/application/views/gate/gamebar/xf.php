<style>
div.menu a {float:left;font-size:0;}
#marquee_block ul {width:308px; margin:23px 0 0 90px; font-size:13px; height:18px; line-height:18px; color:#ddd;}
#marquee_block ul a {color:#fff; text-decoration:none;}
</style>
<script>
$(function(){
	do_poll();
});

function do_poll()
{
	$.get("http://www.long_e.com.tw/ajax/get_realtime_bulletin/<?=$server->game_id?>/<?=$frame_conf['realtime_bulletin_id']?>/0/<?=$server->id?>", function(data) {		
		var items = '<ul class="marquee">';
		$(data).each(function(i,o) {
			items += '<li>'+o.title+'</li>';
		})
		$("#marquee_block").html(items + '</ul>');
		$("#marquee_block ul").marquee({
			 yScroll: "top",
			 pauseSpeed: 180000,
			 scrollSpeed: 26
		});	
		setTimeout(do_poll, 900000);
	}, 'json');	
}
</script>
<div style="">
	<div style="width:960px; margin:0 auto; height:35px; position:relative; background:url('/p/img/gamebar/xf/ingamebar_xf_character.png') no-repeat top right;" >
		
		<!-- logo -->
		<div style="position:absolute;"><a href="http://xf.long_e.com.tw/index.php" target="_blank"><img src="/p/img/gamebar/xf/ingamebar_xf_logo_01.png" style="height:35px;"></a></div>
		
		<!-- new -->
		<div style="position:absolute; top:-14px; left:85px; width:417px; height:50px; background:url('/p/img/gamebar/xf/ingamebar_xf_news_02.png')">
			<div id="marquee_block"></div>
		</div>
		
		<!-- menu -->
		<div class="menu" style="position:absolute; left:500px; top:-21px;">
			<? if ($is_minik_user == false):?>
			<a href="https://www.facebook.com/long_e.xf" target="_blank"><img src="/p/img/gamebar/xf/ingamebar_xf_off_04.png" class="change" style="height:56px;"></a>
			<a href="http://forum.gamer.com.tw/A.php?bsn=23269" target="_blank"><img src="/p/img/gamebar/xf/ingamebar_xf_off_05.png" class="change" style="height:56px;"></a>
			<? endif;?>
			<a href="http://xf.long_e.com.tw/guide/detail/82" target="_blank"><img src="/p/img/gamebar/xf/ingamebar_xf_off_06.png" class="change" style="height:56px;"></a>
			<a href="http://www.long_e.com.tw/service" target="_blank"><img src="/p/img/gamebar/xf/ingamebar_xf_off_07.png" class="change" style="height:56px;"></a>
			<a href="http://www.long_e.com.tw/payment?site=xf&account=<?=urlencode($this->g_user->account)?>" target="_blank"><img src="/p/img/gamebar/xf/ingamebar_xf_off_08.png" class="change" style="height:56px;"></a>
		</div>
		
	</div>
	
</div>

