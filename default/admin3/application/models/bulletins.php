<?php

class Bulletins extends CI_Model {

	function get_bulletin_data($game_id, $bulletin_type, $limit, $offset=0)
	{
		$bulletin_type && $this->db->where("type", $bulletin_type);
		
		if ($offset) { 
			$this->db->limit($limit, $offset);
		} else $this->db->limit($limit);
	
		return $this->db->select("*")
			->from("bulletins")
		    //->where("(target like '%{$game_id},%')", null, false)
		    ->where("game_id", $game_id)
			->order_by("(case priority when 3 then 3 when 2 then 2 else 1 end)", "desc")->order_by("id", "desc")
			->get();
	}
	
	function get_bulletin_count($game_id, $bulletin_type)
	{
		$bulletin_type && $this->db->where("type", $bulletin_type);
		return $this->db->select("count(*) cnt")
		    //->where("(target like '%{$game_id},%')", null, false)
		    ->where("game_id", $game_id)
			->from("bulletins")
			->get()->row()->cnt;
	}
	
	function get_bulletin($id)
	{
		return $this->db->where("id", $id)->from("bulletins")->get()->row();
	}
	
	function insert_bulletin($data)
	{
		isset($data['create_time']) or $data['create_time'] = now();
		isset($data['update_time']) or $data['update_time'] = now();
		isset($data['start_time']) or $data['start_time'] = now();
		isset($data['end_time']) or $data['end_time'] = '2038-01-01 00:00:00';
		
		$data['admin_uid'] = 0; //暫時
		
		$this->db->insert('bulletins', $data);
		return $this->db->insert_id();
	}
	
	function update_bulletin($id, $data)
	{
		$data['update_time'] = now();
		if (empty($data['start_time'])) {
			$data['start_time'] = now();
		}
		if (empty($data['end_time'])) {
			$data['end_time'] = '2038-01-01 00:00:00';
		}
		$this->db->where('id', $id)->update('bulletins', $data);
	}
	
	function delete_bulletin($id)
	{
		$this->db->where('id', $id)->delete('bulletins');
		return $this->db->affected_rows();
	}
}