<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/channel_api.php';
//require_once dirname(__FILE__).'/fb/facebook.php';
//require_once dirname(__FILE__).'/Facebook/autoload.php';

// Facebook 畫布且配合 IFrame 的方式 ( 用在龍邑fb廣告 ) 初始化 Facebook API 後在別的頁面資料無法抓取到 Facebook Cookie
// 加上 header('P3P: CP=CAO PSA OUR') 才能互通。
header('P3P: CP=CAO PSA OUR');

class Fb_Api extends Channel_Api
{
	var $conf;
	var $sdk;
	var $uid, $up;
	
	function __construct($params=array())
	{
		parent::__construct();
		
		$this->conf = $this->load_config("facebook");
		
        $this->sdk = new Facebook\Facebook([
            'app_id' => $this->conf["appId"],
            'app_secret' => $this->conf["secret"],
            'default_graph_version' => 'v2.2',
        ]);
	}
	
    function login($site, $params=array())
    {
		
		$helper = $this->sdk->getRedirectLoginHelper();

        $permissions = ['email']; // Optional permissions
        $loginUrl = $helper->getLoginUrl(base_url().'/member/login_callback/facebook', $permissions);
		
    	echo("
    			<meta property='og:image' content='/p/upload/tmp.jpg' />
    			<meta property='og:title' content='龍邑遊戲' />
    			<meta property='og:description' content=''>
    			<script> top.location.href='" . $loginUrl . "'</script>
    	");
    }
    
    function login_callback($site)
    {	    	
		$helper = $this->sdk->getRedirectLoginHelper();

        try {
            $accessToken = $helper->getAccessToken();
        } catch(Facebook\Exceptions\FacebookResponseException $e) {
            // When Graph returns an error
            exit;
        } catch(Facebook\Exceptions\FacebookSDKException $e) {
            // When validation fails or other local issues
            exit;
        }

        if (! isset($accessToken)) {
            if ($helper->getError()) {
                header('HTTP/1.0 401 Unauthorized');
                //echo "Error: " . $helper->getError() . "\n";
                //echo "Error Code: " . $helper->getErrorCode() . "\n";
                //echo "Error Reason: " . $helper->getErrorReason() . "\n";
                //echo "Error Description: " . $helper->getErrorDescription() . "\n";
            } else {
                header('HTTP/1.0 400 Bad Request');
                //echo 'Bad request';
            }
            exit;
        }

        $_SESSION['fb_access_token'] = (string) $accessToken;

		try {
            // Returns a `Facebook\FacebookResponse` object
            $response = $this->sdk->get('/me?fields=id,name,email', $accessToken);
        } catch(Facebook\Exceptions\FacebookResponseException $e) {
            //echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch(Facebook\Exceptions\FacebookSDKException $e) {
            //echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }

        $user_profile = $response->getGraphUser();
        $this->uid = $user_profile['id'];
		
		if ($this->uid) 
    	{
    		$user_date = array();
    		
    		$row = $this->CI->db->get_where("users", array("external_id" => $this->uid."@facebook"))->row();
    		if ($row) 
    		{
    			$user_data['email'] = $row->email;
    			$user_data['external_id'] = $this->uid;
    		}
    		else 
    		{		
	   			$user_data['external_id'] = $user_profile["id"];
	   			$user_data['name'] = $user_profile["name"];
	   			if ( ! empty($user_profile['email']))
				{
				   $user_data['email'] = $user_profile['email'];
				}
    		}   			
    		return $user_data;    		
    	}
    	else
		{
    		$this->sdk->destroySession();
    		return $this->_return_error("尚未登入");
    	} 
		
    }
     
    function chk_fb_fan($uid, $page_id)
    {
    	$fql = "SELECT type, page_id FROM page_fan WHERE uid='{$uid}' and page_id='{$page_id}'";
    	$ret = $this->sdk->api(array(
    			'method' => 'fql.query',
    			'query' => $fql,
    	));
    	return count($ret) > 0 ? true : false;
    }
}

/* End of file Template.php */
/* Location: ./system/application/libraries/Template.php */