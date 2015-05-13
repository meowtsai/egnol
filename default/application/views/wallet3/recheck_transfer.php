<? if ( ! empty($error_message)):?>
<div class="cz_msg_bar error">
	<?= $error_message?>
</div>
<? endif;?>

		<form name="form1" id="form1" method="post" action="<?=$form_action?>">
			<input type="hidden" name="server_id" value="<?=$server_id?>">
			<input type="hidden" name="price" value="<?=$price?>">
			
              <table width="80%" cellpadding="3" align="center">
                <tr>
                	<td colapsn="3"><h2>《資料確認》</h2></td>
                </tr>
                <tr> 
                  <td width="35%" rowspan="7" valign="top">
                  	<? if ($game->logo_path):?>
                  	<div style="width:230px; padding:2px;">
                    	<img src="<?=site_url($game->logo_path)?>" width="100%"/>
                    </div>
                    <? endif;?>
                  </td>
                  <td width="17%"><div align="right">龍邑帳號：</div></td>
                  <td width="48%" align="left"><?=$this->g_user->account?></td>
                </tr>
				<? if ($channel != 'omg'):?>
                <tr> 
                  <td><div align="right">錢包餘額：</div></td>
                  <td align="left"><?=$remain?></td>
                </tr>            
                <? endif;?>
                <tr> 
                  <td><div align="right">轉入伺服器：</div></td>
                  <td align="left"><label class="tip" style="font-size:18px;"><?=$server->name?></label></td>
                </tr>
                <tr> 
                  <td valign="top"> <div align="right">轉換額度：</div></td>
                  <td align="left"> 
                  	<label class="tip">
                  	<b><?=$price?> 點</b>
                  	</label>
                  	 = 兌換 <b><?=$price*$game->exchange_rate?> 點</b>遊戲內<?=$game->currency?>
                    </td>
                </tr>
                <tr> 
                  <td>&nbsp;</td>
                  <td align="right"> 
                  	<input type="button" value="回上一頁" onclick="location.href='<?=site_url("wallet/transfer/".$server->game_id)?>'"/> 
                  	<input type="submit" value="開始轉換" <?= empty($error_message) ? '' : 'disabled="disabled"'?> title="<?= $error_message?>" /> 
                  
                  </td>
                </tr>
              </table>
		</form>
            
<? $this->load->view("wallet/_note", array("exchange_rate"=>$game->exchange_rate))?>


<map name="Map" id="Map">
  <area shape="rect" coords="4,5,80,22" href="/payment" />
</map>