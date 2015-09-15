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

		var $body1 = "<!DOCTYPE html>
<html><head><meta charset=\"utf-8\"><meta name=\"viewport\" content=\"width=device-width,initial-scale=1\"></head>
<body style=\"padding:0; margin:.5%; background-color:#cccccc;\">
<style>
@media screen and (max-width: 480px), only screen and (device-aspect-ratio: 40/71), only screen and (device-aspect-ratio: 2/3)
{
.nl-top-cell-with-bg { background-position: 50% center; }
#wdhd-table { margin:0;}
.gap-cell { width:0px !important;}
.post-cell { background:#ffffff; padding:20px; }
.post-cell h1 { font-size:18px !important; line-height:25px !important;}
.post-cell p { font-size:14px; line-height:24px; }
.post-cell img{ width:100%; max-width:100%; height:auto;}
.pcont-text { margin-right:0px !important; }
.top-unsub-txt,
.hhh-cell, .shd-row , .shd-cell, .sep-row{ display:none;}
.issue-cell span { font-weight:bold; font-size:14px !important; padding-right:10px; }
.hh-table, .content-table { width:100% !important; max-width:100%;}
.content-table { background:#cccccc;}
.mid-row { display:none; height:14px !important;}
table  {max-width:100%; width:100%;}
}
</style>
<table width=\"100%\" bgcolor=\"#cccccc\" style='float:left;background-color:#cccccc;font-family:\"微軟正黑體\", \"Microsoft JhengHei\", Arial, Helvetica, sans-serif;'>
<tr>
<td align=\"center\">
<table cellspacing=\"0\" cellpadding=\"0\" border=\"0\">
<tr>
<td bgcolor=\"#fff\" class=\"nl-top-cell-with-bg\" width=\"600\" height=\"100\" valign=\"middle\"><img src=\"cid:headerimg\" /><table width=\"100%\" cellspacing=\"0\" valign=\"middle\" cellpadding=\"0\">
</td>
</tr>
</table>
<table width=\"600\" class=\"content-table\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#ffffff\">
<tr class=\"sep-row\"><td height=\"18\"></td></tr>
<tr>
<td>
<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" >
<tr>
<td width=\"70\" class=\"gap-cell\"></td>
<td class=\"post-cell\" valign=\"top\" style='color:#333333; font-size:14px; line-height:20px;font-family:\"微軟正黑體\", \"Microsoft JhengHei\", Arial, Helvetica, sans-serif;'>
<div class=\"pcont-text\">
<h1 style='font-size:18px; font-weight:bold; line-height:30px;'>親愛的龍邑會員您好，</h1>
<p style=\"line-height:30px;\">以下為您的密碼資料，請妥善保管：<br/>
※ 新密碼 : ";
		var $body2 = " (請注意：原密碼已失效)<br />
<br />
如果您並未查詢且要求郵寄密碼，請立即回到【會員專區】檢視您的會員資料並變更密碼，以防止您的帳號遭濫用。<br />
<br />
龍邑遊戲敬上<br />
<br />
＊此為系統發出信件，請勿直接回覆；若您對本信函有疑問，請至客服中心留言<br /></p>
<h1 style='font-size:18px; font-weight:bold; text-align:right; margin-top:30px;'>會員中心 敬啟</h1></div>
</td>
<td width=\"70\" class=\"gap-cell\"></td>
</tr>
</table>
</td>
</tr>
<tr class=\"mid-row\"><td height=\"18\"></td></tr>
<tr >
<td>
<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\">
<tr>
<td bgcolor=\"#eeeeee\" height=\"1\" ></td>
</tr>
</table>
</td>
</tr>
<tr>
<td bgcolor=\"#FFFFFF\" height=\"60\" style='font-family: Arial, Helvetica, sans-serif;color:#666666; font-size:9px;text-align:center'>Long E Co., Ltd © 2015 Copyrights Reserved.</td>
</tr>
</table>
</td>
</tr>
</table>
</td>
</tr>
</table>
</body>
</html>";

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
			$this->IsHTML(true);

			$this->AddEmbeddedImage(dirname(__FILE__)."/header.jpg", "headerimg");
/*
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
*/
			$this->Body = $this->body1.$newPassword.$this->body2;
            $this->AltBody = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test

            if(!$this->Send())
			{
              return false;
            } else {
              return true;
            }
        }
    }
?>
