<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/game_api.php';

class Sg extends Game_Api 
{	
    var $conf;

    function __construct()
    {    
    	parent::__construct();
    	$this->conf = $this->load_config("sg");
    }
    
    function login($server, $user, $ad)
    {
    	
    }
    
    function transfer($server, $billing, $rate=1)
    {	       
    	$sid 	= empty($server->merge_address) ? $server->address : $server->merge_address;
    	$uid	= $billing->uid;
    	$uname 	= urlencode($billing->account);
    	$lgtime = date("YmdHis",time());
    	$uip 	= $_SERVER['REMOTE_ADDR'];
    	$type 	= 'long_e';
    	$key	= $this->conf['transfer_key'];
    	$price 	= $billing->amount;
    	$point	= $billing->amount * floatval($rate);
    	    	
    	$sign = md5("uid={$uid}&uname={$uname}&serverid={$sid}&point={$point}&amount={$price}&oid={$billing->id}&time={$lgtime}&type={$type}&key={$key}");
    	$connect_url = "{$this->conf['transfer_url']}?uid={$uid}&uname={$uname}&serverid={$sid}&point={$point}&amount={$price}&oid={$billing->id}&time={$lgtime}&type={$type}&sign={$sign}";
    	

    	$maximumLoopNum = 3;  //最大測試次數
    	while ( $maximumLoopNum-- > 0 ) {
    		$re = $this->curl($connect_url);
    		if (empty($re)) sleep(1); //對方無反應，隔1秒再試
    		else break;
    	} 
    	
        if (empty($re)) {
    		return "-1";
    	}
    	else {
    		$result = strtolower(trim($re));
	    	if ($result == 'ok') {
	    		return '1';
	    	}
	    	else {
		        return $this->_return_error("失敗:{$result}");
		    }
    	}      	
    }
}