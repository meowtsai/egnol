<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Event extends MY_Controller {

	function __construct()
	{
		parent::__construct();
		$this->zacl->check_login(true);
		$this->zacl->check("event", "read");
	}

	function index()
	{
		$this->_init_layout();
		$this->g_layout->render();
	}

	function code()
	{
		$this->_init_layout();

		$this->DB2->start_cache();

		if ($this->input->post())
		{
			$this->input->post("game") && $this->DB2->where("game_id", $this->input->post("game"));
			$this->input->post("title") && $this->DB2->like("title", $this->input->post("title"));

            if ($this->input->post("is_active")) {
                switch($this->input->post("is_active")) {
                    case "all":
                        break;
                    case "not":
                        $this->DB2->where("is_active", 0);
                        break;
                    default:
                        $this->DB2->where("is_active", 1);
                        break;
                }
            }
		} else {
            $this->DB2->where("is_active", 1);
        }

		$this->DB2->select("game_id, title, COUNT(*) AS `total`, SUM(CASE WHEN uid IS NOT NULL THEN 1 ELSE 0 END) AS `used`, is_active")->from("promotion_codes")->group_by(array("game_id", "title"));

		$this->DB2->stop_cache();

		$total_rows = $this->DB2->count_all_results();

		$this->load->library('pagination');
		$this->pagination->initialize(array(
					'base_url'	=> site_url("event/code?"),
					'total_rows'=> $total_rows,
					'per_page'	=> 100
				));

		$query = $this->DB2->order_by("id")
					->limit(100, $this->input->get("record"))
					->get();

		$this->DB2->flush_cache();

        $games = $this->DB2->from("games")->get();

		$this->g_layout
			->add_breadcrumb("兌換序號")
			->add_js_include("event/list")
			->set("query", $query)
            ->set("games", $games)
			->set("total_rows", $total_rows)
			->render();
	}

	function delete_remain_codes($event)
	{
		if ( ! $this->zacl->check_acl("event", "delete")) die(json_failure("沒有權限"));

		$this->DB1->where("event", $event)->where("uid is null", null, false)->where("lock", "0")->delete("codes");
		echo $this->DB1->affected_rows()>0 ? json_success() : json_failure("沒有未發放的序號");
	}

	function add_code()
	{
		$this->zacl->check("event", "modify");

		if ($_POST)
		{
			$title = $this->input->post("title");
			$game_id = $this->input->post("game");
			$codes = $this->input->post("codes");
			$spt = explode("\n", $codes);
			$i = 0;

            $existing_code = $this->DB2->where("game_id", $game_id)->where("title", $title)->from("promotion_codes")->get()->row();

            $is_active = ($existing_code)? $existing_code->is_active: 1;

			foreach($spt as $code) {
				if (empty($code)) continue;

                $used_code = $this->DB2->where("game_id", $game_id)->where("code", trim($code))->from("promotion_codes")->get()->row();

                $r = 0;
                if (!$used_code) {
    				$r = $this->DB1->insert("promotion_codes", array("title"=>$title, "game_id"=>$game_id, "code"=>trim($code), "is_active"=>$is_active));
                } else {
					echo "<div><b>{$code}</b> 已存在，新增失敗</div>";
                    continue;
                }
				if ($r) {
					$i++;
					echo "<div><b>{$code}</b> 已新增</div>";
				}
				else {
					echo "<div style='color:red'><b>{$code}</b> 錯誤: {$this->DB1->_error_message()}</div>";
				}
			}
			echo "<div style='margin:6px 0 0; color:green'>--- 共新增 {$i} 筆</div>";
			echo "<div style='margin:12px 0 0'>
					<a href='".site_url("event/add_code")."'>繼續新增</a>
					</div>";

			die;
		}

        $games = $this->DB2->from("games")->get();

        $this->_init_layout();
        $this->g_layout
            ->set("games", $games)
            ->add_breadcrumb("新增序號")
            ->render();
	}

	function toggle_code()
	{
		if ( ! $this->zacl->check_acl("event", "delete")) die(json_failure("沒有權限"));

        $title = $this->input->get_post("title");
        $game_id = $this->input->get_post("game_id");
        $is_active = $this->input->get_post("is_active");

		$this->DB1->where("title", $title)->where("game_id", $game_id)->set("is_active", intval($is_active))->update("promotion_codes");
		echo $this->DB1->affected_rows()>0 ? json_success() : json_failure();
	}

	function set_code_lock($id, $val)
	{
		if ( ! $this->zacl->check_acl("event", "modify")) die(json_failure("沒有權限"));

		$this->DB1->where("id", $id)->set("lock", $val)->update("codes");
		echo $this->DB1->affected_rows()>0 ? json_success() : json_failure("無變更");
	}


	function g78_season()
	{
		$this->_init_layout();
		$query = null ;
		if ($this->input->get_post("player_id"))
		{
			$this->input->get_post("player_id") && $this->DB2->where("player_id", $this->input->get_post("player_id"));
    	$query = $this->DB2->select("player_id, season_5v5cnt, punish_cnt, punish_ag, tag")->from("g78_s2_result")->get();
		}


		$this->g_layout
			->add_breadcrumb("平安京S2賽季戰之儀名單")
			->set("query", isset($query) ? $query : false)
      ->render();
	}

	function h55_yahoo()
	{

		$this->_init_layout();
		$result = null ;

		$result = $this->DB2
			->select("b.name,b.in_game_id,b.partner_uid,a.serial,")
			->select("(select create_time from log_yahoo_event c where c.char_id=a.uid order by id desc limit 1 ) as dt",FALSE)
			->from("event_serial a")
			->join("characters b", "a.uid=b.id", "left")
			->where("event_id",11)
			->where("status",1)
			->get();

		$log = $this->DB2
			->select("b.name as char_name,c.*")
			->from("log_yahoo_event c")
			->join("characters b", "c.char_id=b.id", "left")
			->order_by("c.id desc")
			->get();

		$this->g_layout
			->add_breadcrumb("第五人格Yahoo購物活動獎勵查詢")
			->set("result", isset($result) ? $result : false)
			->set("log", isset($log) ? $log : false)
      ->render();
	}


	function h55_tgs()
	{

		$this->_init_layout();
		$result = null ;

		$result = $this->DB2
			->select("b.name,b.in_game_id,b.partner_uid,a.serial,a.event_sub_id,sm.title")
			->select("(select create_time from log_serial_event c where c.char_id=a.uid and c.serial=a.serial and status=1 order by id desc limit 1 ) as dt",FALSE)
			->from("event_serial a")
			->join("characters b", "a.uid=b.id", "left")
			->join("serial_main sm", "sm.id=a.event_sub_id", "left")
			->where("a.event_id",13)
			->where("a.status",1)
			->order_by("a.event_sub_id")
			->get();

		$log = $this->DB2
			->select("b.name as char_name,c.*")
			->from("log_serial_event c")
			->join("characters b", "c.char_id=b.id", "left")
			->where("event_id",13)
			->order_by("c.id desc")
			->get();

		$this->g_layout
			->add_breadcrumb("第五人格TGS活動序號查詢")
			->set("result", isset($result) ? $result : false)
			->set("log", isset($log) ? $log : false)
      ->render();
	}


	function g83_tgs()
	{

		$this->_init_layout();
		$result = null ;
//帳號	角色名稱	角色id
		$result = $this->DB2
			->select("a.personal_id as in_game_id,log_tb.char_name as name, a.email, a.serial,a.event_sub_id,sm.title,log_tb.partner_uid,log_tb.note as server")
			->select("(select create_time from log_serial_event c where c.char_id=a.personal_id and c.serial=a.serial and status=1 order by id desc limit 1 ) as dt",FALSE)
			->from("event_serial a")
			->join("serial_main sm", "sm.id=a.event_sub_id", "left")
			->join("( select * from log_serial_event where event_id=15 and status=1) log_tb","log_tb.serial=a.serial")
			->where("a.event_id",15)
			->where("a.status",1)
			->order_by("a.event_sub_id")
			->get();

		$log = $this->DB2
			->select("b.name as char_name,c.*")
			->from("log_serial_event c")
			->join("characters b", "c.char_id=b.id", "left")
			->where("event_id",15)
			->order_by("c.id desc")
			->get();

		$this->g_layout
			->add_breadcrumb("荒野行動TGS虛寶活動查詢")
			->set("result", isset($result) ? $result : false)
			->set("log", isset($log) ? $log : false)
      ->render();
	}


	function l20na_preregister()
	{

		$this->_init_layout();
		$result = null ;

		$summary = $this->db->query("Select DATE_FORMAT(create_time,'%Y-%m-%d') as dDate,count(id) as count from event_preregister
		where event_id=12
    group by DATE_FORMAT(create_time,'%Y-%m-%d') order by DATE_FORMAT(create_time,'%Y-%m-%d') desc, count(id) desc");

    $summary_country = $this->db->query("Select country,count(id) as count from event_preregister where event_id=12 group by country order by count(id) desc");


		$result = $this->DB2
			->select("a.id,a.nick_name,a.create_time,a.update_time,a.email,a.ip,a.country,")
			->from("event_preregister a")
			->where("event_id",12)
			->get();

			//->select("(select concat(sum(case when status=1 then 1 else 0 end),'/',count(*)) as item_status from l20na_detail where o_id in (select id from l20na_orders where event_uid=a.id)) as item_status",FALSE)


		$log = $this->DB2
			->select("c.*")
			->from("l20na_npc_affections_log c")
			->order_by("c.id desc")
			->limit(1000)
			->get();

		$refrence = $this->DB2
			->select("c.npc_name,b.item_name, a.response,response_text,response_voice")
			->from("l20na_npcs_items a")
			->join("l20na_items b","a.item_code = b.item_code","left")
			->join("l20na_npcs c","a.npc_code=c.npc_code","left")
			->get();


		$this->g_layout
			->add_breadcrumb("逆水寒預註冊")
			->set("result", isset($result) ? $result : false)
			->set("log", isset($log) ? $log : false)
			->set("refrence", isset($refrence) ? $refrence : false)
			->set("summary", isset($summary) ? $summary : false)
			->set("summary_country", isset($summary_country) ? $summary_country : false)
      ->render();
	}
	function l20na_preregister_user($uid){

		$this->_init_layout();

		$user = $this->DB2
			->select("a.id,a.nick_name,a.create_time,a.update_time,a.email,a.ip,a.country,")
			->select("(select concat(sum(case when status=1 then 1 else 0 end),'/',count(*)) as item_status from l20na_detail where o_id in (select id from l20na_orders where event_uid=a.id)) as item_status",FALSE)
			->from("event_preregister a")
			->where("a.event_id",12)
			->where("a.id",$uid)
			->get();

		$npcs =$this->DB2
			->select("a.affection, a.id, a.npc_code,c.npc_name, c.npc_gender")
			->from("l20na_npc_affections a")
			->where("a.event_uid",$uid)
			->join("l20na_npcs c" ,"a.npc_code=c.npc_code", "left")
			->get();



		$items =$this->DB2
			->select("a.desc, a.create_time,b.id,b.item_code,c.item_name,b.status")
			->from("l20na_orders a")
			->join("l20na_detail b" ,"a.id=b.o_id", "left")
			->join("l20na_items c" ,"b.item_code=c.item_code", "left")
			->where("a.event_uid",$uid)
			->get();


		$logs =$this->DB2
				->select(" a.id, a.aff_id,a.affection_change,a.item_id,a.note,a.create_time,b.npc_code")
				->from("l20na_npc_affections_log a")
				->join("l20na_npc_affections b" ,"a.aff_id=b.id", "left")
				->where("b.event_uid",$uid)
				->order_by("id desc")
				->get();


		$this->g_layout
			->add_breadcrumb("逆水寒預註冊玩家明細","event/l20na_preregister")
			->add_breadcrumb("單一玩家資料檢視")
			->set("user", isset($user) ? $user : false)
			->set("npcs", isset($npcs) ? $npcs : false)
			->set("items", isset($items) ? $items : false)
			->set("logs", isset($logs) ? $logs : false)
			->render();

	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
