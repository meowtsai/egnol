<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/channel_api.php';

class Gamebase extends Channel_Api
{
    function login($site)
    {	
    	$conf = $this->load_config("gamebase");
    	if ( ! array_key_exists($site, $conf["sites"])) {
    		return $this->_return_error('無串接此遊戲');
    	}
    	header("LOCATION: {$conf["login_url"]}?game_id={$site}");
    }
    
    function login_callback($site)
    {       	
    	$conf = $this->load_config("gamebase");
    	$game_base_private_key = $conf['key'];
    	$id = $_GET['id'];
    	$time = $_GET['time'];
    	$website = $_GET['website'];
    	$key = $_GET['key'];
    	$game_id = $_GET["game_id"];
    	
    	$keyStr = 'gb_'.$id.'_'.$time.'_'.$game_base_private_key;
    	$keyGen =  substr(strtoupper(md5(strtolower($keyStr))),5,10);
    	if ($keyGen == $_GET['key']) 
    	{    		
			$user_data['euid'] = $id;
    		$user_data['name'] = $id.'@gamebase';
    		$user_data['site'] = $game_id; //game_id與site 可能不同，須寫回去

    		return $user_data;
    	}
    	else{
    		return $this->_return_error('驗證碼錯誤');
    	}
    }
      
}

/* End of file Template.php */
/* Location: ./system/application/libraries/Template.php */