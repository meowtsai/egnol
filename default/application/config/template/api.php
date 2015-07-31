<?php

$config["partner_api"] = array(
	'public_key' => '2b83e52b913984b78de3f76c61aec0Cz',

	// 自己測試用
	'longe' => array(
		'name' => '龍邑科技',
		'ips' => array('61.220.44.200'),
		'sites' => array(
			'leg1' => array('key'=>'12345678901234567890123456789012'),
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
);

$config["game_api"] = array();

$config["channel_api"] = array(	
	'facebook' => array(
		'name' => 'Facebook',
		'lib_name' => 'fb_api',
		'status' => '2',
		'appId' => '1025425380808895',
		'secret' => 'cc9819f7f4eac6749a9ecf714384c48c',
		'scope' => 'email', //publish_stream
	),		
	'google' => array(
		'name' => 'Google',
		'status' => '2',
	),		
);

$config["fb_app"] = array(
	'dh_ad_a' => array(
		'appId' => '229365737185562',
		'secret' => 'c5fd11000b8e3bd9c055d74764ffd406',
	),
);
