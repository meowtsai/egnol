<?php

/**
 * PHP SDK for raidcall.com (using OAuth1.0a)
 *
 * PHP version 5
 *
 * @category  PHP
 * @package   Data
 * @author	  陈彦旭 <yanxu.chen@gmail.com>
 * @copyright 2012 Kalends Ltd.
 * @license   http://www.raidcall.com All rights reserved
 * @link	  http://www.raidcall.com
 */
$rdoauth_sdk_path = dirname(__FILE__);
//require_once "$rdoauth_sdk_path/rd_config.php";

/**
 * raidcall 异常处理类
 *
 * PHP version 5
 *
 * @category  PHP
 * @package   Data 
 * @author	  陈彦旭 <yanxu.chen@gmail.com>
 * @copyright 2012 Kalends Ltd. 
 * @license   http://www.raidcall.com All rights reserved 
 * @version   Release: @1.0@
 * @link	  http://www.raidcall.com
 */
class OAuthException extends Exception
{
	// pass
}

/**
 * raidcall 第三方OAuth接入认证类 （OAuth1.0a）
 *
 * PHP version 5
 *
 * @category  PHP
 * @package   Data 
 * @author	  陈彦旭 <yanxu.chen@gmail.com>
 * @copyright 2012 Kalends Ltd. 
 * @license   http://www.raidcall.com All rights reserved 
 * @version   Release: @1.0@
 * @link	  http://www.raidcall.com
 */
class RdOAuthV1
{
	/**
	 * 第三方应用id（有raidcall授予）
	 * 
	 * @var string
	 */
	public $consumer_key;
	
	/**
	 * 第三方应用密钥（有raidcall授予）
	 * 
	 * @var string
	 */
	public $consumer_secret;

	/**
	 * 临时访问令牌（有raidcall授予）
	 * 
	 * @var string
	 */
	public $request_token;

	/**
	 * 临时访问密钥（有raidcall授予）
	 * 
	 * @var string
	 */
	public $request_token_secret;

	/**
	 * 用户授权访问令牌（有raidcall授予）
	 * 
	 * @var string
	 */
	public $access_token;

	/**
	 * 用户授权访问密钥（有raidcall授予）
	 * 
	 * @var string
	 */
	public $access_token_secret;

	/**
	 * 第三方应用回调url
	 * 
	 * @var string
	 */
	public $callback_url;

	/**
	 * 请求方法
	 * 
	 * @var string
	 */
	public $method;
	
	/**
	 * 请求方法
	 * 
	 * @var string
	 */
	public $body;
	

	/**
	 * http header
	 * 
	 * @var string
	 */
	public $http_header;

	/**
	 * 打印调式信息
	 *
	 * @var bool
	 */
	public $debug = FALSE;

	/**
	 * 参数保存数组
	 * 
	 * @var array
	 */
	public $param;
	
	/**
	 * session 服务器
	 * 
	 * @var array
	 */
	public $session;

	/**
	 * RdOAuthV1(单例类对象)
	 * @var Object(RdOAuthV1)
	 */
	private static $_rdOauth = null;

	/**
	 * 获取 OAuth 认证类实例对象
	 * 
	 * @param $access_token        RC已经授权的令牌
	 * @param $access_token_secret RC已经授权的令牌密钥
	 *
	 * @return Object $obj 认证类实例对象
	 */
	public static function getRdOAuthV1Obj($access_token = NULL, $access_token_secret = NULL)
	{
		if (RdOAuthV1::$_rdOauth == null) {
			RdOAuthV1::$_rdOauth = new RdOAuthV1($access_token, $access_token_secret);
		}
		return RdOAuthV1::$_rdOauth;
	}

	/**
	 * 请求临时Token url
	 */
	function requestTokenURL()
	{
		return 'http://open.raidcall.com.tw/oauth/request_token.php';
	}

	/**
	 * 获取用户授权url
	 */
	function authorizeURL()
	{
		return 'http://open.raidcall.com.tw/oauth/authorize.php';
	}

	/**
	 * 获取访问令牌url
	 */
	function accessTokenURL()
	{
		return 'http://open.raidcall.com.tw/oauth/access_token.php';
	}

