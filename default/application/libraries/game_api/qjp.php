<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/kunlun.php';

class Qjp extends Kunlun 
{	
	function load_game_conf() //override
	{
		return $this->load_config("qjp");
	}    
}

/* End of file Template.php */
/* Location: ./system/application/libraries/Template.php */