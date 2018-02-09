<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Gameop extends MY_Controller {

	function __construct()
	{
		parent::__construct();

		//error_reporting(E_ALL);
		//ini_set('display_errors','Off');
	}


  function m71_gm_command()
  {
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST');
    header("Access-Control-Allow-Headers: content-type");
    header('Content-Type: application/json');

    $data = file_get_contents("php://input");
		$action = json_decode($data)->{'action'};
		$admin_uid = json_decode($data)->{'admin_uid'};

    if ($data)
    {
      //echo "<pre>$data</pre>";
     $ch = curl_init();
     curl_setopt($ch, CURLOPT_URL,'https://ma71na-public.longeplay.com.tw/main56.php/netease');
     curl_setopt($ch, CURLOPT_POST, TRUE);
     curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
     $server_output = curl_exec ($ch);
     curl_close ($ch);
		 //(isset($_SESSION["admin_uid"])?"meow":$_SESSION["admin_uid"])
		 $log_data = array(
			 "admin_uid" => $admin_uid,
			 "ip" => $_SERVER['REMOTE_ADDR'],
			 "action" => $action,
			 "desc" => "input:{$data}; output:{$server_output}" ,
			 "create_time" => now(),
		 );

		 $this->DB1->insert("log_gm_actions", $log_data);

     die($server_output);



    }
    else {
      $error_response = array(
  			'error' => 'no data',
  		);
      die(json_encode($error_response));
    }
    // // $default_value = array(
    // //   'controller' => gm,
    // //   'action' => 'getroleinfo',
    // //   'playerid' => 15,
    // // );
    //

    // }
    // else {
    //
    // }
  }

}
