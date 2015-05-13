<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/game_api.php';

class GameXdd extends Game_Api 
{	
    var $conf;
    var $key;

    function __construct()
    {    
    	parent::__construct();
    	$this->conf = $this->load_config("gamexdd");
    	
    	$this->game = strtolower(get_class($this));
    	$this->game_conf = $this->load_config($this->game);  
    }
	
    function login($server, $user, $ad)
    {    		
    	$get = array(
    		'partner' => $this->conf['partner'],
    		'game' => $this->game_conf['game'],
    		'uid' => $user->euid,
    		'email' => '',
    		'sid' => $server->address,
    		'time' => time(),
    	);
    	$get['verify'] = md5($get['partner'].$get['game'].$get['uid'].$get['email'].$get['sid'].$get['time'].$this->game_conf['key']);    	    	
    	
    	$api_url = $this->conf['login_url'];
    	if (!empty($this->game_conf['login_url'])) $api_url = $this->game_conf['login_url'];

		header("location: ".$api_url.http_build_query($get));
    }    
    
    function transfer($server, $billing, $rate=1)
    {	    	
    	//if ( ! IN_OFFICE && $billing->amount < 50) return $this->_return_error("最少需轉50點");
    	    	
    	$user = (object) array("uid" => $billing->uid);
		$re = $this->check_role_status($server, $user);
    	if ($re == "-1") return "-1";
    	else if ($re == "0") return $this->_return_error("檢查角色失敗: {$this->error_message}");
    	
    	$get = array(
    		'partner' => $this->conf['partner'],
    		'game' => $this->game_conf['game'],
    		'uid' => $this->CI->g_user->encode($billing->uid),
    		'sid' => $server->address,
    		'time' => time(),
    		'order' => $billing->id,
    		'money' => $billing->amount,
    		'coin' => $billing->amount*floatval($rate),
    	); 
    	$get['verify'] = md5($get['partner'].$get['game'].$get['uid'].$get['sid'].$get['time'].$get['order'].$get['money'].$get['coin'].$this->game_conf['key']);      	
    	   	
    	$api_url = $this->conf['transfer_url'];
    	if (!empty($this->game_conf['transfer_url'])) $api_url = $this->game_conf['transfer_url'];
    	
    	//log_message('error', $api_url.http_build_query($get));
   		$re = $this->curl($api_url.http_build_query($get));    	
   		
      	if ($re == '1') return '1';
	   	else if ($re == '') return '-1';
	   	else {
	   		$message = array(
	   			"2" => "訂單重覆",
	   			"-1" => "ip或參數錯誤",
	   			"-2" => "簽名錯誤",
	   			"-3" => "帳號未登錄過遊戲",
	   			"-4" => "連接超時",		
	   			"-5" => "系統內部錯誤",
	   		);
	   		if (array_key_exists($re, $message)) return $this->_return_error("{$re} {$message[$re]}");
	    	else return $this->_return_error("錯誤代碼 {$re}");
	    }
    }    
    
	//判斷有無角色
    function check_role_status($server, $user)
    {    	    	
    	$get = array(
    		'partner' => $this->conf['partner'],
    		'game' => $this->game_conf['game'],
    		'uid' => $this->CI->g_user->encode($user->uid),
    		'sid' => $server->address,	
    		'time' => time(),
    	);    
    	$get['verify'] = md5($get['partner'].$get['game'].$get['uid'].$get['sid'].$get['time'].$this->game_conf['key']);	
    	
    	$api_url = $this->conf['check_user_url'];
    	if (!empty($this->game_conf['check_user_url'])) $api_url = $this->game_conf['check_user_url'];
    	
    	//log_message('error', $api_url.http_build_query($get));
    	
    	$re = $this->curl($api_url.http_build_query($get));
    	$json = json_decode($re);
    	
   		if ($re == '1' || !empty($json->roleName)) return '1'; 
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