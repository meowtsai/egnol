<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/channel_api.php';
require_once dirname(__FILE__).'/yahoo/common.inc.php';
	
class Yoid extends Channel_Api
{
	var $conf;
	
	function __construct() 
	{
		parent::__construct();
		$this->conf = $this->load_config("yoid");
		
		# openid/oauth credentials
		define('OAUTH_CONSUMER_KEY', $this->conf['consumer_key']);
		define('OAUTH_CONSUMER_SECRET', $this->conf['consumer_secret']);
		define('OAUTH_DOMAIN', 'www.long_e.com.tw');
		define('OAUTH_APP_ID', $this->conf['app_id']);
	}
	
    function login($site)
    {	    	
    	$oauthapp = new YahooOAuthApplication(OAUTH_CONSUMER_KEY, OAUTH_CONSUMER_SECRET, OAUTH_APP_ID, OAUTH_DOMAIN);   	
		$oauth_request_token = $oauthapp->getRequestToken("http://www.long_e.com.tw/gate/login_callback/yoid");
    	$_SESSION['oauth_token'] = $oauth_request_token->to_string();
    	    	
    	$url = $oauthapp->getAuthorizationUrl($oauth_request_token); 
    	header("location: {$url}");
    	exit;    	
    }
    
    function login_callback($site)
    {
    	$oauthapp = new YahooOAuthApplication(OAUTH_CONSUMER_KEY, OAUTH_CONSUMER_SECRET, OAUTH_APP_ID, OAUTH_DOMAIN);
    	
    	if (empty($_SESSION['oauth_token'])) {
    		return $this->_return_error("認證已過時。");
    	} 
    	
    	$oauth_token = YahooOAuthAccessToken::from_string($_SESSION['oauth_token']);
		$access_token = $oauthapp->getAccessToken($oauth_token,  $_GET['oauth_verifier']);
    	$oauthapp->refreshAccessToken($access_token);

    	$obj  = $oauthapp->getProfile();    	
    	if ( ! empty($obj)) 
	    { 	    	
			$user_data['euid'] = strtolower($obj->profile->guid);
			
			if ( ! empty($obj->profile->familyName) && ! empty($obj->profile->givenName)) {
    			$user_data['name'] = $obj->profile->familyName.$obj->profile->givenName;
			}
			else if ( ! empty($obj->profile->nickname)) {
				$user_data['name'] = $obj->profile->nickname;
			}

			/* yahoo不提供了
	    	if ( ! empty($obj->profile->emails)) {
	    		if (is_array($obj->profile->emails)) {
	    			$user_data['email'] = @$obj->profile->emails[0]->handle;
	    		}
    			else $user_data['email'] = $obj->profile->emails->handle;
			}
			*/
    		return $user_data;
	    }
	    else { 	    	
	    	//取得個人資料失敗	    	
	    	$user_data['euid'] = strtolower($oauthapp->getGUID());	    	
	    	return $user_data;
	    }    	
    }
      
}

/* End of file Template.php */
/* Location: ./system/application/libraries/Template.php */