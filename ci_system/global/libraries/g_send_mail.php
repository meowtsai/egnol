<?php
    /**
     * 
     *  專案 phpMailer 寄信程式
     *
     */
    require_once(dirname(__FILE__).'/class.phpmailer.php');

    class G_Send_Mail extends PHPMailer
    {
        var $CI;
        
        /** 環境 **/
        public $smtp_host = "edm.longeplay.com.tw";
        public $smtp_user= "no-reply";  // SMTP username
        public $smtp_passwd= "noxj/6u4reply"; // SMTP password
        public $smtp_from= "no-reply@edm.longeplay.com.tw";
        public $smtp_from_name= "longeplay service";
        public $smtp_subject= "LongE Service";

        function __construct()
		{
            $this->CI =& get_instance();  
        }
        
        function mailVerify()
		{
            return false;
        }

        function send_view($toAddress, $subject, $view, $data, $imgs=null)
		{
			$this->ClearAddresses();
			$this->ClearReplyTos();
			$this->ClearAttachments();
			
            $this->IsSMTP();
            $this->SMTPAuth = true;
            $this->Host = $this->smtp_host;
            $this->Port = 25;
            $this->Username = $this->smtp_user;
            $this->Password = $this->smtp_passwd;
            $this->AddReplyTo($this->smtp_from, $this->smtp_from_name);
            $this->SetFrom($this->smtp_from, $this->smtp_from_name);
            $this->AddAddress($toAddress);
            $this->Subject = $subject;
			$this->IsHTML(true);

			if(!empty($imgs))
			{
				foreach($imgs as $imgName => $imgPath)
				{
					$this->AddEmbeddedImage($imgPath, $imgName);
				}
			}
			$this->Body = $this->CI->load->view("mail/".$view, $data, true);
            $this->AltBody = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test

            if(!$this->Send())
			{
              return false;
            } else {
              return true;
            }
        }

        function passwdResetMail($toAddress, $newPassword)
		{
			return $this->send_view($toAddress,
									"龍邑會員密碼通知信[".date("Y/m/d H:i:s")."]",
									"g_pwd_reset_mail",
									array("password" => $newPassword),
									array("headerimg" => FCPATH."/p/image/mail/header.jpg"));
        }
    }
?>
