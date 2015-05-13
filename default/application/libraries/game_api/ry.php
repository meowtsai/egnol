<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/game_api.php';

class Ry extends Game_Api
{
    var $conf;
	var $server_list_api, $game_login_api, $product_list_api, $game_pay_api, $chk_role_exist_api;
	var $open_id;
	
    function __construct()
    {    
    	parent::__construct();
    	$this->conf = $this->load_config("ry");    	
    	$agent_api = $this->conf['agent_info_api'];
    	
    	$time = time();    	
    	$re = $this->curl($agent_api, 
    			array(
    				"ut" => $time,
    				"csid" => $this->conf["csid"],
    				"sign" => md5($time.$this->conf["csid"].$this->conf["key"]), 
    			));    	
    	
    	/*
    	echo "<pre>agent_api:\n\n";
    	echo $agent_api;
    	echo print_r(array(
    				"ut" => $time,
    				"csid" => $this->conf["csid"],
    				"sign" => md5($time.$this->conf["csid"].$this->conf["key"]), 
    			));
    	echo "回應：\n";
    	print_r($re);
    	echo "</pre>";
    	*/
    	
    	$xml = simplexml_load_string($re);
    	$game_item = $xml->xpath("game_group/game_item[gs_code='ry']");
    	$game_xml = $game_item[0];
    	
    	/*
    	echo "<pre>agent_api:\n\n";
		var_dump($xml);		
		
		print_r($game_xml->server_list_api);
		echo "</pre>";
		
		exit();
		*/		
		
    	$this->server_list_api = (string)$game_xml->server_list_api;
    	$this->game_login_api = (string)$game_xml->game_login_api;
    	$this->product_list_api = (string)$game_xml->product_list_api;
    	$this->game_pay_api = (string)$game_xml->game_pay_api;
    	$this->chk_role_exist_api = (string)$game_xml->chk_role_exist_api;
    	
    	$time = time();    	
    	$re = $this->curl($xml->account_register_api, 
    			array(
    				"ut" => $time,
    				"csid" => $this->conf["csid"],
    				"cuid" => $this->CI->g_user->euid,
    				"sign" => md5($this->conf["csid"].$this->conf["key"].$time), 
    			));
    	$xml = simplexml_load_string($re);
    	$this->open_id = (string)$xml->open_id;
    	
    	/*
    	echo "<pre>account_register_api:\n\n";
		var_dump($xml);
		echo "</pre>";*/	
    }
    
    function login($server, $user, $ad)
    {	    	
    	$time = time();    	
    	$re = $this->curl($this->product_list_api, array(
    							"ut" => $time,
    							"csid" => $this->conf["csid"],
    							"sign" => md5($this->conf["csid"].$time.$this->conf["key"]), 
    						));
    	$xml = simplexml_load_string($re);
    	
    	/*
    	echo "<pre>product_list_api:\n\n";
		var_dump($xml);
		echo "</pre>";
		
		foreach($xml->payment_products->product_item as $x) {
			print_r((string)$x->pro_cost);
		}
		exit();
		*/
		
    	/*
		$time = time();    	
    	$re = $this->curl($this->server_list_api, array(
    							"ut" => $time,
    							"csid" => $this->conf["csid"],
    							"sign" => md5($this->conf["csid"].$time.$this->conf["key"]), 
    						));
    	$xml = simplexml_load_string($re);
    	
    	echo "<pre>server_list:\n\n";
		var_dump($xml);
		echo "</pre>";
		exit();
		*/
			

    	$time = time();    	
    	$this->run_post($this->game_login_api, array(
    							"ut" => $time,
    							"csid" => $this->conf["csid"],
    							"open_id" => $this->open_id,
    							"server_id" => $server->address,
    							"sign" => md5($this->open_id.$this->conf["key"].$time), 		
    						));
    }
    
    function transfer($server, $billing, $rate=1)
    {	    	
    	$time = time();    	
    	$re = $this->curl($this->product_list_api, array(
    							"ut" => $time,
    							"csid" => $this->conf["csid"],
    							"sign" => md5($this->conf["csid"].$time.$this->conf["key"]), 
    						));
    	$xml = simplexml_load_string($re);
    	
    	$trans_money = $billing->amount * $rate /2;
    	
    	foreach($xml->payment_products->product_item as $x) {
			if ($trans_money == (string)$x->pro_cost) {
				$money = $trans_money;
				$pro_no = (string)$x->pro_sn;
				break;
			}
		}
				
    	if (empty($money)) return $this->_return_error("金額錯誤");
    	
    	$user = (object) array("uid"=>$billing->uid, "account"=>$billing->account);
		$re = $this->check_role_status($server, $user);
    	if ($re == "-1") return "-1";
    	else if ($re == "0") return $this->_return_error("該伺服器沒有角色");
    	
    	$time = time();    	
    	$re = $this->curl($this->game_pay_api, array(
    							"ut" => $time,
    							"csid" => $this->conf["csid"],
    							"open_id" => $this->open_id,
    							"server_id" => $server->address,
    							"pro_sn" => $pro_no,
    							"joint_order_id" => $billing->id,
    							"sign" => md5($this->open_id.$this->conf["key"].$time), 
    						));
    	$xml = simplexml_load_string($re);
    	 
    	
        if (empty($xml)) {
    		return "-1";
    	}
    	else {    		
	    	if ((string)$xml->result == 'success') {
	    		return '1';
	    	}
	    	else {
		    	return $this->_return_error((string)$xml->errmsg.", 錯誤碼".(string)$xml->errcode);
		    }
    	}      	
    }    
    
	//判斷有無角色
    function check_role_status($server, $user)
    {    
    	$time = time();    	
    	$re = $this->curl($this->chk_role_exist_api, array(
    							"ut" => $time,
    							"csid" => $this->conf["csid"],
    							"open_id" => $this->open_id,
				    			"server_id" => $server->address,	
    							"sign" => md5($this->conf["csid"].$this->open_id.$this->conf["key"].$time), 
    						));
    	$xml = simplexml_load_string($re);
		
		if ((string)$xml->result == 'success') {
    		return '1';
	    }
	    else {
			return $this->_return_error("無角色");
		}
    }        
}

/* End of file Template.php */
/* Location: ./system/application/libraries/Template.php */