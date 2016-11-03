<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mobile extends MY_Controller
{
	function index()
	{		
		$this->load->model("g_bulletins");		
		$this->load->model("g_pictures");		
        
		$this->_init_layout()
			->add_css_link(array('mobile/normalize','mobile/animate','mobile/lity','mobile/layout','mobile/owl.carousel','mobile/owl.theme'))
            ->set("news", $this->g_bulletins->get_list("vxz", "", 7, $this->input->get("record")))
            ->set("slider_news_mobile", $this->g_pictures->get_list_by_category("vxz", "slider_news_mobile"))
            ->set("slider_about_mobile", $this->g_pictures->get_list_by_category("vxz", "slider_about_mobile"))
            ->set("slider_video_mobile", $this->g_pictures->get_list_by_category("vxz", "slider_video_mobile"))
            ->set("slider_heroes_mobile", $this->g_pictures->get_list_by_category("vxz", "slider_heroes_mobile"))
            ->mobile_view("mobile");
	}
	
	function e01_register()
	{
		// 讀取活動資料
		//$event = $this->db->from("events")->where("id", 3)->get()->row();
		$email = $this->input->get_post("email");
		$mobile = $this->input->get_post("mobile");
		$earlylogin_serial = $this->input->get_post("earlylogin_serial");
		$combo_serial = $this->input->get_post("combo_serial");
		$share_code = $this->input->get_post("share_code");
		$receive_code = $this->input->get_post("receive_code");
		
		if (!empty($receive_code)) {
			$receive_code_used = $this->db->from("event_serial")->where("event_id", 9)->where("email", NULL)->where("mobile", NULL)->where("receive_code", $receive_code)->get()->row();
			
			if (empty($receive_code_used)) {
				$receive_code = "此情緣合擊序號已領取";
			}
		}
		
		$this->_init_layout()
			->add_css_link(array('mobile_e01/default','mobile_e01/style','mobile_e01/reset','mobile_e01/colorbox','mobile_e01/animate'))
            ->set("email", $email)
            ->set("mobile", $mobile)
            ->set("share_code", ($share_code)?$share_code:"")
            ->set("receive_code", ($receive_code)?$receive_code:"")
            ->set("earlylogin_serial", ($earlylogin_serial)?$earlylogin_serial:"")
            ->set("combo_serial", ($combo_serial)?$combo_serial:"")
			->api_view();
	}
	
	function e01_register_json()
	{
		// 讀取活動資料
		header('content-type:text/html; charset=utf-8');
		
		//$event = $this->db->from("events")->where("id", 3)->get()->row();
		$email = $this->input->post("email");
		$mobile = $this->input->post("mobile");
		$receive_code = $this->input->get_post("receive_code");
		$share_success = 0;
		
		if (!empty($receive_code)) {
			$receive_code_used = $this->db->from("event_serial")->where("event_id", 9)->where("email", NULL)->where("mobile", NULL)->where("receive_code", $receive_code)->get()->row();
			
			if (empty($receive_code_used)) {
				die(json_failure('此情緣合擊序號已領取'));
			} else {
				$share_success = 1;
			}
		}
		
		if (!empty($email) && !empty($mobile)) {
			$check_code_used = $this->db->from("event_serial")->where("event_id", 8)->where("email", $email)->where("mobile", $mobile)->get()->row();

			if (empty($check_code_used)) {
				$check_used = $this->db->from("event_serial")->where("event_id", 8)->where("email", $email)->or_where("mobile", $mobile)->get()->row();
				
				if (empty($check_used)) {
					$earlylogin_code = $this->db->from("event_serial")->where("event_id", 8)->where("email", NULL)->where("mobile", NULL)->get()->row();
					
					$earlylogin_serial = $earlylogin_code->serial;
					
					$earlylogin_data = array(
					   'email' => $email,
					   'mobile' => $mobile
					);

					$this->db->where('id', $earlylogin_code->id);
					$this->db->update('event_serial', $earlylogin_data); 
					
					
					if ($share_success) {
						$combo_code1 = $this->db->from("event_serial")->where("event_id", 9)->where("share_code", $receive_code)->get()->row();
						
						$combo_data1 = array(
						   'status' => 1
						);
						
						$this->db->where('id', $combo_code1->id);
						$this->db->update('event_serial', $combo_data1); 

						$combo_code2 = $this->db->from("event_serial")->where("event_id", 9)->where("receive_code", $receive_code)->get()->row();
						
						$combo_serial = $combo_code2->serial;
						
						$combo_data2 = array(
						   'status' => 1,
						   'share_code' => md5($email.$mobile.'9')
						);
						
						$this->db->where('id', $combo_code2->id);
						$this->db->update('event_serial', $combo_data2); 
					} else {
						$combo_code1 = $this->db->from("event_serial")->where("event_id", 9)->where("email", NULL)->where("mobile", NULL)->get()->row();
						
						$combo_data1 = array(
						   'email' => $email,
						   'mobile' => $mobile,
						   'status' => 0,
						   'share_code' => md5($email.$mobile.'9')
						);
						
						$this->db->where('id', $combo_code1->id);
						$this->db->update('event_serial', $combo_data1); 
					}

					$combo_code3 = $this->db->from("event_serial")->where("event_id", 9)->where("email", NULL)->where("mobile", NULL)->get()->row();

					$share_code = md5($email.$mobile.'9');
					
					$combo_data3 = array(
					   'status' => 0,
					   'receive_code' => $share_code
					);

					$this->db->where('id', $combo_code3->id);
					$this->db->update('event_serial', $combo_data3); 
				} else {
					die(json_failure('用戶資訊已被使用'));
				}
			} else {
				$earlylogin_serial = $check_code_used->serial;
				
				$combo_code = $this->db->from("event_serial")->where("event_id", 9)->where("email", $email)->where("mobile", $mobile)->get()->row();
				
				if(!empty($combo_code) && $combo_code->status == 1) {
					$combo_serial = $combo_code->serial;
				} else {
					$combo_serial = "";
				}
				
				$share_code = $combo_code->share_code;
			}
		}
		
		$earlylogin_serial=($earlylogin_serial)?$earlylogin_serial:"";
		$combo_serial=($combo_serial)?$combo_serial:"";
		$share_code=($share_code)?$share_code:"";
		
		die(json_message(array("message"=>"成功", "email"=>$email, "mobile"=>$mobile, "earlylogin_serial"=>$earlylogin_serial, "combo_serial"=>$combo_serial, "share_code"=>$share_code), true));
	}
}
