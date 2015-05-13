<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/kunlun.php';

class Sg2 extends Kunlun 
{	
	function load_game_conf() //override
	{
		return $this->load_config("sg2");
	}
	
    function login($server, $user, $ad='') //override
    {	
    	$login_url = $this->get_login_url($server, $user, $ad);
    	
        if ($ad == 'yahoo_keyword') {
    		die('
    			<html><head>
				<SCRIPT language="JavaScript" type="text/javascript">
				<!-- Yahoo! Taiwan Inc.
				window.ysm_customData = new Object();
				window.ysm_customData.conversion = "transId=,currency=,amount=";
				var ysm_accountid  = "1K6N1TQSA1KSTKP6RP5ACN8EJ1C";
				document.write("<SCR" + "IPT language=\'JavaScript\' type=\'text/javascript\' " 
				+ "SRC=//" + "srv1.wa.marketingsolutions.yahoo.com" + "/script/ScriptServlet" + "?aid=" + ysm_accountid 
				+ "></SCR" + "IPT>");				
    			
         		setTimeout(function() {location.href="'.$login_url.'";}, 800);    				
   				// -->
				</SCRIPT>   
    			</head><body></body></html> 				
    		');
    	}
    	else if ($ad == 'scupio') {
    		die('
    			<html><head>
				<script type="text/javascript" src="//rec.scupio.com/recweb/js/rec.js">
				{"mid":5455,"pid":"landing"}</script>
				<script type="text/javascript"> 
				var _bwp=193; var _bwpid=\'landing\'; </script>
				<script type="text/javascript" src="//adsense.scupio.com/conv/js/conv.js"></script>				
				<script type="text/javascript"> 
				var _bwp=193; var _bwpid2=\'buy\'; </script>
				<script type="text/javascript" src="//adsense.scupio.com/conv/js/convbtn.js"></script>    				
    			<script type="text/javascript">
    			bw_conv();
         		setTimeout(function() {location.href="'.$login_url.'";}, 800);    				    				    				
				</script>   
    			</head><body></body></html> 				
    		');    		
    	}
    	
    	header('LOCATION:'.$login_url);
    	exit();
    }
    
    function transfer($server_row, $billing_row, $rate=1)
    {
    	//return; //暫不開放
    	return parent::transfer($server_row, $billing_row, $rate);    		
    }
}