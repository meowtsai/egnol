<style type="text/css">
body {margin:0; padding:0; overflow:auto; font-family:微軟正黑體,新細明體,Arial;}
ul, li {margin:0; padding:0px;}
li {list-style:none;}
img {border:0}
a {
	outline: none; /* for Firefox */
	hlbr:expression(this.onFocus=this.blur()); /* for IE */
}

#skybar {position:relative; width:100%; left:0px; top:0px; z-index:999; height:45px; background:url(http://www.long_e.com.tw/p/img/top_bar_bk.jpg) #fff; line-height:18px;}
#skybar a {color:#666;}
#sb_topbar {width:1000px; margin:0 auto; position:relative;}
#sb_logo {width:145px; height:42px; float:left; display:inline-block; position:relative; z-index:99991}
#sb_banner {height:42px; float:left; overflow:hidden;}
#sb_L_banner {position:absolute; top:42px; left:0px; z-index:99993}
#sb_select {width:300px; height:42px; float:right; position:relative;}
#sb_select .item {width:70px;float:left; font-size:12px; text-align:center; height:42px; line-height:42px; display:inline-block;}
#sb_select .item a {font-size:12px;}
#sb_select .game_center {background:url(http://www.long_e.com.tw/top/images/center_bk.jpg) no-repeat 0 11px;  padding-right:10px;}
#sb_select .game_center a {margin-left:-5px;}

#sb_game_list {width:708px; position:absolute; top:42px; right:0; z-index:99992}
#game_list {background:url('http://www.long_e.com.tw/top/images/list_mid.png');}

</style>
<div id="sb_topbar">
	<span id="sb_logo"><a href="http://www.long_e.com.tw/" target="_blank"><img src="http://www.long_e.com.tw/top/images/logo.jpg"/></a></span>
	<? if ($skybar_banner):?>
	<span class="sb_banner" style="position:relative; display:inline-block;">			
		<img src="<?=$skybar_banner->p1_src?>">
		<span class="sb_banner2" style="position:absolute; left:-150px; top:0px; display:none;">
			<a href="<?=$skybar_banner->link?>" target="_blank"><img src="<?=$skybar_banner->p2_src?>" style="max-width:1024px;"></a>
		</span>
	</span>
	<? endif;?>
    <div id="sb_select">
    	<ul>
        	<li class="item"><a href="http://www.long_e.com.tw/payment">儲值中心</a> │</li>
            <li class="item"><a href="http://www.long_e.com.tw/member/update_member_data">會員中心</a> │</li>
            <li class="item"><a href="http://www.long_e.com.tw/service">客服中心</a> │</li>
            <li class="item game_center"><a href="javascript:;">遊戲中心</a></li>
        </ul>
    </div>
	<div id="sb_game_list" style="display:none;">  
		<img src="http://www.long_e.com.tw/top/images/list_top.png" /><? $this->load->view("platform/_game_list", array("game_a"=>$game_a, "game_b"=>$game_b, "game_c"=>$game_c, "game_d"=>$game_d, "game_e"=>$game_e));?>
 		<img src="http://www.long_e.com.tw/top/images/list_foot.png" />
	</div>      

</div>

<script type="text/javascript">

$(function(){
	$('#sb_topbar .sb_banner').mouseenter(function(){$('.sb_banner2', this).show(); $('#sb_game_list').hide();});
	$('#sb_topbar .sb_banner').mouseleave(function(){$('.sb_banner2', this).hide();});
	$('#sb_topbar .game_center').mouseenter(function() {
		$('#sb_game_list').slideDown(150);	
	});	
	$('#sb_game_list').mouseleave(function(){
		$('#sb_game_list').hide();
	});	
});

</script>