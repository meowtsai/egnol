<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/game_api.php';

class Xf extends Game_Api
{    
    var $conf;

    function __construct()
    {    
    	parent::__construct();
    	$this->conf = $this->load_config("xf");    	
    }
    
    function login($server, $user, $ad)
    {	    	    	
    	$key = $this->conf['platform_key'];
    	$uid = $user->uid;
    	$md5 = md5($this->conf['key01'] + $key + $uid + $this->conf['key02']);
    	$verify_url = sprintf($this->conf['verify_url'], $server->address, $key, $uid, $md5)."&advertise={$ad}";
    	//die($verify_url);    	
    	$code = $this->curl($verify_url);
   	
    	if (substr($code, 0, 3 ) == "\xEF\xBB\xBF") $code = substr_replace($code, '', 0, 3); //去bom頭
    	
//      	if (empty($code)) die("未取得驗證碼");
//       	echo "請求網址：".$verify_url."<br>回傳：";    	
//       	var_dump($code);
//       	exit();
    	
    	//channel,advertise,website,ip
    	$mapping = array(
    		"203.75.245.56" => "s0.xf.longeplay.com.tw",
    		"203.75.245.53" => "s1.xf.longeplay.com.tw",
    		"203.75.245.55" => "s2.xf.longeplay.com.tw",
    		"203.75.245.57" => "s3.xf.longeplay.com.tw",
    	);
    	if ( ! array_key_exists($server->address, $mapping)) {
    		die('伺服器未設定');
    	}
    	
    	$login_url = sprintf($this->conf['login_url'], $mapping[$server->address], $key, $uid, $code)."&advertise={$ad}";

        if ($ad == 'yahoo_keyword') {
    		die('
    			<html><head>
				<SCRIPT language="JavaScript" type="text/javascript">
				<!-- Yahoo! Taiwan Inc.
				window.ysm_customData = new Object();
				window.ysm_customData.conversion = "transId=,currency=,amount=";
				var ysm_accountid  = "10B83APLI63G03NECMAR8C15S3G";
				document.write("<SCR" + "IPT language=\'JavaScript\' type=\'text/javascript\' " 
				+ "SRC=//" + "srv1.wa.marketingsolutions.yahoo.com" + "/script/ScriptServlet" + "?aid=" + ysm_accountid 
				+ "></SCR" + "IPT>");				
    			
         		setTimeout(function() {location.href="'.$login_url.'";}, 800);
    				    				
   				// -->
				</SCRIPT>   
    			</head><body></body></html> 				
    		');
    	}    	
    	
    	header('LOCATION:'.$login_url);
    	exit();
    }
    
    function transfer($server_row, $billing_row, $rate=1)
    {		
    	if ($billing_row->amount < 10) return $this->_return_error("最少需轉10點");		 
		 
		$flag	= $billing_row->id;
		$uid	= $billing_row->uid;		
		$money	= $billing_row->amount * intval($rate) / 10; //money與遊戲內元寶比值為1:10，除10可將比值調回1:1;
		$key 	= $this->conf['platform_key'];
		$sk		= md5($this->conf['key01']+$key+$uid+$flag+$this->conf['key02']);
		
		$transfer_url = sprintf($this->conf['transfer_url'], $server_row->address, $key, $uid, $money, $flag, $sk);
		//die($transfer_url);
		
        $maximumLoopNum = 3;
        while ($maximumLoopNum-- > 0) {        	
        	$re = $this->curl($transfer_url);		
    		if (empty($re)) sleep(1); //對方無反應，隔3秒再試
    		else break;
        }   
        
        if (empty($re)) {
        	return "-1";
    	}
    	else {
			if (substr($re, 0, 3 ) == "\xEF\xBB\xBF") $re = substr_replace($re, '', 0, 3); //去bom頭
    	    		
    		if ($re == 'ok') {
    			return "1";
    		}
    		else {
    			return $this->_return_error($re);
	    	}
    	}
    }
    
}

/* End of file Template.php */
/* Location: ./system/application/libraries/Template.php */