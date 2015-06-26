
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-language" content="zh-CN" />
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<title>3722-仙府之缘-(1)测试一服</title>
<script type="text/javascript" src="http://www.3722.com/ims/jquery-1.4.3.min.js?version=20120323163026"></script>
<link media="screen" href="http://static.3722.com/css/playgame_top.css?ver=1" type="text/css" rel="stylesheet">
<link href="http://static.3722.com/css/playgametop.css" type="text/css" rel="stylesheet">
<script type="text/javascript">
// 定义全局变量.用来记录用户关闭iframe的状态
var gameIframeAdStatus = 0;
var gameIframeAdSecond = 0;
var gameIframeAdInterval;

//************************************************************************************
// scrolling_popup
// Copyright (C) 2006, Massimo Beatini
//
// This software is provided "as-is", without any express or implied warranty. In
// no event will the authors be held liable for any damages arising from the use
// of this software.
//
// Permission is granted to anyone to use this software for any purpose, including
// commercial applications, and to alter it and redistribute it freely, subject to
// the following restrictions:
//
// 1. The origin of this software must not be misrepresented; you must not claim
//    that you wrote the original software. If you use this software in a product,
//    an acknowledgment in the product documentation would be appreciated but is
//    not required.
//
// 2. Altered source versions must be plainly marked as such, and must not be
//    misrepresented as being the original software.
//
// 3. This notice may not be removed or altered from any source distribution.
//
//************************************************************************************

// float directions
var leftRight = 1;
var rightLeft = 2;
var topDown = 3;
var bottopUp = 4;

// side
var leftSide = 1;
var rightSide = 2;

// position
var topCorner = 1;
var bottomCorner = 2;

// default title
var _title = 'Put here your title';

// default width
var popupWidth = 210;
var popupHeight = 81;

var only_once_per_browser = false;

var ns4 = document.layers;
var ie4 = document.all;
var ns6 = document.getElementById&&!document.all;
var crossobj;

function getCrossObj()
{
    var contentDiv;
    var titleDiv;

    if (ns4)
    {
        crossobj = document.layers.postit;
        contentDiv = document.layers.postit_content;
        titleDiv = document.layers.postit_title;
    }
    else if (ie4||ns6)
    {
        crossobj = ns6? document.getElementById("postit") : document.all.postit;
        contentDiv = ns6? document.getElementById("postit_content") : document.all.postit_content;
        titleDiv = ns6? document.getElementById("postit_title") : document.all.postit_title;
    }
    crossobj.style.width = popupWidth + 'px';
    crossobj.style.height = popupHeight + 'px';

    // adjust the size of the div "content"
    contentDiv.style.width = (popupWidth) + 'px';
    contentDiv.style.height = (popupHeight) + 'px';

    // adjust the width of the div "title"
    //titleDiv.style.width = (popupWidth-8) + 'px';

}

//
//  buildPopup_Frame
//  passing it the url of the frame to display inside
//
function buildPopup_Frame(width, height, title, framesrc)
{
    if (width)
        popupWidth = width;

    if (height)
        popupHeight = height;

    if (title)
        _title = title


    document.write('<div id="postit" class="postit" >');

    document.write('<div id="postit_title" class="title" style="display: none;"><b>' + _title + ' <span class="spantitle">&nbsp;</b></span></div>');
    document.write('<div id="postit_content" class="content">');

    document.write('<iframe src="' + framesrc + '" width="100%" height="100%" marginwidth="0" marginheight="0" hspace="0" vspace="0" frameborder="0" scrolling="no" bordercolor="#000000"></iframe>');

    document.write('</div></div>');

    getCrossObj();
}

