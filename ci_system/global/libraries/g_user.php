<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 會員認証用類別
 *
 * @author      eddiehan@molibee.com
 * @package     authUser
*/

class G_User {

	var $CI;
	var $uid;
	var $euid;
	var $account;
	var $password;
	var $mobile;
	var $realName;
	var $remoteAddr;
	var $userAgent;
	var $long_e_uid;
	var $token;
	
	var $error_mssage='錯誤';

	function __construct() 
	{
		$this->CI =& get_instance();
			
		if ( ! empty($_SESSION['long_eDNA'])) {
			$this->uid = $_SESSION['user_id'];
			$this->euid = isset($_SESSION['euid']) ? $_SESSION['euid'] : '';
			$this->account = $_SESSION['account'];
			$this->realName = urldecode($_SESSION['name']);
			$this->long_eDNA = $_SESSION['long_eDNA'];
			$this->remoteAddr = $_SERVER['REMOTE_ADDR'];
			$this->userAgent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
			$this->long_e_uid = isset($_SESSION['long_e_uid']) ? $_SESSION['long_e_uid'] : '';
			$this->mobile = $_SESSION['mobile'];
			$this->token = $_SESSION['token'];
		}
	}

	/**
	 *  登出
	 */
	function logout() 
	{				
		$_SESSION['user_id'] = '';
		$_SESSION['euid'] = '';		
		$_SESSION['account'] = '';		
		$_SESSION['name'] = '';
		$_SESSION['long_eDNA'] = '';
		$_SESSION['long_e_uid'] = '';
		$_SESSION['mobile'] = '';
		$_SESSION['token'] = '';
		$_SERVER['REMOTE_ADDR'] = '';
		
		unset($_SESSION['user_id']);
		unset($_SESSION['euid']);
		unset($_SESSION['account']);
		unset($_SESSION['name']);
		unset($_SESSION['long_eDNA']);
		unset($_SESSION['long_e_uid']);
		unset($_SESSION['mobile']);
		unset($_SESSION['token']);
		
		//setcookie('PHPSESSID', '', time()-3600);
		//session_destroy();
		return true;
	}

	/**
	 *  登入狀態檢查式
	 *  @return bool
	 */
	function loginCheck( $redirect = false, $returnUrl = "" ) 
	{
		if(empty($this->long_eDNA) || empty($this->uid) || empty($this->account)) {
			$this->logout();
			if($redirect == true) {
				header("Content-type: text/html; charset=utf-8");
				$returnUrl = empty($returnUrl)?"http://{$_SERVER['SERVER_NAME']}{$_SERVER['REQUEST_URI']}":$returnUrl;
				$returnUrl = empty($returnUrl)?'http://www.longeplay.com.tw/member/login.php':$returnUrl;
				echo '<script type="text/javascript">
							alert("請先進行登入");
							location.replace("http://'.$_SERVER['SERVER_NAME'].'/member/login.php?returnUrl='.$returnUrl.'");
					</script>';
				//header('LOCATION:http://www.longeplay.com.tw/member/login.php?returnUrl='.$returnUrl);
				exit();
			}
			//echo '<script type="text/javascript">alert("請先進行登入");';
			//echo 'location.replace("http://www.longeplay.com.tw/member/login.php");</script>';
			//header('LOCATION:http://www.longeplay.com.tw/member/login.php');
			return false;
		}else {
			return true;
		}
	}

	function check_login($site='', $redirect_url='') 
	{
		if (empty($this->uid)) {
			if ($redirect_url) {
				if ($redirect_url === true) {
					$redirect_url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
				}
				header("Content-type: text/html; charset=utf-8");
				echo "<script type='text/javascript'>
						alert('請先進行登入');
						top.location.href='http://{$_SERVER['HTTP_HOST']}/member/login?site={$site}&redirect_url=".urlencode($redirect_url)."';
				</script>";
				exit();
			}
			return false;
		}
		else 
		{			
			return true;
		}
	}

