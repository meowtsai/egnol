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
    // if ($user_ip!="61.220.44.200")
    // {
    //   die(json_encode(array("status"=>"failure", "message"=>"illegal ip")));
    // }

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


  function h55_prereg_report_summary()
  {
    header('Access-Control-Allow-Origin: *');
    $user_ip = $_SERVER['REMOTE_ADDR'];
    // if ($user_ip!="61.220.44.200")
    // {
    //   die(json_encode(array("status"=>"failure", "message"=>"illegal ip")));
    // }

    // $query = $this->db->query("Select country, DATE_FORMAT(create_time,'%Y-%m-%d') as dDate,count(distinct email) as count from h55_prereg
    // group by country, DATE_FORMAT(create_time,'%Y-%m-%d') order by DATE_FORMAT(create_time,'%Y-%m-%d') desc, count(distinct email) desc");

    $query = $this->db->query("Select DATE_FORMAT(create_time,'%Y-%m-%d') as dDate,count(distinct email) as count from h55_prereg
    group by DATE_FORMAT(create_time,'%Y-%m-%d') order by DATE_FORMAT(create_time,'%Y-%m-%d') desc, count(distinct email) desc");
    $data = array();
    foreach($query->result() as $row) {
      $data[] = array(
        'dDate' => $row->dDate,
        'count' => $row->count,
      );
    }

    $query = $this->db->query("Select country,count(distinct email) as count from h55_prereg
    group by country order by count(distinct email) desc");
    $data2 = array();
    foreach($query->result() as $row) {
      $data2[] = array(
        'country' => $row->country,
        'count' => $row->count,
      );
    }

    $query = $this->db->query("select distinct status,count(*) as count from h55_prereg group by status order by status");
    $data3 = array();
    foreach($query->result() as $row) {
      $data3[] = array(
        'status' => $row->status,
        'count' => $row->count,
      );
    }

    die(json_encode(array("status"=>"success", "message"=>$data, "message2"=>$data2,"message3"=>$data3)));


  }

  function h55_prereg_check()
  {
    header('Access-Control-Allow-Origin: *');
    $date_now = date("Y-m-d H:i:s");
    $date_end = date("Y-m-d", strtotime("2018-07-12"));
    //echo $date_now."<br/>";
    //echo $date_end."<br/>";
    if ($date_now > $date_end)
    {
      //echo "已經結束";
      echo "NOK";
    }
    else {
      echo "OK";
    }

  }

  function h55_prereg()
  {
    header('Access-Control-Allow-Origin: *');
    die(json_encode(array("status"=>"failure", "message"=>"活動已經結束了唷!")));
    //// TODO:  要改成post, 要視情況擋ip
    $user_email = $this->input->post("user_email");
    if (empty($user_email))
    {
      die(json_encode(array("status"=>"failure", "message"=>"請正確填寫需要的欄位")));
    }

    if(!filter_var($user_email, FILTER_VALIDATE_EMAIL))
    {
      die(json_encode(array("status"=>"failure", "message"=>"E-Mail 格式錯誤。")));
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

    $query->free_result();
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
            https://www.facebook.com/IdentityVgame/<br />

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
              //$this->db->where("email", $user_email)->update("h55_prereg", array("status" => 0));
              die(json_encode(array("status"=>"failure", "message"=>"E-Mail 發送失敗。請確認E-mail為有效信箱。")));
            }
          }
        else {
          // update h55_prereg set status='0' where email ='event_info2@1.1';
          //$this->db->where("email", $user_email)->update("h55_prereg", array("status" =>'0'));
          die(json_encode(array("status"=>"failure", "message"=>"E-Mail 格式錯誤。")));
        }
  }

  function get_event_status($e_id){
    $http_origin = $_SERVER['HTTP_ORIGIN'];

    if ($http_origin == "https://meowroll.com" || $http_origin == "https://yujian-love.com" || $http_origin == "https://www.yujian-love.com" )
    {
        header("Access-Control-Allow-Origin: $http_origin");
    }
    $query = $this->db->from("events")
    ->where("id", $e_id)
    ->select("id,game_id,event_name,status,begin_time,end_time")
    ->get();
    if ($query->num_rows()>0)
    {
      $event = $query->row();
      if (($event->status=='1' && now() > $event->begin_time && now() < $event->end_time) || IN_OFFICE)
      {
        die(json_encode(array("status"=>"success", "message"=>$event)));
      } else {
        die(json_encode(array("status"=>"failure", "message"=>"活動未開放")));
      }
    }
    else {
      die(json_encode(array("status"=>"failure", "message"=>"沒有這個活動")));
    }
  }

  function check_user_data(){
    $http_origin = $_SERVER['HTTP_ORIGIN'];
    if ($http_origin == "https://meowroll.com" || $http_origin == "https://yujian-love.com" || $http_origin == "https://www.yujian-love.com" )
    {
        header("Access-Control-Allow-Origin: $http_origin");
    }
    $event_id = $this->input->get_post("eid");
    $uid = $this->input->get_post("uid");
    $accessToken = $this->input->get_post("accessToken");

    //檢查是否fb token為真

    $fb_result = json_decode($this->check_fb_user($accessToken));

    // {
    //   "id": "10213412799650864",
    //   "name": "Sophie Tsai",
    //   "email": "shihfan.tsai\u0040gmail.com"
    // }
    //echo json_encode($fb_result)."<br />";
    if ($fb_result && $fb_result->id==$uid)
    {
        //echo $_SESSION['event12_uid']."<br />";
      $this->db->where("event_id", $event_id)->where("uid", $uid)->update("event_preregister", array("auth_code"=>$accessToken,"update_time"=>"now()"));

      $result = $this->_check_user_data($event_id,$uid);
      if ($result["status"]=="success")
      {
        $daily_result = $this->_l20na_daily_login($result["message"]->id);
        $result["reward"]=$daily_result["message"];
      }
      die(json_encode($result));
    }
    else {
      die(json_encode(array("status"=>"failure", "message"=>"fb 資料驗證失敗。")));
    }
  }

  function _l20na_daily_login($uid){
       //$uid = $_SESSION['event12_uid'];

       if ($uid)
       {
         $query = $this->db->query("SELECT count(*) as chk from l20na_orders where event_uid={$uid} and date=curdate()");
         if (!$query->row()->chk){
           $today = date("Ymd");
           $item_sp = $this->db->query("call create_l20na_orders(curdate(),'逆水寒 {$today} 登入禮','{$uid}')");
         }
         $query = $this->db->query("SELECT `desc`, create_time,
           (select count(*) from l20na_detail where o_id in(select id from l20na_orders where event_uid={$uid}) and status=1) as total
           from l20na_orders where event_uid={$uid} and date=curdate()");
         return array("status"=>"success", "message"=>$query->row());

       }
       else {
         return array("status"=>"failure", "message"=>"沒有登入資訊。");
       }

  }

  function _check_user_data($event_id,$uid){
    $query = $this->db->from("event_preregister")
    ->where("event_id", $event_id)
    ->where("uid", $uid)
    ->select("id,uid,nick_name,email,status")
    ->get();

    if ($query->num_rows()>0)
    {
      $user = $query->row();
      return array("status"=>"success", "message"=>$user);
    }
    else {
      return array("status"=>"failure", "message"=>"尚未完成活動");
    }
  }

  function user_register(){
    $http_origin = $_SERVER['HTTP_ORIGIN'];

    if ($http_origin == "https://meowroll.com" || $http_origin == "https://yujian-love.com" || $http_origin == "https://www.yujian-love.com" )
    {
        header("Access-Control-Allow-Origin: $http_origin");
    }
    $event_id = $this->input->get_post("eid");
    $uid = $this->input->get_post("uid");
    $email = $this->input->get_post("email");
    $personal_id = $this->input->get_post("personal_id");
    $accessToken =  $this->input->get_post("accessToken");
    $fb_result = json_decode($this->check_fb_user($accessToken));

    if (!$fb_result || $fb_result->id!=$uid)
    {
      die(json_encode(array("status"=>"failure", "message"=>"fb 資料驗證失敗。")));
    }

    $result = $this->_check_user_data($event_id,$uid);
    if ( $result["status"]=="success") //已經註冊完畢
    {
      $daily_result = $this->_l20na_daily_login($result["message"]->id);
      $result["reward"]=$daily_result["message"];
      die(json_encode($result));
    }
    else {
      $query = $this->db->from("event_preregister")
      ->where("event_id", $event_id)
      ->where("email", $email)
      ->select("uid,nick_name,email,status")
      ->get();

      if ($query->num_rows()>0)
      {
        die(json_encode(array("status"=>"failure", "message"=>"該 E-mail 已經被使用。")));
      }

      $user_ip = $_SERVER['REMOTE_ADDR'];
      $country_name = "";
      if ($user_ip)
      {
        $country_name =geoip_record_by_name($user_ip)["country_name"];
      }
      if(filter_var($email, FILTER_VALIDATE_EMAIL)){
        $data = array(
          "event_id" => $event_id,
          "uid" => $uid,
          "email" => $email,
          'nick_name' => $personal_id,
          'ip' => $user_ip,
          'country' => $country_name,
          'auth_code' => $accessToken,
        );
        $this->db
          ->insert("event_preregister", $data);
          $u_id = $this->db->insert_id();
          if($u_id>0)
          {
            $item_sp = $this->db->query("call create_l20na_orders(curdate(),'預註冊成功送豪禮','{$u_id}')");
            $npc_sp = $this->db->query("call create_npc_affections('{$u_id}')");
            $result = $this->_check_user_data($event_id,$uid);
            $reward = "";
            if ($result["status"]=="success")
            {
              $daily_result = $this->_l20na_daily_login($result["message"]->id);
              $reward=$daily_result["message"];
            }

            die(json_encode(array("status"=>"success", "message"=>$result, "reward"=>$reward)));
          }
      }
      else {
        die(json_encode(array("status"=>"failure", "message"=>"請確認E-mail為有效信箱。")));
      }

    }
  }
  function check_fb_user($access_token)
  {
    $http_origin = $_SERVER['HTTP_ORIGIN'];
    if ($http_origin == "https://meowroll.com" || $http_origin == "https://yujian-love.com" || $http_origin == "https://www.yujian-love.com" )
    {
        header("Access-Control-Allow-Origin: $http_origin");
    }
    $facebook_url = "https://graph.facebook.com/v3.2/me?fields=id,name,email&access_token={$access_token}";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $facebook_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $output = curl_exec($ch);
    curl_close($ch);
    //echo $output;
    return $output;
  }

  function l20na_send_items(){
    //item_id=202&npc_id=151
      $http_origin = $_SERVER['HTTP_ORIGIN'];

    if ($http_origin == "https://meowroll.com" || $http_origin == "https://yujian-love.com" || $http_origin == "https://www.yujian-love.com" )
    {
        header("Access-Control-Allow-Origin: $http_origin");
    }
    $item_id = $this->input->get_post("item_id");
    $npc_id = $this->input->get_post("npc_id");
    //select count(*) as chk  from l20na_detail where id=54 and o_id in(select id from l20na_orders where event_uid=14);

    $accessToken = $this->input->get_post("accessToken");
    $user = $this->db->select("id, uid, nick_name,auth_code")
                ->where("event_id", 12)
                ->where("auth_code", $accessToken)
                ->from("event_preregister")
                ->get()->row();
    if (!$user){
      die(json_encode(array("status"=>"failure", "message"=>"User Not found, 沒有資料"))) ;
    }
    $uid=$user->id;

    $query = $this->db->query("SELECT count(*) as chk  from l20na_detail where id={$item_id} and o_id in(select id from l20na_orders where event_uid={$uid});");
    if (!$query->row()->chk){
      die(json_encode(array("status"=>"failure", "message"=>"資料驗證錯誤"))) ;
    }

    $item_sp = $this->db->query("call l20na_give_item('{$item_id}','{$npc_id}')");
    $data = array();
    foreach($item_sp->result() as $row) {
      $data[] = array(
        'rtn_code' => $row->rtn_code,
        'note' => $row->note,
        'npc_res' => $row->npc_res,
        'res_text' => $row->res_text,
        'res_vp' => $row->res_vp,

      );
    }
    if ($data[0]["rtn_code"]=="1")
    {
      die(json_encode(array("status"=>"success", "message"=>$data[0]))) ;
    }else {

      $about_empty = array("天也空 地也空 人生渺渺在其中",
      "日也空 月也空 東昇西墜為誰功",
      "金也空 銀也空 死後何曾在手中",
      "妻也空 子也空 黃泉路上不相逢",
      "權也空 名也空 轉眼荒郊塗一封",
      "酒也空 氣也空 世間浮華一陣風");


      $rand_keys = array_rand($about_empty, 2);
      //return $about_empty[$rand_keys[0]] . "\n";
      //die(json_encode(array("status"=>"failure", "message"=>$data[0]["note"]))) ;
      die(json_encode(array("status"=>"failure", "message"=>$about_empty[$rand_keys[0]]))) ;
    }

  }

  function l20na_get_npcs_items(){
    $http_origin = $_SERVER['HTTP_ORIGIN'];
    if ($http_origin == "https://meowroll.com" || $http_origin == "https://yujian-love.com" || $http_origin == "https://www.yujian-love.com" )
    {
        header("Access-Control-Allow-Origin: $http_origin");
    }

    $accessToken = $this->input->get_post("accessToken");
    $user = $this->db->select("id, uid, nick_name,auth_code")
                ->where("event_id", 12)
                ->where("auth_code", $accessToken)
                ->from("event_preregister")
                ->get()->row();
    if (!$user){
      die(json_encode(array("status"=>"failure", "message"=>"沒有資料"))) ;
    }
    $uid=$user->id;
    $query = $this->db->query("SELECT a.id, a.status,  b.item_code, b.item_name, b.item_pic,b.item_desc
    from l20na_detail a left join
    l20na_items b on a.item_code = b.item_code
    where a.o_id in(select id from l20na_orders
    where event_uid={$uid}) and a.status=1");
    $item_data = array();
    foreach($query->result() as $row) {
      $item_data[] = array(
        'id' => $row->id,
        'item_name' => $row->item_name,
        'item_code' => $row->item_code,
        'item_pic' => $row->item_pic,
        'item_desc' => $row->item_desc,
      );
    }

    $query = $this->db->query("SELECT a.id, a.affection, b.npc_name,b.npc_gender,b.npc_code,b.npc_pic
    from l20na_npc_affections a
    left join l20na_npcs b
    on a.npc_code = b.npc_code
    where a.event_uid={$uid}");
    $npc_data = array();
    foreach($query->result() as $row) {
      $npc_data[] = array(
        'id' => $row->id,
        'affection' => $row->affection,
        'npc_name' => $row->npc_name,
        'npc_gender' => $row->npc_gender,
        'npc_code' => $row->npc_code,
        'npc_pic' => $row->npc_pic,
      );
    }

    die(json_encode(array("status"=>"success", "message"=>array("player"=> $user, "items" => $item_data, "npcs"=> $npc_data) ))) ;

  }


}
