<style>
body {background:#000; color:#eee; font-size:13px; line-height:23px;}
div.menu a {float:left;font-size:0;}
#marquee_block ul {width:308px; margin:20px 0 0 90px; font-size:13px; height:24px; line-height:26px; color:#ddd;}
#marquee_block ul a {color:#fff; text-decoration:none;}
#marquee_block ul a:hover {color:#5cd;}

#info_block ul {height:20px; line-height:20px; width:130px;}
#info_block ul li {text-align:right; width:130px; }

</style>
<script>
$(function(){
	$("#info_block ul").marquee({
		 yScroll: "top",
		 pauseSpeed: 5000,
		 scrollSpeed: 26,
		 pauseOnHover: false
	});		
		
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
	<div style="width:960px; margin:0 auto; height:30px; position:relative; " >
		
		<!-- logo -->
		<div style="position:absolute;"><a href="http://sl2.long_e.com.tw/index.php" target="_blank">
			<img src="/p/img/gamebar/sl2/logo.png" style="height:30px;"></a>
		</div>
		
		<!-- new -->
		<div style="position:absolute; top:-17px; left:85px; width:417px; height:50px; background:url('/p/img/gamebar/xf/ingamebar_xf_news_02.png')">
			<div id="marquee_block"></div>
		</div>
		
		<!-- menu -->
		<div class="menu" style="position:absolute; left:500px; top:-23px;">
			<? if ($is_minik_user == false):?>
			<a href="https://www.facebook.com/long_e.sl2" target="_blank"><img src="/p/img/gamebar/xf/ingamebar_xf_off_04.png" class="change" style="height:54px;"></a>
			<? endif;?>
			<a href="http://sl2.long_e.com.tw/guide/detail/61" target="_blank"><img src="/p/img/gamebar/xf/ingamebar_xf_off_06.png" class="change" style="height:54px;"></a>
			<a href="http://www.long_e.com.tw/service" target="_blank"><img src="/p/img/gamebar/xf/ingamebar_xf_off_07.png" class="change" style="height:54px;"></a>
			<a href="http://www.long_e.com.tw/payment?site=sl2&account=<?=urlencode($this->g_user->account)?>" target="_blank"><img src="/p/img/gamebar/xf/ingamebar_xf_off_08.png" class="change" style="height:54px;"></a>
			 
			
			<? if ($server->id == '--------backup-'):?> 
			<a href="http://sl2.long_e.com.tw/bulletin/detail/304" target="_blank"><img src="/p/img/gamebar/new_server.gif" style="height:26px; margin-top:27px"></a>
			<? endif;?>
		</div>
		
		<div style="position:absolute; right:0; padding:5px;">
			<div id="info_block" class="marquee">
				<ul>
					<li>
						龍邑《<?=$server->name?>》
					</li>
					<li>
						歡迎 <?=mb_strimwidth($this->g_user->account, 0, 16, '...', 'UTF-8');?>
					</li>
				</ul>
			</div>
		</div>
		
	</div>
	
</div>
