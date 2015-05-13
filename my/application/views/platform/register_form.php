<style>
#register_form td {text-align:left;}
</style>

<form id="register_form" method="post" action="<?=site_url("platform/register_json")?>">

<table width="722" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="488" valign="top" background="/p/img/regest_bk.png"><table width="738" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="193" height="180">&nbsp;</td>
        <td width="545" height="180">&nbsp;</td>
      </tr>
      <tr>
        <td height="50">&nbsp;</td>
        <td height="50">
        	<input type="text" name="account" size="24" maxlength="35" class="required" minlength="6" style="width:250px; height:30px;" value="" />
       	</td>
      </tr>
      <tr>
        <td height="60">&nbsp;</td>
        <td height="60">
        	<input type="password" name="pwd" size="24" maxlength="35" class="required" value="" style="width:250px; height:30px;" />
        </td>
      </tr>
      <tr>
        <td height="60">&nbsp;</td>
        <td height="60">
        	<input type="password" name="pwd2" size="24" maxlength="35" class="required" value="" style="width:250px; height:30px;"/>
		</td>
      </tr>
      <tr>
        <td height="50">&nbsp;</td>
        <td height="50">
        	<input type="text" name="captcha" size="5" maxlength="4" minlength="4" class="form-1 required" value="" style="width:125px; height:30px;" />
			<span style="display:inline-block; margin-left:38px;"><div id="captcha_area"></div></span>
        </td>
      </tr>
      <tr>
        <td height="20" colspan="2">&nbsp;</td>
        </tr>
      <tr>
        <td height="50" valign="middle">&nbsp;</td>
        <td height="50" valign="middle">
        	<a href="javascript:;" onclick="$('#register_form').submit()"><img src="/p/img/goin_game.png" name="goingame" width="263" height="98" border="0" id="goingame" onmouseover="MM_swapImage('goingame','','/p/img/goin_game_2.png',1)" onmouseout="MM_swapImgRestore()" /></a>
        </td>
        </tr>
    </table></td>
  </tr>
</table>

</form>