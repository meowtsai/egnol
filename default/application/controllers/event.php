<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Event extends MY_Controller {

	function zj_1106_p1() 
	{
		$this->_init_layout()->render("", "event/zj_1106");	
	}
	
	function zj_1106_p2() 
	{
		$this->load->library("code", array("event"=>$this->router->method));
		$this->_init_layout()->render("", "event/zj_1106");	
	}
	
	function zj_1106_p3() 
	{
		$this->_init_layout()->render("", "event/zj_1106");	
	}
	
	function zj_1106_p3_1() 
	{
		$this->g_user->check_login(true);		
					
		$this->load->library("code", array("event"=>$this->router->method));		
		$record = $this->db->where("uid", $this->g_user->uid)->from("zj_pushs")->get()->row();		
		if (empty($record) && $this->input->post("flag") == '1') {
			$euid = $this->input->post("euid");
			
			if ( empty($euid) ) die("<script>alert('請輸入師父ID'); location.href='/event/zj_1106_p3_1';</script>");			
			if ($euid == $this->g_user->euid) die("<script>alert('不可以唷!'); location.href='/event/zj_1106_p3_1';</script>");
			
			$push_uid = $this->g_user->decode($euid);			
			$cnt = $this->db->where("uid", $push_uid)->from("users")->count_all_results();
			if (empty($push_uid) || $cnt == 0) die("<script>alert('師父ID不存在'); location.href='/event/zj_1106_p3_1';</script>");
			
			$cnt = $this->db->where("push_uid", $push_uid)->from("zj_pushs")->count_all_results();
			if ($cnt >= 10) die("<script>alert('師父已經收滿徒弟了'); location.href='/event/zj_1106_p3_1';</script>");
			
			$url = "http://203.75.245.81:3000/sg_user?serv_id=1&acc_id=".$this->g_user->euid;
			$re = my_curl($url);
			$json = json_decode($re);
			if ($json->status !== 0) die("<script>alert('你還沒有創建角色唷'); location.href='/event/zj_1106_p3_1';</script>");
						
			$this->db
				->set("create_time", "NOW()", FALSE)
				->insert("zj_pushs", array("uid" => $this->g_user->uid, "push_uid" => $push_uid));
			header('location: /event/zj_1106_p3_1');
			exit();
		}						
		
		$this->_init_layout()
			->set("record", $record)
			->render("", "event/zj_1106");	
	}
	
	function zj_1106_p3_2() 
	{
		$this->g_user->check_login(true);
		
		$t_map = array("1" => 20, "2" => 40, "3" => 60, "4" => 70, "5" => 80);
		$lv = $this->input->get("t") ? $t_map[$this->input->get("t")] : 20;
		if (empty($lv)) $lv = 20;

		$this->load->library("code", array("event"=>"zj_1106_p3_lv{$lv}"));
		
		$query = $this->db->from("zj_pushs")->where("push_uid", $this->g_user->uid)->get();
		
		$this->_init_layout()
			->set("query", $query)
			->set("lv", $lv)
			->render("", "event/zj_1106");	
	}
	
	function zj_1106_p4() 
	{
		$this->_init_layout()->render("", "event/zj_1106");	
	}
	
	function web_zj()
	{		    	
    	$this->_init_layout()->render("", "event/zj");		
	}
	
	function web_zj_p2()
	{		    	
    	$this->_init_layout()->render("", "event/zj");		
	}	
	
	function web_zj_p3()
	{		    	
    	$this->_init_layout()->render("", "event/zj");		
	}
	
	function web_zj_p4()
	{		    	
    	$this->_init_layout()->render("", "event/zj");		
	}		
	
	function web_fb_zj_login()
	{
		$this->load->library("channel_api/fb_api");
							
    	if (empty($this->fb_api->uid)) 
    	{    		
    		//$redirect_uri = urlencode("https://www.facebook.com/long_e.inc/app_181504455307555");
    		$loginUrl = $this->fb_api->login("", 
    			array(
    				"scope" => "user_likes",
    				"redirect_uri"=>site_url("event/web_fb_zj")));
    		exit();
    	}
    	else {
    		header('location: '. site_url("event/web_fb_zj"));
    		exit();
    	}
	}
	
	function web_fb_zj()
	{
		$this->load->library("channel_api/fb_api");
		$this->load->library("code", array("event"=>$this->router->method));
				
		$code = ''; $msg = '';
		
		$uid = $this->fb_api->uid;
		$page_id = '125949627496483';
		
		try {
			if ($this->fb_api->chk_fb_fan($uid, $page_id)) {
    			if ($this->code->chk_matched($uid)) {
    				$code = $this->code->get_user_code($uid);
    			}
    			else {
    				if ($this->code->chk_code_enough()) {
    					$this->code->match_code($uid);
    					$code = $this->code->get_user_code($uid);
    				}
    				else {
    					$msg = '序號已發放完畢';
    				}
    			}    
    			$pass = true;			
    		}
		} catch (FacebookApiException $e) {
			$uid = null;
		}
		    	
    	$this->_init_layout();
    	$this->g_layout
    		->set("uid", $uid)    		
    		->set("msg", $msg)
    		->set("code", $code)
    		->view();		
	}
	
	function fb_zj()
	{
		$this->_init_layout()->view();
	}
	
	//真三十六計粉絲按讚活動
	function fb_20141015()
	{	
		$this->load->library("channel_api/fb_api");
		$this->load->library("code", array("event"=>$this->router->method));
				
		$code = ''; $msg = '';
		
		$uid = $this->fb_api->uid;
		$page_id = '125949627496483';
		
		try {
			if ($this->fb_api->chk_fb_fan($uid, $page_id)) {
    			if ($this->code->chk_matched($uid)) {
    				$code = $this->code->get_user_code($uid);
    			}
    			else {
    				if ($this->code->chk_code_enough()) {
    					$this->code->match_code($uid);
    					$code = $this->code->get_user_code($uid);
    				}
    				else {
    					$msg = '序號已發放完畢';
    				}
    			}    			
    		}
    		else {
    			$msg = '請對粉絲團點讚才可以領取喔';
    		}
		} catch (FacebookApiException $e) {
			$this->fb_api->uid = null;
		}
		
    	if (empty($this->fb_api->uid)) 
    	{    		
    		//$redirect_uri = urlencode("https://www.facebook.com/long_e.inc/app_181504455307555");
    		$loginUrl = $this->fb_api->login("", 
    			array(
    				"scope" => "user_likes",
    				"redirect_uri"=>site_url("event/fb_20141015_redirect")));
    		exit();
    	}
    	
    	$this->_init_layout();
    	$this->g_layout
    		->set("msg", $msg)
    		->set("code", $code)
    		->view();
	}
	
	function fb_20141015_redirect()
	{
		$this->load->library("channel_api/fb_api");
		header('location: https://www.facebook.com/long_e.inc/app_699375770144744');
	}	
	
	//反轉地下城粉絲按讚活動
	function fb_20140429()
	{	
		$this->load->library("channel_api/fb_api");
		$this->load->library("code", array("event"=>$this->router->method));
				
		$code = ''; $msg = '';
		
		$uid = $this->fb_api->uid;
		$page_id = '125949627496483';
		
		try {
			if ($this->fb_api->chk_fb_fan($uid, $page_id)) {
    			if ($this->code->chk_matched($uid)) {
    				$code = $this->code->get_user_code($uid);
    			}
    			else {
    				if ($this->code->chk_code_enough()) {
    					$this->code->match_code($uid);
    					$code = $this->code->get_user_code($uid);
    				}
    				else {
    					$msg = '序號已發放完畢';
    				}
    			}    			
    		}
    		else {
    			$msg = '請對粉絲團點讚才可以領取喔';
    		}
		} catch (FacebookApiException $e) {
			$this->fb_api->uid = null;
		}
		
    	if (empty($this->fb_api->uid)) 
    	{    		
    		//$redirect_uri = urlencode("https://www.facebook.com/long_e.inc/app_181504455307555");
    		$loginUrl = $this->fb_api->login("", 
    			array(
    				"scope" => "user_likes",
    				"redirect_uri"=>site_url("event/fb_20140429_redirect")));
    		exit();
    	}
    	
    	$this->_init_layout();
    	$this->g_layout
    		->set("msg", $msg)
    		->set("code", $code)
    		->view();
	}
	
	function fb_20140429_redirect()
	{
		$this->load->library("channel_api/fb_api");
		header('location: https://www.facebook.com/long_e.inc/app_720957214611747');
	}	
	
	//反轉地下城粉絲按讚活動
	function fb_20140326()
	{	
		$this->load->library("channel_api/fb_api");
		$this->load->library("code", array("event"=>$this->router->method));
				
		$code = ''; $msg = '';
		
		$uid = $this->fb_api->uid;
		$page_id = '125949627496483';
		
		try {
			if ($this->fb_api->chk_fb_fan($uid, $page_id)) {
    			if ($this->code->chk_matched($uid)) {
    				$code = $this->code->get_user_code($uid);
    			}
    			else {
    				if ($this->code->chk_code_enough()) {
    					$this->code->match_code($uid);
    					$code = $this->code->get_user_code($uid);
    				}
    				else {
    					$msg = '序號已發放完畢';
    				}
    			}    			
    		}
    		else {
    			$msg = '請對粉絲團點讚才可以領取喔';
    		}
		} catch (FacebookApiException $e) {
			$this->fb_api->uid = null;
		}
		
    	if (empty($this->fb_api->uid)) 
    	{    		
    		//$redirect_uri = urlencode("https://www.facebook.com/long_e.inc/app_181504455307555");
    		$loginUrl = $this->fb_api->login("", 
    			array(
    				"scope" => "user_likes",
    				"redirect_uri"=>site_url("event/fb_20140326_redirect")));
    		exit();
    	}
    	
    	$this->_init_layout();
    	$this->g_layout
    		->set("msg", $msg)
    		->set("code", $code)
    		->view();
	}
	
	function fb_20140326_redirect()
	{
		$this->load->library("channel_api/fb_api");
		header('location: https://www.facebook.com/long_e.inc/app_720957214611747');
	}	
	
	//該死的江湖粉絲按讚活動
	function fb_20140225()
	{	
		$this->load->library("channel_api/fb_api");
		$this->load->library("code", array("event"=>$this->router->method));
				
		$code = ''; $msg = '';
		
		$uid = $this->fb_api->uid;
		$page_id = '125949627496483';
		
		try {
			if ($this->fb_api->chk_fb_fan($uid, $page_id)) {
    			if ($this->code->chk_matched($uid)) {
    				$code = $this->code->get_user_code($uid);
    			}
    			else {
    				if ($this->code->chk_code_enough()) {
    					$this->code->match_code($uid);
    					$code = $this->code->get_user_code($uid);
    				}
    				else {
    					$msg = '序號已發放完畢';
    				}
    			}    			
    		}
    		else {
    			$msg = '請對粉絲團點讚才可以領取喔';
    		}
		} catch (FacebookApiException $e) {
			$this->fb_api->uid = null;
		}
		
    	if (empty($this->fb_api->uid)) 
    	{    		
    		//$redirect_uri = urlencode("https://www.facebook.com/long_e.inc/app_181504455307555");
    		$loginUrl = $this->fb_api->login("", 
    			array(
    				"scope" => "user_likes",
    				"redirect_uri"=>site_url("event/fb_20140225_redirect")));
    		exit();
    	}
    	
    	$this->_init_layout();
    	$this->g_layout
    		->set("msg", $msg)
    		->set("code", $code)
    		->view();
	}
	
	function fb_20140225_redirect()
	{
		$this->load->library("channel_api/fb_api");
		header('location: https://www.facebook.com/long_e.inc/app_1414847435430915');
	}	
	
	function check_xj6_role_exists()
	{
		if ($this->input->get("guid") == false) die("參數錯誤");
		
		$this->g_user->check_login('', true);
		$this->load->library("game_api/xj");
		
		$server = $this->db->where("server_id", 'xj6')->from("servers")->get()->row();
		$user = (object) array("uid"=>$this->g_user->uid, "account"=>$this->g_user->account);
		$re = $this->xj->check_role_status($server, $user);
		
    	if ($re == "-1") echo "伺服器無回應，請稍候再試";
    	else if ($re == "0") echo "伺服器沒有角色！";
    	else {
    		header("location: http://www.beautyme.com.tw/kotex_alp/send.aspx?guid={$this->input->get("guid")}");
    	}
	}
	
	function yahoo_20121018()
	{
    	$this->_init_layout();
    	$this->g_layout->view();
	}
	
	//卡哇粉絲按讚活動
	function fb_20131226()
	{	
		$this->load->library("channel_api/fb_api");
		$this->load->library("code", array("event"=>$this->router->method));
				
		$code = ''; $msg = '';
		
		$uid = $this->fb_api->uid;
		$page_id = '125949627496483';
		
		try {
			if ($this->fb_api->chk_fb_fan($uid, $page_id)) {
    			fb('1');
    			if ($this->code->chk_matched($uid)) {
    				$code = $this->code->get_user_code($uid);
    			}
    			else {
    				fb('0');
    				if ($this->code->chk_code_enough()) {
    					fb('1');
    					$this->code->match_code($uid);
    					$code = $this->code->get_user_code($uid);
    				}
    				else {
    					fb('0');
    					$msg = '序號已發放完畢';
    				}
    			}    			
    		}
    		else {
    			fb('0');
    			$msg = '請對粉絲團點讚才可以領取喔';
    		}
		} catch (FacebookApiException $e) {
			$this->fb_api->uid = null;
		}
		
    	if (empty($this->fb_api->uid)) 
    	{    		
    		//$redirect_uri = urlencode("https://www.facebook.com/long_e.inc/app_181504455307555");
    		$loginUrl = $this->fb_api->login("", 
    			array(
    				"scope" => "user_likes",
    				"redirect_uri"=>site_url("event/fb_20131226_redirect")));
    		exit();
    	}
    	fb($code);
    	
    	$this->_init_layout();
    	$this->g_layout
    		->set("msg", $msg)
    		->set("code", $code)
    		->view();
	}
	
	function fb_20131226_redirect()
	{
		$this->load->library("channel_api/fb_api");
		header('location: https://www.facebook.com/long_e.inc/app_570758059676693?ref=ts');
	}
		
	//絕代粉絲按讚活動
	function fb_20130509()
	{	
		$this->load->library("channel_api/fb_api");
		$this->load->library("code", array("event"=>$this->router->method));
				
		$code = ''; $msg = '';
		
		$uid = $this->fb_api->uid;
		$page_id = '125949627496483';
		
		try {
			if ($this->fb_api->chk_fb_fan($uid, $page_id)) {
    			fb('1');
    			if ($this->code->chk_matched($uid)) {
    				$code = $this->code->get_user_code($uid);
    			}
    			else {
    				fb('0');
    				if ($this->code->chk_code_enough()) {
    					fb('1');
    					$this->code->match_code($uid);
    					$code = $this->code->get_user_code($uid);
    				}
    				else {
    					fb('0');
    					$msg = '序號已發放完畢';
    				}
    			}    			
    		}
    		else {
    			fb('0');
    			$msg = '請對粉絲團點讚才可以領取喔';
    		}
		} catch (FacebookApiException $e) {
			$this->fb_api->uid = null;
		}
		
    	if (empty($this->fb_api->uid)) 
    	{    		
    		//$redirect_uri = urlencode("https://www.facebook.com/long_e.inc/app_181504455307555");
    		$loginUrl = $this->fb_api->login("", 
    			array(
    				"scope" => "user_likes",
    				"redirect_uri"=>site_url("event/fb_20130509_redirect")));
    		exit();
    	}
    	fb($code);
    	
    	$this->_init_layout();
    	$this->g_layout
    		->set("msg", $msg)
    		->set("code", $code)
    		->view();
	}
	
	function fb_20130509_redirect()
	{
		$this->load->library("channel_api/fb_api");
		header('location: https://www.facebook.com/long_e.inc/app_335879803204464?ref=ts');
	}
	
	//霸王粉絲按讚活動
	function fb_20130415()
	{	
		$this->load->library("channel_api/fb_api");
		$this->load->library("code", array("event"=>$this->router->method));
				
		$code = ''; $msg = '';
		
		$uid = $this->fb_api->uid;
		$page_id = '589459527731871';
		
		try {
			if ($this->fb_api->chk_fb_fan($uid, $page_id)) {
    			fb('1');
    			if ($this->code->chk_matched($uid)) {
    				$code = $this->code->get_user_code($uid);
    			}
    			else {
    				fb('0');
    				if ($this->code->chk_code_enough()) {
    					fb('1');
    					$this->code->match_code($uid);
    					$code = $this->code->get_user_code($uid);
    				}
    				else {
    					fb('0');
    					$msg = '序號已發放完畢';
    				}
    			}    			
    		}
    		else {
    			fb('0');
    			$msg = '請對粉絲團點讚才可以領取喔';
    		}
		} catch (FacebookApiException $e) {
			$this->fb_api->uid = null;
		}
		
    	if (empty($this->fb_api->uid)) 
    	{    		
    		//$redirect_uri = urlencode("https://www.facebook.com/long_e.inc/app_181504455307555");
    		$loginUrl = $this->fb_api->login("", 
    			array(
    				"scope" => "user_likes",
    				"redirect_uri"=>site_url("event/fb_20130415_redirect")));
    		exit();
    	}
    	fb($code);
    	
    	$this->_init_layout();
    	$this->g_layout
    		->set("msg", $msg)
    		->set("code", $code)
    		->view();
	}
	
	function fb_20130415_redirect()
	{
		$this->load->library("channel_api/fb_api");
		header('location: https://www.facebook.com/pages/%E9%9C%B8%E7%8E%8B%E7%B9%81%E9%AB%94%E4%B8%AD%E6%96%87%E7%B2%89%E7%B5%B2%E5%9C%98/589459527731871?id=589459527731871&sk=app_133122800207475');
	}
	
	//炎黃粉絲按讚活動
	function fb_20130408()
	{	
		$this->load->library("channel_api/fb_api");
		$this->load->library("code", array("event"=>$this->router->method));
				
		$code = ''; $msg = '';
		
		$uid = $this->fb_api->uid;
		$page_id = '584707664886673';
		
		try {
			if ($this->fb_api->chk_fb_fan($uid, $page_id)) {
    			fb('1');
    			if ($this->code->chk_matched($uid)) {
    				$code = $this->code->get_user_code($uid);
    			}
    			else {
    				fb('0');
    				if ($this->code->chk_code_enough()) {
    					fb('1');
    					$this->code->match_code($uid);
    					$code = $this->code->get_user_code($uid);
    				}
    				else {
    					fb('0');
    					$msg = '序號已發放完畢';
    				}
    			}    			
    		}
    		else {
    			fb('0');
    			$msg = '請對粉絲團點讚才可以領取喔';
    		}
		} catch (FacebookApiException $e) {
			$this->fb_api->uid = null;
		}
		
    	if (empty($this->fb_api->uid)) 
    	{    		
    		//$redirect_uri = urlencode("https://www.facebook.com/long_e.inc/app_181504455307555");
    		$loginUrl = $this->fb_api->login("", 
    			array(
    				"scope" => "user_likes",
    				"redirect_uri"=>site_url("event/fb_20130408_redirect")));
    		exit();
    	}
    	fb($code);
    	
    	$this->_init_layout();
    	$this->g_layout
    		->set("msg", $msg)
    		->set("code", $code)
    		->view();
	}
	
	function fb_20130408_redirect()
	{
		$this->load->library("channel_api/fb_api");
		header('location: https://www.facebook.com/pages/%E9%85%B7%E6%A0%97%E9%81%8A%E6%88%B2_%E7%82%8E%E9%BB%83%E4%B9%8B%E9%AD%82%E7%B2%89%E7%B5%B2%E5%9C%98/584707664886673?v=app_143137942535705');
	}
	
	//仙若粉絲按讚活動
	function fb_20130326()
	{	
		$this->load->library("channel_api/fb_api");
		$this->load->library("code", array("event"=>$this->router->method));
				
		$code = ''; $msg = '';
		
		$uid = $this->fb_api->uid;
		$page_id = '545455272161124';
		
		try {
			if ($this->fb_api->chk_fb_fan($uid, $page_id)) {
    			fb('1');
    			if ($this->code->chk_matched($uid)) {
    				$code = $this->code->get_user_code($uid);
    			}
    			else {
    				fb('0');
    				if ($this->code->chk_code_enough()) {
    					fb('1');
    					$this->code->match_code($uid);
    					$code = $this->code->get_user_code($uid);
    				}
    				else {
    					fb('0');
    					$msg = '序號已發放完畢';
    				}
    			}    			
    		}
    		else {
    			fb('0');
    			$msg = '請對粉絲團點讚才可以領取喔';
    		}
		} catch (FacebookApiException $e) {
			$this->fb_api->uid = null;
		}
		
    	if (empty($this->fb_api->uid)) 
    	{    		
    		//$redirect_uri = urlencode("https://www.facebook.com/long_e.inc/app_181504455307555");
    		$loginUrl = $this->fb_api->login("", 
    			array(
    				"scope" => "user_likes",
    				"redirect_uri"=>site_url("event/fb_20130326_redirect")));
    		exit();
    	}
    	fb($code);
    	
    	$this->_init_layout();
    	$this->g_layout
    		->set("msg", $msg)
    		->set("code", $code)
    		->view();
	}
	
	function fb_20130326_redirect()
	{
		$this->load->library("channel_api/fb_api");
		header('location: https://www.facebook.com/long_e.xl/app_500122113385408');
	}
	
	//小李2粉絲按讚活動
	function fb_20130104()
	{	
		$this->load->library("channel_api/fb_api");
		$this->load->library("code", array("event"=>$this->router->method));
				
		$code = ''; $msg = '';
		
		$uid = $this->fb_api->uid;
		$page_id = '341088939311782';
		
		try {
			if ($this->fb_api->chk_fb_fan($uid, $page_id)) {
    			fb('1');
    			if ($this->code->chk_matched($uid)) {
    				$code = $this->code->get_user_code($uid);
    			}
    			else {
    				fb('0');
    				if ($this->code->chk_code_enough()) {
    					fb('1');
    					$this->code->match_code($uid);
    					$code = $this->code->get_user_code($uid);
    				}
    				else {
    					fb('0');
    					$msg = '序號已發放完畢';
    				}
    			}    			
    		}
    		else {
    			fb('0');
    			$msg = '請對粉絲團點讚才可以領取喔';
    		}
		} catch (FacebookApiException $e) {
			$this->fb_api->uid = null;
		}
		
    	if (empty($this->fb_api->uid)) 
    	{    		
    		//$redirect_uri = urlencode("https://www.facebook.com/long_e.inc/app_181504455307555");
    		$loginUrl = $this->fb_api->login("", 
    			array(
    				"scope" => "user_likes",
    				"redirect_uri"=>site_url("event/fb_20130104_redirect")));
    		exit();
    	}
    	fb($code);
    	
    	$this->_init_layout();
    	$this->g_layout
    		->set("msg", $msg)
    		->set("code", $code)
    		->view();
	}
	
	function fb_20130104_redirect()
	{
		$this->load->library("channel_api/fb_api");
		header('location: https://www.facebook.com/long_e.sl2/app_317296311708873');
	}
		
	//三國2粉絲按讚活動
	function fb_20120927()
	{	
		$this->load->library("channel_api/fb_api");
		$this->load->library("code", array("event"=>$this->router->method));
				
		$code = ''; $msg = '';
    	if ($uid = $this->fb_api->uid) 
    	{
    		$page_id = '125949627496483';
    		//$page_id = '287486362762';

    		if ($this->fb_api->chk_fb_fan($uid, $page_id)) {
    			fb('1');
    			if ($this->code->chk_matched($uid)) {
    				$code = $this->code->get_user_code($uid);
    			}
    			else {
    				fb('0');
    				if ($this->code->chk_code_enough()) {
    					fb('1');
    					$this->code->match_code($uid);
    					$code = $this->code->get_user_code($uid);
    				}
    				else {
    					fb('0');
    					$msg = '序號已發放完畢';
    				}
    			}    			
    		}
    		else {
    			fb('0');
    			$msg = '請對粉絲團點讚才可以領取喔';
    		}
    		/*
			echo '<script type="text/javascript">
    			if (window.top.location != "https://www.facebook.com/long_e.inc/app_181504455307555")
    				window.top.location = "https://www.facebook.com/long_e.inc/app_181504455307555";</script>';
    				*/
    	}
    	else { //尚未登入fb 
    		
    		//$redirect_uri = urlencode("https://www.facebook.com/long_e.inc/app_181504455307555");
    		$loginUrl = $this->fb_api->login("", 
    			array(
    				"scope" => "user_likes",
    				"redirect_uri"=>site_url("event/fb_20120927_redirect")));
    		exit();
    	}
    	fb($code);
    	
    	$this->_init_layout();
    	$this->g_layout
    		->set("msg", $msg)
    		->set("code", $code)
    		->view();
	}
	
	function fb_20120927_redirect()
	{
		$this->load->library("channel_api/fb_api");
		header('location: https://www.facebook.com/long_e.inc/app_181504455307555');
	}
	
	function sg_20121016()
	{	
		$this->load->library("code", array("event"=>$this->router->method));
				
		$code = ''; $status = '1';
    	if ($this->g_user->loginCheck()) 
    	{
    		fb('1');
    		
    		$uid = $this->g_user->uid;
    		$create_time = $this->db->select("create_time")->from("users")->where("uid", $uid)->get()->row()->create_time;

    		if (strtotime($create_time) <= strtotime('2012-09-30 23:59:59')) {
    			fb('1');
    			if ($this->code->chk_matched($uid)) {
    				$code = $this->code->get_user_code($uid);
    			}
    			else {
    				fb('0');
    				if ($this->code->chk_code_enough()) {
    					fb('1');
    					$this->code->match_code($uid);
    					$code = $this->code->get_user_code($uid);
    				}
    				else {
    					fb('0');
    					$status = 3;
    				}
    			}    			
    		}
    		else {
    			fb('0');
    			$status = 2;
    		}
    	}
    	else { //尚未登入long_e
    		$status = 0;
    	}
    	fb($code);
    	
    	$this->_init_layout();
    	$this->g_layout
    		->set("status", $status)
    		->set("code", $code)
    		->view();
	}	
	
	//萌寵粉絲按讚活動
	function fb_20121017()
	{	
		$this->load->library("channel_api/fb_api");
		$this->load->library("code", array("event"=>$this->router->method));
				
		$code = ''; $msg = '';
    	if ($uid = $this->fb_api->uid) 
    	{
    		fb('1');
    		$page_id = '235204416608569';
    		//$page_id = '287486362762';

    		if ($this->fb_api->chk_fb_fan($uid, $page_id)) {
    			fb('1');
    			if ($this->code->chk_matched($uid)) {
    				$code = $this->code->get_user_code($uid);
    			}
    			else {
    				fb('0');
    				if ($this->code->chk_code_enough()) {
    					fb('1');
    					$this->code->match_code($uid);
    					$code = $this->code->get_user_code($uid);
    				}
    				else {
    					fb('0');
    					$msg = '序號已發放完畢';
    				}
    			}    			
    		}
    		else {
    			fb('0');
    			$msg = '請對粉絲團點讚才可以領取喔';
    		}
    	}
    	else { //尚未登入fb 
    		
    		$loginUrl = $this->fb_api->login("", 
    			array(
    					"scope" => "user_likes",
    					"redirect_uri"=>site_url("event/fb_20121017_redirect")));
    		exit();
    	}
    	fb($code);
    	
    	$this->_init_layout();
    	$this->g_layout
    		->set("msg", $msg)
    		->set("code", $code)
    		->view();
	}	
	
	function fb_20121017_redirect()
	{
		$this->load->library("channel_api/fb_api");		
		header('location: https://www.facebook.com/pages/%E8%90%8C%E5%AF%B5%E9%81%8E%E6%B1%9F/235204416608569?sk=app_353790294714404');
	}
	
	//仙府粉絲按讚活動
	function fb_20121113()
	{	
		$this->load->library("channel_api/fb_api");
		$this->load->library("code", array("event"=>$this->router->method));
				
		$code = ''; $msg = '';
    	if ($uid = $this->fb_api->uid) 
    	{
    		fb('1');
    		$page_id = '353038094780310';

    		if ($this->fb_api->chk_fb_fan($uid, $page_id)) {
    			fb('1');
    			if ($this->code->chk_matched($uid)) {
    				$code = $this->code->get_user_code($uid);
    			}
    			else {
    				fb('0');
    				if ($this->code->chk_code_enough()) {
    					fb('1');
    					$this->code->match_code($uid);
    					$code = $this->code->get_user_code($uid);
    				}
    				else {
    					fb('0');
    					$msg = '序號已發放完畢';
    				}
    			}    			
    		}
    		else {
    			fb('0');
    			$msg = '請對粉絲團點讚才可以領取喔';
    		}
    	}
    	else { //尚未登入fb 
    		
    		$loginUrl = $this->fb_api->login("", 
    			array(
    					"scope" => "user_likes",
    					"redirect_uri"=>site_url("event/fb_20121113_redirect")));
    		exit();
    	}
    	fb($code);
    	
    	$this->_init_layout();
    	$this->g_layout
    		->set("msg", $msg)
    		->set("code", $code)
    		->view();
	}	
	
	function fb_20121113_redirect()
	{
		$this->load->library("channel_api/fb_api");		
		header('location: https://www.facebook.com/pages/%E4%BB%99%E5%BA%9C%E4%B9%8B%E7%B7%A3xflong_ecomtw/353038094780310?sk=app_469441026431901');
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */