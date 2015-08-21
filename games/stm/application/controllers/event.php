<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//
// 活動用頁面
//
class Event extends MY_Controller
{
	// 預註冊活動
    // 1.事前登錄活動時間為2015/9/14(一) 0:00~ 2015/9/27(一)23:59。
	// 2. 序號保留至2015/12/31 23:59。
	// 3. 本活動需輸入正確之手機號碼，若為空號或無法使用將喪失獲獎資格。
	// 4. 一組E-MAIL/手機號碼僅可參加活動一次。
	// 5. 每個遊戲帳號僅能兌換一次事前登錄獎勵。
	function register()
	{
		// 讀取活動資料
		$event = $this->db->from("events")->where("id", 1)->where("game_id", "stm")->get();

		$this->_init_layout()
				->set("event", $event)
				->event_view();
	}

	function register_json()
	{

	}
}
