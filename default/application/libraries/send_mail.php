<?php
    /**
     * 
     *  專案 phpMailer 寄信程式
     *
     */
    require_once(dirname(__FILE__).'/class.phpmailer.php');

    class Send_Mail extends PHPMailer
    {
        /** 環境 **/
        public $smtp_host = "edm.longeplay.com.tw";
        public $smtp_user= "no-reply";  // SMTP username
        public $smtp_passwd= "noxj/6u4reply"; // SMTP password
        public $smtp_from= "no-reply@edm.longeplay.com.tw";
        public $smtp_from_name= "longeplay service";
        public $smtp_subject= "LongE Service";
        public $objMail = '';

        function __construct()
		{
        }
        
        function mailVerify()
		{
            return false;
        }

        function passwdResetMail($toAddress, $account, $newPassword)
		{
            $this->IsSMTP();
            $this->SMTPAuth = true;
            $this->Host = $this->smtp_host;
            $this->Port = 25;
            $this->Username = $this->smtp_user;
            $this->Password = $this->smtp_passwd;
            $this->AddReplyTo($this->smtp_from, $this->smtp_from_name);
            $this->SetFrom($this->smtp_from, $this->smtp_from_name);
            $this->AddAddress($toAddress);
            $this->Subject = "龍邑會員密碼通知信[".date("Y/m/d H:i:s")."]";

            $body = "親愛的龍邑會員您好：\n<br />

                    以下為您的密碼資料，請妥善保管：<br />
                    -------------------------------------------------------------------------------<br />
                    ※ 新密碼 : $newPassword (請注意：原密碼已失效)<br />
                    -------------------------------------------------------------------------------<br />
                    如果您並未查詢且要求郵寄密碼，請立即回到【會員專區】檢視您的會員資料並<br />
                    變更密碼，以防止您的帳號遭濫用。<br />
                    <br />
                    龍邑遊戲敬上<br />
                    -------------------------------------------------------------------------------<br />
                    ＊此為系統發出信件，請勿直接回覆；若您對本信函有疑問，請至客服中心留言<br />
                    ";  

            $this->AltBody = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
            $this->MsgHTML($body);

            if(!$this->Send())
			{
              return false;
            } else {
              return true;
            }
        }
    }
?>
