<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['enable'] = array(
		1 => array("name"=>"是", "color"=>"#000"),
		0 => array("name"=>"否", "color"=>"#999"),
	);

$config['main_menu'] = array(
	"statistics" => array('name' => '統計', 'menu' => array(
		 	array('name' => '數據報表', 'path' => 'statistics/operation'),
			array('name' => '在線時長', 'path' => 'statistics/game_length'),
			array('name' => '留存率', 'path' => 'statistics/retention'),
			array('name' => '營收分析', 'path' => 'statistics/revenue'),
			array('name' => '鯨魚用戶', 'path' => 'statistics/whale_users'),
			array('name' => '行銷效益', 'path' => 'ad'),
		)
	),	
	"game" => array('name' => '遊戲設定', 'menu' => array(	
			array('name' => '遊戲管理', 'path' => 'game'),
			array('name' => '伺服器管理', 'path' => 'server/get_list'),
		)
	),		
	"trade" => array('name' => '交易', 'menu' => array(
		 	array('name' => '轉點查詢', 'path' => 'trade/transfer'),
			array('name' => '轉點統計', 'path' => 'trade/transfer_statistics'),
			array('name' => 'Gash儲值查詢', 'path' => 'trade/gash'),
			array('name' => 'Gash儲值統計', 'path' => 'trade/gash_statistics'),
			array('name' => 'Pepay儲值查詢', 'path' => 'trade/pepay'),
			array('name' => 'Pepay儲值統計', 'path' => 'trade/pepay_statistics'),
			array('name' => 'Google儲值查詢', 'path' => 'trade/google'),
			array('name' => 'Google儲值統計', 'path' => 'trade/google_statistics'),
			array('name' => 'IOS儲值查詢', 'path' => 'trade/ios'),
			array('name' => 'IOS儲值統計', 'path' => 'trade/ios_statistics'),
			array('name' => 'OMG交易API查詢', 'path' => 'trade/omg_api'),
			array('name' => '---------------', 'path' => ''),
			array('name' => 'MyCard儲值查詢', 'path' => 'trade/mycard'),
			array('name' => 'MyCard儲值統計', 'path' => 'trade/mycard_statistics'),			
			array('name' => 'MyCard交易API查詢', 'path' => 'trade/mycard_api'),			
		)
	),	
	"service" => array('name' => '客服', 'menu' => array(
			array('name' => '總覽', 'path' => 'service'),
		 	array('name' => '待處理案件 (自動重整)', 'path' => 'service/todo'),
			array('name' => '等待中案件', 'path' => 'service/get_list?status=2&action=%E6%9F%A5%E8%A9%A2'),
			array('name' => '電話案件', 'path' => 'service/get_list?type=9&action=%E6%9F%A5%E8%A9%A2'),			
			array('name' => '+新增電話案件', 'path' => 'service/add'),
			array('name' => '交接案件', 'path' => 'service/question_assign/not'),
			array('name' => '我的後送案件', 'path' => 'service/my'),
		)
	),	
	"member" => array('name' => '會員', 'menu' => array(
		 	array('name' => '查詢', 'path' => 'member'),
			array('name' => '測試帳號', 'path' => 'member/testaccounts'),
			array('name' => '不平衡帳號', 'path' => 'member/no_balance'),
			array('name' => '停權帳號', 'path' => 'member/is_banned'),
		)
	),
	"frontend" => array('name' => '前台管理', 'menu' => array(
		 	array('name' => '公告管理', 'path' => 'bulletin/get_list?game_id=long_e'),
			array('name' => '通知管理', 'path' => 'notice/get_list'),
			array('name' => 'banner', 'path' => 'picture/get_list?game_id=long_e'),
			array('name' => 'skybar_banner', 'path' => 'home/skybar_banner'),
		)
	),	
	"backend" => array('name' => '後台管理', 'menu' => array(
			array('name' => '平台登入', 'path' => 'log/login'),
			array('name' => '遊戲登入', 'path' => 'log/game_login'),
			array('name' => '線上會員', 'path' => 'log/online_user'),
			array('name' => '後台動作', 'path' => 'log/admin_action'),	
			array('name' => '使用者管理', 'path' => 'manage/user'),		
			array('name' => '權限設定', 'path' => 'manage/role'),
			array('name' => '功能設定', 'path' => 'manage/resource'),
		)
	),	
);	

