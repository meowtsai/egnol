<?php

require_once(dirname(__FILE__)."/../../global.php");

define("CASH_FLOW_DEBUG", FALSE);        //內定FALSE

$returnUrl = 'http://'.$_SERVER['SERVER_NAME'].$_SERVER['PHP_SELF'];  //頁面所在路徑
//
define("DOC_ROOT",	dirname(dirname(__FILE__)).'/');	// 網站相對根目錄
define("DOMAIN_NAME",	"www.long_e.com.tw");	// 網站名稱
define("SYS_ROOT",	DOC_ROOT . "admin/");   // 系統名稱
define("INC_ROOT",	DOC_ROOT . "Inc/");          //子網域環境設定
define("IMG_ROOT",	DOC_ROOT . "img/");
define("LIB_ROOT",	DOC_ROOT . "libraries/");    //子網域函式庫
define("SYSLIB_ROOT", g_conf("http_document_root")."lib/"); //全網域函式庫
define("TMP_ROOT",  "/tmp/");

//require_once (LIB_ROOT . 'lib.php');          //註解此行，應由各頁面自行載入

//pdo
define("DB_HOST",	g_conf("db", "hostname"));
define("DB_NAME",   g_conf("db", "database"));
define("DB_LOGIN",  g_conf("db", "username"));
define("DB_PASS",   g_conf("db", "password"));
define("DB_PORT",   g_conf("db", "port"));

try 
{
	$myPDO = new PDO('mysql:host='.DB_HOST.';port='.DB_PORT.';dbname='.DB_NAME, DB_LOGIN, DB_PASS);
	$myPDO->query("SET NAMES 'utf8'");
	$myPDO->setAttribute(PDO::FETCH_BOTH, 0);
} 
catch (PDOException $e) 
{
	print "Error!: " . $e->getMessage() . "<br/>";
	die();
}

//伺服器列表
$game_server_list = array('mj1'=>"mj1.long_e.com.tw", 'mj2'=>"mj2.long_e.com.tw", 'mj3'=>"mj3.long_e.com.tw");              //伺服器串接位置
$aryServerList = array(
                        'mj'=>array(
                                    'mj1'=>array(
                                                'name'=>'魔晶幻想-希望之路',
                                                'address'=>'mj1.long_e.com.tw',
                                                'server_status'=>'public'
                                                ),
                                    'mj2'=>array(
                                                'name'=>'魔晶幻想-星空之城',
                                                'address'=>'mj2.long_e.com.tw',
                                                'server_status'=>'public'
                                                ),
                                    'mj3'=>array(
                                                'name'=>'魔晶幻想-魔幻森林',
                                                'address'=>'mj3.long_e.com.tw',
                                                'server_status'=>'public'
                                                )
                                    ),
                        'sg'=>array(
                                    'sg1'=>array(
                                                'name'=>'三國風雲-獨霸群雄',
                                                'address'=>'312001',
                                                'server_status'=>'public'
                                                ),
                                    'sg2'=>array(
                                                'name'=>'三國風雲-XXXXX',
                                                'address'=>'312002',
                                                'server_status'=>'hide'
                                                ),
                                    'sg3'=>array(
                                                'name'=>'三國風雲-XXXXX',
                                                'address'=>'312003',
                                                'server_status'=>'hide'
                                                )
                                    )
                      );          //伺服器代號
                 
                      
/** FaceBook Environment Var **/
define('FACEBOOK_APP_ID', '121799614502274');
define('FACEBOOK_SECRET', '68053a3ba8afb32c0158a5ea4d8f57fc');

/** Yahoo API Environment Setting **/
// Your Consumer Key (API Key) goes here <br /> /n.  
define('CONSUMER_KEY', "dj0yJmk9ZnBqS2JDQWFna3lFJmQ9WVdrOVVqaGhSREYxTTJNbWNHbzlNVE0xT1RBeE5qVTJNZy0tJnM9Y29uc3VtZXJzZWNyZXQmeD1kZQ--");
// Your Consumer Secret goes here.  
define('CONSUMER_SECRET', "84606b896c9918854519fc74ffb8259d15a719b2");
// Your application ID goes here.  
define('APPID', "R8aD1u3c");
//end of file


/** Error Recorder **/
require_once(SYSLIB_ROOT.'class.err_handler.php');
