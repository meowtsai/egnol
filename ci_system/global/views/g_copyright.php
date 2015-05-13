<style type="text/css">
#copyright ul, #copyright li {margin:0; padding:0px;}
#copyright li {list-style:none;}

#copyright {font-family:"微軟正黑體"; color:#666; font-size:13px; position:relative; clear:both;
	width:100%; height:209px; margin:0 auto; background:url(http://www.long_e.com.tw/p/img/copyright_bk.jpg) center top no-repeat;}
#copyright2 {width:500px; height:30px; margin:0 auto;  text-align:center;}
#copyright .cr_title {color:#333; font-size:14px; font-weight:bold;}
#copyright a {color:#666;}
#select {width:1000px; height:169px; margin:0 auto; position:relative;}

#game_select {background:url(http://www.long_e.com.tw/p/img/select_bk.png) no-repeat center top; position:absolute; width:100%; top:0px;
				display:none;} 
#game_select2 {background:#fff; min-height:130px; position:relative; z-index:100;}
#game_select .boundary { width:1000px; margin:0 auto;}
#copyright .box {width:25%; float:left;}
#copyright .box2 { padding:2px 10px 6px 20px; text-align:center;}
#copyright h3 {margin:5px 0 20px -99px; padding:0; color:#111;}
#copyright h4 {margin:5px 0 10px; padding:0; color:#333;}
#copyright li {float:left; width:132px; margin:4px 2px 3px 0;}
</style>

<script type="text/javascript"> 
$(function(){
	$('#copyright').on("mouseenter", "#long_e_game_center", function(){
		$('#game_select').show();
	})
	.on("mouseleave", "#game_select", function(){
		$('#game_select').hide();
	});
});
</script>

<div id="copyright">
            
        <div id="select">
        
		  <div class="box">
		  	<div class="box2">
				<h3><img src="http://www.long_e.com.tw/p/img/icon4.gif"/>儲值中心</h3>
	            <ul>
					<li><a href="http://www.long_e.com.tw/payment" target="_blank">儲值購買</a></li>
					<li><a href="http://www.long_e.com.tw/wallet/transfer" target="_blank">兌換遊戲幣</a></li>
					<li><a href="http://www.long_e.com.tw/member/payment_log" target="_blank">儲值歷程</a></li>
					<li><a href="http://www.long_e.com.tw/member/wallet_log" target="_blank">兌換歷程</a></li>
				</ul>
			</div>
		  </div>
		  
		  <div class="box">
		  	<div class="box2">
				<h3><img src="http://www.long_e.com.tw/p/img/icon4.gif" />客服中心</h3>
	            <ul>
					<li><a href="http://www.long_e.com.tw/service/question" target="_blank">線上提問</a></li>
					<li><a href="http://www.long_e.com.tw/service/listing" target="_blank">提問查詢</a></li>
					<li><a href="http://www.long_e.com.tw/service/download" target="_blank">表單下載</a></li>
					<li><a href="http://www.long_e.com.tw/platform/game_rule" target="_blank">遊戲規章</a></li>
				</ul>
			</div>
		  </div>
		  
		  <div class="box">
		  	<div class="box2">
				<h3><img src="http://www.long_e.com.tw/p/img/icon4.gif" />會員中心</h3>
	            <ul>
					<li><a href="http://www.long_e.com.tw/member/update_member_data" target="_blank">修改資料</a></li>
					<li><a href="http://www.long_e.com.tw/member/change_password" target="_blank">修改密碼</a></li>
					<li></li>
					<li></li>
				</ul>
			</div>
		  </div>
		  
		  <div class="box" id="long_e_game_center">
		  	<div class="box2">
				<h3><img src="http://www.long_e.com.tw/p/img/icon4.gif" />遊戲中心</h3>
	            <ul>
					<li>網頁遊戲</li>
					<li>手機遊戲</li>
				</ul>
			</div>
		  </div>
	
		</div>

		<div id="game_select">
			<div class="boundary">
				<div style="line-height:30px; height:30px; margin-left:813px; font-size:15px; font-weight:bold;">
					遊戲中心
				</div>
			</div>
		   <div id="game_select2">
              <div class="boundary">
              	<div style="padding:15px;">
              		<div style="width:70%; float:left; padding-left:5%">
              			<h4>網頁遊戲</h4>
              			<ul style="display:inline-block; border-right:1px solid #ccc;">
              			<? 
              				$game = $this->db->from("games")->not_like("tags", "手遊")->where("is_active", "1")->order_by("rank")->get();
              				foreach($game->result() as $row):
              			?>
              				<li><a href="http://<?=$row->game_id?>.long_e.com.tw" target="_blank"><?=$row->name?></a></li>
              			<? endforeach;?>                  			          				              		
              			</ul>
              		</div>
              		<div style="width:15%; float:right; padding-right:3%">
              			<h4>手機遊戲</h4>
              			<ul>
						<? 
              				$game = $this->db->from("games")->like("tags", "手遊")->where("is_active", "1")->order_by("rank")->get();
              				foreach($game->result() as $row):
              			?>
              				<li><a href="http://<?=$row->game_id?>.long_e.com.tw" target="_blank"><?=$row->name?></a></li>
              			<? endforeach;?>  
              			</ul>              			
              		</div>
              		<div style="clear:both;"></div>
                </div>
              </div>
            </div>
          </div>
    
   	<div class="cr_title" id="copyright2">龍邑科技 版權所有 2009 Cooz.com All Rights Reserved</div>
</div>