<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/game_api.php';

class Mon extends Game_Api
{    
    function login($server, $user, $ad)
    {	    	
    	if ( ! empty($server->merge_address)) { //併服
    		$this->CI->load->model("games");
    		$merge_server = $this->CI->games->get_server($server->merge_address);
    		if ($merge_server) {
    			$server_address = $merge_server->address;
    		} else $server_address = $server->address;
		} else $server_address = $server->address;
    	
    	$conf = $this->load_config("mon");
    	$login_url = "http://{$server_address}/flash/index.php";
    	$username = $user->account;
    	$time = time();    	
    	/*	cm: 防沉迷
    	  	0 已经登记信息但没有满18岁（未通过防沉迷）
			1 已经登记信息并且已经满18岁（已通过防沉迷）
			2 没有登录信息（未通过防沉迷，应该提醒用户填写） */
    	$cm = 1;
    	$flag = md5($username . $time . $conf['key'] . $cm);    	
    	$login_url .= "?username={$username}&time={$time}&flag={$flag}&cm={$cm}&ad={$ad}";

    	if ($ad == 'mon_yahoo_a' || $ad == 'yahoo_keyword') {
    		die('
    			<html><head>
				<SCRIPT language="JavaScript" type="text/javascript">
				<!-- Yahoo! Taiwan Inc.
				window.ysm_customData = new Object();
				window.ysm_customData.conversion = "transId=,currency=,amount=";
				var ysm_accountid  = "17LA2JFSJP5R3LSLEIA6LA6RP4S";
				document.write("<SCR" + "IPT language=\'JavaScript\' type=\'text/javascript\' " 
				+ "SRC=//" + "srv1.wa.marketingsolutions.yahoo.com" + "/script/ScriptServlet" + "?aid=" + ysm_accountid 
				+ "></SCR" + "IPT>");				
    			
         		setTimeout(function() {location.href="'.$login_url.'";}, 800);
    				    				
   				// -->
				</SCRIPT>   
    			</head><body></body></html> 				
    		');
    	}
    	else if ($ad == 'scupio') {
    		die('
    			<html><head>
				<script type="text/javascript" src="//rec.scupio.com/recweb/js/rec.js">
				{"mid":5454,"pid":"landing"}</script>
				<script type="text/javascript"> 
				var _bwp=192; var _bwpid=\'landing\'; </script>
				<script type="text/javascript" src="//adsense.scupio.com/conv/js/conv.js"></script>				
				<script type="text/javascript"> 
				var _bwp=192; var _bwpid2=\'buy\'; </script>
				<script type="text/javascript" src="//adsense.scupio.com/conv/js/convbtn.js"></script>    				
    			<script type="text/javascript">
    			bw_conv();
         		setTimeout(function() {location.href="'.$login_url.'";}, 800);    				    				    				
				</script>   
    			</head><body></body></html> 				
    		');    		
    	}
    		
    	
		header("location: {$login_url}");
    	exit();
    }
    
    function transfer($server, $billing, $rate=1)
    {	
    	//判斷有無角色
		$user = (object) array("uid"=>$billing->uid, "account"=>$billing->account);
		$re = $this->check_role_status($server, $user);
		
    	if ($re == "-1") return "-1";
    	else if ($re == "0") return $this->_return_error("該伺服器沒有角色(".$this->error_message.")");    	
    	
    	intval($billing->amount*intval($rate)/10);
    	
		$paynum		= $billing->id;
		$paytouser	= urlencode($billing->account);
		$paygold	= intval($billing->amount * $rate);
		$time		= time();
		$key 		= 'YzM5NzFmZjVkNjNlMWUyZGNmOGRiMDM0MWQ2MzAzYT';
		$flag		= md5($paynum . $billing->account . $paygold . $time . $key);
		$rmb		= $billing->amount;
		$group		= '100'.strtr($server->address, array("mon"=>"",".long_e.com.tw"=>""));				
		if ($group == '1000') $group = '1999'; //測服固定為1001
		
		//echo "md5(".$paynum . $objAuthUser->account . $paygold . $time . $key.") = ";
		//echo $flag."<br>"; 
		
    	if ( ! empty($server->merge_address)) { //併服
    		$this->CI->load->model("games");
    		$merge_server = $this->CI->games->get_server($server->merge_address);
    		if ($merge_server) {
    			$server_address = $merge_server->address;
    		} else $server_address = $server->address;
		} else $server_address = $server->address;
           
		$str = "$paynum|$paytouser|$paygold|$time|$flag|$rmb|$group";
		$url = "http://{$server_address}/flash/addpoint.php?p={$str}";
        
        $msg_ary = array(
        	'1'	=> '成功',
        	'2' => '訂單重複',
        	'-1' => '參數格式錯誤',
        	'-2' => '驗證失敗',
        	'-3' => '用戶不存在',
        	'-4' => '請求超時',
        	'-90' => 'DB操作異常',		
        );
        $msg_ary["-93"] = $msg_ary["-92"] = $msg_ary["-91"] = $msg_ary["-91.5"] = $msg_ary["-90"];  
                
        $maximumLoopNum = 3;  //最大測試次數
    	while ( $maximumLoopNum-- > 0 ) {
    		$re = $this->curl($url);
    		if (empty($re)) sleep(1); //對方無反應，隔1秒再試
    		else break;
    	} 
    	
        if (empty($re)) {
    		return "-1";
    	}
    	else {
			
    		if (substr($re, 0, 3 ) == "\xEF\xBB\xBF") $re = substr_replace($re, '', 0, 3); //去bom頭
    	    		
	    	if ($re == '1') {
	    		return '1';
	    	}
	    	else {
				if (array_key_exists($re, $msg_ary)) {
        			$msg = $msg_ary[$re];	
        		} else $msg = "未知錯誤:".var_export($re, true);
		    	return $this->_return_error($msg);
		    }
    	}     
    }      

    //判斷有無角色
    function check_role_status($server_row, $user_row)
    {    	
		if ( ! empty($server_row->merge_address)) { //併服
    		$this->CI->load->model("games");
    		$merge_server = $this->CI->games->get_server($server_row->merge_address);
    		if ($merge_server) {
    			$server_address = $merge_server->address;
    		} else $server_address = $server_row->address;
		} else $server_address = $server_row->address;
    	
		$username = $user_row->account;
		$url = "http://{$server_address}/flash/check_role_exists.php";
		$group = '100'.strtr($server_row->address, array("mon"=>"",".long_e.com.tw"=>""));	    	
		if ($group == '1000') $group = '1999'; //測服固定為1001

		$maximumLoopNum = 3;
        while ($maximumLoopNum-- > 0) { 
			$re = my_curl($url, array("username"=>$username, "group"=>$group));	
    		if (empty($re)) sleep(1); //對方無反應，隔1秒再試
    		else break;
        }           
        
        if (substr($re, 0, 3 ) == "\xEF\xBB\xBF") $re = substr_replace($re, '', 0, 3); //去bom頭
        
        if ($re == '') {
    		return "-1";
    	}
    	else {
    		if (trim($re) == '1') {
    			return '1';
    		}
	    	else {
		    	return $this->_return_error($re);
    		}
    	} 
    }    
    
}

/* End of file Template.php */
/* Location: ./system/application/libraries/Template.php */