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
        public $smtp_host = "mail.longeplay.com.tw";
        public $smtp_user= "no-reply@longeplay.com.tw";  // SMTP username
        public $smtp_passwd= "noxj/6u4reply"; // SMTP password
        public $smtp_from= "no-reply@longeplay.com.tw";
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
        log_message("error", "send_view: 1");
			$this->CI->load->library('email');

        log_message("error", "send_view: 2");
			$config['protocol'] = 'smtp';
			$config['smtp_user'] = $this->smtp_user;
			$config['smtp_pass'] = $this->smtp_passwd;
			$config['smtp_host'] = $this->smtp_host;
			$config['mailtype']  = 'html';

			$this->CI->email->initialize($config);

			$this->CI->email->from($this->smtp_from, '龍邑自動回覆系統');

			$this->CI->email->to($toAddress); 

			$this->CI->email->subject($subject);
			$this->CI->email->message($this->CI->load->view("mail/".$view, $data, true));	

            if(!$this->CI->email->send())
			{
        log_message("error", "send_view: 3");
              return false;
            } else {
        log_message("error", "send_view: 4");
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
