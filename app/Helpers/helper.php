<?php 

if(!function_exists('sms')){
    function sms($number,$message){
		$apicode="TR-RENMA302689_LP556";
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
if(!function_exists('generateRandomString')){
    function generateRandomString($length = 10){
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString;
    }
}
if(!function_exists('conver_sms_result')){
	function conver_sms_result($result){
		switch($result):
			case 0:
				return "Notification has been sent";
			break;
			case 1:
				return "Notification is not delivered, Invalid Number";
			break;
			case 2:
				return "Notification is not delivered, Invalid message or missing";
			break;
			case 3:
				return "Notification is not delivered, invalid Api key";
			break;
			default:
			 return "Notification message failed to send";
			break;
		endswitch;
	}
}
if(!function_exists('conver_sms')){
	function conver_sms($result){
		switch($result):
			case 0:
				return "Notification has been sent";
			break;
			case 1:
				return "Notification not delivered, Invalid Number";
			break;
			default:
			 return "SMS failed to send";
			break;
		endswitch;
	}
}
if(!function_exists('docxtostring')){
	function docxtostring($file){
		$filepath = $file;		
		if(isset($filepath) && !file_exists($filepath)) {
            return "File Not exists";
        }
        $fileArray = pathinfo($filepath);
        $file_ext  = $fileArray['extension'];
        if($file_ext == "doc" || $file_ext == "docx")
        {
            if($file_ext == "doc") {
                return read_doc($filepath);
            } elseif($file_ext == "docx") {
                return read_docx($filepath);
            }
        } else {
            return "Invalid File Type";
		}
	}
}
if(!function_exists('read_docx')){
	function read_docx($filepath){
			$striped_content = '';
			$content = '';
	
			$zip = zip_open($filepath);
	
			if (!$zip || is_numeric($zip)) return false;
	
			while ($zip_entry = zip_read($zip)) {
	
				if (zip_entry_open($zip, $zip_entry) == FALSE) continue;
	
				if (zip_entry_name($zip_entry) != "word/document.xml") continue;
	
				$content .= zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));
	
				zip_entry_close($zip_entry);
			}// end while
	
			zip_close($zip);
	
			$content = str_replace('</w:r></w:p></w:tc><w:tc>', " ", $content);
			$content = str_replace('</w:r></w:p>', "\r\n", $content);
			$striped_content = strip_tags($content);
	
			return $striped_content;
	}
}
if(!function_exists('read_doc')){
	function read_doc($filepath){
		$fileHandle = fopen($filepath, "r");
			$line = @fread($fileHandle, filesize($filepath));   
			$lines = explode(chr(0x0D),$line);
			$outtext = "";
			foreach($lines as $thisline)
			  {
				$pos = strpos($thisline, chr(0x00));
				if (($pos !== FALSE)||(strlen($thisline)==0))
				  {
				  } else {
					$outtext .= $thisline."";
				  }
			  }
			 $outtext = preg_replace("/[^a-zA-Z0-9\s\,\.\-\n\r\t@\/\_\(\)]/","",$outtext);
			return $outtext;
	}
}