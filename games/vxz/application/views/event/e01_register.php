<!-- 菜單 -->
<div class="header">
	<div class="nav">
		<ul class="menu">
			<li class="new"><a href="https://game.longeplay.com.tw/news?site=vxz">最新情報</a></li>
			<li class="center"><a href="https://game.longeplay.com.tw/payment?site=vxz">儲值中心</a></li>
			<li class="teach"><a href="#" onclick="javascript:alert('【敬請期待】');">儲值教學</a></li>
			<li class="fb"><a href="https://www.facebook.com/playvxz/?fref=ts" target="_blank">粉絲團</a></li>
			<li class="service"><a href="https://game.longeplay.com.tw/service?site=vxz">客服中心</a></li>
			<li class="download"><a href="#" onclick="javascript:alert('【敬請期待】');">下載遊戲</a></li>
				<div class="nav2" style="display:none">
					<a class="ios" href="#" onclick="javascript:alert('【敬請期待】');">APPSTORE</a>
					<a class="google" href="#" onclick="javascript:alert('【敬請期待】');">GOOGLEPLAY</a>
					<a class="apk" href="#" onclick="javascript:alert('【敬請期待】');">APK</a>
				</div>
			<li class="back"><a href="<?=base_url()?>">回官網</a></li>
		</ul>
	</div>
</div> 
<!-- 版頭 -->
<div class="wrapper">
	<div class="top">
		 <div class="logo">
			<div class="wow fadeInDown animated" style="visibility:visible">
			<a href="#"><img src="<?=base_url()?>p/img/event/logo.png"></a></div>

			<div class="mvbt">
			<a id="colorbox" class="youtube" href="https://www.youtube.com/embed/kzaiMQL0JjE?rel=0&amp;wmode=transparent">
			<img src="<?=base_url()?>p/img/event/play.png"></a>
			</div>

			<div class="mv">
			<div class="wow flipInY animated" style="visibility:visible;">              
			<img src="<?=base_url()?>p/img/event/mv.png"></div>
			</div>

			<div class="people1">
			<div class="wow bounceInLeft animated" data-wow-delay="0.4s" style="visibility:visible;-webkit-animation-delay:0.4s;-moz-animation-delay:0.4s;animation-delay:0.4s;">
			<img src="<?=base_url()?>p/img/event/lsh.png"></div>
			</div>

			<div class="people2">
			<div class="wow bounceInRight animated" data-wow-delay="0.4s" style="visibility:visible;-webkit-animation-delay:0.4s;-moz-animation-delay:0.4s;animation-delay:0.4s;">
			<img src="<?=base_url()?>p/img/event/sgjh.png"></div>
			</div>

			<div class="tt">
			<div class="wow bounceIn animated" data-wow-delay="0.5s" style="visibility:visible;-webkit-animation-delay:0.5s;-moz-animation-delay:0.5s;animation-delay:0.5s;">
			<img src="<?=base_url()?>p/img/event/text.png"></div>
			</div>                                      
		</div>
	</div>
</div>
<!-- 事前登陸頁面 -->
	<div class="section">
	    <div class="abgne_tab">

			<ul id="tabs">
				<li id="btn1" class="btn1_style"><a class="active"></a></li>
				<li id="btn2" class="btn2_style"><a></a></li>
			</ul>

			<div id="all_tab">
				<div id="btn_1" class="BOXS">

					<form id="earlylogin_form" method="post" action="<?=base_url()?>event/e01_register_json">
						<div class="fb-like">
							 <iframe src="https://www.facebook.com/plugins/like.php?href=https%3A%2F%2Fdevelopers.facebook.com%2Fdocs%2Fplugins%2Fhttps%3A%2F%2Fwww.facebook.com%2Fplayvxz%2F%3Ffref%3Dts&width=115&layout=button_count&action=like&size=large&show_faces=false&share=true&height=46&appId" width="115" height="46" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowTransparency="true"></iframe>
						</div>              
						<p><input type="text" id="email" name="email" class="required email t1" placeholder="請輸入E-mail" value="<?=$email?>"></p>
						<p><input type="text" id="mobile" name="mobile" class="required mobile isMobile t2" placeholder="請輸入手機號碼" value="<?=$mobile?>"></p>
						<p><input type="text" id="receive_code" name="receive_code" class="t3" value="<?=$receive_code?>"></p>
						<p>
							<input name="doLogin" type="submit" id="doSubmit" value="" class="button_submit" style="display:none;" />
							<div class="t4"><img src="<?=base_url()?>p/img/event/login.png" class="button_submit" onclick="javascript:$('#doSubmit').trigger('click')"></div>
						</p>
						<div class="display">
							<ul>
								<li class="t5">事前登錄序號<span class="t6"><?=$earlylogin_serial?></span></li>
								<li class="t7">情緣合擊序號<span class="t8"><?=$combo_serial?></span></li>
							</ul>
						</div>
					</form>
				</div>
				<div id="btn_2" class="BOXS">
					<ul>
						<li class="t9"><input type="text" id="website" style="width:480px;" value="<?=($share_code)?base_url()."event/e01_register?receive_code=".$share_code:"";?>" /></li>
					</ul>
					<p><div class="t10"><img data-copytarget="#website" onclick="javascript:alert('<?=($share_code)?"分享網址已加入剪貼簿":"請先完成預約登錄"?>')" style="cursor:copy;" src="<?=base_url()?>p/img/event/copy.png"></div></p>
				</div>
			</div>
	    </div>
    </div>
