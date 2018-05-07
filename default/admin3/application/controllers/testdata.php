<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Testdata extends CI_Controller {

	//function __construct()
	//{
	//	parent::__construct();
	//}
	function evil_laugh()
	{
		echo "MUHUAHAA";
	}

	function free_user_billing_note()
	{
		ini_set("memory_limit","2048M");
		ini_set('max_execution_time', 9999);
		$this->lang->load('db_lang', 'zh-TW');

		$query = $this->db->get("user_billing");

		if ($query->num_rows() > 0) {
		    foreach ($query->result() as $row) {
				if ($row->note) {
					$note = explode("|", $row->note);

					$data = array(
						'product_id'  => $note[0],
						'verify_code' => $note[1]
					);

					$this->db->where("id", $row->id)->update("user_billing", $data);
				}
			}
		}
	}

	function generate_logout_time()
	{
		ini_set('max_execution_time', 9999);
		$this->lang->load('db_lang', 'zh-TW');

		$query = $this->db->get("log_game_logins");

		if ($query->num_rows() > 0) {
		    foreach ($query->result() as $row) {
				$rand = rand(100, 10000);

				$gen_logout_time = date('Y-m-d H:i:s', strtotime($row->create_time) + $rand);

			    $data = array(
				    'logout_time' => $gen_logout_time
			    );

				$this->db->where("id", $row->id)->update("log_game_logins", $data);
			}
		}
	}

	function generate_consume()
	{
		ini_set('max_execution_time', 9999);
		$this->lang->load('db_lang', 'zh-TW');

		$query = $this->db->get("log_game_logins");

		if ($query->num_rows() > 0) {
		    foreach ($query->result() as $row) {
				$rand = rand(1, 5);

				if ($rand == 5) {
					$rand_amount = rand(1, 10)*rand(10, 100);
					$rand_time = rand(100, 1000);
				    $gen_time = date('Y-m-d H:i:s', strtotime($row->create_time) + $rand_time);

			        $data = array(
				        'uid' => $row->uid,
					    'server_id' => $row->server_id,
					    'game_id' => $row->game_id,
					    'account' => $row->account,
					    'ip' => $row->ip,
					    'amount' => $rand_amount,
				        'create_time' => $gen_time
			        );

				    $this->db->insert("log_game_consumes", $data);
			    }
			}
		}
	}

	function unify_server_id()
	{
		ini_set('max_execution_time', 9999);
		$this->lang->load('db_lang', 'zh-TW');

		$query = $this->db->get("servers");

		if ($query->num_rows() > 0) {
		    foreach ($query->result() as $row) {

			    $data = array(
				    'server_id' => $row->server_id
			    );

				$this->db->where("server_id", $row->id)->update("log_game_consumes", $data);
			}
		}
	}

	function generate_country_code()
	{
		ini_set('max_execution_time', 9999);
		$this->lang->load('db_lang', 'zh-TW');

		$query = $this->db->get("user_billing");

		if ($query->num_rows() > 0) {
		    foreach ($query->result() as $row) {
				if ($row->ip) {
				    $country_code = geoip_country_code3_by_name($row->ip);

			        $data = array(
				        'country_code' => $country_code
			        );

				    $this->db->where("id", $row->id)->update("user_billing", $data);
				}
			}
		}
	}

	function generate_log_user_updates()
	{
		ini_set('max_execution_time', 9999);
		$this->lang->load('db_lang', 'zh-TW');

		$query = $this->db->get("users");

		if ($query->num_rows() > 0) {
		    foreach ($query->result() as $row) {

                $log_content = ($row->external_id)?"NEW: [external_id]".$row->external_id:"NEW: [email]".$row->email." [mobile]".$row->mobile;

				$log_data = array(
                            "uid" => $row->uid,
                            "content" => $log_content
                        );

                $this->db->insert("log_user_updates", $log_data);
			}
		}
	}


	function test_ip()
	{
// 		等級=26, 系統=ASUS_X00DD, os=7.1.1, app_ver=3.3.0,time_zone=,network=0| IP=49.216.146.77, 國家=Taiwan          |
// | 等級=17, 系統=iPhone7,1, os=10.30, app_ver=3.1.0.126,time_zone=,network=1| IP=39.12.162.66, 國家=Taiwan        |
// | 等級=21, 系統=SM-N9500, os=7.1.1, app_ver=3.3.0,time_zone=,network=1| IP=45.64.243.26, 國家=Hong Kong          |
// | 等級=5, 系統=, os=8.0.0, app_ver=3.3.0,time_zone=,network=0| IP=61.231.137.51, 國家=Taiwan                     |
// | 等級=10, 系統=iPhone8,2, os=10.30, app_ver=3.1.0.126,time_zone=,network=1| IP=42.76.217.61, 國家=Taiwan
		$note = "等級=21, 系統=SM-N9500, os=7.1.1, app_ver=3.3.0,time_zone=,network=1| IP=45.64.243.26, 國家=Hong Kong";
		echo "note ".$note;
		echo "<br />";
		$found_ip = strpos($note,"IP=");
		echo "hello ".$found_ip;
		echo "<br />";
		$found_endofip = strpos($note,",",$found_ip);
		echo "hello ".$found_endofip;
		echo "<br />";
		echo "IP 長度 ". $found_endofip - ($found_ip+2) ;
		echo "<br />";
		echo substr($note,$found_ip+3, $found_endofip - ($found_ip+3));
		echo "<br />";

		echo substr($note,0,5);

	}
}

/* End of file search.php */
/* Location: ./application/controllers/search.php */
