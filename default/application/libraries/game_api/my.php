<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/game_api.php';

class My extends Game_Api 
{	
    var $conf;

    function __construct()
    {    
    	parent::__construct();
    	$this->conf = $this->load_config(strtolower(get_class()));
    }
	
    function login($server, $user, $ad)
    {
		$sid = strtr($server->address, array("my-"=>"", ".longeplay.com.tw"=>""));
    	$username = $this->CI->g_user->encode($user->uid);
    	$time = time();
    	$cm = '1'; //防沉迷
    	$key = $this->conf['login_key'];
    	$sp = $this->conf['sp'];
    	$flag = md5($username . $sid . $key . $time);	
    	$login_url = "http://".strtr($server->address, array("-s"=>""))."{$this->conf["login_url"]}?username=".$username."&time=".$time."&cm=".$cm."&flag=".$flag."&sp={$sp}&sid=".$sid."&ad=".$ad;    	 
    	
   		if ($ad == 'cobi') {
    		die('
    			<html><head>
				<script type="text/javascript"> var _bwp=460; var _bwpid2="buy"; </script>
				<script type="text/javascript" src="//adsense.scupio.com/conv/js/convbtn.js"></script>    				 				
    			<script type="text/javascript">
    			bw_conv();
         		setTimeout(function() {location.href="'.$login_url.'";}, 800);    				    				    				
				</script>   
    			</head><body></body></html> 				
    		');    		
    	}
    	
		header("location:{$login_url}");
    }    
    
    function transfer($server, $billing, $rate=1)
    {	
    	if (!IN_OFFICE && $billing->amount < 50) return $this->_return_error("最少需轉50點");
    	
    	$user = (object) array("uid"=>$billing->uid, "account"=>$billing->account);
		$re = $this->check_role_status($server, $user);
    	if ($re == "-1") return "-1";
    	else if ($re == "0") return $this->_return_error("該伺服器沒有角色");
    	
    	//p=充值订单号|充值用户|游戏币数量| UNIX 时间戳|加密串|充值人民币数    	
    	$key = $this->conf['transfer_key'];
    	$order = str_pad($billing->id, 18, '0', STR_PAD_LEFT);
    	$euid = $this->CI->g_user->encode($billing->uid);
    	$amount = $billing->amount*intval($rate);
    	$time = time();
    	$flag = md5($order.$euid.$amount.$time.$key);
    	$money = floatval($billing->amount/5);
    	$p = "{$order}|{$euid}|{$amount}|{$time}|{$flag}|{$money}";
    	$service_zone = strtr($server->address, array("my-"=>"", ".longeplay.com.tw"=>""));
    	   		
    	$transfer_url = "http://{$server->address}{$this->conf['transfer_url']}?p={$p}&service_zone={$service_zone}";
    	//return $this->_return_error($transfer_url);
    	
    	$maximumLoopNum = 1;  //最大測試次數
    	while ( $maximumLoopNum-- > 0 ) {
    		$re = $this->curl($transfer_url);
    		if (empty($re)) sleep(1); //對方無反應，隔1秒再試
    		else break;
    	}

    	//log_message('error', $transfer_url.' '.$re);
    	
        if (empty($re)) {
    		return "-1";
    	}
    	else {    		
    		$re = trim($re);
	    	if ($re == '1') {
	    		return '1';
	    	}
	    	else if ($re == "-3") {
	    		return $this->_return_error("-3 該伺服器沒有角色");
	    	}
	    	else {
		    	return $this->_return_error("錯誤代碼 {$re}");
		    }
    	}      	
    }    
    
	//判斷有無角色
    function check_role_status($server, $user)
    {    	
    	$query = $this->CI->db->where("address", $server->address)->from("servers")->get();
    	$sids = array();
    	foreach($query->result() as $row) {
    		$sids[] = $row->id;
    	}
    	//log_message('error', print_r($sids, true));
    	    		
    	$query = $this->CI->db->from("characters")
    		->where_in("server_id", $sids)
    		->where("uid", $user->uid)->get();
    	
    	if ($query->num_rows() > 0) {
    		return '1';
	    }
	    else {
			return $this->_return_error("無角色");
		}
    }   
}