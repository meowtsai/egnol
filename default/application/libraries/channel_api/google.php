<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/channel_api.php';
require_once(dirname(__FILE__)."/google/Google_Client.php");
require_once(dirname(__FILE__)."/google/contrib/Google_Oauth2Service.php");

class Google extends Channel_Api
{
	var $client, $oauth2;
	
	function __construct()
	{
		parent::__construct();
		$this->client = new Google_Client();
		$this->oauth2 = new Google_Oauth2Service($this->client);		
	}
	
    function login($site)
    {	
		$authUrl = $this->client->createAuthUrl();
		header("location: {$authUrl}");
		exit();
    }
    
    function login_callback($site)
    {
		if (isset($_GET['error']))
		{
			$user_data = array();
			$user_data['error'] = $_GET['error'];
			return $user_data;
		}

//		if (isset($_GET['code'])) {
			$this->client->authenticate();
			$_SESSION['token'] = $this->client->getAccessToken();
/*			$redirect = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
			header('Location: ' . filter_var($redirect, FILTER_SANITIZE_URL));
			exit();
		}

		if (isset($_SESSION['token'])) {
			$this->client->setAccessToken($_SESSION['token']);
		}

    	if ($this->client->getAccessToken())
    	{
*/		  	$user = $this->oauth2->userinfo->get();
			// The access token may have been updated lazily.
//		  	$_SESSION['token'] = $this->client->getAccessToken();
		  	
			$user_data = array();
			$user_data['external_id'] = $user["id"];
			$user_data['name'] = $user["name"];
			$user_data['email'] = filter_var($user['email'], FILTER_SANITIZE_EMAIL);
			return $user_data;
//		}
	}      
}

/* End of file Template.php */
/* Location: ./system/application/libraries/Template.php */