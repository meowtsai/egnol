<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/game_api.php';

class Dxc extends Game_Api 
{	
    var $conf;
    var $key;

    function __construct()
    {    
    	parent::__construct();
    	//$this->conf = $this->load_config(strtolower(get_class()));
    	
    	$this->key = 'istar@#$corp#%long_e';
    }
	
    function login($server, $user, $ad)
    {    		
    	$api_url = 'http://www.870.com/api/user/openid/long_e/action.php';

    	$get = array(
    		'userid' => $user->euid,
    		'server_id' => $server->address,    				
    		'gamecode' => 'dxc',    			    		
    		'time' => time(),    		
    	);
    	$get['signmsg'] = md5($get['userid'].$get['server_id'].$this->key.$get['time']);    	
		header("location: ".$api_url.'?'.http_build_query($get));
    }    
    
    function transfer($server, $billing, $rate=1)
    {	
    	if ($billing->amount < 1) return $this->_return_error("最少需轉50點");
    	
    	$api_url = 'http://pay.870.com/api/long_e_pay/dxc/action.php';

    	$get = array(
    		'orderid' => $billing->id,
    		'userid' => $this->CI->g_user->encode($billing->uid),
    		'gamecode' => 'dxc',    			  
    		'server_id' => $server->address,		
			'money' => $billing->amount*intval($rate)/2,    		
    		'time' => time(),
    	); 
    	$get['signmsg'] = md5($get['userid'].$get['server_id'].$get['money'].$this->key.$get['time']);      	
    	
    	$user = (object) array("uid" => $billing->uid);
		$re = $this->check_role_status($server, $user);
    	if ($re == "-1") return "-1";
    	else if ($re == "0") return $this->_return_error("該伺服器沒有角色({$this->error_message})");
    	   	
   		$re = $this->curl($api_url.'?'.http_build_query($get));    	
   		
        if (empty($re)) {
    		return "-1";
    	}
    	else {    		
    		$re = trim($re);
	    	if ($re == '1') {
	    		return '1';
	    	}
	    	else if ($re == "-2") {
	    		return $this->_return_error("用戶不存在");
	    	}
	    	else {
		    	return $this->_return_error("錯誤代碼 {$re}".$api_url.'?'.http_build_query($get));
		    }
    	}      	
    }    
    
	//判斷有無角色
    function check_role_status($server, $user)
    {    	
    	$api_url = 'http://www.870.com/api/user/openid/long_e/role.php';
    	
    	$get = array(
    		'userid' => $this->CI->g_user->encode($user->uid),
    		'server_id' => $server->address,
    		'time' => time(),
    	);    	
    	$get['signmsg'] = md5($get['userid'].$get['server_id'].$get['time']);
    	
    	$re = $this->curl($api_url.'?'.http_build_query($get));
    	
    	if (empty($re)) {
    		return "-1";
    	}
    	else {     		
    		if ($re == '-1') {
    			return $this->_return_error("查無角色");
    		}
    		else {
    			$data = json_decode($re);
    			if (empty($data)) return $this->_return_error("json解析錯誤");
    			return '1';
    		}
		}
    }   
}