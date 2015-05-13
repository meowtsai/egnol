<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/game_api.php';

class Muxplay extends Game_Api
{
    var $conf, $game_conf, $game;
	var $auth_key;
	
    function __construct()
    {    
    	parent::__construct();
    	$this->conf = $this->load_config("muxplay");
    	
    	$this->game = strtolower(get_class($this));
    	$this->game_conf = $this->load_config($this->game);    	
    }    
    
    function login($server, $user, $ad)
    {    	
    	$auth_key = $this->get_auth_key();
   		
   		$data = array(    			
    		"company" => "long_e",
   			"account" => $user->euid,
    		"user_tstamp" => time(),
   			"game_id" => $this->game_conf["game_id"],
   			"server_id" => $server->address,
    	);  
   		$data["user_ticket"] = md5($data["company"].$data["account"].$data["user_tstamp"].$data["game_id"].$data["server_id"].$this->conf['key'].$auth_key);
   		
   		header("location: ".$this->conf['login_url'].http_build_query($data));
    }
    
	function transfer($server, $billing, $rate=1)
    {	
    	if ( ! IN_OFFICE && $billing->amount < 50) return $this->_return_error("最少需轉50點");
    	    	
    	$user = (object) array("uid" => $billing->uid);
		$re = $this->check_role_status($server, $user);
    	if ($re == "-1") return "-1";
    	else if ($re == "0") return $this->_return_error("檢查角色失敗: {$this->error_message}");

    	$auth_key = $this->get_auth_key();
    	
   		$data = array(    			
    		"company" => "long_e",
   			"account" => $this->CI->g_user->encode($user->uid),
    		"user_tstamp" => time(),
   			"game_id" => $this->game_conf["game_id"],
   			"server_id" => $server->address,
   			"order_id" => $billing->id,
   			"gold_num" => $billing->amount*intval($rate),
    	);  
    	$data["user_ticket"] = md5($data["company"].$data["account"].$data["user_tstamp"].$data["game_id"].$data["server_id"].$data["order_id"].$data["gold_num"].$this->conf['key'].$auth_key);    	

    	$url = $this->conf['transfer_url'].http_build_query($data);
    	
    	$re = $this->curl($url);
    	//log_message('error', "充值完成");
   		
	   	if ($re == '') return '-1';
	   	else 
	   	{	   		
	   		$json = json_decode($re);
	   		if (empty($json)) $this->_return_error("json解析錯誤");
	   		
	   		if ($json->status == '13') return '1';
	   		else {
	   			return $this->_return_error("錯誤代碼 {$json->status}: {$json->statusTxt}");
	   		}
	    }
    }    
    
    //判斷有無角色
    function check_role_status($server, $user)
    {    	
    	$auth_key = $this->get_auth_key();
   		
   		$data = array(    			
    		"company" => "long_e",
   			"account" => $this->CI->g_user->encode($user->uid),
    		"user_tstamp" => time(),
   			"game_id" => $this->game_conf["game_id"],
   			"server_id" => $server->address,
    	);  
   		$data["user_ticket"] = md5($data["company"].$data["account"].$data["user_tstamp"].$data["game_id"].$data["server_id"].$this->conf['key'].$auth_key);

    	$check_user_url = $this->conf['check_user_url'].http_build_query($data);
    	
    	$cnt = 3;    	
        while ($cnt-- > 0) { 
			$re = $this->curl($check_user_url);	
    		if (json_decode($re)) break;
    		else sleep(2); //對方無反應，隔1秒再試
        }        

        if ($re == '') {
    		return "-1";
    	}
    	else {
    		$json = json_decode($re);    		
    		//log_message('error', print_r($json, true));
    		
    		if (empty($json)) {
    			return $this->_return_error($re);
    		}
    		else {
    			if ($json->status == '4') {
	    			return '1';
	    		}
	    		else {
    				return false;
		    	}
    		}
    	}
    }

    function get_auth_key()
    {   		
    	if ($this->auth_key) return $this->auth_key;
    	
        $cnt = 3;
    	while ($cnt-- > 0) {   		
			$data = array(    			
	    		"company" => "long_e",
	    		"tstamp" => time(),
	    	);    	
	    	$data["ticket"] = md5($data["company"].$data["tstamp"].$this->conf['key']);
	    	$url = $this->conf['auth_url'].http_build_query($data);
   		
    		$re = $this->curl($url); 
    		if (json_decode($re)) break;    		
    		sleep(2);
    	}
   		
   		$auth_key = "";
   		if ($json = json_decode($re)) {
   			if ($json->status == "4") {
   				$auth_key = $json->statusTxt;
   			}
   			else log_message("error", "muxplay {$this->game}: 獲取令牌失敗! ({$json->status}:{$json->statusTxt})");
   		}
   		else log_message("error", "muxplay {$this->game}: 獲取令牌失敗! ({$json}) ".print_r($re, true));   

   		$this->auth_key = $auth_key;
   		return $auth_key;
    }
}
