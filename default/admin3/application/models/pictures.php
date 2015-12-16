<?php

class Pictures extends CI_Model {

	function get_picture_data($game_id, $category_id, $limit, $offset=0)
	{
		$category_id && $this->db->where("p.category_id", $category_id);
		
		if ($offset) { 
			$this->db->limit($limit, $offset);
		} else $this->db->limit($limit);
	
		return $this->db->select("p.*, pc.category")
			->where("pc.game_id", $game_id)
			->from("pictures p")
			->join("picture_categories pc", "p.category_id=pc.id", "left")
			->order_by("create_time", "desc")->get();
	}
	
	function get_picture_count($game_id, $category_id)
	{
		$category_id && $this->db->where("p.category_id", $category_id);
		return $this->db->select("count(*) cnt")
			->where("pc.game_id", $game_id)
			->from("pictures p")
			->join("picture_categories pc", "p.category_id=pc.id", "left")
			->get()->row()->cnt;
	}
	
	function get_picture($id)
	{
		return $this->db->where("id", $id)->from("pictures")->get()->row();
	}
	
	function insert_picture($data)
	{
		isset($data['create_time']) or $data['create_time'] = now();		
		
		$this->db->insert('pictures', $data);
		return $this->db->insert_id();
	}	

	function insert_picture_category($game_id, $data)
	{
		$data['game_id'] = $game_id;
		$this->db->insert('picture_categories', $data);
		return $this->db->insert_id();
	}
	
	function update_picture_category($id, $data)
	{
		$this->db->where('id', $id)->update('picture_categories', $data);
		return $this->db->affected_rows();
	}
	
	function delete_picture_category($id)
	{
		$this->db->where('id', $id)->delete('picture_categories');
		return $this->db->affected_rows();
	}
	
	function get_category_data($game_id)
	{
		return $this->db
		->select("bc.*, (select count(*) from pictures where category_id=bc.id) cnt")
		->from("picture_categories bc")
		->where("bc.game_id", $game_id)
		->order_by("id")->get();
	}
	
}