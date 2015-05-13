<?php

$config["partner_api"] = array(
	'public_key' => '2b83e52b913984b78de3f76c61aec0Cz',
	//'google_iab_products' => array("p50" => 50, "p100" => 100, "p500" => 500, "p1000" => 1000, "p3000" => 3000, "p5000" => 5000, "android.test.purchased" => "purchased", "android.test.canceled" => "canceled", "android.test.refunded" => "refunded"),		
	'kimi' => array(
		'name' => '奇米',
		'ips' => array('61.148.75.238'),
		'transfer_url' => 'http://www.kimi.com.tw/?act=voucher.main&pid=',
		'sites' => array(
			'xf' => array('key'=>'4d963914a5f6d5b8ba84cda47a4b4bCz'), 
			'sl2' => array(
				'pid' => '516',
				'key'=>'35ab9deab457cfd771bebefa838bdaCz'
			),
		),
	),
	'artsy' => array(
		'name' => '艾斯',
		'ips' => array('114.34.165.184'),
		//'transfer_url' => 'http://www.kimi.com.tw/?act=voucher.main&pid=',
		'sites' => array(
			'xj' => array('key'=>'911d960b3a91bd7e412de94049b6c5Cz'),
		),
	),
	'dtalent' => array(
		'name' => '大天人',
		'ips' => array(),
		//'transfer_url' => 'http://www.kimi.com.twf/?act=voucher.main&pid=',
		'sites' => array(
			'xj' => array('key'=>'f665de352973a5197183f3d4e5d009Cz'),
		),
	),				
	'tenone' => array(
		'name' => '騰王',
		'ips' => array(),
		'sites' => array(
			'eya' => array(
				'key'=>'479a4211b1048a687db14556673c31Cz',
				'iab' => array(
					'key' => 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAn1DKORUfSmtpahPpm7M7l8nuPqvRot25fRJMCvOv42ha9gYZ3UppZ/NRfmAcf3Lr+x06Qop2xWpXTYxAvSOI854xKnf0WS6dDTUpNEMwcB1fc3pgVE+BgOMLD/bCRVYwi19x1//Th55ap6uxOI+5eiTMN8ENsrvCU9ukmtqnK6vU6GK8/2RPU86//aNSzrgiC89oKPwcTeNClPTEdA7ESGI9HjXpom6RjNRNkn/Ww2NOCm1Mv2f+gf3oJhQhlnedz+F5Oy59toZONjxyPbaPiHORwrP9fH470URDnVQSnkbjrz/j6JPxg/rwWZwNNDzCKNjwTErTRZiBtPqbRIA8cQIDAQAB',
					'products' => array("p50" => 50, "p100" => 100, "p500" => 500, "p1000" => 1000, "p3000" => 3000, "p5000" => 5000),
				),
				'tapjoy' => array(
					'id' => 'd840cd2d-8151-4979-a870-dd31129f89bb',
					'key' => 'BMgsKU0zCoPWXmDqVP6P',	
					'ppe' => 'f37bfabd-ded6-4ca8-a010-77168c61d085',
				),				
// 				'inmobi' => array(
// 					'id' => '901b42a708c3433b92d4d4c5d61b83af',
// 					'android_app_id' => '901b42a708c3433b92d4d4c5d61b83af',
// 					'ios_app_id' => '84ebb198abab4fdd9ddac90dfd9a3aeb',
// 				),					
			),
		),
	),			
	'179game' => array(
		'name' => '玩客',
		'ips' => array('206.161.221.111', '59.152.252.228'),
		//'transfer_url' => 'http://www.kimi.com.tw/?act=voucher.main&pid=',
		'sites' => array(
			'my' => array('key'=>'3343c7eb7103ebe92eb5fecc042e43Cz'),
		),
	),			
	'smmo' => array(
		'name' => '星馬',
		'ips' => array('202.76.226.172', '175.139.145.241'),
		//'transfer_url' => 'http://www.kimi.com.tw/?act=voucher.main&pid=',
		'sites' => array(
			'my' => array('key'=>'e47daa964e821912590ac40c7fcaeaCz'),
		),
	),			
	'muxplay' => array(
		'name' => '一遊',
		'ips' => array('106.187.96.25'),
		//'transfer_url' => 'http://www.kimi.com.tw/?act=voucher.main&pid=',
		'sites' => array(
			'my' => array('key'=>'d457a234546e602528d083cbd20759Cz'),
		),
	),
	'egame101' => array(
		'name' => '貓眼',
		'ips' => array('175.99.87.49', '175.99.87.79'),
		//'transfer_url' => 'http://www.kimi.com.tw/?act=voucher.main&pid=',
		'sites' => array(
			'my' => array('key'=>'c8a171e1b2822d3f48e557cf85d611Cz'),
		),
	),				
	'huizhong' => array(
		'name' => '江蘇匯眾',
		'ips' => array(),
		'sites' => array(
			'gsg' => array(
				'key'=>'baca0a4568c6d983716224e3f8582dCz',
				'iab' => array(
					'key' => 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAl5XOm9TY+agyGPKGzDfIphS9E8N/Xpsmjm3sXea8tULiOciBiEzcR79M33qnvGc/aBR5uMPEdgOhHtnzTRMTG9ECLMZZ2VG/nYywO47IseuLphEh7fc2UIwQHYdkUIz27Ri5ndTwL8860mBgYy4YtUmo8OCWp7/KzxXzt1vllnCmyP9wnf9hm8vD7IgJERzeurdVlwhzoHuxFpwfcF8AZ7PuTdfkb9C5ixamqfsr1tgukPHmOsz1oAOnfzk52ei2WlG1MccHUX8phYsdm3D0/JclX3oBk7LLTnz5tVj3V6Qw1SnrxalVyanSzolMhHw5PZhaJ50QqxIC3YNTQsxpWwIDAQAB',
					'products' => array("p50" => 50, "p100" => 100, "p300" => 300, "p500" => 500, "p1000" => 1000, "p3000" => 3000, "p5000" => 5000), //, "android.test.purchased" => "0 (內部測試用)"
					//, "android.test.canceled" => "canceled", "android.test.refunded" => "refunded", "android.test.item_unavailable" => "item_unavailable "						
				),
				'facebook' => array(
					'ad_ids' => array('1449664885292866', '811837458827097'), //, '598264776953355'
				),
				/*'tapjoy' => array(
					'id' => '8cbcd7c1-5280-4c2d-a9a5-678beeb784e4',
					'key' => 'qvLqbedrvoFvUgFbyvge',	
					'ppe' => 'f37bfabd-ded6-4ca8-a010-77168c61d085',
				),*/
				'inmobi' => array(
					'id' => 'b51fac0603f64f8180f6fb548ff09a02', //待刪
					'android_app_id' => 'b51fac0603f64f8180f6fb548ff09a02',
					'ios_app_id' => '84ebb198abab4fdd9ddac90dfd9a3aeb',
				),
				'ios' => array(
					'products' => array("com.long_e.test001" => 30, "com.long_e.test002" => 60),		
				),
				'google' => array( //測試用
					'ios_conversion_id' => '966592837',
					'ios_label' => 'uX7XCITJtlYQxZL0zAM',	
				),					
			),
		),
	),	
	'58play' => array(
		'name' => '赤霄',
		'ips' => array('61.210.22.231'),
		'sites' => array(
			'ly' => array('key'=>'0929aad055bd70303d52871c6d6809Cz'),
		),
	),		
	'nicegame' => array(
		'name' => '戲雋',
		'ips' => array('210.242.247.39', '218.32.53.111', '118.163.116.102'),
		'sites' => array(
			'ly' => array('key'=>'1aa14614b9f03e36918553a204ae1dCz'),
		),
	),			
	'ridley' => array(
		'name' => '雷利移動',
		'ips' => array(),
		'sites' => array(
			'zj' => array(
				'key'=>'afd46074e35b14c8d0d983cb6fb0aaCz',
				'iab' => array(
					'key' => 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAlbGOhDwpq0BU7PiEAl1pPTJdSsxDrpYm9eC3FTih0si9Oc7ZOsy+3tpLVYCXWTznJwS/aisCs4CMXnNtbugkBA31c9+jASNS2l+jfB65R5wvWTn9R3sytXPUoCEvl6lKPd/BECGBwADyqLGzVjMbiwEf7rjUfrNwNEkKO7TUmP5bUecmFv/W+r5lNZ97BcxiwRdiBR2EcK6EBLeKakngkULZQrlZZNz0ZtFlWvHAWGJQW6J/8IB8Ol0XR/7OfkftTBgEbqmSJHl1/CnmCLCCbdaj5QVMq+ZCdgM09c3ZZqpD03GCX1LBmToKOk5uG/cyJKTQqWDRHkRqFCGrUSgR3wIDAQAB',
					'products' => array("p60" => 60, "p150" => 150, "p300" => 300, "p660" => 660, "p790" => 790, "p1490" => 1490, "p3590" => 3590, "p5990" => 5990), //, "android.test.purchased" => "0 (內部測試用)"
					//, "android.test.canceled" => "canceled", "android.test.refunded" => "refunded", "android.test.item_unavailable" => "item_unavailable "						
				),
				'ios' => array(
					'products' => array("com.long_e.zj.p60" => 60, "com.long_e.zj.p150" => 150, "com.long_e.zj.p300" => 300, "com.long_e.zj.p660" => 660, "com.long_e.zj.p790" => 790, 
							"com.long_e.zj.p1490" => 1490, "com.long_e.zj.p3590" => 3590, "com.long_e.zj.p5990" => 5990, "com.long_e.zj.p9990" => 9990, "com.long_e.zj.p14900" => 14900, "com.long_e.zj.p29900" => 29900
					),		
				),
				'facebook' => array(
					'ad_ids' => array('1396351777321829'),
				),
// 				'tapjoy' => array(
// 					'id' => '8cbcd7c1-5280-4c2d-a9a5-678beeb784e4',
// 					'key' => 'qvLqbedrvoFvUgFbyvge',	
// 					'ppe' => 'f37bfabd-ded6-4ca8-a010-77168c61d085',
// 				),
// 				'inmobi' => array(
// 					'id' => '901b42a708c3433b92d4d4c5d61b83af',
// 					'android_app_id' => '901b42a708c3433b92d4d4c5d61b83af',
// 					'ios_app_id' => '84ebb198abab4fdd9ddac90dfd9a3aeb',
// 				),
				'google' => array(
					'ios_conversion_id' => '966592837',
					'ios_label' => 'uX7XCITJtlYQxZL0zAM',	
				),
			),
		),
	),	
	'skyler' => array(
		'name' => 'Skyler',
		'ips' => array('103.7.159.196'),
		'sites' => array(
			'xj' => array('key'=>'7adb63137c4ae638a84fb9f87d3c61Cz'),
			'my' => array('key'=>'5b65b0918e342f66e32af1cd6bc31eCz'),
			'qjp2' => array('key'=>'7d2cdcdb7dd0c6b3c50324074ff276Cz'),
			'sg2' => array('key'=>'6e0f4ce6c0b39b3d2f49744ea8fde5Cz'),
			'gt' => array('key'=>'bfad22f0925978f310a37440bfdff4Cz'),
			'ly' => array('key'=>'728b47751c6555942cb60f97d1e455Cz'),
		),
	),			
);

