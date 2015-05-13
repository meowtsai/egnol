<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/channel_api.php';
require_once(dirname(__FILE__)."/pixnet/PixAPI.php");

class Pixnet extends Channel_Api
{	
	var $api;
	
	function __construct()
	{
				error_reporting(E_ALL);
		ini_set('display_errors','On');
		parent::__construct();
		$this->api = new PixAPI('3fecff177a9ef13b90035d5bd3c136e7', '8b04a38fef14ac800596d161453d62ed');
	}
	
    function login($site)
    {		    	
		list($oauth_token, $oauth_token_secret) = $this->api->getRequestTokenPair();
		// 記錄起來 $request_token & $request_token_secret
		$_SESSION['oauth_token_' . $oauth_token] = $oauth_token_secret;
		header('Location: ' . $this->api->getAuthURL());
    }
    
    function login_callback($site)
    {    	    
		$this->api->setToken($_GET['oauth_token'], $_SESSION['oauth_token_' . $_GET['oauth_token']]);
		list($access_token, $access_token_secret) = $this->api->getAccessToken($_GET['oauth_verifier']);
		// 將 $access_token 和 $access_token_secret 記錄起來，以後就直接使用 $api->setToken($access_token, $access_token_secret) 就可以作 API 的動作了。
		$_SESSION['access_token'] = $access_token;
		$_SESSION['access_token_secret'] = $access_token_secret;
		
		$user = $this->api->user_get_account();
				
		$user_data = array();
		$user_data['euid'] = $user->account->identity;
		$user_data['name'] = $user->account->name;
		return $user_data;
	}      
}

/* End of file Template.php */
/* Location: ./system/application/libraries/Template.php */