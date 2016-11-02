<!-- MEUN on -->        
<div class="header01">			
    <div class="nav">
        <ul class="menu">
            <li id="btn_NEW" class="Nowtab"><a class="anchor" href="#NEW">最新消息</a></li>
            <li id="btn_CHARACTER" class="Nowtab"><a class="anchor" href="#CHARACTER">遊戲特色</a></li>
            <li id="btn_FB" class="Nowtab"><a href="https://www.facebook.com/playvxz/?fref=ts">粉絲團</a></li>
            <li id="btn_DOWNLOAD" class="Nowtab"><a href="#" onclick="javascript:alert('【敬請期待】');">下載遊戲</a></li>
        </ul>
    </div>
    <div class="nav2"  style="display:none">
        <a class="ios" href="#" target="_blank">APPSTORE</a>
        <a class="google" href="#" target="_blank">GOOGLEPLAY</a>
        <a class="apk" href="#" target="_blank">APK</a>
    </div>
</div>

<!-- MEUN under -->
<div class="header02">
    <div class="nav">
        <ul class="menu">
            <div class="ICON"><img src="<?=base_url()?>p/img/mobile/ICON.png"></div>
            <li id="btn_CENTER" class="Nowtab"><a href="https://game.longeplay.com.tw/payment?site=vxz">儲值中心</a></li>
            <li id="btn_TEACH" class="Nowtab"><a href="#" onclick="javascript:alert('【敬請期待】');">儲值教學</a></li>
            <li id="btn_SERVICE" class="Nowtab"><a href="https://game.longeplay.com.tw/service?site=vxz">客服中心</a></li>
        </ul>
    </div>
</div>

<div class="wrapper">		
    <!-- TOP -->
    <div id="TOP">
        <div class="inner_box">
            <h1 class="wow fadeInUp" data-wow-delay="0.2s"><img src="<?=base_url()?>p/img/mobile/LOGO.png"></h1> 
            <!-- APP 下載點 -->
            <a href="#" class="btn_IOS01" title="IOS" onclick="javascript:alert('【敬請期待】');"><img src="<?=base_url()?>p/img/mobile/APPSTORE.png"></a>
            <a href="#" class="btn_Android01" title="Android" onclick="javascript:alert('【敬請期待】');"><img src="<?=base_url()?>p/img/mobile/GOOGLEPLAY.png"></a>
            <a href="#" class="btn_APK01" title="APK" onclick="javascript:alert('【敬請期待】');"><img src="<?=base_url()?>p/img/mobile/APK.png"></a>
        </div>
    </div>

    <!-- NEW -->
    <div id="NEW">
    <div class="box"></div>
        <div class="inner_box">
            <div id="newsbox"> 
                <h2><img src="<?=base_url()?>p/img/mobile/h2_news.png"></h2>
                <a href="https://game.longeplay.com.tw/news?site=vxz" class="btn_more" title="MORE"><img src="<?=base_url()?>p/img/mobile/MORE.png"></a>
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
                                          <span class="news_icon"><img src="'.base_url().'p/img/'.$img.'.png" width="91" height="47"/></span>
                                          <span class="news_tips_date">'.date("Y-m-d", strtotime($row->start_time)).'</span>
                                          <span class="news_tips">'.$row->title.'</span>	
                                      </a> 
                                  </li>';
						}
					?>
                <? endif;?>
            </div>					
        </div>
    </div>

    <!-- SLIDER -->
    <div id="SLIDERBOX">
        <div class="inner_box">
            <div id="slider"> 
                <? if (isset($slider_news_mobile)):?>
                    <?
                        $count=1;

                        foreach($slider_news_mobile->result() as $row)
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
        <div class="box"></div>
        <h2><img src="<?=base_url()?>p/img/mobile/h2_ch.png"></h2>
        <ul class="tabs">
            <li class="btn_A active"><a class="" href="#tab1"></a></li>
            <li class="btn_B"><a href="#tab2"></a></li>
        </ul>
        <div class="inner_box">					
            <div id="tab_container">
                <div id="tab1" class="tab_content">							
                    <div class="slider01">
                        <div id="owl-demo2" class="owl-wrapper-outer">
                            <? if (isset($slider_about_mobile)):?>
                                <?
                                    foreach($slider_about_mobile->result() as $row)
                                    {
                                        echo '<div class="item"><img src="'.$row->src.'"></div>';
                                    }
                                ?>
                            <? endif;?>
                        </div>
                    </div>
                </div>
                <div id="tab2" class="tab_content">
                    <div  class="btn_morev"><a href="https://www.youtube.com/playlist?list=PLXR5aguP6xFioLu1TSZ_j1mgcCbcqATNV" title="更多影片"><img src="<?=base_url()?>p/img/mobile/MORE_V.png"></a></div>
                    <div  class="slider02">
                        <? if (isset($slider_video_mobile)):?>
                            <?
                                foreach($slider_video_mobile->result() as $row)
                                {
                                    echo '<a href="'.$row->link.'" data-lity><img src="'.$row->src.'"></a>';
                                }
                            ?>
                        <? endif;?>
                    </div>
                </div>						
            </div>
        </div>
    </div>

    <!-- HEROS -->
    <div id="HEROS">
        <div class="inner_box">
            <div id="owl-demo" class="owl-wrapper-outer">
                <? if (isset($slider_heroes_mobile)):?>
                    <?
                        foreach($slider_heroes_mobile->result() as $row)
                        {
                            echo '<div class="item"><img src="'.$row->src.'"></div>';
                        }
                    ?>
                <? endif;?>
            </div>
            <a href="#" class="btn_IOS02" title="IOS"><img src="<?=base_url()?>p/img/mobile/APPSTORE.png"></a>
            <a href="#" class="btn_Android02" title="Android"><img src="<?=base_url()?>p/img/mobile/GOOGLEPLAY.png"></a>
            <a href="#" class="btn_APK02" title="APK"><img src="<?=base_url()?>p/img/mobile/APK.png"></a>
        </div>
    </div>

    <!-- SERVICE -->
    <div id="service">
        <a href="https://game.longeplay.com.tw/service?site=vxz" class="btn_24" title="24小時客服中心"><img src="<?=base_url()?>p/img/mobile/btn_24.png"></a>
        <p>小李飛刀  例行性維護時間 每週四 10:00~12:00 </p>
    </div>

    <!-- FOOTER -->
    <div id="footer">
        <img src="<?=base_url()?>p/img/mobile/footer.jpg">
    </div>
</div>

<!-- GO TOP -->
<div style="display:none" id="goTop"><a href="#"><img src="<?=base_url()?>p/img/mobile/top.png" title="Back to Top"/></a></div>

<!-- 連結JS -->
<script src="<?=base_url()?>p/js/mobile/jquery-1.12.3.min.js" type="text/javascript"></script>
<script src="<?=base_url()?>p/js/mobile/owl.carousel.js"></script>
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
<script>

    $(document).ready(function() {
    $("#owl-demo2").owlCarousel({
    autoPlay : true,
    stopOnHover : true,
    navigation:true,
    goToFirstSpeed : 2000,
    singleItem : true,
    autoHeight : true,
    });
    });
</script>		
<script src="<?=base_url()?>p/js/mobile/wow.js" type="text/javascript"></script>
<script src="<?=base_url()?>p/js/mobile/jquery.cycle.all.js" type="text/javascript" ></script>
<script src="<?=base_url()?>p/js/mobile/lity.js"></script>
<script src="<?=base_url()?>p/js/mobile/action.js" type="text/javascript"></script>