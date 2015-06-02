<style type="text/css">
.games a {display:inline-block; width:200px; height:160px; line-height:160px; overflow:hidden; vertical-align:top;}
.games img {width:100%;  vertical-align:middle;}
</style>

<a href="/wallet/guide" target="_blank"><strong>必讀：購點 / 轉點流程說明</strong></a>

<table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr> 
          <td >
              <table width="100%" border="0" cellspacing="0" cellpadding="0"> 
              <tr> 
                <td height="35" colspan="5" align="left"><strong>請先選擇遊戲項目：</strong></td> 
              </tr>
              <tr>
              	<td colspan="5" class="games">

              	<? 
              	foreach($games->result() as $game):
						$url = "http://".base_url()."/wallet/transfer/{$game->game_id}";
              	?>              		
              		<a href="<?=$url?>">
              			<span class="item">
              				<img src="/p/img/logo/<?=$game->game_id?>.png" border="0" />
              			</span>
              		</a>
              		
              	<? endforeach;?>   
              	
              	</td>
              </tr>              
              
            </table>
          </td>
        </tr>
        <tr> 
          <td bgcolor="#FFFFFF">&nbsp;</td>
        </tr>
        <tr> 
          <td valign="middle" bgcolor="#FFFFFF"><table width="100%" border="0" align="center" cellpadding="3" cellspacing="0">
              <tr> 
                <td colspan="2" bgcolor="#EEEEEE"><strong>注意事項：</strong></td>
              </tr>
              <!--
              <tr> 
                <td width="3%" valign="top"><img src="img/ball-blue.gif" width="13" height="13" /></td>
                <td width="97%" valign="top">您所購買的點數與新台幣兌換幣值為1：1，購買之點數僅能在該帳號中使用，無法轉換。</td>
              </tr>
              -->
              <tr> 
                <td valign="top"><img src="/img/ball-gray.gif" width="13" height="13" /></td>
                <td valign="top">由於安全性考量，使用『帳單出帳』的小額付款機制皆有『消費金額上限』，請特別注意。</td>
              </tr>
              <tr> 
                <td valign="top"><img src="/img/ball-blue.gif" width="13" height="13" /></td>
                <td valign="top">一經儲值至所指定之伺服器及遊戲帳號後，即表示您已經確認本服務所處理之結果，本站無法進行回復或退費等動作，而且被儲值點數之權利 
                  義務適用該遊戲之所有相關規定。</td>
              </tr>
              <tr> 
                <td valign="top"><img src="/img/ball-gray.gif" width="13" height="13" /></td>
                <td valign="top">若您於本服務中與他人為任何交易或互易行為，因此而產生的糾紛，本站恕不負責，亦無法進行回復或退費等動作。</td>
              </tr>
            </table></td>
        </tr>
      </table>