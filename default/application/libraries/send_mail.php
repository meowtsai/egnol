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
        public $Host = "";
        public $smtp_host = "";
        public $smtp_user="";  // SMTP username
        public $smtp_passwd= ""; // SMTP password
        public $smtp_from="service@longeplay.com.tw";
        public $smtp_from_name="service";
        public $smtp_subject="longE Service";
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
            $this->SMTPSecure = "tls";
            $this->Host = $this->smtp_host;
            $this->Port = 587;
            $this->Username = $this->smtp_user;
            $this->Password = $this->smtp_passwd;
            $this->AddReplyTo($this->smtp_from, $this->smtp_from_name);
            $this->SetFrom($this->smtp_from, $this->smtp_from_name);
            $this->AddAddress($toAddress);
            $this->Subject = "[".date("Y/m/d H:i:s")."]密碼通知信";

            $body = "親愛的會員您好：\n <br />

                    以下為您的帳號密碼資料，請妥善保管：\n <br />
                    ----------------------------------------------------------------------------------------------------------------- \n <br />
                    ※ 帳號 : $account \n <br />
                    ※ 新密碼 : $newPassword (請注意：原密碼已失效) \n <br />
                    ----------------------------------------------------------------------------------------------------------------- \n <br />
                    如果您並未查詢且要求郵寄密碼，請立即回到【會員專區】檢視您的會員資料並 \n <br />
                    變更密碼，以防止您的帳號遭濫用。 \n <br />
                     \n <br />
                    龍邑遊戲敬上：http://www.longeplay.com.tw/   \n <br />
                    ----------------------------------------------------------------------------------------------------------------- \n <br />
                    ＊此為系統發出信件，請勿直接回覆；若您對本信函有疑問，請至客服中心留言  \n <br />
                    ";  

            //$body = preg_replace("/\/",'',$body);

            $this->AltBody = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
            $this->MsgHTML($body);

            //$mail->AddAttachment("images/phpmailer.gif");      // attachment
            //$mail->AddAttachment("images/phpmailer_mini.gif"); // attachment

            if(!$this->Send())
			{
              return false;
            } else {
              return true;
            }
        }
    }
?>
