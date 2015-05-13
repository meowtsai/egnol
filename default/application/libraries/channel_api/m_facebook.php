<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/channel_api.php';

class M_Facebook extends Channel_Api
{	
    function login($site)
    {
    	header('location: http://www.long_e.com.tw/api/m_res_facebook');
    	exit();
    }
    
    function login_callback($site)
    {	    	
    }
}

/* End of file Template.php */
/* Location: ./system/application/libraries/Template.php */