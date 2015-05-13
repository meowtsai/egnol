<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/channel_api.php';

class C_176game extends Channel_Api
{
	var $conf;
	
	function __construct()
	{
		parent::__construct();
		$this->conf = $this->load_config("176game");
	}
	
    function login($site)
    {	    	    	
		$url = "http://www.176Game.com.tw/Partner/Login.aspx?storeid={$this->conf['storeid']}";
		header("location: {$url}");
    }
    
    function login_callback($site)
    {
    	$sign = md5($this->conf['appkey'].$this->CI->input->get("userid").$this->CI->input->get("storeid").$this->CI->input->get('company').$this->CI->input->get('stimestamp'));
		if ($sign == $this->CI->input->get("sign")) {
			$user_data['euid'] = $this->CI->input->get("userid");
    		$user_data['name'] = '';
    		$user_data['email'] = '';
    		return $user_data;
		}
    	else {
    		return $this->_return_error('驗證碼錯誤');
    	}
    }
      
}

/* End of file Template.php */
/* Location: ./system/application/libraries/Template.php */