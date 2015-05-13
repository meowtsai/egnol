<? if ( ! $this->input->is_ajax_request()):?>
<style type="text/css">
body {background:#000;}
</style>
<? endif;?>

<div id="choose_server_form">

<div style="background:url(/p/img/server_bg.png); width:750px; height:642px; position:relative;">

<table width="722" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="488" valign="top" background="/p/img/sever_select.png"><table width="620" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td height="178" colspan="2">&nbsp;</td>
      </tr>
      <tr>
        <td height="63" colspan="2" align="center" valign="middle">
        	<? if ($this->server['new']): 
				if ($url = chk_server_open($this->server['new'])):?>
        	<a href="<?=$url?>" target="_top"><img src="/p/img/server/<?=$this->server['new']->server_id?>n.png"/></a>
        		<? endif;
        	endif;?>
      </tr>
      <tr>
        <td height="37" colspan="2">&nbsp;</td>
      </tr>
      <tr>
        <td height="170" colspan="2" align="center" valign="top">
        	<div style="padding:0 80px 0 40px">
	        <? foreach($this->server['list'] as $row): 
	               if ($url = chk_server_open($row)):?>
	        <div style="float:left; margin:5px 7px;">
	        	<a href="<?=$url?>" target="_self"><img src="/p/img/server/<?=$row->server_id?>_off.png" class="change"/></a>
	        </div>
	        <? endif; 
	      	endforeach;?>	
	      	</div>
		</td>
      </tr>
    </table></td>
  </tr>
</table>

</div>