<div style="width:650px; margin:0 auto;">

  <div style="padding:12px 0;">
	<table border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td><img src="/images/points-img-21-on.jpg" width="152" height="77" /></td>
            <td><img src="/images/points-img-25-on.jpg" width="143" height="77" /></td>
            <td><img src="/images/points-img-26.jpg" width="147" height="77" /></td>
            <td width="160">&nbsp;</td>            
          </tr>
        </table>
  </div>

  <div>
  
<form name="form1" id="form1" method="post" action="/mycard/confirm">
  	<input type="hidden" name="authCode" value="<?=$authCode?>">
  	<input type="hidden" name="trade_seq" value="<?=$trade_seq?>">
  	
  	
  	<div style="float:left; margin-right:20px;">
  		<img src="/images/points-icon-5.jpg" width="273" height="174" style="vertical-align:top;" />
  	</div>
	<table style="width:350px;">
          <tr>   
            <td align="left" valign="middle"> 
                  <table width="100%" border="0" cellpadding="8" cellspacing="1" bgcolor="#AAAAAA">
                    <tr>
                      <td align="left" valign="middle" bgcolor="#ffffff">

                          <table width="100%" height="100%" border="0" cellpadding="3" cellspacing="0">
                            <tr>
                                <td colspan="2">
                                    <div>
                              			您現在所即將進行儲值的帳號為 <br>
                                    	<b><?=$this->g_user->account?></b><br>
                                    	尚有龍邑點數 <b><?=$remain?></b>。
                                    </div>
                                </td>
                            </tr>
                            <tr> 
                              <td colspan="2"><div>使用 MyCard 序號購買點數：</div></td>
                            </tr>
                            <tr> 
                              <td align="right" style="width:80px;">輸入序號：</td>
                              <td><input type="text" name="cardId" value="" style="width:142px;" class="{required:true,minlength:16,messages:{minlength:'序號為16碼'}}" maxlength="16" /><br>
                              </td>
                            </tr>
                            <tr> 
                              <td align="right">輸入密碼：</td>
                              <td><input type="text" name="cardPwd" value="" style="width:142px;" class="{required:true,minlength:16,messages:{minlength:'密碼為16碼'}}" maxlength="16" /> </td>
                            </tr>
                            <tr> 
                              <td align="right">&nbsp;</td>
                              <td align="right">
                              	<input name="y" width="107" type="image" height="25" border="0" src="/images/points-icon-5.gif">
                            	</td>
                            </tr>
                          </table>
                        </td>
	              </tr>
	            </table>
            </td>
          </tr>
        </table>
</form>        
</div>
        
  <div style="padding:12px 0;">
	<table width="100%" border="0" align="center" cellpadding="3" cellspacing="0">
              <tr> 
                <td colspan="2" bgcolor="#EEEEEE"><strong>注意事項：</strong></td>
              </tr>
              <tr> 
                <td width="3%" valign="top"><img src="/images/ball-blue.gif" width="13" height="13" /></td>
                <td width="97%" valign="top">您所購買的點數與新台幣兌換幣值為1：1，購買之點數僅能在該帳號中使用，無法轉換。</td>
              </tr>
              <tr> 
                <td valign="top"><img src="/images/ball-gray.gif" width="13" height="13" /></td>
                <td valign="top">由於安全性考量，使用『帳單出帳』的小額付款機制皆有『消費金額上限』，請特別注意。</td>
              </tr>
              <tr> 
                <td valign="top"><img src="/images/ball-blue.gif" width="13" height="13" /></td>
                <td valign="top">一經儲值至所指定之遊戲帳號後，即表示您已經確認本服務所處理之結果，本站無法進行回復或退費等動作，而且被儲值點數之權利 
                  義務適用該遊戲之所有相關規定。</td>
              </tr>
              <tr> 
                <td valign="top"><img src="/images/ball-gray.gif" width="13" height="13" /></td>
                <td valign="top">若您於本服務中與他人為任何交易或互易行為，因此而產生的糾紛，本站恕不負責，亦無法進行回復或退費等動作。</td>
              </tr>
            </table>        
  </div>            
</div>            
