<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/game_api.php';

class Sl2 extends Game_Api
{
    var $conf;

    function __construct()
    {    
    	parent::__construct();
    	$this->conf = $this->load_config("sl2");    	
    }
    
    function _get_server_id($address)
    {
    	$no = strtr(substr($address, 1), array('.sl2.longeplay.com.tw'=>''));
    	return '221674'.sprintf("%03s", $no);
    }
    
    function login($server_row, $user, $ad)
    {
    	$username = $user->account;
    	$time = date("YmdHis");
    	$product_id = '221';
    	$server_id = $this->_get_server_id($server_row->address);
    	$adult= '1';
    	$key = $this->conf['login_key'];
    	$sign = md5($username.$time.$adult.$key);    	
    	$login_url = sprintf($this->conf['login_url'], $server_row->address, urlencode($username), $time, $adult, $sign, $product_id, $server_id)."&direct=1&ad={$ad}&";
    	
		if ($ad == 'google') {
    		die('
    			<html><head>
				<!-- Google Code for &#36681;&#25563; Conversion Page -->
				<script type="text/javascript">
				/* <![CDATA[ */
				var google_conversion_id = 989426073;
				var google_conversion_language = "zh_TW";
				var google_conversion_format = "2";
				var google_conversion_color = "ffffff";
				var google_conversion_label = "JGhyCM-ftQQQmePl1wM";
				var google_conversion_value = 0;
    				
    			setTimeout(function() {location.href="'.$login_url.'";}, 800); 
				/* ]]> */
				</script>
				<script type="text/javascript" src="http://www.googleadservices.com/pagead/conversion.js">
				</script>
				<noscript>
				<div style="display:inline;">
				<img height="1" width="1" style="border-style:none;" alt="" src="http://www.googleadservices.com/pagead/conversion/989426073/?value=0&amp;label=JGhyCM-ftQQQmePl1wM&amp;guid=ON&amp;script=0"/>
				</div>
				</noscript>
    			</head><body></body></html> 				
    		');
    	}    	
    	
		header("location: {$login_url}");
		exit();
    }
    
	function transfer($server_row, $billing_row, $rate=1)
    {		
		$partner_order = $billing_row->id;
		$username = $billing_row->account;
		$partner_id = $this->conf['partner_id'];
		$app_id = $this->conf['product_id'];
		$server_id = $this->_get_server_id($server_row->address);
		
		if ($billing_row->amount < 10) return $this->_return_error("最少需轉10點");		
		$amount = $billing_row->amount * floatval($rate) / 10; //amount與遊戲內元寶比值為1:10，除10可將比值調回1:1
		
		$key = $this->conf['transfer_key'];
		
		//判斷有無角色
		$user_row = $this->CI->db->where("account", $username)->get("users")->row();				
		$re = $this->check_role_status($server_row, $user_row);
		
    	if ($re == "-1") return "-1";
    	else if ($re == "0") return $this->_return_error("該伺服器沒有角色(".$this->error_message.")");
    	
    	//進行充值		
        $maximumLoopNum = 3;
        while ($maximumLoopNum-- > 0) {        	
        	$re = $this->curl($this->conf['transfer_url'], array(
				"partner_order" => $partner_order,
				"partner_id" => $partner_id,				
				"username" => $username,								
				"app_id" => $app_id,
				"server_id" => $server_id,
				"amount" => $amount,
				"sign" => md5($partner_order.$username.$app_id.$server_id.$amount.$key),
			), true);		
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
    		else {
	    		if ($json_result['errno'] == '0') {
	    			return '1';
	    		}
    			else if ($json_result['errno'] == '24') {
    				$this->_return_error("訂單重覆");
	    			return '-2';
	    		}
	    		else {
		            return $this->_return_error($json_result['errno']." ".$json_result['error']);
		    	}
    		}
    	}    	 
    }
    
    //判斷有無角色
    function check_role_status($server_row, $user_row)
    {    	
		$partner_id = $this->conf['partner_id'];
		$username = $user_row->account;
		$app_id = $this->conf['product_id'];		
		$server_id = $this->_get_server_id($server_row->address);
		    	
		$maximumLoopNum = 3;
        while ($maximumLoopNum-- > 0) { 
			$re = $this->curl($this->conf['check_user_url'], array(
				'partner_id' => $partner_id,
				'app_id' => $app_id,
				'server_id' => $server_id,
				'username' => $username,
				'sign' => md5($partner_id.$app_id.$server_id.$username.$this->conf['transfer_key']),
			), true);	
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
	    		if ($json_result['errno'] == '0') {
	    			return '1';
	    		}
	    		else {
		            return $this->_return_error($json_result['errno']." ".$json_result['error']);
		    	}
    		}
    	} 
    }
}

/* End of file Template.php */
/* Location: ./system/application/libraries/Template.php */