	/**
	 * 初始化接口
	 * 
	 * @param $access_token        RC已经授权的令牌
	 * @param $access_token_secret RC已经授权的令牌密钥
	 * 
	 */
	function __construct($access_token, $access_token_secret)
	{
		$this->consumer_key = RD_AKEY;
		$this->consumer_secret = RD_SKEY;
		$this->callback_url = RD_CALLBACK_URL;
		$this->method = 'POST';
		$this->access_token = $access_token;
		$this->access_token_secret = $access_token_secret;
		$this->session = &$_SESSION ['oauth_' . RD_AKEY];
	}

	/**
	 * 获取临时token 
	 */
	public function getRequestToken()
	{
		$this->setParam('oauth_signature_method', 'HMAC-SHA1');
		$this->setParam('oauth_signature', '');
		$this->setParam('oauth_nonce', uniqid(''));
		$this->setParam('oauth_timestamp', time());
		$this->setParam('oauth_consumer_key', $this->consumer_key);
		$this->setParam('oauth_version', '1.0');
		$this->setParam('oauth_callback', $this->callback_url);

		$body = $this->getBody();
		if (!is_null($body)) {
			$body_signature = $this->signature($body, $this->consumer_secret, $this->access_token_secret, $this->param ['oauth_signature_method']);
			$this->setParam('xoauth_body_signature', $body_signature, true);
		}

		$signature = $this->calculateParamSignature($this->requestTokenURL(), $this->consumer_secret, $this->access_token_secret);
		$this->setParam('oauth_signature', $signature, true);


		$text = $this->curl_raw($this->requestTokenURL());

		if (empty($text)) {
			throw new OAuthException('No answer from the server  while requesting a request token');
		}

		$data = $this->curl_parse($text);
		if ($data ['code'] != 200) {
			throw new OAuthException('Unexpected result from the server while requesting a request token');
		}

		$token = array();
		$params = explode('&', $data ['body']);
		foreach ($params as $p) {
			@list ( $name, $value ) = explode('=', $p, 2);
			$token [$name] = $this->urldecode($value);
		}

		$this->request_token = $token ['oauth_token'];
		$this->request_token_secret = $token ['oauth_token_secret'];
		
		$this->session['request_token_secret'] = $this->request_token_secret;
		$this->session['request_token'] = $this->request_token;
		return $token;
	}

	/**
	 * authorize接口 对应API：{@link http://open.raidcall.com.tw/oauth/authorize.php}
	 * 		
	 * @return string $url 授权的URL
	 */
	function getAuthorizeURL()
	{
		$params = array();
		$params['oauth_token'] = $this->request_token;
		$params['oauth_callback'] = $this->callback_url;
		return $this->authorizeURL() . "?" . http_build_query($params);
	}

	/**
	 * 请求用户真正的访问权限密钥
	 *
	 * @param $oauth_token    服务器回调回来的令牌
	 * @param $oauth_verifier 服务器确认令牌需要传给服务器验证
	 * 
	 */
	function getAccessTocken($oauth_token, $oauth_verifier)
	{

		$this->setParam('oauth_consumer_key', $this->consumer_key);
		$this->setParam('oauth_token', $oauth_token);
		$this->setParam('oauth_signature_method', 'HMAC-SHA1');
		$this->setParam('oauth_signature', '');
		$this->setParam('oauth_timestamp', time());
		$this->setParam('oauth_verifier', $oauth_verifier);
		$this->setParam('oauth_nonce', uniqid(''));
		$this->setParam('oauth_version', '1.0');
		
		$this->request_token_secret = $this->session['request_token_secret'];
		$body = $this->getBody();
		if (!is_null($body)) {
			$body_signature = $this->signature($body, $this->consumer_secret, $this->request_token_secret, $this->param ['oauth_signature_method']);
			$this->setParam('xoauth_body_signature', $body_signature, true);
		}
			
		$signature = $this->calculateParamSignature($this->accessTokenURL(), $this->consumer_secret, $this->request_token_secret, 'oauth_token');
		$this->setParam('oauth_signature', $signature, true);

		$text = $this->curl_raw($this->accessTokenURL());

		if (empty($text)) {
			throw new OAuthException('No answer from the server  while requesting an access token');
		}
		$data = $this->curl_parse($text);
	
		if ($data ['code'] != 200) {
			throw new OAuthException('Unexpected result from the server while requesting an access token');
		}

		$token = array();
		$params = explode('&', $data ['body']);
		foreach ($params as $p) {
			@list ( $name, $value ) = explode('=', $p, 2);
			$token [$this->urldecode($name)] = $this->urldecode($value);
		}

		$this->access_token = $token ['oauth_token'];
		$this->access_token_secret = $token ['oauth_token_secret'];
		return $token;
	}
	
