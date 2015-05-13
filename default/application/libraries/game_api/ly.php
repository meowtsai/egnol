<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/game_api.php';

class Ly extends Game_Api 
{	
    var $conf;
    var $key;

    function __construct()
    {    
    	parent::__construct();
    	//$this->conf = $this->load_config(strtolower(get_class()));
    	
    	$this->key = 'QxWbedfQxHzzfZpyMjJE7kmsAx9zDN@S';
    }
	
    function login($server, $user, $ad)
    {    		
    	$api_url = "http://{$server->address}.ly.long_e.com.tw";

    	$get = array(
    		'id' => $user->euid,
    		'time' => time(),    		
    		'isAdult' => '1',    		
    		'source' => '',
    		'sub_source' => '',    		
    		'is_client' => '0',
    		
    	);
    	$get['sign'] = md5("id={$get['id']}&now={$get['time']}.{$this->key}");    	    	
    	
    	//die($api_ur.'?'.http_build_query($get));
    	//log_message('error', $api_url.'?'.http_build_query($get));
		header("location: ".$api_url.'?'.http_build_query($get));
    }    
    
    function transfer($server, $billing, $rate=1)
    {	
    	//if ( ! IN_OFFICE && $billing->amount < 50) return $this->_return_error("最少需轉50點");
    	    	
    	$user = (object) array("uid" => $billing->uid);
		$re = $this->check_role_status($server, $user);
    	if ($re == "-1") return "-1";
    	else if ($re == "0") return $this->_return_error("檢查角色失敗: {$this->error_message}");
    	
    	$api_url = 'http://union.9377.com/pay.php';

    	$get = array(
    		'game' => 'ly',
    		'agent' => 'long_e', //合作方代碼，由9377提供    		
    		'referer' => '',
    		'user' => $this->CI->g_user->encode($billing->uid),
    		'order' => $billing->id,
    		'money' => floatval($billing->amount/5),
    		'coin' => $billing->amount*intval($rate),    			    			    			
    		'server' => strtoupper($server->address),	    			
    		'time' => time(),
    		'other' => '',
    	); 
    	$get['sign'] = strtoupper(md5($get['game'].$get['agent'].$get['server'].$get['user'].$get['order'].$get['money'].$get['coin'].$get['time'].$this->key));      	

    	
    	$cnt = 5;
    	while ($cnt-- > 0) {
	    	//log_message('error', "充值: ".$api_url.'?'.http_build_query($get));
	   		$re = $this->curl($api_url.'?'.http_build_query($get));
	    	//log_message('error', "充值回應: ".$re);
	    	if ($re !== '' && $re != '-102') break;
	    	sleep(2);
	    }
	    	
      	if ($re == '1') return '1';
	   	else if ($re == '') return '-1';
	   	else {
	   		$message = array(
	   			"0" => "參數不全",
	   			"2" => "合作方不存在",
   				"3" => "金額超出範圍 ( 0 < money ≤ 100,000 )",
   				"4" => "服務器未登記",
   				"5" => "驗證失敗",
   				"6" => "充值遊戲不存在",
   				"7" => "玩家角色不存在",
   				"8" => "時間超時（大於5分鐘）",
   				"9" => "請求過於頻繁",
	   			"-1" => "訪問IP不在白名單裏",
	   			"-4" => "充值失敗",
	   			"-7" => "訂單號重復，相同訂單已成功註入",
	   			"-102" => "充值異常，遊戲方無響應",
	   			"-103" => "充值接口異常",		
	   		);
	   		if (array_key_exists($re, $message)) return $this->_return_error("{$re} {$message[$re]}");
	    	else return $this->_return_error("錯誤代碼 {$re}");
	    }
    }    
    
	//判斷有無角色
    function check_role_status($server, $user)
    {    	
    	$api_url = 'http://union.9377.com/checkuser.php';

    	$get = array(
    		'game' => 'ly',
    		'agent' => 'long_e', //合作方代碼，由9377提供    		
    		'user' => $this->CI->g_user->encode($user->uid),
    		'server' => strtoupper($server->address),	
    	);    
    	$get['sign'] = strtoupper(md5($get['game'].$get['agent'].$get['server'].$get['user'].$this->key));	
    	
    	
    	//log_message('error', "創角查詢: ".$api_url.'?'.http_build_query($get));
        $cnt = 5;
    	while ($cnt-- > 0) {
    		$re = $this->curl($api_url.'?'.http_build_query($get));
    		if ($re !== '' && $re != '-102') break;    		
    		sleep(2);
    	}
    	//log_message('error', "創角回應: ".$re);
    	
   		if ($re == '1') return '1';
   		else if ($re == '') {
    		log_message('error', $server->game_id." check_role timeout");
    		return  '-1';	
   		}
   		else {   			
   			$message = array(
   				"0" => "角色不存在",
   				"-1" => "參數不全",
   				"-2" => "合作方不存在",
   				"-3" => "服務器未登記",
   				"-4" => "驗證失敗",
   				"-5" => "遊戲不存在",
   				"-6" => "請求過於頻繁",
   				"-7" => "訪問IP不在白名單裏",
   				"-102" => "充值異常，遊戲方無響應",
   				"-103" => "充值接口異常",
   			);   			
	   		if (array_key_exists($re, $message)) return $this->_return_error("{$re} {$message[$re]} ");
	    	else return $this->_return_error("錯誤代碼 {$re}");
   		}
    }   
}