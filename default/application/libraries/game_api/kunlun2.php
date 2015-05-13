<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/kunlun.php';

class Kunlun2 extends Kunlun
{    
	var $kunlun_conf, $game_conf;
	
	function __construct()
	{
		parent::__construct();
		$this->kunlun_conf = $this->load_config("kunlun2");
		$this->game_conf = $this->load_game_conf();
	}
	
}
