<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/channel_api.php';
require_once dirname(__FILE__).'/oauth.php';

class Bahamut extends Channel_Api
{
    function login($site)
    {	    	
    	$conf = $this->load_config("bahamut");    	   		
    	if ( ! array_key_exists($site, $conf["sites"])) {
    		return $this->_return_error('無串接此遊戲');
    	}
    	 
    	$key = $conf["sites"][$site]["key"];
    	$secret = $conf["sites"][$site]["secret"];    	
    	$request_token_endpoint = $conf["request_token_url"];
    	$authorize_endpoint = $conf["authorize_url"];    		 
    	$confirm_url = site_url("gate/login_callback/bahamut");    	
    	
    	$test_consumer = new OAuthConsumer($key, $secret, NULL);
    	
    	//prepare to get request token    	
    	$sig_method = new OAuthSignatureMethod_HMAC_SHA1();
   		$parsed = parse_url($request_token_endpoint);
   		$params = array('oauth_callback' => $confirm_url);
    	
    	$req_req = OAuthRequest::from_consumer_and_token($test_consumer, NULL, "GET", $request_token_endpoint, $params);
    	$req_req->sign_request($sig_method, $test_consumer, NULL);
   	
    	$req_token = $this->do_http_request($req_req->to_url());

    	//assuming the req token fetch was a success, we should have
    	//oauth_token and oauth_token_secret    	
    	parse_str($req_token, $tokens);
    	
    	$oauth_token = $tokens['oauth_token'];
    	$oauth_token_secret = $tokens['oauth_token_secret'];
    	
    	$_SESSION['oauth_token_secret'] = '3gd' . $oauth_token_secret.'ahp';
    	
   		//$callback_url = "$base_url/baha_accesstoken_callback.php?key=$key&token=$oauth_token&token_secret=$oauth_token_secret&endpoint=" . urlencode($authorize_endpoint);
    	$auth_url = $authorize_endpoint . "?oauth_token=$oauth_token";
    	die($auth_url);

   		//Forward us to callback app for auth    	
    	header("Location: $auth_url");
    	exit();
    }
    
    function login_callback($site)
    {
    	$memberLoginUrl = 'http://'.base_url().'/member/login.php';
    	
    	if (!empty($_GET['oauth_token']) && !empty($_GET['oauth_verifier']) && ($_GET['xoauth_allow'] == 1) )
    	{       		
    		$conf = $this->load_config("bahamut");
    		if ( ! array_key_exists($site, $conf["sites"])) {
	    		return $this->_return_error('無串接此遊戲');
	    	}
    		
    		$key = $conf["sites"][$site]["key"];
    		$secret = $conf["sites"][$site]["secret"];    		 
    		$oauth_access_token_endpoint = $conf["access_token_url"];
    		$oauth_authorize_endpoint = $conf["authorize_url"];    		
    		    		 
    		//We were passed these through the callback.
    		$token = $_REQUEST['oauth_token'];
    		$token_verifier = $_REQUEST['oauth_verifier'];
    		$token_secret = substr($_SESSION['oauth_token_secret'],3,32);
    		 
    		$consumer = new OAuthConsumer($key, $secret);
    		$auth_token = new OAuthConsumer($token, $token_secret);
    		$oauth_token = new OAuthToken($secret,$token_secret);
    		$access_token_req = new OAuthRequest("GET", $oauth_access_token_endpoint);
    		$access_token_req = $access_token_req->from_consumer_and_token($consumer, $auth_token, "GET", $oauth_access_token_endpoint, array('oauth_verifier'=>$token_verifier));
    		
    		$access_token_req->sign_request(new OAuthSignatureMethod_HMAC_SHA1(), $consumer, $auth_token);
    		$after_access_request = $this->do_http_request($access_token_req->to_url());
    		 
    		parse_str($after_access_request, $access_token);      //analysis the retreived data of bahamut user uid
    		
    		if ( !empty($access_token['xoauth_baha_userid']) ) {      //Check BahaMut users unique ID GETED or not
    			/**
    			 * Example
    			 * array(3) {  ["oauth_token"]=>  string(41) "ed1f60e88534d2e4275a1562b0f4128504e28ee47"  ["oauth_token_secret"]=>  string(32) "9dfdd756d89651843fcf9b3d9fcaeb7a"  ["xoauth_baha_userid"]=>  string(32) "bc4845e3842f7e9ba96e8602fa4161eb"
    			 */   			
    			    			 
    			$user_date = array();
    			if (!empty($access_token) && (strlen($access_token['xoauth_baha_userid']) > 5)) 
    			{
    				$user_data['euid'] = $access_token['xoauth_baha_userid'];
    				$user_data['name'] = $access_token['xoauth_baha_userid'].'@bahamut';
    				
    				return $user_data;
    			}
    			else {
    				return $this->_return_error('認證錯誤');
    			}
    		}
    		else
    		{
    			echo '交握失敗<br />Prepare to reconnect again at 3 sec...<br />';
    			flush();
    			sleep(5);
    			echo '<script type="text/javascript">location.replace("'.$memberLoginUrl.'")</script>';
    			 
    			exit('ends here');
    		}
    	}
    	else 
    	{
    		if (!empty($_GET['oauth_token']) && !empty($_GET['oauth_verifier']) && ($_GET['xoauth_allow'] == 0) ){
    			
    			$rejectAgreementUrl = $memberLoginUrl.'?msg=您剛拒絕授權巴哈姆特登入權,講選擇其他登入方式';
    			
    			header('LOCATION:'.$rejectAgreementUrl);    			 
    			exit();
    		}
    		else {
    			return $this->_return_error('參數錯誤');
    		}
    	}
    }
      
}

/* End of file Template.php */
/* Location: ./system/application/libraries/Template.php */