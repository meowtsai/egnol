<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Service extends MY_Controller {

	function __construct()
	{
		parent::__construct();
		$this->zacl->check_login(true);
		$this->zacl->check("service", "read");

		$this->load->config("g_service");
	}

	function _init_service_layout()
	{
		return $this->_init_layout()->add_breadcrumb("客服", "service");
	}

	function todo()
	{
		$_GET['action'] = '查詢';
		$_GET['todo'] = '1';

		header("refresh:30");
		$this->router->method = 'get_list';
		$this->get_list();
	}

	function my()
	{
		$_GET['action'] = '查詢';
		$_GET['allocate_status'] = '1';
		$_GET['allocate_auid'] = $this->input->get("allocate_auid") ? $this->input->get("allocate_auid") : $_SESSION['admin_uid'];

		$this->router->method = 'get_list';
		$this->get_list();
	}
	function my_favorite()
	{
		$this->zacl->check_acl("service", "favorite");
		$_GET['action'] = '查詢';
		$_GET['favorite'] = '1';


		$this->router->method = 'get_list';
		$this->get_list();
	}

	function daily_report()
	{

		$question_type = $this->config->item('question_type');
		$question_status = $this->config->item('question_status');

		$this->_init_service_layout();
		$report_result = [];
		if ($this->input->get("action"))
		{
			header("Cache-Control: private");
			$this->DB2->start_cache();
			if ($this->input->get("start_date")) {
				$start_date = $this->DB2->escape($this->input->get("start_date"));
				if ($this->input->get("end_date")) {
					$end_date = $this->DB2->escape($this->input->get("end_date").":59");
				}

			}

			//echo '$start_date='.$start_date;
			//echo '$end_date='.$end_date;



			switch ($this->input->get("action"))
			{
				case "查詢":
					$report_result = $this->DB2->query("SELECT g.name as game_name, id, create_time, type, gi.name as server_name, character_name, partner_uid,
									 (SELECT in_game_id from characters where partner_uid =q.partner_uid and server_id=q.server_id and name=q.character_name) as gid,
									 phone, email, content, status, adm.name as adm_username, update_time,
									 CASE WHEN (pic_path1 is not null OR pic_path2 is not null OR pic_path3 is not null) THEN 'Y' ELSE 'N' END as has_pic
									  FROM questions q LEFT JOIN servers gi
									  ON gi.server_id=q.server_id
									  LEFT JOIN games g on g.game_id=gi.game_id
									  LEFT JOIN admin_users adm on adm.uid=q.admin_uid
									  WHERE  create_time BETWEEN {$start_date} AND {$end_date}")->result();
					break;

					case "輸出":
						ini_set("memory_limit","2048M");

						$report_result = $this->DB2->query("SELECT g.name as game_name, id, create_time, type, gi.name as server_name, character_name, partner_uid,
										 (SELECT in_game_id from characters where partner_uid =q.partner_uid and server_id=q.server_id and name=q.character_name) as gid,
										 phone, email, content, status, adm.name as adm_username, update_time,
										 CASE WHEN (pic_path1 is not null OR pic_path2 is not null OR pic_path3 is not null) THEN 'Y' ELSE 'N' END as has_pic
										  FROM questions q LEFT JOIN servers gi
										  ON gi.server_id=q.server_id
										  LEFT JOIN games g on g.game_id=gi.game_id
										  LEFT JOIN admin_users adm on adm.uid=q.admin_uid
										  WHERE  create_time BETWEEN  {$start_date} AND {$end_date} ")->result();

						$filename = "output.csv";
						header("Content-type:application/vnd.ms-excel;");
						header("Content-Disposition: filename={$filename};");

						$content = "遊戲,	提問單號,	進件時間,	提問單類型,	伺服器,	暱稱,	原廠UID,	角色 GID,	手機,	EMAIL,	玩家提問,	狀態,	回覆人員,	回覆時間,	圖片\n";
						foreach($report_result as $row) {
							$content .= "{$row->game_name},{$row->id},{$row->create_time},";
							$content .= "{$question_type[$row->type]},{$row->server_name},{$row->character_name},";
							$content .= "{$row->partner_uid},{$row->gid},";
							$content .= ($row->phone==="0"?",": " \"{$row->phone}\" ,");
							$content .= ($row->email==="0"?",":"{$row->email},");
							$content .= "\"{$row->content}\",{$question_status[$row->status]},";
							$content .= "{$row->adm_username},{$row->update_time},{$row->has_pic},";

							$content .= "\n";
						}

						echo iconv('utf-8', 'big5//TRANSLIT//IGNORE', $content);
						exit();
						break;
			}

			$this->DB2->stop_cache();
			$this->DB2->flush_cache();

		}
		else {

			$default_value = array(
				'use_default' => true,
				'start_date' => date('Y-m-d',(strtotime ( '-1 day' , strtotime ( date('Y-m-d')) ) ))." 00:00",
				'end_date' => date('Y-m-d',(strtotime ( '-1 day' , strtotime (date('Y-m-d')) ) ))." 23:59",
			);
			$_GET = $default_value;
		}

		$myFields = ['遊戲',	'提問單號',	'進件時間',	'提問單類型',	'伺服器',	'暱稱',	'原廠UID',	'角色 GID',	'手機',	'EMAIL',	'玩家提問',	'狀態',	'回覆人員',	'回覆時間',	'圖片'];
		$this->g_layout
			->add_breadcrumb("每日進件數據")
			->set("fields", $myFields)
			->set("report_result", $report_result)
			->add_js_include("service/get_list")
			->add_js_include("jquery-ui-timepicker-addon")
			->render();
	}


	function question_assign($type='')
	{
		$this->_init_service_layout();

		header("Cache-Control: private");

		$this->DB2->start_cache();

		$this->DB2
			->from("question_assigns dlv")
			->join("admin_users au", "au.uid=dlv.admin_uid", "left");

		if ($type == 'not') {
			$this->DB2->where("id in (select question_assign_id from question_assignees where admin_uid=".$_SESSION['admin_uid']." and `is_read`=0)", null, false);
		}

		$this->input->get("source") && $this->DB2->where("dlv.source", $this->input->get("source"));
		$this->input->get("admin_uid") && $this->DB2->where("dlv.admin_uid", $this->input->get("admin_uid"));
		$this->input->get("desc") && $this->DB2->like("dlv.desc", $this->input->get("desc"));
		$this->input->get("result") && $this->DB2->like("dlv.result", $this->input->get("result"));
		$this->input->get("status") && $this->DB2->like("dlv.status", $this->input->get("status"));

		if ($date = $this->input->get("date")) {
			$this->DB2->where("dlv.create_time between '{$date} 00:00:00' and '{$date} 23:59:59'", null, false);
		}

		$this->DB2->stop_cache();

		$total_rows = $this->DB2->count_all_results();

		$query = $this->DB2->limit(10, $this->input->get("record"))->get();

		$get = $this->input->get();
		$query_string = '';
		if ($get) {
			unset($get["record"]);
			$query_string = http_build_query($get);
		}

		$this->load->library('pagination');
		$this->pagination->initialize(array(
				'base_url'	=> site_url("service/question_assign?".$query_string),
				'total_rows'=> $total_rows,
				'per_page'	=> 10
			));

		$this->g_layout->set("total_rows", $total_rows);

		$this->DB2->flush_cache();

		$cs_user = $this->DB2->from("admin_users")->like("role", "cs", "after")->get();

		$this->g_layout
			->add_breadcrumb("交接項目")
			->set("query", isset($query) ? $query : false)
			->set("type", $type)
			->set("cs_user", $cs_user)
			->add_js_include("service/question_assign")
			->add_js_include("jquery-ui-timepicker-addon")
			->render();
	}

	function modify_question_assign($id=0)
	{
		$this->load->library("user_agent");

		$question_assigns = false;
		$target_arr = array();
		if ($id) {
			$question_assigns = $this->DB2->from("question_assigns")->where("id", $id)->get()->row();
			$targets = $this->DB2->from("question_assignees")->where("question_assign_id", $id)->get();
			foreach($targets->result() as $row) {
				$target_arr[] = $row->admin_uid;
			}
		}

		$users = $this->DB2->like("role", "cs", "after")->from("admin_users")->get();

		$this->_init_service_layout()
			->add_breadcrumb(($id?"修改":"新增")."交接事項")
			->add_js_include("service/modify_question_assign")
			->set("question_assigns", $question_assigns)
			->set("back_url", $this->agent->is_referral() ? $this->agent->referrer() : "")
			->set("target_arr", $target_arr)
			->set("users", $users)
			->render();
	}

	function modify_question_assign_json()
	{
		$id = $this->input->post("id");

		$data = array(
			"source" => $this->input->post("source"),
			'desc' => nl2br(htmlspecialchars($this->input->post("desc"))),
			'result' => nl2br(htmlspecialchars($this->input->post("result"))),
			'admin_uid' => $_SESSION['admin_uid'],
			"status" => $this->input->post("status"),
		);

		if ($id) {
			$this->DB1
				->where("id", $id)
				->update("question_assigns", $data);

			if ($this->input->post('targets')) $this->DB1->where("question_assign_id", $id)->delete("question_assignees");
		}
		else {
			$this->DB1
				->set("create_time", "now()", false)
				->set("update_time", "now()", false)
				->insert("question_assigns", $data);
			$id = $this->DB1->insert_id();
		}
		if ($this->input->post('targets')) {
			foreach($this->input->post('targets') as $uid) {
				$this->DB1->insert("question_assignees", array("question_assign_id" => $id, "admin_uid" => $uid));
			}
		}

		die(json_message(array("id"=>$id, "back_url"=>$this->input->post("back_url")), true));
	}

	function delete_question_assign_json()
	{
		$id = $this->input->get("id");

		$this->DB1
			->where("id", $id)
			->where("admin_uid", $_SESSION['admin_uid'])
			->delete("question_assigns");

		if ($this->DB1->affected_rows() > 0) echo json_success();
		else echo json_failure("資料庫刪除失敗或沒有權限".$this->DB1->last_query());
	}

	function read_question_assign_json()
	{
		$id = $this->input->get("id");

		$this->DB1
			->set("read", "1")
			->where("id", $id)
			->where("admin_uid", $_SESSION['admin_uid'])
			->update("question_assignees");

		if ($this->DB1->affected_rows() > 0) echo json_success();
		else echo json_failure();
	}

	function index()
	{
        $where_allow_games = (in_array('all_game', $this->zacl->allow_games))?"":" and s.game_id in ('".implode("','",$this->zacl->allow_games)."')";

		$stat = $this->DB2->query("
			select
				(select count(*)
                    from questions q
                    left join servers s on s.server_id=q.server_id
                    where q.create_time>=CURDATE() {$where_allow_games}) as 'all',
				(select count(*)
                    from questions q
                    left join servers s on s.server_id=q.server_id
                    where q.create_time>=CURDATE()
                    and q.status='1' {$where_allow_games}) as 'new',
				(select count(*)
                    from questions q
                    left join servers s on s.server_id=q.server_id
                    where q.create_time>=CURDATE()
                    and q.status='2' {$where_allow_games}) as 'success',
				(select count(*)
                    from questions q
                    left join servers s on s.server_id=q.server_id
                    where q.create_time>=CURDATE()
                    and q.status='4' {$where_allow_games}) as 'close',
				(select count(*)
                    from questions q
                    left join servers s on s.server_id=q.server_id
                    where q.create_time>=CURDATE()
                    and q.type='9' {$where_allow_games}) as 'phone',
				(select count(*)
                    from questions q
                    left join servers s on s.server_id=q.server_id
                    where q.status='1' {$where_allow_games}) as 'new_total',
				(select count(*)
                    from questions q
                    left join servers s on s.server_id=q.server_id
                    where q.status='2' {$where_allow_games}) as 'success_total',
				(select count(*)
                    from questions q
                    left join servers s on s.server_id=q.server_id
                    where q.status='4' {$where_allow_games}) as 'close_total',
				(select count(*)
                    from questions q
                    left join servers s on s.server_id=q.server_id
                    where q.status='0' {$where_allow_games}) as 'hidden_total',
				(select count(*)
                    from questions q
                    left join servers s on s.server_id=q.server_id
                    where q.type='9' {$where_allow_games}) as 'phone_total'
		")->row();

		$query = $this->DB2->query("
				select q.allocate_status, au.uid, au.name, count(*) cnt from questions q
                left join servers s on s.server_id=q.server_id
				left join admin_users au on au.uid = q.allocate_admin_uid
				where allocate_status in ('1','2') {$where_allow_games}
				group by allocate_status, uid
		");
		$allocate = array();
		foreach($query->result() as $row) {
			$allocate[$row->allocate_status][] = $row;
		}


		 $chart_data = $this->DB2->query("SELECT g.name , count(*)  as value
		 FROM questions q LEFT JOIN servers s ON q.server_id = s.server_id
		 left join games g on g.game_id = s.game_id
		 WHERE s.game_id IS NOT NULL  and q.create_time between CURDATE()-3 and CURDATE()-2 GROUP BY g.name");

		$this->_init_service_layout()
			->set("stat", $stat)
			->set("allocate", $allocate)
			->set("chart_data", $chart_data)
			->add_js_include("d3")
			->render();
	}

	function add()
	{
        if (!in_array('all_game', $this->zacl->allow_games)) $this->DB2->where_in("game_id", $this->zacl->allow_games);
		$games = $this->DB2->from("games")->where("is_active", "1")->get();
		$servers = $this->DB2->where_in("server_status", array("public", "maintenance"))->order_by("server_id")->get("servers");

		$this->_init_service_layout()
			->add_breadcrumb("新增電話案件")
			->add_js_include("service/form")
			->set("games", $games)
			->set("servers", $servers)
			->set("question", false)
			->render("service/form");
	}

	function edit($id)
	{
        if (!in_array('all_game', $this->zacl->allow_games)) $this->DB2->where_in("game_id", $this->zacl->allow_games);
		$games = $this->DB2->from("games")->where("is_active", "1")->get();
		$servers = $this->DB2->where_in("server_status", array("public", "maintenance"))->order_by("server_id")->get("servers");

		$query = $this->DB2->from("questions qt")
			->join("servers gi", "qt.server_id=gi.server_id")
			->where("type", "9")->where("id", $id)->get();
		if ($query->num_rows() == 0) die('無此單號');

		$question = $query->row();

		$this->_init_service_layout()
			->add_breadcrumb("編輯電話案件")
			->add_js_include("service/form")
			->set("games", $games)
			->set("servers", $servers)
			->set("question", $question)
			->render("service/form");
	}

	function modify_json()
	{
		$question_id = $this->input->post("question_id");

		$data = array(
			"uid" => 0,
			"server_id" => $this->input->post("server"),
			'character_name' => htmlspecialchars($this->input->post("character_name")),
			"type" => '9',
			'content' => nl2br(htmlspecialchars($this->input->post("content"))),
			'admin_uid' => $_SESSION['admin_uid'],
			'phone' => $this->input->post("phone"),
			'email' => $this->input->post("email"),
		);

		if ($question_id) {
			$this->DB1
				->where("id", $question_id)
				->update("questions", $data);
		}
		else {
			$this->DB1
				->set("create_time", "now()", false)
				->set("update_time", "now()", false)
				->insert("questions", $data);
			$question_id = $this->DB1->insert_id();
		}

		die(json_message(array("id"=>$question_id), true));
	}

	function get_list()
	{
		$this->_init_service_layout();
		$question_type = $this->config->item('question_type');
		$question_status = $this->config->item('question_status');

		if ($this->input->get("action"))
		{
			header("Cache-Control: private");

			$this->DB2->start_cache();

			$this->input->get("question_id") && $this->DB2->where("q.id", $this->input->get("question_id"));
			//$this->input->get("uid") && $this->DB2->where("q.uid", $this->input->get("uid"));
			$this->input->get("partner_uid") && $this->DB2->where("q.partner_uid", $this->input->get("partner_uid"));
			$this->input->get("status")<>'' && $this->DB2->where("q.status", $this->input->get("status"));
			$this->input->get("type") && $this->DB2->where("q.type", $this->input->get("type"));
			$this->input->get("game") && $this->DB2->where("gi.game_id", $this->input->get("game"));
			$this->input->get("character_name") && $this->DB2->where("q.character_name", $this->input->get("character_name"));
			$this->input->get("check_id") && $this->DB2->where("q.check_id", $this->input->get("check_id"));
			if (!isset($_SESSION['page_size'])) {
				$_SESSION['page_size']=10;
			}
			if ($this->input->get("page_size")) {
				$page_size = $this->input->get("page_size");
				$_SESSION['page_size'] = $page_size;
			}


			if ($this->input->get("email")) {
                //$this->DB2->where("u.email", $this->input->get("email"));
                $this->DB2->where("q.email", $this->input->get("email"));
            }
			if ($this->input->get("mobile")) {
                //$this->DB2->where("u.mobile", $this->input->get("mobile"));
                $this->DB2->where("q.phone", $this->input->get("mobile"));
            }
			$this->input->get("content") && $this->DB2->like("q.content", $this->input->get("content"));

			$this->input->get("allocate_auid") && $this->DB2->where("q.allocate_admin_uid", $this->input->get("allocate_auid"));
			$this->input->get("allocate_status") && $this->DB2->where("q.allocate_status", $this->input->get("allocate_status"));

			$this->input->get("todo") && $this->DB2->where("q.status=1", null, false);

            if (!in_array('all_game', $this->zacl->allow_games)) $this->DB2->where_in("gi.game_id", $this->zacl->allow_games);

			$this->DB2
				//->select("q.*, g.name as game_name, au.name as admin_uname, gi.name as server_name, c.name as in_game_name")
				->select("q.*, g.name as game_name, au.name as admin_uname, gi.name as server_name,")
				//->select("(select name from `characters` where partner_uid=q.partner_uid and server_id=q.server_id and name=q.character_name) as in_game_name")
				//->select("(select sum(amount) from user_billing where uid=q.uid and billing_type=2 and result=1) as expense")
				// ->select("(select case when is_official=1 then CONCAT('官方#' , create_time) when is_official=0 then CONCAT('玩家#' , create_time) 	end as reply_status
				//   from question_replies where question_id =q.id order by id desc limit 1) as reply_status ",FALSE)
				->select("(select count(*) from `question_favorites` where question_id=q.id and admin_uid={$_SESSION['admin_uid']}) as is_favorite",FALSE)
				->from("questions q")
				->join("servers gi", "gi.server_id=q.server_id", "left")
				->join("games g", "g.game_id=gi.game_id", "left")
				//->join("users u", "u.uid=q.uid", "left")
				->join("admin_users au", "au.uid=q.admin_uid", "left");
			    //->join("characters c", "c.partner_uid=q.partner_uid and c.server_id=q.server_id and c.name=q.character_name", "left");

			// if ($this->input->get("account")) {
			// 	$this->DB2->join("users u", "u.uid=q.uid", "left")
			// 		->where("u.email", $this->input->get("account"))
			// 		->or_where("u.mobile", $this->input->get("account"));
			// }

			if ($this->input->get("replies") || $this->input->get("cs_admin") || $this->input->get("reply_start_date") ) {
				$this->DB2->join("question_replies qr", "q.id=qr.question_id", "left");
        if ($this->input->get("replies")) $this->DB2->like("qr.content", $this->input->get("replies"), 'both');
        if ($this->input->get("cs_admin")) $this->DB2->where("qr.admin_uid", $this->input->get("cs_admin"));
				if ($this->input->get("reply_start_date")) {
					$reply_start_date = $this->DB2->escape($this->input->get("reply_start_date"));
					if ($this->input->get("reply_end_date")) {
						$reply_end_date = $this->DB2->escape($this->input->get("reply_end_date").":59");
						$this->DB2->where("qr.create_time between {$reply_start_date} and {$reply_end_date}", null, false);
					}
						else $this->DB2->where("qr.create_time >= {$reply_start_date}", null, false);
					}
			}
			if ($this->input->get("favorite")) {
				$this->DB2->where("q.id in(select question_id from question_favorites where admin_uid={$_SESSION['admin_uid']})", null, false);
			}




			if ($this->input->get("start_date")) {
				$start_date = $this->DB2->escape($this->input->get("start_date"));
				if ($this->input->get("end_date")) {
					$end_date = $this->DB2->escape($this->input->get("end_date").":59");
					$this->DB2->where("q.create_time between {$start_date} and {$end_date}", null, false);
				}
				else $this->DB2->where("q.create_time >= {$start_date}", null, false);
			}




			switch ($this->input->get("action"))
			{
				case "查詢":
					$this->DB2->stop_cache();

					$total_rows = $this->DB2->count_all_results();
					$sort = $this->input->get("sort") ? $this->input->get("sort") : 'id';

					$query = $this->DB2->limit($_SESSION['page_size'], $this->input->get("record"))
								->order_by("{$sort} desc")->get();

					$get = $this->input->get();
					unset($get["record"]);
					$query_string = http_build_query($get);

					$this->load->library('pagination');
					$this->pagination->initialize(array(
							'base_url'	=> site_url("service/get_list?".$query_string),
							'total_rows'=> $total_rows,
							'per_page'	=> $_SESSION['page_size']
						));

					$this->g_layout->set("total_rows", $total_rows);

					break;
				case "輸出":
					ini_set("memory_limit","2048M");
					$sort = $this->input->get("sort") ? $this->input->get("sort") : 'id';
					$query = $this->DB2->order_by("{$sort} desc")->get();

					$filename = "cs_output.csv";
					header("Content-type:application/vnd.ms-excel;");
					header("Content-Disposition: filename={$filename};");

					// ->select("q.*, g.name as game_name, au.name as admin_uname, gi.name as server_name")
					// ->select("(select name from `characters` where partner_uid=q.partner_uid and server_id=q.server_id and name=q.character_name) as in_game_name")
					// ->select("(select sum(amount) from user_billing where uid=q.uid and billing_type=2 and result=1) as expense")

					$content = "編號,遊戲,角色名稱,提問類型,描述,原廠uid,狀態,處理人,日期\n";
					foreach($query->result() as $row) {
						$content .= "{$row->id},{$row->game_name},";
						$content .= "{$row->character_name}($row->server_name)";
						// if (($row->partner_uid && !$row->uid && !$row->in_game_name) || !$row->partner_uid)
						// {
						// 	$content .="(玩家填寫)";
						// }
						if ($row->is_in_game =='0')
						{
							$content .="(玩家填寫)";
						}
						$content .= ",";
						$content .= "{$question_type[$row->type]},";
						//'\"' +  content.replace('\n', '').replace('<br />', ' , ').encode('utf-8') + '\"' ,
						$content .= '"'. strip_tags($row->content).'",';
						$content .= "{$row->partner_uid},";
						$content .= "{$question_status[$row->status]}";

						if ($row->allocate_status == '1'){
							$content .= "(後送中)";
						}
						elseif ($row->allocate_status == '2') {
							$content .= "(後送完成)";
						}
						$content .= ",";
						$content .= "{$row->admin_uname},";
						$content .= date("Y-m-d H:i", strtotime($row->create_time));

						$content .= "\n";




						//$question_type = $this->config->item('question_type');

					}
					echo iconv('utf-8', 'big5//TRANSLIT//IGNORE', $content);
					exit();
					break;
			}

			$this->DB2->stop_cache();
			$this->DB2->flush_cache();

            $q_ids = array();

            if ($query && $query->num_rows() > 0) {

                foreach($query->result() as $row) {
                    $q_ids[] = $row->id;
                }

                $reply_query = $this->DB2
                    ->select("qr.question_id, au.name, count(*) as cnt")
                    ->from("question_replies qr")
                        ->join("admin_users au", "au.uid=qr.admin_uid", "left")
                    ->where_in("qr.question_id", $q_ids)
                    ->where("qr.is_official >", 0)
                    ->group_by(array("qr.question_id", "au.name"))->get();
            }
		}
		else {
			$default_value = array(
				'use_default' => true,
				'start_date' => date('Y-m-d')." 00:00",
			);
			$_GET = $default_value;
		}

        if (!in_array('all_game', $this->zacl->allow_games)) $this->DB2->where_in("game_id", $this->zacl->allow_games);
		$games = $this->DB2->from("games")->get();
		$cs_admins = $this->DB2->from("admin_users")->where_in("role", array("cs", "cs_master","glory_service"))->get();

		$add_favor_ok = $this->zacl->check_acl("service", "favorite") ;

		$this->g_layout
			->add_breadcrumb("查詢")
			->set("query", isset($query) ? $query : false)
			->set("reply_query", isset($reply_query) ? $reply_query : false)
			->set("games", $games)
			->set("cs_admins", $cs_admins)
			->set("todo", $this->input->get("todo"))
			->set("add_favor_ok", $add_favor_ok)
			->add_js_include("service/get_list")
			->add_js_include("jquery-ui-timepicker-addon")
			->add_js_include("fontawesome-all")
			->render();
	}

	function view($id)
	{
		$this->zacl->check("service", "modify");

		$question = $this->DB2->select("q.*, g.name as game_name, gi.name as server_name, u.mobile, u.email user_email, u.external_id, u.uid, au.name allocate_user_name, c.in_game_id, c.name as in_game_name, aux.name close_admin_name")
			->select("(select count(*) from `question_favorites` where question_id={$id} and admin_uid={$_SESSION['admin_uid']}) as is_favorite",FALSE)
			->where("q.id", $id)
			->from("questions q")
			->join("servers gi", "gi.server_id=q.server_id", "left")
			->join("games g", "g.game_id=gi.game_id", "left")
			->join("users u", "u.uid=q.uid", "left")
			->join("admin_users au", "au.uid=q.allocate_admin_uid", "left")
			->join("admin_users aux", "aux.uid=q.close_admin_uid", "left")
			->join("characters c", "c.partner_uid=q.partner_uid and c.server_id=q.server_id and c.name=q.character_name", "left")
			->get()->row();

		// if ($question->status == '1') {
		// 	$this->DB2->where("id", $id)->update("questions", array("is_read"=>'1'));
		// }

		$ip_pos = strrpos($question->note,"IP");
		$ip =  "";
		$endofip = "";
		if ( $ip_pos>-1)
		{
			$endofip = strpos($question->note,",",$ip_pos);
			$ip =substr($question->note,$ip_pos+3, $endofip - ($ip_pos+3));

		}

		$replies = $this->DB2
			->select("qt.*, au.name as admin_uname")
			->from("question_replies qt")
			->join("admin_users au", "au.uid=qt.admin_uid", "left")
			->where("question_id", $id)->order_by("qt.id", "asc")->get();

		$pic_plus = $this->DB2->from("question_pictures")->where("question_id", $id)->order_by("id", "asc")->get();

        $allocate_groups = array('pm', 'admin', 'cs_master', 'pd_chang', 'RC');

		$service_users = $this->DB2->from('admin_permissions')->where('resource', 'service')->order_by("role")->get();

        foreach($service_users->result() as $row) {
            $allocate_groups[] = $row->role;
        }

        $allocate_groups = array_unique($allocate_groups);

		$allocate_users = $this->DB2->from('admin_users')->where_in('role', $allocate_groups)->order_by("role")->get();
		$add_favor_ok = $this->zacl->check_acl("service", "favorite") ;
		$this->_init_service_layout()
			->add_breadcrumb("檢視")
			->add_js_include("service/view")
			->add_js_include("fontawesome-all")
			->set("question", $question)
			->set("replies", $replies)
			->set("pic_plus", $pic_plus)
			->set("allocate_users", $allocate_users)
			->set("add_favor_ok", $add_favor_ok)
			->set("ip", $ip)
			->render();
	}


	function statistics()
	{
		$this->_init_service_layout();

		if ($this->input->get("action"))
		{
			header("Cache-Control: private");

			$this->DB2->start_cache();

			$this->input->get("question_id") && $this->DB2->where("q.id", $this->input->get("question_id"));
			$this->input->get("uid") && $this->DB2->where("q.uid", $this->input->get("uid"));
			$this->input->get("partner_uid") && $this->DB2->where("q.partner_uid", $this->input->get("partner_uid"));
			$this->input->get("status")<>'' && $this->DB2->where("q.status", $this->input->get("status"));
			$this->input->get("type") && $this->DB2->where("q.type", $this->input->get("type"));
			$this->input->get("game") && $this->DB2->where("gi.game_id", $this->input->get("game"));
			$this->input->get("character_name") && $this->DB2->where("q.character_name", $this->input->get("character_name"));
			$this->input->get("email") && $this->DB2->where("u.email", $this->input->get("email"));
			$this->input->get("content") && $this->DB2->like("q.content", $this->input->get("content"));

			$this->input->get("allocate_auid") && $this->DB2->where("q.allocate_admin_uid", $this->input->get("allocate_auid"));
			$this->input->get("allocate_status") && $this->DB2->where("q.allocate_status", $this->input->get("allocate_status"));

			$this->input->get("todo") && $this->DB2->where("q.status=1", null, false);

			$this->DB2
				->select("COUNT(*) as cnt, au.name as admin_uname, `au`.`uid` as admin_uid")
				->from("question_replies qr")
				->join("questions q", "q.id=qr.question_id", "left")
				->join("servers gi", "gi.server_id=q.server_id", "left")
				->join("users u", "u.uid=q.uid", "left")
				->join("admin_users au", "au.uid=qr.admin_uid", "left")
                ->where("qr.is_official >", 0);

			if ($this->input->get("account")) {
				$this->DB2->join("users u", "u.uid=q.uid", "left")
					->where("u.email", $this->input->get("account"))
					->or_where("u.mobile", $this->input->get("account"));
			}

			if ($this->input->get("replies") || $this->input->get("cs_admin")) {
                if ($this->input->get("replies")) $this->DB2->like("qr.content", $this->input->get("replies"), 'both');
                if ($this->input->get("cs_admin")) $this->DB2->where("qr.admin_uid", $this->input->get("cs_admin"));
			}

			if ($this->input->get("start_date")) {
				$start_date = $this->DB2->escape($this->input->get("start_date"));
				if ($this->input->get("end_date")) {
					$end_date = $this->DB2->escape($this->input->get("end_date").":59");
					$this->DB2->where("qr.create_time between {$start_date} and {$end_date}", null, false);
				}
				else $this->DB2->where("qr.create_time >= {$start_date}", null, false);
			}

			switch ($this->input->get("action"))
			{
				case "查詢":
					$this->DB2->stop_cache();

					$query = $this->DB2->group_by("au.name")->get();

					break;
			}

			$this->DB2->stop_cache();
			$this->DB2->flush_cache();

		}
		else {
			$default_value = array(
				'use_default' => true,
				'start_date' => date('Y-m-d')." 00:00",
			);
			$_GET = $default_value;
		}

		$games = $this->DB2->from("games")->get();
		$cs_admins = $this->DB2->from("admin_users")->where_in("role", array("cs", "cs_master","glory_service"))->get();

		$this->g_layout
			->add_breadcrumb("查詢")
			->set("query", isset($query) ? $query : false)
			->set("games", $games)
			->set("cs_admins", $cs_admins)
			->add_js_include("service/get_list")
			->add_js_include("jquery-ui-timepicker-addon")
			->render();
	}

	function reply_preview()
	{
		$content = "aaa";

		$this->_init_service_layout()
				->set("content", $content)
				->render();
	}

	function edit_reply($id)
	{
		$row = $this->DB2->where("id", $id)->from("question_replies")->get()->row();

		$this->_init_service_layout()
			->add_breadcrumb("編輯回覆")
			->add_js_include("service/edit_reply")
			->set("row", $row)
			->render();
	}

	function modify_reply_json()
	{
		$question_id = $this->input->post("question_id");
		$id = $this->input->post("reply_id");
		$post_content = nl2br($this->input->post("content"));

		$query = $this->DB2->query("SELECT count(*) as chk FROM question_replies WHERE question_id={$question_id} and content='{$post_content}'");
		if ($query->row()->chk) die(json_encode(array("status"=>"failure", "message"=>"請勿重覆回答!")));


		$data = array(
			"uid" => 0,
			"question_id" => $question_id,
			'content' => nl2br($this->input->post("content")),
			'is_official' => '1',
			'admin_uid' => $_SESSION['admin_uid'],
		);

		if ($id) {
			$row = $this->DB2->from("question_replies")->where("id", $id)->get()->row();
			$this->load->model("log_admin_actions");
			$this->log_admin_actions->insert_log($_SESSION["admin_uid"], 'question_reply', 'update',
					"編輯回覆 #{$id} {$row->content} => {$data['content']}");

			$this->DB1
				->where("is_official", "1")
				->where("id", $id)
				->update("question_replies", $data);
		}
		else {
			$this->DB1
				->set("create_time", "now()", false)
				->insert("question_replies", $data);

			$this->DB1->set("update_time", "now()", false)
				->where("id", $question_id)->update("questions",
					array("is_read"=>'0', "status"=>'2', 'admin_uid'=>$_SESSION['admin_uid']));
		}

		die(json_success());
	}
	function delete_reply_json($id)
	{
		$this->DB1
			->where("id", $id)
			->delete("question_replies");

		if ($this->DB1->affected_rows() > 0) echo json_success();
		else echo json_failure("資料庫刪除失敗或沒有權限".$this->DB1->last_query());
	}

	function update_note_json()
	{
		$this->DB1->where("id", $this->input->post("question_id"))
			->update("questions", array('note' => $this->input->post("note")));

		die(json_success());
	}

	function update_type_json()
	{
		$this->DB1->where("id", $this->input->post("update_question_id"))
			->update("questions", array('type' => $this->input->post("select_type")));

		die(json_success());
	}

	function allocate_json()
	{
		$result = $this->input->post("allocate_result").date("Y-m-d H:i")." - ".$_SESSION['admin_name']."：".$this->input->post("result")."<br>";

		$this->DB1->where("id", $this->input->post("question_id"))
			->set('allocate_date', 'NOW()', false)
			->set('allocate_status', '1')
			->set('allocate_result', $result)
			->set("close_admin_uid", null)
			->set("system_closed_start", null)
			->update("questions", array('allocate_admin_uid' => $this->input->post("allocate_admin_uid")));

		die(json_success());
	}

	function finish_allocate_json()
	{
		$result = $this->input->post("allocate_result").date("Y-m-d H:i")." - ".$_SESSION['admin_name']."：".$this->input->post("result")."<br>";
		$this->DB1->where("id", $this->input->post("question_id"))
			->set('allocate_finish_date', 'NOW()', false)
			->set('allocate_status', '2')
			->set('allocate_result', $result)
			->set('allocate_admin_uid', $_SESSION['admin_uid'])
			->update("questions");

		die(json_success());
	}

	function close_question($id)
	{
		if ( ! $this->zacl->check_acl("service", "modify")) die(json_failure("沒有權限"));

		$this->DB1->set("status", "4")->set("close_admin_uid", $_SESSION['admin_uid'])->set("system_closed_start", null)->where("admin_uid is not null", null, false)->where("id", $id)->update("questions");
		if ($this->DB1->affected_rows() > 0) {
			die(json_success());
		}
		else {
			die(json_failure("問題尚未處理"));
		}
	}


	function hide_question($id)
	{
		if ( ! $this->zacl->check_acl("service", "delete")) die(json_failure("沒有權限"));

		$this->DB1->set("update_time", "now()", false)->where("id", $id)->update("questions", array("status"=>"0", 'admin_uid'=>$_SESSION['admin_uid']));
		if ($this->DB1->affected_rows()>0)
		{
			$this->load->model("log_admin_actions");
			$this->log_admin_actions->insert_log($_SESSION["admin_uid"], 'question', 'hide', "隱藏提問 #{$id}");
			die(json_success());
		}
		else die(json_failure());
	}

	function show_question($id)
	{
		if ( ! $this->zacl->check_acl("service", "delete")) die(json_failure("沒有權限"));

		$this->DB1->set("update_time", "now()", false)->where("id", $id)->update("questions", array("status"=>"1", 'admin_uid'=>$_SESSION['admin_uid']));
		echo $this->DB1->affected_rows()>0 ? json_success() : json_failure();
	}


	function reserved_question($id)
	{

		if ( ! $this->zacl->check_acl("service", "modify")) die(json_failure("沒有權限"));
		// if ($question->status == '2' && ($question->allocate_status == '0' || $question->allocate_status == '2')):


		$this->DB1->set("status", "7")->set("close_admin_uid", $_SESSION['admin_uid'])
		->set("system_closed_start", "now()", false)
		->where("admin_uid is not null", null, false)
		->where("status", "2")
		->where("(allocate_status='2' or allocate_status='0')", null, false)
		->where("id", $id)->update("questions");
		if ($this->DB1->affected_rows() > 0) {
			die(json_success());
		}
		else {
			die(json_failure("問題尚未處理"));
		}
	}

	function cancel_reserved_question($id)
	{
		if ( ! $this->zacl->check_acl("service", "modify")) die(json_failure("沒有權限"));

		//$this->DB1->set("status", "2")->set("close_admin_uid", null)->set("system_closed_start", null)->where("admin_uid is not null", null, false)->where("id", $id)->where("system_closed!=", '1')->update("questions");
		$this->DB1->set("status", "2")->set("close_admin_uid", null)->set("system_closed_start", null)->where("system_closed!=1", null, false)->where("id", $id)->update("questions");
		if ($this->DB1->affected_rows() > 0) {
			die(json_success());
		}
		else {
			die(json_failure("無法取消"));
		}
	}
	function add_to_favorites($id)
	{
		if ( ! $this->zacl->check_acl("service", "favorite")) die(json_failure("沒有權限"));

		$this->DB1->insert("question_favorites", array("question_id" => $id, 'admin_uid'=>$_SESSION['admin_uid']));
		echo $this->DB1->affected_rows()>0 ? json_success() : json_failure();
	}

	function remove_favorites($id)
	{
		if ( ! $this->zacl->check_acl("service", "favorite")) die(json_failure("沒有權限"));

		$this->DB1
			->where("question_id", $id)
			->where("admin_uid", $_SESSION['admin_uid'])
			->delete("question_favorites");
		echo $this->DB1->affected_rows()>0 ? json_success() : json_failure();
	}

	function complaints()
	{
		//select server_id,reporter_char_id,reporter_name,flagged_player_char_id,flagged_player_name,category,reason,create_time from complaints
		$this->_init_service_layout();

		header("Cache-Control: private");
		$this->DB2->start_cache();

		$category =  $this->input->get("category");
		$status =  $this->input->get("status");
		$server =  $this->input->get("server");
		$character_name =  $this->input->get("character_name");
		$character_id =  $this->input->get("character_id");

		$this->DB2
		->select("c.*, sv.name as server_name")
		->from("complaints c")
		->join("servers sv", "sv.server_id=c.server_id", "left");

		if ($category)
		{
			$this->DB2->where("category",$category);
		}
		if ($status)
		{
			$this->DB2->where("status",$status);
		}

		if ($character_name)
		{
			$this->DB2->where("reporter_name",$character_name);
			$this->DB2->or_where("flagged_player_name",$character_name);

		}
		if ($character_id)
		{
			$this->DB2->where("reporter_char_id",$character_id);
			$this->DB2->or_where("flagged_player_char_id",$character_id);
		}

		if ($server)
		{
			$this->DB2->where("c.server_id",$server);
		}

		if ($this->input->get("start_date")) {
			$start_date = $this->DB2->escape($this->input->get("start_date"));
			if ($this->input->get("end_date")) {
				$end_date = $this->DB2->escape($this->input->get("end_date").":59");
				$this->DB2->where("c.create_time between {$start_date} and {$end_date}", null, false);
			}
			else $this->DB2->where("c.create_time >= {$start_date}", null, false);
		}


		$this->DB2->stop_cache();
		$total_rows = $this->DB2->count_all_results();

		$query = $this->DB2->limit(30, $this->input->get("record"))
					->order_by("id desc")->get();


		$this->DB2->flush_cache();
		$games = $this->DB2->from("games")->get();
		$servers = $this->DB2->where("game_id","h35naxx1hmt")->where_in("server_status", array("public", "maintenance"))->order_by("server_id")->get("servers");
		$cs_admins = $this->DB2->from("admin_users")->where_in("role", array("cs", "cs_master","glory_service"))->get();

		$get = $this->input->get();
		$query_string = '';
		if ($get) {
			unset($get["record"]);
			$query_string = http_build_query($get);
		}


		//$query_string = http_build_query($get);

		$this->load->library('pagination');
		$this->pagination->initialize(array(
				'base_url'	=> site_url("service/complaints?.$query_string"),
				'total_rows'=> $total_rows,
				'per_page'	=> 30
			));


		$this->g_layout
			->add_breadcrumb("玩家檢舉")
			->set("query", isset($query) ? $query : false)
			->set("games", $games)
			->set("servers", $servers)
			->set("cs_admins", $cs_admins)
			->set("total_rows", $total_rows)
			->add_js_include("fontawesome-all")
			->add_js_include("service/complaints")
			->add_js_include("jquery-ui-timepicker-addon")
			->render();
	}

	function complaint_mark_read()
	{
		if ( ! $this->zacl->check_acl("service", "modify")) die(json_failure("沒有權限"));

		$id = $this->input->post("id");
		$this->DB1->set("status", "2")->set("admin_uid", $_SESSION['admin_uid'])->set("update_time",  now())->where("id", $id)->update("complaints");
		if ($this->DB1->affected_rows() > 0) {
			die(json_success());
		}
		else {
			die(json_failure($query));
		}
	}

	function complaint_add_comment()
	{
		if ( ! $this->zacl->check_acl("service", "modify")) die(json_failure("沒有權限"));

		$id = $this->input->post("id");
		$comment = $this->input->post("comment");
		//$new_comment = $comment."by ".$_SESSION['admin_uid'];
		//$this->DB1->set("admin_comment", "CONCAT(admin_comment, $comment)",FALSE)->set("admin_uid", $_SESSION['admin_uid'])->set("update_time", "NOW()")->where("id", $id)->update("complaints");
		if ($comment)
		{
		$this->DB1->set("admin_comment", $comment)->set("admin_uid", $_SESSION['admin_uid'])->set("update_time",  now())->where("id", $id)->update("complaints");
		if ($this->DB1->affected_rows() > 0) {
			die(json_success());
		}
		else {
			die(json_failure($query));
		}
		}
		else {
			die(json_failure("請輸入註解"));
		}
	}


	function complaint_batch_mark()
	{
		if ( ! $this->zacl->check_acl("service", "modify")) die(json_failure("沒有權限"));

		$role_id = $this->input->post("role_id");
		$this->DB1->set("status", "2")->set("admin_comment", "帳號停權或禁言")->set("admin_uid", $_SESSION['admin_uid'])->set("update_time", now())->where("flagged_player_char_id", $role_id)->update("complaints");
		//die("role_id is" .$role_id );
		if ($this->DB1->affected_rows() > 0) {
			die(json_success());
		}
		else {
			die(json_failure($query));
		}
	}


	function complaints_ranking()
	{
		$period = $this->input->post("period");
		if (!$period)  $period=1;
		$ranking_report = $this->DB2->query("select server_id,flagged_player_name, flagged_player_char_id,count(*) as cnt
		from complaints
		where datediff(now(),create_time) < {$period}
		and status = 1
		group by server_id,flagged_player_name, flagged_player_char_id
		order by cnt desc limit 5")->result();
		die(json_success($ranking_report));



	}





}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
