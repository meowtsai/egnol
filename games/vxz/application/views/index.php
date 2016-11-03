<div class="header">
    <div class="nav">
        <ul class="menu">
            <li class="new"><a class="anchor" href="#NEW">最新消息</a></li>
            <li class="charater"><a class="anchor" href="#CHARACTER">遊戲特色</a></li>
            <li class="center"><a href="https://game.longeplay.com.tw/payment?site=vxz">儲值中心</a></li>
            <li class="teach"><a href="#" onclick="javascript:alert('【敬請期待】');">儲值教學</a></li>
            <li class="fb"><a href="https://www.facebook.com/playvxz/?fref=ts" target="_blank">粉絲團</a></li>
            <li class="download"><a href="#" onclick="javascript:alert('【敬請期待】');">下載遊戲</a></li>
            <div class="nav2" style="display:none">
                <a class="ios" href="#" onclick="javascript:alert('【敬請期待】');">APPSTORE</a>
                <a class="google" href="#" onclick="javascript:alert('【敬請期待】');">GOOGLEPLAY</a>
                <a class="apk" href="#" onclick="javascript:alert('【敬請期待】');">APK</a>
            </div>
            <li class="service"><a href="https://game.longeplay.com.tw/service?site=vxz">客服中心</a></li>
            <li class="back"><a class="anchor" href="#TOP">回首頁</a></li>
        </ul>
    </div>
</div>

<div class="wrapper">
    <!-- TOP -->
    <div id="TOP">				
        <h1 class="wow fadeInLeft" data-wow-delay="0.2s">
            <img src="<?=base_url()?>p/img/LOGO.png" alt="">
        </h1> 			
        <div class="QR01"><img src="<?=base_url()?>p/img/QR.png">
            <a href="#" title="IOS" onclick="javascript:alert('【敬請期待】');"><img src="<?=base_url()?>p/img/APPSTORE.png"></a>
            <a href="#" title="Android" onclick="javascript:alert('【敬請期待】');"><img src="<?=base_url()?>p/img/GOOGLEPLAY.png"></a>
            <a href="#" title="APK" onclick="javascript:alert('【敬請期待】');"><img src="<?=base_url()?>p/img/APK.png"></a>
        </div>
    </div>

    <!-- NEW+SLIDER -->
    <div class="inner_box">
        <div id="NEW">					
            <h2><img src="<?=base_url()?>p/img/h2_news.png"></h2>
            <div id="newsbox"> 							
                <a href="https://game.longeplay.com.tw/news?site=vxz" class="btn_more" title="MORE"><img src="<?=base_url()?>p/img/MORE.png"></a>
                <? if (isset($news)):?>
					<?
						foreach($news->result() as $row)
						{   
							if($row->type == 3)
								$img = "icon_system";
							else if($row->type == 1)
								$img = "icon_news";
							else if($row->type == 2)
								$img = "icon_event";
                            
                            echo '<li>
                                      <a class="links" href="https://game.longeplay.com.tw/news/detail/'.$row->id.'?site='.$site.'">	
                                          <span class="news_icon"><img src="'.base_url().'p/img/'.$img.'.png" width="65" height="33"/></span>
                                          <span class="news_tips_date">'.date("Y-m-d", strtotime($row->start_time)).'</span>
                                          <span class="news_tips">'.$row->title.'</span>	
                                      </a> 
                                  </li>';
						}
					?>
                <? endif;?>
            </div>
            <div id="slider"> 
                <? if (isset($slider_news)):?>
					<?
                        $count=1;
                
						foreach($slider_news->result() as $row)
						{   
                            $count_str = str_pad($count, 2, '0', STR_PAD_LEFT);
                            $count++;
                            
                            echo '<div class="show'.$count_str.'"><a target="_blank" href="'.$row->link.'"><img src="'.$row->src.'"></a></div>';
						}
					?>
                <? endif;?>
            </div>
        </div>
    </div>

    <!-- CHARACTER -->
    <div id="CHARACTER" class="abgne_tab">					
        <ul class="tabs">
            <li class="btn_A active"><a class="" href="#tab1"></a></li>
            <li class="btn_B"><a href="#tab2"></a></li>
        </ul>
        <h2><img src="<?=base_url()?>p/img/h2_ch.png"></h2>
        <div class="tab_container">
            <div id="tab1" class="tab_content">
                <div class="slider01">
                    <div class="flexslider">
                        <ul class="slides">
                            <? if (isset($slider_about)):?>
                                <?
                                    foreach($slider_about->result() as $row)
                                    {
                                        echo '<li><img src="'.$row->src.'"></li>';
                                    }
                                ?>
                            <? endif;?>
                        </ul>
                    </div>
                </div>
            </div>

            <div id="tab2" class="tab_content">
                <div class="btn_morev"><a href="https://www.youtube.com/playlist?list=PLXR5aguP6xFioLu1TSZ_j1mgcCbcqATNV" title="更多影片"><img src="<?=base_url()?>p/img/MORE_V.png"></a></div>
                <div class="slider02">
                    <? if (isset($slider_video)):?>
                        <?
                            foreach($slider_video->result() as $row)
                            {
                                echo '<a href="'.$row->link.'" data-lity><img src="'.$row->src.'"></a>';
                            }
                        ?>
                    <? endif;?>
                </div>
            </div>
        </div>
    </div>	

    <!-- HEROS -->
    <div id="HEROS">
        <div id="owl-demo" class="owl-wrapper-outer">
            <? if (isset($slider_heroes)):?>
                <?
                    foreach($slider_heroes->result() as $row)
                    {
                        echo '<div class="item"><img src="'.$row->src.'"></div>';
                    }
                ?>
            <? endif;?>
        </div>   
        <div class="QR02"><img src="<?=base_url()?>p/img/QR.png">
            <a href="#" class="btn_IOS02" title="IOS" target="_blank"><img src="<?=base_url()?>p/img/APPSTORE.png"></a>
            <a href="#" class="btn_Android02" title="Android" target="_blank"><img src="<?=base_url()?>p/img/GOOGLEPLAY.png"></a>
            <a href="#" class="btn_APK02" title="APK" target="_blank"><img src="<?=base_url()?>p/img/APK.png"></a>				
        </div>					
    </div>

    <!-- SERVEICE -->
    <div id="service">
        <a href="https://game.longeplay.com.tw/service?site=vxz" class="btn_24" title="24小時客服中心"><img src="<?=base_url()?>p/img/btn_24.png"></a>
        <p>小李飛刀  例行性維護時間 每週四 10:00~12:00 </p>
    </div>
