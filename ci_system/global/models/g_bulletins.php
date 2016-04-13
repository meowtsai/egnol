<?php

class G_Bulletins extends CI_Model {
	
	function get_row($id)
	{
		return $this->db->where("id", $id)->get("bulletins")->row();
	}
	
	function get_preview($game_id, $id)
	{
		return $this->db->where("id", $id)
				->where("game_id", $game_id)
				->from("bulletins")
				->get()->row();	
	}
	
	function get_bulletin($game_id, $id)
	{	
		return $this->db->where("id", $id)
                ->where("(target like '%{$game_id},%' or game_id='{$game_id}')", null, false)
				->where("priority >", "0")
				->where("now() between start_time and end_time", null, false)
				->from("bulletins")				
				->get()->row();
	}
	
	function get_list($game_id, $type, $limit=0, $offset=0)
	{
		if ($type) $this->db->where("type", $type);
		
		if ($offset) $this->db->limit($limit, $offset);
		else if ($limit) $this->db->limit($limit);
		
		$this->db->where("(target like '%{$game_id},%' or game_id='{$game_id}')", null, false);
		
		return $this->db->select("*")
			->from("bulletins")
			->where("priority >", "0")
			->where("now() between start_time and end_time", null, false)
			->order_by("priority", "desc")->order_by("start_time", "desc")->get();
	}
	
	function get_list_target($game_id, $type, $target, $limit=0, $offset=0)
	{
		$this->db->where("(target=',' or target like '%{$target},%')", null, false);
		return $this->get_list($game_id, $type, $limit, $offset);		
	}
	
	function get_count($game_id, $type)
	{
		
		return $this->db
					->from("bulletins")
					->where("type", $type)
					->where("priority >", "0")					
					->where("now() >= start_time", null, false)
		            ->where("(target like '%{$game_id},%')", null, false)
					->count_all_results();
	}
}

