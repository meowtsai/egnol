<?php

class G_Guides extends CI_Model {

	function get_row($game_id, $id)
	{
		return $this->db->where("game_id", $game_id)->where("id", $id)->get("guides")->row();
	}	
	
	function get_list($game_id, $parent_id, $limit=0, $offset=0, $order='')
	{
		if ($parent_id) $this->db->where("parent_id", $parent_id);
	
		if ($offset) $this->db->limit($limit, $offset);
		else if ($limit) $this->db->limit($limit);
	
		if ($order) $this->db->order_by($order);
		else $this->db->order_by("create_time");
	
		return $this->db->from("guides g")
			->where("game_id", $game_id)
			->where("enable", "1")->get();
	}
	
	function get_count($game_id, $parent_id)
	{
		if ($parent_id) $this->db->where("parent_id", $parent_id);
		return $this->db->where("game_id", $game_id)->from("guides")->count_all_results();
	}	
}

