<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>:::龍邑 Web Game:::霸王</title>



<style type="text/css">
body {
	background-color: #A5937B;
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
	background-position: center;
	

}

body,td,th {
	color: #000;
	font-family: "新細明體", Arial;
	font-size: 14px;
	line-height: 22px;
}


#middle {
    position:absolute; width:100%; height:580px;
    top:0; bottom:0; left:0; right:0; margin:auto;
}
<br />

.form-1{ 
	margin-right: 3px;
	border: 0px;  solid:#63574d;
	color:#0f374d;
	background-color:#f5e9db;
	font-size: 14px;
	line-height: 18px;
	font-family:"Arial,新細明體"
}

.form-11 {	margin-right: 3px;
	border: 0px;  solid:#63574d;
	color:#0f374d;
	background-color:#f5e9db
	font-size: 14px;
	line-height: 18px;
	font-family:"Arial,新細明體"
}
.form-111 {margin-right: 3px;
	border: 0px;  solid:#63574d;
	color:#0f374d;
	background-color:#f5e9db;
	font-size: 14px;
	line-height: 18px;
	font-family:"Arial,新細明體"
}
</style>

<body><br />

<form method="post" action="<?=site_url("transfer/beanfun/trade/{$game_id}")?>">

<div id="middle">

<table width="100%" border="0" cellspacing="1" cellpadding="1">
  <tr>
    <td align="center" style="background:url(/p/transfer/beanfun/bw/img/bg.jpg) top ;"><table border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td colspan="3"><img src="/p/transfer/beanfun/bw/img/img-01.jpg" width="1177" height="260" /></td>
        </tr>
      <tr>
        <td rowspan="3"><img src="/p/transfer/beanfun/bw/img/img-02.jpg" width="405" /></td>
        <td height="63" style="background:url(/p/transfer/beanfun/bw/img/img-03.jpg);"><table border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td align="left"><strong><font color="#ffffff">會員帳號：<?=$beanfun_id?></font></strong>
            	<div style="font-size:12px; line-height:9px; color:#601;">beanfun!樂豆 <b>點數</b> 與<?=$game_row->name?> <b><?=$game_row->currency?></b> 比值為 <b>1 : <?=$game_row->exchange_rate?></b></div>            
            </td>
          </tr>
          <tr>
            <td><table border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td><img src="/p/transfer/beanfun/bw/img/img-07.jpg" width="77" height="25" /></td>
                <td align="right" style="background:url(/p/transfer/beanfun/bw/img/img-09.jpg) right top;"><select name="server" class="form-111" id="jumpMenu2">
                  <option value=""> 下拉選單------- </option>
	                      <? foreach($server_list->result() as $row): 
	                      		if ( IN_OFFICE == false && in_array($row->server_status, array("private", "hide"))) continue;?>
	                      <option value="<?=$row->id?>"><?=$row->name?> </option>
	                      <? endforeach;?>
                </select></td>
                <td width="20">&nbsp;</td>
                <td><img src="/p/transfer/beanfun/bw/img/img-08.jpg" width="64" height="25" /></td>
                <td align="right" style="background:url(/p/transfer/beanfun/bw/img/img-09.jpg) right top;"><select name="point" class="form-111" id="jumpMenu">
	                   	<? foreach($product_point as $point):?>
	                      <option value="<?=$point?>" <?=($point=='1000') ? 'selected="selected"' : ''?>><?=$point?></option>
	                    <? endforeach;?>
	                    </select>
	                </td>
              </tr>
            </table></td>
          </tr>
        </table></td>
        <td rowspan="3"><img src="/p/transfer/beanfun/bw/img/img-04.jpg" width="330" /></td>
      </tr>
      <tr>
        <td><img src="/p/transfer/beanfun/bw/img/img-05.jpg" width="442" height="78" border="0" usemap="#Map" /></td>
        </tr>
      <tr>
        <td><img src="/p/transfer/beanfun/bw/img/img-06.jpg" width="442" height="128" border="0" usemap="#Map2" /></td>
      </tr>
    </table></td>
  </tr>
</table></div>

</form>
<map name="Map" id="Map">
  <area shape="rect" coords="18,3,216,57" id="form_submit" href="javascript:;" />
  <area shape="rect" coords="237,30,326,59" href="http://tw.beanfun.com/TW/CheckLogin.aspx?Page=2" target="_blank" />
  <area shape="rect" coords="326,30,416,60" href="<?=site_url("gate/login/{$game_id}?channel=beanfun")?>" target="_blank" />
</map>

<map name="Map2" id="Map2">
  <area shape="rect" coords="269,76,344,111" href="http://tw.beanfun.com/customerservice/HelpDeskWeb/HelpDeskWeb_Gamania/frmCustomer.aspx" target="_blank" />
</map>


<script type="text/javascript" src="http://www.long_e.com.tw/p/js/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src='http://www.long_e.com.tw/p/js/jquery.blockUI.js'></script>
<script type="text/javascript">
$(function(){
	$("#form_submit").click(function(){
		$.blockUI({message: '<h3><img src="http://www.long_e.com.tw/p//p/transfer/beanfun/bw/img/icon/loading.gif" border="0"> 交易處理中，請稍候...</h3>' });		
		$('form').submit();
	});	
})
</script>

</body>
</html>