<!-- 活動注意事項 -->
	<div class="article">
	<div class="box1">
		<p>01. 事前登錄活動時間為<font color="c20b66">2016/11/7(一)下午14:00起</font>到雙平台CB不刪檔當天<font color="c20b66">晚上23:59</font>止。<br/>02. 所有活動序號兌換至<font color="c20b66">2016/12/31(六)晚上23:59止</font>。<br/>03. 本活動需輸入正確之E-MAIL或手機號碼，若為無法使用或空號將喪失獲獎資格。<br/>04. 一組E-MAIL及手機號碼僅可參加活動一次。<br/>
05. 每個遊戲帳號僅能兌換一次事前登錄獎勵。<br/>06. 活動序號不得轉換、轉讓及折換現金。<br/>07. 官方保留活動隨時修改條款的權利，如有任何爭議，皆以主辦單位之解釋及本活動的最終決策權為準。<br/>08. 如本活動因不可抗力之特殊原因無法執行時，主辦單位有權決定取消、終止、修改或暫停本活動。<br/>09. 本活動之所有物品均不得要求官方轉換、轉讓、折換現金或遊戲道具、貨幣。<br/>10. <font color="c20b66">事前登錄序號可於封測啟動後進入《小李飛刀》遊戲內兌換，請大俠自行保留序號。</font><br/>11. 情緣合擊分享碼相關資訊：<br/></p>
	  <p class="box2">(1) 此情緣合擊分享網址可分享給一位朋友，只要朋友點擊該網址並成功預約登錄，兩人便完成了情緣合擊，皆可獲得情緣合擊禮包序號。<br/>(2) 一人僅能領取一份情緣合擊分享禮包序號。<br/>
例：李尋歡第一次進行預約登錄後拿到情緣合擊分享碼，將分享碼分享給阿飛，阿飛在成功預約登錄後也可至情緣合擊分享碼裡拿到一組分享碼，阿飛再將自己的分享碼給林仙兒，林仙兒便也可透過阿飛的分享碼取得禮包序號。但阿飛只會擁有一組禮包序號，不會因為林仙兒成功輸入而多增加一份。</p>
		<p>12. 若有活動相關問題，請洽<a href="#">官網/客服中心</a>。</p>
<!---"mailto:信箱"--->
	 </div>
	</div>
	<div class="footer"></div>
<!-- GO TOP -->	
	<div id="gotop"><img src="<?=base_url()?>p/img/event/totop_01.png" width="163" height="170"></div>
<!-- JQ --> 
<script src="<?=base_url()?>p/js/event/jquery-1.12.3.min.js" type="text/javascript"></script>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
<script src="<?=base_url()?>p/js/event/wow.js"></script>
<script src="<?=base_url()?>p/js/event/action.js"></script>
<script src="<?=base_url()?>p/js/event/jquery.colorbox.js" type="text/javascript"></script>
<script src="<?=base_url()?>p/js/event/jquery.colorbox-min.js" type="text/javascript"></script>
<script src="<?=base_url()?>p/js/event/jquery.form.js" type="text/javascript"></script>
<script src="<?=base_url()?>p/js/event/jquery.validate.min.js" type="text/javascript"></script>
<script src="<?=base_url()?>p/js/event/default.js" type="text/javascript"></script>
<script src="<?=base_url()?>p/js/mobile_e01/validate.js" type="text/javascript"></script>
<script src="<?=base_url()?>p/js/event/clipboard.js" type="text/javascript"></script>
<script>
		$(document).ready(function(){
			//Examples of how to assign the Colorbox event to elements
			$(".group1").colorbox({rel:'group1'});
			$(".group2").colorbox({rel:'group2', transition:"fade"});
			$(".group3").colorbox({rel:'group3', transition:"none", width:"75%", height:"75%"});
			$(".group4").colorbox({rel:'group4', slideshow:true});
			$(".ajax").colorbox();
			$(".youtube").colorbox({iframe:true, innerWidth:960, innerHeight:720});
			$(".vimeo").colorbox({iframe:true, innerWidth:500, innerHeight:409});
			$(".iframe").colorbox({iframe:true, width:"80%", height:"80%"});
			$(".inline").colorbox({inline:true, width:"50%"});
			$(".callbacks").colorbox({
				onOpen:function(){ alert('onOpen: colorbox is about to open'); },
				onLoad:function(){ alert('onLoad: colorbox has started to load the targeted content'); },
				onComplete:function(){ alert('onComplete: colorbox has displayed the loaded content'); },
				onCleanup:function(){ alert('onCleanup: colorbox has begun the close process'); },
				onClosed:function(){ alert('onClosed: colorbox has completely closed'); }
			});

			$('.non-retina').colorbox({rel:'group5', transition:'none'})
			$('.retina').colorbox({rel:'group5', transition:'none', retinaImage:true, retinaUrl:true});

			//Example of preserving a JavaScript event for inline calls.
			$("#click").click(function(){ 
				$('#click').css({"background-color":"#f00", "color":"#fff", "cursor":"inherit"}).text("Open this window again and this message will still be here.");
				return false;
			});
		});
 </script>