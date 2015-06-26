<style>
div.menu a {float:left;font-size:0;}
#marquee_block ul {width:318px; margin:1px 5px;  font-size:13px; height:22px; line-height:22px; color:#333;}
#marquee_block ul li {}
#marquee_block ul a {color:#333; text-decoration:none;}

#info_block ul {height:20px; line-height:20px; width:160px; font-size:14px;}
#info_block ul li {text-align:right; width:160px; }

.link a {margin:0 4px;}
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
	$.get("http://www.longeplay.com.tw/ajax/get_realtime_bulletin/<?=$server->game_id?>/<?=$frame_conf['realtime_bulletin_id']?>/0/<?=$server->id?>", function(data) {		
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
<div style="background:url(/p/img/gamebar/bg02.png) 0 -6px; height:35px; ">

	<span style="display:inline-block; width:44px; height:41px; margin:-6px -12px 0 0 ; background:url('/p/img/gamebar/bg03.png') #000; float:right;"></span>
	<span style="display:inline-block; width:44px; height:41px; margin:-6px  0 0 -20px; background:url('/p/img/gamebar/bg01.png') #000; float:left;"></span>
		
	<div style="width:960px; margin:0 auto; height:35px; position:relative; " >
		
		<div style="position:absolute; top:-3px; left:40px">
			<a href="http://www.longeplay.com.tw/gate/website/<?=$server->game_id?>" target="_blank">
				<img src="/p/img/gamebar/<?=$server->game_id?>/logo.png" >
			</a>
		</div>
		
		<!-- new -->
		<div style="position:absolute; top:7px; left:135px; width:329px; height:24px; background:url('/p/img/gamebar/notice.png')">
			<div id="marquee_block"></div>
		</div>
		
		<div style="position:absolute; right:190px; top:9px;" class="link">		
			<a href="https://www.facebook.com/long_e.inc" target="_blank"><img src="/p/img/gamebar/b3_off.png" class="change"></a>			
			<img src="/p/img/gamebar/line.png">			
			<a href="http://www.longeplay.com.tw/payment?game=<?=$server->game_id?>" target="_blank"><img src="/p/img/gamebar/b5_off.png" class="change"></a>			
			<img src="/p/img/gamebar/line.png">			
			<a href="http://www.longeplay.com.tw/service" target="_blank"><img src="/p/img/gamebar/b4_off.png" class="change"></a>
		</div>
		<div style="position:absolute; right:20px; top:2px; padding:5px;">
			<div id="info_block" class="marquee">
				<ul>
					<li>
						龍邑《<?=$server->name?>》
					</li>
					<li>
						<span title="<?=$this->g_user->account?>">歡迎 <?=mb_strimwidth($this->g_user->account, 0, 14, '...', 'UTF-8');?></span>
						<? if ($this->g_user->check_extra_account($this->g_user->account)):?>
						<!-- <a href="http://www.longeplay.com.tw/member/bind_account?redirect_url=http://<?=$server->game_id?>.longeplay.com.tw" target="_blank" title="綁定帳號">綁定</a> -->
						<? endif;?>
					</li>
				</ul>
			</div>
		</div>		
		<div style="position:absolute; right:0; top:2px; padding:5px;">
			<?=$this->g_user->display_notice()?>
		</div>		
	</div>
</div>

