<?php

class G_Notices extends CI_Model {
	
	function get_preview($id)
	{
		return $this->db->where("id", $id)->get("notices")->row();
	}
	
	function get_notice($uid, $id)
	{
		return $this->db->where("n.id", $id)->where("nt.uid", $uid)->where("is_active", "1")
			->from("notices n")
			->join("notice_targets nt", "nt.notice_id=n.id")
			->get()->row();
	}
	
	function get_list($uid, $limit=0, $offset=0)
	{		
		if ($offset) $this->db->limit($limit, $offset);
		else if ($limit) $this->db->limit($limit);
		
		return $this->db->select("*")
			->from("notices n")
			->join("notice_targets nt", "nt.notice_id=n.id")
			->where("nt.uid", $uid)
			->order_by("n.id", "desc")->get();
	}
	
	function get_count($uid)
	{		
		return $this->db
				->from("notices n")
				->join("notice_targets nt", "nt.notice_id=n.id")
				->where("nt.uid", $uid)
				->count_all_results();
	}
	
	function set_read($uid)
	{
		$this->db
			->set("read_date", "NOW()", false)
			->set("is_read", "1")			
			->where("uid", $uid)->where("is_read", "0")->update("notice_targets");
	}
	
}

