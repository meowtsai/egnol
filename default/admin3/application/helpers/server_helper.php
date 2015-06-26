<?php

function server_site_url($game_id, $path='')
{
	$CI =& get_instance();
	$CI->load->config("server");
	
	$servers =  $CI->config->item("servers");	
	if ($game_id=='long_e') $game_id='www';
	return "http://".$game_id.".longeplay.com.tw/".$path;
}

