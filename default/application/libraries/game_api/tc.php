<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/game_api.php';

class Tc extends Game_Api 
{	
    var $conf;

    function __construct()
    {    
    	parent::__construct();
    	$this->conf = $this->load_config("tc");
    }
    
    function login($server, $user, $ad)
    {
    	
    }
    
    function transfer($server, $billing, $rate=1)
    {	       
        $ip = $server->address;
		$ordered = $billing->id;     //充值id編號
        $type='1';       //0或1無意義
        $payname = "J9THGuLqTZLKJ9m4sE";
		$passport = urlencode($billing->account); //會員帳號
		$passtype='mjhx';
		$money= $billing->amount * floatval($rate);;     //充值金額
       	$code='';     // 
		$key= $this->conf["transfer_key"];
 
		$sign=md5($ordered.$type.$payname.$billing->account.$passtype.$money.$code.$key);
		$connect_url="http://".$ip."/php/script/pay.php?p=".$ordered."|".$type."|".$payname."|".$passport."|".$passtype."|".$money."|".$code."|".$sign;
            	
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
	    	if ($result == 'pay_succ') {
	    		return '1';
	    	}
	    	else {
		        return $this->_return_error("失敗:{$result}");
		    }
    	}      	
    }
}