<?php
    /**
     *
     *  yoyo8 簡訊系統發送
     *
     */
    class G_Send_sms
    {
		var $member_id  = 'longeplay';
		var $message = '';

        function __construct()
		{
        }

		function check_mobile_region($mobile)
		{
			// 886 台
			if(preg_match("/^8869\d{8}$/", $mobile) || preg_match("/^09\d{8}$/", $mobile))
				return 'tw';

			// 852 港
			if(preg_match("/^852[5-9]\d{7}$/", $mobile))
				return 'hk';

			// 853 澳
			if(preg_match("/^8536\d{7}$/", $mobile))
				return 'mo';

			return 'undefined';
		}

    function send($product_id, $phone_number, $msg)
		{
            srand((double)microtime()*1000000);
			$msg_id = time().rand(1,9999);
			$pwd = MD5("{$this->member_id}:leru03vmp4:YoYoSMS_{$product_id}:{$msg_id}");

			$msg = urlencode($msg);
			$url = "http://www.yoyo8.com.tw/SMSBridge.php"
					."?MemberID={$this->member_id}"
					."&Password={$pwd}"
					."&MobileNo={$phone_number}"
					."&CharSet=U"
					."&SMSMessage={$msg}"
					."&SourceProdID=YoYoSMS_{$product_id}"
					."&SourceMsgID={$msg_id}";

			if($this->check_mobile_region($phone_number) != 'tw')
			{
				$url = $url."&GlobalSms=Y";
			}

			$ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_HEADER, false);
			curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$curl_res = curl_exec($ch);
			curl_close($ch);

			$result = array();
			parse_str($curl_res, $result);

			if($result['status'] == '0')
			{
				// 成功
				$this->message = $result['retstr'];
				return true;
			}
			else
			{
        switch ($result['status']) {
          case '1':
            $this->message = "手機號碼格式錯誤: {$result['status']}";
            break;
          case '3':
            $this->message = "簡訊內容為空白: {$result['status']}";
            break;
          case '5':
            $this->message = "廠商登入帳號或已加密之登入密碼錯誤: {$result['status']}";
            break;
          case '6':
            $this->message = "無剩餘簡訊通數: {$result['status']}";
            break;
          case '7':
            $this->message = "發送 WapPush但是 WapLink參數 無內容或是此參數: {$result['status']}";
            break;
          case '9':
            $this->message = "無指定編碼: {$result['status']}";
            break;
          case '10':
            $this->message = "無 SourceProdID 或 SourceMsgID: {$result['status']}";
            break;
          case '11':
            $this->message = "IP 位址錯誤 (需通知 Yoyo8 PM 開通 IP): {$result['status']}";
            break;
          case '12':
            $this->message = "現在時間非可發送: {$result['status']}";
            break;
          case '13':
            $this->message = "接收簡訊手機號碼為您於後台所設定的黑名單: {$result['status']}";
            break;
          case '14':
            $this->message = "接收簡訊手機號碼為 Yoyo8 系統所設定的黑名單: {$result['status']}";
            break;
          case '15':
            $this->message = "接收簡訊手機號碼為經銷商所設定的黑名單: {$result['status']}";
            break;
          case '16':
            $this->message = "簡訊有效時間秒數錯誤: {$result['status']}";
            break;
          case '17':
            $this->message = "簡訊字數過長: {$result['status']}";
            break;
          case '18':
            $this->message = "禁止發送國際簡訊: {$result['status']}";
            break;
          default:
            $this->message = "簡訊發送失敗: {$result['status']}";
            break;
          }
          log_message("error", "G_Send_sms  - phone is ".$phone_number." - Status is ".$result['status']." - message is ".$this->message);
				return false;
			}
    }

		function get_message()
		{
			return $this->message;
		}
    }
?>
