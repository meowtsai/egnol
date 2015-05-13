<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/channel_api.php';
require_once dirname(__FILE__).'/rc/rdoauth.ex.class.php';

class Rc extends Channel_Api
{
	var $conf;
	
	function __construct()
	{
		parent::__construct();
		$this->conf = $this->load_config("rc");
		define( "RD_AKEY" , $this->conf["akey"] );
		define( "RD_SKEY" , $this->conf["skey"] );
		define( "RD_CALLBACK_URL" , site_url('gate/login_callback/rc') );		
	}
	
    function login($site)
    {	
    	$oauth = RdOAuthV1::getRdOAuthV1Obj();
    	$oauth->getRequestToken();    	
    	$code_url = $oauth->getAuthorizeURL();
    	header("location: {$code_url}");
    }
    
    function login_callback($site)
    { 
		if (isset($_REQUEST['oauth_token']) && isset($_REQUEST['oauth_verifier'])) 
		{
			$oauth = RdOAuthV1::getRdOAuthV1Obj();
			$token = $oauth->getAccessTocken($_REQUEST['oauth_token'], $_REQUEST['oauth_verifier']);			
			if ( ! empty($token)) {
				$c = new RdOAuthClientV1($token['oauth_token'], $token['oauth_token_secret']);
				$uinfo = (object)$c->getUserInfo();
			}
		}
		else if (isset($_REQUEST['oauth_token']) && isset($_REQUEST['oauth_token_secret'])) 
		{
			$oauth = RdOAuthV1::getRdOAuthV1Obj($_REQUEST['oauth_token'], $_REQUEST['oauth_token_secret']);
 			$uinfo = json_decode($oauth->onRegueset('http://open.raidcall.com.tw/oauth/api.php'));	
		}
				
		if ( ! empty($uinfo)) 
		{
			$user_data = array();
			$user_data['euid'] = $uinfo->id;
			$user_data['name'] = $uinfo->nick;	
			return $user_data;
		}
		else return $this->_return_error('授權失敗');
    }

    function login_callback2($site)
    {
    	$oauth = RdOAuthV1::getRdOAuthV1Obj($_REQUEST['oauth_token'], $_REQUEST['oauth_token_secret']);
 		$uinfo = json_decode($oauth->onRegueset('http://open.raidcall.com.tw/oauth/api.php'));
 		
		$user_data = array();
		$user_data['euid'] = $uinfo->id;
		$user_data['name'] = $uinfo->nick;
		var_dump($user_data);
		return $user_data;
			 		
 		exit();    	
    }
}

/* End of file Template.php */
/* Location: ./system/application/libra
 * ries/Template.php */