//
//  buildPopup_HtmlCode
//  build popup passing it the html code to put inside
//
function buildPopup_HtmlCode(width, height, title, htmlCode)
{
    if (width)
        popupWidth = width;

    if (height)
        popupHeight = height;

    if (title)
        _title = title

    document.write('<div id="postit" class="postit">');

    document.write('<div id="postit_title" class="title"><b>' + _title + ' <span class="spantitle"><img src="close.gif" border="0" title="Close" align="right" WIDTH="11" HEIGHT="11" onclick="closeit()">&nbsp;</b></span></div>');
    document.write('<div id="postit_content" class="content">');

    document.write(htmlCode);

    document.write('</div></div>');

    getCrossObj();
}

//
//  closeit
//
function closeit()
{
    if (ie4||ns6)
        crossobj.style.visibility="hidden";
    else if (ns4)
        crossobj.visibility="hide";

	if (gameIframeAdStatus == 0) {
		gameIframeAdStatus = 1;

		gameIframeAdInterval = setInterval("showAgain()", 60000);
	}
}

function showAgain()
{
	gameIframeAdSecond++;
	if (gameIframeAdSecond % 60 == 0) {
		ShowTheBox(false, rightSide, bottomCorner, bottopUp);
		clearInterval(gameIframeAdInterval);
		gameIframeAdSecond = 0;
		gameIframeAdStatus = 0;
	}
}

//
//  get_cookie
//
function get_cookie(Name)
{
    var search = Name + "=";
    var returnvalue = "";

    if (document.cookie.length > 0)
    {
        offset = document.cookie.indexOf(search);
        if (offset != -1)
        {
            // if cookie exists
            offset += search.length;
            // set index of beginning of value
            end = document.cookie.indexOf(";", offset);
            // set index of end of cookie value
            if (end == -1)
                end = document.cookie.length;
            returnvalue=unescape(document.cookie.substring(offset, end));
         }
    }
    return returnvalue;
}

//
// check the cookie
//
function showOrNot(direction)
{
    var showit = false;

    if (get_cookie('postTheBoxDisplay')=='')
    {
        showit = true;
        document.cookie = "postTheBoxDisplay=yes";
    }
    return showit;
}

//
//  showItRight
//
function showIt(direction)
{
    var steps;

    steps = Math.floor(popupHeight / 4)+5;


    if (ie4||ns6)
    {
        crossobj.style.visibility = "visible";
        if ((direction == rightLeft) || (direction == leftRight))
            flyTheBox(direction, 0, popupWidth , steps, 1000);
        else
            flyTheBox(direction, 0, popupHeight , steps, 1000);
    }
    else if (ns4)
        crossobj.visibility = "show";
}

//
//  flyTheBox
//
function flyTheBox(direction, start, end, steps, msec, counter)
{
	if(!counter)
		counter = 1;

	var tmp;

	if(start < end)
	{
	    if (direction == rightLeft)
	        crossobj.style.width = end / steps * counter + 'px';
	    else if (direction == bottopUp)
	        crossobj.style.height = end / steps * counter + 'px';
	    else if (direction == topDown)
	        crossobj.style.top = ((end / steps * counter) - popupHeight) + 'px';
        else if (direction == leftRight)
	        crossobj.style.left = (end / steps * counter)-popupWidth + 'px';

    }
	else
	{

	    tmp=steps -	counter;
	    if (direction == rightLeft)
	        crossobj.style.width = start / steps * tmp + 'px';
	    else if (direction == bottopUp)
	        crossobj.style.height = start / steps * tmp + 'px';
	    else if (direction == topDown)
	        crossobj.style.top = ((end / steps * counter) - popupHeight) + 'px';

	}
	if(counter != steps)
	{
	    counter++;
	    flyTheBox_timer=setTimeout('flyTheBox('+ direction + ',' + start + ','+ end + ',' + steps + ',' + msec + ', '+ counter + ')', msec/steps);
	}
	else
	{
	    if(start > end)
			crossobj.style.display = 'none';
	}
}


