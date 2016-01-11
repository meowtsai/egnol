<?php

$config["partner_api"] = array(
	'public_key' => '2b83e52b913984b78de3f76c61aec0Cz',

	// 自己測試用
	'longe' => array(
		'name' => '龍邑遊戲',
		'ips' => array('61.220.44.200', '203.73.59.138'),
		'sites' => array(
			'leg1' => array('key'=>'12345678901234567890123456789012'),
			'test' => array('key' => '12345678901234567890123456789012',
				'Google' => array('Analytics' => array('ID' => 'UA-66673104-3', 'Enable' => '1')),
				'Facebook' => array('ID' => '1691157641127869', 'EventTracking' => '1'),
				'AppsFlyer' => array('DevKey' => 'RgFZmTS3qoYGsPrfPsaq8c', 'Enable' => '1'),
			),
		),
	),

	// 正式合作廠商
	'mobiark' => array(
		'name' => '移動方舟',
		'ips' => array('61.148.75.238'),
		'sites' => array(
			'stm' => array('key'=>'d2e3b7998ac3c3405956dbfb5fe584d4'),
		),
	),

	'netease' => array(
		'name' => '網易',
		'ips' => array('0.0.0.0'),
		'sites' => array(
			'r2g' => array(
				'key' => '85cb5665a2a868410cf740a3c537ab09',
				'Google' => array('Analytics' => array('ID' => 'UA-66673104-2', 'Enable' => '1')),
				'Facebook' => array('ID' => '152562051774453', 'EventTracking' => '1'),
				'AppsFlyer' => array('DevKey' => 'RgFZmTS3qoYGsPrfPsaq8c', 'Enable' => '1'),
			),
		),
	),
);

$config["game_api"] = array(
	'stm' => array('billing' => 'http://203.66.111.18',
				  'alive_check' => true),
);

$config["channel_api"] = array(	
	'facebook' => array(
		'name' => 'Facebook',
		'lib_name' => 'fb_api',
		'status' => '2',
		'appId' => '599788076790753',
		'secret' => '9fd9c4900793f62ad9346a154c66cdb6',
		'scope' => 'email', //publish_stream
	),
	'google' => array(
		'name' => 'Google',
		'status' => '2',
	),		
);

$config["fb_app"] = array(
	'dh_ad_a' => array(
		'appId' => '599788076790753',
		'secret' => '9fd9c4900793f62ad9346a154c66cdb6',
	),
);
