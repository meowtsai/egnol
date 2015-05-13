<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/game_api.php';

class Elex extends Game_Api
{
    var $conf, $game_conf, $game;

    function __construct()
    {    
    	parent::__construct();
    	$this->conf = $this->load_config("elex");
    	
    	$this->game = strtolower(get_class($this));
    	$this->game_conf = $this->load_config($this->game);    	
    }    
    
    function login($server_row, $user, $ad)
    {    	
    	$data = array(
    		"issued_at" => time(),
    		"algorithm" => "HMAC-SHA256",
    		"app_id" => $this->game,
    		"server_id" => $server_row->address,
    		"user_id" => $this->CI->g_user->encode($user->uid),
    	);    	
    	$url = $this->game_conf['login_url'];
    	
    	$signed_request = genSignedRequest($data, $this->conf['login_key']);
    	
//     	log_message('error', $url);
//     	log_message('error', print_r($data, ture));
//     	log_message('error', $signed_request);
    	
    	$this->run_post($url, array("signed_request" => $signed_request));
    }
    
	function transfer($server_row, $billing_row, $rate=1)
    {		
		$post = array(
			"trans_id" => $billing_row->id,
			"currency" => "TWD",
			"gross" => $billing_row->amount,
			"amount" => $billing_row->amount * intval($rate),
			"uid" => $this->CI->g_user->encode($billing_row->uid),
			"server_id" => $server_row->address,
		);				
		$post["token"] = md5($post["trans_id"].$post["uid"].$post["gross"].$this->conf['transfer_key']);
		
		//判斷有無角色
		$user = (object) array("uid"=>$billing_row->uid, "account"=>$billing_row->account);				
		$re = $this->check_role_status($server_row, $user);		
    	if ($re == "-1") return "-1";
    	else if ($re == "0") return $this->_return_error("該伺服器沒有角色({$this->error_message})");		
		
    	//log_message('error', $this->conf['transfer_url']);
    	///log_message('error', print_r($post, true));
    	$re = $this->curl($this->conf['transfer_url'], $post);
    	//log_message('error', $re);
    	
        if (empty($re)) {
    		return "-1";
    	}
    	else {
    		//log_message('error', $re);    		
    		$re = trim(preg_replace("/\<html(.*?)html\>/s",'',$re)); 
    		
	    	if ($re == 'SUCCESS') {
	    		return '1';
	    	}
	    	else {
		    	return $this->_return_error("錯誤代碼 {$re}");
		    }
    	} 		
    }
    
    //判斷有無角色
    function check_role_status($server_row, $user_row)
    {    	
    	$id = $this->CI->g_user->encode($user_row->uid);
    	$check_user_url = sprintf($this->conf['check_user_url'], $server_row->address, $id);
    	
    	$maximumLoopNum = 3;    	
        while ($maximumLoopNum-- > 0) { 
			$re = $this->curl($check_user_url);	
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
    			if ( ! empty($json_result['character_name']) || ! empty($json_result[0]['character_name'])) {
	    			return '1';
	    		}
	    		else {
    				return false;
		    	}
    		}
    	}
    }

}

function genSignedRequest($data, $secret) {
	$json_encoded_data = json_encode($data);
	//echo "Json encoded data:<br>" . $json_encoded_data . '<br>';
	$payload = base64_url_encode($json_encoded_data);
	//echo "Base64 encoded data:<br>" . $payload . '<br>';
	$sig = base64_url_encode(hash_hmac('sha256', $payload, $secret, $raw = true));
	//echo "Base64 encoded & sha256 encrypted sig:<br>". $sig . '<br>';
	return "{$sig}.{$payload}";
}

// signed_request解析算法
function parse_signed_request($signed_request, $secret) 
{
	list($encoded_sig, $payload) = explode('.', $signed_request, 2);
	// decode the data
	$sig = base64_url_decode($encoded_sig);
	$data = json_decode(base64_url_decode($payload), true);
	if (strtoupper($data['algorithm']) !== 'HMAC-SHA256') {
		error_log('Unknown algorithm. Expected HMAC-SHA256');
		return null;
	}
	// Adding the verification of the signed_request below
	$expected_sig = hash_hmac('sha256', $payload, $secret, $raw = true);
	if ($sig !== $expected_sig) {
		error_log('Bad Signed JSON signature!');
		return null;
	}
	return $data;
}
	

function base64_url_encode($input) {
	return base64_encode($input);
}

// 解碼方法
function base64_url_decode($input) {
	return base64_decode(strtr($input, '-_', '+/'));
}

/* End of file Template.php */
/* Location: ./system/application/libraries/Template.php */