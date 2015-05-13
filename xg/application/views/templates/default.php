<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?=$meta["title"]?></title>
<? 
	unset($meta["title"]);
	foreach($meta as $key => $content) {
		echo "<meta name='{$key}' content='{$content}'>";
	}
	echo $css_link;
?>
<script src="/p/js/swfobject_modified.js" type="text/javascript"></script>
<link href="http://www.long_e.com.tw/top/skybar.css" rel="stylesheet" type="text/css">
<link rel="shortcut icon" href="/p/favicon.ico">
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

<?=$js_include?>
<script type="text/javascript">
$(function(){
	$('#pro_select a').mouseover(function(){
		$("#show_out div").hide();
		$("#"+$(this).attr("aa")).show();
	});
	
});
</script>
<script src="http://www.long_e.com.tw/top/skybar.js?<?php echo date("YmdHi");?>" type="text/javascript"></script>

</head>
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
    	<div id="intop" style="background:url(/p/img/top_bk.jpg) no-repeat center top">
    <? /*
          <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="100%" height="450" align="middle" id="FlashID">
            <param name="movie" value="/p/swf/index.swf" />
            <param name="quality" value="high" />
            <param name="wmode" value="transparent" />
            <param name="swfversion" value="6.0.65.0" />
            <!-- 此 param 標籤會提示使用 Flash Player 6.0 r65 和更新版本的使用者下載最新版本的 Flash Player。如果您不想讓使用者看到這項提示，請將其刪除。 -->
            <param name="expressinstall" value="/p/swf/expressInstall.swf" />
            <!-- 下一個物件標籤僅供非 IE 瀏覽器使用。因此，請使用 IECC 將其自 IE 隱藏。 -->
            <!--[if !IE]>-->
            <object data="/p/swf/index.swf" type="application/x-shockwave-flash" width="100%" height="450" align="middle">
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
      */ ?>
</div>
        <div id="content">
        	<div id="content2">
            <div id="left">
           	  
           	  
           	  <form id="login_form" method="post" action="http://www.long_e.com.tw/gate/login/<?=$this->game?>">
           	      <? if ($this->g_user->check_login($this->game)):?>
              	<div id="box01-2">
              	  <table width="230" border="0" cellspacing="0" cellpadding="0">
              	    <tr>
              	      <td height="120" align="center" valign="middle">
              	      	<a href="javascript:;" class="enter_game"><img src="/p/img/start_1.jpg" name="start_btn" width="217" height="115" border="0" id="start_btn" onmouseover="MM_swapImage('start_btn','','/p/img/start_2.jpg',1)" onmouseout="MM_swapImgRestore()" /></a>
              	      </td>
           	        </tr>
              	    <tr>
              	      <td height="120" align="left" valign="middle">
                      	<div id="msg" class="input02">
                      	
                    <div style="line-height:21px;">                    	
				        <div class="Avatar">您好，<span id="long_e_account"><?=$this->g_user->display_account()?></span></div>
				        <div><a href="http://www.long_e.com.tw/gate/logout"><img src="/p/img/log_out.png"></a></div>
						<div style="">最近登入過的伺服器：</div>
					<? if ($recent_server):
						foreach ($recent_server->result() as $row):
							if ($url = chk_server_open($row)):?>

						<div style="float:left; margin:1px 1px; overflow:hidden; background:url(/p/img/sever_bk.png); width:68px; height:24px; line-height:24px; text-align:center">
			        		<a href="<?=$url?>" target="_self"><?=$row->name?></a>
			        	</div>
			        	
					<? 		endif;
						endforeach;
					endif;?>
			        </div>                      	
                      	
                      	</div>
                      </td>
           	        </tr>
           	      </table>
              	</div>
              	            			

                    
            		<? else:?>
            		
				<div id="box01">
				
 				<table width="230" border="0" cellspacing="0" cellpadding="0">
           	      <tr>
           	        <td height="55" colspan="3">&nbsp;</td>
       	          </tr>
           	      <tr>
           	        <td width="50" height="30">&nbsp;</td>
           	        <td width="100" height="30">
           	          <input name="account" type="text" class="input01" id="textfield" tabindex="1" />
       	            </td>
           	        <td width="80" height="60" rowspan="2" align="center" valign="middle">
           	        	<a href="javascript:$('#login_form').submit()" tabindex="3"><img src="/p/img/log_btn_1.jpg" name="log_btn" width="57" height="60" border="0" id="log_btn" onmouseover="MM_swapImage('log_btn','','/p/img/log_btn_2.jpg',1)" onmouseout="MM_swapImgRestore()" /></a>
           	        	</td>
       	          </tr>
           	      <tr>
           	        <td width="50" height="30">&nbsp;</td>
           	        <td width="100" height="30"><input name="pwd" type="password" class="input01" id="textfield2" tabindex="2"/></td>
       	          </tr>
           	      <tr>
           	        <td height="30" colspan="3" align="right">
                    	<div style="padding:10px; width:180px; height:30px; margin:0 auto;">
                        	<div class="input02" style="width:70px; height:25px; display:block; float:right;"><a href="http://www.long_e.com.tw/member/forgot_password" target="_blank">◎忘記密碼</a></div>
                            <div class="input02" style="width:70px; height:25px; display:block; float:right;"><a id="register" href="javascript:;">◎快速註冊</a></div>
                        </div>
                    </td>
       	          </tr>
           	      <tr>
           	        <td height="30" colspan="3">&nbsp;	           	        
           	        </td>
       	          </tr>
           	      <tr>
           	        <td height="50" colspan="3" align="center">