	// 檢查玩家是否已登入, 若未登入則導向登入畫面
	function require_login($site='', $redirect_url='')
	{
		if (empty($this->uid))
		{
			if (empty($redirect_url))
			{
				$redirect_url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
			}
			header("Content-type: text/html; charset=utf-8");
			echo "<script type='text/javascript'>
					alert('請先進行登入');
					top.location.href='http://{$_SERVER['HTTP_HOST']}/member/login?site={$site}&redirect_url=".urlencode($redirect_url)."';
					</script>";
			exit();
		}
		else
		{
			return true;
		}
	}

	// 檢查玩家是否已登入
	function is_login()
	{
		return !empty($this->uid);
	}

	// 進行登入，若帳號不存在，則建立
	function login($account, $password='', $email='', $name='', $site='')
	{
		if ($site) $_SESSION['site'] = $site;
		
		if ($this->check_account_exist($account)) {
			//帳號存在，進行登入
			return $this->verify_account($account, $password);		
		}
		else {
			//帳號不存在，創立帳號
			if ($this->create_account($account, $password, $email, $name, $site)) {
				//創立成功，進行登入
				return $this->verify_account($account, $password);
			}
			else{
				//創立失敗
				return false;
			}
		}	
	}
	
	function verify_account($account, $password='') 
	{		
		$this->account = strtolower(trim($account));
		
		if ($this->check_extra_account($this->account) == FALSE) {
			$this->password = md5(trim($password));
			$query = $this->CI->db->from("users")
						->where("account", $this->account)
						->where("password", $this->password)
						->get();
						
			if ($query->num_rows() == 0 ) {
				unset($query);
			    $query = $this->CI->db->from("users")
						    ->where("mobile", $this->account)
						    ->where("password", $this->password)
						    ->get();
			}
		}
		else  {
			$query = $this->CI->db->from("users")
						->where("account", $this->account)
						->get();
		}
		
		if ($query->num_rows() > 0 ) {
			$row = $query->row();	
			
			if ( ! empty($row->bind_uid)) {//若登入綁定用途帳號，則讀取主帳號
				$long_e_uid = $row->uid; 				
				$query = $this->CI->db->from("users")->where("uid", $row->bind_uid)->get();
				if ($query->num_rows() > 0 ) $row = $query->row(); 
				else return $this->_return_error("綁定帳號不存在");
			}
			else {
				$long_e_uid = '';
			}
						
			if ($row->is_banned != 0 && !IN_OFFICE) return $this->_return_error("停權");			
					
				
			$this->set_user($row->uid, $row->account, $row->name, $long_e_uid, $row->mobile);
			return true;
		} 
		else {
			return $this->_return_error("帳號不存在或密碼錯誤");	
		}			
	}
	
	function set_user($uid, $account, $name, $long_e_uid='', $mobile='') 
	{		
		//登入log
		/*
		$this->CI->db
			->where("uid", $row->uid)
			->where("is_recent", "1")
			->update("log_logins", array("is_recent" => "0"));
			*/
		
		/*if ($this->CI->input->get("ad")) {
			$ad = $this->CI->input->get("ad");
		} else $ad = empty($_SESSION['ad']) ? '' : $_SESSION['ad'];
		*/
		
		$site = $this->CI->input->get('site') ? $this->CI->input->get('site') : (empty($_SESSION['site']) ? 'long_e' : $_SESSION['site']);	
		$ad = $this->CI->input->get('ad') ? $this->CI->input->get('ad') : (empty($_SESSION['ad']) ? '' : $_SESSION['ad']);
				
		$data = array(
			'uid' => $uid,
			'account' => $account,
			'ip' => $_SERVER["REMOTE_ADDR"],
			'create_time' => now(),
			'is_recent' => '1',
			'ad' => $ad,
			'site' => $site,
		);
		if ( ! empty($_SESSION['log_imei'])) $data['imei'] = $_SESSION['log_imei'];
		if ( ! empty($_SESSION['log_android_id'])) $data['android_id'] = $_SESSION['log_android_id'];
		
		$this->CI->db->insert("log_logins", $data);	
		
		$this->uid = $uid;
		$this->euid = $this->encode($uid);
		$this->account = $account;
		$this->realName = urlencode($name);
		$this->long_eDNA = md5(trim($this->account.$this->remoteAddr.$this->userAgent));
		$this->long_e_uid = $long_e_uid;
		$this->mobile = $mobile;		
		$this->token = $this->generate_token();		
		
		$_SESSION['user_id'] = $this->uid;
		$_SESSION['euid'] = $this->euid;
		$_SESSION['account'] = $this->account;
		$_SESSION['name'] = $this->realName;
		$_SESSION['long_eDNA'] = $this->long_eDNA;
		$_SESSION['long_e_uid'] = $this->long_e_uid;
		$_SESSION['mobile'] = $this->mobile;
		$_SESSION['token'] = $this->token;
	}
	
