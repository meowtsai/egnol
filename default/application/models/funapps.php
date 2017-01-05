<?php

class Funapps extends CI_Model {

	function get_billing_row($where_array)
	{
		return $this->db->where($where_array)
			->from("funapp_billing")->get()->row();
	}
	
	function insert_billing($data)
	{
		$this->db
			->set('create_time', 'NOW()', FALSE)
			->insert("funapp_billing", $data);
        
        return $this->db->insert_id();
	}
	
	function update_billing($data, $where_array)
	{
		$this->db->where($where_array)
			->set('update_time', 'NOW()', FALSE)
			->update("funapp_billing", $data);
	}
	
	function update_billing_note($note, $where_array)
	{
		$this->db->where($where_array)
			->set('update_time', 'NOW()', FALSE)
			->set("note", $note)
			->update("funapp_billing");
	}
	
	function update_billing_status($status, $where_array)
	{
		$this->db->where($where_array)
			->set('update_time', 'NOW()', FALSE)
			->set("status", $status)
			->update("funapp_billing");
	}
	
	function check_value_exists($field, $value)
	{
		$cnt = $this->db->from("funapp_billing")->where($field, $value)->count_all_results();
		return $cnt > 0;
	}
	
}