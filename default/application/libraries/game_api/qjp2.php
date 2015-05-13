<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/kunlun2.php';

class Qjp2 extends Kunlun2 
{	
	function load_game_conf() //override
	{
		return $this->load_config("qjp2");
	}    
}

/* End of file Template.php */
/* Location: ./system/application/libraries/Template.php */