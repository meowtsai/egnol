<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/game_api.php';

class Kingnet extends Game_Api
{    
	var $kingnet_conf, $game_conf;
	
	function __construct()
	{
		parent::__construct();
		$this->kingnet_conf = $this->load_config("kingnet");
		$this->game_conf = $this->load_game_conf();
	}
	
	function load_game_conf() 
	{
		//由各遊戲 override
	}
	
/* 
	gid
	1 蜀山传奇
	2 神将传奇
	4 剑道
	5 仙宗
*/
    function login($server, $user, $ad)
    {	
		$login_url = $this->_get_login_url($server, $user, $ad);	
    	header('LOCATION:'.$login_url);
    	exit();
    }
    
    function _get_login_url($server, $user, $ad)
    {
    	if (empty($ad)) $ad = '';
    	$param = array(
    		"c" => "game",
    		"a" => "playin",
    		"pf" => "long_e",
    		"openid" => $this->CI->g_user->encode($user->uid),
    		"gid" => $this->game_conf["gid"],
    		"sid" => $server->address,  //eg 25, 26,... 對應到伺服器編號
    		"t" => time(),
    		"ad" => $ad,
    	);    	
    	$sig = $this->genSignStr($param, $this->kingnet_conf["pkey"]);
    	return $this->kingnet_conf["login_url"].http_build_query($param)."&sig={$sig}"; 	
    }
    
    function transfer($server, $billing, $rate=1)
    {	
		//判斷有無角色
		$user = $this->CI->db->where("uid", $billing->uid)->get("users")->row();				
		$re = $this->check_role_status($server, $user);

		if ($re == "-1") return "-1";
    	else if ($re == "0") return $this->_return_error("該伺服器沒有角色(".$this->error_message.")");  	
    	
    	$param = array(
			"plat" => "long_e",
			"user" => $this->CI->g_user->encode($billing->uid),
			"game" => $this->game_conf["game"],
			"gameid" => $this->game_conf["transfer_gameid"],
			"server" => $server->address,
			"tradeno" => $billing->id,
			"money" => intval($billing->amount),
			"gold" => $billing->amount * intval($rate),
			"time" => time(),    			
    	);
    	$sig = $this->genSignStr($param, $this->kingnet_conf["transfer_pkey"]);	
    	$connect_url = $this->game_conf["transfer_url"].http_build_query($param)."&sig={$sig}";
    	
   		$re = $this->curl($connect_url);
    	
        if (empty($re)) {
    		return "-1";
    	}
    	else {
    		$json_result = json_decode($re, TRUE);
    		if (empty($json_result)) {
    			return $this->_return_error("error:".$re);
    		}
    		else 
    		{		
	    		if ($json_result['code'] == '1') {
	    			return '1';
	    		}
	    		else {	    			
    				if (array_key_exists($json_result['code'], $this->kingnet_conf["code"])) {
		    			$error_message = $json_result['code']." ".$this->kingnet_conf["code"][$json_result['code']];
		    		} else $error_message = "未知的錯誤代碼:{$json_result['code']}";
		            return $this->_return_error($error_message);
		    	}
    		}
    	}      	    	
    	
    }
	
	//判斷有無角色
    function check_role_status($server, $user)
    {       	    	
    	$param = array(
    		"c" => "game",
    		"a" => "checkrole",
    		"gid" => $this->game_conf["gid"],
    		"openid" => $this->CI->g_user->encode($user->uid),    				
    		"pf" => "long_e",
    		"sid" => $server->address,    		
    		"t" => time(),
    	);
    	
    	$sig = $this->genSignStr($param, $this->kingnet_conf["pkey"]);
    	$connect_url = $this->kingnet_conf["check_role_status_url"].http_build_query($param)."&sig={$sig}";  	    	
    	
		$maximumLoopNum = 3;
        while ($maximumLoopNum-- > 0) { 
			$re = $this->curl($connect_url);	
    		if (empty($re)) sleep(1); //對方無反應，隔1秒再試
    		else break;
        }        

        if (empty($re)) {
    		return "-1";
    	}
    	else {
    		$json_result = json_decode($re, TRUE);
    		if (empty($json_result)) {
    			return $this->_return_error($re);
    		}
    		else {
	    		if ($json_result['sign'] == '1') {
	    			return '1';
	    		}
	    		else {
    				if (array_key_exists($json_result['sign'], $this->kingnet_conf["sign"])) {
		    			$error_message = $json_result['sign']." ".$this->kingnet_conf["sign"][$json_result['sign']];
		    		} else $error_message = "未知的錯誤代碼:{$json_result['sign']}";
		            return $this->_return_error($error_message);
		    	}
    		}
    	}
    }   	
    
	function genSignStr($param, $pkey)
	{
		$query = array();
		ksort($param);
		$query_string = array();
		foreach ($param as $key => $val)
		{
			if($key == "sig"){
				continue;
			}
			array_push($query_string, $key . '=' . $val);
		}
		$query_string = join('&', $query_string);
		fb($pkey.$query_string, "加密前");
		$sig = base64_encode(md5($pkey.$query_string));
	
		return $sig;
	}    
    
}

/* End of file Template.php */
/* Location: ./system/application/libraries/Template.php */