//
// ShowTheBox
//
function ShowTheBox(only_once, side, corner, direction)
{
    if (side == leftSide)
    {
        if (direction == rightLeft)
            return;
        crossobj.style.left = '1px';
    }
    else
    {
        if (direction == leftRight)
            return;
	    crossobj.style.right = '1px';
    }

    if ((corner == topCorner) && (direction == bottopUp))
        return;

    if ((corner == bottomCorner) && (direction == topDown))
        return;

    if ( (direction == topDown) && (corner == topCorner) )
        crossobj.style.top = '-' + popupHeight + 'px';
    else if ( ((direction == rightLeft)||(direction == leftRight)) && (corner == topCorner) )
        crossobj.style.top = '1px';
    else if (corner == bottomCorner)
        crossobj.style.bottom = '2px';

    if (only_once)
        only_once_per_browser = only_once;

    if (only_once_per_browser)
    {
        // verify the presence of a cookie
	    if (showOrNot())
	        showIt(direction);
    }
    else
	    setTimeout("showIt("+ direction + ")",1030);
}
//document.domain = 'longeplay.com.tw';
</script>
 <script type="text/javascript" src="http://static.3722.com/js/jquery.js"></script>
<script type="text/javascript" src="http://static.3722.com/js/jquery.vticker-min.js"></script>
<script type="text/javascript">
$(function(){
	 $('#news-container1').vTicker({
		speed: 700,
		pause: 4000,
		animation: 'fade',
		mousePause: false,
		showItems: 1
	});
});
</script>
</head>

<body style="margin:0; padding:0px; width:100%; height:100%; position:absolute; background-color:#fff; "  scroll="no" bgcolor="#000000" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" >
<div class="ToP gameTopBox"><!--注意class名大小写,内容显示宽度1002px-->

	<div class=" gameTopLeft">
				<span class="a1"><a href="http://www.3722.com/" onclick="toplog(1);" target="_blank" title="3722平台首页"><img style="border:0px;" src="http://static.3722.com/images/playgame_top/ToP_img_r1_c4.jpg" /></a></span>
                <span class="a2"><img src="http://static.3722.com/images/horn.gif"/></span>
				<div id="news-container1" class="a3">
                    <ul>
                                                   <li> <a target="_blank" title="【傲剑】46服“唯我独尊”炫丽登场" href="http://aj.3722.com/newspublic/2012/0428/3017.html" onclick="toplog(2);">【傲剑】46服“唯我独尊”炫丽登场</a></li>
                                                   <li> <a target="_blank" title="【海贼王】双线1区“扬帆起航”开启" href=" http://hzw.3722.com/zhxw/zxzx/2012/0529/3210.html" onclick="toplog(2);">【海贼王】双线1区“扬帆起航”开启</a></li>
                                                   <li> <a target="_blank" title="【梦幻飞仙】首服“仙风道骨”火爆开启中" href="http://mhfx.3722.com/zhxw/gg/2012/0411/2933.html" onclick="toplog(2);">【梦幻飞仙】首服“仙风道骨”火爆开启中</a></li>
                                            </ul>
			    </div>
  </div>

			<div class="gameTopRight">
            <span class="ToP_right_p a2" id="im" style="display:none;"><a href="javascript:void(0);" onclick="SHOW_IM();return false;"><img src="http://www.3722.com/ims/msg_im.gif" style="border:0px;" id="im_img"></a></span>
				<span class="a1">
                                                <a target="_blank" href="http://xianfu.3722.com" title="仙府之缘">官网</a>
                                                <a title="3722_充值" target="_blank" href="http://www.3722.com/payment2/17/98" onclick="toplog(3);" class="ToP_chongzhi_a">充值</a>
				<a title="3722_论坛" target="_blank" href="http://bbs.3722.com/" onclick="toplog(7);">论坛</a>
                <a target="_blank" href="http://chat.looyu.com/chat/chat/p.do?c=28140&f=91747" onclick="toplog(6);" title="客服">客服</a><i>|</i>
             			<a class="username" href="http://passport.3722.com/ucenter/index/">amelia01</a>
			[ <a target="_top" href="http://passport.3722.com/member/logout" class="go">注销</a> ]
			</span>
			</div>	

