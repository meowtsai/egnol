<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Game extends MY_Controller {

	function __construct()
	{
		parent::__construct();

		error_reporting(E_ALL);
		ini_set('display_errors','On');
	}

	function _init_game_layout()
	{
		$this->zacl->check_login(true);
		if ($this->zacl->check_acl("all_game", "all") == false) {
			if ($this->game_id) $this->zacl->check($this->game_id, "read");
		}

		return $this->_init_layout()
			->add_breadcrumb("遊戲", "game");
	}

	function index()
	{
		$this->zacl->check("game_setting", "read");

		$query = $this->DB2->from("games")->order_by("rank")->get();

		//"<?="http://test-payment.longeplay.com.tw:3000/ma71gmtools?admin_uid=21&admin_name=mepw&time=1500255122&token=123445")
		$gm_site = "http://test-payment.longeplay.com.tw:5000";
		$admin_uid = $_SESSION["admin_uid"];
		$admin_name = $_SESSION["admin_name"];
		$secret = '69i57j0l5.1287j0j4';
		$myTimestamp = time();

    $myToken = md5($admin_uid.$admin_name.$myTimestamp.$secret) ;

		///gm工具的url生成
		$gm_tools_data = array(
			"Ma71tw" => "{$gm_site}/ma71gmtools?admin_uid={$admin_uid}&admin_name={$admin_name}&time={$myTimestamp}&token={$myToken}",
		);

		$this->_init_game_layout()
			->set("query", $query)
			->set("gm_tools_data", $gm_tools_data)
			->render();
	}

	function sort()
	{
		$query = $this->DB2->from("games")->order_by("rank")->get();
		$this->_init_game_layout()
			->add_breadcrumb("排序")
			->set("query", $query)
			->render();
	}

	function save_sort()
	{
		if ($data = $this->input->post("data")) {
			$arr = explode(",", $data);
			$i = 1;
			foreach($arr as $game) {
				$this->DB1->query("update games set rank=".($i++)." where game_id=".$this->DB1->escape($game)."; ");
			}
			die(json_success());
		}
		else die(json_failure("缺少參數"));
	}

	function add()
	{
		$this->zacl->check("game_setting", "modify");

		//$this->_chk_game_id();

		$this->_init_layout();
		$this->g_layout
			->add_breadcrumb("遊戲管理", "game")
			->add_breadcrumb("新增遊戲")
			->set("row", false)
			->set("theme_list", $this->DB2->get("themes"))
			->render("game/modify");
	}
	function test_upload()
	{
		$this->_init_game_layout();
		if ($post = $this->input->post())
		{
			if ( ! empty($_FILES["file04"]['name']))
			{
				$this->load->library('upload', array("upload_path"=>realpath("p/upload/pictures"), "allowed_types"=>"gif|jpg|jpeg|png", 'encrypt_name'=>TRUE));

				if ( ! $this->upload->do_upload())
				{
					echo json_failure($this->upload->display_errors());
					return;
				}
				else
				{
					//rsync_to_slave();
					$data = $this->upload->data();
					$logo_path = site_url("p/upload/pictures/{$data['file_name']}");
				}
			}
			else {
				$logo_path = $this->input->post("logo_path");
			}

			$logo_path = str_replace("https://manager.longeplay.com.tw", "https://game.longeplay.com.tw", $logo_path);

		}
		$this->g_layout->render();


	}

	function modify()
	{
		$this->_init_game_layout();
		$this->zacl->check("game_setting", "modify");

		if ($post = $this->input->post())
		{
			$msg = array();

			if ($this->input->post("tags")) {
				$tags = implode(",", $this->input->post("tags")).",".$this->input->post("type");
			}
			else {
				$tags = $this->input->post("type");
			}



			// $this->load->library('upload');
			// $config['upload_path'] = g_conf("http_document_root")."long_e/p/img/game/";
			// $config['allowed_types'] = 'gif|jpg|jpeg|png';
			// $config['max_size']	= '600';
			// $config['max_width'] = '2048';
			// $config['max_height'] = '768';
			// $config['overwrite'] = true;

			// $upload_cnt = 0;
			// if ( ! empty($_FILES["file01"]["name"])) {
			// 	$config['file_name'] = "{$this->game_id}_01";
			// 	$this->upload->initialize($config);
			// 	if ( ! $this->upload->do_upload("file01"))
			// 	{
			// 		$msg[] = $this->upload->display_errors('', '');
			// 	}
			// 	else
			// 	{
			// 		$upload_cnt++;
			// 	}
			// }
			$bg_path="";
			if ( ! empty($_FILES["file01"]['name']))
			{
				$this->load->library('upload', array("upload_path"=>realpath("p/upload/pictures"), "allowed_types"=>"gif|jpg|jpeg|png", 'encrypt_name'=>TRUE));

				if ( ! $this->upload->do_upload("file01"))
				{
					$msg[] = $this->upload->display_errors('', '');
				}
				else
				{
					//rsync_to_slave();
					$upload_data = $this->upload->data();
					$bg_path = site_url("p/upload/pictures/{$upload_data['file_name']}");
				}
			}
			else {
				$bg_path = $this->input->post("bg_path");
			}

			$bg_path = str_replace("https://manager.longeplay.com.tw", "https://game.longeplay.com.tw", $bg_path);

			$logo_path="";

			if ( ! empty($_FILES["file04"]['name']))
			{
				$this->load->library('upload', array("upload_path"=>realpath("p/upload/pictures"), "allowed_types"=>"gif|jpg|jpeg|png", 'encrypt_name'=>TRUE));

				if ( ! $this->upload->do_upload("file04"))
				{
					$msg[] = $this->upload->display_errors('', '');
				}
				else
				{
					//rsync_to_slave();
					$upload_data = $this->upload->data();
					$logo_path = site_url("p/upload/pictures/{$upload_data['file_name']}");
				}
			}
			else {
				$logo_path = $this->input->post("logo_path");
			}

			$logo_path = str_replace("https://manager.longeplay.com.tw", "https://game.longeplay.com.tw", $logo_path);

			$data = array(
				"name" => $this->input->post("name"),
				"abbr" => $this->input->post("abbr"),
				"exchange_rate" => $this->input->post("exchange_rate"),
				"currency" => $this->input->post("currency"),
				"is_active" => $this->input->post("is_active"),
				"tags" => $tags,
				'theme_id' => $this->input->post("game_theme"),
				'fanpage' => $this->input->post("fanpage"),
				'site' => $this->input->post("site"),
				'logo_path'=> $logo_path,
				'bg_path'=> $bg_path,
				'slogan' => $this->input->post("slogan"),
			);

			if ($id = $this->input->post("id")) { //修改
				$this->DB1->where("game_id", $id)->update("games", $data);
			} else { //新增
				$data["game_id"] = $this->input->post("game_id");
				$data["vendor_game_id"] = $this->input->post("game_id");
				$this->DB1->insert("games", $data);
			}



			if ($this->DB1->affected_rows()==0) $msg[] = '資料無變更';

			$this->g_layout->set("msg", $msg);
		}

		($this->input->post("game_id"))?$show_game_id=$this->input->post("game_id"):$show_game_id=$this->game_id;

		if ($show_game_id) {
		    $query = $this->DB2->get_where("games", array("game_id" => $show_game_id));

			if ($query->num_rows() == 0) {
				die("無此遊戲");
			}
			else {
				$row = $query->row();
			}
		} else {
			$row = "";
		}

		$this->g_layout
			->add_breadcrumb("修改")
			->set("row", $row)
			->set("theme_list", $this->DB2->get("themes"))
			->render();
	}

	function statistics()
	{
		$this->_init_game_layout();
		$this->load->helper("output_table");

		$this->zacl->check("game_statistics", "read");

		if ($this->input->get("action"))
		{
			$date_field = "";

			switch ($this->input->get("action"))
			{
				case "流失率統計":

					$start_date = $this->input->get("start_date") ? $this->input->get("start_date") : date("Y-m-d");
					$end_date = $this->input->get("end_date") ? $this->input->get("end_date") : date("Y-m-d");

					$where = " where 1=1";

					if ($server = $this->input->get("server")) {
						$where .= " and gi.server_id={$server} ";
					} else $where .= " and gi.game_id='{$this->game_id}' ";

					if ($channel = $this->input->get("channel")) {
						if ($channel == 'long_e') $where .= " and lgl.account not like '%@%' ";
						else {
							$where .= " and lgl.account like '%@{$channel}' ";
						}
					}

					if ($ad_channel = $this->input->get("ad_channel")) {
						$where .= " and lgl.ad like '{$ad_channel}%' ";
					}

					$query = $this->DB2->query("

select d, count(*) 'login_cnt', sum(role) 'role_cnt',
	round(1-sum(role)/count(*), 3) 'role_p',
	sum(role)-sum(c1) 'c1',
	round((sum(role)-sum(c1)) / sum(role), 3) 'c1_p',
	sum(role)-sum(c7) 'c7',
	round((sum(role)-sum(c7)) / sum(role), 3) 'c7_p',
	sum(role)-sum(c15) 'c15',
	round((sum(role)-sum(c15)) / sum(role), 3) 'c15_p',
	sum(role)-sum(c30) 'c30',
	round((sum(role)-sum(c30)) / sum(role), 3) 'c30_p'
from
(
	SELECT
		date(lgl.create_time) 'd', lgl.uid, lgl.server_id, gsr.name,
		if (gsr.id is null, '0', '1') 'role',
		if (gsr.id is null, '0', (select if(count(*)>0, 1, 0) from log_game_logins
			where uid=gsr.uid and server_id=gsr.server_id
				and create_time >= date_add(date(gsr.create_time), interval 1 day))
		) 'c1',
		if (gsr.id is null, '0', (select if(count(*)>0, 1, 0) from log_game_logins
			where uid=gsr.uid and server_id=gsr.server_id
				and create_time >= date_add(date(gsr.create_time), interval 6 day))
		) 'c7',
		if (gsr.id is null, '0', (select if(count(*)>0, 1, 0) from log_game_logins
			where uid=gsr.uid and server_id=gsr.server_id
				and create_time >= date_add(date(gsr.create_time), interval 14 day))
		) 'c15',
		if (gsr.id is null, '0', (select if(count(*)>0, 1, 0) from log_game_logins
			where uid=gsr.uid and server_id=gsr.server_id
				and create_time >= date_add(date(gsr.create_time), interval 29 day))
		) 'c30'
	FROM log_game_logins lgl
	join servers gi on lgl.server_id=gi.server_id
	left join characters gsr on date(gsr.create_time)=date(lgl.create_time) and gsr.uid=lgl.uid and gsr.server_id=lgl.server_id
	{$where}
	and lgl.create_time between '{$start_date}' and '{$end_date} 23:59:59'
	and not exists(select * from characters where
		create_time < lgl.create_time and uid=lgl.uid and server_id=lgl.server_id
	)
	group by date(lgl.create_time), lgl.uid, lgl.server_id
) tmp
group by d
					");
					break;

				case "廣告時段統計":
					$query = $this->DB2->select("ga.ad, LEFT(ga.create_time, {$len}) time, COUNT(*) cnt", false)
						->from('characters ga')
						->join("servers gi", "ga.server_id=gi.server_id")
						->group_by("time, ga.ad")
						->order_by("time desc, ga.ad")->get();
					//die($this->DB2->last_query());
					break;
			}

			if (empty($query)) {
				if ($this->input->get("start_date")) {
					$start_date = $this->DB2->escape($this->input->get("start_date"));
					if ($this->input->get("end_date")) {
						$end_date = $this->DB2->escape($this->input->get("end_date").":59");
						$this->DB2->where("{$date_field} between {$start_date} and {$end_date}", null, false);
					}
					else $this->DB2->where("{$date_field} >= {$start_date}", null, false);
				}

				$query = $this->DB2->get();
			}
		}
		else {
			$default_value = array(
				'use_default' => true,
				'time_unit' => 'day',
				'start_date' => date("Y-m-d"),
				'single_date' => date("Y-m-d"),
			);
			$_GET = $default_value;
		}

		$this->g_layout
			->set("query", isset($query) ? $query : false)
			->set("servers", $this->DB2->where("game_id", $this->game_id)->from("servers")->order_by("server_id")->get())
			->add_js_include("game/statistics")
			->add_js_include("jquery-ui-timepicker-addon")
			->render();
	}



	function service_test_internal()
	{
		$this->zacl->check("game_setting", "read");

		$query = $this->DB2->where("is_active", "1")->from("games")->order_by("rank")->get();
		$this->_init_game_layout()
			->set("query", $query)
			->add_js_include("game/md5")
			->add_js_include("game/service_test_internal")
			->render();
	}


}

/* End of file search.php */
/* Location: ./application/controllers/search.php */
