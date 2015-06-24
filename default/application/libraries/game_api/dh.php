<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/game_api.php';

class Dh extends Game_Api
{
    var $conf;

    function __construct()
    {    
    	parent::__construct();
    	$this->conf = $this->load_config("dh");
    }
    
    function login($server, $user, $ad)
    {
		if ($ad == 'yahoo_keyword') {
    		echo '
				<SCRIPT language="JavaScript" type="text/javascript">
				<!-- Yahoo! Taiwan Inc.
				window.ysm_customData = new Object();
				window.ysm_customData.conversion = "transId=,currency=,amount=";
				var ysm_accountid  = "1BTD6D1AU9OB45I9MM8PI735EGS";
				document.write("<SCR" + "IPT language=\'JavaScript\' type=\'text/javascript\' " 
				+ "SRC=//" + "srv1.wa.marketingsolutions.yahoo.com" + "/script/ScriptServlet" + "?aid=" + ysm_accountid 
				+ "></SCR" + "IPT>");				
    			    				
   				// -->
				</SCRIPT>   			
    		';
    	}
    	    	
    	$url = $this->get_login_url($server, $user, $ad);    	
    	header("location:{$url}");
    }
    
	function get_login_url($server, $user, $ad)
	{
		$sid = "s".strtr($server->address, array("dhcq"=>"", ".longeplay.com.tw"=>""));
    	$username = urldecode($user->account);
    	$time = time();
    	$cm = 1; //防沉迷
    	$key = $this->conf['key'];
    	$flag = md5($username . $sid . $key . $time);
    	$sp = $server->address; //平台代號
    
    	$url = "http://{$server->address}{$this->conf["login_url"]}?username=".$username."&time=".$time."&cm=".$cm."&flag=".$flag."&sp=3&sid=".$sid."&ad=".$ad;    	 
    	return  $url;    
    }
    
    function transfer($server, $billing, $rate=1)
    {	
    	if ($billing->amount < 5) return $this->_return_error("最少需轉5點");
    	
    	$key = $this->conf['transfer_key'];
    	
    	$post['server'] = "S".strtr($server->address, array("dhcq"=>"", ".longeplay.com.tw"=>""));       
      	$post['game'] = 'dhcq'; 
      	$post['agent'] = 'long_e';
      	$post['user'] = urlencode($billing->account);
     	$post['order'] = str_pad($billing->id, 18, '0', STR_PAD_LEFT);
      	$post['money'] = floatval($billing->amount*intval($rate)/10);
      	$post['time'] = time();
		$post['sign'] = md5($key.$post['time'].$post['user'].$post['game'].$post['agent'].$post['order']);
       			
		//log_message("error", $this->conf['transfer_url']);
		//log_message("error", print_r($post, true));
		
    	$maximumLoopNum = 1;  //最大測試次數
    	while ( $maximumLoopNum-- > 0 ) {
    		$re = $this->curl($this->conf['transfer_url'], $post);
    		if (empty($re)) sleep(1); //對方無反應，隔1秒再試
    		else break;
    	} 
    	//log_message("error", "dh:".$re);
    	
        if (empty($re)) {
    		return "-1";
    	}
    	else {    		
	    	if ($re == '1') {
	    		return '1';
	    	}
	    	else {
		    	return $this->_return_error("錯誤代碼 {$re}");
		    }
    	}      	
    }    
}

/* End of file Template.php */
/* Location: ./system/application/libraries/Template.php */