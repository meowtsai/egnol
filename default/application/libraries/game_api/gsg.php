<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/game_api.php';

class Gsg extends Game_Api
{
    var $conf;

    function __construct()
    {    
    	parent::__construct();
    	$this->conf = $this->load_config("gsg");
    }
    
    function login($server, $user, $ad)
    {
    	$url = "http://".base_url();
    	
		header("location:{$url}");
    }
    
    function transfer($server, $billing, $rate=1)
    {	
    	return '1'; 	
    }    
    
	//判斷有無角色
    function check_role_status($server, $user)
    {    
		return;
    }        
}

/* End of file Template.php */
/* Location: ./system/application/libraries/Template.php */