<?php 
	$items = get_channel_items($this->game, "../");
	foreach($items as $channel => $name):
?>
	<a href="http://www.long_e.com.tw/gate/login/<?=$this->game?>?channel=<?=$channel?>"><img src="http://www.long_e.com.tw/img/login/<?=$channel?>.png" width="34" title="<?=$name?> 帳號登入"/></a>

<? endforeach;?>                                    
                    </td>
       	          </tr>
       	        </table>
       	       
 			</div>
 			
			<? endif;?>       
           	     </form>
           	     
           	 
                <div id="box02">
                	<table width="230" border="0" align="center" cellpadding="0" cellspacing="0">
  						<tr>
    						<td height="65" align="center" valign="middle">
    							<a href="http://www.long_e.com.tw/payment?game=<?=$this->game?>" target="_blank">
    								<img src="/p/img/btn1.png" name="btn1" width="230" height="65" border="0" id="btn1" onmouseover="MM_swapImage('btn1','','/p/img/btn1-2.png',1)" onmouseout="MM_swapImgRestore()" />
    							</a>
    						</td>
					  </tr>
 						<tr>
    						<td height="65" align="center" valign="middle">
    							<a href="http://www.long_e.com.tw/wallet/transfer?game=<?=$this->game?>" target="_blank">
    								<img src="/p/img/btn2.png" name="btn2" width="230" height="65" border="0" id="btn2" onmouseover="MM_swapImage('btn2','','/p/img/btn2-2.png',1)" onmouseout="MM_swapImgRestore()" />
    							</a>
    						</td>
					  </tr>
					</table>
			  </div>	
          </div>
            <div id="right">
            
            	<div style="margin-top:15px;">
            		<a href="<?=site_url("/")?>"><img src="/p/img/game_logo.png"  /></a>
            		<span style="display:inline-block;">
            			<div style="margin:0 0 5px ;"><img src="/p/img/security.jpg" width="36" height="36" /></div>
	            		<div class="fb-like" data-href="https://www.facebook.com/long_e.inc" data-width="The pixel width of the plugin" data-height="The pixel height of the plugin" data-colorscheme="light" data-layout="button_count" data-action="like" data-show-faces="true" data-send="false"></div>
	            	</span>
            	</div>
   	      
<? 		if ($this->router->class == 'platform' && $this->router->method == 'index'): ?>
    		
                <div id="box04">
                  <div style="height:40px;">
                  	<table width="50" border="0" align="right" cellpadding="0" cellspacing="0">
                      <tr>
                        <td height="25" valign="middle" class="text2">
                        	<ul id="bulletin_menu">
                                <li class="tab_btn1"><a href="javascript:;"></a></li>
                            </ul>
                        <a href="<?=site_url("bulletin/get_list")?>" style="color:#cc0000">More..</a></td>
                      </tr>
                    </table>
				  </div>
				  <div id="bulletin_content"></div>
           	  </div>
                <div id="box05">
                	<img src="/p/img/banner_bk.jpg" height="30" />
                	<div style="text-align:center">
                	
            <? foreach($pic_icon->result() as $row):?>
			<span style="padding:4px; display:inline-block;"><?=make_pic_item($row)?></span>
			<? endforeach;?>
			  
                	</div>
                  </div>    		 
                  
    		<? else: ?>
    		
                <div id="box04" style="height:30px;">
				</div>
				<div style=" margin:5px; padding:10px; width:560px; background:#ededed;">	  
					<? echo $layout_content;?>
				</div>
    		<? endif;?>
    		
 				<div style="clear:both;"></div>
              </div>
              
              <div style="clear:both;"></div>
          </div>
           <div style="clear:both;"></div>
      </div>

      <? $this->load->view("g_copyright")?>
    </div>
</div>
<script type="text/javascript">
swfobject.registerObject("FlashID");
</script>
				

<input type="hidden" name="ad" value="<?=$this->input->get("ad")?>">


</body>
</html>
