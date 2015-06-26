<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/channel_api.php';
require_once dirname(__FILE__).'/fb/facebook.php';

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

		$this->sdk = new Facebook(array_merge(array(
			'appId'  => $this->conf["appId"],
			'secret' => $this->conf["secret"],
			), $params));
		
		$this->uid = $this->sdk->getUser();
		/*
		try {
			$this->up = $this->sdk->api('/me');
		} catch (FacebookApiException $e) {
			$this->uid = null;
		}*/
	}	
	
    function login($site, $params=array())
    {
	   	$loginUrl = $this->sdk->getLoginUrl(array_merge(array(
   				'scope' => $this->conf["scope"],
   				'redirect_uri' => base_url().'/gate/login_callback/facebook'
   				//'redirect_uri' => 'http://www.longeplay.com.tw/gate/login_callback/facebook'
	   		), $params));
	   	//header("location: {$loginUrl}");
	   	//exit();
    	echo("
    			<meta property='og:image' content='".base_url()."/p/upload/tmp.jpg' />
    			<meta property='og:title' content='龍邑遊戲' />
    			<meta property='og:description' content='超人氣三國大亂鬥，可愛萌英雄任你挑選！一起萌翻三國歷史吧！'>
    			<script> top.location.href='" . $loginUrl . "'</script>
    	");
    }
    
    function login_callback($site)
    {	    	
    	if ($this->uid) 
    	{
    		$user_date = array();
    		
    		$row = $this->CI->db->get_where("users", array("account" => $this->uid."@facebook"))->row();
    		if ($row) 
    		{
    			$user_data['euid'] = $this->uid;
    			$user_data['name'] = $row->name;
    			$user_data['email'] = $row->email;
    		}
    		else 
    		{
    			try {
	    			// Proceed knowing you have a logged in user who's authenticated.
	    			$user_profile = $this->sdk->api('/me');
	    		} catch (FacebookApiException $e) {
	    			return $this->_return_error($e);
	    		} 
	    		    		
	   			$user_data['euid'] = $user_profile["id"];
	   			$user_data['name'] = $user_profile["name"];
	   			if ( ! empty($user_profile['email'])) $user_data['email'] = $user_profile['email'];
    		}   			
    		return $user_data;    		
    	}
    	else {
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