	function get_user_data($uid='') 
	{
		if (empty($uid)) $uid = $this->uid;
		return $this->CI->db->from("users")->where("uid", $uid)->get()->row();
	}

	function create_account($account, $password, $email='', $name='', $site='', $bind_uid='') 
	{			
		$account = trim($account);
		
		if (!preg_match("/^[a-z0-9@_]+$/", $account))
		//if (!ereg("^[a-z0-9@_]+$", $account))
		{
			return $this->_return_error('帳號不得包含特殊字元及大寫字母.');
		}		
		
		if (strlen($account) < 4) return $this->_return_error('帳號不得少於四碼');
		if (strlen($password) < 4) return $this->_return_error('密碼不得少於四碼'); 

		if ($this->check_account_exist($account)) {
			return $this->_return_error('帳號已經存在');
		}
		else {
			$data = array(
				'account'	=> strtolower($account),
				'password'	=> md5(trim($password)),
				'email'	=> strtolower(trim($email)),
				'name' => trim($name),
				'create_time' => date("YmdHis"),
				'is_approved'	=> 0
			);
			if ($bind_uid) $data['bind_uid'] = $bind_uid;
			
			$this->CI->db->insert("users", $data);
			$uid = $this->CI->db->insert_id();
			if ( ! $uid) {
				return $this->_return_error('資料庫新增錯誤');
			} else {
			    $user_info_data = array(
				    'uid'	=> $uid
			    );
				$this->CI->db->insert("user_info", $user_info_data);
			}
			return true;
		}
	}
	
	function set_mobile($account, $password, $mobile) {
		
		return $this->CI->db
			->where("account", $account)
			->update("users", array("password" => md5($password), "mobile" => $mobile));
	}
	
	function check_account_exist($account) 
	{		
		$account = strtolower(trim($account));
		$cnt = $this->CI->db->from("users")->where("account", $account)->count_all_results();
		return $cnt > 0 ? true : false;
	}
	
	function check_extra_account($account='')
	{		
		if (empty($account)) $account = $this->account;
		if (strstr($account, '@') == FALSE) {
			return false;
		} else return true;
	}
	
	//切換用戶(用於模擬角色)
	function switch_account($account)
	{
		$query = $this->CI->db->from("users")->where("account", $account)->get();
		if ($query->num_rows() > 0) {
			$row = $query->row();
			$this->set_user($row->uid, $row->account, $row->name);
			return true;
		}
		else return false;
	}
	
	//切換用戶(用於模擬角色)
	function switch_uid($uid)
	{
		$query = $this->CI->db->from("users")->where("uid", $uid)->get();
		if ($query->num_rows() > 0) {
			$row = $query->row();
			if ( ! empty($row->bind_uid)) { //若登入綁定用途帳號，則讀取主帳號 				
				$query = $this->CI->db->from("users")->where("uid", $row->bind_uid)->get();
				if ($query->num_rows() > 0 ) $row = $query->row(); 
				else return $this->_return_error("綁定帳號不存在");
			}
			$this->set_user($row->uid, $row->account, $row->name);
			return true;
		}
		else $this->_return_error("帳號不存在");
	}
	
