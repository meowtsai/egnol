<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config["game_events"] = 
	array(
		'users' => array(
            'name' => '在線用戶',
			'fields' => array('uid' => 'uid', 'game_id' => '遊戲', 'server_id' => '伺服器', 'latest_update_time' => '更新時間')
		),
	);