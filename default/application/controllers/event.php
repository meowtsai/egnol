<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Event extends MY_Controller
{
  function __construct()
  {
    parent::__construct();
  }

  function h55_prereg_report()
  {
    header('Access-Control-Allow-Origin: *');
    $user_ip = $_SERVER['REMOTE_ADDR'];
    if ($user_ip!="61.220.44.200")
    {
      die(json_encode(array("status"=>"failure", "message"=>"illegal ip")));
    }

    $query = $this->db->query('SELECT * FROM h55_prereg ORDER BY ID DESC');
    $data = array();
    foreach($query->result() as $row) {
      $data[] = array(
        'id' => $row->id,
        'email' => $row->email,
        'ip' => $row->ip,
        'country' => $row->country,
        'date' =>  date("Y/m/d", strtotime($row->create_time)),
        'status' => $row->status,
      );
    }

    die(json_encode(array("status"=>"success", "message"=>$data)));


  }


  function h55_prereg()
  {
    header('Access-Control-Allow-Origin: *');
    //// TODO:  要改成post, 要視情況擋ip
    $user_email = $this->input->get_post("user_email");
    if (empty($user_email))
    {
      die(json_encode(array("status"=>"failure", "message"=>"請正確填寫需要的欄位")));
    }
    $user_ip = $_SERVER['REMOTE_ADDR'];
    $country_name = "";
		if ($user_ip)
		{
			$country_name =geoip_record_by_name($user_ip)["country_name"];
		}

    //check if email exist
    //check if ip repeat over 5 times for the past 10 minutes
    //insert DATA
    // send mail
    //mark status as 0 if send mail failed
    $query = $this->db->query('call h55_prereg_insert("'.$user_email.'","'.$user_ip.'","'.$country_name.'")');


    $data = array();
    foreach($query->result() as $row) {
      $data[] = array(
        'id' => $row->id,
        'email' => $row->email,
        'date' =>  date("Y/m/d", strtotime($row->create_time)),
        'rtn_code' => $row->rtn_code,
      );
    }
  //  die(json_encode($data[0]["rtn_code"]));
    if ($data[0]["rtn_code"] =="5")
    {
      die(json_encode(array("status"=>"failure", "message"=>"請勿重複發送喔！")));
    }



    if(filter_var($user_email, FILTER_VALIDATE_EMAIL))
    {
        $msg = "我們已經收到您在『第五人格』的預註冊登錄資訊。<br />
            非常感謝您參與預註冊表示對我們的支持！<br />
            <br />
            今後我們會通過您的這個郵箱給您發送最新資訊。<br />
            請期待我們的正式版『第五人格』的發佈吧！^_^<br />
            <br />
            -------<br />
            另外，關於『第五人格』的最新資訊，<br />
            將會在官網及官方粉絲團中公佈。<br />

            『第五人格』官網<br />
            http://www.identity-v.com/<br />

            『第五人格』官方粉絲團<br />
            https://www.facebook.com/%E7%AC%AC%E4%BA%94%E4%BA%BA%E6%A0%BC-1925425637491722/<br />

            -------------------------------------------------------------------------------<br />
            ※這封郵件是系統自動發送。請勿回覆，謝謝！<br />

            ※今後，若對此類資訊不感興趣，可以停止郵件自動發送。<br />

            ※若您有問題或意見，請透過<a href='https://game.longeplay.com.tw/service_quick?site=long_e&param_game_id=h55naxx2tw'>線上系統</a>進行提報
            ";

            $this->load->library("g_send_mail");

        if ($this->g_send_mail->send_view($user_email,
            "『第五人格』預註冊成功通知",
            "g_blank_mail",
            array("game_name" => "『第五人格』", "msg" => $msg),
            array("headerimg" => FCPATH."/p/image/mail/header.jpg")))
            {
                //發送成功
              die(json_encode(array("status"=>"success", "site"=> $site, "message"=>"預註冊登錄成功！(OK)")));
            }
            else
            {
              //發送失敗標註
              $this->db->where("email", $user_email)->update("h55_prereg_insert", array("status" => 0));
              die(json_encode(array("status"=>"failure", "message"=>"E-Mail 發送失敗。請確認E-mail為有效信箱。(Send Failed)")));
            }
          }
        else {
          die(json_encode(array("status"=>"failure", "message"=>"E-Mail 格式錯誤。(Invalid Email)")));
        }
  }
}
