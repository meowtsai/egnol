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
<div style="background:url(/p/img/gamebar/bw/bg02.png) 0 -6px; height:35px; ">
	<span style="display:inline-block; width:44px; height:41px; margin:-6px -12px 0 0 ; background:url('/p/img/gamebar/bw/bg03.png') #000; float:right;"></span>
	<span style="display:inline-block; width:44px; height:41px; margin:-6px  0 0 -20px; background:url('/p/img/gamebar/bw/bg01.png') #000; float:left;"></span>
		
	<div style="width:960px; margin:0 auto; height:35px; position:relative; " >
		
		<div style="position:absolute; top:-2px; left:50px">
		</div>
		
		<!-- new -->
		<div style="position:absolute; top:7px; left:125px; width:329px; height:24px; background:url('/p/img/gamebar/bw/notice.png')">
			<div id="marquee_block"></div>
		</div>
		
		<div style="position:absolute; right:200px; top:9px;" class="link">		
			<a href="https://www.facebook.com/pages/%E9%9C%B8%E7%8E%8B%E7%B9%81%E9%AB%94%E4%B8%AD%E6%96%87%E7%B2%89%E7%B5%B2%E5%9C%98/589459527731871" target="_blank"><img src="/p/img/gamebar/bw/b3_off.png" class="change"></a>			
			<img src="/p/img/gamebar/bw/line.png">			
			<a href="http://www.long_e.com.tw/payment?game=bw" target="_blank"><img src="/p/img/gamebar/bw/b5_off.png" class="change"></a>			
			<img src="/p/img/gamebar/bw/line.png">			
			<a href="http://www.long_e.com.tw/gate/check_account_channel/service" target="_blank"><img src="/p/img/gamebar/bw/b4_off.png" class="change"></a>
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
						<!-- <a href="http://www.long_e.com.tw/member/bind_account?redirect_url=http://bw.long_e.com.tw" target="_blank" title="綁定帳號">綁定</a> -->
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