	function check_account_channel($type='')
	{		
		$site = $this->CI->input->get("site");
		$account = $this->CI->input->get("account");
		$uid = $this->CI->input->get("uid");
		$euid = $this->CI->input->get("euid");
		
		if (empty($site)) $site = 'long_e';
		
		$back_url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		$long_e_login_page = site_url("member/login?site={$site}&redirect_url=".urlencode($back_url));

		if ($this->is_login())
		{ //已登入帶登入資訊
			$account = $this->account;	
		}
		else {	//使用get資訊
			if ($euid) $uid = $this->decode($euid);		
			if ($uid) $account = $this->CI->db->from("users")->where("uid", $uid)->get()->row()->account;
		}
		
		if ($account) 
		{
			$spt = explode("@", $account);
			$channel = empty($spt[1]) ? '' : $spt[1];
			
			if ($type == 'trade') 
			{
				switch($channel) 
				{
					case 'omg':
						header("location: /transfer/omg/"); exit();
						break;
						
					case 'artsy':
						header("location: http://www.artsy.com.tw/product/index"); exit();
						break;

						/*
					case 'rc2':
						header("location: http://game.raidtalk.com.tw/pay/game_detail"); exit();
						break;
						*/
						
					case 'dtalent':
						if ($site == 'long_e') $site='xj';
						header("location: http://www.player.com.tw/bank/bk_portal.php?product={$site}"); exit();
						break;
					
					case 'beanfun':
						$this->CI->load->config("api");
						$channel_api = $this->CI->config->item("channel_api");				
						if ($site && array_key_exists($site, $channel_api['beanfun']['sites'])) {
							$ServiceCode = $channel_api['beanfun']['sites'][$site]['ServiceCode'];
							$redirect_url = "http://tw.beanfun.com/TW_ThirdPartyWeb/gamelaunch.aspx?ServiceCode={$ServiceCode}&ServiceRegion=B2&ServiceMode=D";
						}
						else {
							$redirect_url = "http://tw.beanfun.com/playweb/index.aspx";
						}
			  			header("location: {$redirect_url}"); exit();
			  			break;							
						
					case 'kimi':
						$this->CI->load->config("api");
						$partner_api = $this->CI->config->item("partner_api");	
						if ($site && array_key_exists($site, $partner_api['kimi']['sites'])) {
							$redirect_url = $partner_api['kimi']['transfer_url'].$partner_api['kimi']['sites'][$site]['pid'];
							header("location: {$redirect_url}"); exit();
						}						
						break;
						
					case '179game':
						header("location: http://www.gamexdd.com/Point"); exit();
						break;						
						
					case 'smmo':
						header("location: http://www.smmogames.com/TopUpListing.aspx"); exit();
						break;
						
					case 'muxplay':
						header("location: http://www.muxplay.com/pay/43"); exit();
						break;

					case 'egame101':
						header("location: http://www.egame101.com/index.php/member/index"); exit();
						break;		

					case '58play':
						header("location: http://www.58play.com.tw/payment2.php?gamesn=52"); exit();
						break;	
						
					case 'nicegame':
						header("location: http://www.nicegame.com.tw/payment/"); exit();
						break;	
						
					case 'skyler':
						header("location: http://www.skyler.asia/changepoint.php"); exit();
						break;	
				}		
			}
			else if ($type == 'service') 
			{
				switch($channel) 
				{
					case 'beanfun':
						header("location: http://tw.beanfun.com/customerservice/"); exit();
						break;
						
					case '179game':
						header("location: http://www.gamexdd.com/public/news/9/50.htm"); exit();
						break;			

					case 'muxplay':
						header("location: http://www.muxplay.com/service"); exit();
						break;
					
					case 'egame101':
						header("location: http://www.egame101.com/"); exit();
						break;
						
					case 'skyler':
						header("location: http://www.skyler.asia/customerservice.php"); exit();
						break;
				}						
			}
			else 
			{
				switch($channel) 
				{
					case 'beanfun':
						header("location: http://tw.beanfun.com/playweb/index.aspx"); exit();
						break;
						
					case 'muxplay':
						header("location: http://www.muxplay.com/myz"); exit();
						break;
						
					case 'egame101':
						header("location: http://www.egame101.com/"); exit();
						break;
					
					case 'skyler':
						header("location: http://www.skyler.asia/"); exit();
						break;
				}						
			}
		}
		
		if ($this->is_login()) {
			//if (($uid && $uid <> $this->uid) || ($account && $account <> $this->account)) {
			//	header("location: {$long_e_login_page}"); 
			//	exit();
			//}
			return true;
		}
		else {
						
			if (empty($account)) {
				header("location: {$long_e_login_page}"); 
				exit();
			}
			else {
				
				//暫時全導登入頁
				header("location: {$long_e_login_page}"); 
				exit();
				//end
					
				if ($this->check_extra_account($account)) { //外站帳號
					$spt = explode("@", $account);					
					$extra_login_page = site_url("gate/login/{$site}?channel={$spt[1]}&redirect_url=".urlencode($back_url));
					header("location: {$extra_login_page}");
					exit();
				}
				else {
					header("location: {$long_e_login_page}");
					exit();
				}
			}
			return true;
		}		
	}
	
