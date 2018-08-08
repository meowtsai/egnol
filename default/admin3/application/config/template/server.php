<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$fn = array(
			'game/modify' => array('name' => '遊戲設定', 'path' => 'game/modify'),
			'server' => array('name' => '伺服器管理', 'path' => 'server/get_list'),
			'news' => array('name' => '最新消息', 'path' => 'bulletin/get_list'),
			'gmt' => array('name' => 'GMT', 'path' => 'gmt/tool'),
			'guide' => array('name' => '遊戲資料', 'path' => 'guide/get_list'),
			'picture' => array('name' => '圖片管理', 'path' => 'picture/get_list'),
			'picture' => array('name' => '圖片管理', 'path' => 'picture/get_list'),
			'log/login' => array('name' => '平台登入記錄', 'path' => 'log/login'),
			'log/game_login' => array('name' => '遊戲登入記錄', 'path' => 'log/game_login'),
			'log/online_user' => array('name' => '線上會員', 'path' => 'log/online_user'),
			'trade/transfer_statistics' => array('name' => '轉點統計', 'path' => 'trade/transfer_statistics'),
			'game/statistics' => array('name' => '流失率統計', 'path' => 'game/statistics'),
			//--
			'character' => array('name' => '創角資料', 'path' => 'character'),
			'character/xf_ad' => array('name' => '創角統計(原廠提供)', 'path' => 'character/xf_ad'),
		);

$config["servers"] =
	array(
		'long_e' => array(
			'menu' => array()
		),
		'default' => array(
                        'name' => 'default',
			'menu' => array($fn['game/modify'], $fn['server'], $fn['news'],$fn['gmt'], $fn['guide'], $fn['picture'], $fn['character'], $fn['log/game_login'], $fn['log/online_user'], $fn['trade/transfer_statistics'], $fn['game/statistics']),
		),
	);

$config["server_status"] =
	array(
		"public" => array("name" => "公開", "color" => "#080"),
		"maintenance" => array("name" => "維護", "color" => "#921"),
		"private" => array("name" => "內測", "color" => "#129"),
		"hide" => array("name" => "關閉", "color" => "#888")
	);
