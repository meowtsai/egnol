<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/game_api.php';

class Zj extends Game_Api
{
    var $conf;

    function __construct()
    {    
    	parent::__construct();
    	$this->conf = $this->load_config("zj");
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
    
    function get_lv($sid, $euid)
    {
    	$url = "http://203.75.245.81:3000/sg_user?serv_id={$sid}&acc_id={$euid}";   	
    	$re = $this->curl($url);
   		
	   	if ($re == '') return '-1';
	   	else 
	   	{	   		
	   		$json = json_decode($re);
	   		if (empty($json)) $this->_return_error("json解析錯誤");
	   		
	   		if ($json->status == '0') {
	   			return $json->player->level;
	   		}
	   		else {
	   			return $this->_return_error("錯誤代碼 {$json->status}");
	   		}
	    }
    }
}

/* End of file Template.php */
/* Location: ./system/application/libraries/Template.php */