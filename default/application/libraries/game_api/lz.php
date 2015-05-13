<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/kunlun2.php';

class Lz extends Kunlun2 
{	
	function load_game_conf() //override
	{
		return $this->load_config("lz");
	}    
}