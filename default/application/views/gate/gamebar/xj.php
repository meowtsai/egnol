<style type="text/css">
a {color:#ddd; font-size:14px; text-decoration:none;}
a:hover {color:#fff; font-size:14px;}

#marquee_block {width:300px; display:inline-block; vertical-align:middle; margin-left:30px;}
#marquee_block ul {width:318px; margin:3px 5px;  font-size:13px; height:18px; line-height:18px; color:#aaa;}
#marquee_block ul li {}
#marquee_block ul a {text-decoration:none;}
</style>
<script type="text/javascript">

$(function(){
	$("#info_block ul").marquee({
		 yScroll: "top",
		 pauseSpeed: 5000,
		 scrollSpeed: 26,
		 pauseOnHover: false
	});		
	<? //if ($server->id == 160): ?>
	do_poll();
	<? //endif;?>
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

function AddFavorite(sURL, sTitle) {
    try {
	if('' == sURL){sURL = self.location}
        window.external.addFavorite(sURL, sTitle);
    } catch (e) {
        try {
            window.sidebar.addPanel(sTitle, sURL, "");
        } catch (e) {
            alert("加入收藏失敗，請使用Ctrl+D進行添加");
        }
    }
}

function Add2Favorite(sURL,sTitle)
{   try{
        if('' == sURL) {sURL = self.location}
        if (window.sidebar) {
            window.sidebar.addPanel(sTitle, sURL, "");
        } else if( document.all ) {
            window.external.AddFavorite(sURL, sTitle);
        }
    }
    catch(e){
        alert("加入收藏失敗，請使用Ctrl+D進行添加");
    }
}
</script>
<? if (strpos($this->g_user->account, "@artsy") !== false):?>
<div style="width:1024px; margin:0 auto; color:#777;">
	<div style="position:absolute; top:1px; width:700px; height:30px; background:url('http://x1.xj.longeplay.com.tw/images/bandeau_gene.jpg') 332px 0">
	</div>
</div>
<? else:?>
<div style="width:1024px; margin:0 auto; color:#777;">
	<div style="position:absolute; top:1px; width:700px; height:30px; background:url('http://x1.xj.longeplay.com.tw/images/bandeau_gene.jpg') 332px 0">
	
		<div style="padding:3px;">		
			<a href="http://xj.longeplay.com.tw" target="_blank">官網</a> ｜
			<a href="http://www.longeplay.com.tw/payment?site=xj" target="_blank">儲值</a> ｜  
			<a href="https://www.facebook.com/long_e.inc" target="_blank">粉絲團</a> ｜
			<a href="http://www.longeplay.com.tw/service" target="_blank">線上提問</a> ｜  
			<a href="javascript:Add2Favorite('http://xj.longeplay.com.tw/','2013年最具創意的即時卡通頁遊——《仙境幻想》')">加入最愛</a>
			
			<span id="marquee_block"></span>
		</div>
		
		<div style="position:absolute; right:0; top:2px; padding:5px;">
			<?=$this->g_user->display_notice()?>
		</div>		
		
	</div>
</div>
<? endif;?>