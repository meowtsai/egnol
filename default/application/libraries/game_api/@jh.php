<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/game_api.php';

class Jh extends Game_Api 
{	
    var $conf;

    function __construct()
    {    
    	parent::__construct();
    	//$this->conf = $this->load_config(strtolower(get_class()));
    }
	
    function login($server, $user, $ad)
    {    		
    	$api_url = 'http://www.tt-play.com/loginApi/gameLogin/';
    	$key = 'GameWave@%_]tLog&In^2009';

    	$get = array(
    		'account' => $user->euid,
    		'game' => '32',
    		'server' => $server->address,
    		'time' => time(),
    		'src' => 'long_e',
    		'ad' => $ad,
    	);
    	$get['sign'] = md5($get['account'].$get['time'].$get['game'].$get['server'].$key);    	    	
    	
		header("location: ".$api_url.'?'.http_build_query($get).'&errorUrl='.urlencode('http://jh.longeplay.com.tw'));
    }    
    
    function transfer($server, $billing, $rate=1)
    {	
    	if ($billing->amount < 50) return $this->_return_error("最少需轉50點");
    	
    	$api_url = 'http://www.tt-play.com/loginApi/gamePay/';
    	$key = '^T@_@TPl@yW0rd@Cy2@@9!';

    	$get = array(
    		'account' => $this->CI->g_user->encode($billing->uid),
    		'game' => '32',
    		'server' => $server->address,
    		'time' => time(),
    		'from' => 'long_e',
    		'trade_no' => $billing->id,
    		'gold' => $billing->amount*intval($rate)/2,
    	); 
    	$get['ticket'] = md5($key.$get['account'].$get['gold'].$get['time'].$get['trade_no'].$get['server'].$get['game']);      	
    	
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
	    	if ($re == '5') {
	    		return '1';
	    	}
	    	else if ($re == "2") {
	    		return $this->_return_error("用戶不存在");
	    	}
	    	else {
		    	return $this->_return_error("錯誤代碼 {$re}");
		    }
    	}      	
    }    
    
	//判斷有無角色
    function check_role_status($server, $user)
    {    	
    	$api_url = 'http://www.tt-play.com/loginApi/checkrolejh/';
    	
    	$get = array(
    		'account' => $this->CI->g_user->encode($user->uid),
    		'server' => $server->address,
    		'from' => 'long_e',
    	);    	
    	
    	$re = $this->curl($api_url.'?'.http_build_query($get));
    	
    	if (empty($re)) {
    		return "-1";
    	}
    	else { 
    		$data = json_decode($re);
			
    		if (empty($data)) return $this->_return_error("json解析錯誤"); 
    		
    		if ($data->result == '1') return '1';
    		else {
    			return $this->_return_error($data->result);
    		}
		}
    }   
}