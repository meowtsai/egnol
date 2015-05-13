<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/channel_api.php';
require_once(dirname(__FILE__)."/qq/comm/config.php");
require_once(CLASS_PATH."QC.class.php");

class Qq extends Channel_Api
{
    function login($site)
    {	    	
    	$conf = $this->load_config("qq");    
    	 
    	$qc = new QC();
		$qc->qq_login();    	
    }
    
    function login_callback($site)
    {
    	$qc = new QC();
		$acs = $qc->qq_callback();
		$oid = $qc->get_openid();
		
		$qc = new QC($acs, $oid);	
		$arr = $qc->get_user_info();
		$user_data = array();
		$user_data['euid'] = strtolower($oid);
		$user_data['name'] = $arr["nickname"];
		return $user_data;
	}      
}

/* End of file Template.php */
/* Location: ./system/application/libraries/Template.php */