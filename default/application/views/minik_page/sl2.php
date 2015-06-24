<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>:::龍邑 Web Game:::小李飛刀</title>


<style type="text/css">
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
	background-color: #2E3E50;
}
</style>
<link rel='stylesheet' type='text/css' href='http://sl2.longeplay.com.tw/p/css/default.css'>
<script type="text/javascript">
function MM_swapImgRestore() { //v3.0
  var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}
function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
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
<body onload="MM_preloadImages('/p/minik/sl2/img/icon-01-on.jpg','/p/minik/sl2/img/icon-02-on.jpg','/p/minik/sl2/img/icon-03-on.jpg')">

<div style="position:absolute; right:40px; top:345px;">
	<a href="http://www.longeplay.com.tw/payment?site=sl2" target="_blank"><img src="/p/minik/icon-pay.png"></a>
</div>

<iframe id="invite_ifm" src="" width="0" height="0" frameborder="0"></iframe>

<table border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td colspan="3"><img src="/p/minik/sl2/img/img-01.jpg" width="760" height="193" /></td>
  </tr>
  <tr>
    <td><img src="/p/minik/sl2/img/img-02.jpg" width="206" height="138" /></td>
    <td><a href="http://sl2.longeplay.com.tw/" target="_blank"><img src="/p/minik/sl2/img/img-03.jpg" width="352" height="138" border="0" /></a></td>
    <td><img src="/p/minik/sl2/img/img-04.jpg" width="202" height="138" /></td>
  </tr>
  <tr>
    <td colspan="3"><table border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td rowspan="3"><img src="/p/minik/sl2/img/img-05.jpg" width="83" height="209" /></td>
        <td rowspan="3">
            <? if ($old_user):?>
        	<a href="javascript:;" onclick="chose_server_form()"><img src="/p/minik/sl2/img/img-06.jpg" width="224" height="209" border="0" /></a>
        	<? else:?>
        	<a href="http://www.longeplay.com.tw/play_game/sl2" target="_blank"><img src="/p/minik/sl2/img/img-06.jpg" width="224" height="209" border="0" /></a>
        	<? endif;?>          
        </td>
        <td><img src="/p/minik/sl2/img/img-07.jpg" width="453" height="68" /></td>
      </tr>
      <tr>
        <td><a href="javascript:invite_friend();" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('/icon-01','','/p/minik/sl2/img/icon-01-on.jpg',1)"><img src="/p/minik/sl2/img/icon-01-off.jpg" name="/icon-01" width="113" height="72" border="0" id="/icon-01" /></a><a href="http://tw.socialgame.yahoo.net/mtag/mtag.php?ac=home&tagid=4993" target="_blank" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('/icon-02','','/p/minik/sl2/img/icon-02-on.jpg',1)"><img src="/p/minik/sl2/img/icon-02-off.jpg" name="/icon-02" width="142" height="72" border="0" id="/icon-02" /></a><a href="http://sl2.longeplay.com.tw" target="_blank" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('/icon-03','','/p/minik/sl2/img/icon-03-on.jpg',1)"><img src="/p/minik/sl2/img/icon-03-off.jpg" name="/icon-03" width="126" height="72" border="0" id="/icon-03" /></a><img src="/p/minik/sl2/img/img-08.jpg" width="72" height="72" border="0" /></td>
      </tr>
      <tr>
        <td><img src="/p/minik/sl2/img/img-09.jpg" width="453" height="69" /></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td colspan="3">
    	<div style="position:absolute; right:15px; bottom:15px;"><img src="http://www.longeplay.com.tw/img/rating/rating15.png"></div>
    	<img src="/p/minik/sl2/img/img-10.jpg" width="760" height="420" />
    </td>
  </tr>
</table>

<div id="choose" style="display:none;"><?=$choose_server_form?></div>

<script type="text/javascript" src="http://www.longeplay.com.tw/p/js/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src='http://www.longeplay.com.tw/p/js/jquery.blockUI.js'></script>
<script type="text/javascript">
function invite_friend() {
	$('#invite_ifm').attr("src", "<?=$inviteSrc?>");
}
function chose_server_form() {
	$.blockUI({ message: $("#choose"),
		css: { 
			top: '30px',
			left: '120px',
			backgroundColor: '',
			cursor: 'auto',
			border: '0'
		}}); 
	$('.blockOverlay').click($.unblockUI);
	$("#choose").find('.close').click($.unblockUI);		
}
</script>

</body>
</html>