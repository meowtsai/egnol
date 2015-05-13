<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/channel_api.php';

class C_2000fun extends Channel_Api
{
	var $conf;
	
	function __construct()
	{
		parent::__construct();
		$this->conf = $this->load_config("2000fun");
	}
	
    function login($site)
    {	    	    	
    	if ($site == 'long_e') $site_name = '龍邑科技';
    	else {
    		$this->CI->load->model("games");
    		$game = $this->CI->games->get_game($site);
    		$site_name = $game->name;
    	}    	
		$method = 'post';
		$url = bin2hex(site_url("gate/login_callback/2000fun"));
		$appname = bin2hex($site_name);//如要使用中文字請使用UTF-8編碼
		$sign = md5($method.$url.$appname.$this->conf['partner_id'].$this->conf['key']);
		$contents_url = sprintf("%s/method/%s/url/%s/appname/%s/partner/%u/sign/%s", $this->conf['oauth_url'], $method, $url, $appname, $this->conf['partner_id'], $sign);
		$oauth_token = @file_get_contents($contents_url);
		if(strlen($oauth_token) == 32) {
			exit(header(sprintf("Location:%s/%s", $this->conf['connect_url'], $oauth_token)));
		}
		else {
			exit($oauth_token);
		}
    }
    
    function login_callback($site)
    {
    	$uid = $_POST['uid'];
    	$nickname = $_POST['nickname'];
    	$email = $_POST['email'];
    	$time = $_POST['time'];

    	if ($_POST['sign'] == md5($uid.$nickname.$email.$time.$this->conf['key'])) {
    		$user_data['euid'] = $uid;
    		$user_data['name'] = $nickname;
    		$user_data['email'] = $email;
    		return $user_data;
    	} 
    	else {
    		return $this->_return_error('驗證碼錯誤');
    	}
    }
      
}

/* End of file Template.php */
/* Location: ./system/application/libraries/Template.php */