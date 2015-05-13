<div style="padding-top:30px;">
		<form name="form1" id="form1" method="post" action="<?=site_url("wallet/recheck_transfer")?>">
			<input type="hidden" name="channel" value="<?=$channel?>">
              <table width="100%" cellpadding="3" align="center">
                <tr>
                	<td colapsn="3"></td>
                </tr>
                <tr> 
                  <td width="35%" rowspan="7" valign="top">
                   	<img src="/p/img/logo/<?=$game->game_id?>.png" style="width:200px; vertical-align:top;"/>
                  </td>
                  <td width="17%"><div align="right">龍邑帳號：</div></td>
                  <td width="48%" align="left"><?=$this->g_user->display_account()?></td>
                </tr>
                <? if ($channel != 'omg'):?>
                <tr> 
                  <td><div align="right">錢包餘額：</div></td>
                  <td align="left"><?=$remain?></td>
                </tr>            
                <? endif;?>
                <tr> 
                  <td><div align="right">選擇伺服器：</div></td>
                  <td align="left">
                    <select name="server_id" class="required">
                      <option selected="selected" value="">請選擇伺服器</option>
	                    <? foreach($game_list->result() as $row): 
	                    	if ( IN_OFFICE == false && in_array($row->server_status, array("private", "hide"))) continue;
	                    ?>
                      <option value="<?=$row->id?>"><?=$row->name?></option>
                    <? endforeach;?>
                    </select> </td>
                </tr>
                <tr> 
                  <td valign="top"> <div align="right">兌換額度：</div></td>
                  <td align="left"> 
                    <select name="price" id="price" class="required">
                      <option value="">選擇轉點金額</option>
                      <? foreach($product_point as $price):?>
                        <option value="<?=$price?>"><?=$price?></option>
                      <? endforeach;?>
                    </select><br />
                   	<label class="tip">提示： <b>1 點</b><?=$channel=="omg" ? "O幣" : "龍邑點數"?> = <b><?=$game->exchange_rate?> 點</b>遊戲內<?=$game->currency?></label>
                    </td>
                </tr>
                <!--
                <tr> 
                  <td><div align="right">驗證碼：</div></td>
                  <td>&nbsp;</td>
                </tr>
                -->
                <tr> 
                  <td>&nbsp;</td>
                  <td align="right"><input type="submit" value="開始轉換" /> </td>
                </tr>
              </table>
		</form>
            
<? $this->load->view("wallet/_note", array("exchange_rate"=>$game->exchange_rate))?>

</div>