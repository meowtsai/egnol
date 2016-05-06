<?php
require_once dirname(__FILE__).'/config.php';

//define('IN_OFFICE', in_array($_SERVER["REMOTE_ADDR"], g_conf("office_ip")) && empty($_GET["lock"]) ? TRUE : FALSE);
if (isset($_SERVER["REMOTE_ADDR"])) {
    
    if(empty($_GET["lock"])) {
        
        $whitelist = g_conf("office_ip");
        $count = count($whitelist); 
        for ($i = 0; $i < $count; $i++) { 
            if ($whitelist[$i]==$_SERVER["REMOTE_ADDR"]) {
                define('IN_OFFICE', TRUE);
                break;
            }
            
            if (strpos($whitelist[$i], '/')) {
                list($net, $mask) = explode('/', $whitelist[$i]); 
                if ((ip2long($_SERVER["REMOTE_ADDR"]) & ~((1 << (32 - $mask)) - 1)) == ip2long($net)) {
                    define('IN_OFFICE', TRUE);
                    break;
                } 
            }
        } 
    }
    
    if(!defined('IN_OFFICE')) define('IN_OFFICE', FALSE);
}

switch (ENVIRONMENT)
{
	case 'development':
		error_reporting(E_ERROR | E_WARNING | E_PARSE);
		ini_set('display_errors',1);
		break;

	case 'testing':
	case 'production':
		error_reporting(0);
		ini_set('display_errors','Off');
		break;

	default:
		exit('The application environment is not set correctly.');
}

date_default_timezone_set("Asia/Taipei"); //設定時區

$a = session_id(); //開啟session
if (empty($a)) {		
	ini_set('session.save_handler', g_conf("session", "save_handler"));
	ini_set('session.save_path', g_conf("session", "save_path"));
	ini_set('session.use_cookies', 1);
	ini_set('session.cookie_domain', g_conf("session", "cookie_domain"));
	ini_set('session.gc_maxlifetime', g_conf("session", "gc_maxlifetime")); 
	session_start();
}

function g_conf($key, $key2='') //取得global_config資料
{
	global $g_config;
	if (array_key_exists($key, $g_config)) {
		$item = $g_config[$key];
		if ($key2) {
			if (array_key_exists($key2, $item)) {
				$item = $item[$key2];
			}
			else $item = false;
		}
	}
	else $item = false;
	return $item;
}

function rsync_to_slave()
{
	$master_web = g_conf("master_web");
	$slave_web = g_conf("slave_web");
	
	if ($master_web['wan_ip'] ==  $_SERVER["SERVER_ADDR"]) {
		exec("sudo /usr/bin/rsync -avlR --delete -e ssh /var/www/html/ {$slave_web['lan_ip']}:/");
	}		
}