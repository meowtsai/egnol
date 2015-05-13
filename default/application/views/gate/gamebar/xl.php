<style>
div.menu a {float:left;font-size:0;}
#marquee_block ul {width:318px; margin:3px 5px;  font-size:13px; height:18px; line-height:18px; color:#333;}
#marquee_block ul li {}
#marquee_block ul a {color:#333; text-decoration:none;}

.link a {margin:0 4px;}
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
		});
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
<div style="background:url(/p/img/gamebar/xl/bg02.png) 0 -6px; height:35px; ">
	<span style="display:inline-block; width:44px; height:41px; margin:-6px -12px 0 0 ; background:url('/p/img/gamebar/xl/bg03.png') #000; float:right;"></span>
	<span style="display:inline-block; width:44px; height:41px; margin:-6px  0 0 -20px; background:url('/p/img/gamebar/xl/bg01.png') #000; float:left;"></span>
		
	<div style="width:960px; margin:0 auto; height:35px; position:relative; " >
		

		
		<div style="position:absolute; left:0px; top:9px;" class="link">		
			<a href="http://www.long_e.com.tw/payment?site=xl" target="_blank"><img src="/p/img/gamebar/xl/b5_off.png" class="change"></a>			
			<img src="/p/img/gamebar/xl/line.png">			
			<a href="http://www.long_e.com.tw/service" target="_blank"><img src="/p/img/gamebar/xl/b4_off.png" class="change"></a>
		</div>
		
		<div style="position:absolute; top:-4px; left:205px">
			<a href="http://xl.long_e.com.tw/index.php" target="_blank">
				<img src="/p/img/gamebar/xl/logo_off.png" class="change" height="41">
			</a>
		</div>
		
		<!-- new -->
		<div style="position:absolute; top:7px; left:325px; width:329px; height:24px; background:url('/p/img/gamebar/xl/notice.png')">
			<div id="marquee_block"></div>
		</div>
		
		<div style="position:absolute; right:0px; top:9px;" class="link">		
			<a href="https://www.facebook.com/long_e.xl" target="_blank"><img src="/p/img/gamebar/xl/b3_off.png" class="change"></a>			
			<img src="/p/img/gamebar/xl/line.png">			
			<a href="http://xl.long_e.com.tw/guide/get_list/213" target="_blank"><img src="/p/img/gamebar/xl/b2_off.png" class="change"></a>
			<img src="/p/img/gamebar/xl/line.png">			
			<a href="http://xl.long_e.com.tw/guide/get_list/218l" target="_blank"><img src="/p/img/gamebar/xl/b1_off.png" class="change"></a>
		</div>
		
		
	</div>
</div>

