<?php 

date_default_timezone_set("Asia/Taipei");

// 共用函式庫

function now()
{
	return date("Y-m-d H:i:s");
}

function json_message($arr, $result=true)
{
	$arr['status'] = $result ? 'success' : 'failure';
	return json_encode($arr);
}

function json_success($message='success')
{
	return json_encode(array('status'=>'success', 'message'=>$message));
}

function json_failure($message='fail')
{
	return json_encode(array('status'=>'failure', 'message'=>$message));
}

function my_curl($url, $data=array(), $user_ssl=false)
{
    // 對空格進行轉換
    $url = str_replace(' ','+',$url);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "$url");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    
    // 超時設定
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 9);
    curl_setopt($ch, CURLOPT_TIMEOUT, 13);    
    	
    if ($user_ssl) {
   	   	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 1); 
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    }     
    
    if ( ! empty($data)) {
	    //post
	    curl_setopt($ch, CURLOPT_POST, 1);
	    if (is_array($data)) {
	    	curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
	    }
	    else {
	    	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	    }
    }
    
    $output = curl_exec($ch);
    $errorCode = curl_errno($ch);
    
    curl_close($ch);
    if (0 !== $errorCode) {
        return false;
    }
    return $output;
}

function run_post($url, $data)
{ 
?>
	<form id="post_frm" method="POST" action="<?=$url?>" style="display:none">
		<? foreach($data as $k => $d): ?>
	    <input type="hidden" name="<?=$k?>" value="<?=$d?>">
	    <? endforeach;?>
	</form>
	
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
	<script type="text/javascript">
	$(function(){
	   	$("#post_frm").submit();
	});
	</script>    	
<? 
}

function get_channel_items($game, $html_path)
{
	include $html_path."default/application/config/api.php";	
	
	$items = array();
	foreach($config["channel_api"] as $channel => $arr) {
		$status = '2';
		if (array_key_exists("status", $arr)) $status = $arr['status'];
		if (array_key_exists("sites", $arr)) {
			if (array_key_exists($game, $arr['sites'])) {
				if (is_array($arr['sites'][$game]) && array_key_exists("status", $arr['sites'][$game])) $status = $arr['sites'][$game]['status']; //override
			}
			else continue;
		}
		if ($status <> '2') continue;
		$items[$channel] = $arr['name'];
	}
	return $items;
}

function check_mobile()
{
    $regex_match="/(nokia|iphone|android|motorola|^mot\-|softbank|foma|docomo|kddi|up\.browser|up\.link|";
    $regex_match.="htc|dopod|blazer|netfront|helio|hosin|huawei|novarra|CoolPad|webos|techfaith|palmsource|";
    $regex_match.="blackberry|alcatel|amoi|ktouch|nexian|samsung|^sam\-|s[cg]h|^lge|ericsson|philips|sagem|wellcom|bunjalloo|maui|";
    $regex_match.="symbian|smartphone|midp|wap|phone|windows ce|iemobile|^spice|^bird|^zte\-|longcos|pantech|gionee|^sie\-|portalmmm|";   
    $regex_match.="jig\s browser|hiptop|^ucweb|^benq|haier|^lct|opera\s*mobi|opera\*mini|320x320|240x320|176x220";
    $regex_match.=")/i";
    $user_agent = empty($_SERVER['HTTP_USER_AGENT']) ? "" : strtolower($_SERVER['HTTP_USER_AGENT']);
    return preg_match($regex_match, $user_agent);
} 

function get_mobile_os()
{
	$agent = strtolower($_SERVER['HTTP_USER_AGENT']);
	$type = 'other';
	if(strpos($agent, 'iphone') || strpos($agent, 'ipad')){
		$type = 'ios';
	}
	if(strpos($agent, 'android')){
		$type = 'android';
	}
	return $type;
}

function get_ip()
{
	if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
   		$myip = $_SERVER['HTTP_CLIENT_IP'];
	}
	else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
   		$myip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	}
	else {
   		$myip = $_SERVER['REMOTE_ADDR'];
	}
	return $myip;
}
?>