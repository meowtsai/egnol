<style type="text/css">
.field {width:140px; font-size:17px; font-weight:bold; text-align:center; padding:0 9px 0 0; display:inline-block; vertical-align:top;}
.line_field {margin:6px 0; padding:2px 30px; color:red; font-size:15px; font-weight:bold;}
</style>

<div style="margin:50px auto; width:580px;">

	<div class="line_row" style="border-top:1px solid #cacaca;">
		<span class="field" style="height:60px; line-height:60px;">儲值結果</span>
		<span class="line_field" style="height:44px; max-width:350px;">
                    	    <?php
                              if($status == 1) {
						        echo "<div>消費成功，購點完成：</div>";
					          }else{
						        echo "<div>消費失敗，請洽技術人員：</div>";
                              }
                            ?>			
                    	<?php
                    	if ($status == 1)
						{
                          	echo '<font class="text-orange">本次消費金額：'.$this->input->get("price").'元，購買點數：'.$this->input->get("price").'點 </font>';
                            
                    	}
                    	echo '<div>'.$message.'</div>';
                    	?>                              
		</span>
	</div>
	<div style="text-align:right; padding-top:30px;">
		<a href="<?=site_url("payment")?>">我要儲值</a> |
		<a href="<?=site_url("wallet/transfer")?>">我要轉點</a>
		<? if ($this->input->get("sid")):?>
		 | <a href="<?=site_url("play_game?sid={$this->input->get("sid")}")?>">進入遊戲</a>
		<? endif;?>
	</div>
        
  <div style="padding:12px 0;">
		<? $this->load->view("payment/_note")?>  
  </div>        
</div>            