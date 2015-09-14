<?php
    /**
     * 
     *  yoyo8 簡訊系統發送
     *
     */
    class Send_sms
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
				// 失敗
				$this->message = "簡訊發送失敗: {$result['status']}";
				return false;
			}
        }

		function get_message()
		{
			return $this->message;
		}
    }
?>
