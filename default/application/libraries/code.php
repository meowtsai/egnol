<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class Code
{
    var $CI;
    var $event;

    function __construct($param)
    {    
    	$this->CI =& get_instance();   	
    	$this->event = $param["event"];
    }
        
    function chk_matched($uid)
    {
    	$cnt = $this->CI->db->from("codes")
    		->where("uid", $uid)->where("event", $this->event)
    		->count_all_results();
    	return $cnt > 0;
    }
    
    function chk_code_enough()
    {
    	$cnt = $this->CI->db->from("codes")
    		->where("uid is null", null, false)->where("event", $this->event)->where("lock", "0")
    		->count_all_results();
    	return $cnt > 0;
    }
    
    function match_code($uid)
    {
    	$this->CI->db->set("uid", $uid)->set("get_date", "NOW()", false)
    		->where("uid is null", null, false)->where("event", $this->event)->where("lock", "0")
    		->order_by("id")
    		->limit(1)->update("codes");
    }
    
    function get_user_code($uid)
    {
    	$row = $this->CI->db->from("codes")
    		->where("uid", $uid)->where("event", $this->event)
    		->get()->row();
    	return $row ? $row->code : false;	
    }
}
