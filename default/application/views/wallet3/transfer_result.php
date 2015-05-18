

		<table width="80%" cellpadding="3" align="center">
		        <tr>
                	<td colapsn="3"><h2>《<?=($status == '1' ? '成功' : '失敗')?>》</h2></td>
                </tr>
                <tr> 
                  <td width="35%" rowspan="7" valign="top">
                  	<? if ( ! empty($game) && $game->logo_path):?>
                  	<div style="width:230px; padding:2px;">
                    	<img src="<?=site_url($game->logo_path)?>" width="100%"/>
                    </div>
                    <? endif;?>
                  </td>
                  <td colspan="2"><div style="padding: 0 6px; font-size:15px;"><?=$message?></div></td>
                </tr>
                <tr> 
                  <td>&nbsp;</td>
                  <td align="right" style=" font-size:15px;"> 
                  	<? if ( ! empty($server)):?>
                  	<a href="<?=site_url("wallet/transfer/".$server->game_id)?>"> 繼續轉點 </a> |
                  	<a href="<?=site_url("play_game?sid=".$server->server_id)?>" target="_blank">進遊戲</a>
                  	<? endif;?>
                  </td>
                </tr>
              </table>
              
<map name="Map" id="Map">
  <area shape="rect" coords="4,5,80,22" href="/payment" />
</map>              