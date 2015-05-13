<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/game_api.php';

class Yeapgame extends Game_Api 
{	
    var $conf;
    var $key;

    function __construct()
    {    
    	parent::__construct();
    	$this->conf = $this->load_config("yeapgame");
    	
    	$this->game = strtolower(get_class($this));
    	$this->game_conf = $this->load_config($this->game);  
    }
	
    function login($server, $user, $ad)
    {    		
    	//http://admin.hg.yeapgame.com/9998.html?gw=1&time=1411465333&uid=207336&sid=9998&mutil=6&sign=d255e42875a6ae4cc10b0cf11a9da5a1
    	
    	$get = array(
    		'gw' => '1',
    		'time' => time(),
    		'uid' => $user->euid,
    		'sid' => $server->address,
    		'uf' => 'rc',	
    		'mutil' => '6',
    		'op' => 'yeapgame',
    	);
    	//log_message('error', $get['gw'].$get['time'].$get['uid'].$get['sid'].$get['uf'].$this->game_conf['key']);
    	$get['sign'] = md5($get['gw'].$get['uid'].$get['time'].$this->game_conf['key']);    	    	
    	
    	$api_url = "http://admin.hg.yeapgame.com/".$server->address.".html?".http_build_query($get);
    	//if (!empty($this->game_conf['login_url'])) $api_url = $this->game_conf['login_url'];
    	
    	//log_message('error', $api_url);
    	
		header("location: ".$api_url);
    }    
    
    function transfer($server, $billing, $rate=1)
    {	    	
    	//if ( ! IN_OFFICE && $billing->amount < 50) return $this->_return_error("最少需轉50點");
    	    	
    	$user = (object) array("uid" => $billing->uid);
		$re = $this->check_role_status($server, $user);
    	if ($re == "-1") return "-1";
    	else if ($re == "0") return $this->_return_error("檢查角色失敗: {$this->error_message}");
    	
    	$get = array(
    		'op' => 'charge',
    		'server_id' => $server->address,
    		'account' => $this->CI->g_user->encode($billing->uid),
			'order_form' => $billing->id,
    		'amount' => $billing->amount*intval($rate),
    		'type' => '1',
    		'time' => time(),
    		'money' => $billing->amount,
    	); 
    	
    	$sign_arr = $get;
    	unset($sign_arr['money']);
    	$sign_arr['key'] = $this->game_conf['key']; 

    	//log_message('error', http_build_query($sign_arr));
    	$get['sign'] = md5(http_build_query($sign_arr));      	
    	   	
    	$api_url = "http://s".$server->address."-hg.yeapgame.com/pay?".http_build_query($get);
    	//if (!empty($this->game_conf['transfer_url'])) $api_url = $this->game_conf['transfer_url'];
    	
    	//log_message('error', $api_url);
    	
   		$re = $this->curl($api_url);    	
   		
      	if ($re == '1') return '1';
	   	else if ($re == '') return '-1';
	   	else {
	   		$message = array(
	   			"10" => "無效伺服器編號",
	   			"11" => "無效玩家帳號",
	   			"12" => "無效訂單號",
	   			"13" => "沖值金額錯誤",
	   			"14" => "校驗碼錯誤",
	   			"15" => "其他錯誤",		
	   		);
	   		if (array_key_exists($re, $message)) return $this->_return_error("{$re} {$message[$re]}");
	    	else return $this->_return_error("錯誤代碼 {$re}");
	    }
    }    
    
	//判斷有無角色
    function check_role_status($server, $user)
    {    	    	    	
    	$get = array(
    		'uid' => $this->CI->g_user->encode($user->uid),
    		'sid' => $server->address,	
    	);    
    	$get['sign'] = md5($get['uid'].$get['sid'].$this->game_conf['key']);	
    	
    	$api_url = "http://s".$server->address."-hg.yeapgame.com/active?";
    	//if (!empty($this->game_conf['check_user_url'])) $api_url = $this->game_conf['check_user_url'];
    	
    	$re = $this->curl($api_url.http_build_query($get));
    	    	
    	//log_message('error', $api_url.http_build_query($get));
    	//log_message('error', print_r($re, true)); 
    	
   		if ($re === '0') return '1'; 
   		else if ($re == '') return '-1';
   		else {   			
   			$message = array(
   				"5" => "該帳號無創立角色"
   			);   			
	   		if (array_key_exists($re, $message)) return $this->_return_error("{$re} {$message[$re]}");
	    	else return $this->_return_error("錯誤代碼 {$re}");
   		}
    }   
}