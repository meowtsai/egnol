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
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */