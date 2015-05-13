<?php

class Guides extends CI_Model {

	function get_guide_list($game_id, $parent_id="")
	{
		if ($parent_id) {
			$this->db->where("parent_id", $parent_id);
		} else $this->db->where("parent_id", '0');
		return $this->db->where("game_id", $game_id)->from("guides")->order_by("modify_date desc")->get();
	}
	
	function get_guide($id)
	{
		return $this->db->where("id", $id)->from("guides")->get()->row();
	}
	
	function insert_guide($data)
	{
		isset($data['create_time']) or $data['create_time'] = now();
		isset($data['modify_date']) or $data['modify_date'] = now();
		
		$this->db->insert('guides', $data);
		return $this->db->insert_id();
	}
	
	function update_guide($id, $data)
	{
		$data['modify_date'] = now();
		$this->db->where('id', $id)->update('guides', $data);
	}
	
	function delete_guide($id)
	{
		$query = $this->db->where("parent_id", $id)->from("guides")->get();
		foreach($query->result() as $row) {
			$this->delete_guide($row->id);
		}
		$this->db->where('id', $id)->delete("guides");
	}
}