/*$config['main_menu'] = array(
	"member" => array('name' => '會員', 'menu' => array(
		 	array('name' => '查詢', 'path' => 'member'),
			array('name' => '測試帳號', 'path' => 'member/testaccounts'),
			array('name' => '不平衡帳號', 'path' => 'member/no_balance'),
			array('name' => '停權帳號', 'path' => 'member/is_banned'),
		)
	),		
	"trade" => array('name' => '交易', 'menu' => array(
		 	array('name' => '轉點查詢', 'path' => 'trade/transfer'),
			array('name' => '轉點統計', 'path' => 'trade/transfer_statistics'),
			array('name' => 'Gash儲值查詢', 'path' => 'trade/gash'),
			array('name' => 'Gash儲值統計', 'path' => 'trade/gash_statistics'),
			array('name' => 'Pepay儲值查詢', 'path' => 'trade/pepay'),
			array('name' => 'Pepay儲值統計', 'path' => 'trade/pepay_statistics'),
			array('name' => 'Google儲值查詢', 'path' => 'trade/google'),
			array('name' => 'Google儲值統計', 'path' => 'trade/google_statistics'),
			array('name' => 'IOS儲值查詢', 'path' => 'trade/ios'),
			array('name' => 'IOS儲值統計', 'path' => 'trade/ios_statistics'),
			array('name' => 'OMG交易API查詢', 'path' => 'trade/omg_api'),
			array('name' => '---------------', 'path' => ''),
			array('name' => 'MyCard儲值查詢', 'path' => 'trade/mycard'),
			array('name' => 'MyCard儲值統計', 'path' => 'trade/mycard_statistics'),			
			array('name' => 'MyCard交易API查詢', 'path' => 'trade/mycard_api'),			
		)
	),
	"home" => array('name' => '首頁', 'use_submenu' => true, 'menu' => array(
		 	array('name' => '公告管理', 'path' => 'bulletin/get_list?game_id=long_e'),
			array('name' => '通知管理', 'path' => 'notice/get_list'),
			array('name' => 'banner', 'path' => 'picture/get_list?game_id=long_e'),
			array('name' => 'skybar_banner', 'path' => 'home/skybar_banner'),
			array('name' => '遊戲管理', 'path' => 'game'),
		)
	),		
	"game" => array('name' => '遊戲', 'menu' => array(	
		)
	),				
 	"ad" => array('name' => '廣告', 'menu' => array(
 			array('name' => '廣告管理', 'path' => 'ad/manage'),
 			array('name' => '廣告統計', 'path' => 'ad/statistics'),
 		)
 	),
	"event" => array('name' => '活動', 'menu' => array(

			array('name' => '真36_師徒獎勵_lv80', 'path' => 'event/code/zj_1106_p3_lv80'),
			array('name' => '真36_師徒獎勵_lv70', 'path' => 'event/code/zj_1106_p3_lv70'),
			array('name' => '真36_師徒獎勵_lv60', 'path' => 'event/code/zj_1106_p3_lv60'),
			array('name' => '真36_師徒獎勵_lv40', 'path' => 'event/code/zj_1106_p3_lv40'),
			array('name' => '真36_師徒獎勵_lv20', 'path' => 'event/code/zj_1106_p3_lv20'),
			array('name' => '真36_師徒獎勵_拜師', 'path' => 'event/code/zj_1106_p3_1'),
			array('name' => '真36_全家咖啡創角', 'path' => 'event/code/zj_1106_p2'),
			array('name' => '真36_官網序號發放', 'path' => 'event/code/web_fb_zj'),			
			array('name' => '真36_FB序號發放', 'path' => 'event/code/fb_20141015'),
			array('name' => '狩神_fb_20140429', 'path' => 'event/code/fb_20140429'),
			array('name' => '反轉_fb_20140326', 'path' => 'event/code/fb_20140326'),
			array('name' => '該死_fb_20131226', 'path' => 'event/code/fb_20140225'),
			array('name' => '卡哇_fb_20131226', 'path' => 'event/code/fb_20131226'),
			array('name' => '絕代_fb_20130509', 'path' => 'event/code/fb_20130509'),
			array('name' => '霸王_fb_20130415', 'path' => 'event/code/fb_20130415'),
			array('name' => '炎黃_fb_20130408', 'path' => 'event/code/fb_20130408'),
			array('name' => '仙若_fb_20130326', 'path' => 'event/code/fb_20130326'),
			array('name' => '小李2_fb_20130104', 'path' => 'event/code/fb_20130104'),
			array('name' => '仙府_fb_20121113', 'path' => 'event/code/fb_20121113'),
			array('name' => '萌寵_fb_20121017', 'path' => 'event/code/fb_20121017'),
			array('name' => '三國2_fb_20121016', 'path' => 'event/code/sg_20121016'),
			array('name' => '三國2_fb_20120927', 'path' => 'event/code/fb_20120927'),										
		)
	),
	"service" => array('name' => '客服', 'menu' => array(
			array('name' => '總覽', 'path' => 'service'),
		 	array('name' => '待處理案件 (自動重整)', 'path' => 'service/todo'),
			array('name' => '等待中案件', 'path' => 'service/get_list?status=2&action=%E6%9F%A5%E8%A9%A2'),
			array('name' => '電話案件', 'path' => 'service/get_list?type=9&action=%E6%9F%A5%E8%A9%A2'),			
			array('name' => '+新增電話案件', 'path' => 'service/add'),
			array('name' => '交接案件', 'path' => 'service/question_assign/not'),
			array('name' => '我的後送案件', 'path' => 'service/my'),
		)
	),			
	"log" => array('name' => '記錄', 'menu' => array(
			array('name' => '平台登入', 'path' => 'log/login'),
			array('name' => '遊戲登入', 'path' => 'log/game_login'),
			array('name' => '線上會員', 'path' => 'log/online_user'),
			array('name' => '後台動作', 'path' => 'log/admin_action'),			
		)
	),
	"manage" => array('name' => '系統管理', 'menu' => array(
			array('name' => '使用者管理', 'path' => 'manage/user'),		
			array('name' => '權限設定', 'path' => 'manage/role'),
			array('name' => '功能設定', 'path' => 'manage/resource'),			
		)
	),		
);*/

