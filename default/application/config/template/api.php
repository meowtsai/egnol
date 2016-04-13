<?php

$config["partner_api"] = array(
	'public_key' => '2b83e52b913984b78de3f76c61aec0Cz',

	// 自己測試用
	'longe' => array(
		'name' => '龍邑遊戲',
		'ips' => array('61.220.44.200', '203.73.59.138', '54.169.43.10', '106.2.49.177', '106.2.49.211', '106.2.49.225'),
		'sites' => array(
			'leg1' => array('key'=>'12345678901234567890123456789012'),
			'test' => array(
				'name' => '測試 App',
				'key' => '12345678901234567890123456789012',
				'LongE' => array('EnableTracking' => '1'),
				'Google' => array(
					'Analytics' => array(
						'ID' => 'UA-66673104-3',
						'Enable' => '1'
					),
					'GooglePlay' => array(
						'LicenseKey' => 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAyhQESbfjx7ZLKHjfqPr9dbW50GYNf8rB+RvsKw/oIPe9gc05GZZS+Iayq/EVZujHIsdSQZ5llshHbaD1XzWINzwOtXrfmGIlTyWKZgeWvs2TELZ+fnUW5bFD+sR8UF73UMt8Tt+ROf45fwZ0GIyXhtSK3xWo1CNgHy0++Bj8FehULdDLrJVn2u3rey0v9Yy4cg7Az2SsodtQwBVMgI705YCvRx8dATc1EcWtVXOI7YGG74ZLLhsx0WlyNtpWNf1JpV1kmyGNuEBH2XBw0abcEJaEx7t0rzoBG3HG9S8CW3bkFZ47eWPvqrTnAqhOiN/IravB5i7L+eeco0bWad6EZwIDAQAB',
						'Products' => array(
							'gold.100',
							'gold.200'
						)
					)
				),
				'Apple' => array(
					'ID' => '1005400992',
					'AppStoreProducts' => array(
						'longe_test_gold_100',
						'longe_test_gold_200'
					)
				),
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
		'ips' => array('54.169.43.10', '106.2.49.177', '106.2.49.211', '106.2.49.225'),
		'sites' => array(
			'r2g' => array(
				'name' => '絕代雙驕',
				'key' => '85cb5665a2a868410cf740a3c537ab09',
				'LongE' => array('EnableTracking' => '1'),
				'Google' => array(
					'Analytics' => array(
						'ID' => 'UA-66673104-2',
						'Enable' => '1'
					),
					'GooglePlay' => array(
						'LicenseKey' => 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAkAFddQDMsmMNdDZXOq1bvgCkmewiKGmPzTEXBDQeGHukQheAzL1z232EykFyubZ6ATzkAjFTPa5HICJ/o+h9h6vkYqob1hKg1x7XslZH3pUpi/jZZel7seN8p3WDOL3W2eFYdna4MSb6nkcJzh2WDdG2k+dvdLoKw8uURHYuRTBv13XDHm8AIkemNPZcVsDdh7Y7S0B5Fl+1WxrnOGzK1XoHvDNhcNPVh/5p8gQhpJLdO53B0EELJ5WgVdQM5F6oKpUS9/G0obIDL3GVwP41E+7QVqA3p1RM91SsGcCpnnN49H6BYe6eDnCNCqSNX3ngppdAD7eo2miC+sdDx4GKmwIDAQAB',
						'Products' => array(
							'r2g_60',
							'r2g_150',
							'r2g_450',
							'r2g_590',
							'r2g_1490',
							'r2g_2990'
						)
					)
				),
				'Apple' => array(
					'ID' => '1014250566',
					'AppStoreProducts' => array(
						'longer2g_60',
						'longer2g_150',
						'longer2g_450',
						'longer2g_590',
						'longer2g_1490',
						'longer2g_2990',
						'longer2gmo_120',
						'longer2gm_240',
					)
				),
				'Facebook' => array('ID' => '152562051774453', 'EventTracking' => '1'),
				'AppsFlyer' => array('DevKey' => 'RgFZmTS3qoYGsPrfPsaq8c', 'Enable' => '1'),
			),
		),
	),
);

$config["game_api"] = array(
	'test' => array('billing' => 'http://203.66.111.26:8660/mp/mplongeplay_pay_notify_process_l7tw_ad'),
	'stm' => array('billing' => 'http://203.66.111.18',
				  'alive_check' => true),
	'r2g' => array('billing' => 'http://203.66.111.26:8660/mp/mplongeplay_pay_notify_process_l7tw_ad'),
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
