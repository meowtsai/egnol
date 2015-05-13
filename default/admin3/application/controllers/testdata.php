<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Testdata extends CI_Controller {
	
	//function __construct() 
	//{
	//	parent::__construct();					
	//}		
	function evil_laugh()
	{
		echo "MUHUAHAA";
	}
	
	function generate_logout_time()
	{
		$this->lang->load('db_lang', 'zh-TW');
		
		$query = $this->db->get("log_game_logins");
		
		if ($query->num_rows() > 0) {
		    foreach ($query->result() as $row) {
				$rand = rand(100, 10000);
				
				$gen_logout_time = date('Y-m-d H:i:s', strtotime($row->create_time) + $rand);
				
			    $data = array(
				    'logout_time' => $gen_logout_time
			    );
				
				if (empty($row->logout_time)) $this->db->where("id", $row->id)->update("log_game_logins", $data);
			}
		}
	}
	
	function generate_consume()
	{
		$this->lang->load('db_lang', 'zh-TW');
		
		$query = $this->db->get("log_game_logins");
		
		if ($query->num_rows() > 0) {
		    foreach ($query->result() as $row) {
				$rand = rand(1, 5);
				
				if ($rand == 5) {
					$rand_amount = rand(1, 10)*rand(10, 100);
					$rand_time = rand(100, 1000);
				    $gen_time = date('Y-m-d H:i:s', strtotime($row->create_time) + $rand_time);
				
			        $data = array(
				        'uid' => $row->uid,
					    'server_id' => $row->server_id,
					    'game_id' => $row->game_id,
					    'account' => $row->account,
					    'ip' => $row->ip,
					    'amount' => $rand_amount,
				        'create_time' => $gen_time
			        );
				
				    $this->db->insert("log_game_consumes", $data);
			    }
			}
		}
	}
}

/* End of file search.php */
/* Location: ./application/controllers/search.php */