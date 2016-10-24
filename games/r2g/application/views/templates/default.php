<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE9" /> 

<title><?=$meta["title"]?></title>
<? 
	unset($meta["title"]);
	foreach($meta as $key => $content) {
		echo "<meta name='{$key}' content='{$content}'>";
	}
	echo $css_link;
	echo $js_include;
?>
<link href="http://www.cooz.com.tw/top/skybar.css" rel="stylesheet" type="text/css">
<link rel="shortcut icon" href="/p/favicon.ico">
<script src="/p/js/swfobject_modified.js" type="text/javascript"></script>
<script type="text/javascript">
function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}

function MM_swapImgRestore() { //v3.0
  var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}

function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function MM_swapImage() { //v3.0
  var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
   if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
}
</script>
<style type="text/css">
.slideshowlite ul li a, .slideshowlite ul li a.current, .slideshowlite ul li a:hover{
	background:url('/p/img/banner_select.png') no-repeat; display:inline-block; width:15px; height:15px;
	border:0px;
	color:#999;
}
.slideshowlite ul li a.current {color:#333;}
.slideshowlite ul, .slideshowlite ol {right:165px;}
</style>
<body>
<div id="skybar"></div>
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/zh_TW/all.js#xfbml=1&appId=120701231314999";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

<div id="wrap">
  <div id="main">
    <div id="intop">
   	  <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="1200" height="350" align="absmiddle" id="FlashID">
    	  <param name="movie" value="/p/swf/index.swf" />
    	  <param name="quality" value="high" />
    	  <param name="wmode" value="transparent" />
    	  <param name="swfversion" value="6.0.65.0" />
    	  <!-- 此 param 標籤會提示使用 Flash Player 6.0 r65 和更新版本的使用者下載最新版本的 Flash Player。如果您不想讓使用者看到這項提示，請將其刪除。 -->
    	  <param name="expressinstall" value="Scripts/expressInstall.swf" />
    	  <!-- 下一個物件標籤僅供非 IE 瀏覽器使用。因此，請使用 IECC 將其自 IE 隱藏。 -->
    	  <!--[if !IE]>-->
    	  <object data="/p/swf/index.swf" type="application/x-shockwave-flash" width="1200" height="350" align="absmiddle">
    	    <!--<![endif]-->
    	    <param name="quality" value="high" />
    	    <param name="wmode" value="transparent" />
    	    <param name="swfversion" value="6.0.65.0" />
    	    <param name="expressinstall" value="/p/swf/expressInstall.swf" />
    	    <!-- 瀏覽器會為使用 Flash Player 6.0 和更早版本的使用者顯示下列替代內容。 -->
    	    <div>
    	      <h4>這個頁面上的內容需要較新版本的 Adobe Flash Player。</h4>
    	      <p><a href="http://www.adobe.com/go/getflashplayer"><img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="取得 Adobe Flash Player" width="112" height="33" /></a></p>
  	      </div>
    	    <!--[if !IE]>-->
  	    </object>
    	  <!--<![endif]-->
  	  </object>
    </div>
    <div id="incontent">
    <div id="inleft">
    	<div id="start">
    	  <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="285" height="155" align="absmiddle" id="FlashID2">
    	    <param name="movie" value="/p/swf/start.swf" />
    	    <param name="quality" value="high" />
    	    <param name="wmode" value="transparent" />
    	    <param name="swfversion" value="6.0.65.0" />
    	    <!-- 此 param 標籤會提示使用 Flash Player 6.0 r65 和更新版本的使用者下載最新版本的 Flash Player。如果您不想讓使用者看到這項提示，請將其刪除。 -->
    	    <param name="expressinstall" value="/p/swf/expressInstall.swf" />
    	    <!-- 下一個物件標籤僅供非 IE 瀏覽器使用。因此，請使用 IECC 將其自 IE 隱藏。 -->
    	    <!--[if !IE]>-->
    	    <object data="/p/swf/start.swf" type="application/x-shockwave-flash" width="285" height="155" align="absmiddle">
    	      <!--<![endif]-->
    	      <param name="quality" value="high" />
    	      <param name="wmode" value="transparent" />
    	      <param name="swfversion" value="6.0.65.0" />
    	      <param name="expressinstall" value="/p/swf/expressInstall.swf" />
    	      <!-- 瀏覽器會為使用 Flash Player 6.0 和更早版本的使用者顯示下列替代內容。 -->
    	      <div>
    	        <h4>這個頁面上的內容需要較新版本的 Adobe Flash Player。</h4>
    	        <p><a href="http://www.adobe.com/go/getflashplayer"><img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="取得 Adobe Flash Player" width="112" height="33" /></a></p>
  	        </div>
    	      <!--[if !IE]>-->
  	      </object>
    	    <!--<![endif]-->
  	    </object>
    	</div>
        <div id="l_00">
        <div id="l_01">
          <table width="285" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td height="55">&nbsp;</td>
            </tr>
            <tr>
              <td height="75" valign="top">
              
              <? if ($this->g_user->check_login($this->game)):?>
              
					<? if ($this->server['new']): 
							if ($url = chk_server_open($this->server['new'])):?>
			      		<!-- <div style="padding:5px 0 10px;">
			      			新服：				
			        		<a href="<?=$url?>" target="_top"><img src="/p/img/server/<?=$this->server['new']->keyName?>n_off.jpg" class="change" style="vertical-align:middle" /></a>
			        	</div> -->
			        		<? endif;
			        	endif;?>   	            
			        	
					<div style="background:#fff; margin-left:20px; width:249px;">
						<div style="padding:6px;">
						
				       	您好，歡迎登入萌英雄
				        <div class="Avatar">帳號：<span id="cooz_account"><?=$this->g_user->display_account()?></span>
				          <p>                            
				          	<a href="http://www.cooz.com.tw/member/update_member_data" target="_blank">
				          	<?= $this->g_user->check_extra_account($this->g_user->account) ? '帳號綁定' : '帳號管理';?>
				          	</a>-				          	
				          	<a href="http://www.cooz.com.tw/payment?site=my" target="_blank">儲值</a>-<a href="http://www.cooz.com.tw/gate/logout" >登出</a>
				          </p>
				        </div>
					<? if ($recent_server):
					?>
			<div style="margin-top:4px;">以下爲您最近登入過的伺服器</div>
					<? 
						foreach ($recent_server->result() as $row):
							if ($url = chk_server_open($row)):?>
					<div style="float:left; margin:5px 7px;">
			        		<a href="<?=$url?>" target="_self"><?=$row->name?></a>
			        	</div>
			        	
					<? 		endif;
						endforeach;
					endif;?>
              
              <? else:?>
              
				<form id="login_form" action="http://www.cooz.com.tw/gate/login/my" method="post">				
				              
              <table width="285" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td height="75" align="right" valign="middle"><div id="input" style="width:245px; height:100px; float:right; margin-right:18px;">
                    <table width="245" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td width="166" height="30" align="right">
                        	<input type="text" name="account" id="textfield" style="width:117px; height:25px;" tabindex="1" /></td>
                        <td width="79" rowspan="2" align="center" valign="middle">
                        	<a href="javascript:;" onclick="$('#login_form').submit();"><img src="/p/img/login_1.png" width="66" height="67" border="0" id="log_btn" onmouseover="MM_swapImage('log_btn','','/p/img/login_2.png',1)" onmouseout="MM_swapImgRestore()" /></a></td>
                      </tr>
                      <tr>
                        <td height="40" align="right">
                        	<input type="password" name="pwd" id="textfield2" style="width:117px; height:25px;" tabindex="2"/></td>
                      </tr>
                    </table>
                    <span class="text1"><a id="register" href="javascript:;">快速註冊</a> │ <a href="http://www.cooz.com.tw/member/forgot_password" target="_blank">忘記密碼</a></span></div></td>
                  </tr>
                </table>
                
                </form>
      
<div style="background:#fff; margin-left:20px; width:249px;">
	<div style="padding:5px;">                
<?php 
	$items = get_channel_items($this->game, "../");
	foreach($items as $channel => $name):
?>
	<a href="http://www.cooz.com.tw/gate/login/<?=$this->game?>?channel=<?=$channel?>"><img src="http://www.cooz.com.tw/img/login/<?=$channel?>.png" width="36" title="<?=$name?> 帳號登入"/></a>

<? endforeach;?>   
	</div>
</div>
                <? endif;?>
                
                </td>
            </tr>
            <tr>
              <td height="90" align="center" valign="middle">&nbsp;</td>
            </tr>
          </table>
        </div>
        <div id="l_02">
        	<table width="285" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="155" height="135">&nbsp;</td>
                <td width="65" height="135" align="center" valign="top">
                	<a href="http://www.cooz.com.tw/payment?game=my" target="_blank">
                		<img src="/p/img/btn1_1.png" name="btn1" width="58" height="123" vspace="10" border="0" id="btn1" onmouseover="MM_swapImage('btn1','','/p/img/btn1_2.png',1)" onmouseout="MM_swapImgRestore()" />
                	</a>                	
                </td>
                <td width="65" height="135" align="center" valign="top">
                	<a href="http://www.cooz.com.tw/wallet/transfer" target="_blank">
                		<img src="/p/img/btn2_1.png" name="btn2" width="58" height="123" vspace="10" border="0" id="btn2" onmouseover="MM_swapImage('btn2','','/p/img/btn2_2.png',1)" onmouseout="MM_swapImgRestore()" />
                	</a>
                </td>
              </tr>
            </table>
		</div>
        <div id="l_03">
        	<div style="margin:49px 30px 0; font-size:13px; line-height:18px;">
	        	<ul style="padding-left:7px;">
	        		<li>客服專線：02-2218-5988</li>
					<li>專線時間：每日10:00-22:00</li>
					<li>線上提問時間：24小時全年無休</li>
	        	</ul>
               	<a href="http://www.cooz.com.tw/service" target="_blank" >
               		<img src="/p/img/btn3_1.png" name="btn3" border="0" id="btn3" onmouseover="MM_swapImage('btn3','','/p/img/btn3_2.png',1)" onmouseout="MM_swapImgRestore()" />
               	</a>        
            </div>
           </div>
        </div>
    </div>	
    <div id="inright">
    
    	<?
    		if ($this->router->class == 'platform' && $this->router->method == 'index'):
    	?>    
    
    	<div id="r_01">
        	<div class="r_01-1">
        	  <table width="453" border="0" cellspacing="0" cellpadding="0">
        	    <tr id="bulletin_menu">
        	      <td width="151" height="51">
        	      	<a href="javascript:;" id=""><img src="/p/img/tab1-1.png" name="tab1" width="150" height="51" border="0" id="Image1" onmouseover="MM_swapImage('tab1','','/p/img/tab1-2.png',1)" onmouseout="MM_swapImgRestore()" /></a>
        	      </td>
        	      <td width="151" height="51">
        	      	<a href="javascript:;" id="51"><img src="/p/img/tab2-1.png" name="tab2" width="150" height="51" border="0" id="tab2" onmouseover="MM_swapImage('tab2','','/p/img/tab2-2.png',1)" onmouseout="MM_swapImgRestore()" /></a>
        	      </td>
        	      <td width="151" height="51">
        	      	<a href="javascript:;" id="52"><img src="/p/img/tab3-1.png" name="tab3" width="150" height="51" border="0" id="tab3" onmouseover="MM_swapImage('tab3','','/p/img/tab3-2.png',1)" onmouseout="MM_swapImgRestore()" /></a>
        	      </td>
      	      </tr>
        	    <tr>
        	      <td height="259" colspan="3" valign="top">
        	      
        	      	<ul id="bulletin_content"></ul>
        	      	<div style="padding:3px; text-align:right;"><a href="<?=site_url("bulletin/get_list")?>">More...</a></div>
        	      </td>
       	        </tr>
      	    </table>
  </div>
            <div class="r_01-2">
            	<div id="banner_1">
            		<div id="slideshow">
						<? foreach($pic_slide->result() as $row) echo make_pic_item($row)?>
					</div>					
            	</div>
                <div id="banner_2">
                	<div style="background:#fff;" class="fb-like-box" data-href="https://www.facebook.com/cooz.inc" data-width="240" data-height="260" data-colorscheme="" data-border-color="" data-show-faces="true" data-stream="false" data-header="false"></div>
                </div>
            </div>
        </div>
        <div id="r_02">
          <table width="685" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td height="105"><img src="/p/img/job_tittle.png" width="685" height="91" /></td>
            </tr>
            <tr>
              <td height="245"><table width="702" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td width="106" height="245" align="left"><img src="/p/img/job_1.jpg" name="job1" width="105" height="245" border="0" id="job1" onmouseover="MM_swapImage('job5','','/p/img/job_1-2.jpg',1)" onmouseout="MM_swapImgRestore()" /></td>
                  <td width="106" height="245" align="left"><img src="/p/img/job_2.jpg" name="job2" width="105" height="245" border="0" id="job2" onmouseover="MM_swapImage('job5','','/p/img/job_2-2.jpg',1)" onmouseout="MM_swapImgRestore()" /></td>
                  <td width="106" height="245" align="left"><img src="/p/img/job_3.jpg" name="job3" width="105" height="245" border="0" id="job3" onmouseover="MM_swapImage('job5','','/p/img/job_3-2.jpg',1)" onmouseout="MM_swapImgRestore()" /></td>
                  <td width="106" height="245" align="left"><img src="/p/img/job_4.jpg" name="job4" width="105" height="245" border="0" id="job4" onmouseover="MM_swapImage('job5','','/p/img/job_4-2.jpg',1)" onmouseout="MM_swapImgRestore()" /></td>
                  <td width="278" height="245"><img src="/p/img/job_1-2.jpg" name="job5" width="278" height="245" border="0" id="job5" /></td>
                </tr>
              </table></td>
            </tr>
          </table>
        </div>

<script type="text/javascript">
swfobject.registerObject("FlashID");
swfobject.registerObject("FlashID2");
</script>
    		
<? else:?> 
    	<table width="665" border="0" align="center" cellpadding="0" cellspacing="0">
          <tr>
            <td height="43" background="/p/img/page_tittle.png">
           	<div id="page_tittle">
           		<span class="text4" id="page_tittle2"><a href="<?=site_url("/")?>">首頁</a>&gt;</span>
            </div>
            </td>
          </tr>
          <tr>
            <td height="620" align="left" valign="top" background="/p/img/page_content.png">
            	<div style="margin:20px; width:625px; overflow:hidden;">
            	<?= $layout_content;?>
            	</div>
            </td>
           </tr>
           <tr>
            <td height="24" align="center" valign="top" background="/p/img/page_foot.png"></td>
          </tr>
        </table>            
<? endif;?>

    </div>
    </div>
    
</div>

    <div style="clear:both;"></div>
  </div>
  
   <div id="foot" style="clear:both; position:relative;">
    	<? $this->load->view("g_copyright")?>
		<div style="position:absolute; bottom:0px; background-color:#bcbcbc; height:70px; text-align:center; width:100%;">
		    	<table width="840" border="0" align="center" cellpadding="0" cellspacing="0" style="font-size:12px;">
		          <tr>
		            <td height="70" align="center" valign="middle">
		            	<table width="840" border="0" align="center" cellpadding="0" cellspacing="0">
		                  <tr>
		                    <td width="520" height="70" align="right" valign="middle" class="text5">酷栗科技 版權所有  Cooz.com All Rights Reserved</td>
		                    <td width="10" height="70" align="right" valign="middle">&nbsp;</td>
		                    <td width="50" height="70" align="center" valign="middle"><img src="http://www.cooz.com.tw/p/img/rating/rating12.png" width="45" height="45" /></td>
		                    <td width="10" height="70" align="center" valign="middle">&nbsp;</td>
		                    <td width="250" height="70" align="left" valign="middle" class="text5"><table width="250" border="0" cellspacing="0" cellpadding="0">
		                      <tr>
		                        <td width="250" height="15">■遊戲情節涉及暴力(武俠動作)。</td>
		                      </tr>
		                      <tr>
		                        <td width="250" height="15">■部分內容須另行支付費用。</td>
		                      </tr>
		                      <tr>
		                        <td width="250" height="15">■請避免沉迷於遊戲。</td>
		                      </tr>
		                    </table></td>
		                  </tr>
		            </table>
		            </td>
		          </tr>
		  		</table>
		</div>    	
    </div>

 
<input type="hidden" name="ad" value="<?=$this->input->get("ad")?>">

<script src="http://www.cooz.com.tw/top/skybar.js?<?php echo date("YmdHi");?>" type="text/javascript"></script>

</body>
</html>
