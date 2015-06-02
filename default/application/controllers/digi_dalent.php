<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Digi_Dalent extends MY_Controller {		

	var $conf = array(
		"ry" => array(
			"game" => "ry",
			"facebook" => "https://www.facebook.com/long_e.inc",
		)		
	);
	
	var $game, $product, $server; 
	
	function __construct()
	{
		parent::__construct();
		$this->product = $this->input->get("product");
		$this->server = $this->input->get("server");
		if (empty($this->product)) {
			die('參數不足');
		}
		
		$this->game = $this->conf[$this->product]["game"]; 
	}
	
	
	function pay_entry()
	{
		header("location: ".base_url()."/payment?game={$this->game}");
	}
		
	function feedback_entry()
	{
		header("location: ".base_url()."/service/question?game={$this->game}");
	}
	
	function portal_entry()
	{
		header("location: http://{$this->game}.long_e.com.tw");
	}
	
	function facebook_entry()
	{
		header("location: ".$this->conf[$this->product]["facebook"]); 
	}
	
	function forum_entry()
	{
		header("location: ".$this->conf[$this->product]["facebook"]);
	}
	
}