</div>
<script type="text/javascript">
function addBookmark() {
	var sURL = 'http://www.3722.com';
	var sTitle = '3722-中国领先的网页互动娱乐发行平台';
 if (document.all) {
  try{
   window.external.addFavorite(sURL, sTitle);
  }catch (e1){
   try{
    window.external.addToFavoritesBar(sURL, sTitle);
   }catch (e2){
    alert('加入收藏失败，请Ctrl+D手动添加。');
   }
  }
 } else if (window.external) {
  window.sidebar.addPanel(sTitle, sURL,"");
 } else {
  alert('加入收藏失败，请Ctrl+D手动添加。');
 }
}
function toplog(id)
{
	$.ajax({
	   type: "POST",
	   url: "http://www.3722.com/playgame/top_log/",
	   data: "typyid="+id
	});
}
</script>
<?php echo $game_url?>
            <iframe id="gameif" marginwidth="0" style="z-index:-9999;" marginheight="0" src="<?=$game_url?>" width="100%" frameborder="0" marginheight="0" marginwidth="0"  height="95%" scrolling="auto"></iframe>
    <!-- for IM -->
<div id="im_div" style="width:1px;height:1px;">
    <div id="flashContent"></div>
</div>

<script src="http://www.3722.com/ims/strophe/strophe.js"></script>
<script src='http://www.3722.com/ims/strophe/flXHR.js'></script>
<script src='http://www.3722.com/ims/strophe/strophe.flxhr.js'></script>
<script src='http://im.3722.com/im/im.js?version=20121128205347'></script>
<script type="text/javascript" src="http://www.3722.com/ims/swfobject.js"></script>
<script type="text/javascript" src="http://www.3722.com/ims/history/history.js"></script>
<script type="text/javascript">
    var swfVersionStr = "10.0.0";
    var xiSwfUrlStr = "playerProductInstall.swf";
    var flashvars = {"action":"user","sign":"xCR8uDMimifYvYpweC/MfFKOe8Ou3rblpwhcJH7Ezz9Mqr2Q9OaerH1mNVCjKboreAatu8D1OWKG6EnAl2EQZVzXZ97YLK%2bjjVl%2bZqzbYYIs7Tng4ajk9Yp0LzkqKZbMl7ZwxMGUGm1pX8TbPySJMYG4F5DWHfoOeZOc9bX5lTxB6gQgm6djy4dPWSFdDxRZX44veET3D6FLvXgulglPKpWnrM1/D3owqZWqM1NoZekjdCFPLvm1S80KwFTFcutjmjoihinaZbRjBtiKuS/Az8IyL77kWRJoSieT6%2be0NC7WYl3LBAP9%2bYI76UhbOoY7t2IV50fXXXfSWT0=","upload":"http://im.3722.com/upload.php","login":"http://im.3722.com/login.php", "send":"http://im.3722.com/index.php?action=sendmsg"};
    var params = {"wmode":"window"};
    params.quality = "high";
    params.bgcolor = "#ffffff";
    params.allowscriptaccess = "always";
    params.allowfullscreen = "true";
    var attributes = {};
    attributes.id = "test";
    attributes.name = "test";
    attributes.align = "middle";
    window.onload = function() {
        var width	=	$(window).width()/2-392/2;
        var height	=	$(window).height()/2-486/2;
        swfobject.embedSWF(
        "http://www.3722.com/ims/im.swf?rand=20121128205347", "flashContent",
        "1", "1",
        swfVersionStr, xiSwfUrlStr,
        flashvars, params, attributes);
        swfobject.createCSS("#im_div", "position: absolute;left: 50px;top: "+height+"px;z-index:9999;");
    }