$fn = array(
	'p_character' => array('name' => '創角統計', 'path' => 'character/partner', 'auth' => 'p_character'),
	'p_transfer' => array('name' => '轉點統計', 'path' => 'trade/partner', 'auth' => 'p_transfer'), 
);
$config['partner_def_menu'] = array($fn['p_character'], $fn['p_transfer']);
$config['partner_menu'] = array(
	'all' => array('name' => '龍邑 - 廠商查詢', 'menu' => array()),
	'kimi' => array('name' => '奇米', 'menu' => array()),
	'rc' => array('name' => 'RC語音', 'menu' => array()),
	'artsy' => array('name' => '艾斯科技', 'menu' => array()),
);

$config['channels'] = array( 
			'long_e'		=> '龍邑會員',
			'facebook'	=> 'Facebook',
			'bahamut'	=> '巴哈姆特',
			'gamebase'	=> '遊戲基地',
			'omg'		=> 'OhMyGod',
			'ggc'		=> '因思銳',
			'play168'	=> 'play168',
			'rc'		=> 'RC語音',
			'rc2'		=> 'RC大廳',
			'rc%'		=> 'RC語音+大廳',
			'yoid'		=> 'yahoo國際',		
			'yahoo'		=> 'yahoo遊戲',		
			'minik'		=> 'yahoo社群遊戲',
			'gogobox'	=> 'gogobox',
			'2000fun'	=> '2000fun',
			'igame'		=> '遊戲天堂',		
			'more'		=> '摸摸耳',
			'yam'		=> '蕃薯藤',		
			'beanfun'	=> 'beanfun!樂豆',
			'kimi'		=> '奇米',
			'176game'	=> '176game',
			'artsy'		=> '艾斯科技',
			'google'	=> 'Google',
			'dtalent'	=> '大天人',
			'179game'	=> '玩客',
			'smmo'	=> '星馬',		
			'muxplay'	=> '一遊',					
			'egame101'	=> '貓眼',
			'58play'	=> '赤霄',
			'nicegame'	=> '戲雋',
			'skyler'	=> 'Skyler',
			'imei'		=> '手機試玩',
		);

