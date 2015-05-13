<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/kingnet.php';

class Yh extends Kingnet 
{	
	function load_game_conf() //override
	{
		return $this->load_config("yh");
	}
	
    function login($server, $user, $ad)
    {
    	$login_url = $this->_get_login_url($server, $user, $ad);
    	
		/*if ($ad == 'yahoo_keyword') {
    		die('
<SCRIPT language="JavaScript" type="text/javascript">
<!-- Yahoo! Taiwan Inc.
window.ysm_customData = new Object();
window.ysm_customData.conversion = "transId=,currency=,amount=";
var ysm_accountid  = "117SKPGABGHH3BQKH70P1SJQUQK";
document.write("<SCR" + "IPT language=\'JavaScript\' type=\'text/javascript\' " 
+ "SRC=//" + "srv1.wa.marketingsolutions.yahoo.com" + "/script/ScriptServlet" + "?aid=" + ysm_accountid 
+ "></SCR" + "IPT>");
    				
    				setTimeout(function() {location.href="'.$login_url.'";}, 800);    
// -->
</SCRIPT>				
    		');
    	}   */
    	
    	header("location: {$login_url}");
    	exit(); 	
    }
	
}