<style type="text/css">
.field {width:150px; text-align:right; padding:0 9px 0 0; display:inline-block; vertical-align:top;}
</style>

<div style="padding-top:30px;">

		<? if ($channel != 'omg'):?>
		<div class="items" style="width:651px; height:32px; line-height:32px; background-position:0 -630px; margin:0 auto 20px;">
        	<div style="padding:0 100px; font-weight:bold;">
            	您目前龍邑點數剩餘 <b style="color:red"><?=$remain?></b> 點
			</div>
		</div>   
        <? endif;?>


<? if ( ! empty($error_message)):?>
<div class="cz_msg_bar error" style="width:500px; margin:0 auto;">
	<?= $error_message?>
</div>
<? endif;?>        
        
	<div style="width:500px; margin:0 auto;">
	
		<form name="form1" id="form1" method="post" action="<?=$form_action?>">
			<input type="hidden" name="server_id" value="<?=$server_id?>">
			<input type="hidden" name="price" value="<?=$price?>">
			
			<ul>
				<li class="line_row">
					<h4 style="margin:10px;">《資料確認》</h4>
				</li>
				<li class="line_row">
					<span class="field">儲值的遊戲</span>
					<span class="line_field">
						<?=$game->name?>					
					</span>
				</li>
				<li class="line_row">
					<span class="field">儲值的伺服器</span>
					<span class="line_field">
						<label class="tip" style="font-size:18px;"><?=$server->name?></label>		
					</span>
				</li>
				<li class="line_row">
					<span class="field">轉換額度</span>
					<span class="line_field">
						<label class="tip">
	                  	<b><?=$price?> 點</b>
	                  	</label>
	                  	 = 兌換 <b><?=$price*$game->exchange_rate?> 點</b>遊戲內<?=$game->currency?>								
					</span>
				</li>
				<li class="line_row" style="text-align:center;">
					<input type="button" value="回上一頁" onclick="location.href='<?=site_url("wallet/transfer/".$server->game_id)?>'"/> 
                  	<input type="submit" value="開始轉換" <?= empty($error_message) ? '' : 'disabled="disabled"'?> title="<?= $error_message?>" />
				</li>
			</ul>			
		</form>
            
	<? $this->load->view("wallet/_note")?>

	</div>

</div>