<?php

class notices extends CI_Model {

	function get_notice_data($limit, $offset=0)
	{		
		if ($offset) { 
			$this->db->limit($limit, $offset);
		} else $this->db->limit($limit);
	
		return $this->db
			->from("notices")
			->order_by("id", "desc")
			->get();
	}
	
	function get_notice_count()
	{
		return $this->db->from("notices")->count_all_results();
	}
	
	function get_notice($id)
	{
		return $this->db->where("id", $id)->get("notices")->row();
	}
	
	function insert_notice($data)
	{
		isset($data['create_time']) or $data['create_time'] = now();	
		$this->db->insert('notices', $data);
		return $this->db->insert_id();
	}
	
	function update_notice($id, $data)
	{
		$this->db->where('id', $id)->update('notices', $data);
	}
	
	function delete_notice($id)
	{
		$this->db->where('id', $id)->delete('notices');
		return $this->db->affected_rows();
	}	
	
	function insert_notice_category($game_id, $data)
	{	
		$data['game_id'] = $game_id;
		$data['order'] = "255";
		$this->db->insert('notice_categories', $data);
		return $this->db->insert_id();
	}	
	
	function update_notice_category($id, $data)
	{
		$this->db->where('id', $id)->update('notice_categories', $data);
		return $this->db->affected_rows();
	}	
	
	function delete_notice_category($id)
	{
		$this->db->where('id', $id)->delete('notice_categories');
		return $this->db->affected_rows();
	}
	
	function get_category_data($game_id)
	{
		return $this->db
			->select("bc.*, (select count(*) from notices where category_id=bc.id) cnt")
			->from("notice_categories bc")
			->where("bc.game_id", $game_id)
			->order_by("id")->get();
	}
	
}