	/**
	 * 根据接口url获取RC中的信息
	 * 
	 * @param $url                 协议url
	 * 
	 * @return string
	 */
	public function onRegueset($url)
	{
		$user_info_url = $url;
		$this->clearParam();
		$this->setParam('oauth_signature_method', 'HMAC-SHA1');
		$this->setParam('oauth_signature', '');
		$this->setParam('oauth_nonce', uniqid(''));
		$this->setParam('oauth_timestamp', time());
		$this->setParam('oauth_consumer_key', $this->consumer_key);
		$this->setParam('oauth_token', $this->access_token);
		
		$body = $this->getBody();
		if (!is_null($body)) {
			$body_signature = $this->signature($body, $this->consumer_secret, $this->access_token_secret, $this->param ['oauth_signature_method']);
			$this->setParam('xoauth_body_signature', $body_signature, true);
		}
			
		$signature = $this->calculateParamSignature($user_info_url, $this->consumer_secret, $this->access_token_secret, 'oauth_token');
		$this->setParam('oauth_signature', $signature, true);

		$text = $this->curl_raw($user_info_url);

		if (empty($text)) {
			throw new OAuthException('No answer from the server  while requesting a request token');
		}

		$data = $this->curl_parse($text);
		if ($data ['code'] != 200) {
			throw new OAuthException('Unexpected result from the server while requesting a request token');
		}

		return $data['body'];
	}

	/**
	 * 根据名字获取对应的值
	 *
	 * @param  string	$name      查询的键值名字
	 * @param  boolean $urldecode 设置成true则对返回值解码处理
	 * @return string  $value     失败返回false
	 */
	public function getParam($name, $urldecode = false)
	{
		if (isset($this->param [$name])) {
			$s = $this->param [$name];
		} else if (isset($this->param [$this->urlencode($name)])) {
			$s = $this->param [$this->urlencode($name)];
		} else {
			$s = false;
		}
		if (!empty($s) && $urldecode) {
			if (is_array($s)) {
				$s = array_map(array(
					$this,
					'urldecode'
						), $s);
			} else {
				$s = $this->urldecode($s);
			}
		}
		return $s;
	}

	/**
	 * 设置参数
	 *
	 * @param string  $name    参数名字
	 * @param string  $value   参数值
	 * @param boolean $encoded	是否对名字和值进行编码
	 */
	public function setParam($name, $value, $encoded = false)
	{
		if (!$encoded) {
			$name_encoded = $this->urlencode($name);
			if (is_array($value)) {
				foreach ($value as $v) {
					$this->param [$name_encoded] [] = $this->urlencode($v);
				}
			} else {
				$this->param [$name_encoded] = $this->urlencode($value);
			}
		} else {
			$this->param [$name] = $value;
		}
	}
	
	/**
	 * 设置参数
	 *
	 * @param string  $name    参数名字
	 * @param string  $value   参数值
	 * @param boolean $encoded	是否对名字和值进行编码
	 */
	public function clearParam()
	{
		unset($this->param);
	}

	/**
	 * 返回请求的内容
	 *
	 * @return string $body 内容
	 */
	function getBody()
	{
		return $this->body;
	}

	/**
	 * 设置请求的内容
	 * 
	 * @param string $body 内容
	 */
	function setBody($body)
	{
		$this->body = $body;
	}

	/**
	 * 字串编码 RFC3986
	 *
	 * @param string $s 编码字串
	 * @return string $value 字串
	 */
	public function urlencode($s)
	{
		if ($s === false) {
			return $s;
		} else {
			return str_replace('%7E', '~', rawurlencode($s));
		}
	}

	/**
	 * 字串解码 RFC3986.
	 *
	 * @param string $s 解码字串
	 * @return string $value 字串
	 */
	public function urldecode($s)
	{
		if ($s === false) {
			return $s;
		} else {
			return rawurldecode($s);
		}
	}

	/**
	 * 返回请求的方法
	 *
	 * @return string $method 方法
	 */
	function getMethod()
	{
		return $this->method;
	}

