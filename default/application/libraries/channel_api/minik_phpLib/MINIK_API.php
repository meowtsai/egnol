<?php 
class MINIK_API {
	private $secret;
	private $API_Key;
	private $use_curl_if_available;
	private static $hash;
	private $rawData = null;
	private $server_address;
	private $requestUrl;
	private static $session;
	private static $mksecret;
	private static $timeStamp;
					
	public function __construct($secret, $API_Key) {
		$config_ip = gethostbyname($_SERVER['SERVER_NAME']);
		$this->secret = $secret;
		$this->API_Key = $API_Key;
		$this->use_curl_if_available = true;
		$this->server_address = 'http://www.minik.com.tw/OpenPHPAPI/appminik.php/OpenAPI/';
	}
	
	public function Init() {
		header('P3P:CP="IDC DSP COR ADM DEVi TAIi PSA PSD IVAi IVDi CONi HIS OUR IND CNT"');

		// 清除cookie
		if ( ! empty($_COOKIE['minik_timeStamp'])) {
			$this->clearCookie(md5($this->API_Key.$_COOKIE['minik_timeStamp']).'_mksession');
			$this->clearCookie(md5($this->API_Key.$_COOKIE['minik_timeStamp']).'_mksecret');
			$this->clearCookie(md5($this->API_Key.$_COOKIE['minik_timeStamp']).'_mkauth');
		}
		$this->clearCookie('minik_timeStamp');
	
		if($this->getSession()) {
			// 取得timeStamp
			self::$timeStamp = time();
			
			// 取得Minik Session值
			$sessionName = md5($this->API_Key.self::$timeStamp).'_mksession';
			self::$session = $this->getSession();
			
			// 取得minik_secret值
			$secretName = md5($this->API_Key.self::$timeStamp).'_mksecret';
			self::$mksecret = $_GET['minik_secret'];
			
			// 計算hash值
			$hashName = md5($this->API_Key.self::$timeStamp).'_mkauth';
			self::$hash = $this->getHash(self::$session);
			
			// 寫入客戶端cookie
			$this->setCookie($sessionName, self::$session);
			$this->setCookie($secretName, self::$mksecret);
			$this->setCookie($hashName, self::$hash);
			$this->setCookie('minik_timeStamp', self::$timeStamp);
		}
	}
	
	public function GetUserInfo() {
		// 取得使用者資訊
		$api = 'GetUserInfo';
		return $this->call_method($api);
	}
	
	public function GetMtagInfo() {
		// 取得使用者資訊
		$api = 'GetMtagInfo';
		return $this->call_method($api);
	}
	
	public function GetFriendList() {
		// 取得好友列表
		$api = 'GetFriendList';
		return $this->call_method($api);
	}	
	
	public function GetAppFriendList() {
		// 取得應用好友列表
		$api = 'GetAppFriendList';
		return $this->call_method($api);
	}
	
	public function JoinAppGroup() {
		// 加入應用同好
		$api = 'JoinAppGroup';
		return $this->call_method($api);
	}
	
	public function SetAppFeed($params = array())
	{
		// 發佈應用動態
		$api = 'SetAppFeed';
		return $this->call_method($api, $params);		
	}
	
	public function SetAppAchive($params = array())
	{
		// 發佈成就等級
		$api = 'SetAppAchive';
		return $this->call_method($api, $params);		
	}
	
	public function getInviteSrc($inviteParams = array(), $inviteType = 'all')
	{
		// 取得應用邀請url
		$appParams = '';
		if(!empty($inviteParams))
		{
			$inviteParams = urlencode(json_encode($inviteParams));		
			$appParams .= '&inviteParams='.$inviteParams;			
		}
		if($inviteType == 'notjoin')
		{
			$appParams .= '&inviteType='.$inviteType;
		}
		$inviteUrl = $this->server_address.'Invite?auth=' . $this->getAuthes() . $appParams;
		return $inviteUrl;			
	}
	
	public function getFeedSrc($feedParams = array())
	{
		// 取得應用動態url
		$feedUrl = $this->server_address.'Feed?auth=' . $this->getAuthes();
	
		foreach($feedParams as $key => $value)
		{			
			$feedUrl = $feedUrl . '&' . $key . '=' . urlencode($value);
		}
		
		return $feedUrl;			
	}

	
	private function call_method($api, $params = array()) {
		
		$data = $this->post_request($api, $params);
		return $this->convert_result($data);
	}
	
	public function post_request($api, $params) {			
		$URL = $this->server_address.$api;
		
		// 取得驗證值
		$params = $this->getParams($params);			

		// 設定回傳格式為json
		$params['format'] = 'json';

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$URL);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
		curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
		$result = curl_exec($ch);				
		curl_close($ch);						
		return $result;
	}
	
	private function convert_result($data) {
		return json_decode($data, true);
	}
	
	private function getHash($session) {
		$dns = $_SERVER['HTTP_HOST'];
		
		// 取得當前request的url
		$rowData = $this->secret.$this->API_Key.$dns.$session;
		return md5($rowData);
	}
	
	private function setCookie($name, $value) {
		$mtime = explode(' ', microtime());
		setcookie($name, $value);
	}
	
	private function clearCookie($name) {
		setcookie($name);
		setcookie ($name, "", time() - 3600);
	}
	
	private function getSession() {
		$session = $_GET['minik_session'];
		
		if(empty($session)) {
			$session = $_COOKIE['uchome_auth'];	
			$session = urlencode($session);	
		}			

		return  $session;
	}
	
	private function getParams($params = array())
	{
		// 參數加入驗證值
		$params['key'] = $this->API_Key;
		$params['dns'] = $_SERVER['HTTP_HOST'];
		$params['timeStamp'] = self::$timeStamp = empty(self::$timeStamp) ? $_COOKIE["minik_timeStamp"] : self::$timeStamp;
		$params['hash'] = empty(self::$hash) ? $_COOKIE[md5($this->API_Key.self::$timeStamp).'_mkauth'] : self::$hash;
		$params['session'] = empty(self::$session) ? $_COOKIE[md5($this->API_Key.self::$timeStamp).'_mksession'] : self::$session;
		$params['mksecret'] = empty(self::$mksecret) ? $_COOKIE[md5($this->API_Key.self::$timeStamp).'_mksecret'] : self::$mksecret;

		return $params;
	}
	
	public function getAuthes()
	{
		$params = $this->getParams();

		return rawurlencode(json_encode($params));
	}
	
	public function getGiftInviteSrc($title, $content, $giftParams = array())
	{
		// 取得送禮邀請url
		$giftParams = urlencode(json_encode($giftParams));		
		$giftInviteUrl = $this->server_address.'GiftInvite?auth=' . $this->getAuthes() . '&giftParams=' . $giftParams . '&title=' . $title . '&content=' . $content;

		return $giftInviteUrl;			
	}
}
?>