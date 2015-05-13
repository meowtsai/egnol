<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/channel_api.php';

class More extends Channel_Api
{
	var $conf;
	
	function __construct()
	{
		parent::__construct();
		$this->conf = $this->load_config("more");
	}
	
    function login($site)
    {	    	
    	if ( ! array_key_exists($site, $this->conf["sites"])) {
    		return $this->_return_error('無串接此遊戲');
    	}
    	header("LOCATION: {$this->conf['sites'][$site]}");
    }
    
    function login_callback($site)
    {
    	$key = $this->conf["key"];  
    	
    	$userid = $this->CI->input->get('userid');
    	$useremail = $this->CI->input->get('useremail');
    	$game = $this->CI->input->get('game');
    	$company = $this->CI->input->get('company');
    	$stimestamp = $this->CI->input->get('stimestamp');
    	$sign = $this->CI->input->get('sign');
           
		if ($sign == md5($key.$userid.$company.$stimestamp)) {
		    $user_data['euid'] = $userid;
    		$user_data['email'] = $useremail;
    		return $user_data;
    	} 
    	else {
    		return $this->_return_error('驗證碼錯誤');
    	}   
    }
      
}

/* End of file Template.php */
/* Location: ./system/application/libraries/Template.php */