	/**
	 * 计算签名信息 HMAC-SHA1
	 *
	 * @param string base_string     需要签名的字串
	 * @param string consumer_secret 客户密钥
	 * @param string token_secret    用户密钥
	 * @return string $vale          返回加密字串
	 */
	function signature($base_string, $consumer_secret, $token_secret)
	{
		$key = $this->urlencode($consumer_secret) . '&' . $this->urlencode($token_secret);
		if (function_exists('hash_hmac')) {
			$signature = base64_encode(hash_hmac("sha1", $base_string, $key, true));
		} else {
			$blocksize = 64;
			$hashfunc = 'sha1';
			if (strlen($key) > $blocksize) {
				$key = pack('H*', $hashfunc($key));
			}
			$key = str_pad($key, $blocksize, chr(0x00));
			$ipad = str_repeat(chr(0x36), $blocksize);
			$opad = str_repeat(chr(0x5c), $blocksize);
			$hmac = pack('H*', $hashfunc(($key ^ $opad) . pack('H*', $hashfunc(($key ^ $ipad) . $base_string))));
			$signature = base64_encode($hmac);
		}
		return $this->urlencode($signature);
	}

	/**
	 * 返回所有的参数的完整字串用作签名检查
	 *
	 * @return string $value 字串
	 */
	function getNormalizedParams()
	{
		$params = $this->param;
		$normalized = array();

		ksort($params);
		foreach ($params as $key => $value) {
			if ($key != 'oauth_signature') {
				if (is_array($value)) {
					$value_sort = $value;
					sort($value_sort);
					foreach ($value_sort as $v) {
						$normalized [] = $key . '=' . $v;
					}
				} else {
					$normalized [] = $key . '=' . $value;
				}
			}
		}
		return implode('&', $normalized);
	}

	/**
	 * 版本检查
	 *
	 */
	function checks()
	{
		if (isset($this->param ['oauth_version'])) {
			$version = $this->urldecode($this->param ['oauth_version']);
			if ($version != '1.0') {
				throw new OAuthException('Expected OAuth version 1.0, got "' . $this->param ['oauth_version'] . '"');
			}
		}
	}

	/**
	 * 返回用于签名的基本字串 不能使用 rawurlencode 只能使用RFC3986
	 *
	 * @return string
	 */
	function signatureBaseString($url)
	{
		$sig = array();
		$sig [] = $this->method;
		$sig [] = $url;
		$sig [] = $this->getNormalizedParams();

		return implode('&', array_map(array(
							$this,
							'urlencode'
								), $sig));
	}

	/**
	 * 对参数进行签名处理
	 *
	 * @param string consumer_secret 应用密钥
	 * @param string token_secret    用户获取的密钥
	 * @param string token_type      类型   
	 * @return string
	 */
	function calculateParamSignature($url, $consumer_secret, $token_secret, $token_type = 'requestToken')
	{
		$required = array(
			'oauth_consumer_key',
			'oauth_signature_method',
			'oauth_timestamp',
			'oauth_nonce'
		);

		if ($token_type != 'requestToken') {
			$required [] = 'oauth_token';
		}

		foreach ($required as $req) {
			if (!isset($this->param [$req])) {
				throw new OAuthException('Can\'t sign request, missing parameter "' . $req . '"');
			}
		}

		$this->checks();

		$base = $this->signatureBaseString($url);
		$signature = $this->signature($base, $consumer_secret, $token_secret, $this->param ['oauth_signature_method']);
		return $signature;
	}

	/**
	 * 解析服务器返回的内容
	 *
	 * @param string $response HTTP返回的内容字串
	 * @return array $vals (code=>http-code, headers=>http-headers, body=>body)
	 */
	protected function curl_parse($response)
	{
		if (empty($response)) {
			return array();
		}

		@list ( $headers, $body ) = explode("\r\n\r\n", $response, 2);
		$lines = explode("\r\n", $headers);

		if (preg_match('@^HTTP/[0-9]\.[0-9] +100@', $lines [0])) {
			/*
			 * HTTP/1.x 100 Continue the real data is on the next line
			 */
			@list ( $headers, $body ) = explode("\r\n\r\n", $body, 2);
			$lines = explode("\r\n", $headers);
		}

		// first line of headers is the HTTP response code
		$http_line = array_shift($lines);
		if (preg_match('@^HTTP/[0-9]\.[0-9] +([0-9]{3})@', $http_line, $matches)) {
			$code = $matches [1];
		}

		// put the rest of the headers in an array
		$headers = array();
		foreach ($lines as $l) {
			list ( $k, $v ) = explode(': ', $l, 2);
			$headers [strtolower($k)] = $v;
		}

		return array(
			'code' => $code,
			'headers' => $headers,
			'body' => $body
		);
	}

