<div style="width:650px; margin:0 auto;">

  <div style="padding:12px 0;">
	<table border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td><img src="/images/points-img-21-on.jpg" width="152" height="77" /></td>
            <td><img src="/images/points-img-22-on.jpg" width="140" height="77" /></td>
            <td><img src="/images/points-img-23.jpg" width="143" height="77" /></td>
            <td><img src="/images/points-img-24.jpg" width="147" height="77" /></td>
          </tr>
        </table>
  </div>

  <div>
  
<form name="form1" id="form1" method="post" action="/mycard/long_e_auth.php">

<table border="0" cellspacing="0" cellpadding="3">
          <tr>
            <td width="273"><img src="/images/points-icon-6.jpg" width="273" height="174" /></td>
            <td width="4">&nbsp;</td>    
            <td width="354" align="left" valign="middle"> 
                  <table width="355" border="0" cellpadding="8" cellspacing="1" bgcolor="#AAAAAA">
                    <tr>
                      <td width="268" align="left" valign="middle" bgcolor="#ffffff"> 
                        
                          <table width="100%" height="100%" border="0" cellpadding="3" cellspacing="0">
                            <tr>
                                <td colspan="3">
                                    	您現在所即將進行儲值的帳號為 <br>
                                    	<b><?=$this->g_user->account?></b><br>
                                    	尚有龍邑點數 <b><?=$remain?></b>。
                                </td>
                            </tr>
                            <tr> 
                              <td colspan="3"><div>使用 MyCard 機制購買點數：</div></td>
                            </tr>
                            <tr>
                              <td align="right" width="110">1.選擇購買金額： </td>
                              <td valign="top">
                              	<select name="payment_amount" id="payment_amount" class="form-1 required">
                                  <?php
                                    foreach($price as $key => $value) {
										if ($key == '1000') $selected=' selected="selected" ';
										else $selected = '';										
                                        echo "<option value='{$key}' {$selected}>{$value}</option>";
                                    }
                                  ?>
                                </select>
                              </td>
                            </tr>
                            <tr>
                              <td align="right">2.選擇購點方式：</td>
                              <td colspan="2">
                              	<select name="pay_kind" id="pay_kind" class="form-1 required">
                                  <option value="" selected="selected"> --選擇購點方式-- </option>
                                  <?php 
                                      if(!empty($p)) {
                                        foreach($aryCPPayment as $key => $value) {
											//if (trim($value[1])=='中國信託實體ATM') continue;
                                            echo "<option value='{$value[0]}'> {$value[1]} </option>";
                                        }
                                      }
                                  ?>
                                </select>
                              </td>
                            </tr>
                            <tr> 
                              <!--
                              <td align="right">選擇購買金額： </td>
                              <td valign="top">
                              	<select name="payment_amount" id="payment_amount" class="form-1">
                                  <option value="" selected="selected"> -額度- </option>
                                  <?php
                                    foreach($aryData['products'] as $key => $value) {
                                        echo "<option value='{$value}'>{$value}</option>";
                                    }
                                  ?>
                                </select>
                              </td>
                              -->
                              <td valign="top" align="right" colspan="3">
                              	<input width="107" type="image" height="25" border="0" src="/images/points-icon-5.gif">
                              </td>
                            </tr>
                          </table>
                        </td>
              </tr>
            </table></td>
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
