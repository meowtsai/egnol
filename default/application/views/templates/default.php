<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?=$meta["title"]?></title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<? 
	unset($meta["title"]);
	foreach($meta as $key => $content) {
		echo "<meta name='{$key}' content='{$content}'>";
	}
	//echo $css_link;
?>

<link rel='stylesheet' type='text/css' href='http://<?=base_url();?>/p/css/common.css'>
<link rel='stylesheet' type='text/css' href='http://<?=base_url();?>/p/css/default.old.css'>
<link rel='stylesheet' type='text/css' href='http://<?=base_url();?>/p/css/jquery-ui-1.8.22.custom.css'>
<link rel="stylesheet" href="/css/validationEngine.jquery.css" type="text/css" media="screen" charset="utf-8" />

<?=$js_include?>
<script src="/Js/jquery.validationEngine-tc.js" type="text/javascript"></script> 
<script src="/Js/jquery.validationEngine.js" type="text/javascript"></script> 
<script type="text/javascript"> 
    $(document).ready(function() {
      $("#form1").validationEngine();
    });
</script>

</head>

<body leftmargin=0 topmargin=0 marginwidth=0 marginheight=0>
<table width="996" border="0" align="center" cellpadding="0" cellspacing="0" background="http://<?=base_url();?>/img/Template-bg.jpg">
  <tr> 
    <td width="305"><a href="/index.php"><img src="http://<?=base_url();?>/img/logo.gif" height="42" border="0" /></a></td>
    <td width="662" align="right" class="text-white" style="padding-right: 10px;">
        <a href="/index.php"><font class="text-white">回首頁</font></a>
       │<a href="/PayFun/PayMethod.php"><font class="text-white">儲值轉點</font></a>
       │<a href="/eWallet/member_query.php"><font class="text-white">儲值記錄</font></a>
       │<a href="/member/msettings.php"><font class="text-white">會員專區</font></a>
       │<a href="http://<?=base_url();?>/service"><font class="text-white">線上客服</font></a>
       │<font class="text-white">
            <? if ($this->g_user->loginCheck()):?>       
                <a href="http://<?=base_url();?>/member/logout.php">登出</a>
            <? else:?>
                <a href="http://<?=base_url();?>/member/login.php">登入</a>
            <? endif;?>
        </font>
    </td>
  </tr>

  <tr> 
    <td colspan="3" style="padding-top:50px;">   
		<table width="762" border="0" align="center" cellpadding="0" cellspacing="0">
			<tr>
	          <td height="37" background="http://<?=base_url();?>/img/Template-img-01.gif">
	            <span style="float:right; padding-right:12px;">
				  <?= empty($layout_tmp) ? '' : $layout_tmp?>
				</span>
	          	<span style="padding:0 0 0 12px;">
					<?=$layout_breadcrumb?>
				</span>				
	          </td>
	          
          	</tr>   
	        <tr>
				<td style="background:#fff; padding:12px; height:200px; vertical-align:top"><?=$layout_content?></td>
			</tr>
		</table>    
    </td>
  </tr>
  <tr> 
     <td colspan="3" align="center"><img src="/img/Template-img-012.gif" width="762" height="16" /></td>
  </tr>
  <tr>
   <td colspan="3" align="center" style="padding-top:12px;">
 	 	<table border="0" cellspacing="0" cellpadding="3">
              <tr> 
                <td align="center" class="text-gray-light"><div align="center"><a href="/aboutUs.php">關於我們</a> 
                    | <a href="/privacy.php">隱私權保護</a> | <a href="mailto:business@molibee.com">合作提案</a> 
                    | <a href="http://<?=base_url();?>/service">客服中心</a> | </div></td>
              </tr>
              <tr> 
                <td align="center" class="text-gray-light"> 龍邑科技 版權所有 2009 Cooz.com 
                  All Rights Reserved. </td>
              </tr>
              <tr> 
                <td align="center" class="text-gray-light"><div align="left"></div></td>
              </tr>
            </table>
	</td>
  </tr>
</table>

<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-35161950-1']);
  _gaq.push(['_setDomainName', 'long_e.com.tw']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>

</body>
</html>