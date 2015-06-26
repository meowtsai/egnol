<?php 

date_default_timezone_set("Asia/Taipei");

$a = session_id();
if (empty($a)) {
	
	ini_set('session.save_handler', 'memcache');
	ini_set('session.save_path', 'tcp://localhost:11211?persistent=1&weight=1&timeout=1&retry_interval=15');
	//ini_set('session.cookie_domain', '127.0.0.1');
	//session_set_cookie_params(0, '/', '.longeplay.com.tw');
	
	session_start();
}
