<div style="padding-top:30px;">

		<table style="width:500px; margin:0 auto;">
		        <tr>
                	<td colapsn="2"><span style="font-size:24px; font-weight:bold;">《<?=($status == '1' ? '成功' : '失敗')?>》</span></td>
                </tr>
                <tr> 
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
                    
</div>