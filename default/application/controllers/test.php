<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Test extends MY_Controller {
	
	function d1205()
	{
		
		$key = 'cooZ!@#99';
		$euid = '54580463';
		$gold = '1';
		$time = time();
		
		var_dump($euid.$gold.$time.$key);
		echo "<br>";
		
		$hash = md5($euid.$gold.$time.$key);
		$url = "http://yiyatw.tenone.cn/yiya/yiya_taiwan/integral/Callback.php?euid={$euid}&gold={$gold}&time={$time}&hash={$hash}";
		
		$re = my_curl($url);
		
		var_dump($url);
		echo "<br>";
		var_dump($re);
	}
	
	function d1030()
	{
		$this->load->library("game_api/zj");
		var_dump($this->zj->get_lv('1', '52512627'));
		print_r($this->zj->error_message);
	}
	
	function d0822()
	{
		$date = '2014-08-22 04:05:35 Etc/GMT';
		echo date("Y-m-d H:i:s", strtotime(substr($date, 0, -8))+28800);
		
	}
	
function ssss()
{
	set_include_path(dirname(__FILE__)."/../libraries" . PATH_SEPARATOR . get_include_path());
	
	require_once 'GooglePlay/InAppBilling/GooglePlayResponseData.php';
	require_once 'GooglePlay/InAppBilling/GooglePlayResponseValidator.php';
	require_once 'GooglePlay/InAppBilling/GooglePlayInvalidArgumentException.php';
	require_once 'GooglePlay/InAppBilling/GooglePlayOrder.php';
	
	//Your key, copy and paste from https://market.android.com/publish/editProfile
	define('PUBLIC_KEY', 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAl5XOm9TY+agyGPKGzDfIphS9E8N/Xpsmjm3sXea8tULiOciBiEzcR79M33qnvGc/aBR5uMPEdgOhHtnzTRMTG9ECLMZZ2VG/nYywO47IseuLphEh7fc2UIwQHYdkUIz27Ri5ndTwL8860mBgYy4YtUmo8OCWp7/KzxXzt1vllnCmyP9wnf9hm8vD7IgJERzeurdVlwhzoHuxFpwfcF8AZ7PuTdfkb9C5ixamqfsr1tgukPHmOsz1oAOnfzk52ei2WlG1MccHUX8phYsdm3D0/JclX3oBk7LLTnz5tVj3V6Qw1SnrxalVyanSzolMhHw5PZhaJ50QqxIC3YNTQsxpWwIDAQAB');
	//Your app's package name, e.g. com.example.yourapp
	define('PACKAGE_NAME', 'com.long_e.gsg');
	
	//The | delimited response data from the licensing server
	$responseData = '{"orderId":"12999763169054705758.1357556224180558","packageName":"com.long_e.gsg","productId":"p50","purchaseTime":1405403964025,"purchaseState":0,"developerPayload":"{\"id\":\"1371\",\"payload\":\"d3ef854e\",\"euid\":\"54580463\"}","purchaseToken":"lmhjeldihfifickmhngoplkg.AO-J1OxkuTUxq0EqAUEvBwQajb7vTbgidBjwhEZE5KsyF9zYYo_birY0ReUfAmqrrFNjpzb4gAaU013JRFImAb33yDHqo-UaCybxiDzSBcElVGJsRaKOmPg"}';
	//The signature provided with the response data (Base64)
	$signature = 'eZfVspynNA/w895UH624A7eqyw9tLTzkD6lCZK4tRCn+N6xadxYXxsvUFuWRKYjU1MussU/Pvq9C3AAi7/HF9zQ0BsouWIqcQGMgYH5/MXVz79wrUvZkYrR1f3m/vNtaVrtN7pMOGQYrIdEqMGVzt6L5/7tRMdof1dGtt8dC8JkNiySzgJjAX1mOryJ5jVzscKalK+ItgKxI7F2eesukGm0MHBV852KH5y7+KOjMZwSv+1zxGTM3Lya+aaArptmsgrbqr4KluLDAtQNWjoxVGfTVNaV1AVrXVqTidQdwxb8OHVqgWPViXa7JgR3RjyBGUFzU+GpPsLUrCyjo9jOWPw==';
	
	//if you wish to inspect or use the response data, you can create
	//a response object and pass it as the first argument to the Validator's verify method
	//$response = new AndroidMarket_Licensing_ResponseData($responseData);
	//$valid = $validator->verify($response, $signature);
	
	//$validator = new GooglePlayResponseValidator(PUBLIC_KEY, PACKAGE_NAME);
	$valid = $this->verify_purchase(PUBLIC_KEY, $responseData, $signature);
	
	var_dump($valid);	
}


	function google_callback()
	{
		$client_id = '43086991528-jus4ns58iriinu63shrrfu1udb7gfl82.apps.googleusercontent.com';
		$service_account = '43086991528-jus4ns58iriinu63shrrfu1udb7gfl82@developer.gserviceaccount.com';
		$callback = 'http://'.base_url().'/test/google_callback';		
		$auth_url = 'https://accounts.google.com/o/oauth2/token';
		
		$code = $this->input->get('code');
		$data = array(
			"grant_type" => "authorization_code",
			"code" => $code,
			"client_id" => $client_id,
			"client_secret" => 'hA_w-PKZ5hMMJxJ43GX95nPv',
			"redirect_uri" => $callback,
		);
		
		echo "<pre>";
		var_dump($data);
		echo "<pre>";
		
		
		$re = my_curl($auth_url, $data) ;
		
		var_dump($re);

		//$auth_url = "https://accounts.google.com/o/oauth2/auth?scope=https://www.googleapis.com/auth/androidpublisher&response_type=code&access_type=offline&redirect_uri={$callback}&client_id={$client_id}";
		
	}
	
	
	function d0715()
	{
		set_include_path(dirname(__FILE__)."/../libraries" . PATH_SEPARATOR . get_include_path());
		
		require_once 'Google/Client.php';
		require_once 'Google/Service/Books.php';
		require_once 'Google/Service/AndroidPublisher.php';
		require_once 'Google/Auth/AssertionCredentials.php';
		

//user token, in json format
$ANDROIDUsertoken = '{"orderId":"12999763169054705758.1305281430445281","packageName":"com.long_e.gsg","productId":"p1000","purchaseTime":1405339811658,"purchaseState":0,"purchaseToken":"gbpiajfchnmefgejgcmdecme.AO-J1Owem-9KZ_oE36DN"}';
$user_token= json_decode($ANDROIDUsertoken,true);

			
$client_id = '43086991528-jus4ns58iriinu63shrrfu1udb7gfl82.apps.googleusercontent.com';
$service_account = '43086991528-jus4ns58iriinu63shrrfu1udb7gfl82@developer.gserviceaccount.com';
$key = file_get_contents("http://".base_url()."/p/key.p12"); 
//'hA_w-PKZ5hMMJxJ43GX95nPv';

$client = new Google_Client();
$client->setApplicationName($user_token['packageName']);
$client->setClientId($client_id);
 
 
//'AIzaSyD_bcHsh7TN69H49An6zsRPD56XdZiMtYo';
 
$auth = new Google_Auth_AssertionCredentials(
    $service_account,
    array('https://www.googleapis.com/auth/androidpublisher'),
    $key);
 
$client->setAssertionCredentials($auth);
 
//$client->getAuth()->refreshTokenWithAssertion();
//$accessToken=$client->getAccessToken();
//$client->setAccessToken($accessToken);
 
//try { 
	$AndroidPublisherService = new Google_Service_AndroidPublisher($client);
	$res = $AndroidPublisherService->inapppurchases->get($user_token['packageName'], $user_token['productId'], $user_token['purchaseToken']);
//} catch (Exception $e) { var_dump( $e->getMessage() ); }

var_dump($res);


/*
		
		$client = new Google_Client(); 
		$client->setApplicationName( 'Cooz' );
		$client->setClientId('43086991528-783qqa6n5rn0opae4pt4rhqhl6u6mn1t.apps.googleusercontent.com'); 
		$key = 'AIzaSyD_bcHsh7TN69H49An6zsRPD56XdZiMtYo'; 
		$service_account_name = '43086991528-783qqa6n5rn0opae4pt4rhqhl6u6mn1t@developer.gserviceaccount.com';
		$client->setScopes( array('https://www.googleapis.com/auth/androidpublisher') );
		$cred = new Google_Auth_AssertionCredentials( $service_account_name, array('https://www.googleapis.com/auth/androidpublisher'), $key );
		$client->setAssertionCredentials($cred);
		
		$externalAppId = 'com.long_e.gsg';
		$externalProductId = 'com.long_e.gsg.p1000';
		$purchaseToken = 'gbpiajfchnmefgejgcmdecme.AO-J1Owem-9KZ_oE36DN';
*/		
		/*
		try { 
			$service = new Google_Service_AndroidPublisher( $client ); 
			$googleApiResult = $service->inapppurchases->get($externalAppId, $externalProductId, $purchaseToken); 
		} catch (Exception $e) { var_dump( $e->getMessage() ); }
		
		
if($client->getAuth()->isAccessTokenExpired()) {
    $client->getAuth()->refreshTokenWithAssertion($cred);
}
		
		echo '<pre>'; 
		$results = $service->volumes->listVolumes('Henry David Thoreau', array('filter' => 'free-ebooks')); print_r($results);
		echo '</pre>';
		*/
		
		
		/*
		$client = new Google_Client();
		$client->setApplicationName("Cooz");
		$client->setDeveloperKey("AIzaSyD_bcHsh7TN69H49An6zsRPD56XdZiMtYo");

		$service = new Google_Service_Books($client);
		
		$optParams = array('filter' => 'free-ebooks');
		$results = $service->inapppurchases->get;
		
		foreach ($results as $item) {
			echo $item['volumeInfo']['title'], "<br /> \n";
		}
		*/
	}
	
	function reback()
	{
		$this->load->library("g_wallet");
		
		$game = 'zj';		
		$game_name = '真三十六計';
		
		$back = array(
			//uid => money,
			"304757" => "100",
				
		);
		
		foreach($back as $uid => $money) {
			$billing_id = $this->g_wallet->produce_order($uid, "long_e_billing", "3", $money, $game, "");
			$this->db->where("id", $billing_id)->update("user_billing", array("result" => "1", "note" => $game_name."補點"));
		}
	}
	
	function d0204()
	{
		$query = $this->db
			->select("uid")->distinct()
			->from("characters gsr")
			->join("servers gi", "gsr.server_id=gi.server_id")
			->where("gi.game_id", "my")->order_by("uid")->get();
		foreach($query->result() as $row) {
			echo "{$row->uid}, {$this->g_user->encode($row->uid)}<br>";
		}
	}
	
	function d0115()
	{
		if (preg_match('/^\d{20}\.\d{16}$/', $this->input->get('g'))) {
			die('ok');
		}
		
		die('no');
	}
	
	function d1210()
	{
		die("ggg:".$this->g_user->uid);
	}

	function d1121()
	{
		die("
<script>
	function showData(){
		var data = document.getElementById('data');
		document.getElementById('showhere').innerHTML = data.value;

		window.Brad.alert(data.value);
		// 或是直接 Brad.alert(data.value);亦可
	}

	// 這段函式負責處理接收資料
	function fromAndroid(msg){
		document.getElementById('showhere').innerHTML = msg;
	}

</script>
<h1>Brad Big Company</h1>
<hr />
<input type='text' id='data' />
<input type='button' onclick='showData()' value='Click' />
<hr />
<div id='showhere'></div>		
				");
	}
	
	function d1107() 
	{
		$this->load->library("g_wallet");
			
		$billing_id = $this->g_wallet->produce_order("304757", "google_billing", "2", "100", "eya", "order_ddddddddddddddddid");
		//if (empty($billing_id)) output_json(RESPONSE_FAILD, "資料庫發生錯誤");
		//$order["id"] = $billing_id;
	}
	
	function d1016()
	{
		$this->load->model("games");
		$server = $this->games->get_server(108);		
		$billing = $this->db->where("id", "395906")->get("user_billing")->row();
		
		$this->load->library("game_api/kj");
		var_dump($this->kj->transfer($server, $billing, 1));
		print_r($this->kj->error_message);
	}
	
	function d102()
	{
		die("data=".$_POST["data"]);
	}
	
	function d813()
	{
		$this->load->library("g_wallet");
		$this->g_wallet->produce_mycard_order(304757, 333076, "mycard_ingame", 100);
		var_dump($this->g_wallet->error_message);
	}
	
	function d74()
	{
		$re = my_curl("http://tracking1.aleadpay.com/Pixel/Advertiser/178/?cid=46999a18-7b54-4d7f-9381-ded4f6df1a5f");
		var_dump($re);
		
	}
	
	function ddh()
	{				
		echo "Ts".date("YmdHis",time()).rand(11,99);
	}
	
	function d71()
	{
		$key = '7b2f0f068e48a78ca9374163af1766Cz';
		
		//euid,服务器ID,payGold,payNum(订单号),time(时间戳,如1371719717），验证码（MD5(euid + 服务器ID + payGold + payNum + time + key))）
		$data['euid'] = '44176265';
		$data['sid'] = 'lxf01';
		$data['payGold'] = '10';
		$data['payNum'] = '123456789';
		$data['time'] = time();
		$data['hash'] = md5($data['euid'].$data['sid'].$data['payGold'].$data['payNum'].$data['time'].$key);		
		
		$sbody = '';
		foreach($data as $val) {
			$sbody .= chr(strlen($val)).$val;	
		}
		
		$blen = strlen($sbody);
		for($i =0; $i < $blen; $i++) {
			$sbody[$i] = $sbody[$i] ^ chr(0x37);			
        }
        $sbody = $sbody . chr(0x37);
        $len = $blen +1;
        $cmd = chr(0xff) . chr(0xd8) . chr(24) . chr(2) . chr($len >> 24 & 0xff) . chr($len >> 16 & 0xff) . chr($len >> 8 & 0xff) . chr($len & 0xff) . $sbody;
 		$sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP) or die("socket create failed");
 		 		
 		$sip = '203.75.245.111';
		$sport = '4901';
        socket_connect($sock, $sip, $sport) or die("err");
        socket_write($sock, $cmd, strlen($cmd)) or die("err2");
        
        $a = socket_read($sock, 1024);
        
        var_dump($a);
        exit();       
        
        
        /*
-1：版本错误
-2：充值IP验证未通过
-3：参数不全
-4：订单超时
-5：密串验证未通过
-6：游戏中无此账户
-7：玩家还未创建角色
0：充值成功
         */
	}
	
	function p1()
	{
		echo "<form action='/test/p2' method='post'>
				<input type='text' name='test' value='p333'>
			</form>	
		";
	}
	
	function p2()
	{
		header("location:/test/p3", TRUE, 307);
	}
	
	function p3()
	{
		var_dump($_POST);
	}
	
	function mmm()
	{
		$url = "https://b2b.mycard520.com.tw/MyCardBillingRESTSrv/MyCardBillingRESTSrv.svc/PaymentQuery/long_e";
		//$url = "https://b2b.mycard520.com.tw/MyCardBillingRESTSrv/MyCardBillingJsonRESTSrv.svc/ProductQueryForPrice/long_e/MBFIRSTBANKATM00100";
		$re = my_curl($url);
		$re = explode(",", $re);
		echo "<pre>";
		var_dump($re);
		echo "</pre>";
	}
	
	function send_mail()
	{
		$this->load->library("long_e_mailer");
		$this->long_e_mailer->passwdResetMail('sea7306@yahoo.com.tw', 'bean91', '123456', '彬');
	}
	
	function xl()
	{
		$this->load->model("games");
		$server = $this->games->get_server(90);		
		$user = $this->db->where("account", "oscar12345")->get("users")->row();
		
		$this->load->library("game_api/xl");
		$this->xl->login($server, $user, "");
	}
	
	function ooo()
	{
		$this->load->model("mycards");
		var_dump($this->mycards->check_value_exists("mycard_card_id", "MCAUHE0000014534"));
		var_dump($this->mycards->check_value_exists("mycard_trade_seq", "SW12122700300426"));
		
	}
	
	function gggg()
	{
		$this->load->model("games");
		$server = $this->games->get_server(537);
		
		$user = $this->db->where("account", "4483727@rc2")->get("users")->row();
		$this->load->library("game_api/st"); 
		$re = $this->st->check_role_status($server, $user);
		
		echo "<pre>";
		var_dump($re);
		var_dump($this->st->error_message);
		echo "</pre>";
	}
	
	function test_fb()
	{
		require_once dirname(__FILE__).'/../libraries/channel_api/fb/facebook.php';
		
		$sdk = new Facebook(array(
			'appId'  => '1025425380808895',
			'secret' => 'cc9819f7f4eac6749a9ecf714384c48c',
			));
		$user = $sdk->getUser();
		echo "<pre>";
		var_dump($sdk);
		var_dump($user);
		echo "</pre>";
		
		$loginUrl = $sdk->getLoginUrl();
		echo $loginUrl;
	}
	
	function z()
	{
		$this->load->library("g_wallet");
		$order = $this->g_wallet->get_order("295841");
		fb($order);
	}
	
	function id_rand()
	{
		echo "uid => euid <br>";
		echo "1234560 => ".$this->g_user->encode("1234560")."<Br>";
		echo "1234561 => ".$this->g_user->encode("1234561")."<Br>";
		echo "1234562 => ".$this->g_user->encode("1234562")."<Br>";
		echo "1234563 => ".$this->g_user->encode("1234563")."<Br>";
		echo "1234564 => ".$this->g_user->encode("1234564")."<Br>";
		echo "1234565 => ".$this->g_user->encode("1234565")."<Br>";
        echo "1234566 => ".$this->g_user->encode("1234566")."<Br>";
        echo "1234567 => ".$this->g_user->encode("1234567")."<Br>";
        echo "1234568 => ".$this->g_user->encode("1234568")."<Br>";
        echo "1234569 => ".$this->g_user->encode("1234569")."<Br>";
        echo "--<br>";
        echo "54179209 <= ".$this->g_user->decode("54179209")."<Br>";
        echo "37031224 <= ".$this->g_user->decode("37031224")."<Br>";
		echo "25346508 <= ".$this->g_user->decode("25346508")."<Br>";
		echo "47285427 <= ".$this->g_user->decode("47285427")."<Br>";
		echo "86329542 <= ".$this->g_user->decode("86329542")."<Br>";
		echo "47285107 <= ".$this->g_user->decode("47285107")."<Br>";
        echo "13012865 <= ".$this->g_user->decode("13012865")."<Br>";
        echo "78164263 <= ".$this->g_user->decode("78164263")."<Br>";
        echo "62758321 <= ".$this->g_user->decode("62758321")."<Br>";
        echo "54179289 <= ".$this->g_user->decode("54179289")."<Br>";
	
        for ($i=0; $i<100; $i++) {
        	//echo "{$i} => ".$this->g_user->encode($i)."<Br>";
        	if ($i <> $this->g_user->decode($this->g_user->encode($i))) echo "{$i}.gg<br>";
        }
	    //echo $this->decode("3785730")."<br>";
	    
        echo $this->g_user->uid." => ".$this->g_user->euid;
	}
	
	//補轉點數用
	function transfer()
	{
		$account 		= '6520@omg';
		$server_id 	= '67'; //sg_02:59, sg2_03:67,
		$billing_id 	= '328050'; 
		$price 			= '500';
		
		//切至該身份
		if ($this->g_user->switch_account($account)) 
		{
			//獲取資訊
			$this->load->model("games");
			$server = $this->games->get_server($server_id);
			$game = $this->games->get_game($server->game_id);			
			
			if (empty($game) || empty($server)) {
				die('資訊不齊全');
			}
			
			$order = $this->g_wallet->get_order($billing_id);
			
			//執行轉點
			$this->load->library("game_api/sg2");
			var_dump ($this->sg2->transfer($server, $order, $game->exchange_rate));
		}
		else die('角色不存在');
	}	
	
	function run()
	{
		while (empty($data)) {$data = $this->create_account();}
		$this->g_user->verify_account($data['account'], $data['password']);
		header('location: http://'.base_url().'/gate/login_game/sg2');
	}
	
	function create_account()
	{
		$ratio = array(
			'long_e' => 2,
			'facebook' => 40,
			'yahoo' => 25,
			'bahamut' => 5,
			'rc' => 3,
			'minik' => 15,
			'omg' => 10,
		);
		
		$rand = rand(0, 100);
		foreach ($ratio as $channel => $val) {
			$rand -= $val;
			if ($rand < 0) break;
		}
		
		switch ($channel) {
			case 'long_e':
				$os = rand(3000, 150000);
				$row = $this->db->from("users")->where("account not like '%@%'", null, false)->limit(1, $os)->get()->row();
				if (empty($row->account)) return false;
				$account = $row->account.rand(1,99);
				break;
			case 'facebook':				
				$account = '100000'.rand(100000000,599999999).'@facebook'; 
				break;
			case 'yahoo':
				$account = md5(uniqid()).'@yahoo';
				break;
			case 'bahamut':
				$account = md5(uniqid()).'@bahamut';
				break;
			case 'rc':
				$account = md5(uniqid())."@rc";
				break;
			case 'minik':
				$account = substr(md5(uniqid()),0,26)."@minik";
				break;
			case 'omg':
				$account = rand(1000000,5555555).'@omg';
				break;
			default:
				return false;
		}
		$password = $name = $email = 'auto_creation';
		$site = 'sg2';
			
		if ($this->g_user->check_account_exist($account)) {
			return false;
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
			fb($data);
			
			$this->db->insert("users", $data);
			if ( ! $this->db->insert_id()) {
				return false;
			}
			return $data;
		}		
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */