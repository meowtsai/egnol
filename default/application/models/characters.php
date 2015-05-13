<?php

class Characters extends CI_Model {

	function insert_character($data)
	{		    				
		$this->db->insert("characters", $data);
		return $this->db->insert_id();
	}
	
	/*
	function chk_exist_character($game_id, $account)
	{
		$this->db->from("characters")
			->where("account", $this->g_user->account)
			->where("server_id in (SELECT id FROM `servers` WHERE game_id='{$game_id}')", null, false);
		
		return $this->db->count_all_results() > 0;
	}
	*/
	
}