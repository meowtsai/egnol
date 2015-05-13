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
	
	function code($event)
	{		
		$this->_init_layout();
		
		$this->db->start_cache();
		
		if ($this->input->post()) 
		{	
			$this->input->post("code") && $this->db->where("code", $this->input->post("code"));
			$this->input->post("uid") && $this->db->where("uid", $this->input->post("uid"));
		}
		
		$this->db->from("codes")->where("event", $event);
		
		$this->db->stop_cache();
		
		$total_rows = $this->db->count_all_results();
		
		$this->load->library('pagination');			
		$this->pagination->initialize(array(
					'base_url'	=> site_url("event/code/{$event}?"),
					'total_rows'=> $total_rows,
					'per_page'	=> 100
				));

		$query = $this->db->order_by("id")
					->limit(100, $this->input->get("record"))
					->get();
				
		$this->db->flush_cache();

		$this->g_layout
			->add_breadcrumb($event)
			->set("event", $event)
			->set("query", $query)
			->set("total_rows", $total_rows)
			->render();		
	}
	
	function delete_remain_codes($event)
	{
		if ( ! $this->zacl->check_acl("event", "delete")) die(json_failure("沒有權限"));
		
		$this->db->where("event", $event)->where("uid is null", null, false)->where("lock", "0")->delete("codes");
		echo $this->db->affected_rows()>0 ? json_success() : json_failure("沒有未發放的序號");
	}
	
	function add_code($event)
	{
		$this->zacl->check("event", "modify");	
		
		if ($_POST)
		{
			$codes = $this->input->post("codes");
			$spt = explode("\n", $codes);
			$i = 0;
			foreach($spt as $code) {
				if (empty($code)) continue;
				$r = $this->db->insert("codes", array("event"=>$event, "code"=>trim($code)));
				if ($r) {
					$i++;
					echo "<div><b>{$code}</b> 已新增</div>";
				}
				else {
					echo "<div style='color:red'><b>{$code}</b> 錯誤: {$this->db->_error_message()}</div>";
				} 
			}	
			echo "<div style='margin:6px 0 0; color:green'>--- 共新增 {$i} 筆</div>";
			echo "<div style='margin:12px 0 0'>
					<a href='".site_url("event/add_code/{$event}")."'>繼續新增</a> | <a href='".site_url("event/code/{$event}")."'>回清單</a>
					</div>";
			
		}
		else
		{			
			$this->_init_layout();
			$this->g_layout
				->add_breadcrumb("新增序號")
				->render();
		}
	}
	
	function delete_code($id)
	{
		if ( ! $this->zacl->check_acl("event", "delete")) die(json_failure("沒有權限"));
		
		$this->db->where("id", $id)->delete("codes");
		echo $this->db->affected_rows()>0 ? json_success() : json_failure();
	}
	
	function set_code_lock($id, $val)
	{
		if ( ! $this->zacl->check_acl("event", "modify")) die(json_failure("沒有權限"));
		
		$this->db->where("id", $id)->set("lock", $val)->update("codes");
		echo $this->db->affected_rows()>0 ? json_success() : json_failure("無變更");
	}	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */