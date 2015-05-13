<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/game_api.php';

class Kunlun extends Game_Api
{    
	var $kunlun_conf, $game_conf;
	
	function __construct()
	{
		parent::__construct();
		$this->kunlun_conf = $this->load_config("kunlun");
		$this->game_conf = $this->load_game_conf();
	}
	
	function load_game_conf() 
	{
		//由各遊戲 override
	}
	
    function login($server, $user, $ad='')
    {	
    	$login_url = $this->get_login_url($server, $user, $ad);
    	header('LOCATION:'.$login_url);
    	exit();
    }
    
	function get_login_url($server, $user, $ad='')
	{
		//$query_string = parse_url($server, PHP_URL_QUERY);
		//parse_str($query_string, $get); //將server的query string轉到$get陣列
		
		$sid 	= empty($server->merge_address) ? $server->address : $server->merge_address;
		$uid 	= $user->uid;
    	$uname 	= urlencode($user->account);
    	$lgtime = date("YmdHis",time());
    	$uip 	= $_SERVER['REMOTE_ADDR'];
    	$type 	= 'long_e';    	
    	$key	= $this->game_conf['login_key'];   	    	
    	$sign 	= md5("uid={$uid}&uname={$uname}&lgtime={$lgtime}&uip={$uip}&type={$type}&sid={$sid}&key={$key}");   	    	
    	return "{$this->kunlun_conf['login_url']}?sid={$sid}&uid={$uid}&uname={$uname}&lgtime={$lgtime}&uip={$uip}&type={$type}&sign={$sign}";    
    }
    
    function transfer($server, $billing, $rate=1)
    {	
    	if ( ! IN_OFFICE && $billing->amount < 50) return $this->_return_error("最少需轉50點");
    	
    	$sid 	= empty($server->merge_address) ? $server->address : $server->merge_address;
    	$uid	= $billing->uid;
    	$uname 	= urlencode($billing->account);
    	$lgtime = date("YmdHis",time());
    	$uip 	= $_SERVER['REMOTE_ADDR'];
    	$type 	= 'long_e';
    	$key	= $this->game_conf['transfer_key'];
    	$price 	= $billing->amount;
    	$point	= $billing->amount * floatval($rate);
    	
    	if ($server->game_id == 'kw') $price = round($point / 15, 2);
    	
		//判斷有無角色
		$user = (object) array("uid"=>$billing->uid, "account"=>$billing->account);
		$re = $this->check_role_status($server, $user);
		
    	if ($re == "-1") return "-1";
    	else if ($re == "0") return $this->_return_error($this->error_message);
    	    	
    	
    	//fb("uid={$uid}&uname={$uname}&serverid={$sid}&point={$point}&amount={$price}&oid={$billing->id}&time={$lgtime}&type={$type}&key={$key}", "code");
    	
    	$sign = md5("uid={$uid}&uname={$uname}&serverid={$sid}&point={$point}&amount={$price}&oid={$billing->id}&time={$lgtime}&type={$type}&key={$key}");
    	$connect_url = "{$this->kunlun_conf['transfer_url']}?uid={$uid}&uname={$uname}&serverid={$sid}&point={$point}&amount={$price}&oid={$billing->id}&time={$lgtime}&type={$type}&sign={$sign}";
    	
    	//log_message('error', $connect_url);
    	//fb($connect_url);
    	
    	$maximumLoopNum = 1;  //最大測試次數
    	while ( $maximumLoopNum-- > 0 ) {
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
    			return $this->_return_error("error:".$re);
    		}
    		else 
    		{
	    		$status_msg = array(
	    			'401' => '使用者ID不存在', 	//缺少用户ID
	    			'402' => '使用者名稱不存在', //缺少用户名
	    			'403' => '伺服器ID不存在', 	//缺少服务器ID
	    			'404' => '轉點點數異常', 	//缺少充值点数
	    			'405' => '轉點點數異常', 	//缺少充值金额
	    			'406' => '訂單流水號異常', 	//缺少订单ID
					'407' => '訂單時間戳異常', 	//缺少提交时间
					'408' => '資料傳遞異常', 	//缺少运营商标识
	   				'409' => '資料傳遞異常', 	//缺少数字签名
	   				'410' => '資料傳遞異常', 	//IP访问限制
	   				'411' => '資料傳遞異常', 	//签名错误
	   				'412' => '角色不存在', 		//用户角色不存在
	   				'413' => '伺服器異常', //獲取角色資料失敗
	   				'414' => '伺服器異常', //內部錯誤充值失敗		
				);
	    		    			
	    		if ($json_result['status'] == '400') {
	    			return '1';
	    		}
	    		else {
    				if (array_key_exists($json_result['status'], $status_msg)) {
		    			$error_message = $json_result['status']." ".$status_msg[$json_result['status']];
		    		} else $error_message = "未知的錯誤代碼:{$json_result['status']}";
		            return $this->_return_error($error_message);
		    	}
    		}
    	}      	
    }
    
	//判斷有無角色
    function check_role_status($server, $user)
    {   
    	if ($server->id == '393') return "1";
    	if ($server->id == '414') return "1";
    	if ($server->id == '424') return "1";
    	
    	$sid = empty($server->merge_address) ? $server->address : $server->merge_address;
    	$key = $this->game_conf['transfer_key'];
    	$uname 	= urlencode($user->account);
    	$lgtime = date("YmdHis",time());
    	$sign = md5("uid={$user->uid}&uname={$uname}&serverid={$sid}&type=long_e&key={$key}");
    	$connect_url = "{$this->kunlun_conf['check_role_url']}?uid={$user->uid}&uname={$uname}&serverid={$sid}&type=long_e&sign={$sign}";
  	    	
    	#log_message('error', 'kunlun check_role_status: '.$connect_url);
    	
    	
		$maximumLoopNum = 6;
        while ($maximumLoopNum-- > 0) { 
			$re = $this->curl($connect_url);	
    		if (empty($re)) sleep(4); //對方無反應，隔3秒再試
    		else {    		
	        	$json_result = json_decode($re, TRUE);
	    		if ( ! empty($json_result) && $json_result['status'] == '611') {	    				    			
	    			sleep(4);    				
	    		}
	    		else break;
    		}
        }       
        
        #log_message('error', 'kunlun check_role_status:'.$server->server_id.', '.$user->uid.', '.$re);

        if (empty($re)) {
    		return "-1";
    	}
    	else {
    		$json_result = json_decode($re, TRUE);
    		if (empty($json_result)) {
    			return $this->_return_error($re);
    		}
    		else {
	    		if ($json_result['status'] == '600') {
	    			return '1';
	    		}
	    		else {
	    			$message = $json_result['status'].' '.$json_result['data'];
	    			if ($json_result['status'] == '611') {
	    				log_message('error', '('.$_SERVER["REMOTE_ADDR"].') check_role_status: '.$server->server_id.' 611');
	    				$message .= "，請稍後再進行嘗試，若持續發生此問題，請至客服中心與我們聯繫";
	    			}
		            return $this->_return_error($message);
		    	}
    		}
    	}
    	/*
			601	no_user_id	缺少用户ID
			602	no_user_name	缺少用户名
			603	no_server_id	缺少服务器ID
			604	no_type	缺少运营商标识
			606	no_sign	缺少数字签名
			607	access_forbidden	IP访问限制
			608	encrypt_error	签名错误
			609	no_user	用户不存在
			610	no_character	用户角色不存在
    	 */ 
    }    
}

/* End of file Template.php */
/* Location: ./system/application/libraries/Template.php */