$config["game_api"] = array(
		'kunlun' => array(
			'login_url' => 'http://user.qjp.long_e.com.tw/User/uniteLogin',
			'transfer_url' => 'http://user.qjp.long_e.com.tw/VouchV2/addGameCoin',
			'check_role_url' => 'http://user.qjp.long_e.com.tw/Char/getCharInfo',
		),
		'kunlun2' => array(
			'login_url' => 'http://user.unite.kimi.com.tw/User/uniteLogin',
			'transfer_url' => 'http://user.unite.kimi.com.tw/VouchV2/addGameCoin',
			'check_role_url' => 'http://user.unite.kimi.com.tw/Char/getCharInfo',
		),		
		'dh' => array(
			'key' => 'd#864UF8s+ds@j&#%&m^-^x1V',
			'login_url' => '/api/login.php',
			'transfer_url' => 'http://pay.union.qq499.com:8029/pay_sync_togame.php',
			'transfer_key' => 'CYak48rujkfd0cvdfa',
		),
		'bw' => array(
			'key' => 'd#864UF8s+ds@j&#%&m^-^x1V',
			'login_url' => '/api/login.php',
			'transfer_url' => 'http://pay.union.qq499.com:8029/pay_sync_togame.php',
			'transfer_key' => 'CYak48rujkfd0cvdfa',
		),		
		'my' => array(
			'key' => '550472fa0115160488c5d39c6ec383Cz',
			'sp' => '7',
			'login_key' => 'd#864UF8s+ds@j&#%&m^-^x1V',				
			'login_url' => '/api/login.php',
			'transfer_url' => '/pay_api/pay.php',
			'transfer_key' => 'H3TxNYxPzNBF7e1s',
		),		
		'sg2' => array(
			'login_key' => 'PXVSYCDusmyvLXjZkLprWIqHmEenIkKD', 
			'transfer_key' => 'daGYtUEtDdPTljdbqVgpdeHcNhjlGcNg',
		),
		'xj' => array( //Kunlun
			'login_key' => 'AiFxKkcAesLbzNvnKLQgddxkRexSeBkX', 
			'transfer_key' => 'LMIoMmgXUfePZpjyTFvDXBjKkvuwHCkU',
		),
		'gt' => array( //Kunlun
			'login_key' => 'ktQBGkGHxcsFMnbxMXVaiMRoAWkQomnQ', 
			'transfer_key' => 'tWAztbLgIJtaZDCIQUwQlBBOnohTwhov',
		),
		'qjp' => array( //Kunlun
			'login_key' => 'ifdasdlnewescLmCOuFouico', 
			'transfer_key' => 'iicozdasdlnewescLmCOuFougIyixu',
		),
		'qjp2' => array( //Kunlun2
			'login_key' => 'f5d2cb5fa1084a575ef2131e5583155a', 
			'transfer_key' => '6c1e4f2167a3bea23bd023012003370e',
		),		
		'lz' => array( //Kunlun2
			'login_key' => 'ikjbfyesysysqdwloguibqvoxayjlikq', 
			'transfer_key' => 'hojhbyxlwuwkbllkmfbthbugkaovwvku',
		),			
		'sg' => array(
			'login_key' => 'CkOuOnZLCuOnMcToWm0511', 
			'transfer_key' => 'CkOuOnZLCuOnMcToWm0511',
			'transfer_url' => 'http://user.qjp.long_e.com.tw/Vouch/AddGameCoin',
		),		
		'kw' => array( //Kunlun
			'login_key' => 'caed2aa219b6a97a066247fd77740f4c', 
			'transfer_key' => 'c656282cba259f0f5f0435a8b230de38',
		),		
		'tc' => array(
			'transfer_key' => '580c6193aa76f8986bfa019435b03748',		
		),
		'mon' => array(
			'key' => 'YzM5NzFmZjVkNjNlMWUyZGNmOGRiMDM0MWQ2MzAzYT',
		),
		'xf' => array(
			'platform_key' => '03',
			'verify_url' => 'http://%s:136/interface/index.php/andy/index?key=%s&adult=1&uid=%s&sk=%s',
			'login_url' => 'http://%s/index.html?key=%s&uid=%s&wk=%s', //ip為 203.75.245.51
			'transfer_url' => 'http://%s:136/interface/index.php/andy/payment?key=%s&uid=%s&money=%d&flag=%d&sk=%s',
			'key01' => 'andy',
			'key02' => '0510',
			'key'	=> '121Xf',
		),
		'sl2' => array(
			'partner_id' => '674',
			'product_id' => '221',
			'key' => '23434545',
			'login_url' => 'http://%s?username=%s&time=%s&adult=%s&sign=%s&product_id=%s&server_id=%d',
			'login_key' => 'HeC7Q4c9FkdpEyaNuv9OLp4x5hGPvWHQ',
			'transfer_url' => 'http://passport.9wee.com/pay/index.php?mod=api', 
			'transfer_key' => 'XfMs5Ct6yv6bL73ToH7iDmzJ54eeKiLY',
			'check_user_url' => 'http://passport.9wee.com/pay/index.php?mod=api&ctl=user&ac=check',				
		),
		'kingnet' => array(
			'login_url' => 'http://tw.kingnet.com/?',
			'check_role_status_url' => 'http://tw.kingnet.com/?',
			'transfer_url' => 'http://pay4.kingnet.com/index.php?action=gameunion&resource_id=1&',
				//正式：http://pay.kingnet.com/Xiyou/pay_gate_s3.php
				//測試：http://dev1.pay.kingnet.com/Xiyou/pay_gate_s3.php
			'pkey' => 'long_e@gamekey_20121214',
			'transfer_pkey' => 'KiNgnEt201212COoZ18KUAidaOwoDWanlILaIbeANFunS3',
			'code' => array(
				"1" => "成功",
				"2"	=> "非法服务器连接",
				"3"	=> "必要参数缺失",
				"4"	=> "未创建游戏角色",
				"5"	=> "参数错误",
				"6"	=> "插入失败",
				"7"	=> "无权调用测试环境",
				"8"	=> "测试环境下充值金额超过限制(100)",
				"9"	=> "无权调用线上环境",
				"10" => "测试环境下游戏币超过限制(1000)",
				"-1" => "平臺标识错误",
				"-2" => "账号不存在",
				"-3" => "充值金额错误",
				"-4" => "充值游戏币错误",
				"-6" => "游戏区服不存在",
				"-7" => "签名错误",
				"-9" => "订单重复",
				"-11" => "充值失败",					
			),
			'sign' => array(
				"1" => "成功",
				"0" => "無創建角色",
				"-1" => "參數錯誤",
				"-4" => "請求的遊戲區服不存在",
				"-5" => "簽名錯誤",
			),
		),
		'kj' => array( //kingnet
			'transfer_url' => 'http://pay4.kingnet.com/index.php?action=gameunion&resource_id=1&',
			//舊的 http://pay.kingnet.com/Xiyou/pay_gate_union.php?				
			'gid' => '12',
			'game' => 'kj',
			'transfer_gameid' => '1084057',
			'key' => 'f1bf8b70bd356456153a5ccef153feCz',
		),				
		'yh' => array( //kingnet
			'transfer_url' => 'http://pay4.kingnet.com/index.php?action=gameunion&resource_id=1&',
				//正式：http://pay.kingnet.com/Xiyou/pay_gate_yanhuang.php			
				//測試：	http://dev1.pay.kingnet.com/Xiyou/pay_gate_yanhuang.php
			'gid' => '6',
			'game' => 'yanhuang',
			'transfer_gameid' => '1038072',
			'key' => 'da25a2d40555fb6b2a3af3a33bf16cCz',
		),
		'elex' => array(
			'login_url' => 'http://p.337.com/site/?gKey=%s',
			'login_key' => 'ccc7434ab288d131fa07628a854d0fCz',	
			'transfer_url' => 'http://v3.pay.337.com/payment/callback/custom_channel_id/long_e',
			//新的：http://v3.pay.337.com/payment/callback/custom_channel_id/long_e 
			//测试：http://huanhailantian.appspot.com/callback?custom_channel_id=long_e
			//正式：http://payelex.appspot.com/callback?custom_channel_id=long_e
			'transfer_key' => '17ad05693d3e8a9463468157466ed1Cz',
			'check_user_url' => 'http://p.337.com/site/api.php?method=game.getUserInfo&gKey=%s&uid=long_e_%d',	
		),
		'xl' => array(
			'elex_gid' => 'xlfc',
			'login_url' => 'http://p.337.com/site/?gKey=xlfc@long_e_tw_1',
			'login_key' => 'ccc7434ab288d131fa07628a854d0fCz',	
			'transfer_url' => 'http://v3.pay.337.com/payment/callback/custom_channel_id/long_e',
			'transfer_key' => '17ad05693d3e8a9463468157466ed1Cz',
			'check_user_url' => 'http://p.337.com/site/api.php?method=game.getUserInfo&gKey=xlfc@long_e_tw_%d&uid=long_e_%d',	
		),
		'bt' => array(
			'elex_gid' => 'btqz',
			'login_url' => 'http://p.337.com/site/?gKey=btqz@long_e_tw_22',
		),
		'lf' => array(
			'login_key' => 'b2f2b58067b2314d32a90b30e10db6Cz',
			'key' => '6c0a0dca1fcac3b41959900a472240Cz',
			'transfer_key' => '7b2f0f068e48a78ca9374163af1766Cz',
		),
		'ry' => array(
			'csid' => 'long_e',
			'key' => 'd057b36ad384652e1a95c12772f79ccf',
			//測試 long_e123456
			'agent_info_api' => 'http://playerapi.player.com.tw/player/agent/agent_info_api.php',
			//測試 http://playerapi.win777.com.tw/player/agent/agent_info_api.php
		),
		'eb' => array(),
		'dxc' => array(
			'gamecode' => 'dxc',
			'transfer_url' => 'http://pay.870.com/api/long_e_pay/dxc/action.php',
			'check_role_url' => 'http://www.870.com/api/user/openid/long_e/role.php',
		),
		'sw' => array(
			'gamecode' => 'swjt',
			'transfer_url' => 'http://pay.870.com/api/long_e_pay/swjt/action.php',
			'check_role_url' => 'http://www.870.com/api/user/openid/long_e/role_swjt.php',		
		),
		'muxplay' => array(
			'key' => 'long_e34ba78238579876ee1bb6fc9up9y',
			'auth_url' => 'http://www.muxplay.com/union/auth?',
			'login_url' => 'http://www.muxplay.com/union/login?',
			'check_user_url' => 'http://www.muxplay.com/union/character?',
			'transfer_url' => 'http://www.muxplay.com/union/pay?',
		),
		'sj' => array(
			'game_id' => '33',		
		),
		'ttplay' => array(
			'login_key' => 'GameWave@%_]tLog&In^2009',
			'transfer_key' => '^T@_@TPl@yW0rd@Cy2@@9!',
			'login_url' => 'http://www.tt-play.com/loginApi/gameLogin/?',
			'transfer_url' => 'http://www.tt-play.com/loginApi/gamePay/?',
		),
		'jh' => array( //ttplay
			'game_id' => '32',
			'check_user_url' => 'http://www.tt-play.com/loginApi/checkrolejh/?',				
		),
		'dp' => array( //ttplay
			'game_id' => '37',
			'check_user_url' => 'http://www.tt-play.com/loginApi/checkroledps/?',				
		),
		'mq' => array( //ttplay
			'game_id' => '39',
			'check_user_url' => 'http://www.tt-play.com/loginApi/checkroleQQ/?',				
		),		
		'aj' => array( //ttplay
			'game_id' => '40',
			'check_user_url' => 'http://www.tt-play.com/loginApi/checkroleAJ2/?',				
		),			
		'dd' => array( //ttplay
			'game_id' => '43',
			'check_user_url' => 'http://www.tt-play.com/loginApi/checkroleDD/?',
		),
		'gamexdd' => array(
			'partner' => 'COOZ',				
			'login_url' => 'http://www.gamexdd.com/partner/login/?',
			'transfer_url' => 'http://www.gamexdd.com/partner/recharge/?',
			'check_user_url' => 'http://www.gamexdd.com/partner/role/?',
		),
		'xg' => array( //gamexdd
			'game' => 'RR',
			'key' => 'ee5a1f09e64ca3bf8b8276f0d2eb7b0f',				
			'login_url' => 'http://www.179game.com/partner/login/?',
			'transfer_url' => 'http://www.179game.com/partner/recharge/?',
			'check_user_url' => 'http://www.179game.com/partner/checkrole/?',
		),
		'st' => array( //gamexdd
			'game' => 'ST',
			'key' => '2eb6956719c805e12d72637218a11c30',
		),
		'qq' => array( //gamexdd
			'game' => 'QQ',
			'key' => '911d4d67911e7996a69cb1f84e8e8708',
		),
		'jj' => array( //gamexdd
			'game' => 'JJ', 
			'key' => '4be569973baba234215c95864dc31013',
		),		
		'cy' => array( //gamexdd
				'game' => 'CY',
				'key' => 'd199658869bba46862804a12c9d857fd',
		),		
		'eya' => array(),
		'gsg' => array(),
		'zj' => array(),
		'fs' => array( //kunlun2
			'login_key' => 'jlctswyqhitovkkrdoahdptkfopkqmdd', 
			'transfer_key' => 'jbznpibeerojstjzxzthsmniweuaheig',	
		),
		'yeapgame' => array(), //嘻遊太子
		'hg' => array( //yeapgame
			'key' => '805_IP8i-MKqhuxiaoIGgW6-cnolanCA',
		), 		
	);

