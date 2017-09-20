<?php

class G_Characters extends CI_Model {

	function create_character($server, $data)
	{
		if (is_array($server)) $server = (object)$server;
		if ($data["uid"] == "0" && $data["partner_uid"] == "0") return false; 
		
		$create_status = 1;
		$cnt = $this->db->from("characters gr")->where("uid", $data["uid"])->count_all_results();

    	if ($cnt > 0) {
	    	$query = $this->db->from("characters gr")->join("servers gi", "gr.server_id=gi.server_id")
	    					->where("gi.game_id", $server->game_id)->where("uid", $data["uid"])->get();
	
	    	if ($query->num_rows() > 0) {
	    		foreach($query->result() as $row) {
	    			if ($row->server_id == $server->server_id) {
	    				$create_status = 0;
	    				break;
	    			}
	    		}
	    	}
	    	else $create_status = 2;
    	}
    	else $create_status = 3;
		
		$data['server_id'] = $server->server_id;
		$data['create_status'] = $create_status;		
		if (empty($data["ad"])) $data["ad"] = ""; //預設放空白
		if (empty($data["create_time"])) $this->db->set("create_time", "now()", false);
		
		$this->db->insert("characters", $data);
		return $this->db->insert_id();
	}
	
	function update_character($data, $where_array)
	{
		$this->db->where($where_array)
			->update("characters", $data);
		
		return $this->db->affected_rows();
	}
	
	function chk_character_exists($server, $uid, $name)
	{
		$this->db->from("characters")
			->where("uid", $uid)
			->where("name", $name)
			->where("server_id", $server->server_id);
		
		return $this->db->count_all_results() > 0;
	}

	function get_character($server, $uid, $name)
	{
		$query = $this->db->from("characters")
							->where("uid", $uid)
							->where("name", $name)
							->where("server_id", $server)
							->get();

		return $query->row();
	}

	function get_latest_character($server, $uid)
	{
		$query = $this->db->from("characters")
							->where("uid", $uid)
							->where("server_id", $server)
							->order_by("create_time")
							->limit(1)
							->get();

		return $query->row();
	}
}