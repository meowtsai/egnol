<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/channel_api.php';

class Gogobox extends Channel_Api
{
	var $conf;
	
	function __construct()
	{
		parent::__construct();
		$this->conf = $this->load_config("gogobox");
	}
	
    function login($site)
    {	    	    	
    	if ( ! array_key_exists($site, $this->conf["sites"])) {
    		return $this->_return_error('無串接此遊戲');
    	}
    	header("LOCATION: {$this->conf['sites'][$site]["login_url"]}");
    }
    
    function login_callback($site)
    {
    	$key1 = $this->conf['sites'][$site]['key1'];
    	$key2 = $this->conf['sites'][$site]['key2'];
    	
    	$uid = $this->CI->input->post("UserID");
		$login_time = $this->CI->input->post("LoginTime");
		$code = $this->CI->input->post("TransferCode");

		$part1 = substr(md5("{$uid}_".substr($login_time,2)."_{$key1}"), 0, 25);
		$part2 = substr(md5("{$uid}_".substr($login_time,2)."_{$key2}"), -15);

       	if ($part1.$part2 == $code) 
    	{    		
			$user_data['euid'] = $uid;
    		$user_data['name'] = $uid;
    		return $user_data;
    	}
    	else{
    		return $this->_return_error('驗證碼錯誤');
    	}
    }
      
}

/* End of file Template.php */
/* Location: ./system/application/libraries/Template.php */