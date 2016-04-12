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
	$.get("http://test-payment.longeplay.com.tw/ajax/get_realtime_bulletin/r2g/1/0/", function(data) {		
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
		
		
		<!-- new -->
		<div style="position:absolute; top:7px; left:135px; width:329px; height:24px; background:url('/p/img/gamebar/notice.png')">
			<div id="marquee_block"></div>
		</div>
			
	</div>
    <div style="position:absolute; right:20px; top:2px; padding:5px;">
        <div id="info_block" class="marquee">
        </div>
    </div>	
</div>