$config["channel_api"] = array(	
		'facebook' => array(
			'name' => 'Facebook',
			'lib_name' => 'fb_api',
			'status' => '2',
			'appId' => '120701231314999',
			'secret' => 'b2afbc46366f6b92ff373fc90d0211a7',
			'scope' => 'email', //publish_stream
		),		
		'm_facebook' => array(
			'name' => 'Facebook',
			'status' => '2',
			'sites' => array('eya'=>''),
		),			
		'bahamut' => array(
			'name' => '巴哈姆特',
			'status' => '2',
			'request_token_url' => 'http://api.gamer.com.tw/oauth/oauth_requestToken.php',
			'access_token_url' => 'http://api.gamer.com.tw/oauth/oauth_accessToken.php',
			'authorize_url' => 'http://api.gamer.com.tw/oauth/oauth_confirm.php',
			'transfer_key' => 'ddb5918cbc7c67f7c6152f7b8cd111Cz',
			'transfer_getdata_url' => 'https://user.gamer.com.tw/webcoin/playshop_getdata.php',
			'transfer_result_url' => 'https://user.gamer.com.tw/webcoin/playshop_result.php',
			'sites' => array(
				/*'long_e' => array(
					//暫用大漢的key
					'key' => '01507174904aac890344c894e374c7fe04f8250de',
					'secret' => 'b46ea1e6fb765092a83685bfa422a71f',							
					//'key' => 'd92dbd5afbb3f16920ef18a9e259224004e2d157a',
					//'secret' => 'a51e31e7d104fa2af8fdabc2134107bb',
				),*/
				'dh' => array(
					'key' => '01507174904aac890344c894e374c7fe04f8250de',
					'secret' => 'b46ea1e6fb765092a83685bfa422a71f',	
				),
				'mh' => array(
					'key' => '7d5cbad7209e9fab458d1f3db13c60c504f8250f6',
					'secret' => 'ef41587523b7fba857fdfc4e089c2a49',
				),
				'tc' => array(
					'key' => '9aae5cbdafd934e33bfc79ca8969f02b04f8250ee',
					'secret' => 'ccbc92c38cbecd4137b289dfdf1cab93',
				),
				'sg2' => array(
					'key' => 'fdf99f1d979ab7a0f0340939713b108805076877a',
					'secret' => '2a38d14960871de8b8edffdf4bd1569a',
				),
				'mon' => array(
					'key' => 'fdfe2feeabd10198dbc4d429f64cb96b0507cd51e',
					'secret' => '21a012f61d82594c7f8d68c124c99030',
				),
				'xf' => array(
					'key' => 'd0de19dde9166a06b89bfb14f4c28ff4050a2fe9c',
					'secret' => 'e4a7c13281bd684abe0c7d26cc9c1071',
				),
				'sl2' => array(
					'status' => '2',
					'key' => '4b6671ca6ba7a38defa4d628434fb84c050e4f687',
					'secret' => '5525796de91810006a010eb1111fef49',
					'trade_gid' => '682',
				),	
				'yh' => array(
					'status' => '2',
					'key' => '166c462e3c01b8bd60beb63be4a1599a051650037',
					'secret' => '9f4e6d8a66bf0bf14ea8a95315b41648',
					'trade_gid' => '762',
				),	
				'bw' => array(
					'status' => '2',
					'key' => '5608e8146f9c6d13a6154e89f1f00e470516bb947',
					'secret' => '666a371171a7a0e49d2293e113276dc9',
					'trade_gid' => '768',
				),
				'my' => array(
					'status' => '2',
					'key' => '242447c03c9bf7ef26ee7d2006fbea7b052d7bb79',
					'secret' => '6f69c958a4b51f12bf8f4fcb7dfe77db',
					'trade_gid' => '1127',
				),
				'ly' => array(
					'status' => '2',
					'key' => 'c0e57480716c6cf9d1c5bf6f11a02d11053fae5d0',
					'secret' => '4de72c7580f5cd502bc51e421dae4512',
					//'trade_gid' => '1127',
				),
			) 
		),
		'gamebase' => array(
			'name' => '遊戲基地',
			'status' => '2',
			'login_url' => 'http://www.gamebase.com.tw/etc/api/long_e_login_api.php',
			'key' => 'co5Goco$go3zsT&s%D3DcoHoz52fy2co7oz8ay9f',
			'sites' => array('sl'=>'', 'ms'=>'', 'tc'=>'', 'dh'=>'', 'mh'=>'', 'sg2'=>'', 'xf'=>'', 'mon'=>'', 'sl2'=>'', 'bw'=>'', 'xj'=>'', 'my'=>'','ly'=>''),
		),
		'yoid' => array(
			'name' => 'Yahoo奇摩國際',		
			'status' => '2',	
			'oauth_url' => 'https://api.login.yahoo.com/oauth/v2/get_request_token?oauth_nonce=%s&oauth_timestamp=%d&oauth_consumer_key=%s&oauth_signature_method=PLAINTEXT&oauth_signature=%s&oauth_version=1.0&xoauth_lang_pref=zh-Hant-TW&oauth_callback=%s',							
			'consumer_key' => 'dj0yJmk9S2twc2FMTU9qNVQyJmQ9WVdrOWJWSlZRMFZoTlRJbWNHbzlNVE14TWpVMk5ETTJNZy0tJnM9Y29uc3VtZXJzZWNyZXQmeD0zMA--',
			'consumer_secret' => '7678b9a868926471bda6cf8ca420407aeb9ac96f',
			'app_id' => 'mRUCEa52',
			//'sites' => array('long_e'=>'', 'yh'=>''),
		),
		'yahoo' => array(
			'name' => 'Yahoo奇摩遊戲',
			'status' => '2',
			'secret' => 'W7iTucsr3Lbr3rM4FZZsjI_cP9knJtrC2M76ye9V.SA-',
			'login_url' => 'http://tw.games.yahoo.com/playweb/reg.php?gamename=%s',
			'version' => '1',
			'sites' => array(
				/*'long_e' => array(
					'game_name' => 'COOZ_POINT',
				),*/
				//'dh' => array('game_name' => 'DH'),
				'sg' => array('game_name' => 'SGWEB'),
				'sl' => array('game_name' => 'SL'),
				//'sl2' => array('game_name' => 'SL2'),					
				'xj' => array('game_name' => 'XJ'),
				'tc' => array('game_name' => 'TC'),
				'mh' => array('game_name' => 'MH'),
				'eya' => array('game_name' => 'M_COOZ'),
				'qjp' => array('game_name' => 'QJP'),
			),
			'game_type' => array(
				'long_e02' => 'qjp',
				'long_e03' => 'long_e',
				'long_e04' => 'sl',
				'long_e05' => 'ms',
				'long_e06' => 'xj',
				'long_e07' => 'dh',
				'long_e08' => 'mhkl',
				'long_e09' => 'sl2',
				'm_long_e_01' => 'eya',
			),	
		),			
		'minik' => array(
			'name' => 'Yahoo奇摩社群遊戲',
			'status' => '2',
			'login_url' => 'http://www.minik.com.tw/userapp/userapp.php?ac=play&appid=%d',
			//http://tw.socialgame.yahoo.net/userapp/userapp.php?ac=play&appid=%d
			'sites' => array(
				'sg2' => array(
					'id' => '1187',
					'key' => 'c223ad097805cdfdd19b123bec76b1c5',
					'secret' => 'bed6afb3546c98e220e92ab120ab77b5',	
				),
				'mon' => array(
					'id' => '1189',
					'key' => 'ea445b4887f740d59c6133d1eca3ecd1',
					'secret' => '3a1c3aa00eb46fede35c65dcc250fea5',	
				),
				'xf' => array(
					'id' => '1197',
					'key' => '6c23d82d90b060c45854c66771bbbba7',
					'secret' => '7852f1cee3a7aebd335f5a2a1d711c80',	
				),
				'sl2' => array(
					'status' => '1',
					'id' => '1257',
					'key' => '17a166faacf2a1b5fe193181a0c96248',
					'secret' => 'e0680c7328ec69e5e5c102a654f7575d',	
				),
				'gt' => array(
					'status' => '1',
					'id' => '1337',
					'key' => '87c591f4bf2803a38fd5fe039fb17995',
					'secret' => 'a09c7f1647f38f32a06c3e9a83f87394',	
				),
			),
		),		
		'omg' => array(
			'name' => 'O-Play',
			'status' => '2',
			'login_url' => 'https://pay.omg.com.tw/Partner/Login/Index.aspx',
			'storeid' => "O2012071801",
			'hashkey' => "fa5deb6fcf2a",
			'sites' => array(
				//'long_e'=> array('appkey' => '726db3bdeaa547b2bb'),
				'dh'=> array('appkey' => '726db3bdeaa547b2bb'),
				'xj'=> array('appkey' => 'aaabaff2fcb946628b'),
				'sg2'=> array('appkey' => 'b3cfaf85b9cb4ffe87'),
// 				'xf'=> array('appkey' => '2ebea6a5b5844d35b4'),
				'mon'=> array('appkey' => 'bec7dcb686b244578a'),
// 				'sl2'=> array('appkey' => 'faa5d24c33af496baf'),
				'bw'=> array('appkey' => 'da822edfa5a545788c'),
			),
		),
		'rc' => array(
			'name' => 'RC語音',
			'status' => '2',
			'akey' => 'd493aff510cc4d7b6fdae48b03a80aca05061540f',
			'skey' => 'eb5b0f204f0e59491340085410afb435', 
		),
		'rc2' => array(
			'name' => 'RC語音(大廳)',
			'status' => '1',
			'key' => 'rc2rc2rc2rc2', 
			'login_key' => '7bdfbd456b02a9b8bc979fad8ac6ca73',
			'transfer_key' => '1bd5f345e691s$d235b68cbf5ef3508b',
			'game_mapping' => array(
				'16' => 'bw',
				'17' => 'mon',
				'23' => 'my',		
				'24' => 'jh',
				'25' => 'eb',
				'26' => 'dxc',
				'27' => 'bt',
				'28' => 'xg',
				'29' => 'sw',
				'31' => 'qjp2',
				'35' => 'dp',
				'36' => 'sj',
				'37' => 'st',
				'38' => 'ly',
				'41' => 'fs',
				'42' => 'mq',
				'49' => 'lz',
				'50' => 'qq',
				'51' => 'hg',
				'52' => 'aj',
				'53' => 'jj',
				'54' => 'fen',
				'56' => 'dd',
				'57' => 'dd',
			),
			'sites' => array('bw'=>'', 'mon'=>'', 'my'=>'', 'jh'=>'', 'eb'=>'', 'dxc'=>'', 'bt'=>'', 'xg'=>'', 'sw'=>'', 'qjp2'=>'', 'dp'=>'', 'sj'=>'', 'st'=>'', 'ly'=>'', 'fs'=>'', 'mq'=>'', 'lz'=>'', 'qq'=>'', 'hg'=>'', 'aj'=>'', 'jj'=>'', 'fen'=>'', 'dd'=>'', 'cy'=>''),
		),
		'play168' => array(
			'name' => 'play168',
			'status' => '2',
			'sites' => array(
				'qjp' => 'http://www.play168.com.tw/LoginForm.aspx?game=klcp',
				'xj' => 'http://www.play168.com.tw/LoginForm.aspx?game=klsh',
			),
		),
		'igame' => array(
			'name' => '遊戲天堂',
			'status' => '2',
			'key' => 'igamer!@#$fun%long_e;tw;#',
			'sites' => array(
				'dh' => 'http://www.i-gamer.net/webgame/login.asp?game=cozdhs',
				'tc' => 'http://www.i-gamer.net/webgame/login.asp?game=coztcs',
				'qjp' => 'http://www.i-gamer.net/webgame/login.asp?game=cozqjp',
				'xj' => 'http://www.i-gamer.net/webgame/login.asp?game=cozxjs',
				'sg2' => 'http://www.i-gamer.net/webgame/login.asp?game=cozsag2',
				'mon' => 'http://www.i-gamer.net/webgame/login.asp?game=cozmcj',
				'xf' => 'http://www.i-gamer.net/webgame/login.asp?game=cozime',
				'sl2' => 'http://www.i-gamer.net/webgame/ig2coz.asp?game=coznsl',
				'bw' => 'http://www.i-gamer.net/webgame/ig2coz.asp?game=cozbw',
				'my' => 'http://www.i-gamer.net/webgame/ig2coz.asp?game=cozczmy',
				'ly' => 'http://www.i-gamer.net/webgame/ig2coz.asp?game=cozly',
			),
			'mapping' => array(
				'bw' => 'bw',
				'dhs' => 'dh',
				'tcs' => 'tc',
				'qjp' => 'qjp',
				'xjs' => 'xj',
				'sag2' => 'sg2',
				'mcj' => 'mon',
				'ime' => 'xf',
				'nsl' => 'sl2',
				'czmy' => 'my',
			)
		),
		'gogobox' => array(
			'name' => 'gogobox',
			'status' => '2',
			'sites' => array(
				'sg2' => array(
					'login_url' => 'http://evn.gogobox.com.tw/act/user_conn.php?a=user_conn&c=long_e&y=2012&n=72_sg2',
					'key1' => 'jBuSp5CV',
					'key2' => 'y46FYjfa',
				),
				'mon' => array(
					'login_url' => 'http://evn.gogobox.com.tw/act/user_conn.php?a=user_conn&c=long_e&y=2012&n=71_mon',
					'key1' => 'mhJf71FF',
					'key2' => 'n9I6urqz',
				),		
				'xf' => array(
					'login_url' => ' http://evn.gogobox.com.tw/act/user_conn.php?a=user_conn&c=long_e&y=2012&n=73_noname',
					'key1' => 'AyBYRRyl',
					'key2' => 'zwEu7jtv',
				),			
			) 
		),
		'2000fun' => array(
			'name' => '2000fun',
			'lib_name' => 'c_2000fun',
			'status' => '2',
			'oauth_url' => 'http://webgame.2000fun.com/api/oauth',
			'connect_url' => 'http://webgame.2000fun.com/api/connect',
			'partner_id' => '1163',
			'key' => 'ChFTwrBINE',
		),
		'more' => array(
			'name' => '摸摸耳',
			'key' => 'KLd46U6XKpf8DMP',
			'status' => '2',
			'sites' => array(
				//'long_e' => 'http://www.more.game.tw/webgame/dh.php', //暫用大漢的
				'dh' => 'http://www.more.game.tw/webgame/dh.php',
				'sg2' => 'http://www.more.game.tw/webgame/sg2.php',
				'mon' => 'http://www.more.game.tw/webgame/mon.php',
				'xf' => 'http://www.more.game.tw/webgame/xf.php',
				'sl2' => 'http://www.more.game.tw/webgame/sl2.php',
				'xl' => 'http://www.more.game.tw/webgame/xl.php',
				'yh' => 'http://www.more.game.tw/webgame/yh.php',
				'bw' => 'http://www.more.game.tw/webgame/bw.php',
				'gt' => 'http://www.more.game.tw/webgame/tth.php',
				'my' => 'http://www.more.game.tw/webgame/my.php',
			),
		),		
		'beanfun' => array(
			'name' => 'beanfun!樂豆',
			'status' => '2',
			'lib_name' => 'beanfun_api',
			'WS' => 'https://tw.webservices.beanfun.com/TW_ThirdPartyAdapter/ThirdPartyAdapter.asmx?WSDL',
			'WS_test' => 'http://202.80.108.42/TW_ThirdPartyAdapter/ThirdPartyAdapter.asmx?WSDL',
			'ThirdPartyID' => 'AliasProvider00027',
			'HashKey' => 'B292276c23bce44a979e',
			'sites' => array(
				'sl2' => array(
					'ServiceCode' => '611075',
				),
				'bw' => array(
					'ServiceCode' => '611076',
				),
			),
		),
		'ggc' => array(
			'name' => '因思銳',
			'status' => '2',
			'login_url' => 'http://www.ggconline.com/ggc/bank/Cooz_Free/Member_Free.asp',
			'key' => 'dj4xu4u.6vu4',
			'sites' => array('mon' => ''),
		),		
		'176game' => array(
			'name' => '176game',
			'lib_name' => 'c_176game',
			'status' => '2',
			'appkey' => 'g767fdd47f7098y8g5mnohiovfuiutgy',
			'storeid' => '9',
			'sites' => array('gt'=>'', 'bw'=>''),
		),		
		'qq' => array(
			'name' => 'QQ',
			'status' => '1',
		),
		'google' => array(
			'name' => 'Google',
			'status' => '2',
		),		
		'pixnet' => array(
			'name' => '痞客邦',
			'status' => '1',
		),		
		'imei' => array(
			'name' => '手機臨時',
			'status' => '1',
		),	
		'58play' => array(
			'name' => '58play',
			'status' => '1',
		),					
		'nicegame' => array(
			'name' => 'nicegame',
			'status' => '1',
		),	
	);

