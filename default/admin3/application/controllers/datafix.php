<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Datafix extends CI_Controller {
	
	function __construct() 
	{
		parent::__construct();	
		$this->global_dir = BASEPATH.'../global/';
		$this->load->add_package_path($this->global_dir);
		$this->load->helper("g_common");
        
		$this->DB1 = $this->load->database('long_e', TRUE);
        $this->DB2 = $this->load->database('long_e_2', TRUE);
			
    	$query = $this->DB2->select("uid")->from("testaccounts")->get();

        $testaccounts = array();
        
		if ($query->num_rows() > 0) {
		    foreach ($query->result() as $row) {
			    $testaccounts[] = $row->uid;
		    }
		}
        
        $testaccounts_str = implode(",", $testaccounts);
        $this->testaccounts = $testaccounts_str;
	}
	
	/*function fix_ios_billing()
	{
		$this->lang->load('db_lang', 'zh-TW');
		
        $file = fopen("p/data/fix_ios_billing.csv", "r");


        while (!feof($file)) {
            $data = fgetcsv($file);
            
            $vendor_server_id = $data[];
            $ios_order_no = $data[];
            
            $vendor_server = $this->DB2->from("servers")->where("address", $vendor_server_id)->order_by("server_id")->get()->row();
            $server_id = $vendor_server->server_id;
            
			$this->DB1->where("transaction_type", "inapp_billing_ios")->where("order_no", $ios_order_no)->update('user_billing', array('server_id' => $server_id));
        }
	}*/
	
	function fix_gash_billing_server_id()
	{
		$this->lang->load('db_lang', 'zh-TW');
        
    	$query = $this->DB2->from("user_billing")->where("transaction_type", "gash_billing")->get();
        
		if ($query->num_rows() > 0) {
		    foreach ($query->result() as $row) {
                $query2 = $this->DB2->select("server_id")->from("characters")->where("id", $row->character_id)->get()->row();
			   
                if ($query2 && $query2->server_id) {
                    $this->DB1->where("id", $row->id)->update('user_billing', array('server_id' => $query2->server_id));
                }
		    }
		}
	}
	
	
	function fix_mycard_billing_transaction_id()
	{
		$this->lang->load('db_lang', 'zh-TW');
        
    	$query = $this->DB2->from("mycard_billing")
			->where("status", "4")
			->where("result", "1")
			->where("is_confirm", "1")
			->get();
        
		if ($query->num_rows() > 0) {
		    foreach ($query->result() as $row) {
                $this->DB1->where("mycard_billing_id", $row->id)->update('user_billing', array('transaction_id' => $row->trade_seq));
				
                $query2 = $this->DB2->select("id")->from("user_billing")
					->where("uid", $row->uid)
					->where("transaction_type", "top_up_account")
					->where("result", "1")
					->where("server_id", $row->server_id)
					->where("amount", $row->amount)
					->where("create_time >=", $row->cash_out_time)
					->order_by("create_time", "asc")
					->get()->row();
			   
                if ($query2 && $query2->id) {
                	$this->DB1->where("id", $query2->id)->update('user_billing', array('transaction_id' => $row->trade_seq));
                }
		    }
		}
	}
}

/* End of file search.php */
/* Location: ./application/controllers/search.php */
