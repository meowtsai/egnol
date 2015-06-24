<?php
    /**
     * 
     *  Cooz 專案phpMailer寄信程式
     *  Author: EddieHan
     */
    require_once(dirname(__FILE__).'/class.phpmailer.php');

    class Cooz_Mailer extends PHPMailer 
    {
        /** 環境 **/
        public $Host = '';
        //public $DBUser = '';
        //public $DBPass = '';
        //public $DB = '';
        public $smtp_host = 'smtp.gmail.com';
        public $smtp_user="xijunlin@163.com";  // SMTP username
        public $smtp_passwd= "xjl-9511143"; // SMTP password
        public $smtp_from="service@molibee.com";
        public $smtp_from_name="service";
        public $smtp_subject="Cooz Service";    
        public $objMail = '';

        function __construct() {

        }
        
        function mailVerify() {
            return false;
        }

        function passwdResetMail($toAddress, $account, $newPassword, $realName) {
                                               
            $this->IsSMTP();
            
            $this->SMTPAuth = true;
            
            $this->SMTPSecure = "ssl";
            
            $this->Host = "smtp.gmail.com";
            
            $this->Port = 465;
            
            $this->Username = "service@longeplay.com.tw";
            
            $this->Password = "QWERT@long_e.web";
            
            $this->AddReplyTo("service@molibee.com","Cooz Service");

            $this->SetFrom('service@molibee.com', 'Cooz Servic');

            $this->AddReplyTo("service@molibee.com","Cooz Servic");

            $this->AddAddress($toAddress);

            $this->Subject = "[".date("Y/m/d H:i:s")."]密碼通知信";

            $body = "親愛的 $realName 您好：\n <br />

                    以下為您的帳號密碼資料，請妥善保管：\n <br />
                    ----------------------------------------------------------------------------------------------------------------- \n <br />
                    ※ 帳   號 : $account \n <br />
                    ※ 新密碼 : $newPassword (請注意：原密碼已失效) \n <br />
                    ----------------------------------------------------------------------------------------------------------------- \n <br />
                    如果您並未查詢且要求郵寄密碼，請立即回到【會員專區】檢視您的會員資料並 \n <br />
                    變更密碼，以防止您的帳號遭濫用。 \n <br />
                     \n <br />
                    龍邑 Web Game 平台敬上：http://www.longeplay.com.tw/   \n <br />
                    ----------------------------------------------------------------------------------------------------------------- \n <br />
                    ＊此為系統發出信件，請勿直接回覆；若您對本信函有疑問，請至客服中心留言  \n <br />
                    ";  

            $body = eregi_replace("[\]",'',$body);

            $this->AltBody = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test

            $this->MsgHTML($body);

            //$mail->AddAttachment("images/phpmailer.gif");      // attachment
            //$mail->AddAttachment("images/phpmailer_mini.gif"); // attachment

            if(!$this->Send()) {
              return false;
            } else {
              return true;
            }
        }

        function sendMsg() {
            
            $to     = empty($to)?'eddiehan@molibee.com':$to;
            
            $subject = empty($subject)?"[".date("Y/m/d H:i:s")."]Some thing happened":"[".date("Y/m/d H:i:s")."]".$subject;
            
            $message = $msg;

            $body             = $message;

            $body             = eregi_replace("[\]",'',$body);

            $this->AddReplyTo("eddiehan@molibee.com","WebAdmin");

            $this->SetFrom('eddiehan@molibee.com', 'WebAdmin');

            $this->AddReplyTo("eddiehan@molibee.com","WebAdmin");

            $address = $to;
            $this->AddAddress($address);

            $this->Subject    = $subject;

            $this->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test

            $this->MsgHTML($body);

            //$mail->AddAttachment("images/phpmailer.gif");      // attachment
            //$mail->AddAttachment("images/phpmailer_mini.gif"); // attachment

            if(!$this->Send()) {
              return false;
              //echo "Mailer Error: " . $mail->ErrorInfo;
            } else {
              return true;
              //echo "Message sent!";
            }
        }
    }
?>
