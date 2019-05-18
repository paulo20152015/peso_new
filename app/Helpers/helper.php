<?php 

if(!function_exists('sms')){
    function sms($number,$message){
		$apicode="TR-PAUPA989186_V6VC3";
        $url = 'https://www.itexmo.com/php_api/api.php';
		$itexmo = array('1' => $number, '2' => $message, '3' => $apicode);
		$param = array(
			'http' => array(
				'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
				'method'  => 'POST',
				'content' => http_build_query($itexmo),
			),
		);
		$context  = stream_context_create($param);
		return file_get_contents($url, false, $context);
    }
}
if(!function_exists('orderConvert')){
    function orderConvert($number){
		$value ='ASC';
		if($number == 1){
			$value = 'ASC';
		}elseif($number == 2){
			$value = 'DESC';
		}
        return $value;
    }
}
if(!function_exists('conver_sms_result')){
	function conver_sms_result($result){
		switch($result):
			case 0:
				return "Sms notification has been sent";
			break;
			case 1:
				return "SMS not delivered, Invalid Number";
			break;
			case 2:
				return "SMS not delivered, Invalid message or missing";
			break;
			case 3:
				return "SMS not delivered, invalid Api key";
			break;
			default:
			 return "SMS message failed to send";
			break;
		endswitch;
	}
}