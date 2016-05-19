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
	
	function fix_ios_billing()
	{
		$this->lang->load('db_lang', 'zh-TW');
		
        $file = fopen("p/data/fix_ios_billing.csv", "r");


        while (!feof($file)) {
            $data = fgetcsv($file);
            
            $vendor_server_id = $data[];
            $ios_order_no = $data[];
            
            $vendor_server = $this->db->from("servers")->where("address", $vendor_server_id)->order_by("server_id")->get()->row();
            $server_id = $vendor_server->server_id;
            
			$this->DB1->where("transaction_type", "inapp_billing_ios")->where("order_no", $ios_order_no)->update('user_billing', array('server_id' => $server_id);
        }
	}
}

/* End of file search.php */
/* Location: ./application/controllers/search.php */
