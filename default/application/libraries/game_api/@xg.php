<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/game_api.php';

class Xg extends Game_Api 
{	
    var $conf;
    var $key;

    function __construct()
    {    
    	parent::__construct();
    	//$this->conf = $this->load_config(strtolower(get_class()));
    	
    	$this->key = 'ee5a1f09e64ca3bf8b8276f0d2eb7b0f';
    }
	
    function login($server, $user, $ad)
    {    		
    	$api_url = 'http://www.179game.com/partner/login/';

    	$get = array(
    		'partner' => 'COOZ',
    		'game' => 'RR',
    		'uid' => $user->euid,
    		'email' => '',
    		'sid' => $server->address,
    		'time' => time(),
    	);
    	$get['verify'] = md5($get['partner'].$get['game'].$get['uid'].$get['email'].$get['sid'].$get['time'].$this->key);    	    	
    	
		header("location: ".$api_url.'?'.http_build_query($get));
    }    
    
    function transfer($server, $billing, $rate=1)
    {	
    	if ( ! IN_OFFICE && $billing->amount < 50) return $this->_return_error("最少需轉50點");
    	    	
    	$user = (object) array("uid" => $billing->uid);
		$re = $this->check_role_status($server, $user);
    	if ($re == "-1") return "-1";
    	else if ($re == "0") return $this->_return_error("檢查角色失敗: {$this->error_message}");
    	
    	$api_url = 'http://www.179game.com/partner/recharge/';

    	$get = array(
    		'partner' => 'COOZ',
    		'game' => 'RR',
    		'uid' => $this->CI->g_user->encode($billing->uid),
    		'sid' => $server->address,
    		'time' => time(),
    		'order' => $billing->id,
    		'money' => $billing->amount*intval($rate),
    		'coin' => $billing->amount*intval($rate),
    	); 
    	$get['verify'] = md5($get['partner'].$get['game'].$get['uid'].$get['sid'].$get['time'].$get['order'].$get['money'].$get['coin'].$this->key);      	
    	   	
   		$re = $this->curl($api_url.'?'.http_build_query($get));    	
   		
      	if ($re == '1') return '1';
	   	else if ($re == '') return '-1';
	   	else {
	   		$message = array(
	   			"2" => "訂單重覆",
	   			"-1" => "ip或參數錯誤",
	   			"-2" => "簽名錯誤",
	   			"-3" => "帳號未登錄過遊戲",
	   			"-4" => "連接超時",		
	   		);
	   		if (array_key_exists($re, $message)) return $this->_return_error("{$re} {$message[$re]}");
	    	else return $this->_return_error("錯誤代碼 {$re}");
	    }
    }    
    
	//判斷有無角色
    function check_role_status($server, $user)
    {    	
    	$api_url = 'http://www.179game.com/partner/checkrole/';
    	
    	$get = array(
    		'partner' => 'COOZ',
    		'game' => 'RR',
    		'uid' => $this->CI->g_user->encode($user->uid),
    		'sid' => $server->address,	
    		'time' => time(),
    	);    
    	$get['verify'] = md5($get['partner'].$get['game'].$get['uid'].$get['sid'].$get['time'].$this->key);	
    	
    	$re = $this->curl($api_url.'?'.http_build_query($get));

   		if ($re == '1') return '1';
   		else if ($re == '') return '-1';
   		else {   			
   			$message = array(
   				"0" => "帳戶角色不存在",
   				"-1" => "簽名錯誤",
   				"-2" => "參數錯誤",
   				"-3" => "驗證過期",
   				"-4" => "系統內部錯誤",
   			);   			
	   		if (array_key_exists($re, $message)) return $this->_return_error("{$re} {$message[$re]}");
	    	else return $this->_return_error("錯誤代碼 {$re}");
   		}
    }   
}