// IM程序初始化完毕
function IM_INIT(result){
    var obj = eval("("+result+")");
    BOSH_LOCATION = 'http://im.3722.com/http-bind/';
    Chat.gs_name = obj.gs_name;
    Chat.bindInfo = obj;
    Chat.init(obj.jid + "/" + obj.gameinfo, obj.pass, obj.nickname, obj.to);
    try {
        connection = new Strophe.Connection(BOSH_LOCATION);
        connection.connect(Chat.jid + "/" + obj.gameinfo, Chat.password, function (status) {
            if (status == Strophe.Status.CONNFAIL) {
//                alert("An error occured:Failed to connect");
            } else if (status == Strophe.Status.DISCONNECTED) {
                $(document).trigger('disconnected');
            } else if (status == Strophe.Status.CONNECTED) {
                $(document).trigger('connected');
            }
        });
        Chat.connection = connection;
    } catch (e) {
    }
}

// 监听到新消息
function IM_NEW_MSG(){
    if($("#test").css("width") == '1px'){
       $("#im_img").attr("src", "http://www.3722.com/ims/msg_new.gif");
    }
    swfobject.embedSWF("http://www.3722.com/ims/msg.swf", "msg_sound", "0", "0", "9.0.0");

}

$(document).bind('connected', function () {
    try{
        Chat.connection.addHandler(Chat.listenMessage, null, "message");
        Chat.connection.addHandler(Chat.listenPresence, null, 'presence');
        Chat.connection.send($pres().tree());
        Chat.addRoster({uid:Chat.room,uname:Chat.gs_name});
        Chat.connected();
        $("#im").show();
    }catch(e){
    }finally{
    }

});


$(document).bind('disconnected', function () {
    Chat.doDisconnect();
});
// 监听到新消息
function IM_NEW_MSG(){
    if($("#test").css("width") == '1px'){
       $("#im_img").attr("src", "http://www.3722.com/ims/msg_new.gif");
    }
    swfobject.embedSWF("http://www.3722.com/ims/msg.swf", "msg_sound", "0", "0", "9.0.0");

}

// 最小化IM聊天窗口
function IM_MIN_WINDOW(){
    $("#test").attr("height", 1);
    $("#test").attr("width", 1);
}

// 显示IM聊天窗口
function SHOW_IM(){
    $("#test").attr("height", 487);
    $("#test").attr("width", 392);
    $("#im_img").attr("src", "http://www.3722.com/ims/msg_im.gif");
}
</script>
<div id="msg_sound" style="display:none;"></div>
<!--游戏条滚动新闻begin-->
<!-- <script language="javascript" src="http://static.3722.com/js/ScrollText.js"></script>
 <script language="javascript" type="text/javascript">
    window.onload = function()
    {
        var scrollup = new ScrollText("listcontent");
        scrollup.LineHeight = 32;
        scrollup.Amount = 4;
        scrollup.Start();
	}
 </script>-->

<!--游戏条滚动新闻end-->
	<!--online time-->
	<script type="text/javascript">
		setTimeout("online_time()", 10*60*1000);

		function online_time(){
			$.get("http://www.3722.com/ajax/online_time/");
		}
	</script>

	<!-- google analytics -->
	<script type="text/javascript">

	  var _gaq = _gaq || [];
	  _gaq.push(['_setAccount', 'UA-24624852-1']);
	  _gaq.push(['_trackPageview']);

	  (function() {
		var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
		ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
		var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	  })();

	</script>
<!-- google analytics end -->

<!--xianfu baidu analytics begin-->
<script type="text/javascript">
var _bdhmProtocol = (("https:" == document.location.protocol) ? " https://" : " http://");
document.write(unescape("%3Cscript src='" + _bdhmProtocol + "hm.baidu.com/h.js%3Fe0ba44583778bc0ac26da267ecda09fc' type='text/javascript'%3E%3C/script%3E"));
</script>
<!--xianfu baidu analytics end-->

<!--xianfu google analytics begin-->
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-24624852-18']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
<!--xianfu google analytics end-->
</body>
</html>