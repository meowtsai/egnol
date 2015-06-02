<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Link extends MY_Controller {

	function jh_fb_push()
	{
		header('location: https://www.facebook.com/long_e.inc/app_1414847435430915');
		exit();
	}
	
	function dxc_fb_push()
	{
		header('location: https://www.facebook.com/long_e.inc/app_720957214611747');
		exit();
	}
	
	function sw_fb_push()
	{
		header('location: https://www.facebook.com/long_e.inc/app_1481979295353624');
		exit();
	}
	
	function hg_fb_push()
	{
		die('hg_fb_push');
		header('location: https://www.facebook.com/long_e.inc/app_1481979295353624');
		exit();
	}

	//空白頁
	function s()
	{
		die('');
	}
	
	//空白圖片
	function s_gif()
	{
		$fileres = file_get_contents('http://'.base_url().'/p/img/s.gif');
		header('Content-type: image/gif');
		echo $fileres;		
	}
	
	//空白頁
	function pass($url)
	{
		//die($url);
		header('location: '.urldecode($url));
		exit();
	}
}
