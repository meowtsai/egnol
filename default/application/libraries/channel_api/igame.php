<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/channel_api.php';

class Igame extends Channel_Api
{
	var $conf;
	
	function __construct()
	{
		parent::__construct();
		$this->conf = $this->load_config("igame");		
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
	    $key = $this->conf['key'];
	    	
	    $userid = $this->CI->input->get('userid');
	    $gamecode = $this->CI->input->get('gamecode');
	    $time = $this->CI->input->get('time');
	    $signmsg = $this->CI->input->get('signmsg');
	
	    if (md5($userid.$key.$time) == $signmsg ) 
	    {
	    	if (array_key_exists($gamecode, $this->conf['mapping'])) { 
	        	$site = $this->conf['mapping'][$gamecode];
	    	} else $site = $gamecode;
	        
			$user_data['euid'] = $userid;
			$user_data['site'] = $site;
    		return $user_data;
	    }        	 
    	else {
    		return $this->_return_error('驗證碼錯誤');
    	}  
    }
      
}

/* End of file Template.php */
/* Location: ./system/application/libraries/Template.php */