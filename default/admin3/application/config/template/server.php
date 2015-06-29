<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$fn = array(
			'game/modify' => array('name' => '遊戲設定', 'path' => 'game/modify'),
			'server' => array('name' => '伺服器管理', 'path' => 'server/get_list'),
			'news' => array('name' => '最新消息', 'path' => 'bulletin/get_list'),
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
		'dh' => array(
			'menu' => array($fn['game/modify'], $fn['server'], $fn['news'], $fn['character'], $fn['log/game_login'], $fn['log/online_user'])
		),		
		'qjp' => array(
			'menu' => array($fn['server'],  $fn['news'])
		),
		'xj' => array(
			'menu' => array($fn['game/modify'], $fn['server'], $fn['news'], $fn['character'], $fn['log/game_login'], $fn['log/online_user'], $fn['trade/transfer_statistics'])
		),			
		'sg2' => array(
			'menu' => array($fn['game/modify'], $fn['server'], $fn['news'], $fn['guide'], $fn['picture'], $fn['character'], $fn['log/game_login'], $fn['log/online_user'])
		),
		'sl2' => array(
			'menu' => array($fn['game/modify'], $fn['server'], $fn['news'], $fn['guide'], $fn['picture'], $fn['character'], $fn['log/game_login'], $fn['log/online_user'])
		),
		'xf' => array(
			'menu' => array($fn['game/modify'], $fn['server'], $fn['news'], $fn['guide'], $fn['picture'], $fn['character'], $fn['log/game_login'], $fn['log/online_user']),
		),
		'mon' => array(
			'menu' => array($fn['game/modify'], $fn['server'], $fn['news'], $fn['guide'], $fn['picture'], $fn['character'], $fn['log/game_login'], $fn['log/online_user']),
			'extra_menu' => array(
				array('name' => '祕密入口', 'url' => 'http://mon1.longeplay.com.tw/flash/charlie.php', 'icon' => 'icon-share')
			),
		),		
		'xl' => array(
			'menu' => array($fn['game/modify'], $fn['server'], $fn['news'], $fn['guide'], $fn['picture'], $fn['character'], $fn['log/game_login'], $fn['log/online_user']),
		),			
		'yh' => array(
			'menu' => array($fn['game/modify'], $fn['server'], $fn['news'], $fn['guide'], $fn['picture'], $fn['character'], $fn['log/game_login'], $fn['log/online_user']),
		),
		'bw' => array(
			'menu' => array($fn['game/modify'], $fn['server'], $fn['news'], $fn['picture'], $fn['character'], $fn['log/game_login'], $fn['log/online_user']),
		),	
		'gt' => array(
			'menu' => array($fn['game/modify'], $fn['server'], $fn['news'], $fn['guide'], $fn['picture'], $fn['character'], $fn['log/game_login'], $fn['log/online_user']),
		),			
		'lf' => array(
			'menu' => array($fn['game/modify'], $fn['server'], $fn['news'], $fn['guide'], $fn['picture'], $fn['character'], $fn['log/game_login'], $fn['log/online_user']),
		),	
		'kj' => array(
			'menu' => array($fn['game/modify'], $fn['server'], $fn['news'], $fn['picture'], $fn['character'], $fn['log/game_login'], $fn['log/online_user']),
		),	
		'eya' => array(
			'menu' => array($fn['game/modify'], $fn['server'], $fn['news'], $fn['picture'], $fn['character'], $fn['log/game_login'], $fn['log/online_user']),
		),	
		'kw' => array(
			'menu' => array($fn['game/modify'], $fn['server'], $fn['news'], $fn['picture'], $fn['character'], $fn['log/game_login'], $fn['log/online_user']),
		),
		'ry' => array(
			'menu' => array($fn['game/modify'], $fn['server'], $fn['news'], $fn['picture'], $fn['log/game_login'], $fn['log/online_user']),
		),	
		'my' => array(
			'menu' => array($fn['game/modify'], $fn['server'], $fn['news'], $fn['guide'], $fn['picture'], $fn['character'], $fn['log/game_login'], $fn['log/online_user'], $fn['trade/transfer_statistics'], $fn['game/statistics']),
		),
		'jh' => array(
			'menu' => array($fn['game/modify'], $fn['server'], $fn['news'], $fn['picture'], $fn['character'], $fn['log/game_login'], $fn['log/online_user'], $fn['trade/transfer_statistics'], $fn['game/statistics']),
		),		
		'eb' => array(
			'menu' => array($fn['game/modify'], $fn['server'], $fn['news'], $fn['picture'], $fn['character'], $fn['log/game_login'], $fn['log/online_user'], $fn['trade/transfer_statistics'], $fn['game/statistics']),
		),		
		'dxc' => array(
			'menu' => array($fn['game/modify'], $fn['server'], $fn['news'], $fn['picture'], $fn['character'], $fn['log/game_login'], $fn['log/online_user'], $fn['trade/transfer_statistics'], $fn['game/statistics']),
		),	
		'bt' => array(
			'menu' => array($fn['game/modify'], $fn['server'], $fn['news'], $fn['picture'], $fn['character'], $fn['log/game_login'], $fn['log/online_user'], $fn['trade/transfer_statistics'], $fn['game/statistics']),
		),		
		'xg' => array(
			'menu' => array($fn['game/modify'], $fn['server'], $fn['news'], $fn['picture'], $fn['character'], $fn['log/game_login'], $fn['log/online_user'], $fn['trade/transfer_statistics'], $fn['game/statistics']),
		),		
		'sw' => array(
			'menu' => array($fn['game/modify'], $fn['server'], $fn['news'], $fn['picture'], $fn['character'], $fn['log/game_login'], $fn['log/online_user'], $fn['trade/transfer_statistics'], $fn['game/statistics']),
		),			
		'qjp2' => array(
			'menu' => array($fn['game/modify'], $fn['server'], $fn['news'], $fn['picture'], $fn['character'], $fn['log/game_login'], $fn['log/online_user'], $fn['trade/transfer_statistics'], $fn['game/statistics']),
		),		
		'ly' => array(
			'menu' => array($fn['game/modify'], $fn['server'], $fn['news'], $fn['guide'], $fn['picture'], $fn['character'], $fn['log/game_login'], $fn['log/online_user'], $fn['trade/transfer_statistics'], $fn['game/statistics']),
		),	
		'sj' => array(
			'menu' => array($fn['game/modify'], $fn['server'], $fn['news'], $fn['picture'], $fn['character'], $fn['log/game_login'], $fn['log/online_user'], $fn['trade/transfer_statistics'], $fn['game/statistics']),
		),
		'dp' => array(
			'menu' => array($fn['game/modify'], $fn['server'], $fn['news'], $fn['picture'], $fn['character'], $fn['log/game_login'], $fn['log/online_user'], $fn['trade/transfer_statistics'], $fn['game/statistics']),
		),			
		'gsg' => array(
			'menu' => array($fn['game/modify'], $fn['server'], $fn['news'], $fn['picture'], $fn['character'], $fn['log/game_login'], $fn['log/online_user'], $fn['trade/transfer_statistics'], $fn['game/statistics']),
		),
		'st' => array(
			'menu' => array($fn['game/modify'], $fn['server'], $fn['news'], $fn['picture'], $fn['character'], $fn['log/game_login'], $fn['log/online_user'], $fn['trade/transfer_statistics'], $fn['game/statistics']),
		),	
		'fs' => array(
			'menu' => array($fn['game/modify'], $fn['server'], $fn['news'], $fn['picture'], $fn['character'], $fn['log/game_login'], $fn['log/online_user'], $fn['trade/transfer_statistics'], $fn['game/statistics']),
		),	
		'mq' => array(
			'menu' => array($fn['game/modify'], $fn['server'], $fn['news'], $fn['picture'], $fn['character'], $fn['log/game_login'], $fn['log/online_user'], $fn['trade/transfer_statistics'], $fn['game/statistics']),
		),						
		'zj' => array(
			'menu' => array($fn['game/modify'], $fn['server'], $fn['news'], $fn['picture'], $fn['character'], $fn['log/game_login'], $fn['log/online_user'], $fn['trade/transfer_statistics'], $fn['game/statistics']),
		),		
		'hg' => array(
			'menu' => array($fn['game/modify'], $fn['server'], $fn['news'], $fn['picture'], $fn['character'], $fn['log/game_login'], $fn['log/online_user'], $fn['trade/transfer_statistics'], $fn['game/statistics']),
		),	
		'lz' => array(
			'menu' => array($fn['game/modify'], $fn['server'], $fn['news'], $fn['picture'], $fn['character'], $fn['log/game_login'], $fn['log/online_user'], $fn['trade/transfer_statistics'], $fn['game/statistics']),
		),	
		'qq' => array(
			'menu' => array($fn['game/modify'], $fn['server'], $fn['news'], $fn['picture'], $fn['character'], $fn['log/game_login'], $fn['log/online_user'], $fn['trade/transfer_statistics'], $fn['game/statistics']),
		),		
		'aj' => array(
			'menu' => array($fn['game/modify'], $fn['server'], $fn['news'], $fn['picture'], $fn['character'], $fn['log/game_login'], $fn['log/online_user'], $fn['trade/transfer_statistics'], $fn['game/statistics']),
		),			
		'jj' => array(
			'menu' => array($fn['game/modify'], $fn['server'], $fn['news'], $fn['picture'], $fn['character'], $fn['log/game_login'], $fn['log/online_user'], $fn['trade/transfer_statistics'], $fn['game/statistics']),
		),	
		'fen' => array(
			'menu' => array($fn['game/modify'], $fn['server'], $fn['news'], $fn['picture'], $fn['character'], $fn['log/game_login'], $fn['log/online_user'], $fn['trade/transfer_statistics'], $fn['game/statistics']),
		),
		'dd' => array(
			'menu' => array($fn['game/modify'], $fn['server'], $fn['news'], $fn['picture'], $fn['character'], $fn['log/game_login'], $fn['log/online_user'], $fn['trade/transfer_statistics'], $fn['game/statistics']),
		),
		'cy' => array(
			'menu' => array($fn['game/modify'], $fn['server'], $fn['news'], $fn['picture'], $fn['character'], $fn['log/game_login'], $fn['log/online_user'], $fn['trade/transfer_statistics'], $fn['game/statistics']),
		),
	);

$config["server_status"] = 
	array(
		"public" => array("name" => "公開", "color" => "#080"),
		"maintaining" => array("name" => "維護", "color" => "#921"),
		"private" => array("name" => "內測", "color" => "#129"),
		"hide" => array("name" => "關閉", "color" => "#888")
	);
