<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>:::龍邑 Web Game:::新小李飛刀</title>

<style type="text/css">
body {
	background-color: #113040;
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
	background-image: url(/p/transfer/beanfun/sl2/img/bg-01.jpg);
	background-position: center;
}
body,td,th {
	color: #000;
	font-family: "新細明體,Arial";
	font-size: 14px;
	line-height: 22px; 
}

.form-1{ 
	margin-right: 3px;
	border: 0px;  solid:#63574d;
	color:#0f374d;
	background-color:#def1f9;
	font-size: 14px;
	line-height: 18px;
	font-family:"Arial,新細明體"
}

.form-11 {	margin-right: 3px;
	border: 0px;  solid:#63574d;
	color:#0f374d;
	background-color:#def1f9;
	font-size: 14px;
	line-height: 18px;
	font-family:"Arial,新細明體"
}
</style>
<body>

<form method="post" action="<?=site_url("transfer/beanfun/trade/{$game_id}")?>">

<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center" style="background:url(/p/transfer/beanfun/sl2/img/bg-02.jpg) no-repeat  center top;"><table width="800" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td><img src="/p/transfer/beanfun/sl2/img/img-01.jpg" width="800" height="221" /></td>
      </tr>
      <tr>
        <td><img src="/p/transfer/beanfun/sl2/img/img-02.jpg" width="800" height="91" /></td>
      </tr>
      <tr>
        <td valign="middle"><table width="800" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td width="188" rowspan="3"><img src="/p/transfer/beanfun/sl2/img/img-03.jpg" width="188" height="144" /></td>
            <td width="215"><img src="/p/transfer/beanfun/sl2/img/img-04.jpg" width="418" height="10" /></td>
            <td width="397" rowspan="3"><img src="/p/transfer/beanfun/sl2/img/img-08.jpg" width="194" height="144" /></td>
          </tr>
          <tr>
            <td height="72" align="center" style="background:url(/p/transfer/beanfun/sl2/img/img-05-bg.jpg)  center top;"><table border="0" cellspacing="0" cellpadding="2">
              <tr>
                <td align="left"><strong><font color="#ffffff">會員帳號：<?=$beanfun_id?></font></strong></td>
              </tr>
              <tr>
                <td><table border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td><img src="/p/transfer/beanfun/sl2/img/img-09.jpg" width="77" height="25" /></td>
                    <td align="right" style="background:url(/p/transfer/beanfun/sl2/img/img-10-bg.jpg) right top;">
	                    <select name="server" class="form-11">
	                      <option value="">--選擇伺服器--</option>
	                      <? foreach($server_list->result() as $row): 
	                      		if ( IN_OFFICE == false && in_array($row->server_status, array("private", "hide"))) continue;?>
	                      <option value="<?=$row->id?>"><?=$row->name?> </option>
	                      <? endforeach;?>
	                    </select>
                    </td>
                    <td width="20">&nbsp;</td>
                    <td><img src="/p/transfer/beanfun/sl2/img/img-10.jpg" width="64" height="25" /></td>
                    <td align="right" style="background:url(/p/transfer/beanfun/sl2/img/img-10-bg.jpg) right top;">                    
	                    <select name="point" class="form-11">
	                   	<? foreach($product_point as $point):?>
	                      <option value="<?=$point?>" <?=($point=='1000') ? 'selected="selected"' : ''?>><?=$point?></option>
	                    <? endforeach;?>
	                    </select>
                    </td>
                  </tr>
                </table></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td height="62" valign="bottom"><img src="/p/transfer/beanfun/sl2/img/img-06.jpg" width="418" height="62" border="0" usemap="#Map" /></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td height="149" align="center" valign="top" style="background:url(/p/transfer/beanfun/sl2/img/img-07-bg.jpg) no-repeat  center top;">
        <table width="418" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td align="left">
            	<div style="font-size:15px; margin-top:4px; color:#601;">beanfun!樂豆 <b>點數</b> 與<?=$game_row->name?> <b><?=$game_row->currency?></b> 比值為 <b>1 : <?=$game_row->exchange_rate?></b></div>

            	<hr color="#70a9c9" size=1 noshade width="418">
            </td>
          </tr>
          <tr>
            <td align="left"><strong><font color="#1b5678">注意事項：</font></strong><br />
              1. 儲值完成即無法轉回樂豆點數<br />
              2. 點數儲值異常請使用以下方式聯繫：[ <a href="http://tw.beanfun.com/customerservice/HelpDeskWeb/HelpDeskWeb_Gamania/frmCustomer.aspx" target="_blank">客服信箱</a> ]</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
    </table></td>
  </tr>
</table>

</form>

<map name="Map" id="Map">
  <area shape="rect" coords="18,3,216,57" id="form_submit" href="javascript:;" />
  <area shape="rect" coords="237,30,326,59" href="http://tw.beanfun.com/TW/CheckLogin.aspx?Page=2" target="_blank" />
  <area shape="rect" coords="326,30,416,60" href="<?=site_url("gate/login/{$game_id}?channel=beanfun")?>" target="_blank" />
</map>

<script type="text/javascript" src="/p/js/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src='/p/js/jquery.blockUI.js'></script>
<script type="text/javascript">
$(function(){
	$("#form_submit").click(function(){
		$.blockUI({message: '<h3><img src="/p/img/icon/loading.gif" border="0"> 交易處理中，請稍候...</h3>' });		
		$('form').submit();
	});	
})
</script>

</body>
</html>
