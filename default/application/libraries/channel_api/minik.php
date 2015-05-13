<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/channel_api.php';
require_once dirname(__FILE__).'/minik_phpLib/MINIK_API.php';

class Minik extends Channel_Api
{
	var $conf;
	
	function __construct()
	{
		parent::__construct();
		$this->conf = $this->load_config("minik");		
	}

	function new_minik($site)
	{					
	    if ( ! array_key_exists($site, $this->conf["sites"])) {
    		return $this->_return_error('無串接此遊戲');
    	}
    	
		$minik = new MINIK_API($this->conf["sites"][$site]['secret'], $this->conf["sites"][$site]['key']);
		$minik->Init();
		return $minik;
	}
	
    function login($site)
    {	    	
        if ( ! array_key_exists($site, $this->conf["sites"])) {
    		return $this->_return_error('無串接此遊戲');
    	}
    	$login_url = sprintf($this->conf['login_url'], $this->conf["sites"][$site]['id']); 
    	header("location: {$login_url}");
    }
    
    function login_callback($site)
    {
    	//目前廠商指向  http://www.long_e.com.tw/member/play168/dataExchanger.php
    	return;    	
    }
      
}

/* End of file Template.php */
/* Location: ./system/application/libraries/Template.php */