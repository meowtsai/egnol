<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/game_api.php';

class Ttplay extends Game_Api 
{	
    var $conf;

    function __construct()
    {    
    	parent::__construct();
    	$this->conf = $this->load_config("ttplay");
    	
    	$this->game = strtolower(get_class($this));
    	$this->game_conf = $this->load_config($this->game);  
    }
	
    function login($server, $user, $ad)
    {    		
    	$data = array(
    		'account' => $user->euid,
    		'game' => $this->game_conf["game_id"],
    		'server' => $server->address,
    		'time' => time(),
    		'src' => 'long_e',
    		'ad' => $ad,
    	);
    	$data['sign'] = md5($data['account'].$data['time'].$data['game'].$data['server'].$this->conf['login_key']);    	    	
    	
    	//log_message('error', $this->conf['login_url'].http_build_query($data));
		header("location: ".$this->conf['login_url'].http_build_query($data).'&errorUrl='.urlencode('http://'.$server->game_id.'.longeplay.com.tw'));
    }    
    
    function transfer($server, $billing, $rate=1)
    {	
    	//if ( ! IN_OFFICE &&  $billing->amount < 50) return $this->_return_error("最少需轉50點");
    	
    	$data = array(
    		'account' => $this->CI->g_user->encode($billing->uid),
    		'game' => $this->game_conf["game_id"],
    		'server' => $server->address,
    		'time' => time(),
    		'from' => 'long_e',
    		'trade_no' => $billing->id,    		
    	); 
    	if ($this->game == 'dd') {
    		$data['gold'] = $billing->amount*intval($rate);
    	} else  $data['gold'] = $billing->amount*intval($rate)/2;
    	
    	
    	$data['ticket'] = md5($this->conf['transfer_key'].$data['account'].$data['gold'].$data['time'].$data['trade_no'].$data['server'].$data['game']);      	
    	
    	$user = (object) array("uid" => $billing->uid);
		$re = $this->check_role_status($server, $user);
    	if ($re == "-1") return "-1";
    	else if ($re == "0") return $this->_return_error("該伺服器沒有角色({$this->error_message})");
    	   	
    	//log_message('error', $server->game_id.":".$this->conf['transfer_url'].http_build_query($data));
   		$re = $this->curl($this->conf['transfer_url'].http_build_query($data));    	
   		//log_message('error', $re); 
   		
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
    	$data = array(
    		'account' => $this->CI->g_user->encode($user->uid),
    		'server' => $server->address,
    		'src' => 'long_e',
    		'from' => 'long_e', //jh
    	);    	
    	
    	//log_message('error', $server->game_id." check_role: ".$this->game_conf["check_user_url"].http_build_query($data));
    	$re = $this->curl($this->game_conf["check_user_url"].http_build_query($data));
    	//log_message('error', $re);
    	
    	$cnt = 5;
    	while ($cnt-- > 0) {
    		$re = $this->curl($this->game_conf["check_user_url"].http_build_query($data));
    		if ($re !== '') break;      		
    		sleep(3);
    	}
    	    	
    	if ($re == '') {
    		log_message('error', $server->game_id." check_role error: ".$re);
    		return  '-1';
    	}
    	
    	if ($this->game == 'jh' || $this->game == 'aj') {
    		$data = json_decode($re);			
    		if (empty($data)) return $this->_return_error("json解析錯誤"); 
    		
    		if ($data->result == '1') return '1';
    		else {
    			return $this->_return_error($data->result);
    		}
    	}
    	else if ($this->game == 'mq') {    		
    	    if ($re == '1') return '1';
    		else {
    			return $this->_return_error($re);
    		}		
    	}
    	else if ($this->game == 'dd') {
    	    $data = json_decode($re);			
    		if (empty($data)) return $this->_return_error("json解析錯誤"); 
    		    		
    		if ($data->status == 'ok' && !empty($data->roles)) return '1';
    		else {
    			return $this->_return_error("沒有角色");
    		}
    	}
    	else {
    		$data = json_decode($re);
    		if (empty($data)) return $this->_return_error("json解析錯誤"); 
    		
    		if ($data->status == '1') return '1';
    		else {
    			return $this->_return_error($data->msg);
    		}
    	}    	
    }   
}