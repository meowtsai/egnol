<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/kingnet.php';

class Kn_ss extends Kingnet 
{	
	function load_game_conf() //override
	{
		return $this->load_config("kn_ss");
	}
	
}