$config["fb_app"] = array(
		'dh_ad_a' => array(
			'appId' => '229365737185562',
			'secret' => 'c5fd11000b8e3bd9c055d74764ffd406',
		),
		'dh_ad_b' => array(
			'appId' => '404913996239236',
			'secret' => '9bdfc7233794888b7de41cbe5e5c6f1d',
		),
		'dh_ad_c' => array(
			'appId' => '184735148326044',
			'secret' => '3bac92411c04a35852a7c9bf0d57ce47',
		),
		'dh_ad_d' => array(
			'appId' => '193602910769883',
			'secret' => '5c91a4f7b404eff4ae864ace6031b26c',
		),
		'dh_ad_e' => array(
			'appId' => '145871988883996',
			'secret' => '83db98fc64b0b179d951aeb41b572372',
		),
		'dh_ad_f' => array(
			'appId' => '235815329849622',
			'secret' => '00a1b5582a3fcdee595d5a3545cc2c91',
		),
		'dh_ad_g' => array(
			'appId' => '232041880234891',
			'secret' => '99d66432fc0bc287bd01b2177c3f9f98',
		),
		'mon_ad_a' => array(
			'appId' => '165491690256201',
			'secret' => '5f12d030f2a8531a12f72c67fd50c748',
		),		
		'mon_ad_b' => array(
			'appId' => '492540787423253',
			'secret' => 'e15d676a36bc550f83cca14752063619',
		),				
		'xf_ad_a' => array(
			'appId' => '301473156620259',
			'secret' => 'cc52a07e59bad64cb88b10ae2b83cd37',
		),		
		'xf_ad_b' => array(
			'appId' => '397018817033091',
			'secret' => '7686dbf39e09a8144243a00a515a6047',
		),
		'xf_ad_c' => array(
			'appId' => '108381859328912',
			'secret' => '5cc6e629a4515d60798c9c66c9688c91',
		),
		'sl2_ad_a' => array(
			'appId' => '579935035353179',
			'secret' => 'aebcfa7b9ed9953b12de58c67dc5f927',
		),
		'sl2_ad_b' => array(
			'appId' => '535815153103593',
			'secret' => '197b6638f358b07ebb1aba9a5d0fd657',
		),
		'sl2_ad_c' => array(
			'appId' => '403849629696345',
			'secret' => '63043c8f75dd14a0cc45add020db0265',
		),
		'sl2_ad_d' => array(
			'appId' => '546521528692440',
			'secret' => '4c05ba0a08158acfe4015cb0146bc269',
		),
		'sl2_ad_e' => array(
			'appId' => '552908694722210',
			'secret' => 'f773b04a73272335550f662260f1ba76',
		),
		'sl2_ad_app' => array(
			'appId' => '145324982297815',
			'secret' => '6352a68763240378178fe57080baf2f3',
		),		
		'xj_ad_a' => array(
			'appId' => '407204576033174',
			'secret' => '6a61a9d2470fcbb924b10da1027d78cf',
		),		
		'xj_ad_b' => array(
			'appId' => '163767200437637',
			'secret' => '0993413bde12e610c82c82b97fd8b1e3',
		),	
		'xj_ad_c' => array(
			'appId' => '531231766907258',
			'secret' => 'bc10da52c73b5f378e947d7c4e8524ce',
		),	
		'xj_ad_d' => array(
			'appId' => '476882249051689',
			'secret' => '1388f6c83b47ba94e5c9b538031e42a0',
		),
		'xj_ad_e' => array(
			'appId' => '144358389078842',
			'secret' => 'e6b631b621844421fa9ffe4ec18df430',
		),
		'xj_ad_f' => array(
			'appId' => '149759291870053',
			'secret' => '31edcb4c94080a5d0a68ed85493fb7c8',
		),
		'xj_ad_g' => array(
			'appId' => '193454587469570',
			'secret' => 'ee2a46f9a8d74ea763e27daf5b852896',
		),
		'xf_ad_app' => array(
			'appId' => '312074702247895',
			'secret' => '2220d2c875c7c9552c9ce1e49360b969',
		),		
		'xl_ad_a' => array(
			'appId' => '144044002431869',
			'secret' => '65296ebff1103f563e76494997456707',
		),
		'xl_ad_b' => array(
			'appId' => '608181142544344',
			'secret' => '79a880bc38fbc148a45838a9d54c1a96',
		),
		'xl_ad_c' => array(
			'appId' => '310654722397419',
			'secret' => '8d4c704513d2b2aa7e688eb1bff01bd6',
		),
		'xl_ad_d' => array(
			'appId' => '553065638057846',
			'secret' => '284d76025ef5441baba8c68216e81e94',
		),
		'xl_ad_e' => array(
			'appId' => '432046386887123',
			'secret' => '7da865c5825116b3b3112212926bbad4',
		),
		'xl_ad_f' => array(
			'appId' => '308586539269458',
			'secret' => 'e07db39e5f91d3b78aab4049a4e68fe5',
		),
		'xl_ad_g' => array(
			'appId' => '156291097868862',
			'secret' => 'e89456a1b115e48fd6dd3d80bb1551c1',
		),							
		'yh_ad_a' => array(
			'appId' => '358983007543415',
			'secret' => '9675614710b063062fd393b335cf7d36',
		),
		'yh_ad_b' => array(
			'appId' => '441403725933925',
			'secret' => '3300ef6e4f6ba8d7991cc9f0579c3809',
		),				
		'yh_ad_c' => array(
			'appId' => '437543116337040',
			'secret' => '2176a8308566895d8bcb785ea277a00d',
		),				
		'yh_ad_d' => array(
			'appId' => '168766956610773',
			'secret' => '28f3b2b73b52be5ee3c49becbd65c557',
		),
		'yh_ad_e' => array(
			'appId' => '633276493365605',
			'secret' => 'bd04885bf959418d86f8c848c14fd72a',
		),
		'yh_ad_f' => array(
			'appId' => '492324387484098',
			'secret' => '2be3d713bc3a42972ee893a289d2b3be',
		),
		'yh_ad_g' => array(
			'appId' => '154030328092602',
			'secret' => '6df96ac82b0d74327fc8b3b685560c64',
		),
		'bw_ad_a' => array(
			'appId' => '606329792729329',
			'secret' => '859b1484c4f6a5bcc0006a82f10d18ba',
		),
		'bw_ad_b' => array(
			'appId' => '281673748634365',
			'secret' => '891a321b9dafdb67549e1c5c52d18460',
		),	
		'bw_ad_c' => array(
			'appId' => '315475928581086',
			'secret' => 'eaf05e7fab74851547abd39a335d5e3c',
		),	
		'bw_ad_d' => array(
			'appId' => '449272961821289',
			'secret' => '24f3849eef06680c97f5bc4e7f4b2a97',
		),	
		'bw_ad_e' => array(
			'appId' => '137898653065066',
			'secret' => 'd6fc0a28bfafa31e152de5d3c19841c6',
		),	
		'bw_ad_f' => array(
			'appId' => '523876771009983',
			'secret' => '611de7cd8eef60c7421e2ad8de3b1533',
		),	
		'bw_ad_g' => array(
			'appId' => '451165448302545',
			'secret' => 'd7fdd0de5fefe2db71da688d60819960',
		),
		'bw_ad_h' => array(
			'appId' => '168668069979991',
			'secret' => '38c42ee61a2089a77c1af4c874f13053',
		),		
		'gt_ad_a' => array(
			'appId' => '142473015938280',
			'secret' => '9d0d3434095ac25d8afc9e3e411d7d95',
		),
		'gt_ad_b' => array(
			'appId' => '578735332148166',
			'secret' => '84edb5d77ad46fd932cd9bceb38a18e6',
		),
		'gt_ad_c' => array(
			'appId' => '144413455743322',
			'secret' => '66bd88dc3c342cc684ac7cb84ea15877',
		),
		'gt_ad_d' => array(
			'appId' => '197633127051373',
			'secret' => 'e042015d4c6c001ed314a8886fdb3801',
		),
		'gt_ad_e' => array(
			'appId' => '502334149832522',
			'secret' => 'cb8190502a92c9a15847397aab0266fc',
		),
		'gt_ad_f' => array(
			'appId' => '535864753125944',
			'secret' => 'd2923ec45f0d9234de0f9c2e0f0931dd',
		),
		'gt_ad_g' => array(
			'appId' => '516671535053706',
			'secret' => '4cb0fd17816b645d25f738202ce9b15a',
		),		
		'my_ad_a' => array(
			'appId' => '211704879027885',
			'secret' => 'b341bf4fcfe8c35c693dc15d077bc17d',
		),
		'my_ad_b' => array(
			'appId' => '644052562308526',
			'secret' => '57ca98f9f81fe9d2f0250f8a6e5215a3',
		),
		'my_ad_c' => array(
			'appId' => '212555065609029',
			'secret' => '8d6b1e6a0e8d9d1882babccfbb4d4a03',
		),
		'my_ad_d' => array(
			'appId' => '1449083421987997',
			'secret' => 'd2e53a0ba758a58de51cd7a6829f70cb',
		),
		'my_ad_e' => array(
			'appId' => '531089876998142',
			'secret' => '1611c3df92b4516107d99227a81d9d6f',
		),
		'my_ad_f' => array(
			'appId' => '762435920433295',
			'secret' => 'c31cb2f14864bd1962b5d5bd5f9c4425',
		),
		'my_ad_g' => array(
			'appId' => '662120210514409',
			'secret' => '79847b039feb0657550dfa59444c3c83',
		),	
		'my_ad_h' => array(
			'appId' => '787802187911561',
			'secret' => 'e7c4483130aa42746764bb68bcb5b07b',
		),	
		'my_ad_i' => array(
			'appId' => '510748502385584',
			'secret' => '5fd5e15fb1b7d9a0666dd9445e6d2f49',
		),					
	);