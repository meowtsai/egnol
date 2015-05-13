<div style="width:650px; margin:0 auto;">



  <div>
<table border="0" cellspacing="0" cellpadding="3">
          <tr>
            <td width="273"><img src="/images/points-icon-6.jpg" width="273" height="174" /></td>
            <td width="4">&nbsp;</td>    
            <td style="vertical-align:top; padding:6px;"> 

			<table width="260" border="0" cellspacing="1" cellpadding="8" bgcolor="#AAAAAA">
              <tr>
                <td align="left" valign="bottom" bgcolor="#ffffff"><table width="100%" border="0" cellpadding="0" cellspacing="0">
                  <tr>
                    <td>
                        <div>
                    	    <?php
                              if($status == 1) {
						        echo "<div>消費成功，購點完成</div>";
					          }else{
						        echo "<div>消費失敗，請洽技術人員</div>";
                              }
                            ?>
                        </div>
                    </td>
                  </tr>
                  
                  <tr>
                    <td height="10"><img src="/images/space.gif" width="10" height="10" /></td>
                  </tr>
                  <tr>
                    <td valign="top">
                    	<font class="text-orange">
                    	<?php
                    	if ($status == 1)
						{
                          	echo '本次消費金額：'.$this->input->get("price").'元，購買龍邑點數：'.$this->input->get("price").'點';
                            
                    	}
                    	echo '<div>'.$message.'</div>';
                    	?>                    	
                        </font>
                        
                    </td>
                    </tr>
                  <tr>
                    <td valign="top"><img src="/images/space.gif" width="10" height="10" /></td>
                  </tr>
                </table>                  </td>
              </tr>
            </table>            
            	
            	<div style="padding:6px;">
	            	<a href="<?=site_url("mycard/choose")?>">繼續儲值</a> | 
	                <a href="<?=site_url("wallet/choose")?>">前往轉點</a>
                </div>
           	</td>
          </tr>
        </table>
        </div>
        
  <div style="padding:12px 0;">
		<? $this->load->view("payment/_note")?>  
  </div>        
</div>            
