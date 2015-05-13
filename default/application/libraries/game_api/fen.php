<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/game_api.php';

class Fen extends Game_Api 
{	
    var $conf;
    var $key;

    function __construct()
    {    
    	parent::__construct();
    	//$this->conf = $this->load_config("gamexdd");
    	
    	//$this->game = strtolower(get_class($this));
    	//$this->game_conf = $this->load_config($this->game);  
    }
	
    function login($server, $user, $ad)
    {    		
    	$key = '88box@##*)32!^*"$long_e';
    	$url = 'http://www.88box.com/co/play/game/ft';
    	
    	$get = array(
    		'partner' => 'long_e',
    		'user_id' => $user->euid,
    		'server_id' => $server->address,
    		'time' => time(),
    		'ip' => '',	
    	);
    	$get['sign'] = md5($get['partner'].$get['user_id'].$get['server_id'].$get['time'].$key);    	    	
    	
    	$re = $this->curl($url.'?'.http_build_query($get));
    	$arr = explode("|", $re);

    	if ($arr[0] == "0") {
    		header("location: ".$arr[1]);
    		exit();
    	}
    	else {
    		die($re);	
    	}		
    }    
    
    function transfer($server, $billing, $rate=1)
    {	    	
    	if ( ! IN_OFFICE && $billing->amount < 50) return $this->_return_error("最少需轉50點");
    	    	
    	$key = '88box@^re^%2*(@long_e';
    	$url = 'http://www.88box.com/co/pay/game/ft';
    	
    	$user = (object) array("uid" => $billing->uid);
		$re = $this->check_role_status($server, $user);
    	if ($re == "-1") return "-1";
    	else if ($re == "0") return $this->_return_error("檢查角色失敗: {$this->error_message}");
    	    	
    	$get = array(
    		'partner' => 'long_e',
    		'user_id' => $this->CI->g_user->encode($billing->uid),
    		'server_id' => $server->address,
    		'money' => $billing->amount,
    		'gold' => $billing->amount*floatval($rate),    		
    		'order_id' => $billing->id,
    		'time' => time(),
    	); 
    	$get['sign'] = md5($get['partner'].$get['server_id'].$get['user_id'].$get['money'].$get['order_id'].$get['time'].$key);      	
    	   	
    	//log_message('error', $url.'?'.http_build_query($get));
   		$re = $this->curl($url.'?'.http_build_query($get));    	
   		
      	if ($re === '0') return '1';
	   	else if ($re == '') return '-1';
	   	else {
	   		$message = array(
	   			"1" => "驗證失敗，兌換失敗",
   				"2" => "無效的合作夥伴",
   				"4" => "無效的時間",
   				"5" => "無效的用戶",
   				"6" => "無效的遊戲",
   				"7" => "無效的伺服器",
   				"8" => "伺服器維護中",
   				"9" => "money和gold輸入不正確",
   				"10" => "訂單號重複",
   				"11" => "sign錯誤",
   				"-1" => "未知的錯誤",
	   		);	   		
	   		if (array_key_exists($re, $message)) return $this->_return_error("{$re} {$message[$re]}");
	    	else return $this->_return_error("錯誤代碼 {$re}");
	    }
    }    
    
	//判斷有無角色
    function check_role_status($server, $user)
    {    	  
    	$key = '88box@##*)32!^*"$long_e';
    	$url = 'http://www.88box.com/co/check_role/game/ft';
    	    	 	
    	$get = array(
    		'partner' => 'long_e',
    		'user_id' => $this->CI->g_user->encode($user->uid),
    		'server_id' => $server->address,	
    		'time' => time(),
    	);    
    	$get['sign'] = md5($get['partner'].$get['user_id'].$get['server_id'].$get['time'].$key);    	
    	
    	$re = $this->curl($url.'?'.http_build_query($get));
    	$arr = explode("|", $re);
    	    	
    	if (empty($arr)) return '-1';
   		else if ($arr[0] == '0') return '1'; 
   		else {   			
   			$message = array(
   				"1" => "驗證失敗",
   				"2" => "無效的合作夥伴",
   				"3" => "無效的伺服器編號",
   				"4" => "無效的時間",
   				"5" => "sign錯誤",
   				"-1" => "未知的錯誤",
   			);   	
	   		if (array_key_exists($arr[0], $message)) return $this->_return_error("{$arr[0]} {$message[$arr[0]]}");
	    	else return $this->_return_error("錯誤代碼 {$arr[0]}");
   		}
    }   
}