<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/channel_api.php';

class Play168 extends Channel_Api
{
    function login($site)
    {	    	
    	$conf = $this->load_config("play168");
    	if ( ! array_key_exists($site, $conf["sites"])) {
    		return $this->_return_error('無串接此遊戲');
    	}
    	header("LOCATION: {$conf['sites'][$site]}");
    }
    
    function login_callback($site)
    {
    	//目前廠商指向  http://www.longeplay.com.tw/member/play168/dataExchanger.php
    	return;    	
    }
      
}

/* End of file Template.php */
/* Location: ./system/application/libraries/Template.php */