$config['ad_channels'] = array(
	'bahamut'	=> '巴哈姆特',
	'gamebase'	=> '遊戲基地',
	'google_keyword'	=> 'Google關鍵字',
	'yahoo_keyword'	=> 'Yahoo關鍵字',
	'yahoo'	=> 'Yahoo廣告',
	'google'	=> 'Google聯播網',
	'gamme'	=> '卡卡洛普',
	'plurk'	=> '噗浪',
	'iga_flash'	=> '井字配方',
	'offerme2'	=> 'Offerme2',
	'pps'	=> 'PPS',
	'takecoco'	=> 'Takecoco',
	'scupio'	=> '酷比Scupio',
	'softworld'	=> '智冠',
	'kyo'	=> '香港Yahoo廣告',		
	'nakuz' => '香港樂古',
	'17t17p' => '17t17p',
	'eprice' => '比價王',
	'adplace'  => '傳漾聯播網',
	'rc' => 'RC語音',
	'hao123' => '百度台灣',
	'long_e_top' => '龍邑資訊列',
	'176game' => '176game',
	'winwin' => 'winwin',
	'ccmedia'	=> 'ccmedia',
	'ccmediasex'	=> 'ccmediasex',
	'inter' => '英特數位',
);

$config['extra_ad_channels'] = array(
	'dh' => array(
		'dh_ad_a'	=> '大漢-FB廣告a',
		'dh_ad_b'	=> '大漢-FB廣告b',
		'dh_ad_c'	=> '大漢-Fb廣告c',
		'dh_ad_d'	=> '大漢-Fb廣告d',
		'dh_ad_e'	=> '大漢-FB廣告e',
		'dh_ad_f'	=> '大漢-FB廣告f',
		'dh_ad_g'	=> '大漢-FB廣告g',
	),
	'mon' => array(
		'mon_ad_a'	=> '萌寵-FB廣告a',
		'mon_ad_b'	=> '萌寵-FB廣告b',	
		'mon_yahoo_a'	=> '萌寵-Yahoo關鍵字廣告a',
	),
	'xf' => array(
		'xf_ad_a' 	=> '仙府-FB廣告a',
		'xf_ad_b' 	=> '仙府-FB廣告b',
		'xf_ad_c' 	=> '仙府-FB廣告c',	
	),
	'sl2' => array(
		'sl2_ad_a' 	=> '新小李-FB廣告a',
		'sl2_ad_b' 	=> '新小李-FB廣告b',
		'sl2_ad_c' 	=> '新小李-FB廣告c',
		'sl2_ad_d' 	=> '新小李-FB廣告d',
		'sl2_ad_e' 	=> '新小李-FB廣告e',	
	),		
	'xj' => array(
		'xj_ad_a' 	=> '仙境-FB廣告a',
		'xj_ad_b' 	=> '仙境-FB廣告b',
		'xj_ad_c' 	=> '仙境-FB廣告c',	
	),		
	'xl' => array(
		'xl_ad_a' 	=> '仙落-FB廣告a',
		'xl_ad_b' 	=> '仙落-FB廣告b',
		'xl_ad_c' 	=> '仙落-FB廣告c',
		'xl_ad_d' 	=> '仙落-FB廣告d',	
	),	
	'yh' => array(
		'yh_ad_a' 	=> '炎黃-FB廣告a',
		'yh_ad_b' 	=> '炎黃-FB廣告b',
		'yh_ad_c' 	=> '炎黃-FB廣告c',
		'yh_ad_d' 	=> '炎黃-FB廣告d',	
	),					
);

$config['transaction_type'] = array(
	'omg_billing' => 'OMG',		
	'bahamut_billing' => '巴哈代幣',
	'beanfun_billing' => 'beanfun!樂豆',		
	'kimi_billing' => '奇米',
	'artsy_billing' => '艾斯',
	'rc_billing' => 'RC',
	'179game_billing' => '玩客',
	'smmo_billing'	=> '星馬',		
	'muxplay_billing'	=> '一遊',		
	'egame101_billing' => '貓眼',
	'58play_billing' => '赤霄',
	'nicegame_billing' => '戲雋',
	'skyler_billing' => 'skyler',
);

$config['bulletin_type'] = array(
	1 => '公告',		
	2 => '活動',
	3 => '系統'
);
/* End of file config.php */
/* Location: ./application/config/config.php */
