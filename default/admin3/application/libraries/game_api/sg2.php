<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class Sg2
{
    var $CI;
    var $game;

    function __construct()
    {    
    	$this->CI =& get_instance();
    	$this->game = "sg2";
    }
    
    function update_role()
    {
    	return; //沒寫完
    	
    	$query = $this->CI->db->from("servers")->where("game_id", $this->game)->order_by("id","desc")->get();
    	foreach($query->result() as $row) 
    	{
    		$server_id = $row->id;
    		$ser = "s".strtr($row->server_id, array("dh"=>""));
    		    		
    		$last_row = $this->CI->db->from("characters")->where("server_id", $server_id)->order_by("create_time", "desc")->limit(1)->get()->row();
    		if ($last_row) {  //檢查資料庫內有無資料,若有資料取最後一筆時間開始撈資料
    			$sdate = strtotime($last_row->create_time)+1;
    			//$edate = time();
    		}
    		else { //若無資料則開始時間從2012年1月1日開始撈資料
    			$sdate = strtotime("2012-01-01 01:00:00" );
    			//$edate = time();
    		}
    		if (rand(1,60) == 1) { //留機會更新到現在
    			$edate = time(); 
    		}
    		else $edate = $sdate + (60*60*3); //三小時內都沒新角色，就跳過
    		 
    		$url = 'http://interface.forgamecenter.com/api_create_role.php?game=dhcq&op=long_e&server='.$ser.'&startdate='.$sdate.'&enddate='.$edate ;
    		echo $url.'<span style="color:green; margin-left:12px;">start...</span><br>';
    		$file = json_decode(file_get_contents($url), true);
    		 
    		if (is_array($file) && count($file)>0) //驗證取得資料筆數是否大於0start 
    		{
    			$cnt = 0;
    			foreach ($file as $value) 
    			{
    				$account = $value['account'];
    				$character_name = $value['name'];
    				$create_time = $value['DateTime'];
    				
    				if (empty($account) || empty($character_name) || empty($create_time)) continue;
    				
    				$create_status = 1;
    				$query = $this->CI->db->from("characters gr")->join("servers gi", "gr.server_id=gi.id")
    					->where("gi.game_id", "dh")->where("account", $account)->get();
    				if ($query->num_rows() > 0) {
    					foreach($query->result() as $row) {
    						if ($row->server_id == $server_id) {
    							$create_status = 0;
    							break;
    						}
    					}
    				}
    				else $create_status = 2;
    				
    				$data = array(
						'account' => $account,
						'character_name' => $character_name,
						'create_time' => $create_time,
						'server_id' => $server_id,
						'create_status' => $create_status,
    				);    		
    				$this->CI->db->insert('characters', $data);    	
    				$cnt++;
    			}
    			echo "<span style='color:blue;'>新增了 {$cnt} 筆角色</span><br>";
    		}	
    	}    
    	echo "<div style='margin-top:30px; font-weight:bold;'>10秒後自動關閉</div>";
    	echo "<script type='text/javascript'>
    				function clock() {
    					i=i-1;
    					if (i>0) setTimeout('clock();', 1000);
    					else self.close();
    				}
    				var i=10;
    				clock();
    			</script>";
    }
}

/* End of file Template.php */
/* Location: ./system/application/libraries/Template.php */