</div>

<!-- FOOTER -->
<div id="footer"><img src="<?=base_url()?>p/img/footer.jpg" width="1400" height="80"></div>

<!-- GO TOP -->	
<div style="display:none" id="goTop"><a href="#"><img src="<?=base_url()?>p/img/top.png" title="Back to Top"/></a></div>


<!-- 連結JS -->	

<script src="<?=base_url()?>p/js/jquery-1.12.3.min.js" type="text/javascript"></script>
<script src="<?=base_url()?>p/js/owl.carousel.js"></script>
<script>
    $(document).ready(function() {
        $("#owl-demo").owlCarousel({
        // autoPlay : true,
        stopOnHover : true,
        navigation:true,
        goToFirstSpeed : 2000,
        singleItem : true,
        autoHeight : true,
        });
    });
</script>
<script src="<?=base_url()?>p/js/lity.js"></script>
<script>
// 回最上方
    $(document).ready(function(){
        $(function(){
            $(window).scroll(function(){
                if($(this).scrollTop()>1){
                    $("#goTop").fadeIn();
                } else {
                    $("#goTop").fadeOut();
                }
            });
        });

        $("#goTop a").click(function(){
            $("html,body").animate({scrollTop:0},1000);
            $(".menu li .anchor").removeClass('active');
            $(".nav2").fadeOut();
            return false;
        });
    });		
</script>
<script src="<?=base_url()?>p/js/jquery.flexslider.js"></script>				
<script src="<?=base_url()?>p/js/jquery.cycle.all.js" type="text/javascript" ></script>				
<script src="<?=base_url()?>p/js/wow.js" type="text/javascript"></script>
<script src="<?=base_url()?>p/js/action.js" type="text/javascript"></script>	