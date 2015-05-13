<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/game_api.php';

class Eb extends Game_Api 
{	
    var $conf;
    var $api_url;

    function __construct()
    {    
		
    	parent::__construct();
    	//$this->conf = $this->load_config(strtolower(get_class()));
    	$this->api_url = 'https://www.artsy.com.tw/partner/gate/';
    	/*
    	測試：https://sandbox.artsy.com.tw/partner/gate/
    	正式：https://www.artsy.com.tw/partner/gate/
    	*/    			
    }
	
    function login($server, $user, $ad)
    {    	
    	$key = 'b04323ee052f8d17e311e928caba6ec5';
    	
    	$get = array(
    		'plat' => 'long_e',
    		'userId' => $user->euid,
    		'serviceId' => '6',
    		'serverId' => $server->address,
    		'time' => time(),
    	);
    	$get['sign'] = md5($get['time'].$key.$get['serviceId'].$get['serverId'].$get['userId'].$get['plat']);    	    	
		header("location: ".$this->api_url.'login?'.http_build_query($get));
    }    
    
	function transfer($server, $billing, $rate=1)
    {	
    	if ($billing->amount < 50) return $this->_return_error("最少需轉50點");
    	
    	$key = 'b04323ee052f8d17e311e928caba6ec5';

    	$get = array(
    		'plat' => 'long_e',
    		'userId' => $this->CI->g_user->encode($billing->uid),
    		'orderId' => $billing->id,
			'amount' => $billing->amount*intval($rate),
   			'serviceId' => '6',
    		'serverId' => $server->address,
    		'time' => time(),
    	); 
    	$get['sign'] = md5($get['userId'].$key.$get['serviceId'].$get['serverId'].$get['time'].$get['plat'].$get['amount'].$get['orderId']);      	
    	
    	/*
    	$user = (object) array("uid" => $billing->uid);
		$re = $this->check_role_status($server, $user);
    	if ($re == "-1") return "-1";
    	else if ($re == "0") return $this->_return_error("該伺服器沒有角色({$this->error_message})");
    	*/
    	   	
   		$re = $this->curl($this->api_url.'deposit?'.http_build_query($get), false, true);    	
   		
        if (empty($re)) {
    		return $this->_return_error($this->api_url.'deposit?'.http_build_query($get));
    	}
    	else {    		
    		$data = json_decode($re);
    		if (empty($data)) return $this->_return_error("json解析錯誤");
    		
	    	if ($data->errorCode == '0') {
	    		return '1';
	    	}
	    	else {
		    	return $this->_return_error("{$data->errorCode} {$data->errorMsg}");
		    }
    	}      	
    }    
    
	//判斷有無角色
    function check_role_status($server, $user)
    {    	
    	return true;
    	
    	$query = $this->CI->db->from("characters")
    		->where("server_id", $server->id)
    		->where("uid", $user->uid)->get();
    	
    	if ($query->num_rows() > 0) {
    		return '1';
	    }
	    else {
			return $this->_return_error("無角色");
		}
    }   
}