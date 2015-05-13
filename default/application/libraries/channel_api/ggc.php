<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/channel_api.php';

class Ggc extends Channel_Api
{
    function login($site)
    {	    	
    	$conf = $this->load_config("ggc");
    	header("LOCATION: {$conf['login_url']}");
    }
    
    function login_callback($site)
    {
    	//目前廠商指向  http://www.long_e.com.tw/member/play168/dataExchanger.php
    	return;    	
    }
      
}

/* End of file Template.php */
/* Location: ./system/application/libraries/Template.php */