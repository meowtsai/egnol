<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/game_api.php';

class Eya extends Game_Api
{
    var $conf;

    function __construct()
    {    
    	parent::__construct();
    	$this->conf = $this->load_config("eya");
    }
    
    function login($server, $user, $ad)
    {
    	$url = "http://www.long_e.com.tw";
    	
		header("location:{$url}");
    }
    
    function transfer($server, $billing, $rate=1)
    {	
    	return '1';
    	
    	if ($billing->amount < 5) return $this->_return_error("最少需轉5點");
    	
    	$user = (object) array("uid"=>$billing->uid, "account"=>$billing->account);
		$re = $this->check_role_status($server, $user);
    	if ($re == "-1") return "-1";
    	else if ($re == "0") return $this->_return_error("該伺服器沒有角色");
    	
    	$key = $this->conf['transfer_key'];
    	
    	$post['server'] = "S10".strtr($server->address, array("bw"=>"", ".long_e.com.tw"=>""));       
      	$post['game'] = 'dhcq'; 
      	$post['agent'] = 'long_e';
      	$post['user'] = urlencode($billing->account);
     	$post['order'] = str_pad($billing->id, 18, '0', STR_PAD_LEFT);
      	$post['money'] = intval($billing->amount*intval($rate)/10);
      	$post['time'] = time();
		$post['sign'] = md5($key.$post['time'].$post['user'].$post['game'].$post['agent'].$post['order']);
		
    	$maximumLoopNum = 1;  //最大測試次數
    	while ( $maximumLoopNum-- > 0 ) {
    		$re = $this->curl($this->conf['transfer_url'], $post);
    		if (empty($re)) sleep(1); //對方無反應，隔1秒再試
    		else break;
    	} 
    	
        if (empty($re)) {
    		return "-1";
    	}
    	else {    		
	    	if ($re == '1') {
	    		return '1';
	    	}
	    	else if ($re == "7") {
	    		return $this->_return_error("該伺服器沒有角色");
	    	}
	    	else {
		    	return $this->_return_error("錯誤代碼 {$re}");
		    }
    	}      	
    }    
    
	//判斷有無角色
    function check_role_status($server, $user)
    {    
		return;
    	
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

/* End of file Template.php */
/* Location: ./system/application/libraries/Template.php */