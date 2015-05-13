<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/channel_api.php';

class Beanfun_Api extends Channel_Api
{
	var $conf;
	
	function __construct()
	{
		parent::__construct();
		$this->conf = $this->load_config("beanfun");
	}
	
    function login($site)
    {			
		//if ( ! isset($_SESSION['beanfun_id']))
		//{
			$str_ServiceAccountID = $this->CI->input->get("str_ServiceAccountID");
			$str_OTP = $this->CI->input->get("str_OTP");
					
			if (empty($str_ServiceAccountID) && empty($str_OTP)) { //無驗證資料，回beanfun進行登入
				$ServiceCode = $this->conf["sites"][$site]['ServiceCode'];				
				$url = "http://tw.beanfun.com/TW_ThirdPartyWeb/gamelaunch.aspx?ServiceCode={$ServiceCode}&ServiceRegion=B2&ServiceMode=S";
				header("location: {$url}");
				exit();
			}
			
			$result = $this->verify_auth($str_ServiceAccountID, $str_OTP);		
	    	if ($result->Code == '1') 
	    	{    		
				$this->login_to_long_e($result, $site);
	    		$_SESSION['beanfun_id'] = $result->ServiceAccountID;			
	    	}
	    	else 
	    	{
	    		//若驗證失敗則將玩家導回Gamania網頁(http://tw.beanfun.com/playweb/index.aspx)重新操作。
	    		die("<script type='text/javascript'>
	    			alert('{$result->Message}');
	    			location.href='http://tw.beanfun.com/playweb/index.aspx';	
				</script>");
	    		//return $this->_return_error($result->Message);
	    	}
		//}
		
		$ad = $this->CI->input->get("ad");
		
		//進入遊戲			
		$choose_server_url = "http://{$site}.long_e.com.tw/common/choose_server_form?ad={$ad}&";
		$url = "http://www.long_e.com.tw/play_game/{$site}?url=".urlencode($choose_server_url)."&ad={$ad}&";
		header("location: {$url}");	
		exit();			
    }
    
    //登入並成為龍邑會員
    function login_to_long_e($data, $site)
    {
    	$account = strtolower($data->ServiceAccountID).'@beanfun';
		$password = rand(100000, 999999);
		$email = '';
		$name = '';
			
		//登入，若不存在，則建立
		$this->CI->g_user->login($account, $password, $email, $name, $site)
			or die("<script type='text/javascript'>
    					alert('".$this->CI->g_user->error_message."');
    					location.href='http://tw.beanfun.com/playweb/index.aspx'; </script>");
    }
    
    //驗證beanfun傳來資料
    function verify_auth($str_ServiceAccountID, $str_OTP)
    {		
	    //----- 參數的設定：請設定橘子提供參數---- 
	    $url = $this->conf['WS']; //-------------------- ★
		$str_ThirdPartyID = $this->conf['ThirdPartyID'];
		$i_EpochTime = time();		
		$HashKey = $this->conf['HashKey'];
	
	    //----- 呼叫WS：供異業廠商用來驗證beanfun會員身份----- 		
		return AuthenticationWS($url, $str_ThirdPartyID, $str_ServiceAccountID, $str_OTP, $i_EpochTime, $HashKey)->AuthenticationResult;
    }
    
    //檢查餘額是否足夠
    function check_point_enough($str_ServiceAccountID, $int_Point, $site)
    {
	    //----- 參數的設定----- 
	    $url = $this->conf['WS']; //-------------------- ★
		$str_ThirdPartyID = $this->conf['ThirdPartyID'];
		$str_ServiceCode = $this->conf['sites'][$site]['ServiceCode'];
		$i_EpochTime = time();		
		$HashKey = $this->conf['HashKey'];
			
	     //----- 呼叫WS：確認玩家的樂豆點是否足夠----- 		
		return AccountingCheckV2WS($url, $str_ThirdPartyID, $str_ServiceAccountID, $str_ServiceCode, $int_Point, $i_EpochTime, $HashKey)->AccountingCheckV2Result;    	
    }
    
    function trade($str_ServiceAccountID, $int_Point, $site)
    {		
	    //----- 參數的設定：請設定橘子提供參數----- 
	    $url = $this->conf['WS']; //-------------------- ★
		$str_ThirdPartyID = $this->conf['ThirdPartyID'];
		$str_ServiceCode = $this->conf['sites'][$site]['ServiceCode'];
		$i_EpochTime = time();
		$HashKey = $this->conf['HashKey'];
		$str_Memo = $site; //備註欄位。廠商若有多款遊戲時，請帶入遊戲代碼在這個欄位。
			
	    //----- 呼叫WS：扣beanfun!會員的樂豆點----- 		
		return AccountingV2WS($url, $str_ThirdPartyID, $str_ServiceAccountID, $str_ServiceCode, $int_Point, $i_EpochTime, $str_Memo, $HashKey)->AccountingV2Result;	
    }
}

//------ 以下為beanfunc提供之function

	//<summary>
    //呼叫WS：供異業廠商用來驗證beanfun會員身份
    //</summary>
	//<param name="url">WS網址 </param>
    //<param name="str_ThirdPartyID">廠商編號</param>
    //<param name="str_ServiceAccountID">遊戲帳號</param>
    //<param name="str_OTP">OTP</param>
    //<param name="i_EpochTime">驗證時間</param>
    //<param name="HashKey">HasyKey</param>
	function AuthenticationWS($url, $str_ThirdPartyID, $str_ServiceAccountID, $str_OTP, $i_EpochTime, $HashKey)
	{	  
		//資料驗證字串：str_ValidID = MD5(str_ThirdPartyID +str_ServiceAccountID + str_OTP + i_EpochTime + HashKey);
		$str_ValidID  = md5($str_ThirdPartyID .$str_ServiceAccountID . $str_OTP . $i_EpochTime . $HashKey); 
	
		//下面二列讀取WS時一定要保留，不然會發生讀取失敗
		$client = new SoapClient($url); 	
		$client->__setLocation($url); 
		
		//----- WS參數的設定，物件的屬性直接對應到WS的參數名稱----- 
		$aryPara = array("str_ThirdPartyID" => $str_ThirdPartyID,
			"str_ServiceAccountID" => $str_ServiceAccountID,
			"str_OTP" => $str_OTP,
			"i_EpochTime" => $i_EpochTime,
			"str_ValidID" => $str_ValidID);
			
		//----- 擷取 WS回傳資料----- 		
		return $client->Authentication($aryPara);			
	}
	
    //<summary>
    //呼叫WS：確認玩家的樂豆點是否足夠
    //</summary>
    // <param name="str_ThirdPartyID">廠商編號</param>
    // <param name="str_ServiceAccountID">遊戲帳號</param>
    // <param name="str_ServiceCode">遊戲代碼</param>
    // <param name="int_Point">扣點點數</param>
    // <param name="date_EpochTime">驗證時間</param>
    // <param name="HashKey">HasyKey</param>
	function AccountingCheckV2WS($url, $str_ThirdPartyID, $str_ServiceAccountID, $str_ServiceCode, $int_Point, $i_EpochTime, $HashKey)
	{	  		
		//資料驗證字串：str_ValidID = MD5(str_ThirdPartyID +str_ServiceAccountID + i_EpochTime + int_Point + HashKey);
		$str_ValidID = md5($str_ThirdPartyID .$str_ServiceAccountID . $i_EpochTime . $int_Point . $HashKey); 
	
		//下面二列讀取WS時一定要保留，不能會發生讀取失敗	
		$client = new SoapClient($url); 	
		$client->__setLocation($url); 
		
		//----- WS參數的設定，物件的屬性直接對應到WS的參數名稱----- 
		$aryPara = array("str_ThirdPartyID" => $str_ThirdPartyID,
			"str_ServiceAccountID" => $str_ServiceAccountID,
			"str_ServiceCode" => $str_ServiceCode,
			"int_Point" => $int_Point,
			"i_EpochTime" => $i_EpochTime,											
			"str_ValidID" => $str_ValidID);
	  	
		//----- 擷取 WS回傳資料----- 		
		return $client->AccountingCheckV2($aryPara);		
	}
		
    //<summary>
    //呼叫WS：扣beanfun!會員的樂豆點
    //</summary>
    // <param name="str_ThirdPartyID">廠商編號</param>
    // <param name="str_ServiceAccountID">遊戲帳號</param>
    // <param name="str_ServiceCode">遊戲代碼</param>
    // <param name="int_Point">扣點點數</param>
    // <param name="date_EpochTime">驗證時間</param>
    // <param name="str_Memo">備註欄位</param>
    // <param name="HashKey">HasyKey</param>
	function AccountingV2WS($url, $str_ThirdPartyID, $str_ServiceAccountID, $str_ServiceCode, $int_Point, $i_EpochTime, $str_Memo, $HashKey)
	{	  		
		//資料驗證字串：str_ValidID = MD5(str_ThirdPartyID +str_ServiceAccountID + i_EpochTime + int_Point + HashKey);
		$str_ValidID = md5($str_ThirdPartyID .$str_ServiceAccountID . $i_EpochTime . $int_Point . $HashKey); 
	
		//下面二列讀取WS時一定要保留，不然會發生讀取失敗
		$client = new SoapClient($url); 	
		$client->__setLocation($url); 
				
		//----- WS參數的設定，物件的屬性直接對應到WS的參數名稱----- 
		$aryPara = array("str_ThirdPartyID" => $str_ThirdPartyID,
			"str_ServiceAccountID" => $str_ServiceAccountID,
			"str_ServiceCode" => $str_ServiceCode,
			"int_Point" => $int_Point,
			"i_EpochTime" => $i_EpochTime,								
			"str_Memo" => $str_Memo,		
			"str_ValidID" => $str_ValidID);
	  	
		//----- 擷取 WS回傳資料----- 		
		return $client->AccountingV2($aryPara);		
	}		