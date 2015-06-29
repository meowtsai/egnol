<?php 

$db['long_e']['hostname'] = g_conf("db", "hostname");
$db['long_e']['username'] = g_conf("db", "username");
$db['long_e']['password'] = g_conf("db", "password");
$db['long_e']['database'] = g_conf("db", "database");
$db['long_e']['dbdriver'] = 'mysql';
$db['long_e']['dbprefix'] = '';
$db['long_e']['pconnect'] = FALSE;
$db['long_e']['db_debug'] = g_conf("db", "db_debug");
$db['long_e']['cache_on'] = FALSE;
$db['long_e']['cachedir'] = '';
$db['long_e']['char_set'] = 'utf8';
$db['long_e']['dbcollat'] = 'utf8_general_ci';
$db['long_e']['swap_pre'] = '';
$db['long_e']['autoinit'] = TRUE;
$db['long_e']['stricton'] = FALSE;
$db['long_e']['port'] 	= g_conf("db", "port");

?>
