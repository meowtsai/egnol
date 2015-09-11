<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ajax extends MY_Controller {
	
	function __construct() 
	{
		parent::__construct();				
	}
		
	function ad_channel_autocomplete()
	{
		$q = $this->input->get("q");

		$query = $this->DB2->distinct()->select('ad')->from('characters')
			->where("create_time>=date_sub(now(), interval 30 day) and ad is not null and ad <> ''", null, false)
			->like('ad', $q, 'after')
			->order_by('ad')->get();
 
		foreach ($query->result() as $row) {
			echo $row->ad."\n";
		}
	}

	function auto_close()
	{
		$this->DB1->where("status", "2")->where("is_read", "1")->where("create_time < DATE_SUB(CURDATE(), INTERVAL 3 DAY)", null, false)->update("questions", array("status"=>"4"));
		$this->DB1->where("status", "2")->where("create_time < DATE_SUB(CURDATE(), INTERVAL 7 DAY)", null, false)->update("questions", array("status"=>"4"));
	}
}
