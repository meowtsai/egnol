<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/game_api.php';

class Xl extends Game_Api
{
    var $conf;

    function __construct()
    {    
    	parent::__construct();
    	$this->conf = $this->load_config("xl");    	
    }    
    
    function login($server_row, $user, $ad)
    {
         if ($ad == 'yahoo_keyword') {
    		echo '
<SCRIPT language="JavaScript" type="text/javascript">
<!-- Yahoo! Taiwan Inc.
window.ysm_customData = new Object();
window.ysm_customData.conversion = "transId=,currency=,amount=";
var ysm_accountid  = "1O7FGBAQLVF5766DUGO3PEJDLSK";
document.write("<SCR" + "IPT language=\'JavaScript\' type=\'text/javascript\' " 
+ "SRC=//" + "srv1.wa.marketingsolutions.yahoo.com" + "/script/ScriptServlet" + "?aid=" + ysm_accountid 
+ "></SCR" + "IPT>");
// -->
</SCRIPT>				
    		';
    	}    	
    	else if ($ad == 'google') {
			echo '
<!-- Google Code for FB&#26371;&#21729; Conversion Page -->
<script type="text/javascript">
/* <![CDATA[ */
var google_conversion_id = 935822711;
var google_conversion_language = "en";
var google_conversion_format = "2";
var google_conversion_color = "ffffff";
var google_conversion_label = "yiPACMmrqwUQ94qevgM";
var google_conversion_value = 0;
/* ]]> */
</script>
<script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">
</script>
<noscript>
<div style="display:inline;">
<img height="1" width="1" style="border-style:none;" alt="" src="//www.googleadservices.com/pagead/conversion/935822711/?value=0&amp;label=yiPACMmrqwUQ94qevgM&amp;guid=ON&amp;script=0"/>
</div>
</noscript>					
			';    		
    	}
    	
    	$data = array(
    		"issued_at" => time(),
    		"algorithm" => "HMAC-SHA256",
    		"app_id" => "xl",
    		"server_id" => $server_row->address,
    		"user_id" => $this->CI->g_user->encode($user->uid),
    	);
    	   	
    	$signed_request = genSignedRequest($data, $this->conf['login_key']);
    	$this->run_post($this->conf['login_url'], array("signed_request" => $signed_request));
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
		$user_row = $this->CI->db->where("uid", $billing_row->uid)->get("users")->row();				
		$re = $this->check_role_status($server_row, $user_row);
		
    	if ($re == "-1") return "-1";
    	else if ($re == "0") return $this->_return_error("該伺服器沒有角色");		
		
        $maximumLoopNum = 3;  //最大測試次數
    	while ( $maximumLoopNum-- > 0 ) {
    		$re = $this->curl($this->conf['transfer_url'], $post);
    		if (empty($re)) sleep(1); //對方無反應，隔1秒再試
    		else break;
    	} 
    	
        if (empty($re)) {
    		return "-1";
    	}
    	else {    		
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
    	$no = (int)strtr($server_row->server_id, array("xl_"=>""));
    	$id = $this->CI->g_user->encode($user_row->uid);
    	$check_user_url = sprintf($this->conf['check_user_url'], $no, $id);
    	//die($check_user_url);
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
	    		if ( ! empty($json_result[0]['character_name'])) {
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