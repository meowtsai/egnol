<?php

class G_Games extends CI_Model {

	function get_game($game_id) 
	{
		$query = $this->db->from("games")->where("game_id", $game_id)->get();		
		return $query->num_rows() > 0 ? $query->row() : false; 
	}
	
	function get_server($id) 
	{
		$query = $this->db->select("gi.*, g.name as game_name")
			->from("servers gi")
			->join("games g", "gi.game_id=g.game_id")
			->where("gi.server_id", $id)->get();		
		return $query->num_rows() > 0 ? $query->row() : false; 
	}
	 
	function get_server_by_address($address)
	{
		return $this->db->from("servers")->where("address", $address)->order_by("server_id")->get()->row();
	}	
	
	function get_server_by_server_id($server_id)
	{
		return $this->db->from("servers")->where("server_id", $server_id)->get()->row();
	}		
		
	function get_server_list($game_id)
	{
		return $this->db->from("servers")->where("game_id", $game_id)->order_by("server_id", "desc")->get();
	}	
	
}