	/**
	 * Get the header info to store.
	 *
	 * @return int
	 * @ignore
	 */
	function getHeader($ch, $header)
	{
		$i = strpos($header, ':');
		if (!empty($i)) {
			$key = str_replace('-', '_', strtolower(substr($header, 0, $i)));
			$value = trim(substr($header, $i + 2));
			$this->http_header[$key] = $value;
		}
		return strlen($header);
	}

	/**
	 * 编译一个请求授权的http头
	 *
	 * @return string
	 */
	function getAuthorizationHeader()
	{
		$h = array();
		$h [] = 'Authorization: OAuth realm=""';
		foreach ($this->param as $name => $value) {
			if (strncmp($name, 'oauth_', 6) == 0 || strncmp($name, 'xoauth_', 7) == 0) {
				$h [] = $name . '="' . $value . '"';
			}
		}
		$hs = implode(', ', $h);
		return $hs;
	}

	/**
	 * 编译请求服务器参数字串
	 * 
	 * @return string
	 */
	function getQueryString()
	{
		$parms = array();
		foreach ($this->param as $name => $value) {
			if ( (strncmp($name, 'oauth_', 6) != 0 && strncmp($name, 'xoauth_', 7) != 0)) {
				if (is_array($value)) {
					foreach ($value as $v) {
						$parms [] = $name . '=' . $v;
					}
				} else {
					$parms [] = $name . '=' . $value;
				}
			}
		}
		return implode('&', $parms);
	}

	/**
	 * 调用curl库模拟一次客户端请求
	 *
	 * @param string $url 请求的URL
	 * @return string $restul 结果
	 */
	protected function curl_raw($url)
	{
		$header = array();
		$ch = curl_init();
		$method = $this->getMethod();
		$header [] = $this->getAuthorizationHeader();
		$query = $this->getQueryString();
		$body = $this->getBody();

		$has_content_type = false;
		foreach ($header as $h) {
			if (strncasecmp($h, 'Content-Type:', 13) == 0) {
				$has_content_type = true;
			}
		}

		if (!is_null($body)) {
			if (!$has_content_type) {
				$header [] = 'Content-Type: application/octet-stream';
				$has_content_type = true;
			}
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
		} else {
			if ($method == 'POST') {
				if (!$has_content_type) {
					$header [] = 'Content-Type: application/x-www-form-urlencoded';
					$has_content_type = true;
				}

				curl_setopt($ch, CURLOPT_POST, true);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $query);
			} else {
				if (!empty($query)) {
					$url .= '?' . $query;
				}
				if ($method != 'GET') {
					curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
				}
			}
		}

		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_USERAGENT, 'anyMeta/OAuth 1.0 - ($LastChangedRevision: 174 $)');
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HEADER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);

		$txt = curl_exec($ch);
		if ($txt === false) {
			$error = curl_error($ch);
			curl_close($ch);
			throw new OAuthException('CURL error: ' . $error);
		}

		curl_close($ch);
		return $txt;
	}

}

/**
 * raidcall 第三方接口类
 *
 * PHP version 5
 *
 * @category  PHP
 * @package   Data 
 * @author	  陈彦旭 <yanxu.chen@gmail.com>
 * @copyright 2012 Kalends Ltd. 
 * @license   http://www.raidcall.com All rights reserved 
 * @version   Release: @1.0@
 * @link	  http://www.raidcall.com
 */
class RdOAuthClientV1
{

	/**
	 * oauth 操作类
	 * 
	 * @var string
	 */
	protected $oauth;

	/**
	 * 初始化接口
	 */
	function __construct($access_token, $access_token_secret)
	{
		$this->oauth = RdOAuthV1::getRdOAuthV1Obj($access_token, $access_token_secret);
	}

	/**
	 * 获取用户信息
	 * 
	 * @return array $vals 用户信息数组{“id”: “abb13854d25fcd101b18248042d7bc34”,"nick" : "test",“imgUri”: ” http://open.raidcall.com.tw/oauth/IMLogo30_30.jpg”}
	 */
	public function getUserInfo()
	{
		$user_info_url = 'http://open.raidcall.com.tw/oauth/api.php';
		$data = $this->oauth->onRegueset($user_info_url);
		return json_decode($data,TRUE);
	}

}

?>