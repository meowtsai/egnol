<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class RespObj
{
    public $status;
    public $message;
    public function __construct($status, $message)
    {

        $this->status = $status;
        $this->message = $message;

    }
}

class User_complaint extends MY_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->config("service");
	}

  //
  // 名稱	說明	型態	範例
  // game_id	遊戲id	string	“h35naxx1hmt”
  // reporter_uid	舉報人帳號id	Int64	“15501641”
  // reporter_char_id	舉報人在遊戲中的唯一id	Int64	“119178”
  // reporter_name	舉報人遊戲角色名稱	string	“喵捲”
  // flagged_player_uid	被舉報人帳號id	Int64	“20008”
  // flagged_player_char_id	被舉報人遊戲中唯一id	Int64	“13982409”
  // flagged_player_name	被舉報人遊戲角色名稱	string	“狼者知乎”
  // reason	玩家自填舉報原因	string	“打boss用跟隨”
  // server_name	伺服器名稱	string	“黎明誓約”
  // category	1.	言行不雅
  // 2.	暱稱不雅
  // 3.	使用外掛
  // 4.	利用bug
  // 5.	線下交易
  // 6.	欺詐行為7.	其他	string	“5”
  // token	md5(game_id+ reporter_uid + flagged_player_uid+key) 	string	“dadasdasdsdcxczxczxc”
  //
  //


	function report()
	{

		define('FAIL', 'error');
		define('SUCCESS', 'ok');

		//define('ERROR_MISSING_PARAM', '遺漏必要參數。');
		//define('ERROR_SPAM', '重複發送。');
		//define('ERROR_LIMIT_EXCEEDED', '達到每日上限。');
		//define('ERROR_VALID_FAIL', '驗證失敗。');

		define('ERROR_MISSING_PARAM', 'ERROR_MISSING_PARAM');
		define('ERROR_SPAM', 'ERROR_SPAM');
		define('ERROR_LIMIT_EXCEEDED', 'ERROR_LIMIT_EXCEEDED');
		define('ERROR_VALID_FAIL', 'ERROR_VALID_FAIL');


		define('DAILY_LIMIT', 24);


//http://test-payment.longeplay.com.tw/user_complaint/?game_id=h35naxx1hmt&reporter_uid=15501641&reporter_char_id=119178&
//reporter_name=%E5%96%B5%E6%8D%B2&flagged_player_uid=20008&flagged_player_char_id=13982409&flagged_player_name=%E7%8B%BC%E8%80%85%E7%9F%A5%E4%B9%8E&category=1&description=sadsdas&token=asdasdasdsd

		$vendor_game_id = $this->input->get_post("game_id");
    $reporter_uid    = $this->input->get_post("reporter_uid");
    $reporter_char_id	    = $this->input->get_post("reporter_char_id");
    $reporter_name	= $this->input->get_post("reporter_name");
    $flagged_player_uid	= $this->input->get_post("flagged_player_uid");
    $flagged_player_char_id	= $this->input->get_post("flagged_player_char_id");
    $flagged_player_name	= $this->input->get_post("flagged_player_name");
    $reason	= $this->input->get_post("reason");
    $server_name	= $this->input->get_post("server_name");
    $category	= $this->input->get_post("category");
    $token	= $this->input->get_post("token");

		$secret_key	= $this->config->item("question_key")[$vendor_game_id];
		//$secret_key	= 'abcde';


		$RespObj = new RespObj(constant('FAIL'),"");


		//TODO: check if value exist
		if ( empty($vendor_game_id) || empty($reporter_uid) ||
				 empty($reporter_char_id) || empty($reporter_name) ||
				 empty($flagged_player_uid) || empty($flagged_player_char_id) ||
				 empty($flagged_player_name) ||
				 empty($server_name) || empty($category) ||
				 empty($token) )
		{
			$RespObj->status=constant('FAIL');
			$RespObj->message=constant('ERROR_MISSING_PARAM');
			die(json_encode($RespObj));
		}


    //TODO: check token validatity
		$str_to_encrypt =  $vendor_game_id.$reporter_uid.$flagged_player_uid.$secret_key;
		$sig = MD5($str_to_encrypt);
		// echo $str_to_encrypt;
		// echo "<br />";
		// echo $sig;
		// echo "<br />";

		//if (1===1)
    if ($token===$sig)
		{
			$game_info = $this->db->from("games")->where("vendor_game_id", $vendor_game_id)->get()->row();
			$server_info = $this->db->from("servers")->where("game_id", $game_info->game_id)->where("address", $server_name)->get()->row();


			//TODO: block report in less than one minute
			$query = $this->db->query("SELECT count(*) > (3-3) as chk FROM complaints WHERE game_id='{$game_info->game_id}' and server_id='{$server_info->server_id}' and  reporter_char_id={$reporter_char_id} and  flagged_player_char_id={$flagged_player_char_id} and create_time > date_sub(now(), INTERVAL 1 HOUR)");
			if ($query->row()->chk)
			{
				$RespObj->status=constant('FAIL');
				$RespObj->message=constant('ERROR_SPAM');
				die(json_encode($RespObj));
			}



			//TODO: check if already exceeded
			$count_report = $this->db->from("complaints")
			->where("game_id", $game_info->game_id)
			->where("server_id", $server_info->server_id)
			->where("reporter_char_id", $reporter_char_id)
      ->where("flagged_player_char_id", $flagged_player_char_id)
      ->where("DATE(create_time) = CURDATE()")
      ->count_all_results();

			//echo $count_report;
			//echo constant('DAILY_LIMIT');

			if ($count_report > constant('DAILY_LIMIT')-1)
			{
				$RespObj->status=constant('FAIL');
				$RespObj->message=constant('ERROR_LIMIT_EXCEEDED');

				die(json_encode($RespObj));
			}








			//TODO: Insert or Update character data

			//print_r($game_info);

			///#### Begin Insert character info
			if (!empty($server_name) && !empty($reporter_name)) {

					//print_r($server_info);
					$character_info = $this->db->from("characters")->where("server_id", $server_info->server_id)->where("in_game_id", $reporter_char_id)->get()->row();
					//print_r($character_info);
					//if (isset($server_info->server_id)) $_SESSION['server_id'] = $server_info->server_id;
					if (isset($server_info->server_id) && !isset($character_info->id)) {

						$this->load->model("g_characters");
						$insert_id = $this->g_characters->create_character($server_info,
									array(
											"uid" => 0,
											'partner_uid' => $reporter_uid,
											'name' => $reporter_name,
											'in_game_id' => $reporter_char_id
									));
					} else if ($character_info->name <> $character_name) {

					$this->load->model("g_characters");
							$affected_rows = $this->g_characters->update_character(
									array(
											'name' => $reporter_name
									),
									array(
											'server_id' => $server_info->server_id,
											'in_game_id' => $reporter_char_id
									));
					}
			}

			///#### END Insert character info



      //TODO: Insert a complaint

			$data = array(
				"game_id" => $vendor_game_id,
				"server_id" =>  $server_info->server_id,
				"reporter_uid" => $reporter_uid,
				"reporter_char_id" => $reporter_char_id,
				"reporter_name" => htmlspecialchars($reporter_name),
				"flagged_player_uid" => $flagged_player_uid,
				"flagged_player_char_id" => $flagged_player_char_id,
				"flagged_player_name" => htmlspecialchars($flagged_player_name),
				"category" => $category,
				"reason" => htmlspecialchars($reason),
			);

			$this->db
				->set("create_time", "now()", false)
				->set("update_time", "now()", false)
				->insert("complaints", $data);


      //TODO: Echo response
			$RespObj->status=constant('SUCCESS');
			$RespObj->message="";
			die(json_encode($RespObj));
		}
		else {
			$RespObj->status=constant('FAIL');
			$RespObj->message=constant('ERROR_VALID_FAIL');
			die(json_encode($RespObj));
		}

	}


function demo()
{

	$this->_init_layout("客服中心")
		->add_css_link("login")
		->add_css_link("server")
		->mobile_view();


}


}
