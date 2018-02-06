<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Gameop extends MY_Controller {

	function __construct()
	{
		parent::__construct();

		//error_reporting(E_ALL);
		//ini_set('display_errors','Off');
	}

	function getroleinfo()
	{
    $ch = curl_init();
    $default_value = array(
      'controller' => gm,
      'action' => 'getroleinfo',
      'playerid' => 15,
    );

    curl_setopt($ch, CURLOPT_URL,'https://ma71na-public.longeplay.com.tw/main56.php/netease');
    curl_setopt($ch, CURLOPT_POST, TRUE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($default_value));

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $server_output = curl_exec ($ch);

    curl_close ($ch);
    echo "<pre>$server_output</pre>";

	}

  function m71_gm_command()
  {
    header('Access-Control-Allow-Origin: *');


    $data = file_get_contents("php://input");
    if ($data)
    {
      //echo "<pre>$data</pre>";
    $ch = curl_init();
    // $default_value = array(
    //   'controller' => gm,
    //   'action' => 'getroleinfo',
    //   'playerid' => 15,
    // );

    curl_setopt($ch, CURLOPT_URL,'https://ma71na-public.longeplay.com.tw/main56.php/netease');
    curl_setopt($ch, CURLOPT_POST, TRUE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $server_output = curl_exec ($ch);

    curl_close ($ch);
    echo "$server_output";
    }
  }

}
