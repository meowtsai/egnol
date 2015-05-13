<style>
div.menu a {float:left;font-size:0;}
#marquee_block ul {width:318px; margin:3px 5px;  font-size:13px; height:18px; line-height:18px; color:#333;}
#marquee_block ul li {}
#marquee_block ul a {color:#333; text-decoration:none;}

#info_block ul {height:20px; line-height:17px; width:166px; font-size:14px;}
#info_block ul li {text-align:right; width:166px; }

.link a {margin:0 4px;}
.btn {display:inline-block; border:1px solid #995; background:#dd0;  text-decoration:none; font-size:12px;}
.btn:hover {background:#ff0;}
.btn .line {display:inline-block; border:1px solid #eeb; border-width:1px 0 0 0; padding:0 2px;}
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
<div style="background:url(/p/img/gamebar/gt/bg02.png) 0 -6px; height:35px; ">
	<span style="display:inline-block; width:44px; height:41px; margin:-6px -12px 0 0 ; background:url('/p/img/gamebar/gt/bg03.png') #000; float:right;"></span>
	<span style="display:inline-block; width:44px; height:41px; margin:-6px  0 0 -20px; background:url('/p/img/gamebar/gt/bg01.png') #000; float:left;"></span>
		
	<div style="width:960px; margin:0 auto; height:35px; position:relative; " >
		
		<div style="position:absolute; top:-7px; left:0px">
			<a href="http://gt.long_e.com.tw/index.php" target="_blank">
				<img src="/p/img/gamebar/gt/logo_off.png" class="change">
			</a>
		</div>
		
		<!-- new -->
		<div style="position:absolute; top:7px; left:110px; width:329px; height:24px; background:url('/p/img/gamebar/gt/notice.png')">
			<div id="marquee_block"></div>
		</div>
		
		<div style="position:absolute; right:174px; top:9px;" class="link">	
			<a href="http://gt.long_e.com.tw/guide/get_list/294" target="_blank"><img src="/p/img/gamebar/gt/b1_off.png" class="change" style="height:17px;"></a>			
			<img src="/p/img/gamebar/gt/line.png">		
			<a href="https://www.facebook.com/long_e.inc" target="_blank"><img src="/p/img/gamebar/gt/b3_off.png" class="change" style="height:17px;"></a>			
			<img src="/p/img/gamebar/gt/line.png">			
			<a href="http://www.long_e.com.tw/payment?site=gt" target="_blank"><img src="/p/img/gamebar/gt/b5_off.png" class="change" style="height:17px;"></a>			
			<img src="/p/img/gamebar/gt/line.png">			
			<a href="http://www.long_e.com.tw/service" target="_blank"><img src="/p/img/gamebar/gt/b4_off.png" class="change" style="height:17px;"></a>
		</div>
		<div style="position:absolute; right:20px; top:3px; padding:5px;">
			<div id="info_block" class="marquee">
				<ul>
					<li>
						龍邑《<?=$server->name?>》
					</li>
					<li>
						<span title="<?=$this->g_user->account?>">歡迎 <?=mb_strimwidth($this->g_user->account, 0, 14, '...', 'UTF-8');?></span>
						<? if ($this->g_user->check_extra_account($this->g_user->account)):?>
						<!-- 
						<a class="btn" href="http://www.long_e.com.tw/member/bind_account?redirect_url=http://gt.long_e.com.tw" target="_blank" title="綁定帳號">
							<span class="line">綁定</span>
						</a>
						 -->
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

