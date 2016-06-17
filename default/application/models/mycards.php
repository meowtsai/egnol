<?php

class Mycards extends CI_Model {

	function get_billing_row($where_array)
	{
		return $this->db->where($where_array)
			->from("mycard_billing")->get()->row();
	}
	
	function insert_billing($data)
	{
		$this->db
			->set('create_time', 'NOW()', FALSE)
			->insert("mycard_billing", $data);
        
        return $this->db->insert_id();
	}
	
	function update_billing($data, $where_array)
	{
		$this->db->where($where_array)
			->set('update_time', 'NOW()', FALSE)
			->update("mycard_billing", $data);
	}
	
	function update_billing_note($note, $where_array)
	{
		$this->db->where($where_array)
			->set('update_time', 'NOW()', FALSE)
			->set("note", $note)
			->update("mycard_billing");
	}
	
	function update_billing_status($status, $where_array)
	{
		$this->db->where($where_array)
			->set('update_time', 'NOW()', FALSE)
			->set("status", $status)
			->update("mycard_billing");
	}
	
	function check_value_exists($field, $value)
	{
		$cnt = $this->db->from("mycard_billing")->where($field, $value)->count_all_results();
		return $cnt > 0;
	}
	
}