	function loadDB()
	{		
		return $this->CI->load->database("long_e", true);
	}
	
	function encode($id) 
	{
		$digit = $id%10; //個位數
		$factor = $this->_mapping_num($digit)%7+1;
		$m_factor = $this->_mapping_num($factor);	
		$code = ($id * $factor).$m_factor;
		$m_code = $this->_mapping_num($code);
		return intval($m_code);
	}
	
	function decode($m_code) 
	{
		$code = $this->_mapping_num($m_code, true);		
		$m_factor = substr($code, -1, 1);
		$factor = $this->_mapping_num($m_factor, true);
		if ($factor == 0) return 0;
		
		$id = substr($code, 0, strlen($code)-1) / $factor;
		if (is_int($id)) return $id;
		else return 0;
	}
	
	//數字轉換
	function _mapping_num($num, $reverse=false) {
		$num = intval($num);
		$mapping_table = array("0", "5", "4", "1", "7", "9", "2", "3", "6", "8");
		if ($reverse) {
			$tmp = array();
			foreach($mapping_table as $k => $v) $tmp[$v] = $k;
			$mapping_table = $tmp;
		}		
		$result = '';
		for ($i=0; $i<=strlen($num)-1; $i++) {
			$result .= $mapping_table[substr($num, $i, 1)];
		}
		return $result;
	}	
	
	function display_account($show_notice=true)
	{
		$this->CI->load->config('../../../default/application/config/api');
		$channel_api = $this->CI->config->item("channel_api");
		$display = array();
		if (is_array($channel_api)) {
			foreach($channel_api as $channel => $arr) {
				$display[$channel] = $arr["name"];
			}		
		}
		$re = $this->account;
		if (strpos($re, "@imei") && $this->mobile){
			$re = $this->mobile;
		}
		elseif ($this->check_extra_account($this->account)) {
			$tmp = explode("@", $this->account);
			//if ($tmp[1] == 'igame') $tmp[1] = 'igamer'; //前人設定錯導致
			if (array_key_exists($tmp[1], $display)) {
				$re = $display[$tmp[1]]." 帳號";
			}		
		}		
		return $re.( $show_notice ? " ".$this->display_notice() : "");
	}
	
	function get_channel()
	{
		if ($this->is_channel_account()) {
			$tmp = explode("@", $this->account);
			return $tmp[1];		
		}	
		return false;
	}
	
	function is_channel_account()
	{		
		if (empty($this->account)) return false;
		
		if (strstr($this->account, '@') == FALSE) {
			return false;
		} else return true;
	}
	
	function display_notice()
	{
		$cnt = $this->CI->db->from("notice_targets")->where("uid", $this->uid)->where("is_read", 0)->count_all_results();
		$re = "<a href='".base_url()."/notice/get_list' style='font-size:11px; line-height:20px; color:#ff0;' target='_blank'>";
		if ($cnt > 0) 
			$re .= "<img src='".base_url()."/p/img/notice_on.gif' title='通知({$cnt})' onclick='$(this).attr(\"src\", \"".base_url()."/p/img/notice_off.gif\")'>";
		else $re .= "<img src='".base_url()."/p/img/notice_off.gif' title='通知'>";
		$re .= "</a>";
		return $re;
	}
	
	function _return_error($msg) {
		$this->error_message = $msg;
		return false;
	}
	
	function generate_token($len = 32)
	{

		// Array of potential characters, shuffled.
		$chars = array(
			'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 
			'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z',
			'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 
			'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z',
			'0', '1', '2', '3', '4', '5', '6', '7', '8', '9'
		);
		shuffle($chars);

		$num_chars = count($chars) - 1;
		$token = '';

		// Create random token at the specified length.
		for ($i = 0; $i < $len; $i++)
		{
			$token .= $chars[mt_rand(0, $num_chars)];
		}

		return $token;

	}
}
